<style>
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 7%;
            background-color: transparent;
            position: fixed; /* Fixed so it follows scroll */
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .navbar.scrolled {
            padding: 1rem 7%;
            background: rgba(253, 251, 247, 0.7);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 40px;
            width: auto;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .logo img:hover {
            transform: scale(1.1) rotate(-2deg);
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #000000;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            transition: color 0.3s ease, transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }

        .nav-links a:hover {
            color: #0067D5;
            transform: translateY(-2px);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: -6px;
            left: 50%;
            background-color: #0067D5;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            border-radius: 2px;
            transform: translateX(-50%);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .login {
            color: #000000 !important;
            font-weight: 700;
            padding: 0.8rem 1.8rem;
            border-radius: 12px;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .login:hover {
            background-color: rgba(0,103,213,0.1);
            color: #0067D5 !important;
            transform: translateY(-4px);
        }

        .sign-up {
            background: rgba(237, 132, 44, 0.9);
            backdrop-filter: blur(10px);
            color: white !important;
            padding: 0.8rem 1.8rem;
            border-radius: 30px;
            font-weight: 700;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 4px 15px rgba(237, 132, 44, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .sign-up:hover {
            background: rgba(255, 49, 49, 0.95);
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 8px 25px rgba(255, 49, 49, 0.4);
        }
        
        .sign-up::after { display: none !important; }

        .burger {
            display: none;
            flex-direction: column;
            justify-content: center;
            cursor: pointer;
            width: 32px;
            height: 32px;
            z-index: 1100;
        }

        .burger span {
            height: 4px;
            width: 100%;
            background: #000000;
            margin: 4px 0;
            border-radius: 2px;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
                position: fixed;
                top: 0;
                right: 0;
                width: 80vw;
                max-width: 350px;
                background: rgba(253, 251, 247, 0.85);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                flex-direction: column;
                gap: 0;
                padding: 6rem 2rem 2rem;
                z-index: 1050;
                height: 100vh;
                border-left: 1px solid rgba(255,255,255,0.4);
                box-shadow: -10px 0 30px rgba(0,0,0,0.05);
                transition: transform 0.4s cubic-bezier(0.77, 0, 0.175, 1);
                transform: translateX(100%);
            }
            .nav-links.active {
                display: flex;
                transform: translateX(0);
            }
            .burger {
                display: flex;
            }
            .navbar {
                padding: 1rem 5%;
            }

            .nav-links a {
                padding: 1rem 0;
                font-size: 18px;
                color: #000000;
                font-weight: 600;
                border-bottom: 1px solid rgba(0,0,0,0.05);
                width: 100%;
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.4s ease;
            }

            .nav-links.active a {
                opacity: 1;
                transform: translateY(0);
            }
            
            /* Staggered animation for nav items on mobile */
            .nav-links.active a:nth-child(1) { transition-delay: 0.1s; }
            .nav-links.active a:nth-child(2) { transition-delay: 0.2s; }
            .nav-links.active a:nth-child(3) { transition-delay: 0.3s; }
            .nav-links.active a:nth-child(4) { transition-delay: 0.4s; }
            .nav-links.active a:nth-child(5) { transition-delay: 0.5s; }

            .nav-links a:last-child {
                border-bottom: none;
            }

            .nav-links a.sign-up {
                background: rgba(237, 132, 44, 0.9);
                color: white !important;
                padding: 1rem 1.5rem;
                border-radius: 30px;
                text-align: center;
                margin-top: 1.5rem;
                font-weight: 700;
                box-shadow: 0 4px 15px rgba(237, 132, 44, 0.3);
            }

            .nav-links a.login {
                color: #0067D5 !important;
                font-weight: 700;
                padding: 1rem 1.5rem;
                text-align: center;
            }
        }

        .burger.open span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 6px);
        }
        .burger.open span:nth-child(2) {
            opacity: 0;
            transform: scale(0);
        }
        .burger.open span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -6px);
        }

</style>
<nav class="navbar">
    <div class="logo">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/Logo.svg') }}" alt="Linkan Logo" id="logo"> 
        </a>
    </div>
    <div class="burger" id="burgerMenu">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="nav-links" id="navLinks">
        <a href="{{ route('pricing') }}">{{ __('layout.pricing') }}</a>
        <a href="{{ route('service') }}">{{ __('layout.service') }}</a>
        <a href="{{ route('FAQ') }}">{{ __('layout.faq') }}</a>
        <a href="{{ route('login') }}" class="login">{{ __('layout.sign_in') }}</a>
        <a href="{{ route('register') }}" class="sign-up">{{ __('layout.sign_up_free') }}</a>
    </div>
</nav>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const burger = document.getElementById('burgerMenu');
    const navLinks = document.getElementById('navLinks');
    
    burger.addEventListener('click', function() {
        navLinks.classList.toggle('active');
        burger.classList.toggle('open');
    });

    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', function() {
            if(window.innerWidth <= 768){
                navLinks.classList.remove('active');
                burger.classList.remove('open');
            }
        });
    });
});
</script>