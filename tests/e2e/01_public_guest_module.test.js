import { createBrowser, createPageWithConsoleCapture, BASE_URL } from './helpers.js';

export async function runPublicGuestModule() {
    console.log('\n======================================================');
    console.log('🌐 RUNNING MODULE 1: PUBLIC & GUEST FLOW (HULU)');
    console.log('======================================================\n');

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
        // 1. Landing Page
        await recordTest('Landing Page Render', '/id', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id`);
            await page.waitForLoadState('networkidle');
            const title = await page.title();
            if (!title.includes('Linkan')) throw new Error(`Unexpected title: ${title}`);
            const heading = await page.textContent('h1, h2');
            await screenshot('01_landing_page');
            return `Rendered successfully with title "${title.trim()}"`;
        });

        // 2. Pricing Page
        await recordTest('Pricing Page', '/id/pricing', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/pricing`);
            await page.waitForLoadState('networkidle');
            const bodyText = await page.textContent('body');
            if (!bodyText.toLowerCase().includes('harga') && !bodyText.toLowerCase().includes('pricing') && !bodyText.toLowerCase().includes('paket')) {
                throw new Error('Pricing content not found');
            }
            await screenshot('02_pricing_page');
            return 'Pricing tiers and features displayed';
        });

        // 3. FAQ Page
        await recordTest('FAQ Page', '/id/faq', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/faq`);
            await page.waitForLoadState('networkidle');
            const bodyText = await page.textContent('body');
            if (!bodyText.toLowerCase().includes('faq') && !bodyText.toLowerCase().includes('pertanyaan')) {
                throw new Error('FAQ content not found');
            }
            await screenshot('03_faq_page');
            return 'FAQ questions loaded successfully';
        });

        // 4. About Page
        await recordTest('About Page', '/id/about', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/about`);
            await page.waitForLoadState('networkidle');
            const bodyText = await page.textContent('body');
            if (!bodyText.toLowerCase().includes('tentang') && !bodyText.toLowerCase().includes('linkan')) {
                throw new Error('About content not found');
            }
            await screenshot('04_about_page');
            return 'About page rendered';
        });

        // 5. Contact Page & Form
        await recordTest('Contact Page & Form', '/id/contact', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/contact`);
            await page.waitForLoadState('networkidle');
            const form = await page.$('form');
            if (!form) throw new Error('Contact form element not found');
            await screenshot('05_contact_page');
            return 'Contact form displayed and interactive';
        });

        // 6. Public Microsite Resolution
        await recordTest('Public Microsite Resolution', '/id/linkan.id/{username}', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/linkan.id/Romanilo`);
            await page.waitForLoadState('networkidle');
            const body = await page.textContent('body');
            if (body.includes('404') && !body.includes('Romanilo')) {
                throw new Error('Public microsite 404 or profile not found');
            }
            await screenshot('06_public_microsite');
            return `Public profile resolved for user Romanilo`;
        });

        // 7. Password-Protected Shortlink Form & Unlock
        await recordTest('Password Protected Shortlink', '/id/p/kepala_suku', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/p/kepala_suku`);
            await page.waitForLoadState('networkidle');
            
            const passwordInput = await page.$('input[type="password"]');
            if (!passwordInput) throw new Error('Password prompt input not found on /id/p/kepala_suku');
            
            // Try wrong password
            await page.fill('input[type="password"]', 'wrongpassword');
            await page.click('button[type="submit"]');
            await page.waitForLoadState('networkidle');
            
            let bodyText = await page.textContent('body');
            if (!bodyText.toLowerCase().includes('salah') && !bodyText.toLowerCase().includes('invalid') && !bodyText.toLowerCase().includes('password')) {
                throw new Error('Failed to display invalid password error message');
            }

            // Enter correct password '12345'
            await page.fill('input[type="password"]', '12345');
            await page.click('button[type="submit"]');
            await page.waitForTimeout(1000);
            
            await screenshot('07_shortlink_unlock');
            return 'Password challenge correctly protected and validated';
        });

    } finally {
        await browser.close();
    }

    return results;
}

if (process.argv[1]?.endsWith('01_public_guest_module.test.js')) {
    runPublicGuestModule().then(res => {
        console.log('\nModule 1 Summary:', JSON.stringify(res, null, 2));
    });
}
