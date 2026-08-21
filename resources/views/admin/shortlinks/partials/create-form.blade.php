<!-- RIGHT COLUMN -->
<div class="right-col mobile-form-collapse">
    <div class="mobile-form-collapse-header" onclick="this.parentElement.classList.toggle('is-open')">
        <span><i class="fas fa-plus-circle icon-orange icon-mr-8"></i> {{ __('shortlink.create_new_link') }}</span>
        <i class="fas fa-chevron-down icon-gray"></i>
    </div>
    <div class="mobile-form-collapse-body">
        <form action="{{ route('admin.shortlinks.store') }}" method="POST">
        @csrf
        
        <!-- CREATE NEW LINK -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">{{ __('shortlink.create_new_link') }} <i class="fas fa-link icon-gray icon-ml-8"></i></div>
            </div>
            <p class="create-form-desc">{{ __('shortlink.create_desc') }}</p>
            
            <div class="input-row">
                <input type="url" name="destination" placeholder="https://example.com/your-long-url" required value="{{ old('destination') }}">
                <button type="submit" class="btn-submit">{{ __('shortlink.btn_create') }} <i class="fas fa-arrow-right"></i></button>
            </div>
            @error('destination') <div class="error-msg-top">{{ $message }}</div> @enderror
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
                    <button type="button" onclick="generateRandomSlug()" class="btn-random-slug"><i class="fas fa-random"></i></button>
                </div>
                <div class="card-subtitle" style="margin-top: 6px;">{{ __('shortlink.slug_hint') }}</div>
                @error('slug') <div class="error-msg-standard">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>{{ __('shortlink.title_label') }}</label>
                <input type="text" name="title" placeholder="{{ __('shortlink.title_placeholder') }}" value="{{ old('title') }}">
                @error('title') <div class="error-msg-standard">{{ $message }}</div> @enderror
            </div>

            <div class="form-group mb-0">
                <label>{{ __('shortlink.desc_label') }}</label>
                <input type="text" name="description" placeholder="{{ __('shortlink.desc_placeholder') }}" value="{{ old('description') }}">
                @error('description') <div class="error-msg-standard">{{ $message }}</div> @enderror
            </div>
        </div>
    </form>
    </div>
</div>
