@extends('layouts.app')

@section('content')

{{-- =====================================================================
     MARKETPLACE PAGE — Seal Viking Online
     Tab 1: Cash Shop (beli dengan Diamond)
     Tab 2: Cegel Shop (beli dengan Cegel / in-game currency)
     Tab 3: Player Market (barang dijual sesama player)
     ===================================================================== --}}

<div class="min-h-screen bg-[#060c18]">

    {{-- ─── HERO BANNER ─── --}}
    <div class="relative overflow-hidden bg-gradient-to-b from-[#0b1830] to-[#060c18] border-b border-[#1e3a68]/40">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%231e3a68%22 fill-opacity=%220.08%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>

        <div class="relative max-w-7xl mx-auto px-4 pt-32 pb-16 text-center">
            <div class="inline-flex items-center gap-2 bg-[#D4AF37]/10 border border-[#D4AF37]/30 rounded-full px-4 py-1.5 text-[#D4AF37] text-xs font-bold tracking-widest uppercase mb-4">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                Viking Marketplace
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white mb-3" style="font-style:italic; text-shadow: 0 0 40px rgba(212,175,55,0.3)">
                ITEM SHOP
            </h1>
            <p class="text-[#5b85cc] text-sm max-w-xl mx-auto">
                Beli item eksklusif dengan Diamond & Cegel, atau jelajahi barang dari sesama Viking
            </p>

            {{-- Search Bar --}}
            <form method="GET" action="{{ url('/marketplace') }}" class="mt-6 flex items-center max-w-md mx-auto gap-2">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="relative flex-1">
                    <input
                        id="marketplace-search"
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nama item..."
                        class="w-full bg-[#0b1830] border border-[#1e3a68]/60 text-white rounded-xl px-4 py-2.5 pl-10 text-sm focus:outline-none focus:border-[#D4AF37]/60 transition-colors"
                    >
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#5b85cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <button type="submit" class="bg-gradient-to-r from-[#D4AF37] to-[#f0c040] text-black font-bold px-5 py-2.5 rounded-xl text-sm hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] transition-all">
                    Cari
                </button>
                @if($search)
                    <a href="{{ url('/marketplace?tab='.$tab) }}" class="text-[#5b85cc] text-sm hover:text-white transition-colors px-2">✕</a>
                @endif
            </form>
        </div>
    </div>

    {{-- DB Error Banner --}}
    @if(session('db_error'))
        <div class="max-w-7xl mx-auto px-4 pt-4">
            <div class="bg-red-900/30 border border-red-500/40 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-red-300 font-bold text-sm">Database game belum terhubung</p>
                    <p class="text-red-400/80 text-xs mt-1">{{ session('db_error') }}</p>
                    <p class="text-yellow-400/80 text-xs mt-1">💡 Isi <code class="bg-black/30 px-1 rounded">SEAL_DB_PASSWORD</code> di file <code class="bg-black/30 px-1 rounded">.env</code> lalu restart server.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- ─── TABS ─── --}}
        <div class="flex gap-2 mb-8 border-b border-[#1e3a68]/40 pb-0 overflow-x-auto">
            @php
                $tabs = [
                    'cash'    => ['label' => '💎 Cash Shop',    'hint' => 'Bayar pakai Diamond'],
                    'cegel'   => ['label' => '🪙 Cegel Shop',   'hint' => 'Bayar pakai Cegel'],
                    'vending' => ['label' => '🏪 Player Market', 'hint' => 'Jual beli antar player'],
                ];
            @endphp

            @foreach($tabs as $key => $info)
                <a href="{{ url('/marketplace?tab='.$key.($search ? '&search='.$search : '')) }}"
                   id="tab-{{ $key }}"
                   class="relative flex flex-col items-center px-6 py-3 text-sm font-bold whitespace-nowrap transition-all
                       {{ $tab === $key
                           ? 'text-[#D4AF37] border-b-2 border-[#D4AF37]'
                           : 'text-[#5b85cc] hover:text-white border-b-2 border-transparent' }}">
                    {{ $info['label'] }}
                    <span class="text-[10px] font-normal {{ $tab === $key ? 'text-[#D4AF37]/70' : 'text-[#3a5580]' }}">{{ $info['hint'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- ─── CASH SHOP TAB ─── --}}
        @if($tab === 'cash')
            <div id="panel-cash">
                @if($cashItems instanceof \Illuminate\Pagination\LengthAwarePaginator && $cashItems->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach($cashItems as $item)
                            @include('components.marketplace-item-card', ['item' => $item, 'currency' => 'diamond', 'icon' => '💎'])
                        @endforeach
                    </div>
                    <div class="mt-8">{{ $cashItems->links() }}</div>
                @else
                    @include('components.marketplace-empty', ['message' => 'Belum ada item di Cash Shop'])
                @endif
            </div>
        @endif

        {{-- ─── CEGEL SHOP TAB ─── --}}
        @if($tab === 'cegel')
            <div id="panel-cegel">
                @if($cegelItems instanceof \Illuminate\Pagination\LengthAwarePaginator && $cegelItems->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach($cegelItems as $item)
                            @include('components.marketplace-item-card', ['item' => $item, 'currency' => 'cegel', 'icon' => '🪙'])
                        @endforeach
                    </div>
                    <div class="mt-8">{{ $cegelItems->links() }}</div>
                @else
                    @include('components.marketplace-empty', ['message' => 'Belum ada item di Cegel Shop'])
                @endif
            </div>
        @endif

        {{-- ─── PLAYER VENDING TAB ─── --}}
        @if($tab === 'vending')
            <div id="panel-vending">
                @if($vendingItems->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach($vendingItems as $item)
                            @include('components.marketplace-item-card', ['item' => $item, 'currency' => 'cegel', 'icon' => '🪙', 'isVending' => true])
                        @endforeach
                    </div>
                @else
                    @include('components.marketplace-empty', ['message' => 'Tidak ada barang yang dijual player saat ini'])
                @endif
            </div>
        @endif

    </div>
</div>

@endsection
