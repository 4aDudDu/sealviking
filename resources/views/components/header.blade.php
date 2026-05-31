<header class="fixed w-full top-0 z-50 pt-4 px-4 transition-all duration-300">
    <div class="container mx-auto max-w-6xl relative">
        
        <!-- Desktop Header Layout -->
        <div class="hidden lg:flex justify-between items-center relative w-full">
            
            <!-- Left Side Nav Pill -->
            <nav class="bg-[#0b192c]/85 border border-[#1e3a68]/60 backdrop-blur-md text-blue-100 font-bold text-xs uppercase tracking-wider px-8 py-3 rounded-full flex space-x-6 shadow-[0_0_20px_rgba(6,12,24,0.5)]">
                <a href="/home" class="{{ request()->is('home') ? 'text-orange-400' : '' }} hover:text-orange-400 transition duration-300">Home</a>
                <a href="/marketplace" class="{{ request()->is('marketplace*') ? 'text-[#D4AF37]' : '' }} hover:text-[#D4AF37] transition duration-300">Marketplace</a>
                <a href="/guild" class="hover:text-orange-400 transition duration-300">Guild</a>
                <a href="#" class="hover:text-orange-400 transition duration-300">Support</a>
            </nav>

            <!-- Centered Overlapping Logo -->
            <div class="absolute left-1/2 transform -translate-x-1/2 -top-5 z-10 filter drop-shadow-[0_0_12px_rgba(59,130,246,0.3)]">
                <a href="/home">
                    <img src="{{ asset('img/logo.png') }}" alt="Seal Viking" class="w-24 hover:scale-105 transition duration-300">
                </a>
            </div>

            <!-- Right Side Nav Pill -->
            <nav class="bg-[#0b192c]/85 border border-[#1e3a68]/60 backdrop-blur-md text-blue-100 font-bold text-xs uppercase tracking-wider px-8 py-3 rounded-full flex space-x-6 items-center shadow-[0_0_20px_rgba(6,12,24,0.5)]">
                <a href="/topup" class="hover:text-orange-400 transition duration-300">Top up</a>
                <a href="/rank" class="hover:text-orange-400 transition duration-300">Rank</a>
                
                @auth
                    <div class="flex items-center gap-4 border-l border-[#1e3a68]/80 pl-4">
                        <div class="flex items-center gap-2">
                            <a href="/topup" class="bg-yellow-500/10 text-yellow-400 px-2 py-0.5 rounded-full border border-yellow-500/20 font-black flex items-center gap-1 hover:bg-yellow-500/20 transition">
                                🟡 {{ number_format(auth()->user()->coins) }}
                            </a>
                            <a href="/topup" class="bg-blue-500/10 text-blue-400 px-2 py-0.5 rounded-full border border-blue-500/20 font-black flex items-center gap-1 hover:bg-blue-500/20 transition">
                                💎 {{ number_format(auth()->user()->diamonds) }}
                            </a>
                        </div>
                        <a href="/profile" class="text-white text-xs italic tracking-wider max-w-[80px] truncate drop-shadow-md hover:text-[#D4AF37] transition-colors">
                            {{ auth()->user()->name }}
                            @if(auth()->user()->game_id)
                                <span class="block text-[9px] text-emerald-400 not-italic">🎮 {{ auth()->user()->char_name }}</span>
                            @endif
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline m-0 p-0">
                            @csrf
                            <button type="submit" class="bg-red-500/20 text-red-400 border border-red-500/30 px-3 py-1 rounded-full text-[10px] hover:bg-red-600 hover:text-white hover:border-transparent transition-all duration-300 font-black tracking-widest uppercase">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="/login" class="bg-gradient-to-r from-orange-600 to-orange-500 text-white border border-orange-500/40 px-5 py-1 rounded-full hover:from-orange-500 hover:to-orange-400 transition shadow-[0_0_10px_rgba(234,88,12,0.2)] font-black uppercase tracking-wider text-[10px]">Login</a>
                @endauth
            </nav>

        </div>

        <!-- Mobile Header Layout -->
        <div class="lg:hidden flex justify-between items-center bg-[#0b192c]/90 border border-[#1e3a68]/60 backdrop-blur-md px-5 py-3.5 rounded-2xl shadow-lg relative z-50">
            
            <a href="/home" class="flex items-center gap-2">
                <img src="{{ asset('img/logo.png') }}" alt="Seal Viking" class="w-10">
                <span class="text-white font-black italic text-sm tracking-widest uppercase">VIKING</span>
            </a>

            <!-- Small Balance Deck for Logged-In Mobile Users -->
            @auth
                <div class="flex items-center gap-2">
                    <span class="text-yellow-400 text-xs font-black bg-[#152744]/80 px-2 py-1 rounded-lg border border-[#1e3a68]">
                        🟡 {{ number_format(auth()->user()->coins) }}
                    </span>
                    <span class="text-blue-400 text-xs font-black bg-[#152744]/80 px-2 py-1 rounded-lg border border-[#1e3a68]">
                        💎 {{ number_format(auth()->user()->diamonds) }}
                    </span>
                </div>
            @endauth

            <!-- Animated Mobile Hamburger Button -->
            <button onclick="toggleMobileMenu()" class="text-blue-300 hover:text-white focus:outline-none p-1 transition duration-300" id="hamburger-btn">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="menu-icon-svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16m-7 6h7" id="menu-path-1"></path>
                </svg>
            </button>

        </div>

        <!-- Mobile Slide-Down Drawer Menu Overlay -->
        <div id="mobile-menu" class="hidden lg:hidden flex-col bg-[#0b192c]/95 border-2 border-[#1e3a68] rounded-2xl shadow-2xl p-6 mt-3 absolute w-full left-0 z-40 transform origin-top scale-95 opacity-0 transition-all duration-300">
            
            <nav class="flex flex-col gap-4 text-center">
                <a href="/home" class="text-blue-100 hover:text-orange-400 font-bold uppercase tracking-wider py-2 border-b border-[#1e3a68]/40">Home</a>
                <a href="/marketplace" class="text-[#D4AF37] hover:text-yellow-300 font-bold uppercase tracking-wider py-2 border-b border-[#1e3a68]/40">⚔️ Marketplace</a>
                @auth
                <a href="/profile" class="text-emerald-300 hover:text-emerald-200 font-bold uppercase tracking-wider py-2 border-b border-[#1e3a68]/40">👤 Profile</a>
                @endauth
                <a href="/guild" class="text-blue-100 hover:text-orange-400 font-bold uppercase tracking-wider py-2 border-b border-[#1e3a68]/40">Guild</a>
                <a href="#" class="text-blue-100 hover:text-orange-400 font-bold uppercase tracking-wider py-2 border-b border-[#1e3a68]/40">Support</a>
                <a href="/topup" class="text-blue-100 hover:text-orange-400 font-bold uppercase tracking-wider py-2 border-b border-[#1e3a68]/40">Top up</a>
                <a href="/rank" class="text-blue-100 hover:text-orange-400 font-bold uppercase tracking-wider py-2 border-b border-[#1e3a68]/40">Rank</a>
                
                @auth
                    <!-- Profile Card inside Mobile Drawer -->
                    <div class="bg-[#152744]/75 border border-[#1e3a68] rounded-xl p-4 mt-4 flex flex-col items-center gap-3">
                        <span class="text-blue-300 text-xs font-mono">Logged in as:</span>
                        <span class="text-white font-black italic tracking-wide text-base">{{ auth()->user()->name }}</span>
                        @if(auth()->user()->game_id)
                            <span class="text-emerald-400 text-xs">🎮 {{ auth()->user()->char_name }}</span>
                        @endif
                        <a href="/profile" class="w-full bg-[#1e3a68]/60 text-[#93c5fd] font-bold py-2 rounded-lg text-center text-xs hover:bg-[#1e3a68] transition">
                            👤 My Profile & Inventory
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-400 text-white font-black italic uppercase tracking-wider py-2.5 rounded-lg shadow-md transition duration-300 text-xs">
                                Logout of Portal
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Login Trigger inside Mobile Drawer -->
                    <a href="/login" class="w-full bg-gradient-to-r from-orange-600 to-orange-500 hover:from-orange-500 hover:to-orange-400 text-white font-black italic uppercase tracking-wider py-3 rounded-lg shadow-lg transition duration-300 text-center text-xs mt-4">
                        Login to Account
                    </a>
                @endauth
            </nav>

        </div>

    </div>
</header>

<script>
    let isMobileMenuOpen = false;

    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const iconSvg = document.getElementById('menu-icon-svg');
        
        isMobileMenuOpen = !isMobileMenuOpen;
        
        if (isMobileMenuOpen) {
            // Open mobile menu
            menu.classList.remove('hidden');
            setTimeout(() => {
                menu.classList.remove('scale-95', 'opacity-0');
                menu.classList.add('scale-100', 'opacity-100');
            }, 10);
            
            // Swap hamburger to close X icon
            iconSvg.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>`;
        } else {
            // Close mobile menu
            menu.classList.remove('scale-100', 'opacity-100');
            menu.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                menu.classList.add('hidden');
            }, 300);
            
            // Swap close icon back to hamburger menu
            iconSvg.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16m-7 6h7"></path>`;
        }
    }
</script>