<footer class="bg-[#f5f5f5] py-5 border-t-[4px] border-[#e0e0e0]">
    <div class="flex justify-between items-center max-w-[1100px] mx-auto px-5">
        <img src="{{ asset('images/logotext.png') }}" alt="Linkan Logo" class="h-[80px]">
        <nav class="flex gap-[30px]">
            <a href="{{ route('about') }}" class="no-underline text-[#666] text-[16px] transition-colors duration-300 hover:text-[#ff7733]">{{ __('layout.about_us') }}</a>
            <a href="{{ route('contact.form') }}" class="no-underline text-[#666] text-[16px] transition-colors duration-300 hover:text-[#ff7733]">{{ __('layout.contact_us') }}</a>
        </nav>
    </div>
</footer>
