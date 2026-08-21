import sys

css_code = """
/* Slide Panel Styles */
.sl-panel-form {
    display: flex;
    flex-direction: column;
    height: 100%;
    margin: 0;
}

.sl-header-spacer {
    flex: 1;
}

.sl-panel-title {
    margin: 0;
    font-size: 18px;
    font-weight: 800;
    text-align: center;
}

.sl-header-actions {
    flex: 1;
    display: flex;
    justify-content: flex-end;
}

.preview-identity-borderless {
    border-bottom: none;
    padding-bottom: 20px;
}

.identity-left-full {
    width: 100%;
}

.panel-slug-badge {
    color: #FF9040;
    font-weight: 700;
}

.panel-action-buttons-container {
    padding: 0 30px 30px 30px;
    display: flex;
    justify-content: center;
    gap: 12px;
    border-bottom: 1px solid var(--border-color);
}

.action-btn-stacked {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    gap: 8px;
    padding: 16px 12px;
    text-decoration: none;
}

.action-btn-icon {
    font-size: 20px;
    color: #666;
}

.action-btn-orange {
    border-color: #FF9040;
    color: #FF9040;
    background: #FFF3E6;
}

.action-btn-orange .action-btn-icon {
    color: #FF9040;
}

.icon-orange {
    color: #FF9040;
}

.text-success {
    color: #2ecc40;
}

.icon-gray {
    color: #999;
}

.preview-section-flex {
    flex: 1;
}

.config-section-title {
    margin-top: 0;
}

.info-section-title {
    margin-top: 30px;
}

.panel-edit-title {
    margin-top: 0;
    margin-bottom: 24px;
}

.config-status-card {
    margin-bottom: 20px;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
}

.config-status-card.last {
    margin-bottom: 24px;
}

.config-status-left {
    display: flex;
    gap: 16px;
    align-items: center;
}

.config-status-icon-wrapper {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: #FFF3E6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #FF9040;
}

.config-status-title {
    font-weight: 800;
    color: #181818;
    margin-bottom: 4px;
    font-size: 15px;
}

.config-status-desc {
    color: #666;
    font-size: 13px;
    line-height: 1.5;
    max-width: 400px;
}

.config-action-btn {
    flex-shrink: 0;
}

.panel-link-text {
    color: #FF9040;
    text-decoration: none;
    word-break: break-all;
}

.hidden-link {
    display: none;
}

.panel-content-section {
    display: none;
    flex: 1;
    padding: 30px;
}

.panel-form-label {
    font-size: 13px;
    font-weight: 700;
    color: #181818;
    display: block;
    margin-bottom: 8px;
}

.panel-form-input {
    width: 100%;
    padding: 12px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-family: inherit;
    font-size: 14px;
    box-sizing: border-box;
}

.mt-16 {
    margin-top: 16px;
}

.panel-form-actions {
    margin-top: 30px;
    display: flex;
    gap: 10px;
}

.panel-btn-primary {
    background: #FF9040;
    color: #fff;
    border: none;
    padding: 12px 24px;
    font-size: 14px;
    flex: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-mr-8 {
    margin-right: 8px;
}

.panel-btn-secondary {
    background: #f1f1f1;
    color: #333;
    border: 1px solid #ddd;
    padding: 12px 24px;
    font-size: 14px;
    cursor: pointer;
}
"""

with open("public/css/pages/shortlink-create.css", "a") as f:
    f.write("\n" + css_code)

