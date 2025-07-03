<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Setting</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f6fa;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            margin-left: 250px;
        }

        .header {
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #333;
        }

        .account-detail, .delete-account {
            background: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 10px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .account-detail h2, .delete-account h2 {
            font-size: 18px;
            color: #333;
            margin-bottom: 15px;
        }

        .account-detail input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .delete-account button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-account button:hover {
            background-color: #e60000;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .form-group span {
            font-size: 12px;
            color: #999;
        }

        .btn-save {
            background-color: #FF9040; /* Warna oranye */
            color: white; /* Warna font putih */
            border: none;
            padding: 10px 60px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            display: block; /* Pastikan tombol berada di baris baru */
            margin-left: auto; /* Geser tombol ke kanan */
            margin-top: 20px; /* Tambahkan jarak dari elemen sebelumnya */
        }

        .btn-save:hover {
            background-color: #e67e22; /* Warna oranye lebih gelap saat hover */
        }

        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .popup-content {
            position: fixed; /* agar posisinya tetap saat scroll */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* untuk benar-benar menempatkan di tengah */
            
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 10px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000; /* pastikan tampil di atas elemen lain */
        }


        .popup-buttons button {
            margin: 5px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-cancel {
            background-color: #ccc;
            color: #333;
        }

        .btn-cancel:hover {
            background-color: #bbb;
        }

        .btn-confirm {
            background-color: #FF9040;
            color: white;
        }

        .btn-confirm:hover {
            background-color: #e67e22;
        }
        .header a {
            color: black !important;
            text-decoration: none; /* kalau mau hilangkan garis bawah juga */
        }

        @media (max-width: 900px) {
            .main-content {
                margin-left: 0;
            }
        }

    </style>
</head>
<body>
    
    <div class="container">
        @include('homeadminS.sidebar.sidebar')

        <div class="main-content">
            <div class="header">
                <h1><a href="{{ route('settings') }}">Settings</a> &gt; <span>Account Settings</h1>
            </div>

            @if(session('success'))
                <div style="color: green; margin-bottom: 15px;">
                    {{ session('success') }}
                </div>
            @endif

<!-- Form Account Detail -->
            <div class="account-detail">
                <h2>Account Detail</h2>
                <form id="accountForm" action="{{ route('account.update') }}" method="POST">
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
                        <form action="{{ route('account.delete') }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-confirm">Ya, Hapus Akun</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
</body>
</html>