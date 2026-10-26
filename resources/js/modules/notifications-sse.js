/**
 * notifications-sse.js
 * Modular Server-Sent Events (SSE) notification connection and realtime badge updater.
 */

export class NotificationSSEManager {
    constructor(options = {}) {
        this.endpoint = options.endpoint || this.resolveEndpoint();
        this.badgeSelector = options.badgeSelector || '.notif-badge';
        this.totalPillSelector = options.totalPillSelector || '.notif-total-pill';
        this.eventSource = null;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 10;
        this.reconnectDelay = 3000;
        this.isConnecting = false;

        if (options.autoInit !== false && this.endpoint) {
            this.connect();
        }
    }

    resolveEndpoint() {
        const configElement = document.getElementById('app-config');
        if (configElement) {
            try {
                const config = JSON.parse(configElement.textContent);
                if (config.notifSSEEndpoint) return config.notifSSEEndpoint;
            } catch (e) {
                console.warn('Failed to parse app-config json', e);
            }
        }
        if (window.PlatformNotifSSEEndpoint) return window.PlatformNotifSSEEndpoint;
        if (window.SellerNotifSSEEndpoint) return window.SellerNotifSSEEndpoint;
        
        const el = document.querySelector('[data-sse-endpoint]');
        return el ? el.getAttribute('data-sse-endpoint') : null;
    }

    connect() {
        if (!this.endpoint || this.eventSource) return;
        this.isConnecting = true;

        try {
            this.eventSource = new EventSource(this.endpoint, { withCredentials: true });

            this.eventSource.onopen = () => {
                this.reconnectAttempts = 0;
                this.isConnecting = false;
            };

            this.eventSource.onmessage = (event) => {
                try {
                    const data = JSON.parse(event.data);
                    this.handleNotificationEvent(data);
                } catch (err) {
                    console.debug('SSE parse message', event.data);
                }
            };

            this.eventSource.addEventListener('notification', (event) => {
                try {
                    const data = JSON.parse(event.data);
                    this.handleNotificationEvent(data);
                } catch (err) {
                    console.warn('SSE notification event parse error', err);
                }
            });

            this.eventSource.onerror = (err) => {
                this.disconnect();
                this.scheduleReconnect();
            };
        } catch (e) {
            console.warn('Could not instantiate EventSource', e);
            this.scheduleReconnect();
        }
    }

    disconnect() {
        if (this.eventSource) {
            this.eventSource.close();
            this.eventSource = null;
        }
        this.isConnecting = false;
    }

    scheduleReconnect() {
        if (this.reconnectAttempts >= this.maxReconnectAttempts) {
            console.info('Notification SSE reached maximum reconnect attempts.');
            return;
        }
        this.reconnectAttempts++;
        const delay = this.reconnectDelay * Math.min(this.reconnectAttempts, 5);
        setTimeout(() => {
            if (!this.eventSource) {
                this.connect();
            }
        }, delay);
    }

    handleNotificationEvent(payload) {
        if (!payload) return;

        const count = payload.unread_count ?? payload.count ?? 0;
        this.updateBadges(count);

        // Dispatch custom DOM event for other listeners to react
        window.dispatchEvent(new CustomEvent('linkan:notification-received', {
            detail: payload
        }));
    }

    updateBadges(count) {
        const badges = document.querySelectorAll(this.badgeSelector);
        badges.forEach((badge) => {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'flex';
            } else {
                badge.textContent = '0';
                badge.style.display = 'none';
            }
        });

        const totalPills = document.querySelectorAll(this.totalPillSelector);
        totalPills.forEach((pill) => {
            pill.textContent = `${count} Baru`;
        });
    }
}

// Auto-initialize if DOM is ready
export function initNotificationSSE(options = {}) {
    const manager = new NotificationSSEManager(options);
    window.notificationSSE = manager;

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible' && !manager.eventSource) {
            manager.connect();
        }
    });

    window.addEventListener('beforeunload', () => {
        manager.disconnect();
    });

    return manager;
}
