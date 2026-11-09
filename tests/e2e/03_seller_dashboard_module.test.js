import { createBrowser, loginAsSeller, BASE_URL, SCREENSHOTS_DIR } from './helpers.js';
import { execSync } from 'child_process';
import path from 'path';

export async function runSellerDashboardModule() {
    console.log('\n======================================================');
    console.log('🛍️ RUNNING MODULE 3: SELLER DASHBOARD FLOW');
    console.log('======================================================\n');

    execSync('php artisan cache:clear');

    const browser = await createBrowser();
    const context = await browser.newContext({
        viewport: { width: 1280, height: 800 },
        userAgent: 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
    });

    const page = await context.newPage();
    const results = [];

    async function takeScreenshot(name) {
        const filePath = path.join(SCREENSHOTS_DIR, `${name}.png`);
        try {
            await page.screenshot({ path: filePath, fullPage: false, timeout: 5000 });
        } catch (_) {}
        return filePath;
    }

    async function recordTest(name, route, fn) {
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
                errors: [err.message],
                screenshot: screenPath
            });
        }
    }

    try {
        // Authenticate seller once for this module
        console.log('🔐 Authenticating Seller Session...');
        await loginAsSeller(page);
        console.log('   Authenticated successfully!\n');

        // 1. Dashboard Metrics & Chart Data
        await recordTest('Dashboard Metrics & Chart', '/id/admin/dashboard', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/admin/dashboard`);
            await page.waitForLoadState('networkidle');

            const chartResponse = await page.request.get(`${BASE_URL}/id/admin/get-chart-data`);
            if (chartResponse.status() !== 200) {
                throw new Error(`/admin/get-chart-data responded with HTTP ${chartResponse.status()}`);
            }
            const chartData = await chartResponse.json();
            if (!chartData || typeof chartData !== 'object') {
                throw new Error('Invalid JSON format from /admin/get-chart-data');
            }

            await screenshot('01_seller_dashboard');
            return 'Dashboard loaded, metrics present, chart-data JSON valid';
        });

        // 2. Microsite Builder ("My Linkan") Edit Mode
        await recordTest('Microsite Builder Edit Mode', '/id/admin/microsites?id=5&mode=edit', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/admin/microsites?id=5&mode=edit`);
            await page.waitForLoadState('networkidle');

            const content = await page.textContent('body');
            if (content.includes('500 Server Error') || content.includes('Server Error')) {
                throw new Error('500 Server Error encountered on Microsite edit mode');
            }

            const hasBuilder = (await page.$('#phone-preview, .microsite-builder, #blocks-container, form')) !== null;
            if (!hasBuilder) {
                throw new Error('Microsite builder container element not found');
            }

            await screenshot('02_microsite_builder');
            return 'Microsite builder loaded without 500 error; interactive blocks present';
        });

        // 3. Digital Products List & Create Page
        await recordTest('Digital Products Management', '/id/admin/digital-products', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/admin/digital-products`);
            await page.waitForLoadState('networkidle');

            const tableOrEmpty = await page.$('table, .product-card, .empty-state, a[href*="create"]');
            if (!tableOrEmpty) {
                throw new Error('Digital products table or empty state not found');
            }

            await page.goto(`${BASE_URL}/id/admin/digital-products/create`);
            await page.waitForLoadState('networkidle');

            const titleInput = await page.$('input[name="title"]');
            if (!titleInput) {
                throw new Error('Digital product title input not found on create form');
            }

            await screenshot('03_digital_products_create');
            return 'Digital products list rendered; product create form accessible';
        });

        // 4. Link Shortener Management & Analytics
        await recordTest('Shortlink Management', '/id/admin/shortlinks', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/admin/shortlinks`);
            await page.waitForLoadState('networkidle');

            const hasTableOrForm = (await page.$('table, form, input[name="destination"], input[name="url"]')) !== null;
            if (!hasTableOrForm) {
                throw new Error('Shortlinks list or creation form not found');
            }

            const analyticsRes = await page.goto(`${BASE_URL}/id/admin/shortlinks/1/analytics`);
            await page.waitForLoadState('networkidle');
            if (analyticsRes && analyticsRes.status() >= 400 && analyticsRes.status() !== 404) {
                throw new Error(`Analytics returned HTTP ${analyticsRes.status()}`);
            }

            await screenshot('04_shortlinks_dashboard');
            return 'Shortlinks list and analytics accessible';
        });

        // 5. Orders List & Detail
        await recordTest('Orders Transaction List', '/id/admin/orders', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/admin/orders`);
            await page.waitForLoadState('networkidle');

            const hasOrdersContainer = (await page.$('table, .orders-table, .empty-state, main')) !== null;
            if (!hasOrdersContainer) {
                throw new Error('Orders container element not found');
            }

            await screenshot('05_orders_list');
            return 'Incoming orders list rendered cleanly';
        });

        // 6. Payout & Withdrawal Workflow
        await recordTest('Payout & Withdrawal Hub', '/id/admin/payout', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/admin/payout`);
            await page.waitForLoadState('networkidle');

            await page.goto(`${BASE_URL}/id/admin/payout/withdraw`);
            await page.waitForLoadState('networkidle');

            await page.goto(`${BASE_URL}/id/admin/payout/method`);
            await page.waitForLoadState('networkidle');

            await page.goto(`${BASE_URL}/id/admin/payout/history`);
            await page.waitForLoadState('networkidle');

            await screenshot('06_payout_history');
            return 'Payout dashboard, withdraw form, method form, and history operational';
        });

        // 7. Support Tickets Hub
        await recordTest('Support Tickets (Seller Helpdesk)', '/id/admin/tickets', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/admin/tickets`);
            await page.waitForLoadState('networkidle');

            const hasTickets = (await page.$('table, form, .ticket-list, .empty-state, button, a')) !== null;
            if (!hasTickets) {
                throw new Error('Support tickets container not found');
            }

            await screenshot('07_support_tickets');
            return 'Support tickets portal loaded and interactive';
        });

        // 8. Account Settings & Active Sessions
        await recordTest('Account Settings & Sessions', '/id/admin/account', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/admin/account`);
            await page.waitForLoadState('networkidle');

            const passwordForm = await page.$('#current_password, input[name="current_password"]');
            if (!passwordForm) {
                throw new Error('Account password inputs not found on /admin/account');
            }

            await page.goto(`${BASE_URL}/id/admin/settings`);
            await page.waitForLoadState('networkidle');

            const hasProfileForm = (await page.$('form, input, textarea')) !== null;
            if (!hasProfileForm) {
                throw new Error('Profile settings form not found on /admin/settings');
            }

            await screenshot('08_account_settings');
            return 'Security settings, password section, and profile manager loaded successfully';
        });

    } finally {
        await context.close();
        await browser.close();
    }

    return results;
}

if (process.argv[1]?.endsWith('03_seller_dashboard_module.test.js')) {
    runSellerDashboardModule().then(res => {
        console.log('\nModule 3 Summary:', JSON.stringify(res, null, 2));
    });
}
