@extends("layouts.admin")

@section("page_title", "Shortlink")

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/shortlink-create.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="dashboard-wrapper">




    @if(session('success'))
        <div class="success-card" id="successToast">
            <div class="success-card-header">
                <div class="icon"><i class="fas fa-check"></i></div>
                <h3 style="font-size: 16px;">{{ __('shortlink.saved_successfully') }}</h3>
                <button class="success-card-close" onclick="closeToast()"><i class="fas fa-times"></i></button>
            </div>
            <div class="shortlink-display">
                <input type="text" id="shortlinkInput" value="{{ session('success') }}" readonly>
                <button class="btn-copy" onclick="copyToClipboard()"><i class="fas fa-copy"></i> {{ __('shortlink.copy') }}</button>
            </div>
        </div>
    @endif

    <div class="dashboard-grid">
        <!-- LEFT COLUMN -->
        <div class="left-col">
            <!-- PERFORMANCE CARD -->
            <div class="card performance-card">
                <div class="card-header">
                    <div class="card-title">{{ __('shortlink.performance') }}</div>
                    <div style="font-size: 13px; color: #888;">{{ __('shortlink.data_preview') }}</div>
                </div>

                <div class="stats-row">
                    <div class="stat-box">
                        <div class="stat-icon" style="background: #EEF0FE; color: #5A5BF1;"><i class="fas fa-chart-line"></i></div>
                        <div class="stat-info">
                            <span>{{ __('shortlink.total_clicks') }}</span>
                            <strong>2,280</strong>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon" style="background: #E6F6FF; color: #0088FF;"><i class="fas fa-link"></i></div>
                        <div class="stat-info">
                            <span>{{ __('shortlink.total_links') }}</span>
                            <strong>{{ $shortlinks->total() }}</strong>
                        </div>
                    </div>
                </div>

                <div class="chart-container">
                    @foreach([300, 280, 260, 310, 250, 400, 220, 350, 450, 240, 410, 380] as $index => $val)
                    <div class="chart-bar-group">
                        <div class="chart-bar" style="height: {{ ($val / 450) * 160 }}px;"></div>
                        <div class="chart-label">{{ ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][$index] }}</div>
                    </div>
                    @endforeach
                </div>
                <button class="hide-chart-btn"><i class="fas fa-chevron-up"></i> {{ __('shortlink.hide_chart') }}</button>
            </div>

            <!-- SEARCH & FILTER -->
            <div class="card search-filter-card" style="margin-bottom: 0;">
                <form method="GET" action="{{ route('admin.shortlinks.index') }}" id="search-filter-form">
                    <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                        <div style="flex: 1; position: relative; min-width: 200px;">
                            <i class="fas fa-search" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #999;"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('shortlink.search_placeholder') ?? 'Search your links...' }}" style="width: 100%; padding: 12px 40px 12px 40px; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;">
                            @if(request('search'))
                                <a href="{{ route('admin.shortlinks.index', request()->except(['search', 'page'])) }}" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #999; text-decoration: none;"><i class="fas fa-times-circle"></i></a>
                            @endif
                        </div>
                        <div style="width: 180px; position: relative; flex-shrink: 0;" class="desktop-sort-wrapper">
                            <select name="sort" onchange="this.form.submit()" style="width: 100%; padding: 12px 32px 12px 16px; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; font-size: 14px; background: #fff; cursor: pointer; appearance: none; color: #181818; box-sizing: border-box;">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('shortlink.filter_newest') ?? 'Newest' }}</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>{{ __('shortlink.filter_oldest') ?? 'Oldest' }}</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>{{ __('shortlink.filter_popular') ?? 'Most Popular' }}</option>
                            </select>
                            <i class="fas fa-chevron-down" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #999; pointer-events: none; font-size: 12px;"></i>
                        </div>
                    </div>

                    <!-- Mobile Segmented Sorting Tabs -->
                    <div class="mobile-segment-control">
                        <button type="button" class="segment-tab {{ request('sort') != 'popular' ? 'active' : '' }}" data-sort="newest">
                            <i class="fas fa-bolt"></i> {{ __('shortlink.filter_newest') ?? 'Newest' }}
                        </button>
                        <button type="button" class="segment-tab {{ request('sort') == 'popular' ? 'active' : '' }}" data-sort="popular">
                            <i class="fas fa-fire"></i> {{ __('shortlink.filter_popular') ?? 'Most Popular' }}
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="right-col mobile-form-collapse">
            <div class="mobile-form-collapse-header" onclick="this.parentElement.classList.toggle('is-open')">
                <span><i class="fas fa-plus-circle" style="color:#5A5BF1; margin-right:8px;"></i> {{ __('shortlink.create_new_link') }}</span>
                <i class="fas fa-chevron-down" style="color:#999;"></i>
            </div>
            <div class="mobile-form-collapse-body">
                <form action="{{ route('admin.shortlinks.store') }}" method="POST">
                @csrf
                
                <!-- CREATE NEW LINK -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">{{ __('shortlink.create_new_link') }} <i class="fas fa-link" style="color: #999; margin-left: 8px;"></i></div>
                    </div>
                    <p class="create-form-desc">{{ __('shortlink.create_desc') }}</p>
                    
                    <div class="input-row">
                        <input type="url" name="destination" placeholder="https://example.com/your-long-url" required value="{{ old('destination') }}">
                        <button type="submit" class="btn-submit">{{ __('shortlink.btn_create') }} <i class="fas fa-arrow-right"></i></button>
                    </div>
                    @error('destination') <div style="color: red; font-size: 12px; margin-top: -10px; margin-bottom: 10px;">{{ $message }}</div> @enderror
                </div>

                <!-- CUSTOM YOUR LINK -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">{{ __('shortlink.custom_link') }}</div>
                    </div>
                    
                    <div class="form-group">
                        <label>{{ __('shortlink.slug_label') }}</label>
                        <div class="slug-wrapper">
                            <div class="slug-prefix"><i class="fas fa-link"></i> Linkan.id/</div>
                            <input type="text" name="slug" id="slug" placeholder="custom-slug" required value="{{ old('slug') }}">
                            <button type="button" onclick="generateRandomSlug()" style="background: transparent; border: none; padding: 0 16px; color: #5A5BF1; font-weight: bold; cursor: pointer; border-left: 1px solid #e0e0e0;"><i class="fas fa-random"></i></button>
                        </div>
                        <div style="font-size: 12px; color: #888; margin-top: 6px;">{{ __('shortlink.slug_hint') }}</div>
                        @error('slug') <div style="color: red; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label>{{ __('shortlink.title_label') }}</label>
                        <input type="text" name="title" placeholder="{{ __('shortlink.title_placeholder') }}" value="{{ old('title') }}">
                        @error('title') <div style="color: red; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label>{{ __('shortlink.desc_label') }}</label>
                        <input type="text" name="description" placeholder="{{ __('shortlink.desc_placeholder') }}" value="{{ old('description') }}">
                        @error('description') <div style="color: red; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>

            <!-- ENGAGEMENT ALL TIME -->
            <div class="card engagement-card">
                <div class="card-header desktop-only-card-header">
                    <div class="card-title">{{ __('shortlink.engagement') }}</div>
                    <div style="font-size: 13px; color: #888;">{{ $shortlinks->total() }} {{ __('shortlink.results') }}</div>
                </div>

                <!-- Mobile List Subheader -->
                <div class="mobile-list-subheader">
                    <span class="col-name">{{ __('shortlink.name_label') }} <i class="fas fa-sort"></i></span>
                    <span class="col-actions">{{ __('shortlink.actions_label') }} <i class="fas fa-sort"></i></span>
                </div>

                <div class="engagement-list">
                    @forelse($shortlinks as $link)
                    <div class="engagement-item sl-card"
                         data-id="{{ $link->id }}"
                         data-title="{{ $link->title ?: $link->slug }}"
                         data-description="{{ $link->description ?: '' }}"
                         data-destination="{{ $link->destination }}"
                         data-url="{{ url('/' . $link->slug) }}"
                         data-created="{{ $link->created_at->format('d M Y, H:i') }}"
                         data-updated="{{ $link->updated_at->format('d M Y, H:i') }}"
                         data-slug="{{ $link->slug }}"
                         data-password="{{ $link->password }}"
                         data-expires="{{ $link->expires_at ? $link->expires_at->format('Y-m-d\TH:i') : '' }}"
                    >
                        <!-- Mobile Link Icon -->
                        <div class="mobile-list-icon-container">
                            <i class="fas fa-link"></i>
                        </div>

                        <div class="eng-info">
                            <h4>{{ $link->title ?: __('shortlink.untitled') . ' (' . $link->slug . ')' }}</h4>
                            <a href="{{ url('/' . $link->slug) }}" target="_blank" style="font-size: 13px; color: #5A5BF1; font-weight: 700; text-decoration: none; display: block; margin-bottom: 4px;">Linkan.id/{{ $link->slug }}</a>
                            <p>{{ Str::limit($link->destination, 60) }}</p>
                            @if($link->description)
                            <p style="font-size: 12px; color: #999; margin-top: 4px;">{{ Str::limit($link->description, 60) }}</p>
                            @endif
                            <!-- Mobile Subtitle -->
                            <span class="mobile-slug-text">Linkan.id/{{ $link->slug }}</span>
                        </div>
                        
                        <!-- Desktop Actions -->
                        <div class="eng-actions desktop-only-actions">
                            <button type="button" class="btn-detail-text sl-btn--detail">{{ __('shortlink.detail') }}</button>
                            <a href="{{ route('admin.shortlinks.analytics', $link) }}" class="btn-icon" title="Analytics"><i class="fas fa-chart-bar"></i></a>
                            <button type="button" class="btn-icon sl-btn--copy" onclick="copySlugToClipboard('{{ url('/' . $link->slug) }}', this)" title="Copy Link"><i class="fas fa-copy"></i></button>
                        </div>

                        <!-- Mobile Actions -->
                        <div class="mobile-only-actions">
                            <button type="button" class="mobile-action-circle-btn sl-btn--edit-direct" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                            <button type="button" class="mobile-action-circle-btn sl-btn--detail" title="Detail"><i class="fas fa-ellipsis-h"></i></button>
                        </div>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 30px; color: #999;">
                        <i class="fas fa-link" style="font-size: 32px; margin-bottom: 10px; color: #ddd;"></i>
                        <p>{{ __('shortlink.no_shortlink') }}</p>
                    </div>
                    @endforelse
                </div>
                
                <div style="margin-top: 20px;">
                    {{ $shortlinks->appends(request()->except('page'))->links() }}
                </div>
            </div>
