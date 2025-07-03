<style>

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #e0e7ff;
            min-height: 100vh;
            padding: 20px;
        }

        .sidebar .logo {
            width: 120px;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #1a1a1a;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #d1d9ff;
        }

        .sidebar a i {
            margin-right: 10px;
            width: 20px;
        }

        .sidebar hr {
            border: none;
            border-top: 3px solid #000;
            margin: 15px 0;
        }
/* Tambahkan ini ke dalam tag <style> di bagian atas */
    .sidebar a.active {
    background-color: #FF9040;
    color: white;
}

</style>
<div class="sidebar">
    <img src="{{ asset('images/logo.png') }}" alt="Linkan Logo" class="logo">
    <a href="{{ route('beranda.platformadmin') }}" class="{{ request()->routeIs('beranda.platformadmin') ? 'active' : '' }}">
        <i class="fas fa-home"></i>Home
    </a>
    <a href="{{ route('verifikasi.platformadmin') }}" class="{{ request()->routeIs('verifikasi.platformadmin') ? 'active' : '' }}">
        <i class="fas fa-check-circle"></i> Verification
    </a>
    <hr>

    <div class="marketing-tools">
        <a href="{{ route('welcome') }}">
            <span style="display: flex; align-items: center;">
                <img src="{{ asset('images/logout.png') }}" alt="Logout" style="width: 20px; height: 20px; margin-right: 10px;">
                LogOut
            </span>
        </a>
    </div>
</div>

