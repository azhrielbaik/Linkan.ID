    <!-- COMBINED HEADER & MODE SWITCH -->
    <div class="section-header microsite-main-header">
        @include('admin_seller.features.mylinkan.partials._gallery_header')
    </div>

        <!-- GALLERY LIST VIEW -->

        <div class="microsite-gallery-grid">
            
            @if(isset($appearances) && $appearances->count() > 0)
                @foreach($appearances as $appearance)
                <!-- MAIN MICROSITE CARD WITH PHONE SCREENSHOT THUMBNAIL -->
                <div class="microsite-card">
                    
                    <!-- CARD HEADER & THUMBNAIL CONTAINER -->
                    <div class="card-thumbnail-container">

                        <!-- REAL PHONE THUMBNAIL REPRESENTATION -->
                        <div class="phone-thumbnail">
                            <div class="phone-thumbnail-screen" style="
                                background-image: url('{{ $appearance->background_color ? asset('images/background/' . $appearance->background_color) : '' }}');
                                background-color: {{ $appearance->background_color ? 'transparent' : '#f8f9fa' }};
                            ">
                                <!-- Banner -->
                                <div class="phone-thumb-banner">
                                    @if($appearance->banner)
                                        <img src="{{ asset('storage/' . $appearance->banner) }}" alt="Banner">
                                    @endif
                                </div>

                                <!-- Avatar -->
                                <div class="phone-thumb-avatar">
                                    @if($appearance->profile_image)
                                        <img src="{{ asset('storage/' . $appearance->profile_image) }}" alt="Avatar">
                                    @else
                                        <i class="fas fa-user" style="color: #888; font-size: 16px;"></i>
                                    @endif
                                </div>

                                <!-- Name & Bio -->
                                <div class="phone-thumb-name" style="color: {{ $appearance->theme_color ?? '#FF9040' }}">
                                    {!! $appearance->name !!}
                                </div>
                                <div class="phone-thumb-bio" style="color: {{ $appearance->theme_color ?? '#666' }}">
                                    {!! strip_tags($appearance->bio ?? 'Selamat datang di linkan saya!') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD BODY DETAILS -->
                    <div class="card-body-details">
                        <div class="card-title-row">
                            <h3 class="microsite-name">{!! $appearance->title ?? $appearance->name !!}</h3>
                        </div>

                        <a href="{{ url('/' . $appearance->alias) }}" target="_blank" class="url-pill">
                            <i class="fas fa-globe"></i> linkan.id/{{ $appearance->alias }}
                        </a>

                        <div class="card-stats-tags mt-3">
                            <span class="stat-tag"><i class="fas fa-eye"></i> {{ number_format($viewsData[$appearance->alias] ?? 0) }} views</span>
                            <span class="stat-tag"><i class="fas fa-cube"></i> {{ $digitalProducts ? $digitalProducts->count() : 0 }} {{ __('admin.product') }}</span>
                        </div>

                        <!-- ACTION BUTTONS -->
                        <div class="card-actions-grid mt-4">
                            <a href="{{ url('/' . $appearance->alias) }}" target="_blank" class="btn-action-secondary">
                                <i class="fa-solid fa-arrow-up-from-ground-water"></i> Kunjungi
                            </a>
                            <a href="{{ route('admin.mylinkan', ['mode' => 'edit', 'id' => $appearance->id]) }}" class="btn-action-primary">
                                <i class="fas fa-pen"></i> {{ __('admin.edit_block') }}
                            </a>

                            <button type="button" class="btn-action-secondary" onclick="copyToClipboard('{{ url('/' . $appearance->alias) }}')">
                                <i class="fas fa-copy"></i> {{ __('admin.copy_link') }}
                            </button>
                        </div>
                    </div>

                </div>
                @endforeach
            @else
                <div class="text-center p-8 bg-white rounded-xl border border-dashed border-gray-300 col-span-full">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-layer-group text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Microsite</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Anda belum membuat microsite apapun. Klik tombol "Buat Microsite" di pojok kanan atas untuk mulai membangun halaman profil dan tautan Anda.</p>
                </div>
            @endif

        </div>
