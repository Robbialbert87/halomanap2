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
        <div class="relative" id="notifDropdown">
            <button onclick="toggleNotifMenu()"
                class="relative text-gray-500 hover:text-blue-600 transition-colors">
                <i class="fa-regular fa-bell text-xl"></i>
                @if($unreadCount > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center border border-white">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                @endif
            </button>
            <div id="notifMenu"
                class="hidden absolute right-0 top-full mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-100 z-50">
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                    <p class="text-sm font-semibold text-gray-800">Notifikasi</p>
                    @if($unreadCount > 0)
                    <span class="text-xs bg-blue-100 text-blue-700 font-semibold px-2 py-0.5 rounded-full">{{ $unreadCount }} baru</span>
                    @endif
                </div>
                <div class="max-h-72 overflow-y-auto">
                    @forelse($notifications as $notif)
                    <a href="{{ $notif['url'] ?? route('admin.tickets.show', $notif['id']) }}"
                        data-ticket-id="{{ $notif['id'] }}"
                        class="notif-link flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                        @if($notif['notif_type'] === 'selesai')
                        <div class="w-5 h-5 mt-0.5 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-check text-[10px] text-green-600"></i>
                        </div>
                        @else
                        <div class="w-2 h-2 mt-1.5 rounded-full bg-blue-500 shrink-0"></div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $notif['title'] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                <span class="font-medium text-blue-600">{{ $notif['ticket_number'] }}</span>
                                @if($notif['category']) · {{ $notif['category'] }} @endif
                                @if($notif['notif_type'] === 'selesai')
                                <span class="text-green-600 font-medium"> · Selesai</span>
                                @endif
                            </p>
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $notif['time'] }}</p>
                        </div>
                    </a>
                    @empty
                    <div class="px-4 py-8 text-center text-gray-400 text-sm">
                        <i class="fa-regular fa-bell-slash text-2xl mb-2 block"></i>
                        Tidak ada notifikasi baru
                    </div>
                    @endforelse
                </div>
                <a href="{{ route('admin.tickets.index', ['status' => 'NEW']) }}"
                    class="block text-center text-sm font-medium text-blue-600 hover:bg-blue-50 py-3 rounded-b-xl border-t border-gray-100 transition-colors">
                    Lihat Semua Pengaduan Baru
                </a>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="relative" id="profileDropdown">
            <button onclick="toggleProfileMenu()"
                class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 px-2 py-1.5 rounded-xl transition-colors">
                <div class="hidden md:block text-right">
                    <p class="text-sm font-semibold text-gray-700 leading-tight">{{ auth()->user()?->nama }}</p>
                    <p class="text-xs text-gray-400 leading-tight">{{ auth()->user()?->roles->first()?->name ?? 'Pegawai' }}</p>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()?->nama ?? 'User') }}&background=eff6ff&color=1e3a8a"
                    alt="Profile" class="w-9 h-9 rounded-full border border-gray-200">
                <i class="fa-solid fa-chevron-down text-gray-400 text-xs hidden md:block" id="profileChevron"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="profileMenu"
                class="hidden absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-50">
                <div class="px-4 py-2.5 border-b border-gray-100">
                    <p class="text-sm font-semibold text-gray-800">{{ auth()->user()?->nama }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()?->phone_number ?? auth()->user()?->email ?? '-' }}</p>
                </div>
                <a href="{{ route('admin.profil') }}"
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
        if (chevron) {
            chevron.style.transform = isHidden ? 'rotate(180deg)' : '';
            chevron.style.transition = 'transform 0.2s';
        }
    }

    function toggleNotifMenu() {
        const menu = document.getElementById('notifMenu');
        menu.classList.toggle('hidden');
    }

    // Mark notification as read via AJAX before navigating
    document.addEventListener('click', function (e) {
        const link = e.target.closest('.notif-link');
        if (!link) return;
        const ticketId = link.getAttribute('data-ticket-id');
        if (!ticketId) return;
        e.preventDefault();
        fetch('{{ route('notifications.mark-read') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({ ticket_id: ticketId })
        }).catch(function() {}).finally(function() {
            window.location.href = link.href;
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function (e) {
        const notifDropdown = document.getElementById('notifDropdown');
        const notifMenu = document.getElementById('notifMenu');
        if (notifDropdown && notifMenu && !notifDropdown.contains(e.target)) {
            notifMenu.classList.add('hidden');
        }
        const profileDropdown = document.getElementById('profileDropdown');
        const profileMenu = document.getElementById('profileMenu');
        if (profileDropdown && profileMenu && !profileDropdown.contains(e.target)) {
            profileMenu.classList.add('hidden');
            const chevron = document.getElementById('profileChevron');
            if (chevron) chevron.style.transform = '';
        }
    });
</script>
