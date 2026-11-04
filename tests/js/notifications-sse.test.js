import { describe, it, expect, beforeEach, vi } from 'vitest';
import { NotificationSSEManager } from '../../resources/js/modules/notifications-sse';

describe('NotificationSSEManager', () => {
    let manager;

    beforeEach(() => {
        document.body.innerHTML = `
            <div class="notif-badge" style="display: none;">0</div>
            <div class="notif-badge" style="display: none;">0</div>
            <div class="notif-total-pill">0 Baru</div>
            <div data-sse-endpoint="/id/admin/notifications/stream"></div>
        `;
        delete window.PlatformNotifSSEEndpoint;
        delete window.SellerNotifSSEEndpoint;
    });

    it('resolves endpoint from data attribute', () => {
        manager = new NotificationSSEManager({ autoInit: false });
        expect(manager.resolveEndpoint()).toBe('/id/admin/notifications/stream');
    });

    it('resolves endpoint from app-config JSON if present', () => {
        const configScript = document.createElement('script');
        configScript.id = 'app-config';
        configScript.type = 'application/json';
        configScript.textContent = JSON.stringify({ notifSSEEndpoint: '/api/v1/sse' });
        document.body.appendChild(configScript);

        manager = new NotificationSSEManager({ autoInit: false });
        expect(manager.resolveEndpoint()).toBe('/api/v1/sse');
    });

    it('resolves endpoint from window global as fallback', () => {
        document.body.innerHTML = '';
        window.SellerNotifSSEEndpoint = '/seller/stream';

        manager = new NotificationSSEManager({ autoInit: false });
        expect(manager.resolveEndpoint()).toBe('/seller/stream');
    });

    it('updates badge DOM elements when count is greater than zero', () => {
        manager = new NotificationSSEManager({ autoInit: false });
        manager.updateBadges(5);

        const badges = document.querySelectorAll('.notif-badge');
        badges.forEach((b) => {
            expect(b.textContent).toBe('5');
            expect(b.style.display).toBe('flex');
        });

        const pill = document.querySelector('.notif-total-pill');
        expect(pill.textContent).toBe('5 Baru');
    });

    it('formats badges over 99 as 99+', () => {
        manager = new NotificationSSEManager({ autoInit: false });
        manager.updateBadges(150);

        const badge = document.querySelector('.notif-badge');
        expect(badge.textContent).toBe('99+');
        expect(badge.style.display).toBe('flex');
    });

    it('hides badges when count is 0', () => {
        manager = new NotificationSSEManager({ autoInit: false });
        manager.updateBadges(0);

        const badge = document.querySelector('.notif-badge');
        expect(badge.textContent).toBe('0');
        expect(badge.style.display).toBe('none');
    });

    it('dispatches custom event with payload on notification event', () => {
        manager = new NotificationSSEManager({ autoInit: false });
        
        let receivedDetail = null;
        window.addEventListener('linkan:notification-received', (e) => {
            receivedDetail = e.detail;
        });

        manager.handleNotificationEvent({ unread_count: 3, id: 'tx_123' });

        expect(receivedDetail).toEqual({ unread_count: 3, id: 'tx_123' });
        expect(document.querySelector('.notif-badge').textContent).toBe('3');
    });

    it('never prints payload to console.debug (privacy enforcement)', () => {
        const debugSpy = vi.spyOn(console, 'debug');
        manager = new NotificationSSEManager({ autoInit: false });

        // Simulate incoming message parsing
        const rawEvent = { data: JSON.stringify({ buyer: 'Azhriel', amount: 500000 }) };
        manager.handleNotificationEvent(JSON.parse(rawEvent.data));

        expect(debugSpy).not.toHaveBeenCalled();
        debugSpy.mockRestore();
    });
});

