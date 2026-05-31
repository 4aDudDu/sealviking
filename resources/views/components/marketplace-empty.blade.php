{{-- Empty state untuk marketplace --}}
<div class="flex flex-col items-center justify-center py-24 text-center">
    <div class="text-6xl mb-4 opacity-30">⚔️</div>
    <p class="text-[#5b85cc] font-bold text-lg">{{ $message ?? 'Tidak ada item' }}</p>
    <p class="text-[#3a5580] text-sm mt-1">
        @if(session('db_error'))
            Periksa koneksi database game kamu
        @else
            Coba kata kunci lain atau cek tab lain
        @endif
    </p>
</div>
