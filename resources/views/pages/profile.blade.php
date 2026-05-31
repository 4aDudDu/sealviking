@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-[#060c18]">

    {{-- ─── PROFILE HEADER ─── --}}
    <div class="relative bg-gradient-to-b from-[#0b1830] to-[#060c18] border-b border-[#1e3a68]/40 pt-28 pb-10">
        <div class="max-w-5xl mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">

                {{-- Avatar --}}
                <div class="relative">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-[#D4AF37] to-[#f97316] flex items-center justify-center text-4xl font-black text-black shadow-[0_0_30px_rgba(212,175,55,0.4)]">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    @if($gameAccount)
                        <div class="absolute -bottom-1 -right-1 bg-emerald-500 w-5 h-5 rounded-full border-2 border-[#0b1830] flex items-center justify-center">
                            <span class="text-[8px]">✓</span>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="text-center md:text-left flex-1">
                    <h1 class="text-2xl font-black text-white italic uppercase">{{ $user->name }}</h1>
                    <p class="text-[#5b85cc] text-sm">{{ $user->email }}</p>

                    @if($gameAccount)
                        <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-3">
                            <span class="bg-emerald-900/40 border border-emerald-500/30 text-emerald-300 text-xs px-3 py-1 rounded-full font-bold">
                                🎮 {{ $gameAccount->char_name }}
                            </span>
                            <span class="bg-[#1e3a68]/60 border border-[#2d5090]/40 text-[#93c5fd] text-xs px-3 py-1 rounded-full">
                                Server #{{ $gameAccount->gameserver_bumho ?? '?' }}
                            </span>
                            @if($gameAccount->isBlocked())
                                <span class="bg-red-900/40 border border-red-500/30 text-red-300 text-xs px-3 py-1 rounded-full font-bold">
                                    🔒 BLOCKED
                                </span>
                            @endif
                        </div>
                    @else
                        <p class="text-yellow-400/70 text-xs mt-2">⚠️ Akun game belum dihubungkan</p>
                    @endif
                </div>

                {{-- Currency Stats --}}
                <div class="flex gap-3">
                    <div class="bg-[#0b192c] border border-[#1e3a68]/60 rounded-xl px-5 py-3 text-center min-w-[90px]">
                        <p class="text-xl font-black text-[#D4AF37]">{{ number_format($user->diamonds ?? 0) }}</p>
                        <p class="text-[10px] text-[#5b85cc] font-bold">💎 Diamond</p>
                    </div>
                    <div class="bg-[#0b192c] border border-[#1e3a68]/60 rounded-xl px-5 py-3 text-center min-w-[90px]">
                        <p class="text-xl font-black text-[#f0c040]">{{ number_format($user->coins ?? 0) }}</p>
                        <p class="text-[10px] text-[#5b85cc] font-bold">🪙 Coin</p>
                    </div>
                    @if($coins)
                        <div class="bg-[#0b192c] border border-[#1e3a68]/60 rounded-xl px-5 py-3 text-center min-w-[90px]">
                            <p class="text-xl font-black text-emerald-400">{{ $coins->formatted_cegel }}</p>
                            <p class="text-[10px] text-[#5b85cc] font-bold">Cegel</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8 space-y-8">

        {{-- DB Error --}}
        @if(session('db_error'))
            <div class="bg-red-900/30 border border-red-500/40 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-red-300 font-bold text-sm">Gagal memuat data game</p>
                    <p class="text-red-400/80 text-xs mt-1">{{ session('db_error') }}</p>
                </div>
            </div>
        @endif

        {{-- ─── LINK GAME ACCOUNT CARD ─── --}}
        <div class="bg-[#0b192c] border border-[#1e3a68]/60 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-white font-black text-lg italic uppercase tracking-wide">🔗 Akun Game</h2>
                @if($gameAccount)
                    <form method="POST" action="{{ url('/profile/unlink-game') }}" onsubmit="return confirm('Lepas link akun game?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-400/70 hover:text-red-300 text-xs transition-colors">Lepas</button>
                    </form>
                @endif
            </div>

            @if($gameAccount)
                {{-- Sudah terhubung --}}
                <div class="bg-emerald-900/20 border border-emerald-500/20 rounded-xl p-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-[#5b85cc] text-xs mb-1">Account ID</p>
                            <p class="text-white font-bold">{{ $gameAccount->id }}</p>
                        </div>
                        <div>
                            <p class="text-[#5b85cc] text-xs mb-1">Karakter</p>
                            <p class="text-emerald-300 font-bold">{{ $gameAccount->char_name }}</p>
                        </div>
                        <div>
                            <p class="text-[#5b85cc] text-xs mb-1">Level Account</p>
                            <p class="text-white font-bold">{{ $gameAccount->userLevel ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[#5b85cc] text-xs mb-1">Terakhir Login</p>
                            <p class="text-white text-xs">{{ $gameAccount->serverenter_time ? \Carbon\Carbon::parse($gameAccount->serverenter_time)->format('d M Y') : '-' }}</p>
                        </div>
                    </div>
                </div>
            @else
                {{-- Belum terhubung — form link --}}
                <div class="bg-yellow-900/10 border border-yellow-500/20 rounded-xl p-4 mb-4">
                    <p class="text-yellow-300/80 text-sm">Hubungkan akun game kamu untuk melihat inventory dan membeli item di marketplace.</p>
                </div>
                <form method="POST" action="{{ url('/profile/link-game') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @csrf
                    <div>
                        <label class="text-[#5b85cc] text-xs font-bold block mb-1.5">Game Account ID</label>
                        <input
                            id="game_id"
                            type="text"
                            name="game_id"
                            value="{{ old('game_id') }}"
                            placeholder="Username login game kamu"
                            class="w-full bg-[#060c18] border border-[#1e3a68]/60 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#D4AF37]/60 transition-colors"
                            required
                        >
                    </div>
                    <div>
                        <label class="text-[#5b85cc] text-xs font-bold block mb-1.5">Password Game</label>
                        <input
                            id="game_password"
                            type="password"
                            name="game_password"
                            placeholder="Password akun game kamu"
                            class="w-full bg-[#060c18] border border-[#1e3a68]/60 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#D4AF37]/60 transition-colors"
                            required
                        >
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-[#D4AF37] to-[#f0c040] text-black font-black py-2.5 rounded-xl hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] transition-all text-sm">
                            🔗 Hubungkan
                        </button>
                    </div>
                    @error('game_id') <p class="col-span-3 text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </form>
            @endif
        </div>

        {{-- ─── INVENTORY ─── --}}
        @if($gameAccount)
            <div class="bg-[#0b192c] border border-[#1e3a68]/60 rounded-2xl p-6">
                <h2 class="text-white font-black text-lg italic uppercase tracking-wide mb-4">🎒 Inventory — {{ $gameAccount->char_name }}</h2>

                @if($inventory->count() > 0)
                    <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 gap-2">
                        @foreach($inventory as $invItem)
                            @php
                                $invItemId = $invItem->item_id ?? $invItem->itemid ?? 0;
                                $invCount  = $invItem->item_count ?? $invItem->count ?? 1;
                                $invSlot   = $invItem->slot ?? $invItem->slot_index ?? '?';
                            @endphp
                            <div class="group relative bg-[#060c18] border border-[#1e3a68]/40 rounded-xl p-2 flex flex-col items-center hover:border-[#D4AF37]/40 transition-colors cursor-help"
                                 title="Item ID: {{ $invItemId }} | Slot: {{ $invSlot }} | Qty: {{ $invCount }}">
                                <img
                                    src="/images/items/{{ $invItemId }}.png"
                                    alt="Item #{{ $invItemId }}"
                                    class="w-10 h-10 object-contain"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >
                                <div class="hidden w-10 h-10 items-center justify-center text-lg bg-[#1e3a68]/20 rounded-lg"
                                     style="display:none;">⚔️</div>
                                @if($invCount > 1)
                                    <span class="absolute bottom-1 right-1 bg-[#D4AF37] text-black text-[8px] font-black px-1 rounded-sm leading-none">{{ $invCount }}</span>
                                @endif
                                {{-- Tooltip --}}
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block z-10 whitespace-nowrap bg-[#0b192c] border border-[#1e3a68] rounded-lg px-2 py-1 text-xs text-white shadow-xl">
                                    ID: {{ $invItemId }}<br>
                                    <span class="text-[#D4AF37]">Qty: {{ $invCount }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center py-12 text-center">
                        <span class="text-5xl mb-3 opacity-30">🎒</span>
                        <p class="text-[#5b85cc] font-bold">Inventory kosong</p>
                        <p class="text-[#3a5580] text-xs mt-1">atau kolom database perlu disesuaikan di <code>SealInventory.php</code></p>
                    </div>
                @endif
            </div>

            {{-- ─── STORAGE / WAREHOUSE ─── --}}
            @if($store && count($store) > 0)
                <div class="bg-[#0b192c] border border-[#1e3a68]/60 rounded-2xl p-6">
                    <h2 class="text-white font-black text-lg italic uppercase tracking-wide mb-4">🏛️ Gudang (Storage)</h2>
                    <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 gap-2">
                        @foreach($store as $storeItem)
                            @php
                                $storeItemId = $storeItem->item_id ?? $storeItem->itemid ?? 0;
                                $storeCount  = $storeItem->item_count ?? $storeItem->count ?? 1;
                            @endphp
                            <div class="relative bg-[#060c18] border border-[#1e3a68]/30 rounded-xl p-2 flex items-center justify-center hover:border-[#D4AF37]/30 transition-colors cursor-help"
                                 title="Item ID: {{ $storeItemId }} | Qty: {{ $storeCount }}">
                                <img
                                    src="/images/items/{{ $storeItemId }}.png"
                                    alt="Item #{{ $storeItemId }}"
                                    class="w-10 h-10 object-contain"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >
                                <div class="hidden w-10 h-10 items-center justify-center text-lg" style="display:none;">📦</div>
                                @if($storeCount > 1)
                                    <span class="absolute bottom-1 right-1 bg-[#1e3a68] text-white text-[8px] font-black px-1 rounded-sm leading-none">{{ $storeCount }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        {{-- ─── PANDUAN GANTI GAMBAR ITEM ─── --}}
        <div class="bg-[#0b192c] border border-[#D4AF37]/20 rounded-2xl p-6">
            <h2 class="text-[#D4AF37] font-black text-base italic uppercase mb-3">📁 Cara Menambahkan Gambar Item</h2>
            <div class="space-y-2 text-sm text-[#5b85cc]">
                <p>Saat ini item menggunakan <span class="text-white font-bold">placeholder emoji</span>. Untuk menampilkan gambar item asli dari game:</p>
                <ol class="list-decimal list-inside space-y-1.5 text-[#93c5fd] mt-2">
                    <li>Buka folder: <code class="bg-[#060c18] text-[#D4AF37] px-2 py-0.5 rounded font-mono text-xs">public/images/items/</code></li>
                    <li>Copy semua file icon item dari game client Seal Online kamu (biasanya di folder <code class="bg-[#060c18] text-[#D4AF37] px-2 py-0.5 rounded font-mono text-xs">client/icon/</code> atau <code class="bg-[#060c18] text-[#D4AF37] px-2 py-0.5 rounded font-mono text-xs">client/img/item/</code>)</li>
                    <li>Rename filenya sesuai Item ID: <code class="bg-[#060c18] text-[#D4AF37] px-2 py-0.5 rounded font-mono text-xs">1234.png</code> (dimana 1234 = item_id di database)</li>
                    <li>Upload ke folder <code class="bg-[#060c18] text-[#D4AF37] px-2 py-0.5 rounded font-mono text-xs">public/images/items/</code></li>
                </ol>
                <p class="text-[#3a5580] text-xs mt-2">Website akan otomatis menampilkan gambar jika file ada, fallback ke emoji jika tidak ada.</p>
            </div>
        </div>

    </div>
</div>

@endsection
