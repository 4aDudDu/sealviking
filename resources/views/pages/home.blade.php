@extends('layouts.app')

@section('content')
@php
    $currentDate = now();
    $year = $currentDate->year;
    $month = $currentDate->month;
    $monthName = $currentDate->format('F');
    
    // First day of the month
    $firstDayOfMonth = \Carbon\Carbon::create($year, $month, 1);
    // Number of days in the month
    $daysInMonth = $firstDayOfMonth->daysInMonth;
    // Day of the week of the first day (0 = Sunday, 1 = Monday, etc.)
    $dayOfWeek = $firstDayOfMonth->dayOfWeek;
    
    // Previous month days to fill the gap at the start of the week
    $prevMonth = $firstDayOfMonth->copy()->subMonth();
    $daysInPrevMonth = $prevMonth->daysInMonth;
    
    // Create list of days
    $calendarDays = [];
    
    // Fill in previous month days
    for ($i = $dayOfWeek - 1; $i >= 0; $i--) {
        $calendarDays[] = [
            'day' => $daysInPrevMonth - $i,
            'is_current_month' => false,
            'date_string' => $prevMonth->copy()->day($daysInPrevMonth - $i)->format('Y-m-d'),
        ];
    }
    
    // Fill in current month days
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $calendarDays[] = [
            'day' => $day,
            'is_current_month' => true,
            'date_string' => \Carbon\Carbon::create($year, $month, $day)->format('Y-m-d'),
        ];
    }
    
    // Group events by date string for quick calendar lookup
    $eventsByDate = [];
    foreach($events as $event) {
        $dateStr = \Carbon\Carbon::parse($event->event_date)->format('Y-m-d');
        $eventsByDate[$dateStr][] = $event;
    }
    
    // Find the first event day to show by default
    $initialEventDate = null;
    $initialEvents = [];
    foreach($calendarDays as $dayInfo) {
        if ($dayInfo['is_current_month'] && isset($eventsByDate[$dayInfo['date_string']])) {
            $initialEventDate = $dayInfo['date_string'];
            $initialEvents = $eventsByDate[$dayInfo['date_string']];
            break;
        }
    }
    if (!$initialEventDate) {
        $initialEventDate = now()->format('Y-m-d');
    }
@endphp

