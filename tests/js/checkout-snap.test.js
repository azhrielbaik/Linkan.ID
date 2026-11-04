import { describe, it, expect, beforeEach, vi } from 'vitest';
import { CheckoutSnapHandler } from '../../resources/js/modules/checkout-snap';

describe('CheckoutSnapHandler', () => {
    let handler;

    beforeEach(() => {
        document.body.innerHTML = `
            <meta name="csrf-token" content="token-12345">
            <input data-snap-token="SNAP-XYZ-789" type="hidden">
            <input data-success-url="/checkout-success/1" type="hidden">
            <input data-store-url="/api/checkout/store" type="hidden">
            <form id="checkoutForm" action="/checkout/submit">
                <input name="email" value="buyer@example.com">
                <button type="submit">Bayar</button>
            </form>
        `;
        delete window.snap;
        delete window.snapToken;
        window.alert = vi.fn();
    });

    it('resolves tokens and urls from data attributes', () => {
        handler = new CheckoutSnapHandler({ autoBind: false });
        expect(handler.resolveSnapToken()).toBe('SNAP-XYZ-789');
        expect(handler.resolveSuccessUrl()).toBe('/checkout-success/1');
        expect(handler.resolveStoreUrl()).toBe('/api/checkout/store');
    });

    it('falls back to window globals if data attribute not found', () => {
        document.body.innerHTML = '';
        window.snapToken = 'WINDOW-TOKEN-99';
        window.successUrl = '/success';
        window.storeUrl = '/store';

        handler = new CheckoutSnapHandler({ autoBind: false });
        expect(handler.resolveSnapToken()).toBe('WINDOW-TOKEN-99');
        expect(handler.resolveSuccessUrl()).toBe('/success');
        expect(handler.resolveStoreUrl()).toBe('/store');
    });

    it('alerts user gracefully if window.snap is unavailable when submitting', async () => {
        const alertSpy = vi.spyOn(window, 'alert');
        handler = new CheckoutSnapHandler({ autoBind: false });

        const form = document.querySelector('#checkoutForm');
        await handler.processCheckout(form);

        expect(alertSpy).toHaveBeenCalledWith(
            expect.stringContaining('Sistem pembayaran belum siap')
        );
    });
});
