{{-- ─────────────────────────────────────────────────────
     ITEM CARD COMPONENT — Marketplace
     Props:
       $item     : SealShopItem | SealCegelShopItem | SealVending
       $currency : 'diamond' | 'cegel'
       $icon     : emoji
       $isVending: bool (optional)
     ───────────────────────────────────────────────────── --}}

@php
    $itemId   = $item->item_id ?? $item->itemid ?? 0;
    $itemName = $item->memo ?? $item->item_name ?? "Item #{$itemId}";
    $price    = $item->item_price ?? $item->price ?? 0;
    $stock    = $item->remaining_stock ?? 'Unlimited';
    $outOfStock = method_exists($item, 'isOutOfStock') ? $item->isOutOfStock() : false;
    $seller   = $item->char_name ?? null; // untuk vending
    $isVending = $isVending ?? false;

    // Warna card berdasarkan item_id (biar kartu beda-beda visual)
    $colors = [
        'from-blue-900/40 to-blue-800/20 border-blue-500/30',
        'from-purple-900/40 to-purple-800/20 border-purple-500/30',
        'from-amber-900/40 to-amber-800/20 border-amber-500/30',
        'from-emerald-900/40 to-emerald-800/20 border-emerald-500/30',
        'from-rose-900/40 to-rose-800/20 border-rose-500/30',
        'from-cyan-900/40 to-cyan-800/20 border-cyan-500/30',
        'from-indigo-900/40 to-indigo-800/20 border-indigo-500/30',
        'from-teal-900/40 to-teal-800/20 border-teal-500/30',
    ];
    $cardColor = $colors[$itemId % count($colors)];

    // Placeholder icon berdasarkan nama item
    $itemEmoji = '⚔️';
    $nameLower = strtolower($itemName);
    if (str_contains($nameLower, 'potion') || str_contains($nameLower, 'pot'))  $itemEmoji = '🧪';
    if (str_contains($nameLower, 'hunt'))   $itemEmoji = '🎯';
    if (str_contains($nameLower, 'drop'))   $itemEmoji = '💫';
    if (str_contains($nameLower, 'key'))    $itemEmoji = '🗝️';
    if (str_contains($nameLower, 'chest') || str_contains($nameLower, 'box'))   $itemEmoji = '📦';
    if (str_contains($nameLower, 'pet'))    $itemEmoji = '🐾';
    if (str_contains($nameLower, 'gold'))   $itemEmoji = '🪙';
    if (str_contains($nameLower, 'diamond') || str_contains($nameLower, 'gem'))  $itemEmoji = '💎';
    if (str_contains($nameLower, 'weapon') || str_contains($nameLower, 'sword')) $itemEmoji = '⚔️';
    if (str_contains($nameLower, 'armor') || str_contains($nameLower, 'shield')) $itemEmoji = '🛡️';
    if (str_contains($nameLower, 'wing'))   $itemEmoji = '🪶';
    if (str_contains($nameLower, 'scroll')) $itemEmoji = '📜';
@endphp

<div class="group relative bg-gradient-to-b {{ $cardColor }} border rounded-2xl overflow-hidden
            hover:scale-[1.03] hover:shadow-[0_8px_32px_rgba(0,0,0,0.5)] transition-all duration-300 cursor-pointer"
     onclick="openItemModal({{ $item->num_idx ?? $item->id ?? 0 }}, '{{ addslashes($itemName) }}', {{ $price }}, '{{ $icon }}', '{{ addslashes($itemEmoji) }}', {{ $itemId }}, '{{ $currency }}', '{{ $stock }}', '{{ addslashes($seller ?? '') }}')">

    {{-- Out of Stock Overlay --}}
    @if($outOfStock)
        <div class="absolute inset-0 bg-black/70 z-10 flex items-center justify-center rounded-2xl">
            <span class="bg-red-600 text-white text-xs font-black px-3 py-1 rounded-full tracking-widest uppercase">Habis</span>
        </div>
    @endif

    {{-- Item Image / Placeholder --}}
    <div class="relative p-4 flex items-center justify-center h-28">
        {{-- Cek apakah file gambar tersedia --}}
        <img
            src="/images/items/{{ $itemId }}.png"
            alt="{{ $itemName }}"
            class="h-20 w-20 object-contain drop-shadow-lg"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
        >
        {{-- Fallback: Emoji placeholder --}}
        <div class="hidden h-20 w-20 items-center justify-center text-4xl bg-black/20 rounded-xl"
             style="display: none;">
            {{ $itemEmoji }}
        </div>

        {{-- Item ID Badge (kecil, untuk referensi) --}}
        <span class="absolute top-2 right-2 text-[9px] bg-black/40 text-white/40 px-1.5 py-0.5 rounded font-mono">
            #{{ $itemId }}
        </span>

        {{-- Vending seller badge --}}
        @if($isVending && $seller)
            <span class="absolute top-2 left-2 text-[9px] bg-[#1e3a68]/80 text-[#93c5fd] px-1.5 py-0.5 rounded">
                {{ $seller }}
            </span>
        @endif
    </div>

    {{-- Item Info --}}
    <div class="px-3 pb-4 space-y-2">
        <p class="text-white text-xs font-bold leading-tight line-clamp-2 text-center min-h-[2.5rem]">
            {{ $itemName }}
        </p>

        {{-- Price --}}
        <div class="flex items-center justify-center gap-1.5 bg-black/20 rounded-xl py-1.5">
            <span class="text-sm">{{ $icon }}</span>
            <span class="text-[#D4AF37] font-black text-sm">{{ number_format($price) }}</span>
            <span class="text-[#5b85cc] text-[10px]">{{ $currency === 'diamond' ? 'Diamond' : 'Cegel' }}</span>
        </div>

        {{-- Stock --}}
        <p class="text-center text-[10px] {{ $stock === 'Unlimited' ? 'text-emerald-400/70' : 'text-gray-500' }}">
            Stok: {{ $stock }}
        </p>

        {{-- Buy Button --}}
        @if(!$outOfStock)
            <button
                onclick="event.stopPropagation(); openItemModal({{ $item->num_idx ?? $item->id ?? 0 }}, '{{ addslashes($itemName) }}', {{ $price }}, '{{ $icon }}', '{{ addslashes($itemEmoji) }}', {{ $itemId }}, '{{ $currency }}', '{{ $stock }}', '{{ addslashes($seller ?? '') }}')"
                class="w-full bg-gradient-to-r from-[#D4AF37] to-[#f0c040] hover:from-[#f0c040] hover:to-[#D4AF37]
                       text-black font-black text-xs py-2 rounded-xl transition-all
                       group-hover:shadow-[0_0_15px_rgba(212,175,55,0.4)]">
                BELI
            </button>
        @endif
    </div>