<!-- Hero & Slider Section -->
<div class="relative w-full min-h-[90vh] flex flex-col justify-center bg-[#060c18] bg-cover bg-center bg-no-repeat pt-20 pb-16"
     style="background-image: url('{{ asset('img/hero-bg.jpg') }}');">
    
    <!-- Immersive Dark Overlay -->
    <div class="absolute inset-0 bg-gradient-to-b from-[#060c18]/90 via-black/20 to-[#060c18]"></div>

    <!-- Static Header Content -->
    <div class="relative z-10 w-full flex flex-col items-center text-center max-w-5xl mx-auto pt-8 mb-6">
        <h1 class="text-white text-5xl md:text-[5.5rem] font-black italic tracking-wider leading-none drop-shadow-[0_0_20px_rgba(255,255,255,0.3)] uppercase mb-4">
            SEAL<br>
            <span class="text-orange-500 drop-shadow-[0_0_20px_rgba(249,115,22,0.6)]">///GENESIS///</span>
        </h1>
        
        <a href="https://discord.gg/yourlink" target="_blank" class="border border-orange-500 hover:border-orange-400 text-orange-400 hover:text-white px-8 py-2.5 rounded hover:bg-orange-500/80 transition-all duration-300 uppercase font-black tracking-[0.2em] text-xs backdrop-blur-sm shadow-[0_0_15px_rgba(249,115,22,0.2)]">
            ENTER DISCORD
        </a>
    </div>

    <!-- Carousel Wrapper (Only this part slides) -->
    <div class="relative z-10 w-full max-w-6xl mx-auto flex flex-col justify-center overflow-hidden px-12">
        
        <!-- Slider Track -->
        <div id="slider-track" class="flex transition-transform duration-700 ease-in-out w-full" style="transform: translateX(0%);">
            
            <!-- Slide 1: 6VS6 PVP Winners -->
            <div class="w-full flex-shrink-0 px-4 flex flex-col items-center text-center">
                <p class="text-orange-500 text-[10px] font-black tracking-[0.25em] uppercase mb-1 drop-shadow-md">PVP ARENA</p>
                <h2 class="text-white text-2xl md:text-3xl font-black italic tracking-wider leading-none uppercase mb-6 drop-shadow-md">
                    6VS6 PVP <span class="text-orange-500">WINNERS</span>
                </h2>
                <div class="flex flex-row justify-center gap-4 md:gap-6 w-full max-w-4xl text-left">
                    <div class="flex-1 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-4 md:p-5 shadow-lg">
                        <p class="text-orange-400 font-black text-xs uppercase tracking-wider mb-2 flex items-center justify-between">
                            <span>1st</span>
                        </p>
                        <p class="text-white font-black text-base md:text-lg italic tracking-wide mb-3">TEAM AYVI</p>
                        <div class="text-gray-300 font-semibold text-xs leading-loose space-y-1">
                            <p><span class="text-orange-500">•</span> Xvt <span class="text-gray-500 font-normal">(Archer)</span></p>
                            <p><span class="text-orange-500">•</span> Gajah <span class="text-gray-500 font-normal">(Renegade)</span></p>
                            <p><span class="text-orange-500">•</span> Michlyoo <span class="text-gray-500 font-normal">(Gunner)</span></p>
                            <p><span class="text-orange-500">•</span> Banaficio <span class="text-gray-500 font-normal">(Berserker)</span></p>
                            <p><span class="text-orange-500">•</span> Ayvi <span class="text-gray-500 font-normal">(Apostle)</span></p>
                            <p><span class="text-orange-500">•</span> W <span class="text-gray-500 font-normal">(Gambler)</span></p>
                        </div>
                    </div>
                    <div class="flex-1 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-4 md:p-5 shadow-lg">
                        <p class="text-orange-400 font-black text-xs uppercase tracking-wider mb-2 flex items-center justify-between">
                            <span>2nd</span>
                        </p>
                        <p class="text-white font-black text-base md:text-lg italic tracking-wide mb-3">TEAM OHMAGAD</p>
                        <div class="text-gray-300 font-semibold text-xs leading-loose space-y-1">
                            <p><span class="text-orange-500">•</span> Kampungan <span class="text-gray-500 font-normal">(Gunner)</span></p>
                            <p><span class="text-orange-500">•</span> Selana <span class="text-gray-500 font-normal">(Archer)</span></p>
                            <p><span class="text-orange-500">•</span> ICE <span class="text-gray-500 font-normal">(Gambler)</span></p>
                            <p><span class="text-orange-500">•</span> Vermouth <span class="text-gray-500 font-normal">(Assassin)</span></p>
                            <p><span class="text-orange-500">•</span> Mahjong <span class="text-gray-500 font-normal">(Defender)</span></p>
                            <p><span class="text-orange-500">•</span> Luxcy <span class="text-gray-500 font-normal">(Apostle)</span></p>
                        </div>
                    </div>
                    <div class="flex-1 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-4 md:p-5 shadow-lg">
                        <p class="text-orange-400 font-black text-xs uppercase tracking-wider mb-2 flex items-center justify-between">
                            <span>3rd</span>
                        </p>
                        <p class="text-white font-black text-base md:text-lg italic tracking-wide mb-3">TEAM AUSRINE</p>
                        <div class="text-gray-300 font-semibold text-xs leading-loose space-y-1">
                            <p><span class="text-orange-500">•</span> Ausrine <span class="text-gray-500 font-normal">(Defender)</span></p>
                            <p><span class="text-orange-500">•</span> DAY <span class="text-gray-500 font-normal">(Archer)</span></p>
                            <p><span class="text-orange-500">•</span> L <span class="text-gray-500 font-normal">(Gambler)</span></p>
                            <p><span class="text-orange-500">•</span> Keiden <span class="text-gray-500 font-normal">(Fire Wizard)</span></p>
                            <p><span class="text-orange-500">•</span> Liar <span class="text-gray-500 font-normal">(Swordmaster)</span></p>
                            <p><span class="text-orange-500">•</span> Kudry <span class="text-gray-500 font-normal">(Apostle)</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2: Man of the Match -->
            <div class="w-full flex-shrink-0 px-4 flex flex-col items-center text-center">
                <p class="text-orange-500 text-[10px] font-black tracking-[0.25em] uppercase mb-1 drop-shadow-md">WARZONE ARENA</p>
                <h2 class="text-white text-2xl md:text-3xl font-black italic tracking-wider leading-none uppercase mb-6 drop-shadow-md">
                    MAN OF THE <span class="text-orange-500">MATCH</span>
                </h2>
                <div class="flex flex-row justify-center gap-4 md:gap-6 w-full max-w-2xl text-center">
                    <div class="flex-1 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl py-6 shadow-lg flex flex-col items-center justify-center">
                        <p class="text-orange-400 font-black text-[10px] uppercase tracking-wider mb-1">1st</p>
                        <p class="text-white font-black text-lg md:text-xl italic tracking-wide">ELIZA</p>
                    </div>
                    <div class="flex-1 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl py-6 shadow-lg flex flex-col items-center justify-center">
                        <p class="text-orange-400 font-black text-[10px] uppercase tracking-wider mb-1">2nd</p>
                        <p class="text-white font-black text-lg md:text-xl italic tracking-wide">COOT</p>
                    </div>
                    <div class="flex-1 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl py-6 shadow-lg flex flex-col items-center justify-center">
                        <p class="text-orange-400 font-black text-[10px] uppercase tracking-wider mb-1">3rd</p>
                        <p class="text-white font-black text-lg md:text-xl italic tracking-wide">KAI</p>
                    </div>
                </div>
            </div>

            <!-- Slide 3: Crownfall Winners -->
            <div class="w-full flex-shrink-0 px-4 flex flex-col items-center text-center">
                <p class="text-orange-500 text-[10px] font-black tracking-[0.25em] uppercase mb-1 drop-shadow-md">CHAMPIONS OF CROWNFALL</p>
                <h2 class="text-white text-2xl md:text-3xl font-black italic tracking-wider leading-none uppercase mb-6 drop-shadow-md">
                    CROWNFALL <span class="text-orange-500">WINNERS</span>
                </h2>
                <div class="flex flex-row justify-center gap-3 md:gap-4 w-full max-w-3xl text-center">
                    <div class="flex-1 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl py-5 shadow-lg flex flex-col items-center justify-center">
                        <p class="text-white font-black text-sm md:text-base italic tracking-wide">TEHKUAT</p>
                    </div>
                    <div class="flex-1 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl py-5 shadow-lg flex flex-col items-center justify-center">
                        <p class="text-white font-black text-sm md:text-base italic tracking-wide">VNLA</p>
                    </div>
                    <div class="flex-1 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl py-5 shadow-lg flex flex-col items-center justify-center">
                        <p class="text-white font-black text-sm md:text-base italic tracking-wide">DADDY</p>
                    </div>
                    <div class="flex-1 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl py-5 shadow-lg flex flex-col items-center justify-center">
                        <p class="text-white font-black text-sm md:text-base italic tracking-wide">JAGGERJACK</p>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Navigation Arrows (Inside wrapper to stay close to content) -->
        <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex justify-between px-2 md:px-0 pointer-events-none">
            <button onclick="prevSlide()" class="text-white/40 hover:text-orange-500 text-4xl md:text-5xl transition cursor-pointer hover:scale-110 pointer-events-auto select-none">&lsaquo;</button>
            <button onclick="nextSlide()" class="text-white/40 hover:text-orange-500 text-4xl md:text-5xl transition cursor-pointer hover:scale-110 pointer-events-auto select-none">&rsaquo;</button>
        </div>

    </div>

    <!-- Slider Indicator Dots -->
    <div class="relative z-20 flex justify-center gap-2 w-full mt-8">
        @for($i = 0; $i < 3; $i++)
            <button onclick="goToSlide({{ $i }})" id="slide-dot-{{ $i }}" class="w-8 h-1 rounded bg-white/20 transition-all duration-300 hover:bg-orange-500 cursor-pointer pointer-events-auto {{ $i === 0 ? 'bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.8)]' : '' }}"></button>
        @endfor
    </div>

    <!-- Mouse Indicator (Small mouse icon) -->
    <div class="relative z-20 flex justify-center w-full mt-6 opacity-40">
        <div class="w-4 h-6 border-2 border-white rounded-full flex justify-center pt-1">
            <div class="w-1 h-1 bg-white rounded-full animate-bounce"></div>
        </div>
    </div>
