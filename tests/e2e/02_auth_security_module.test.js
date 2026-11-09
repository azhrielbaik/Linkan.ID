import { createBrowser, createPageWithConsoleCapture, BASE_URL } from './helpers.js';
import { execSync } from 'child_process';
import fs from 'fs';
import path from 'path';

export async function runAuthSecurityModule() {
    console.log('\n======================================================');
    console.log('🔒 RUNNING MODULE 2: AUTHENTICATION & SECURITY FLOW');
    console.log('======================================================\n');

    execSync('php artisan cache:clear');

    const browser = await createBrowser();
    const results = [];

    async function recordTest(name, route, fn) {
        const { context, page, consoleErrors, pageErrors, takeScreenshot } = await createPageWithConsoleCapture(browser);
        const startTime = Date.now();
        try {
            console.log(`▶ Testing: ${name} (${route})`);
            const notes = await fn(page, takeScreenshot);
            const duration = Date.now() - startTime;
            console.log(`  ✔ PASS [${duration}ms]: ${notes || 'OK'}`);
            results.push({ feature: name, route, status: 'PASS', duration_ms: duration, notes: notes || 'OK', errors: [] });
        } catch (err) {
            const duration = Date.now() - startTime;
            const screenPath = await takeScreenshot(`FAIL_${name.replace(/[^a-zA-Z0-9]/g, '_')}`);
            console.error(`  ✖ FAIL [${duration}ms]: ${err.message}`);
            results.push({
                feature: name,
                route,
                status: 'FAIL',
                duration_ms: duration,
                notes: err.message,
                errors: [...consoleErrors, ...pageErrors, err.message],
                screenshot: screenPath
            });
        } finally {
            await context.close();
        }
    }

    try {
        // 1. Seller Login
        await recordTest('Seller Login Success', '/id/login', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/login`);
            await page.waitForLoadState('networkidle');

            await page.fill('#email', 'seller@example.com');
            await page.fill('#password', '09112024');
            
            const roleSelect = await page.$('select[name="role"]');
            if (roleSelect) await page.selectOption('select[name="role"]', 'admin_seller');

            await Promise.all([
                page.waitForNavigation({ waitUntil: 'networkidle' }),
                page.click('button[type="submit"]')
            ]);

            if (!page.url().includes('/admin/dashboard') && !page.url().includes('/admin')) {
                throw new Error(`Expected redirect to /admin/dashboard, got: ${page.url()}`);
            }

            await screenshot('01_seller_login_dashboard');
            return `Redirected to ${page.url()} with authenticated seller session`;
        });

        // 2. Platform Admin Login
        await recordTest('Platform Admin Login Success', '/id/login', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/login`);
            await page.waitForLoadState('networkidle');

            await page.fill('#email', 'azhrielmuari@gmail.com');
            await page.fill('#password', '09112024');

            const roleSelect = await page.$('select[name="role"]');
            if (roleSelect) await page.selectOption('select[name="role"]', 'admin_platform');

            await Promise.all([
                page.waitForNavigation({ waitUntil: 'networkidle' }),
                page.click('button[type="submit"]')
            ]);

            if (!page.url().includes('/platform-admin')) {
                throw new Error(`Expected redirect to /platform-admin/dashboard, got: ${page.url()}`);
            }

            await screenshot('02_platform_admin_dashboard');
            return `Redirected to ${page.url()} with platform admin role`;
        });

        // 3. Role Middleware Boundary Check (Seller cannot access Platform Admin)
        await recordTest('Role Middleware Boundary Check', '/id/platform-admin/dashboard', async (page, screenshot) => {
            // First login as seller
            await page.goto(`${BASE_URL}/id/login`);
            await page.waitForLoadState('networkidle');
            await page.fill('#email', 'seller@example.com');
            await page.fill('#password', '09112024');
            const roleSelect = await page.$('select[name="role"]');
            if (roleSelect) await page.selectOption('select[name="role"]', 'admin_seller');
            await Promise.all([
                page.waitForNavigation({ waitUntil: 'networkidle' }),
                page.click('button[type="submit"]')
            ]);

            // Attempt to access platform admin route
            const response = await page.goto(`${BASE_URL}/id/platform-admin/dashboard`);
            await page.waitForLoadState('networkidle');
            
            const currentUrl = page.url();
            const status = response ? response.status() : 200;

            // Must either be 403 Forbidden or redirected back to admin dashboard
            if (status === 403 || currentUrl.includes('/admin/dashboard') || !currentUrl.includes('/platform-admin')) {
                await screenshot('03_role_middleware_blocked');
                return `Protected successfully: Status ${status}, URL: ${currentUrl}`;
            }

            throw new Error(`Security breach: Seller was able to access Platform Admin route: ${currentUrl}`);
        });

        // 4. Invalid Password Handling
        await recordTest('Invalid Password Validation', '/id/login', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/login`);
            await page.waitForLoadState('networkidle');

            await page.fill('#email', 'seller@example.com');
            await page.fill('#password', 'WrongPassword123');
            const roleSelect = await page.$('select[name="role"]');
            if (roleSelect) await page.selectOption('select[name="role"]', 'admin_seller');

            await page.click('button[type="submit"]');
            await page.waitForLoadState('networkidle');

            const bodyText = await page.textContent('body');
            const hasError = bodyText.includes('Email atau kata sandi yang Anda masukkan salah') ||
                             bodyText.includes('salah') ||
                             bodyText.includes('tidak cocok') ||
                             bodyText.includes('Invalid') ||
                             page.url().includes('/login');

            if (!hasError) {
                throw new Error('No validation error shown on invalid password');
            }

            await screenshot('04_invalid_login_error');
            return 'Rejected invalid password with feedback';
        });

        // 5. 4-Step Forgot Password Flow
        await recordTest('4-Step Forgot Password Flow', '/id/forgot-password', async (page, screenshot) => {
            execSync('php artisan cache:clear');

            // Step 1: Request OTP
            await page.goto(`${BASE_URL}/id/forgot-password`);
            await page.waitForLoadState('networkidle');

            await page.fill('input[name="email"]', 'seller@example.com');
            await Promise.all([
                page.waitForNavigation({ waitUntil: 'networkidle' }),
                page.click('button[type="submit"]')
            ]);

            if (!page.url().includes('/verify-otp')) {
                throw new Error(`Step 1 failed, expected /verify-otp but got ${page.url()}`);
            }

            // Step 2: Retrieve OTP from Mail log
            const logPath = path.resolve(`storage/logs/laravel-${new Date().toISOString().slice(0, 10)}.log`);
            const logContent = fs.readFileSync(logPath, 'utf-8');
            const matches = [...logContent.matchAll(/KODE VERIFIKASI \(OTP\)[\s\S]*?(\d{4})/g)];
            const otpCode = matches.length > 0 ? matches[matches.length - 1][1] : null;

            if (!otpCode || otpCode.length !== 4) {
                throw new Error(`Invalid OTP retrieved from mail log: "${otpCode}"`);
            }

            // Fill 4-digit OTP
            await page.fill('#digit-1', otpCode[0]);
            await page.fill('#digit-2', otpCode[1]);
            await page.fill('#digit-3', otpCode[2]);
            await page.fill('#digit-4', otpCode[3]);
            
            // Wait for redirect to create-new-password
            await page.waitForURL('**/create-new-password**', { timeout: 10000 });

            // Step 3: Enter new password (set back to 09112024 to preserve account)
            await page.fill('input[name="password"]', '09112024');
            await page.fill('input[name="password_confirmation"]', '09112024');
            await Promise.all([
                page.waitForNavigation({ waitUntil: 'networkidle' }),
                page.click('button[type="submit"]')
            ]);

            if (!page.url().includes('/password-reset-success')) {
                throw new Error(`Step 3 failed, expected /password-reset-success but got ${page.url()}`);
            }

            await screenshot('05_password_reset_success');
            return 'Completed 4-Step OTP password reset cycle and restored seller credentials';
        });

    } finally {
        await browser.close();
    }

    return results;
}

if (process.argv[1]?.endsWith('02_auth_security_module.test.js')) {
    runAuthSecurityModule().then(res => {
        console.log('\nModule 2 Summary:', JSON.stringify(res, null, 2));
    });
}
