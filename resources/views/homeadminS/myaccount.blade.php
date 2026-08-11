@extends("layouts.admin")

@section("page_title", "My Account")

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
                <h2>Account Detail</h2>
                <form id="accountForm" action="{{ route('admin.account.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" readonly style="color: gray;">
                    </div>
                    <div class="form-group">
                        <label for="name">Name :</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}">
                    </div>    
                    <div class="form-group">
                        <label for="password">Password :</label>
                        <input type="password" id="password" name="password" placeholder="Enter new password" minlength="8" required>
                        <span style="color: #666; font-size: 12px;">Password minimal 8 karakter</span>
                        @error('password')
                            <span style="color: red; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div> 
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password :</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password baru" minlength="8" required>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn-save" onclick="showPopup()">Save</button>
                    </div>
                </form>
            </div>

            <!-- Popup Confirmation -->
            <div id="confirmationPopup" class="popup-overlay" style="display: none;">
                <div class="popup-content">
                    <h3>Apakah Anda Yakin untuk Mengubah?</h3>
                    <div class="popup-buttons">
                        <button class="btn-cancel" onclick="closePopup()">Cancel</button>
                        <button class="btn-confirm" onclick="submitForm()">Confirm</button>
                    </div>
                </div>
            </div>

            <div class="delete-account">
                <h2>Delete Account</h2>
                <p>Click the button below if you want to delete your account</p><br> 
                <button onclick="showDeletePopup()">Delete Account</button>
            </div> 

            <!-- Delete Account Popup -->
            <div id="deleteConfirmationPopup" class="popup-overlay" style="display: none;">
                <div class="popup-content">
                    <h3>Apakah Anda Yakin Ingin Menghapus Akun?</h3>
                    <p style="color: red; margin: 10px 0;">Tindakan ini tidak dapat dibatalkan!</p>
                    <div class="popup-buttons">
                        <button class="btn-cancel" onclick="closeDeletePopup()">Cancel</button>
                        <form action="{{ route('admin.account.delete') }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-confirm">Ya, Hapus Akun</button>
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