</div>

    {{-- ─── STATS ROW ─── --}}
    <div class="relative z-10 w-full bg-[#060c18]/80 border-t border-[#1e3a68]/30 py-0">
        <div class="max-w-5xl mx-auto px-4 -mt-1 pb-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- Players Online --}}
                <div class="bg-[#0b192c]/80 border border-[#1e3a68]/70 rounded-2xl p-5 flex items-center gap-4 hover:border-emerald-500/40 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-emerald-900/40 border border-emerald-500/30 flex items-center justify-center text-2xl shrink-0">
                        🟢
                    </div>
                    <div>
                        <p class="text-[10px] text-[#5b85cc] font-black uppercase tracking-widest">Players</p>
                        <p class="text-2xl font-black text-white leading-none">
                            <span id="stat-players">{{ number_format($playersOnline ?? 0) }}</span>
                        </p>
                        <p class="text-[9px] text-emerald-400 font-bold uppercase tracking-wider mt-0.5">Total Terdaftar</p>
                    </div>
                </div>

                {{-- Cegel Circulating --}}
                <div class="bg-[#0b192c]/80 border border-[#1e3a68]/70 rounded-2xl p-5 flex items-center gap-4 hover:border-yellow-500/40 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-yellow-900/40 border border-yellow-500/30 flex items-center justify-center text-2xl shrink-0">
                        🪙
                    </div>
                    <div>
                        <p class="text-[10px] text-[#5b85cc] font-black uppercase tracking-widest">Cegel</p>
                        <p class="text-xl font-black text-[#f0c040] leading-none">
                            {{ number_format($cegelCirculate ?? 0) }}
                        </p>
                        <p class="text-[9px] text-yellow-600 font-bold uppercase tracking-wider mt-0.5">Circulating Cegel</p>
                    </div>
                </div>

                {{-- Download / Join --}}
                <div class="bg-[#0b192c]/80 border border-[#1e3a68]/70 rounded-2xl p-5 flex items-center gap-4 hover:border-orange-500/40 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-orange-900/40 border border-orange-500/30 flex items-center justify-center text-2xl shrink-0">
                        ⚔️
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] text-[#5b85cc] font-black uppercase tracking-widest mb-2">Join Us</p>
                        <a href="#"
                           class="block w-full bg-gradient-to-r from-orange-600 to-orange-500 hover:from-orange-500 hover:to-orange-400
                                  text-white font-black italic uppercase tracking-wider py-2 px-4 rounded-xl text-xs text-center
                                  shadow-[0_0_15px_rgba(234,88,12,0.3)] hover:shadow-[0_0_25px_rgba(234,88,12,0.5)] transition-all">
                            Download Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ─── MAIN CONTENT SECTION ─── --}}
