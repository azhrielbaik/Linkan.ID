
<footer class="footer">
        <div class="footer-container">
            <img src="{{ asset('images/logotext.png') }}" alt="Linkan Logo" class="footer-logo">
            <nav class="footer-nav">
                <a href="{{ route('about') }}">{{ __('layout.about_us') }}</a>
                <a href="{{ route('contact.form') }}">{{ __('layout.contact_us') }}</a>
            </nav>
        </div>
    </footer>

    <style>
.footer {
    background-color:rgb(245, 245, 245);
    padding: 20px 0;
    border-top: 4px solid #e0e0e0; /* Garis atas */
}

.footer-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1100px;
    margin: auto;
    padding: 0 20px;
}

.footer-logo {
    height: 80px; /* Sesuaikan ukuran logo */
}

.footer-nav {
    display: flex;
    gap: 30px; /* Jarak antar teks */
}

.footer-nav a {
    text-decoration: none;
    color: #666; /* Warna abu-abu */
    font-size: 16px;
    transition: color 0.3s;
}

.footer-nav a:hover {
    color: #ff7733; /* Warna hover */
}

</style>