html_code = """<aside id="sl-panel">
    <form id="panel-form" method="POST" class="sl-panel-form">
        @csrf
        @method('PUT')
        
        <div class="preview-header">
            <div class="sl-header-spacer"></div>
            <h2 class="sl-panel-title">{{ __('shortlink.link_detail') }}</h2>
            <div class="sl-header-actions">
                <button type="button" class="preview-close" id="sl-panel-close"><i class="fas fa-times"></i></button>
            </div>
        </div>

        <div id="panel-view-section">
            <div class="preview-identity preview-identity-borderless">
                <div class="identity-left identity-left-full">
                    <div class="identity-icon"><i class="fas fa-link"></i></div>
                    <div class="identity-info">
                        <h3 id="panel-title">{{ __('shortlink.untitled') }}</h3>
                        <div class="identity-links">
                            <span><i class="far fa-envelope"></i> <span id="panel-desc">{{ __('shortlink.desc_placeholder') }}</span></span>
                            <span><i class="fas fa-globe"></i> <span id="panel-slug-badge" class="panel-slug-badge">/slug</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BUTTONS IN THE MIDDLE -->
            <div class="panel-action-buttons-container">
                <button type="button" class="action-btn-row action-btn-stacked" onclick="copySlugToClipboard(document.getElementById('panel-url').href, this)">
                    <i class="far fa-copy action-btn-icon"></i> {{ __('shortlink.copy') }}
                </button>
                <button type="button" class="action-btn-row action-btn-stacked action-btn-orange" onclick="toggleSection('edit')">
                    <i class="fas fa-edit action-btn-icon"></i> {{ __('shortlink.btn_edit') }}
                </button>
                <a href="#" id="panel-btn-analytics" class="action-btn-row action-btn-stacked">
                    <i class="fas fa-chart-bar action-btn-icon"></i> {{ __('shortlink.btn_stats') }}
                </a>
                <button type="button" class="action-btn-row action-btn-stacked" onclick="window.open(document.getElementById('panel-url').href, '_blank')">
                    <i class="fas fa-external-link-alt action-btn-icon"></i> {{ __('shortlink.btn_open') }}
                </button>
            </div>

            <div class="preview-meta-grid">
                <div class="meta-box">
                    <div class="meta-box-label">{{ __('shortlink.created_by') }}</div>
                    <div class="meta-box-value"><i class="fas fa-user-circle icon-orange"></i> {{ __('shortlink.sys_admin') }}</div>
                </div>
                <div class="meta-box">
                    <div class="meta-box-label">{{ __('shortlink.status') }}</div>
                    <div class="meta-box-value text-success"><i class="fas fa-check-circle"></i> {{ __('shortlink.active') }}</div>
                </div>
                <div class="meta-box">
                    <div class="meta-box-label">{{ __('shortlink.created_at') }}</div>
                    <div class="meta-box-value"><i class="far fa-calendar-plus icon-gray"></i> <span id="panel-created">...</span></div>
                </div>
                <div class="meta-box">
                    <div class="meta-box-label">{{ __('shortlink.last_edited') }}</div>
                    <div class="meta-box-value"><i class="far fa-calendar-check icon-gray"></i> <span id="panel-updated">...</span></div>
                </div>
            </div>

            <div class="preview-section preview-section-flex">
                <h4 class="section-title config-section-title">{{ __('shortlink.config_status') }}</h4>
                
                <!-- Tautan Terproteksi -->
                <div class="config-status-card">
                    <div class="config-status-left">
                        <div class="config-status-icon-wrapper">
                            <i class="fas fa-unlock" id="status-password-icon"></i>
                        </div>
                        <div>
                            <div class="config-status-title" id="status-password">{{ __('shortlink.pub_link') }}</div>
                            <div class="config-status-desc">{{ __('shortlink.pub_link_desc') }}</div>
                        </div>
                    </div>
                    <button type="button" onclick="toggleSection('password')" class="action-btn-row config-action-btn">
                        <i class="fas fa-key"></i> {{ __('shortlink.set_password') }}
                    </button>
                </div>

                <!-- Tautan Berjangka -->
                <div class="config-status-card last">
                    <div class="config-status-left">
                        <div class="config-status-icon-wrapper">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <div class="config-status-title" id="status-expires">{{ __('shortlink.no_time_limit') }}</div>
                            <div class="config-status-desc">{{ __('shortlink.no_time_limit_desc') }}</div>
                        </div>
                    </div>
                    <button type="button" onclick="toggleSection('expires')" class="action-btn-row config-action-btn">
                        <i class="fas fa-stopwatch"></i> {{ __('shortlink.set_time') }}
                    </button>
                </div>

                <h4 class="section-title info-section-title">{{ __('shortlink.link_info') }}</h4>
                <div class="note-card">
                    <div class="note-header">
                        <div class="note-header-left"><i class="far fa-sticky-note icon-gray"></i> {{ __('shortlink.destination_url') }}</div>
                    </div>
                    <div class="note-body">
                        <a href="#" id="panel-destination" target="_blank" class="panel-link-text"></a>
                    </div>
                </div>
                
                <div class="note-card">
                    <div class="note-header">
                        <div class="note-header-left"><i class="far fa-sticky-note icon-gray"></i> {{ __('shortlink.short_link') }}</div>
                    </div>
                    <div class="note-body">
                        <a href="#" id="panel-url" target="_blank" class="panel-link-text"></a>
                    </div>
                </div>
            </div>
            
            <a href="#" id="panel-url" class="hidden-link"></a>
        </div>

        <div id="panel-edit-section" class="panel-content-section">
            <h4 class="section-title panel-edit-title">{{ __('shortlink.link_config') }}</h4>
            
            <div class="form-group">
                <label class="panel-form-label">{{ __('shortlink.title_label') }}</label>
                <input type="text" name="title" id="panel-input-title" class="panel-form-input">
            </div>

            <div class="form-group mt-16">
                <label class="panel-form-label">{{ __('shortlink.edit_slug_label') }}</label>
                <input type="text" name="slug" id="panel-input-slug" required class="panel-form-input">
            </div>

            <div class="panel-form-actions">
                <button type="submit" class="preview-btn panel-btn-primary"><i class="fas fa-save icon-mr-8"></i> {{ __('shortlink.save_changes') }}</button>
                <button type="button" class="preview-btn panel-btn-secondary" onclick="toggleSection('view')">{{ __('shortlink.cancel') }}</button>
            </div>
        </div>

        <div id="panel-password-section" class="panel-content-section">
            <h4 class="section-title panel-edit-title">{{ __('shortlink.set_password_title') }}</h4>
            <div class="form-group">
                <label class="panel-form-label">{{ __('shortlink.password_label') }}</label>
                <input type="text" name="password" id="panel-input-password" placeholder="{{ __('shortlink.password_placeholder') }}" class="panel-form-input">
            </div>
            <div class="panel-form-actions">
                <button type="submit" class="preview-btn panel-btn-primary"><i class="fas fa-save icon-mr-8"></i> {{ __('shortlink.save_changes') }}</button>
                <button type="button" class="preview-btn panel-btn-secondary" onclick="toggleSection('view')">{{ __('shortlink.cancel') }}</button>
            </div>
        </div>

        <div id="panel-expires-section" class="panel-content-section">
            <h4 class="section-title panel-edit-title">{{ __('shortlink.expiration') }}</h4>
            <div class="form-group">
                <label class="panel-form-label">{{ __('shortlink.expiration_label') }}</label>
                <input type="datetime-local" name="expires_at" id="panel-input-expires" class="panel-form-input">
            </div>
            <div class="panel-form-actions">
                <button type="submit" class="preview-btn panel-btn-primary"><i class="fas fa-save icon-mr-8"></i> {{ __('shortlink.save_changes') }}</button>
                <button type="button" class="preview-btn panel-btn-secondary" onclick="toggleSection('view')">{{ __('shortlink.cancel') }}</button>
            </div>
        </div>
    </form>
</aside>
"""

with open("resources/views/admin/shortlinks/partials/side-panel.blade.php", "w") as f:
    f.write(html_code)

with open("resources/views/homeadminS/shortlink/create.blade.php", "r") as f:
    lines = f.readlines()

new_lines = lines[:233] + ["@include('admin.shortlinks.partials.side-panel')\n"] + lines[397:]

with open("resources/views/homeadminS/shortlink/create.blade.php", "w") as f:
    f.writelines(new_lines)