<div class="w-full bg-[#0a1628] py-10 border-t border-[#1e3a68]/30">
    <div class="max-w-5xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ═══════════════════════════════════════
                 LEFT COLUMN — Top Killers + Latest News
                 ═══════════════════════════════════════ --}}
            <div class="flex flex-col gap-6">

                {{-- TOP KILLERS (24H) --}}
                <div class="bg-[#0b192c] border border-[#1e3a68]/70 rounded-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-red-900/60 to-red-800/40 border-b border-red-700/30 px-4 py-3 flex items-center gap-2">
                        <span class="text-sm">⚔️</span>
                        <span class="text-white font-black text-xs uppercase tracking-widest">Top Killers (24H)</span>
                    </div>
                    <div class="p-3 flex flex-col gap-2">
                        @php
                            $killColors = ['bg-yellow-500 text-black','bg-gray-300 text-gray-900','bg-orange-700 text-orange-100'];
                            $topKillers = [
                                ['name'=>'---','kills'=>0],
                                ['name'=>'---','kills'=>0],
                                ['name'=>'---','kills'=>0],
                            ];
                        @endphp
                        @foreach($topKillers as $idx => $killer)
                            <div class="flex items-center justify-between bg-[#060c18]/60 rounded-xl px-3 py-2.5 hover:bg-[#1e3a68]/20 transition">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full {{ $killColors[$idx] }} flex items-center justify-center font-black text-[10px] shrink-0">
                                        {{ $idx+1 }}
                                    </span>
                                    <div class="w-7 h-7 rounded-full bg-[#1e3a68]/60 border border-[#2d5090]/40 flex items-center justify-center text-xs">
                                        👤
                                    </div>
                                    <span class="text-white text-xs font-bold">{{ $killer['name'] }}</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-red-400 font-black text-sm leading-none">{{ $killer['kills'] }}</p>
                                    <p class="text-[8px] text-[#5b85cc] uppercase tracking-wider font-bold">kills</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- LATEST NEWS --}}
                <div class="bg-[#0b192c] border border-[#1e3a68]/70 rounded-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-900/60 to-orange-800/40 border-b border-orange-700/30 px-4 py-3 flex items-center gap-2">
                        <span class="text-sm">🔥</span>
                        <span class="text-white font-black text-xs uppercase tracking-widest">Latest News</span>
                    </div>
                    <div class="p-3 flex flex-col divide-y divide-[#1e3a68]/30">
                        @forelse($newsList as $news)
                            <div class="py-2.5 first:pt-1 last:pb-1 cursor-pointer hover:bg-[#1e3a68]/10 rounded-lg px-2 transition"
                                 onclick="showNewsModal({{ json_encode($news->title) }}, {{ json_encode($news->description) }}, {{ json_encode($news->content) }}, '{{ $news->image ? asset('uploads/'.$news->image) : '' }}', '{{ \Carbon\Carbon::parse($news->published_at ?? $news->created_at)->format('d M Y') }}')">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white text-xs font-bold line-clamp-1 hover:text-[#D4AF37] transition">{{ $news->title }}</p>
                                        <p class="text-[#5b85cc] text-[9px] mt-0.5 font-mono">
                                            by <span class="text-[#93c5fd]">admin</span> · {{ \Carbon\Carbon::parse($news->published_at ?? $news->created_at)->format('d M Y') }}
                                        </p>
                                    </div>
                                    @if($news->is_hot)
                                        <span class="bg-red-600 text-white text-[8px] px-1.5 py-0.5 rounded font-black tracking-wider uppercase shrink-0 animate-pulse">HOT</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-[#5b85cc] text-xs text-center py-6">Belum ada berita.</p>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- ═══════════════════════════════════════
                 RIGHT 2 COLUMNS — Events Grid
                 ═══════════════════════════════════════ --}}
            <div class="lg:col-span-2">
                <div class="bg-[#0b192c] border border-[#1e3a68]/70 rounded-2xl overflow-hidden h-full">
                    <div class="bg-gradient-to-r from-[#1e3a68]/80 to-[#0b192c]/60 border-b border-[#1e3a68]/50 px-4 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-sm">🎉</span>
                            <span class="text-white font-black text-xs uppercase tracking-widest">Events</span>
                        </div>
                        <div class="flex gap-2" id="events-nav">
                            <button onclick="prevEvents()" class="w-7 h-7 bg-[#060c18]/60 border border-[#1e3a68]/60 rounded-lg text-[#5b85cc] hover:text-white hover:border-[#D4AF37]/40 transition text-sm">‹</button>
                            <button onclick="nextEvents()" class="w-7 h-7 bg-[#060c18]/60 border border-[#1e3a68]/60 rounded-lg text-[#5b85cc] hover:text-white hover:border-[#D4AF37]/40 transition text-sm">›</button>
                        </div>
                    </div>

                    @php $eventChunks = $events->chunk(4); $totalChunks = $eventChunks->count(); @endphp

                    @if($eventChunks->count() > 0)
                        <div id="events-slider" class="relative overflow-hidden">
                            @foreach($eventChunks as $chunkIdx => $chunk)
                                <div class="events-page p-4 grid grid-cols-2 gap-3 {{ $chunkIdx > 0 ? 'hidden' : '' }}">
                                    @foreach($chunk as $event)
                                        <div class="group relative rounded-xl overflow-hidden border border-[#1e3a68]/40 hover:border-[#D4AF37]/40 transition-all cursor-pointer aspect-[4/3]"
                                             onclick="showEventModal({{ json_encode($event->title) }}, {{ json_encode($event->description ?? '') }}, {{ json_encode($event->content ?? '') }}, '{{ $event->image ? asset('uploads/'.$event->image) : '' }}')">
                                            {{-- Banner Image --}}
                                            @if($event->image)
                                                <img src="{{ asset('uploads/'.$event->image) }}"
                                                     alt="{{ $event->title }}"
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-[#1e3a68] to-[#0b192c] flex items-center justify-center">
                                                    <span class="text-4xl">🎉</span>
                                                </div>
                                            @endif
                                            {{-- Overlay --}}
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                            {{-- Label --}}
                                            <div class="absolute bottom-0 left-0 right-0 p-3">
                                                <p class="text-white font-black text-xs uppercase tracking-wide line-clamp-1 drop-shadow">{{ $event->title }}</p>
                                                <p class="text-[#D4AF37] text-[9px] font-bold mt-0.5">
                                                    {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                    {{-- Fill empty slots --}}
                                    @for($i = $chunk->count(); $i < 4; $i++)
                                        <div class="rounded-xl bg-[#060c18]/40 border border-[#1e3a68]/20 aspect-[4/3] flex items-center justify-center">
                                            <span class="text-[#1e3a68] text-2xl">⚔️</span>
                                        </div>
                                    @endfor
                                </div>
                            @endforeach
                        </div>

                        {{-- Dots --}}
                        @if($totalChunks > 1)
                            <div class="flex justify-center gap-2 pb-4">
                                @for($d = 0; $d < $totalChunks; $d++)
                                    <button onclick="goToEvents({{ $d }})" id="evdot-{{ $d }}"
                                            class="w-2 h-2 rounded-full transition {{ $d === 0 ? 'bg-[#D4AF37] scale-125' : 'bg-[#1e3a68] hover:bg-[#5b85cc]' }}"></button>
                                @endfor
                            </div>
                        @endif
                    @else
                        <div class="p-12 flex flex-col items-center justify-center text-center">
                            <span class="text-5xl mb-3 opacity-20">🎉</span>
                            <p class="text-[#5b85cc] font-bold text-sm">Belum ada event aktif.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- ─── NEWS GRID (All News) ─── --}}
        @if($allNews->count() > 0)
        <div class="mt-8">
            <div class="bg-[#0b192c] border border-[#1e3a68]/70 rounded-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-900/60 to-[#0b192c]/60 border-b border-blue-700/30 px-4 py-3 flex items-center gap-2">
                    <span class="text-sm">🛡️</span>
                    <span class="text-white font-black text-xs uppercase tracking-widest">Adventurer News</span>
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($allNews as $news)
                        <div class="group bg-[#060c18]/60 border border-[#1e3a68]/40 rounded-xl overflow-hidden hover:border-[#D4AF37]/30 transition cursor-pointer"
                             onclick="showNewsModal({{ json_encode($news->title) }}, {{ json_encode($news->description) }}, {{ json_encode($news->content) }}, '{{ $news->image ? asset('uploads/'.$news->image) : '' }}', '{{ \Carbon\Carbon::parse($news->published_at ?? $news->created_at)->format('d M Y') }}')">
                            @if($news->image)
                                <div class="overflow-hidden h-36">
                                    <img src="{{ asset('uploads/'.$news->image) }}" alt="{{ $news->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                            @else
                                <div class="h-36 bg-gradient-to-br from-[#1e3a68]/40 to-[#060c18] flex items-center justify-center">
                                    <span class="text-3xl opacity-20">📰</span>
                                </div>
                            @endif
                            <div class="p-3">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <h3 class="text-white font-black text-xs line-clamp-1 group-hover:text-[#D4AF37] transition">{{ $news->title }}</h3>
                                    @if($news->is_hot)
                                        <span class="bg-red-600 text-white text-[8px] px-1.5 py-0.5 rounded font-black shrink-0 animate-pulse">HOT</span>
                                    @endif
                                </div>
                                <p class="text-[#5b85cc] text-[10px] line-clamp-2 leading-relaxed">{{ $news->description }}</p>
                                <p class="text-[#3a5580] text-[9px] mt-2 font-mono">📅 {{ \Carbon\Carbon::parse($news->published_at ?? $news->created_at)->format('d M Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

{{-- ─── EVENT MODAL ─── --}}
<div id="event-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-md p-4"
     onclick="if(event.target===this)closeEventModal()">
    <div class="bg-[#0b192c] border border-[#1e3a68] rounded-2xl max-w-lg w-full max-h-[85vh] flex flex-col overflow-hidden shadow-[0_0_60px_rgba(30,58,104,0.6)]">
        <div class="p-5 border-b border-[#1e3a68] flex justify-between items-start">
            <h3 id="emodal-title" class="text-white font-black italic uppercase tracking-wide text-base"></h3>
            <button onclick="closeEventModal()" class="text-[#5b85cc] hover:text-white transition">✕</button>
        </div>
        <div class="overflow-y-auto flex-1 p-5 space-y-3">
            <div id="emodal-img-wrap" class="hidden">
                <img id="emodal-img" src="" class="w-full rounded-xl border border-[#1e3a68] object-cover max-h-60" alt="">
            </div>
            <p id="emodal-desc" class="text-[#93c5fd] text-sm leading-relaxed"></p>
            <div id="emodal-content" class="text-blue-100 text-sm prose prose-invert max-w-none"></div>
        </div>
    </div>
</div>



<!-- News Detail Modal -->
<div id="news-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/85 backdrop-blur-md p-4 transition-all duration-300">
    <div class="bg-[#152744] border-2 border-blue-500/30 rounded-2xl w-full max-w-2xl max-h-[85vh] flex flex-col shadow-[0_0_50px_rgba(59,130,246,0.3)] overflow-hidden scale-95 opacity-0 transition-all duration-300" id="news-modal-content">
        <!-- Modal Header -->
        <div class="p-6 border-b border-[#1e3a68] flex justify-between items-start bg-[#0b192c]/50">
            <div>
                <span class="text-[#5b85cc] text-xs font-mono font-bold" id="modal-news-date">DATE</span>
                <h3 class="text-white text-xl font-black italic uppercase mt-1 tracking-wide" id="modal-news-title">NEWS TITLE</h3>
            </div>
            <button onclick="closeNewsModal()" class="text-blue-400 hover:text-white transition p-1 bg-[#1e3a68]/50 rounded-lg hover:bg-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1 flex flex-col gap-4 text-blue-200">
            <div id="modal-news-image-container" class="hidden">
                <img id="modal-news-image" src="" class="w-full h-64 object-cover rounded-xl border border-[#1e3a68]" alt="News Image">
            </div>
            <p id="modal-news-desc" class="text-sm font-bold italic text-blue-300 border-l-4 border-blue-500 pl-3 py-1"></p>
            <div id="modal-news-content" class="text-sm leading-relaxed prose prose-invert max-w-none text-blue-100 mt-2">
                <!-- Rich Editor HTML Content -->
            </div>
        </div>
    </div>
</div>

<script>
    let currentSlide = 0;
    const totalSlides = {{ count($slides) }} + 3;

    function updateSlider() {
        const track = document.getElementById('slider-track');
        track.style.transform = `translateX(-${currentSlide * 100}%)`;
        
        // Update dots highlight status
        for (let i = 0; i < totalSlides; i++) {
            const dot = document.getElementById(`slide-dot-${i}`);
            if (dot) {
                if (i === currentSlide) {
                    dot.classList.add('bg-orange-500', 'scale-125', 'shadow-[0_0_10px_rgba(249,115,22,0.8)]');
                    dot.classList.remove('bg-white/20');
                } else {
                    dot.classList.remove('bg-orange-500', 'scale-125', 'shadow-[0_0_10px_rgba(249,115,22,0.8)]');
                    dot.classList.add('bg-white/20');
                }
            }
        }
    }

    function goToSlide(index) {
        currentSlide = index;
        updateSlider();
        resetAutoplay();
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        updateSlider();
        resetAutoplay();
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        updateSlider();
        resetAutoplay();
    }

    // Autoplay - shift slide automatically every 5 seconds
    let autoplayInterval = setInterval(nextSlide, 5000);

    function resetAutoplay() {
        clearInterval(autoplayInterval);
        autoplayInterval = setInterval(nextSlide, 5000);
    }

    // Events Grid Slider Logic
    let currentEventSlide = 0;
    const eventSlides = document.querySelectorAll('.events-page');
    const totalEventSlides = eventSlides.length;

    function updateEventSlider() {
        if(totalEventSlides === 0) return;
        eventSlides.forEach((slide, idx) => {
            if(idx === currentEventSlide) {
                slide.classList.remove('hidden');
            } else {
                slide.classList.add('hidden');
            }
        });
        
        // Update dots
        for(let i=0; i<totalEventSlides; i++) {
            const dot = document.getElementById(`evdot-${i}`);
            if(dot) {
                if(i === currentEventSlide) {
                    dot.className = "w-2 h-2 rounded-full transition bg-[#D4AF37] scale-125";
                } else {
                    dot.className = "w-2 h-2 rounded-full transition bg-[#1e3a68] hover:bg-[#5b85cc]";
                }
            }
        }
    }

    function prevEvents() {
        if(totalEventSlides <= 1) return;
        currentEventSlide = (currentEventSlide - 1 + totalEventSlides) % totalEventSlides;
        updateEventSlider();
    }

    function nextEvents() {
        if(totalEventSlides <= 1) return;
        currentEventSlide = (currentEventSlide + 1) % totalEventSlides;
        updateEventSlider();
    }

    function goToEvents(idx) {
        currentEventSlide = idx;
        updateEventSlider();
    }

    // Event Modal Logic
    function showEventModal(title, desc, content, imgUrl) {
        document.getElementById('emodal-title').innerText = title;
        document.getElementById('emodal-desc').innerText = desc || '';
        document.getElementById('emodal-content').innerHTML = content || '';
        
        const imgWrap = document.getElementById('emodal-img-wrap');
        const img = document.getElementById('emodal-img');
        
        if (imgUrl) {
            img.src = imgUrl;
            imgWrap.classList.remove('hidden');
        } else {
            img.src = '';
            imgWrap.classList.add('hidden');
        }
        
        document.getElementById('event-modal').classList.remove('hidden');
        document.getElementById('event-modal').classList.add('flex');
    }

    function closeEventModal() {
        document.getElementById('event-modal').classList.add('hidden');
        document.getElementById('event-modal').classList.remove('flex');
    }

    function showNewsModal(title, description, content, imageUrl, dateStr) {
        const modal = document.getElementById('news-modal');
        const modalContent = document.getElementById('news-modal-content');
        
        document.getElementById('modal-news-title').innerText = title;
        document.getElementById('modal-news-date').innerText = "📅 " + dateStr;
        document.getElementById('modal-news-desc').innerText = description;
        
        document.getElementById('modal-news-content').innerHTML = content || '';
        
        const imgContainer = document.getElementById('modal-news-image-container');
        const img = document.getElementById('modal-news-image');
        if (imageUrl) {
            img.src = imageUrl;
            imgContainer.classList.remove('hidden');
        } else {
            img.src = '';
            imgContainer.classList.add('hidden');
        }
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeNewsModal() {
        const modal = document.getElementById('news-modal');
        const modalContent = document.getElementById('news-modal-content');
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }
</script>
@endsection