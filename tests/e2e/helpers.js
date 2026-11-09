import { chromium } from 'playwright';
import fs from 'fs';
import path from 'path';

export const BASE_URL = 'http://linkan.gov';
export const SCREENSHOTS_DIR = path.resolve('tests/e2e/screenshots');

if (!fs.existsSync(SCREENSHOTS_DIR)) {
    fs.mkdirSync(SCREENSHOTS_DIR, { recursive: true });
}

export async function createBrowser() {
    return await chromium.launch({
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--disable-dev-shm-usage']
    });
}

export async function createPageWithConsoleCapture(browser) {
    const context = await browser.newContext({
        viewport: { width: 1280, height: 800 },
        userAgent: 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
    });

    const page = await context.newPage();
    const consoleLogs = [];
    const consoleErrors = [];
    const pageErrors = [];

    page.on('console', msg => {
        const text = msg.text();
        consoleLogs.push({ type: msg.type(), text });
        if (msg.type() === 'error') {
            consoleErrors.push(text);
        }
    });

    page.on('pageerror', err => {
        pageErrors.push(err.message || String(err));
    });

    return {
        context,
        page,
        consoleLogs,
        consoleErrors,
        pageErrors,
        async takeScreenshot(name) {
            const filePath = path.join(SCREENSHOTS_DIR, `${name}.png`);
            try {
                await page.screenshot({ path: filePath, fullPage: false, timeout: 5000 });
            } catch (err) {
                console.warn(`[Screenshot Warning] Could not capture ${name}: ${err.message}`);
            }
            return filePath;
        }
    };
}

export async function loginAsSeller(page) {
    await page.goto(`${BASE_URL}/id/login`);
    await page.waitForLoadState('networkidle');

    await page.fill('#email', 'seller@example.com');
    await page.fill('#password', '09112024');
    
    // Choose role if role select exists
    const roleSelect = await page.$('select[name="role"]');
    if (roleSelect) {
        await page.selectOption('select[name="role"]', 'admin_seller');
    }

    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle' }),
        page.click('button[type="submit"]')
    ]);

    if (!page.url().includes('/admin/dashboard') && !page.url().includes('/admin')) {
        throw new Error(`Seller login failed. Current URL: ${page.url()}`);
    }
}

export async function loginAsPlatformAdmin(page) {
    await page.goto(`${BASE_URL}/id/login`);
    await page.waitForLoadState('networkidle');

    await page.fill('#email', 'azhrielmuari@gmail.com');
    await page.fill('#password', '09112024');

    const roleSelect = await page.$('select[name="role"]');
    if (roleSelect) {
        await page.selectOption('select[name="role"]', 'admin_platform');
    }

    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle' }),
        page.click('button[type="submit"]')
    ]);

    if (!page.url().includes('/platform-admin')) {
        throw new Error(`Platform Admin login failed. Current URL: ${page.url()}`);
    }
}
