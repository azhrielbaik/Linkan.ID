import { chromium } from 'playwright';

async function runPerformanceAudit() {
    console.log('🚀 Starting WebApp Performance Audit for Linkan.ID...\n');

    const browser = await chromium.launch({
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--disable-dev-shm-usage']
    });

    const context = await browser.newContext({
        viewport: { width: 1280, height: 720 },
    });

    const results = {};

    // 1. Audit Public Login Page
    console.log('📍 Auditing Public Page: /id/login');
    const loginPage = await context.newPage();
    const loginStart = Date.now();
    
    let totalBytes = 0;
    let requestCount = 0;
    loginPage.on('response', async (response) => {
        requestCount++;
        try {
            const buffer = await response.body();
            totalBytes += buffer.length;
        } catch (_) {}
    });

    await loginPage.goto('http://linkan.gov/id/login', { waitUntil: 'networkidle' });
    const loginDuration = Date.now() - loginStart;

    const loginMetrics = await loginPage.evaluate(() => {
        const nav = performance.getEntriesByType('navigation')[0];
        const paints = performance.getEntriesByType('paint');
        const fcp = paints.find(p => p.name === 'first-contentful-paint')?.startTime || 0;
        const fp = paints.find(p => p.name === 'first-paint')?.startTime || 0;

        return {
            ttfb: Math.round(nav ? nav.responseStart - nav.requestStart : 0),
            domContentLoaded: Math.round(nav ? nav.domContentLoadedEventEnd - nav.startTime : 0),
            loadComplete: Math.round(nav ? nav.loadEventEnd - nav.startTime : 0),
            fp: Math.round(fp),
            fcp: Math.round(fcp)
        };
    });

    results.login = {
        totalDuration: loginDuration,
        requestCount,
        totalBytes,
        ...loginMetrics
    };
    console.log(`   ✔ TTFB: ${loginMetrics.ttfb}ms | FCP: ${loginMetrics.fcp}ms | DCL: ${loginMetrics.domContentLoaded}ms | Requests: ${requestCount} | Transferred: ${Math.round(totalBytes/1024)} KB\n`);

    // 2. Perform Login and Audit Seller Dashboard
    console.log('📍 Authenticating and Auditing Dashboard: /id/admin/dashboard');
    await loginPage.fill('#email', 'seller@example.com');
    await loginPage.fill('#password', '09112024');

    let dashBytes = 0;
    let dashRequests = 0;
    let consoleErrors = [];

    const [dashResponse] = await Promise.all([
        loginPage.waitForNavigation({ waitUntil: 'networkidle' }),
        loginPage.click('button[type="submit"]')
    ]);

    loginPage.on('console', msg => {
        if (msg.type() === 'error') {
            consoleErrors.push(msg.text());
        }
    });

    const dashStart = Date.now();
    await loginPage.reload({ waitUntil: 'networkidle' });
    const dashDuration = Date.now() - dashStart;

    const dashMetrics = await loginPage.evaluate(() => {
        const nav = performance.getEntriesByType('navigation')[0];
        const paints = performance.getEntriesByType('paint');
        const fcp = paints.find(p => p.name === 'first-contentful-paint')?.startTime || 0;
        const fp = paints.find(p => p.name === 'first-paint')?.startTime || 0;

        return {
            ttfb: Math.round(nav ? nav.responseStart - nav.requestStart : 0),
            domContentLoaded: Math.round(nav ? nav.domContentLoadedEventEnd - nav.startTime : 0),
            loadComplete: Math.round(nav ? nav.loadEventEnd - nav.startTime : 0),
            fp: Math.round(fp),
            fcp: Math.round(fcp)
        };
    });

    results.dashboard = {
        totalDuration: dashDuration,
        consoleErrors: consoleErrors.length,
        ...dashMetrics
    };
    console.log(`   ✔ TTFB: ${dashMetrics.ttfb}ms | FCP: ${dashMetrics.fcp}ms | DCL: ${dashMetrics.domContentLoaded}ms | Load: ${dashMetrics.loadComplete}ms | Console Errors: ${consoleErrors.length}\n`);

    // 3. Measure Endpoint Latency directly from the page context
    console.log('📍 Auditing Internal Dynamic Endpoints:');
    const chartLatency = await loginPage.evaluate(async () => {
        const t0 = performance.now();
        const res = await fetch('/id/admin/get-chart-data', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        return {
            duration: Math.round(performance.now() - t0),
            pointsCount: data.labels ? data.labels.length : 0
        };
    });
    console.log(`   ✔ /id/admin/get-chart-data latency: ${chartLatency.duration}ms (${chartLatency.pointsCount} data points)`);

    const notifLatency = await loginPage.evaluate(async () => {
        const t0 = performance.now();
        const res = await fetch('/id/admin/notifications', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        return {
            duration: Math.round(performance.now() - t0),
            unreadCount: data.unread_count ?? 0
        };
    });
    console.log(`   ✔ /id/admin/notifications latency: ${notifLatency.duration}ms (unread: ${notifLatency.unreadCount})\n`);

    results.endpoints = {
        chartLatency: chartLatency.duration,
        notifLatency: notifLatency.duration
    };

    await browser.close();

    console.log('📊 Performance Summary Results:');
    console.table({
        'Login Page': {
            'TTFB (ms)': results.login.ttfb,
            'FCP (ms)': results.login.fcp,
            'DOM Ready (ms)': results.login.domContentLoaded,
            'Load Time (ms)': results.login.loadComplete,
            'Requests': results.login.requestCount,
            'Transfer (KB)': Math.round(results.login.totalBytes / 1024)
        },
        'Seller Dashboard': {
            'TTFB (ms)': results.dashboard.ttfb,
            'FCP (ms)': results.dashboard.fcp,
            'DOM Ready (ms)': results.dashboard.domContentLoaded,
            'Load Time (ms)': results.dashboard.loadComplete,
            'Requests': dashRequests,
            'Transfer (KB)': '-'
        },
        'API: Chart Data': {
            'TTFB (ms)': '-',
            'FCP (ms)': '-',
            'DOM Ready (ms)': '-',
            'Load Time (ms)': results.endpoints.chartLatency,
            'Requests': 1,
            'Transfer (KB)': '-'
        },
        'API: Notifications': {
            'TTFB (ms)': '-',
            'FCP (ms)': '-',
            'DOM Ready (ms)': '-',
            'Load Time (ms)': results.endpoints.notifLatency,
            'Requests': 1,
            'Transfer (KB)': '-'
        }
    });

    return results;
}

runPerformanceAudit().catch(err => {
    console.error('Performance Audit failed:', err);
    process.exit(1);
});

