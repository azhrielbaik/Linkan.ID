@extends("layouts.admin")

@section("page_title", "Shortlink")

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/shortlink-create.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="dashboard-wrapper">






    <div class="dashboard-grid">
        <!-- LEFT COLUMN -->
        <div class="left-col">
            @include('admin.shortlinks.partials.stat-chart')
            @include('admin.shortlinks.partials.search-filter')

        </div>

        @include('admin.shortlinks.partials.create-form')
    </div>

            @include('admin.shortlinks.partials.list-items')
</div>

<!-- PANEL OVERLAY -->
<div id="sl-overlay"></div>

<!-- SLIDE OUT PANEL -->
@include('admin.shortlinks.partials.side-panel')

@endsection

@push("scripts")
<script>
    window.ShortlinkConfig = {
        translations: {
            copied: "{{ __('shortlink.copied') }}",
            password_protected: "{{ __('shortlink.password_protected') }}",
            pub_link: "{{ __('shortlink.pub_link') }}",
            expired_label: "{{ __('shortlink.expired_label') }}",
            no_time_limit: "{{ __('shortlink.no_time_limit') }}",
            desc_placeholder: "{{ __('shortlink.desc_placeholder') }}"
        },
        routes: {
            updateBase: "{{ url('/admin/shortlinks') }}"
        }
    };
</script>
<script src="{{ asset('js/pages/shortlink.js') }}"></script>
@endpush
