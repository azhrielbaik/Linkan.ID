@extends("admin_seller.layouts.app")

@section("page_title", __('admin.my_account_title'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/myaccount.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="dashboard-account-page">

    @if(session('success'))
        <div class="account-alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="account-alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- CARD 1: ACCOUNT DETAIL -->
    <div class="account-blue-card">
        <div class="account-card-header">
            <h2 class="card-hero-title">{{ __('admin.account_detail') }}</h2>
            <p class="card-hero-subtitle">{{ __('admin.account_detail_subtitle') }}</p>
        </div>

        <div class="account-profile-box">
            <div class="profile-avatar-initials">
                @php
                    $words = explode(' ', trim($user->name));
                    $initials = '';
                    if (count($words) >= 2) {
                        $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                    } else {
                        $initials = strtoupper(substr($user->name, 0, 2));
                    }
                @endphp
                {{ $initials }}
            </div>

            <div class="profile-info-content">
                <div class="profile-name-row">
                    <h3 class="profile-full-name">{{ $user->name }}</h3>
                    <div class="profile-action-buttons">
                        <button type="button" class="btn-profile-edit" onclick="openEditModal()">
                            <i class="far fa-edit"></i> {{ __('admin.edit') }}
                        </button>
                        <button type="button" class="btn-profile-delete" onclick="showDeletePopup()">
                            {{ __('admin.delete') }}
                        </button>
                    </div>
                </div>

                <div class="profile-status-badge">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ __('admin.active') }}</span>
                </div>

                <div class="profile-email-badge">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:{{ $user->email }}" class="profile-email-link">{{ $user->email }}</a>
                </div>
            </div>
        </div>

        <div class="account-active-since-box">
            <div class="active-since-label">{{ __('admin.account_active_since') }}</div>
            <div class="active-since-date">
                @php
                    $createdAt = $user->created_at ?? now();
                    $formattedDate = $createdAt->format('d F Y');
                    $diffHumans = $createdAt->diffForHumans(null, true);
                @endphp
                {{ $formattedDate }} ( {{ $diffHumans }} )
            </div>
        </div>
    </div>

    <!-- CARD 2: ACCOUNT PREFERENCE -->
    <div class="account-blue-card">
        <div class="account-card-header">
            <h2 class="card-hero-title">{{ __('admin.account_preference') }}</h2>
            <p class="card-hero-subtitle">{{ __('admin.account_preference_subtitle') }}</p>
        </div>

        <div class="preference-list">
            <!-- LANGUAGE ITEM -->
            <div class="preference-item">
                <div class="preference-left">
                    <div class="preference-icon-box">
                        <i class="fas fa-language"></i>
                    </div>
                    <div class="preference-text">
                        <h4 class="pref-title">{{ __('admin.language') }}</h4>
                        <p class="pref-subtitle">{{ __('admin.language_subtitle') }}</p>
                    </div>
                </div>
                <div class="preference-right">
                    <div class="pref-dropdown-pill">
                        <select onchange="window.location.href=this.value" class="pref-select">
                            <option value="{{ route('lang.switch', 'en') }}" {{ App::getLocale() == 'en' ? 'selected' : '' }}>English</option>
                            <option value="{{ route('lang.switch', 'id') }}" {{ App::getLocale() == 'id' ? 'selected' : '' }}>Indonesia</option>
                        </select>
                        <i class="fas fa-caret-down pill-arrow"></i>
                    </div>
                </div>
            </div>

            <!-- COUNTRY ITEM -->
            <div class="preference-item">
                <div class="preference-left">
                    <div class="preference-icon-box">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                    <div class="preference-text">
                        <h4 class="pref-title">{{ __('admin.country') }}</h4>
                        <p class="pref-subtitle">{{ __('admin.country_subtitle') }}</p>
                    </div>
                </div>
                <div class="preference-right">
                    <div class="pref-dropdown-pill">
                        <select class="pref-select">
                            <option value="default" selected>Default</option>
                            <option value="id">Indonesia</option>
                            <option value="us">United States</option>
                            <option value="sg">Singapore</option>
                            <option value="my">Malaysia</option>
                        </select>
                        <i class="fas fa-caret-down pill-arrow"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT ACCOUNT -->
    <div id="editAccountModal" class="account-modal-overlay" style="display: none;">
        <div class="account-modal-card">
            <div class="account-modal-header">
                <h3><i class="fas fa-user-edit" style="color: #5A5BF1; margin-right: 8px;"></i> {{ __('admin.edit_account_detail') }}</h3>
                <button type="button" class="modal-close-icon" onclick="closeEditModal()">&times;</button>
            </div>
            
            <form id="accountForm" action="{{ route('admin.account.update') }}" method="POST">
                @csrf
                <div class="account-form-body">
                    <div class="account-form-group">
                        <label for="name">{{ __('admin.full_name') }}</label>
                        <input type="text" id="name" name="name" class="account-form-input" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="account-form-group">
                        <label for="username">{{ __('admin.username') }}</label>
                        <input type="text" id="username" name="username" class="account-form-input" value="{{ old('username', $user->username) }}" required>
                    </div>

                    <div class="account-form-group">
                        <label for="email">{{ __('admin.email_address') }}</label>
                        <input type="email" id="email" name="email" class="account-form-input input-readonly" value="{{ old('email', $user->email) }}" readonly>
                    </div>

                    <div class="account-form-group">
                        <label for="password">{{ __('admin.new_password') }} <span class="label-optional">({{ __('admin.optional') }})</span></label>
                        <input type="password" id="password" name="password" class="account-form-input" placeholder="{{ __('admin.password_placeholder') }}" minlength="8">
                    </div>

                    <div class="account-form-group">
                        <label for="password_confirmation">{{ __('admin.confirm_password') }}</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="account-form-input" placeholder="{{ __('admin.confirm_new_password_placeholder') }}" minlength="8">
                    </div>
                </div>

                <div class="account-modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn-modal-save">{{ __('admin.save_changes') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DELETE ACCOUNT -->
    <div id="deleteConfirmationPopup" class="account-modal-overlay" style="display: none;">
        <div class="account-modal-card modal-delete-card">
            <div class="delete-icon-circle">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3>{{ __('admin.delete_account') }}</h3>
            <p class="delete-warning-text">{{ __('admin.delete_account_warning') }}</p>
            
            <div class="delete-modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeDeletePopup()">{{ __('admin.cancel') }}</button>
                <form action="{{ route('admin.account.delete') }}" method="POST" style="display: inline; flex: 1;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modal-danger">{{ __('admin.yes_delete_account') }}</button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push("scripts")
<script>
    function openEditModal() {
        document.getElementById('editAccountModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editAccountModal').style.display = 'none';
    }

    function showDeletePopup() {
        document.getElementById('deleteConfirmationPopup').style.display = 'flex';
    }

    function closeDeletePopup() {
        document.getElementById('deleteConfirmationPopup').style.display = 'none';
    }

    // Close on overlay click
    window.addEventListener('click', function(event) {
        const editModal = document.getElementById('editAccountModal');
        const deleteModal = document.getElementById('deleteConfirmationPopup');
        if (event.target === editModal) {
            closeEditModal();
        }
        if (event.target === deleteModal) {
            closeDeletePopup();
        }
    });
</script>
@endpush
