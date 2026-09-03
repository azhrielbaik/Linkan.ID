<!-- ENGAGEMENT ALL TIME -->
<div class="card engagement-card">
    <div class="card-header desktop-only-card-header">
        <div class="card-title">{{ __('shortlink.engagement') }}</div>
        <div class="card-subtitle">{{ $shortlinks->total() }} {{ __('shortlink.results') }}</div>
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
                <a href="{{ url('/' . $link->slug) }}" target="_blank" class="eng-link">Linkan.id/{{ $link->slug }}</a>
                <p>{{ Str::limit($link->destination, 60) }}</p>
                @if($link->description)
                <p class="eng-desc">{{ Str::limit($link->description, 60) }}</p>
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
        <div class="empty-state-container">
            <i class="fas fa-link empty-state-icon"></i>
            <p>{{ __('shortlink.no_shortlink') }}</p>
        </div>
        @endforelse
    </div>
    
    <div class="mt-20">
        {{ $shortlinks->appends(request()->except('page'))->links() }}
    </div>
</div>
