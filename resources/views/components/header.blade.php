<header class="bg-white h-16 shadow-sm flex items-center justify-between px-4 lg:px-8 z-30 sticky top-0 w-full border-b border-gray-100">
    <!-- Left: Hamburger Menu (Mobile) & Header Title -->
    <div class="flex items-center gap-4">
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
        <!-- Center Logo (Visible only on desktop/tablet usually) -->
        <div class="hidden md:flex items-center gap-2">
            <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Halo MANAP" class="w-8 h-8 object-contain">
            <div class="flex flex-col">
                <span class="font-bold text-lg text-blue-800 leading-tight">Halo MANAP</span>
                <span class="text-[10px] text-gray-500 leading-tight">Pusat Pengaduan, Aspirasi dan Informasi Pelayanan<br>RSUD H. Abdul Manap Kota Jambi</span>
            </div>
        </div>
    </div>

    <!-- Right: Notifications & Profile -->
    <div class="flex items-center gap-4">
        <!-- Notifications -->
        <button class="relative text-gray-500 hover:text-blue-600 transition-colors">
            <i class="fa-regular fa-bell text-xl"></i>
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center border border-white">12</span>
        </button>

        <!-- User Profile Dropdown -->
        <div class="relative" id="profileDropdown">
            <button onclick="toggleProfileMenu()"
                class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 px-2 py-1.5 rounded-xl transition-colors">
                <div class="hidden md:block text-right">
                    <p class="text-sm font-semibold text-gray-700 leading-tight">Admin Pengaduan</p>
                    <p class="text-xs text-gray-400 leading-tight">Super Admin</p>
                </div>
                <img src="https://ui-avatars.com/api/?name=Admin+Pengaduan&background=eff6ff&color=1e3a8a"
                    alt="Profile" class="w-9 h-9 rounded-full border border-gray-200">
                <i class="fa-solid fa-chevron-down text-gray-400 text-xs hidden md:block" id="profileChevron"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="profileMenu"
                class="hidden absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-50">
                <div class="px-4 py-2.5 border-b border-gray-100">
                    <p class="text-sm font-semibold text-gray-800">Admin Pengaduan</p>
                    <p class="text-xs text-gray-400 truncate">admin@halomanap.com</p>
                </div>
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                    <i class="fa-solid fa-user-gear w-4 text-center text-gray-400"></i> Profil & Pengaturan
                </a>
                <div class="border-t border-gray-100 mt-1 pt-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                            <i class="fa-solid fa-right-from-bracket w-4 text-center"></i> Keluar / Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        if (sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    }

    function toggleProfileMenu() {
        const menu     = document.getElementById('profileMenu');
        const chevron  = document.getElementById('profileChevron');
        const isHidden = menu.classList.contains('hidden');
        menu.classList.toggle('hidden', !isHidden);
        chevron.style.transform = isHidden ? 'rotate(180deg)' : '';
        chevron.style.transition = 'transform 0.2s';
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
        const dropdown = document.getElementById('profileDropdown');
        const menu     = document.getElementById('profileMenu');
        if (dropdown && menu && !dropdown.contains(e.target)) {
            menu.classList.add('hidden');
            const chevron = document.getElementById('profileChevron');
            if (chevron) chevron.style.transform = '';
        }
    });
</script>
