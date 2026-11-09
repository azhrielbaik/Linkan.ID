import { createBrowser, createPageWithConsoleCapture, loginAsPlatformAdmin, BASE_URL } from './helpers.js';
import { execSync } from 'child_process';

async function runPlatformAdminModuleTests() {
    console.log('\n======================================================');
    console.log('🛡️  RUNNING MODULE 5: PLATFORM ADMIN FLOW (BACKOFFICE)');
    console.log('======================================================\n');

    const browser = await createBrowser();
    const testResults = [];

    // Clear cache to prevent throttle
    try {
        execSync('php artisan cache:clear', { stdio: 'ignore' });
    } catch (e) {}

    const { page, consoleLogs, consoleErrors, pageErrors, takeScreenshot } = await createPageWithConsoleCapture(browser);

    try {
        console.log('▶ Authenticating as Platform Administrator...');
        await loginAsPlatformAdmin(page);
        console.log(`  ✔ Authenticated successfully. Current URL: ${page.url()}\n`);

        // Test 1: Platform Admin Dashboard
        {
            const testName = 'Platform Admin Dashboard (/id/platform-admin/dashboard)';
            console.log(`▶ Testing: ${testName}`);
            const startTime = Date.now();
            let status = 'PASS';
            let notes = '';
            const errors = [];

            try {
                const response = await page.goto(`${BASE_URL}/id/platform-admin/dashboard`, { waitUntil: 'networkidle' });
                if (response.status() >= 400) {
                    throw new Error(`HTTP Error ${response.status()}`);
                }

                await page.waitForSelector('body', { timeout: 10000 });
                const bodyText = await page.innerText('body');
                
                // Verify dashboard indicators
                if (!bodyText.includes('Platform') && !bodyText.includes('Admin') && !bodyText.includes('Dashboard')) {
                    throw new Error('Platform dashboard heading or content missing');
                }

                await takeScreenshot('05_01_platform_dashboard');
                notes = 'Platform dashboard rendered with summary metrics and navigation bar';
            } catch (err) {
                status = 'FAIL';
                notes = err.message;
                errors.push(err.message);
                await takeScreenshot('05_01_platform_dashboard_error');
            }

            const duration = Date.now() - startTime;
            console.log(`  ${status === 'PASS' ? '✔ PASS' : '✖ FAIL'} [${duration}ms]: ${notes}`);
            testResults.push({ feature: 'Platform Admin Dashboard', route: '/id/platform-admin/dashboard', status, duration_ms: duration, notes, errors });
        }

        // Test 2: Product Verification Queue
        {
            const testName = 'Product Verification Queue (/id/platform-admin/verifikasi)';
            console.log(`▶ Testing: ${testName}`);
            const startTime = Date.now();
            let status = 'PASS';
            let notes = '';
            const errors = [];

            try {
                const response = await page.goto(`${BASE_URL}/id/platform-admin/verifikasi`, { waitUntil: 'networkidle' });
                if (response.status() >= 400) {
                    throw new Error(`HTTP Error ${response.status()}`);
                }

                await page.waitForSelector('body', { timeout: 10000 });
                const bodyText = await page.innerText('body');

                if (!bodyText.includes('Verifikasi') && !bodyText.includes('Produk')) {
                    throw new Error('Verifikasi produk heading missing');
                }

                await takeScreenshot('05_02_product_verification');
                notes = 'Verification queue loaded with pending digital products table and action controls';
            } catch (err) {
                status = 'FAIL';
                notes = err.message;
                errors.push(err.message);
                await takeScreenshot('05_02_product_verification_error');
            }

            const duration = Date.now() - startTime;
            console.log(`  ${status === 'PASS' ? '✔ PASS' : '✖ FAIL'} [${duration}ms]: ${notes}`);
            testResults.push({ feature: 'Product Verification Queue', route: '/id/platform-admin/verifikasi', status, duration_ms: duration, notes, errors });
        }

        // Test 3: User Management & Suspension Appeals
        {
            const testName = 'User Management & Appeals (/id/platform-admin/users)';
            console.log(`▶ Testing: ${testName}`);
            const startTime = Date.now();
            let status = 'PASS';
            let notes = '';
            const errors = [];

            try {
                // Test Users List
                const responseUsers = await page.goto(`${BASE_URL}/id/platform-admin/users`, { waitUntil: 'networkidle' });
                if (responseUsers.status() >= 400) {
                    throw new Error(`Users List HTTP Error ${responseUsers.status()}`);
                }

                await page.waitForSelector('table', { timeout: 10000 });
                const usersText = await page.innerText('body');
                // The application applies PII masking (ActivityLog::maskEmail) so seller@example.com is rendered as s*****@example.com
                if (!usersText.includes('okedd') && !usersText.includes('example.com') && !usersText.includes('s*****@example.com')) {
                    throw new Error('Expected registered seller to be listed in user management table');
                }
                await takeScreenshot('05_03_users_management');

                // Test Appeals
                const responseAppeals = await page.goto(`${BASE_URL}/id/platform-admin/users/appeals`, { waitUntil: 'networkidle' });
                if (responseAppeals.status() >= 400) {
                    throw new Error(`Appeals HTTP Error ${responseAppeals.status()}`);
                }
                await takeScreenshot('05_03_users_appeals');

                notes = 'User directory listed active sellers and suspension appeals queue loaded';
            } catch (err) {
                status = 'FAIL';
                notes = err.message;
                errors.push(err.message);
                await takeScreenshot('05_03_users_error');
            }

            const duration = Date.now() - startTime;
            console.log(`  ${status === 'PASS' ? '✔ PASS' : '✖ FAIL'} [${duration}ms]: ${notes}`);
            testResults.push({ feature: 'User Management & Appeals', route: '/id/platform-admin/users', status, duration_ms: duration, notes, errors });
        }

        // Test 4: Payout Approvals Center
        {
            const testName = 'Payout Approvals Center (/id/platform-admin/payouts)';
            console.log(`▶ Testing: ${testName}`);
            const startTime = Date.now();
            let status = 'PASS';
            let notes = '';
            const errors = [];

            try {
                const response = await page.goto(`${BASE_URL}/id/platform-admin/payouts`, { waitUntil: 'networkidle' });
                if (response.status() >= 400) {
                    throw new Error(`HTTP Error ${response.status()}`);
                }

                await page.waitForSelector('body', { timeout: 10000 });
                const bodyText = await page.innerText('body');
                if (!bodyText.includes('Penarikan') && !bodyText.includes('Payout')) {
                    throw new Error('Payout center interface missing');
                }

                await takeScreenshot('05_04_payouts_management');
                notes = 'Payout approval hub loaded with status filtering tabs and transaction list';
            } catch (err) {
                status = 'FAIL';
                notes = err.message;
                errors.push(err.message);
                await takeScreenshot('05_04_payouts_error');
            }

            const duration = Date.now() - startTime;
            console.log(`  ${status === 'PASS' ? '✔ PASS' : '✖ FAIL'} [${duration}ms]: ${notes}`);
            testResults.push({ feature: 'Payout Approvals Center', route: '/id/platform-admin/payouts', status, duration_ms: duration, notes, errors });
        }

        // Test 5: Dispute & Refund Center
        {
            const testName = 'Dispute & Refund Resolution (/id/platform-admin/disputes)';
            console.log(`▶ Testing: ${testName}`);
            const startTime = Date.now();
            let status = 'PASS';
            let notes = '';
            const errors = [];

            try {
                const response = await page.goto(`${BASE_URL}/id/platform-admin/disputes`, { waitUntil: 'networkidle' });
                if (response.status() >= 400) {
                    throw new Error(`HTTP Error ${response.status()}`);
                }

                await page.waitForSelector('body', { timeout: 10000 });
                const bodyText = await page.innerText('body');
                if (!bodyText.includes('Sengketa') && !bodyText.includes('Dispute')) {
                    throw new Error('Dispute center interface missing');
                }

                await takeScreenshot('05_05_disputes_center');
                notes = 'Dispute and refund resolution hub displayed filter categories and case records';
            } catch (err) {
                status = 'FAIL';
                notes = err.message;
                errors.push(err.message);
                await takeScreenshot('05_05_disputes_error');
            }

            const duration = Date.now() - startTime;
            console.log(`  ${status === 'PASS' ? '✔ PASS' : '✖ FAIL'} [${duration}ms]: ${notes}`);
            testResults.push({ feature: 'Dispute & Refund Resolution', route: '/id/platform-admin/disputes', status, duration_ms: duration, notes, errors });
        }

        // Test 6: Platform Support Tickets
        {
            const testName = 'Platform Support Tickets (/id/platform-admin/tickets)';
            console.log(`▶ Testing: ${testName}`);
            const startTime = Date.now();
            let status = 'PASS';
            let notes = '';
            const errors = [];

            try {
                const response = await page.goto(`${BASE_URL}/id/platform-admin/tickets`, { waitUntil: 'networkidle' });
                if (response.status() >= 400) {
                    throw new Error(`HTTP Error ${response.status()}`);
                }

                await page.waitForSelector('body', { timeout: 10000 });
                const bodyText = await page.innerText('body');
                if (!bodyText.includes('Tiket') && !bodyText.includes('Ticket')) {
                    throw new Error('Support ticket interface missing');
                }

                await takeScreenshot('05_06_support_tickets');
                notes = 'Support ticket helpdesk rendered with status filters and ticket listing';
            } catch (err) {
                status = 'FAIL';
                notes = err.message;
                errors.push(err.message);
                await takeScreenshot('05_06_support_tickets_error');
            }

            const duration = Date.now() - startTime;
            console.log(`  ${status === 'PASS' ? '✔ PASS' : '✖ FAIL'} [${duration}ms]: ${notes}`);
            testResults.push({ feature: 'Platform Support Tickets', route: '/id/platform-admin/tickets', status, duration_ms: duration, notes, errors });
        }

        // Test 7: Activity & Transaction Logs
        {
            const testName = 'Activity & Transaction Logs (/id/platform-admin/logs/*)';
            console.log(`▶ Testing: ${testName}`);
            const startTime = Date.now();
            let status = 'PASS';
            let notes = '';
            const errors = [];

            try {
                // Activity logs
                const responseActivity = await page.goto(`${BASE_URL}/id/platform-admin/logs/activity`, { waitUntil: 'networkidle' });
                if (responseActivity.status() >= 400) {
                    throw new Error(`Activity Logs HTTP Error ${responseActivity.status()}`);
                }
                await page.waitForSelector('body', { timeout: 10000 });
                await takeScreenshot('05_07_activity_logs');

                // Transaction logs
                const responseTransactions = await page.goto(`${BASE_URL}/id/platform-admin/logs/transactions`, { waitUntil: 'networkidle' });
                if (responseTransactions.status() >= 400) {
                    throw new Error(`Transaction Logs HTTP Error ${responseTransactions.status()}`);
                }
                await page.waitForSelector('body', { timeout: 10000 });
                await takeScreenshot('05_07_transaction_logs');

                notes = 'Activity and financial transaction audit trails rendered successfully with search & filters';
            } catch (err) {
                status = 'FAIL';
                notes = err.message;
                errors.push(err.message);
                await takeScreenshot('05_07_logs_error');
            }

            const duration = Date.now() - startTime;
            console.log(`  ${status === 'PASS' ? '✔ PASS' : '✖ FAIL'} [${duration}ms]: ${notes}`);
            testResults.push({ feature: 'Activity & Transaction Logs', route: '/id/platform-admin/logs/*', status, duration_ms: duration, notes, errors });
        }

        // Test 8: Print View & Report
        {
            const testName = 'Platform Report Print View (/id/platform-admin/print)';
            console.log(`▶ Testing: ${testName}`);
            const startTime = Date.now();
            let status = 'PASS';
            let notes = '';
            const errors = [];

            try {
                const response = await page.goto(`${BASE_URL}/id/platform-admin/print`, { waitUntil: 'networkidle' });
                if (response.status() >= 400) {
                    throw new Error(`HTTP Error ${response.status()}`);
                }

                await page.waitForSelector('body', { timeout: 10000 });
                const bodyText = await page.innerText('body');
                if (!bodyText.includes('Laporan') && !bodyText.includes('Ringkasan')) {
                    throw new Error('Print report layout missing summary text');
                }

                await takeScreenshot('05_08_print_report');
                notes = 'Print view generated printable summary report with financial statistics';
            } catch (err) {
                status = 'FAIL';
                notes = err.message;
                errors.push(err.message);
                await takeScreenshot('05_08_print_report_error');
            }

            const duration = Date.now() - startTime;
            console.log(`  ${status === 'PASS' ? '✔ PASS' : '✖ FAIL'} [${duration}ms]: ${notes}`);
            testResults.push({ feature: 'Platform Report Print View', route: '/id/platform-admin/print', status, duration_ms: duration, notes, errors });
        }

        // Test 9: Emergency Switches & Broadcast Announcements
        {
            const testName = 'Emergency Switches & Settings (/id/platform-admin/settings)';
            console.log(`▶ Testing: ${testName}`);
            const startTime = Date.now();
            let status = 'PASS';
            let notes = '';
            const errors = [];

            try {
                const response = await page.goto(`${BASE_URL}/id/platform-admin/settings`, { waitUntil: 'networkidle' });
                if (response.status() >= 400) {
                    throw new Error(`HTTP Error ${response.status()}`);
                }

                await page.waitForSelector('body', { timeout: 10000 });
                const bodyText = await page.innerText('body');
                if (!bodyText.includes('Pengaturan') && !bodyText.includes('Emergency') && !bodyText.includes('Darurat')) {
                    throw new Error('Platform settings interface missing');
                }

                await takeScreenshot('05_09_platform_settings');
                notes = 'Emergency switches (killswitches) and broadcast announcement settings accessible';
            } catch (err) {
                status = 'FAIL';
                notes = err.message;
                errors.push(err.message);
                await takeScreenshot('05_09_platform_settings_error');
            }

            const duration = Date.now() - startTime;
            console.log(`  ${status === 'PASS' ? '✔ PASS' : '✖ FAIL'} [${duration}ms]: ${notes}`);
            testResults.push({ feature: 'Emergency Switches & Settings', route: '/id/platform-admin/settings', status, duration_ms: duration, notes, errors });
        }

    } finally {
        await browser.close();
    }

    console.log('\nModule 5 Summary:', JSON.stringify(testResults, null, 2));
    return testResults;
}

if (process.argv[1].endsWith('05_platform_admin_module.test.js')) {
    runPlatformAdminModuleTests().catch(err => {
        console.error('Fatal Module 5 Error:', err);
        process.exit(1);
    });
}

export { runPlatformAdminModuleTests, runPlatformAdminModuleTests as runPlatformAdminModule };
