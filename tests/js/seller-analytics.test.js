import { describe, it, expect, beforeEach, vi } from 'vitest';
import { SellerAnalyticsManager } from '../../resources/js/modules/seller-analytics';

describe('SellerAnalyticsManager', () => {
    let manager;

    beforeEach(() => {
        document.body.innerHTML = `
            <meta name="csrf-token" content="test-csrf-token">
            <meta name="chart-url" content="/id/admin/get-chart-data">
            <div id="statsChart" data-chart-url="/id/admin/get-chart-data" data-csrf="test-csrf-token"></div>
            <div id="dashboardChartWrapper"></div>
        `;
    });

    it('initializes with default options without crashing', () => {
        manager = new SellerAnalyticsManager({ autoInit: false });
        expect(manager.containerSelector).toBe('#statsChart');
        expect(manager.zoomLevels).toEqual([7, 14, 30, 60]);
        expect(manager.zoomIndex).toBe(2); // default 30
        expect(manager.panOffset).toBe(0);
    });

    it('returns empty slices when no fullData exists', () => {
        manager = new SellerAnalyticsManager({ autoInit: false });
        manager.fullData = null;
        const slice = manager.getVisibleSlice();
        expect(slice).toEqual({ labels: [], views: [], clicks: [] });
    });

    it('slices data correctly based on zoomLevel', () => {
        manager = new SellerAnalyticsManager({ autoInit: false });
        
        // 10 days of sample data
        const labels = Array.from({ length: 10 }, (_, i) => `Nov ${i + 1}`);
        const views = Array.from({ length: 10 }, (_, i) => i * 10);
        const clicks = Array.from({ length: 10 }, (_, i) => i * 2);

        manager.fullData = { labels, views, clicks };
        manager.zoomLevels = [3, 5, 7, 10];
        manager.zoomIndex = 1; // 5 days window
        manager.panOffset = 0;

        const slice = manager.getVisibleSlice();
        expect(slice.labels.length).toBe(5);
        expect(slice.labels[0]).toBe('Nov 6');
        expect(slice.labels[4]).toBe('Nov 10');
        expect(slice.views.length).toBe(5);
        expect(slice.clicks.length).toBe(5);
    });

    it('handles panning offset within bounded limits', () => {
        manager = new SellerAnalyticsManager({ autoInit: false });
        
        const labels = Array.from({ length: 10 }, (_, i) => `Day ${i + 1}`);
        manager.fullData = { labels, views: Array(10).fill(1), clicks: Array(10).fill(0) };
        manager.zoomLevels = [5];
        manager.zoomIndex = 0; // 5 days
        manager.panOffset = 2; // pan back 2 days

        const slice = manager.getVisibleSlice();
        expect(slice.labels.length).toBe(5);
        expect(slice.labels[0]).toBe('Day 4');
        expect(slice.labels[4]).toBe('Day 8');
    });

    it('clamps pan offset so it does not exceed maximum available history', () => {
        manager = new SellerAnalyticsManager({ autoInit: false });
        
        const labels = Array.from({ length: 10 }, (_, i) => `Day ${i + 1}`);
        manager.fullData = { labels, views: Array(10).fill(1), clicks: Array(10).fill(0) };
        manager.zoomLevels = [5];
        manager.zoomIndex = 0;
        manager.panOffset = 999; // Excessive offset

        const slice = manager.getVisibleSlice();
        expect(slice.labels.length).toBe(5);
        expect(slice.labels[0]).toBe('Day 1');
        expect(slice.labels[4]).toBe('Day 5');
    });

    it('resolves chart url and csrf token from DOM attributes', () => {
        manager = new SellerAnalyticsManager({ autoInit: false });
        expect(manager.resolveChartUrl()).toBe('/id/admin/get-chart-data');
        expect(manager.resolveCsrfToken()).toBe('test-csrf-token');
    });
});