</div>

{{-- ─── ITEM DETAIL MODAL (shared, dipanggil via JS) ─── --}}
<div id="item-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
     onclick="if(event.target===this) closeItemModal()">
    <div class="bg-[#0b192c] border border-[#1e3a68] rounded-2xl max-w-sm w-full p-6 shadow-2xl" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-4">
            <h3 id="modal-title" class="text-white font-black text-lg italic uppercase"></h3>
            <button onclick="closeItemModal()" class="text-gray-500 hover:text-white transition-colors text-xl">✕</button>
        </div>

        {{-- Item visual --}}
        <div class="flex justify-center my-4">
            <div class="w-28 h-28 flex items-center justify-center bg-[#060c18] rounded-2xl border border-[#1e3a68]/50">
                <img id="modal-img" src="" alt="" class="w-24 h-24 object-contain" onerror="this.style.display='none'; document.getElementById('modal-emoji').style.display='block'">
                <span id="modal-emoji" class="text-5xl" style="display:none"></span>
            </div>
        </div>

        <div class="space-y-2 text-sm">
            <div class="flex justify-between items-center bg-[#060c18] rounded-xl px-4 py-2">
                <span class="text-[#5b85cc]">Item ID</span>
                <span id="modal-itemid" class="text-white font-mono text-xs"></span>
            </div>
            <div class="flex justify-between items-center bg-[#060c18] rounded-xl px-4 py-2">
                <span class="text-[#5b85cc]">Harga</span>
                <span id="modal-price" class="text-[#D4AF37] font-black"></span>
            </div>
            <div class="flex justify-between items-center bg-[#060c18] rounded-xl px-4 py-2">
                <span class="text-[#5b85cc]">Stok</span>
                <span id="modal-stock" class="text-white"></span>
            </div>
            <div id="modal-seller-row" class="hidden flex justify-between items-center bg-[#060c18] rounded-xl px-4 py-2">
                <span class="text-[#5b85cc]">Seller</span>
                <span id="modal-seller" class="text-emerald-400 font-bold"></span>
            </div>
        </div>

        {{-- Auth Check --}}
        @auth
            @if(auth()->user()->game_id)
                <form id="modal-buy-form" method="POST" action="" class="mt-4">
                    @csrf
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#D4AF37] to-[#f0c040] text-black font-black py-3 rounded-xl hover:shadow-[0_0_20px_rgba(212,175,55,0.5)] transition-all uppercase tracking-wider">
                        💎 Beli Sekarang
                    </button>
                </form>
                <p class="text-center text-xs text-[#5b85cc] mt-2">
                    Diamond kamu: <span class="text-[#D4AF37] font-bold">{{ number_format(auth()->user()->diamonds) }}</span>
                </p>
            @else
                <a href="{{ url('/profile') }}"
                   class="mt-4 block w-full bg-[#1e3a68] text-[#93c5fd] font-bold py-3 rounded-xl text-center hover:bg-[#2a4a7a] transition-all text-sm">
                    🔗 Hubungkan Akun Game Dulu
                </a>
            @endif
        @else
            <a href="{{ url('/login') }}"
               class="mt-4 block w-full bg-gradient-to-r from-[#ea580c] to-[#f97316] text-white font-black py-3 rounded-xl text-center hover:shadow-[0_0_20px_rgba(234,88,12,0.4)] transition-all uppercase text-sm">
                Login untuk Membeli
            </a>
        @endauth
    </div>
</div>

<script>
function openItemModal(id, name, price, icon, emoji, itemId, currency, stock, seller) {
    document.getElementById('modal-title').textContent = name;
    document.getElementById('modal-itemid').textContent = '#' + itemId;
    document.getElementById('modal-price').textContent = icon + ' ' + price.toLocaleString() + ' ' + (currency === 'diamond' ? 'Diamond' : 'Cegel');
    document.getElementById('modal-stock').textContent = stock;

    // Gambar
    const img = document.getElementById('modal-img');
    const emojiEl = document.getElementById('modal-emoji');
    img.src = '/images/items/' + itemId + '.png';
    img.style.display = 'block';
    emojiEl.textContent = emoji;
    emojiEl.style.display = 'none';

    // Seller (vending)
    const sellerRow = document.getElementById('modal-seller-row');
    if (seller) {
        document.getElementById('modal-seller').textContent = seller;
        sellerRow.classList.remove('hidden');
    } else {
        sellerRow.classList.add('hidden');
    }

    // Form action
    const form = document.getElementById('modal-buy-form');
    if (form) {
        form.action = '/marketplace/buy-cash/' + id;
    }

    document.getElementById('item-modal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeItemModal() {
    document.getElementById('item-modal').style.display = 'none';
    document.body.style.overflow = '';
}
</script>
