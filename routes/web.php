<?php

use App\Models\News;
use App\Models\Event;
use App\Models\CarouselSlide;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\PlayerProfileController;
use Illuminate\Support\Facades\Route;

// Halaman Intro (Landing Page)
Route::get('/', function () {
    return view('pages.intro');
});

// Halaman Utama Web
Route::get('/home', function () {
    // Website DB queries
    $newsList = News::where('published_at', '<=', now())
        ->where('is_hot', true)->orderBy('published_at', 'desc')->take(5)->get();
    $allNews  = News::where('published_at', '<=', now())
        ->orderBy('published_at', 'desc')->get();
    $events   = Event::where('is_active', true)->orderBy('event_date', 'asc')->get();
    $slides   = CarouselSlide::where('is_active', true)->orderBy('order', 'asc')->get();

    // Game DB stats (graceful fallback jika belum konek)
    $playersOnline  = 0;
    $cegelCirculate = 0;
    try {
        $playersOnline = \Illuminate\Support\Facades\DB::connection('seal_member')
            ->table('idtable1')->count();
        $cegelCirculate = \Illuminate\Support\Facades\DB::connection('seal_game')
            ->table('coin')->sum('cegel'); // ⚠️ SESUAIKAN nama kolom 'cegel'
    } catch (\Exception $e) {
        // Game DB belum terkonek — tampilkan 0
    }

    return view('pages.home', [
        'newsList'        => $newsList,
        'allNews'         => $allNews,
        'events'          => $events,
        'slides'          => $slides,
        'playersOnline'   => $playersOnline,
        'cegelCirculate'  => $cegelCirculate,
    ]);
});

// Auth Routes (Players — login via game account ID)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Protected Routes (Requires Login) ───
Route::middleware(['auth'])->group(function () {
    // Top-Up Routes
    Route::get('/topup', [TopUpController::class, 'index'])->name('topup');
    Route::post('/topup/checkout', [TopUpController::class, 'checkout'])->name('topup.checkout');
    Route::post('/topup/claim', [TopUpController::class, 'claim'])->name('topup.claim');

    // Marketplace
    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace');
    Route::post('/marketplace/buy-cash/{id}', [MarketplaceController::class, 'buyCashItem'])->name('marketplace.buy.cash');

    // Guild & Rank (Placeholders for now)
    Route::get('/guild', function() {
        return "Guild page is coming soon!";
    })->name('guild');
    
    Route::get('/rank', function() {
        return "Rank page is coming soon!";
    })->name('rank');
});

// Midtrans Notification Callback Webhook (Exempt from CSRF)
Route::post('/api/midtrans/callback', [TopUpController::class, 'callback'])->name('midtrans.callback');

// ─── Player Profile (harus login) ───
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [PlayerProfileController::class, 'index'])->name('profile');
    Route::post('/profile/link-game', [PlayerProfileController::class, 'linkGameAccount'])->name('profile.link-game');
    Route::delete('/profile/unlink-game', [PlayerProfileController::class, 'unlinkGameAccount'])->name('profile.unlink-game');
});