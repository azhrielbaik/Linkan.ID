<nav id="navbar" class="navbar flex justify-between items-center px-[5%] md:px-[7%] py-4 md:py-6 bg-transparent fixed w-full top-0 z-[1000] transition-all duration-400 ease-[cubic-bezier(0.25,0.8,0.25,1)] [&.scrolled]:py-4 [&.scrolled]:bg-[#fdfbf7]/70 [&.scrolled]:backdrop-blur-[15px] [&.scrolled]:border-b [&.scrolled]:border-white/40 [&.scrolled]:shadow-[0_10px_30px_rgba(0,0,0,0.05)]">
    <div class="flex items-center">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/Logo.svg') }}" alt="Linkan Logo" id="logo" class="h-[40px] w-auto transition-transform duration-400 ease-[cubic-bezier(0.34,1.56,0.64,1)] hover:scale-110 hover:-rotate-2"> 
        </a>
    </div>
    
    <div id="burgerMenu" class="flex-col justify-center cursor-pointer w-[32px] h-[32px] z-[1100] hidden max-md:flex [&.open>span:nth-child(1)]:rotate-45 [&.open>span:nth-child(1)]:translate-x-[5px] [&.open>span:nth-child(1)]:translate-y-[6px] [&.open>span:nth-child(2)]:opacity-0 [&.open>span:nth-child(2)]:scale-0 [&.open>span:nth-child(3)]:-rotate-45 [&.open>span:nth-child(3)]:translate-x-[5px] [&.open>span:nth-child(3)]:-translate-y-[6px]">
        <span class="h-[4px] w-full bg-black my-1 rounded-sm transition-all duration-400 ease-[cubic-bezier(0.68,-0.55,0.265,1.55)]"></span>
        <span class="h-[4px] w-full bg-black my-1 rounded-sm transition-all duration-400 ease-[cubic-bezier(0.68,-0.55,0.265,1.55)]"></span>
        <span class="h-[4px] w-full bg-black my-1 rounded-sm transition-all duration-400 ease-[cubic-bezier(0.68,-0.55,0.265,1.55)]"></span>
    </div>
    
    <div id="navLinks" class="hidden md:flex flex-col md:flex-row items-center gap-0 md:gap-10 fixed md:static top-0 right-0 w-[80vw] max-w-[350px] md:w-auto h-screen md:h-auto bg-[#fdfbf7]/85 md:bg-transparent backdrop-blur-[20px] md:backdrop-blur-none px-8 pt-24 pb-8 md:p-0 z-[1050] border-l border-white/40 md:border-none shadow-[-10px_0_30px_rgba(0,0,0,0.05)] md:shadow-none transition-transform duration-400 ease-[cubic-bezier(0.77,0,0.175,1)] md:transition-none translate-x-full md:translate-x-0 [&.active]:translate-x-0 [&.active]:flex max-md:[&.active>a]:opacity-100 max-md:[&.active>a]:translate-y-0 max-md:[&.active>a:nth-child(1)]:delay-[100ms] max-md:[&.active>a:nth-child(2)]:delay-[200ms] max-md:[&.active>a:nth-child(3)]:delay-[300ms] max-md:[&.active>a:nth-child(4)]:delay-[400ms] max-md:[&.active>a:nth-child(5)]:delay-[500ms]">
        
        <a href="{{ route('pricing') }}" class="font-['Outfit',sans-serif] font-semibold text-black text-[18px] md:text-base no-underline transition-all duration-400 md:duration-300 ease-out md:ease-[cubic-bezier(0.34,1.56,0.64,1)] hover:text-[#0067D5] md:hover:-translate-y-[2px] relative after:content-[''] after:absolute after:w-0 after:h-[3px] after:-bottom-[6px] after:left-1/2 after:bg-[#0067D5] after:transition-all after:duration-400 after:ease-[cubic-bezier(0.34,1.56,0.64,1)] after:rounded-[2px] after:-translate-x-1/2 hover:after:w-full md:after:block max-md:after:hidden w-full md:w-auto py-4 md:py-0 border-b border-black/5 md:border-none max-md:opacity-0 max-md:translate-y-5">{{ __('layout.pricing') }}</a>
        
        <a href="{{ route('service') }}" class="font-['Outfit',sans-serif] font-semibold text-black text-[18px] md:text-base no-underline transition-all duration-400 md:duration-300 ease-out md:ease-[cubic-bezier(0.34,1.56,0.64,1)] hover:text-[#0067D5] md:hover:-translate-y-[2px] relative after:content-[''] after:absolute after:w-0 after:h-[3px] after:-bottom-[6px] after:left-1/2 after:bg-[#0067D5] after:transition-all after:duration-400 after:ease-[cubic-bezier(0.34,1.56,0.64,1)] after:rounded-[2px] after:-translate-x-1/2 hover:after:w-full md:after:block max-md:after:hidden w-full md:w-auto py-4 md:py-0 border-b border-black/5 md:border-none max-md:opacity-0 max-md:translate-y-5">{{ __('layout.service') }}</a>
        
        <a href="{{ route('FAQ') }}" class="font-['Outfit',sans-serif] font-semibold text-black text-[18px] md:text-base no-underline transition-all duration-400 md:duration-300 ease-out md:ease-[cubic-bezier(0.34,1.56,0.64,1)] hover:text-[#0067D5] md:hover:-translate-y-[2px] relative after:content-[''] after:absolute after:w-0 after:h-[3px] after:-bottom-[6px] after:left-1/2 after:bg-[#0067D5] after:transition-all after:duration-400 after:ease-[cubic-bezier(0.34,1.56,0.64,1)] after:rounded-[2px] after:-translate-x-1/2 hover:after:w-full md:after:block max-md:after:hidden w-full md:w-auto py-4 md:py-0 border-b border-black/5 md:border-none max-md:opacity-0 max-md:translate-y-5">{{ __('layout.faq') }}</a>
        
        <a href="{{ route('login') }}" class="max-md:!text-[#0067D5] md:!text-black font-bold py-4 md:py-[0.8rem] px-6 md:px-[1.8rem] rounded-xl text-center w-full md:w-auto transition-all duration-400 md:duration-400 ease-out md:ease-[cubic-bezier(0.34,1.56,0.64,1)] hover:bg-[#0067D5]/10 hover:!text-[#0067D5] md:hover:-translate-y-1 max-md:opacity-0 max-md:translate-y-5 no-underline">{{ __('layout.sign_in') }}</a>
        
        <a href="{{ route('register') }}" class="bg-[#ED842C]/90 backdrop-blur-[10px] !text-white font-bold py-4 md:py-[0.8rem] px-6 md:px-[1.8rem] rounded-[30px] text-center w-full md:w-auto mt-6 md:mt-0 shadow-[0_4px_15px_rgba(237,132,44,0.3)] border border-white/50 transition-all duration-400 md:duration-400 ease-out md:ease-[cubic-bezier(0.34,1.56,0.64,1)] hover:bg-[#FF3131]/95 md:hover:-translate-y-1 md:hover:scale-105 hover:shadow-[0_8px_25px_rgba(255,49,49,0.4)] max-md:opacity-0 max-md:translate-y-5 no-underline">{{ __('layout.sign_up_free') }}</a>
        
    </div>
</nav>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const burger = document.getElementById('burgerMenu');
    const navLinks = document.getElementById('navLinks');
    const navbar = document.querySelector('.navbar') || document.getElementById('navbar');
    
    if(burger && navLinks) {
        burger.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            burger.classList.toggle('open');
        });
    }

    document.querySelectorAll('#navLinks a').forEach(link => {
        link.addEventListener('click', function() {
            if(window.innerWidth <= 768){
                navLinks.classList.remove('active');
                if(burger) burger.classList.remove('open');
            }
        });
    });

    if (navbar) {
        window.addEventListener('scroll', function() {
            if(window.scrollY > 20) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }
});
</script>