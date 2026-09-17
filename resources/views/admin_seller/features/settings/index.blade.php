@extends("admin_seller.layouts.settings")

@section("settings_content")
    <!-- Main Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8">
            <div class="mb-8">
                <h2 class="text-xl font-bold text-slate-900">Profile</h2>
                <p class="text-sm text-slate-500 mt-1">Update your photo and personal details here.</p>
            </div>

            <form action="{{ route('admin.account.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Profile Picture -->
                <div class="flex flex-wrap sm:flex-nowrap items-center gap-6 mb-10">
                    <div class="relative w-24 h-24 rounded-2xl overflow-hidden bg-slate-100 flex items-center justify-center border border-slate-200 shrink-0">
                        @if($user->avatar)
                            <img id="avatar-preview" src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            <span id="avatar-initial" class="text-3xl font-bold text-slate-400 hidden">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        @else
                            <img id="avatar-preview" src="" alt="Avatar" class="w-full h-full object-cover hidden">
                            <span id="avatar-initial" class="text-3xl font-bold text-slate-400">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 mb-2">Profile Picture</h3>
                        <div class="flex items-center gap-3 mb-2 flex-wrap">
                            <label class="px-4 py-2 bg-[#ED842C] hover:bg-[#d07323] text-white text-sm font-semibold rounded-lg cursor-pointer transition flex items-center gap-2">
                                <i class="fas fa-upload"></i> Upload Image
                                <input type="file" name="avatar" class="hidden" accept="image/png, image/jpeg, image/gif" onchange="previewImage(this)">
                            </label>
                            <button type="button" class="px-4 py-2 border border-slate-200 text-slate-600 text-sm font-semibold rounded-lg hover:bg-slate-50 transition flex items-center gap-2" onclick="removeAvatar()">
                                <i class="far fa-trash-alt"></i> Remove
                            </button>
                            <input type="hidden" name="remove_avatar" id="remove_avatar" value="0">
                        </div>
                        <p class="text-xs text-slate-500">We support PNGs, JPEGs and GIFs under 2MB</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <!-- Full Name -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-[#ED842C] focus:border-[#ED842C] outline-none transition text-sm text-slate-800 font-medium" required>
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-[#ED842C] focus:border-[#ED842C] outline-none transition text-sm text-slate-800 font-medium" required>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-3 rounded-lg border border-slate-200 bg-slate-50 text-slate-500 text-sm font-medium focus:outline-none" readonly>
                    </div>

                    <!-- Bio -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Bio</label>
                        <input type="text" name="bio" value="{{ old('bio', $user->bio) }}" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-[#ED842C] focus:border-[#ED842C] outline-none transition text-sm text-slate-800 font-medium" placeholder="E.g., Marketing Manager, Content Creator">
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <button type="submit" class="px-6 py-2.5 bg-[#ED842C] hover:bg-[#d07323] text-white text-sm font-semibold rounded-lg transition shadow-sm">
                        Save Changes
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 border border-slate-200 text-slate-700 hover:bg-slate-50 text-sm font-semibold rounded-lg transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Account Preferences Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mt-8">
        <div class="p-8">
            <div class="mb-8">
                <h2 class="text-xl font-bold text-slate-900">{{ __('admin.account_preference', ['default' => 'Account Preferences']) }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ __('admin.account_preference_subtitle', ['default' => 'Manage your region and language settings']) }}</p>
            </div>

            <div class="space-y-6">
                <!-- Language -->
                <div class="flex items-center justify-between py-4 border-b border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-500">
                            <i class="fas fa-language text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">{{ __('admin.language', ['default' => 'Language']) }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">{{ __('admin.language_subtitle', ['default' => 'Choose your preferred language']) }}</p>
                        </div>
                    </div>
                    <div>
                        <select onchange="window.location.href=this.value" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-medium text-slate-700 outline-none focus:ring-2 focus:ring-[#ED842C] focus:border-[#ED842C] transition appearance-none pr-10 relative cursor-pointer" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%234a5568%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 12px top 50%; background-size: 10px auto;">
                            <option value="{{ route('lang.switch', 'en') }}" {{ App::getLocale() == 'en' ? 'selected' : '' }}>English</option>
                            <option value="{{ route('lang.switch', 'id') }}" {{ App::getLocale() == 'id' ? 'selected' : '' }}>Indonesia</option>
                        </select>
                    </div>
                </div>

                <!-- Country -->
                <div class="flex items-center justify-between py-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-500">
                            <i class="fas fa-globe-americas text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">{{ __('admin.country', ['default' => 'Country']) }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">{{ __('admin.country_subtitle', ['default' => 'Select your region']) }}</p>
                        </div>
                    </div>
                    <div>
                        <select class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-medium text-slate-700 outline-none focus:ring-2 focus:ring-[#ED842C] focus:border-[#ED842C] transition appearance-none pr-10 relative cursor-pointer" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%234a5568%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 12px top 50%; background-size: 10px auto;">
                            <option value="default" selected>Default</option>
                            <option value="id">Indonesia</option>
                            <option value="us">United States</option>
                            <option value="sg">Singapore</option>
                            <option value="my">Malaysia</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
                document.getElementById('avatar-preview').classList.remove('hidden');
                
                const initial = document.getElementById('avatar-initial');
                if(initial) initial.classList.add('hidden');
                
                document.getElementById('remove_avatar').value = '0';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeAvatar() {
        document.getElementById('avatar-preview').src = '';
        document.getElementById('avatar-preview').classList.add('hidden');
        
        const initial = document.getElementById('avatar-initial');
        if(initial) initial.classList.remove('hidden');
        
        document.getElementById('remove_avatar').value = '1';
        
        // Clear file input
        const fileInput = document.querySelector('input[type="file"]');
        if(fileInput) fileInput.value = '';
    }
</script>
@endpush