</div>

<!-- PANEL OVERLAY -->
<div id="sl-overlay"></div>

<!-- SLIDE OUT PANEL -->
<aside id="sl-panel">
    <form id="panel-form" method="POST" style="display:flex; flex-direction:column; height:100%; margin:0;">
        @csrf
        @method('PUT')
        
        <div class="preview-header">
            <div style="flex: 1;"></div>
            <h2 style="margin:0; font-size:18px; font-weight:800; text-align:center;">{{ __('shortlink.link_detail') }}</h2>
            <div style="flex: 1; display:flex; justify-content:flex-end;">
                <button type="button" class="preview-close" id="sl-panel-close"><i class="fas fa-times"></i></button>
            </div>
        </div>

        <div id="panel-view-section">
            <div class="preview-identity" style="border-bottom: none; padding-bottom: 20px;">
                <div class="identity-left" style="width: 100%;">
                    <div class="identity-icon"><i class="fas fa-link"></i></div>
                    <div class="identity-info">
                        <h3 id="panel-title">{{ __('shortlink.untitled') }}</h3>
                        <div class="identity-links">
                            <span><i class="far fa-envelope"></i> <span id="panel-desc">{{ __('shortlink.desc_placeholder') }}</span></span>
                            <span><i class="fas fa-globe"></i> <span id="panel-slug-badge" style="color: #5A5BF1; font-weight:700;">/slug</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BUTTONS IN THE MIDDLE -->
            <div style="padding: 0 30px 30px 30px; display: flex; justify-content: center; gap: 12px; border-bottom: 1px solid var(--border-color);">
                <button type="button" class="action-btn-row" style="flex: 1; justify-content: center; flex-direction: column; gap: 8px; padding: 16px 12px;" onclick="copySlugToClipboard(document.getElementById('panel-url').href, this)">
                    <i class="far fa-copy" style="font-size: 20px; color: #666;"></i> {{ __('shortlink.copy') }}
                </button>
                <button type="button" class="action-btn-row" style="flex: 1; justify-content: center; flex-direction: column; gap: 8px; padding: 16px 12px; border-color: #5A5BF1; color: #5A5BF1; background: #EEF0FE;" onclick="toggleSection('edit')">
                    <i class="fas fa-edit" style="font-size: 20px;"></i> {{ __('shortlink.btn_edit') }}
                </button>
                <a href="#" id="panel-btn-analytics" class="action-btn-row" style="flex: 1; justify-content: center; flex-direction: column; gap: 8px; padding: 16px 12px; text-decoration: none;">
                    <i class="fas fa-chart-bar" style="font-size: 20px; color: #666;"></i> {{ __('shortlink.btn_stats') }}
                </a>
                <button type="button" class="action-btn-row" style="flex: 1; justify-content: center; flex-direction: column; gap: 8px; padding: 16px 12px;" onclick="window.open(document.getElementById('panel-url').href, '_blank')">
                    <i class="fas fa-external-link-alt" style="font-size: 20px; color: #666;"></i> {{ __('shortlink.btn_open') }}
                </button>
            </div>

            <div class="preview-meta-grid">
                <div class="meta-box">
                    <div class="meta-box-label">{{ __('shortlink.created_by') }}</div>
                    <div class="meta-box-value"><i class="fas fa-user-circle" style="color: #5A5BF1;"></i> {{ __('shortlink.sys_admin') }}</div>
                </div>
                <div class="meta-box">
                    <div class="meta-box-label">{{ __('shortlink.status') }}</div>
                    <div class="meta-box-value" style="color: #2ecc40;"><i class="fas fa-check-circle"></i> {{ __('shortlink.active') }}</div>
                </div>
                <div class="meta-box">
                    <div class="meta-box-label">{{ __('shortlink.created_at') }}</div>
                    <div class="meta-box-value"><i class="far fa-calendar-plus" style="color: #999;"></i> <span id="panel-created">...</span></div>
                </div>
                <div class="meta-box">
                    <div class="meta-box-label">{{ __('shortlink.last_edited') }}</div>
                    <div class="meta-box-value"><i class="far fa-calendar-check" style="color: #999;"></i> <span id="panel-updated">...</span></div>
                </div>
            </div>

            <div class="preview-section" style="flex:1;">
                <h4 class="section-title" style="margin-top: 0px;">{{ __('shortlink.config_status') }}</h4>
                
                <!-- Tautan Terproteksi -->
                <div style="margin-bottom: 20px; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; display: flex; justify-content: space-between; align-items: center; background: #fff;">
                    <div style="display: flex; gap: 16px; align-items: center;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: #EEF0FE; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #5A5BF1;">
                            <i class="fas fa-unlock" id="status-password-icon"></i>
                        </div>
                        <div>
                            <div style="font-weight: 800; color: #181818; margin-bottom: 4px; font-size: 15px;" id="status-password">{{ __('shortlink.pub_link') }}</div>
                            <div style="color: #666; font-size: 13px; line-height: 1.5; max-width: 400px;">{{ __('shortlink.pub_link_desc') }}</div>
                        </div>
                    </div>
                    <button type="button" onclick="toggleSection('password')" class="action-btn-row" style="flex-shrink:0;">
                        <i class="fas fa-key"></i> {{ __('shortlink.set_password') }}
                    </button>
                </div>

                <!-- Tautan Berjangka -->
                <div style="margin-bottom: 24px; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; display: flex; justify-content: space-between; align-items: center; background: #fff;">
                    <div style="display: flex; gap: 16px; align-items: center;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: #EEF0FE; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #5A5BF1;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <div style="font-weight: 800; color: #181818; margin-bottom: 4px; font-size: 15px;" id="status-expires">{{ __('shortlink.no_time_limit') }}</div>
                            <div style="color: #666; font-size: 13px; line-height: 1.5; max-width: 400px;">{{ __('shortlink.no_time_limit_desc') }}</div>
                        </div>
                    </div>
                    <button type="button" onclick="toggleSection('expires')" class="action-btn-row" style="flex-shrink:0;">
                        <i class="fas fa-stopwatch"></i> {{ __('shortlink.set_time') }}
                    </button>
                </div>

                <h4 class="section-title" style="margin-top: 30px;">{{ __('shortlink.link_info') }}</h4>
                <div class="note-card">
                    <div class="note-header">
                        <div class="note-header-left"><i class="far fa-sticky-note" style="color:#999;"></i> {{ __('shortlink.destination_url') }}</div>
                    </div>
                    <div class="note-body">
                        <a href="#" id="panel-destination" target="_blank" style="color: #5A5BF1; text-decoration: none; word-break: break-all;"></a>
                    </div>
                </div>
                
                <div class="note-card">
                    <div class="note-header">
                        <div class="note-header-left"><i class="far fa-sticky-note" style="color:#999;"></i> {{ __('shortlink.short_link') }}</div>
                    </div>
                    <div class="note-body">
                        <a href="#" id="panel-url" target="_blank" style="color: #5A5BF1; text-decoration: none; word-break: break-all;"></a>
                    </div>
                </div>
            </div>
            
            <a href="#" id="panel-url" style="display:none;"></a>
        </div>

        <div id="panel-edit-section" style="display: none; flex: 1; padding: 30px;">
            <h4 class="section-title" style="margin-top: 0px; margin-bottom: 24px;">{{ __('shortlink.link_config') }}</h4>
            
            <div class="form-group">
                <label style="font-size: 13px; font-weight: 700; color: #181818; display: block; margin-bottom: 8px;">{{ __('shortlink.title_label') }}</label>
                <input type="text" name="title" id="panel-input-title" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;">
            </div>

            <div class="form-group" style="margin-top: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #181818; display: block; margin-bottom: 8px;">{{ __('shortlink.edit_slug_label') }}</label>
                <input type="text" name="slug" id="panel-input-slug" required style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;">
            </div>

            <div style="margin-top: 30px; display: flex; gap: 10px;">
                <button type="submit" class="preview-btn" style="background: #5A5BF1; color: #fff; border: none; padding: 12px 24px; font-size: 14px; flex: 1; cursor: pointer;"><i class="fas fa-save" style="margin-right: 8px;"></i> {{ __('shortlink.save_changes') }}</button>
                <button type="button" class="preview-btn" onclick="toggleSection('view')" style="background: #f1f1f1; color: #333; border: 1px solid #ddd; padding: 12px 24px; font-size: 14px; cursor: pointer;">{{ __('shortlink.cancel') }}</button>
            </div>
        </div>

        <div id="panel-password-section" style="display: none; flex: 1; padding: 30px;">
            <h4 class="section-title" style="margin-top: 0px; margin-bottom: 24px;">{{ __('shortlink.set_password_title') }}</h4>
            <div class="form-group">
                <label style="font-size: 13px; font-weight: 700; color: #181818; display: block; margin-bottom: 8px;">{{ __('shortlink.password_label') }}</label>
                <input type="text" name="password" id="panel-input-password" placeholder="{{ __('shortlink.password_placeholder') }}" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;">
            </div>
            <div style="margin-top: 30px; display: flex; gap: 10px;">
                <button type="submit" class="preview-btn" style="background: #5A5BF1; color: #fff; border: none; padding: 12px 24px; font-size: 14px; flex: 1; cursor: pointer;"><i class="fas fa-save" style="margin-right: 8px;"></i> {{ __('shortlink.save_changes') }}</button>
                <button type="button" class="preview-btn" onclick="toggleSection('view')" style="background: #f1f1f1; color: #333; border: 1px solid #ddd; padding: 12px 24px; font-size: 14px; cursor: pointer;">{{ __('shortlink.cancel') }}</button>
            </div>
        </div>

        <div id="panel-expires-section" style="display: none; flex: 1; padding: 30px;">
            <h4 class="section-title" style="margin-top: 0px; margin-bottom: 24px;">{{ __('shortlink.expiration') }}</h4>
            <div class="form-group">
                <label style="font-size: 13px; font-weight: 700; color: #181818; display: block; margin-bottom: 8px;">{{ __('shortlink.expiration_label') }}</label>
                <input type="datetime-local" name="expires_at" id="panel-input-expires" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;">
            </div>
            <div style="margin-top: 30px; display: flex; gap: 10px;">
                <button type="submit" class="preview-btn" style="background: #5A5BF1; color: #fff; border: none; padding: 12px 24px; font-size: 14px; flex: 1; cursor: pointer;"><i class="fas fa-save" style="margin-right: 8px;"></i> {{ __('shortlink.save_changes') }}</button>
                <button type="button" class="preview-btn" onclick="toggleSection('view')" style="background: #f1f1f1; color: #333; border: 1px solid #ddd; padding: 12px 24px; font-size: 14px; cursor: pointer;">{{ __('shortlink.cancel') }}</button>
            </div>
        </div>
    </form>
