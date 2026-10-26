import './bootstrap';

import { NotificationSSEManager, initNotificationSSE } from './modules/notifications-sse';
import { SellerAnalyticsManager, initSellerAnalytics } from './modules/seller-analytics';
import { CheckoutSnapHandler, initCheckoutSnap } from './modules/checkout-snap';

// Expose on window for backward compatibility and inline partial integrations
window.NotificationSSEManager = NotificationSSEManager;
window.initNotificationSSE = initNotificationSSE;

window.SellerAnalyticsManager = SellerAnalyticsManager;
window.initSellerAnalytics = initSellerAnalytics;

window.CheckoutSnapHandler = CheckoutSnapHandler;
window.initCheckoutSnap = initCheckoutSnap;
