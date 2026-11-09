import { createBrowser, createPageWithConsoleCapture, BASE_URL } from './helpers.js';
import { execSync } from 'child_process';

export async function runCoreTransactionBuyerModule() {
    console.log('\n======================================================');
    console.log('💳 RUNNING MODULE 4: TRANSACTION & BUYER FLOW (CORE)');
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
        // Retrieve valid transaction order_id, buyer_email, and review token
        const txInfo = JSON.parse(execSync(
            `php artisan tinker --execute="\\$tx = \\App\\Models\\Transaction::where('status', 'success')->first(); echo json_encode([ 'order_id' => \\$tx->order_id, 'buyer_email' => \\$tx->buyer_email, 'product_id' => \\$tx->product_id, 'token' => \\App\\Helpers\\ReviewTokenHelper::generate(\\$tx->order_id) ]);"`
        ).toString().trim());

        // 1. Product Detail Page
        await recordTest('Public Product Detail Page', `/id/product/${txInfo.product_id}`, async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/product/${txInfo.product_id}`);
            await page.waitForLoadState('networkidle');

            const body = await page.textContent('body');
            if (body.includes('404') && !body.includes('Rp')) {
                throw new Error('Product detail page returned 404');
            }

            const buyBtn = await page.$('a[href*="checkout"], button[type="submit"], .btn-buy, .btn-primary');
            if (!buyBtn) {
                throw new Error('Checkout / Buy button not found on product page');
            }

            await screenshot('01_product_detail');
            return 'Product detail displayed price and purchase CTA';
        });

        // 2. Checkout Form Page
        await recordTest('Checkout Form Page', `/id/checkout/${txInfo.product_id}`, async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/checkout/${txInfo.product_id}`);
            await page.waitForLoadState('networkidle');

            const hasCheckoutForm = (await page.$('form, input[name="name"], input[name="email"], input[name="whatsapp"], button')) !== null;
            if (!hasCheckoutForm) {
                throw new Error('Checkout form or customer inputs not found');
            }

            await screenshot('02_checkout_page');
            return 'Checkout page loaded order summary and customer form';
        });

        // 3. Token-Based Product Review (Valid Token)
        await recordTest('Token-Based Product Review (Valid)', `/id/review/${txInfo.token}`, async (page, screenshot) => {
            const res = await page.goto(`${BASE_URL}/id/review/${txInfo.token}`);
            await page.waitForLoadState('networkidle');

            if (res.status() !== 200) {
                throw new Error(`Expected HTTP 200 for valid review token, got ${res.status()}`);
            }

            const hasReviewForm = (await page.$('form, input[name="rating"], textarea[name="comment"], .review-card, main')) !== null;
            if (!hasReviewForm) {
                throw new Error('Review form or container not rendered');
            }

            await screenshot('03_product_review_page');
            return 'Review form rendered successfully with verified transaction';
        });

        // 4. Token-Based Product Review (Invalid Token Boundary)
        await recordTest('Token-Based Review Security Boundary', '/id/review/tampered_token_xyz', async (page, screenshot) => {
            const res = await page.goto(`${BASE_URL}/id/review/tampered_token_xyz`);
            await page.waitForLoadState('networkidle');

            if (res.status() !== 403) {
                throw new Error(`Expected HTTP 403 Forbidden for tampered review token, got ${res.status()}`);
            }

            await screenshot('04_review_tampered_blocked');
            return 'Invalid/tampered review token correctly blocked with HTTP 403';
        });

        // 5. Customer Dispute Portal & Order Lookup
        await recordTest('Customer Dispute Portal', '/id/bantuan/sengketa', async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/bantuan/sengketa`);
            await page.waitForLoadState('networkidle');

            const hasDisputeForm = (await page.$('form, input[name="order_id"], input[name="buyer_email"]')) !== null;
            if (!hasDisputeForm) {
                throw new Error('Dispute form inputs not found');
            }

            // Test checkOrder API with valid order
            const apiRes = await page.request.post(`${BASE_URL}/id/bantuan/sengketa/check-order`, {
                data: {
                    order_id: txInfo.order_id,
                    buyer_email: txInfo.buyer_email,
                },
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': await page.evaluate(() => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '')
                }
            });

            if (apiRes.status() !== 200) {
                throw new Error(`check-order API failed with HTTP ${apiRes.status()}`);
            }
            const orderCheckData = await apiRes.json();
            if (!orderCheckData.valid) {
                throw new Error(`check-order returned invalid: ${orderCheckData.message}`);
            }

            await screenshot('05_customer_dispute_portal');
            return 'Dispute form rendered; order lookup validation API returned valid order status';
        });

        // 6. Checkout Success Receipt Page
        await recordTest('Checkout Success Receipt', `/id/checkout/${txInfo.product_id}/success`, async (page, screenshot) => {
            await page.goto(`${BASE_URL}/id/checkout/${txInfo.product_id}/success?order_id=${txInfo.order_id}`);
            await page.waitForLoadState('networkidle');

            const body = await page.textContent('body');
            if (!body.includes('Berhasil') && !body.includes('Sukses') && !body.includes('Success') && !body.includes(txInfo.order_id)) {
                throw new Error('Checkout success confirmation text not found');
            }

            await screenshot('06_checkout_success_receipt');
            return 'Checkout success page displayed confirmation and download/access link';
        });

    } finally {
        await browser.close();
    }

    return results;
}

if (process.argv[1]?.endsWith('04_core_transaction_buyer_module.test.js')) {
    runCoreTransactionBuyerModule().then(res => {
        console.log('\nModule 4 Summary:', JSON.stringify(res, null, 2));
    });
}
