@extends("layouts.admin")

@section("page_title", __('admin.my_account_title'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/myaccount.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="dashboard-myaccount-page">



            @if(session('success'))
                <div style="color: green; margin-bottom: 15px;">
                    {{ session('success') }}
                </div>
            @endif

<!-- Form Account Detail -->
            <div class="account-detail">
                <h2>{{ __('admin.account_detail') }}</h2>
                <form id="accountForm" action="{{ route('admin.account.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="username">{{ __('admin.username') }}</label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}">
                    </div>
                    <div class="form-group">
                        <label for="email">{{ __('admin.email') }}</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" readonly style="color: gray;">
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('admin.name') }}</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}">
                    </div>    
                    <div class="form-group">
                        <label for="password">{{ __('admin.password') }}</label>
                        <input type="password" id="password" name="password" placeholder="{{ __('admin.enter_new_password') }}" minlength="8" required>
                        <span style="color: #666; font-size: 12px;">{{ __('admin.password_min_8') }}</span>
                        @error('password')
                            <span style="color: red; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div> 
                    <div class="form-group">
                        <label for="password_confirmation">{{ __('admin.confirm_password') }}</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="{{ __('admin.confirm_new_password') }}" minlength="8" required>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn-save" onclick="showPopup()">{{ __('admin.save') }}</button>
                    </div>
                </form>
            </div>

            <!-- Popup Confirmation -->
            <div id="confirmationPopup" class="popup-overlay" style="display: none;">
                <div class="popup-content">
                    <h3>{{ __('admin.confirm_change') }}</h3>
                    <div class="popup-buttons">
                        <button class="btn-cancel" onclick="closePopup()">{{ __('admin.cancel') }}</button>
                        <button class="btn-confirm" onclick="submitForm()">{{ __('admin.confirm') }}</button>
                    </div>
                </div>
            </div>

            <div class="delete-account">
                <h2>{{ __('admin.delete_account') }}</h2>
                <p>{{ __('admin.delete_account_desc') }}</p><br> 
                <button onclick="showDeletePopup()">{{ __('admin.delete_account') }}</button>
            </div> 

            <!-- Delete Account Popup -->
            <div id="deleteConfirmationPopup" class="popup-overlay" style="display: none;">
                <div class="popup-content">
                    <h3>{{ __('admin.confirm_delete_account') }}</h3>
                    <p style="color: red; margin: 10px 0;">{{ __('admin.action_cannot_be_undone') }}</p>
                    <div class="popup-buttons">
                        <button class="btn-cancel" onclick="closeDeletePopup()">{{ __('admin.cancel') }}</button>
                        <form action="{{ route('admin.account.delete') }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-confirm">{{ __('admin.yes_delete_account') }}</button>
                        </form>
                    </div>
                </div>
            </div>

</div>
@endsection

@push("scripts")
<script>
// Show the popup
        function showPopup() {
            document.getElementById('confirmationPopup').style.display = 'block';
        }

        // Close the popup
        function closePopup() {
            document.getElementById('confirmationPopup').style.display = 'none';
        }

        // Submit the form
        function submitForm() {
            document.getElementById('accountForm').submit();
        }

        // Show delete confirmation popup
        function showDeletePopup() {
            document.getElementById('deleteConfirmationPopup').style.display = 'block';
        }

        // Close delete confirmation popup
        function closeDeletePopup() {
            document.getElementById('deleteConfirmationPopup').style.display = 'none';
        }
</script>
@endpush