</aside>

@endsection

@push("scripts")
<script>
    function toggleSection(mode) {
        const viewSec = document.getElementById('panel-view-section');
        const editSec = document.getElementById('panel-edit-section');
        const pwdSec = document.getElementById('panel-password-section');
        const expSec = document.getElementById('panel-expires-section');

        if(viewSec) viewSec.style.display = (mode === 'view') ? 'block' : 'none';
        if(editSec) editSec.style.display = (mode === 'edit') ? 'block' : 'none';
        if(pwdSec) pwdSec.style.display = (mode === 'password') ? 'block' : 'none';
        if(expSec) expSec.style.display = (mode === 'expires') ? 'block' : 'none';
    }

    function copyToClipboard() {
        const input = document.getElementById("shortlinkInput");
        if(input) {
            input.select();
            input.setSelectionRange(0, 99999);
            document.execCommand("copy");
            
            const copyBtn = document.querySelector('.btn-copy');
            const originalHTML = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fas fa-check"></i> {{ __('shortlink.copied') }}';
            setTimeout(() => {
                copyBtn.innerHTML = originalHTML;
            }, 2000);
        }
    }

    function closeToast() {
        const toast = document.getElementById('successToast');
        if (toast) {
            toast.classList.add('hiding');
            setTimeout(() => {
                toast.remove();
            }, 400); // Wait for animation to finish
        }
    }

    function generateRandomSlug() {
        const length = 6;
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        
        const slugInput = document.getElementById('slug');
        if(slugInput) {
            slugInput.value = result;
            slugInput.style.backgroundColor = '#EEF0FE';
            slugInput.style.color = '#5A5BF1';
            setTimeout(() => {
                slugInput.style.backgroundColor = 'transparent';
                slugInput.style.color = 'inherit';
            }, 300);
        }
    }

    function copySlugToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.style.background = '#2ecc40';
            btn.style.color = '#fff';
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.style.background = '#EEF0FE';
                btn.style.color = '#5A5BF1';
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }

                // Detail Panel Logic
    if (!window.shortlinkEventDelegationSet) {
        window.shortlinkEventDelegationSet = true;

        window.openPanel = function() {
            const currentOverlay = document.getElementById('sl-overlay');
            const currentPanel = document.getElementById('sl-panel');
            if(currentOverlay) currentOverlay.classList.add('is-visible');
            if(currentPanel) currentPanel.classList.add('is-open');
        }

        window.closePanel = function() {
            const viewSec = document.getElementById('panel-view-section');
            if (viewSec && viewSec.style.display === 'none') {
                toggleSection('view');
                return;
            }
            
            const currentOverlay = document.getElementById('sl-overlay');
            const currentPanel = document.getElementById('sl-panel');
            if(currentOverlay) currentOverlay.classList.remove('is-visible');
            if(currentPanel) currentPanel.classList.remove('is-open');
        }

        document.addEventListener('click', function(e) {
            // Close Panel
            if (e.target.closest('#sl-panel-close') || e.target.id === 'sl-overlay') {
                closePanel();
            }

            // Detail button
            const btn = e.target.closest('.sl-btn--detail');
            if (btn) {
                e.preventDefault();
                const card = btn.closest('.sl-card');
                if (!card) return;

                const form = document.getElementById('panel-form');
                if(form) {
                    form.action = `/admin/shortlinks/${card.dataset.id}`;
                }

                const panelInputSlug = document.getElementById('panel-input-slug');
                if(panelInputSlug) panelInputSlug.value = card.dataset.slug;

                const panelInputTitle = document.getElementById('panel-input-title');
                if(panelInputTitle) panelInputTitle.value = card.dataset.title;

                const panelInputPassword = document.getElementById('panel-input-password');
                if(panelInputPassword) panelInputPassword.value = card.dataset.password;

                const panelInputExpires = document.getElementById('panel-input-expires');
                if(panelInputExpires) panelInputExpires.value = card.dataset.expires;

                toggleSection('view');

                const panelTitle = document.getElementById('panel-title');
                if(panelTitle) panelTitle.innerText = card.dataset.title;
                
                const urlEl = document.getElementById('panel-url');
                if(urlEl) urlEl.href = card.dataset.url;

                const panelCreatedDate = document.getElementById('panel-created-date');
                if(panelCreatedDate) panelCreatedDate.innerText = card.dataset.created;

                const statusPasswordIcon = document.getElementById('status-password-icon');
                const statusPassword = document.getElementById('status-password');
                if(statusPassword) {
                    if (card.dataset.password) {
                        statusPassword.innerText = '{{ __('shortlink.password_protected') }}';
                        if (statusPasswordIcon) statusPasswordIcon.className = 'fas fa-lock';
                    } else {
                        statusPassword.innerText = '{{ __('shortlink.pub_link') }}';
                        if (statusPasswordIcon) statusPasswordIcon.className = 'fas fa-unlock';
                    }
                }

                const statusExpires = document.getElementById('status-expires');
                if(statusExpires) {
                    if (card.dataset.expires) {
                        statusExpires.innerText = '{{ __('shortlink.expired_label') }}: ' + card.dataset.expires.replace('T', ' ');
                    } else {
                        statusExpires.innerText = '{{ __('shortlink.no_time_limit') }}';
                    }
                }

                const panelSlug = document.getElementById('panel-slug-badge');
                if(panelSlug) {
                    const urlPath = new URL(card.dataset.url).pathname;
                    panelSlug.innerText = urlPath;
                }
                
                const panelDesc = document.getElementById('panel-desc');
                if(panelDesc) panelDesc.innerText = card.dataset.description || '{{ __('shortlink.desc_placeholder') }}';
                
                const destEl = document.getElementById('panel-destination');
                if(destEl) {
                    destEl.href = card.dataset.destination;
                    destEl.innerText = card.dataset.destination;
                }

                const urlEl2 = document.getElementById('panel-url');
                if(urlEl2) {
                    urlEl2.href = card.dataset.url;
                    urlEl2.innerText = card.dataset.url;
                }

                const panelCreated = document.getElementById('panel-created');
                if(panelCreated) panelCreated.innerText = card.dataset.created;
                
                const panelUpdated = document.getElementById('panel-updated');
                if(panelUpdated) panelUpdated.innerText = card.dataset.updated;

                openPanel();
                const btnAnalytics = document.getElementById('panel-btn-analytics');
                if(btnAnalytics) {
                    const analyticsLink = card.querySelector('a[title="Analytics"]');
                    if(analyticsLink) btnAnalytics.href = analyticsLink.href;
                }
            }

            // Segmented tab
            const tab = e.target.closest('.segment-tab');
            if (tab) {
                e.preventDefault();
                const sortVal = tab.dataset.sort;
                const sortSelect = document.querySelector('select[name="sort"]');
                if (sortSelect) {
                    sortSelect.value = sortVal;
                    tab.parentElement.querySelectorAll('.segment-tab').forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    window.performAjaxSearch();
                }
            }

            // Edit direct
            const editBtn = e.target.closest('.sl-btn--edit-direct');
            if (editBtn) {
                e.preventDefault();
                const card = editBtn.closest('.sl-card');
                if (card) {
                    const detailBtn = card.querySelector('.sl-btn--detail');
                    if (detailBtn) {
                        detailBtn.click();
                        setTimeout(() => {
                            toggleSection('edit');
                        }, 50);
                    }
                }
            }

            // Clear search
            const clearLink = e.target.closest('.fa-times-circle')?.parentElement || e.target.closest('input[name="search"] ~ a');
            if (clearLink && clearLink.closest('div[style*="position: relative"]')) {
                e.preventDefault();
                const searchInput = document.querySelector('input[name="search"]');
                if (searchInput) searchInput.value = '';
                window.performAjaxSearch();
            }

            // Pagination
            const pageLink = e.target.closest('.pagination a, .pagination-container a, [style*="margin-top: 20px"] a');
            if (pageLink && pageLink.href) {
                const isShortlinkPagination = pageLink.closest('[style*="margin-top: 20px"]');
                if (isShortlinkPagination) {
                    e.preventDefault();
                    window.fetchAndUpdate(pageLink.href);
                }
            }
        });
    }

    // Attach submit event listener on turbo:load because the form element changes every load
    document.addEventListener("turbo:load", function() {
        window.toggleMobileForm = function() {
            const formCol = document.querySelector('.mobile-form-collapse');
            if (formCol) {
                formCol.classList.toggle('is-open');
                if (formCol.classList.contains('is-open')) {
                    formCol.scrollIntoView({ behavior: 'smooth' });
                }
            }
        };

        const searchInput = document.querySelector('input[name="search"]');
        const sortSelect = document.querySelector('select[name="sort"]');
        const searchForm = document.getElementById('search-filter-form');
        let debounceTimeout;

        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                window.performAjaxSearch();
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(() => {
                    window.performAjaxSearch();
                }, 300);
            });
        }

        window.performAjaxSearch = function() {
            const currentSearchInput = document.querySelector('input[name="search"]');
            const currentSortSelect = document.querySelector('select[name="sort"]');
            const searchVal = currentSearchInput ? currentSearchInput.value : '';
            const sortVal = currentSortSelect ? currentSortSelect.value : 'newest';
            
            const url = new URL(window.location.origin + window.location.pathname);
            if (searchVal.trim() !== '') {
                url.searchParams.set('search', searchVal);
            }
            if (sortVal !== 'newest') {
                url.searchParams.set('sort', sortVal);
            }
            
            window.fetchAndUpdate(url.toString());
        }

        window.fetchAndUpdate = function(url) {
            const listContainer = document.querySelector('.engagement-list');
            if (listContainer) listContainer.style.opacity = '0.5';

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newList = doc.querySelector('.engagement-list');
                const currentList = document.querySelector('.engagement-list');
                if (newList && currentList) {
                    currentList.innerHTML = newList.innerHTML;
                    currentList.style.opacity = '1';
                }

                const newPagination = doc.querySelector('[style*="margin-top: 20px"]');
                const currentPagination = document.querySelector('[style*="margin-top: 20px"]');
                if (newPagination && currentPagination) {
                    currentPagination.innerHTML = newPagination.innerHTML;
                } else if (currentPagination) {
                    currentPagination.innerHTML = '';
                }

                const newResultsText = doc.querySelector('.card-header div[style*="font-size: 13px"]');
                const currentResultsText = document.querySelector('.card-header div[style*="font-size: 13px"]');
                if (newResultsText && currentResultsText) {
                    currentResultsText.innerHTML = newResultsText.innerHTML;
                }

                const newTotalLinksBox = doc.querySelector('.stat-box:nth-child(2) strong');
                const currentTotalLinksBox = document.querySelector('.stat-box:nth-child(2) strong');
                if (newTotalLinksBox && currentTotalLinksBox) {
                    currentTotalLinksBox.innerHTML = newTotalLinksBox.innerHTML;
                }

                const newSearchWrapper = doc.querySelector('input[name="search"]').parentElement;
                const currentSearchWrapper = document.querySelector('input[name="search"]').parentElement;
                if (newSearchWrapper && currentSearchWrapper) {
                    const clearBtn = currentSearchWrapper.querySelector('a');
                    const newClearBtn = newSearchWrapper.querySelector('a');
                    if (clearBtn && !newClearBtn) {
                        clearBtn.remove();
                    } else if (!clearBtn && newClearBtn) {
                        currentSearchWrapper.appendChild(newClearBtn);
                    }
                }

                const currentSortSelect2 = document.querySelector('select[name="sort"]');
                if (currentSortSelect2) {
                    const currentSort = currentSortSelect2.value || 'newest';
                    document.querySelectorAll('.segment-tab').forEach(t => {
                        if (t.dataset.sort === currentSort) {
                            t.classList.add('active');
                        } else {
                            t.classList.remove('active');
                        }
                    });
                }

                history.pushState(null, '', url);
            })
            .catch(err => {
                console.error('Ajax search failed:', err);
                if (listContainer) listContainer.style.opacity = '1';
            });
        }
    });
</script>
@endpush
