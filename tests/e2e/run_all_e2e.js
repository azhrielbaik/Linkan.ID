import { runPublicGuestModule } from './01_public_guest_module.test.js';
import { runAuthSecurityModule } from './02_auth_security_module.test.js';
import { runSellerDashboardModule } from './03_seller_dashboard_module.test.js';
import { runCoreTransactionBuyerModule } from './04_core_transaction_buyer_module.test.js';
import { runPlatformAdminModule } from './05_platform_admin_module.test.js';
import fs from 'fs';
import path from 'path';

async function runAllE2ETests() {
    console.log('\n================================================================');
    console.log('🚀 LINKAN.ID E2E TEST SUITE: COMPLETE HULU KE HILIR AUTOMATION');
    console.log('================================================================\n');

    const globalStartTime = Date.now();
    const allResults = {
        meta: {
            timestamp: new Date().toISOString(),
            baseUrl: 'http://linkan.gov',
            environment: 'Local / Linux Headless Chromium'
        },
        modules: {},
        summary: {
            total_tests: 0,
            passed_tests: 0,
            failed_tests: 0,
            pass_rate: '0%',
            total_duration_ms: 0
        }
    };

    try {
        // Module 1: Publik & Guest Flow
        console.log('--- STARTING MODULE 1/5: PUBLIK & GUEST ---');
        allResults.modules.module_1_public_guest = await runPublicGuestModule();

        // Module 2: Autentikasi & Keamanan Flow
        console.log('\n--- STARTING MODULE 2/5: AUTENTIKASI & KEAMANAN ---');
        allResults.modules.module_2_auth_security = await runAuthSecurityModule();

        // Module 3: Seller Dashboard Flow
        console.log('\n--- STARTING MODULE 3/5: SELLER DASHBOARD ---');
        allResults.modules.module_3_seller_dashboard = await runSellerDashboardModule();

        // Module 4: Transaksi & Pembeli Flow
        console.log('\n--- STARTING MODULE 4/5: TRANSAKSI & PEMBELI ---');
        allResults.modules.module_4_transaction_buyer = await runCoreTransactionBuyerModule();

        // Module 5: Platform Admin Flow
        console.log('\n--- STARTING MODULE 5/5: PLATFORM ADMIN ---');
        allResults.modules.module_5_platform_admin = await runPlatformAdminModule();

    } catch (fatalErr) {
        console.error('Fatal execution error during E2E test run:', fatalErr);
    }

    // Calculate aggregations
    let total = 0;
    let passed = 0;
    let failed = 0;

    for (const [moduleKey, items] of Object.entries(allResults.modules)) {
        if (Array.isArray(items)) {
            for (const item of items) {
                total++;
                if (item.status === 'PASS') {
                    passed++;
                } else {
                    failed++;
                }
            }
        }
    }

    const totalDuration = Date.now() - globalStartTime;
    const passRate = total > 0 ? ((passed / total) * 100).toFixed(2) + '%' : '0%';

    allResults.summary = {
        total_tests: total,
        passed_tests: passed,
        failed_tests: failed,
        pass_rate: passRate,
        total_duration_ms: totalDuration
    };

    // Save JSON report
    const reportPath = path.resolve('tests/e2e/e2e_results_report.json');
    fs.writeFileSync(reportPath, JSON.stringify(allResults, null, 2), 'utf-8');

    console.log('\n================================================================');
    console.log('📊 FINAL E2E AUTOMATION TEST SUMMARY');
    console.log('================================================================');
    console.log(`Total Tests Run : ${total}`);
    console.log(`Passed Tests    : ${passed}`);
    console.log(`Failed Tests    : ${failed}`);
    console.log(`Pass Rate       : ${passRate}`);
    console.log(`Total Duration  : ${(totalDuration / 1000).toFixed(2)}s`);
    console.log(`Report JSON     : ${reportPath}`);
    console.log('================================================================\n');

    if (failed > 0) {
        process.exitCode = 1;
    }
}

runAllE2ETests();
