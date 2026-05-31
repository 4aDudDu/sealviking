@extends('layouts.app')

@section('content')
<div class="relative w-full min-h-screen flex items-center justify-center bg-cover bg-center overflow-hidden pt-32 pb-24 px-4"
     style="background-image: url('{{ asset('img/hero-bg.jpg') }}');">

    {{-- Dark overlay --}}
    <div class="absolute inset-0 bg-[#060c18]/85 backdrop-blur-sm z-0"></div>

    <div class="relative z-10 w-full max-w-md">

        {{-- Logo --}}
        <div class="flex justify-center mb-6">
            <img src="{{ asset('img/logo.png') }}" alt="Seal Viking" class="w-20 drop-shadow-[0_0_20px_rgba(212,175,55,0.5)]">
        </div>

        {{-- Card --}}
        <div class="bg-[#0b192c]/90 border-2 border-[#1e3a68] rounded-2xl shadow-[0_0_60px_rgba(30,58,104,0.6)] backdrop-blur-lg overflow-hidden p-8">

            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-white font-black italic text-2xl uppercase tracking-widest">ENTER VALHALLA</h1>
                <p class="text-[#5b85cc] text-xs mt-1">Login menggunakan ID akun Seal Viking kamu</p>
            </div>

            {{-- Login Form —— pakai game account ID --}}
            <form id="form-login" action="{{ route('login') }}" method="POST" class="flex flex-col gap-5">
                @csrf

                {{-- Game Account ID --}}
                <div>
                    <label class="block text-[#5b85cc] text-xs font-black uppercase tracking-widest mb-2">
                        ⚔️ Account ID
                    </label>
                    <input
                        id="game_id"
                        type="text"
                        name="game_id"
                        value="{{ old('game_id') }}"
                        required
                        placeholder="Username game kamu"
                        autocomplete="username"
                        class="w-full bg-[#060c18] border-2 border-[#1e3a68] text-white px-4 py-3 rounded-xl outline-none
                               focus:border-[#D4AF37]/60 focus:shadow-[0_0_15px_rgba(212,175,55,0.15)] transition font-bold
                               placeholder:text-[#3a5580] placeholder:font-normal"
                    >
                    @error('game_id')
                        <p class="text-red-400 text-xs mt-1.5 font-semibold flex items-center gap-1">
                            <span>❌</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-[#5b85cc] text-xs font-black uppercase tracking-widest mb-2">
                        🔑 Password
                    </label>
                    <div class="relative">
                        <input
                            id="login-password"
                            type="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            autocomplete="current-password"
                            class="w-full bg-[#060c18] border-2 border-[#1e3a68] text-white px-4 py-3 pr-12 rounded-xl outline-none
                                   focus:border-[#D4AF37]/60 focus:shadow-[0_0_15px_rgba(212,175,55,0.15)] transition font-bold
                                   placeholder:text-[#3a5580]"
                        >
                        {{-- Toggle show/hide password --}}
                        <button type="button" onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[#5b85cc] hover:text-white transition text-lg">
                            👁
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-400 text-xs mt-1.5 font-semibold">❌ {{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember --}}
                <div class="flex items-center gap-2 text-xs">
                    <input type="checkbox" id="remember" name="remember" class="accent-[#D4AF37] w-4 h-4 rounded">
                    <label for="remember" class="text-[#5b85cc] cursor-pointer select-none">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-gradient-to-r from-[#D4AF37] to-[#f0c040] hover:from-[#f0c040] hover:to-[#D4AF37]
                               text-black font-black italic uppercase tracking-widest py-3.5 rounded-xl
                               shadow-[0_0_20px_rgba(212,175,55,0.4)] hover:shadow-[0_0_30px_rgba(212,175,55,0.6)]
                               transition-all duration-300 mt-2 text-sm">
                    ⚔️ Masuk ke Viking
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-4 my-6">
                <div class="flex-1 h-px bg-[#1e3a68]"></div>
                <span class="text-[#3a5580] text-xs font-bold">ATAU</span>
                <div class="flex-1 h-px bg-[#1e3a68]"></div>
            </div>

            {{-- Info box — tidak perlu register --}}
            <div class="bg-[#1e3a68]/30 border border-[#1e3a68]/60 rounded-xl p-4 text-center">
                <p class="text-[#5b85cc] text-xs leading-relaxed">
                    Belum punya akun?<br>
                    <span class="text-[#93c5fd] font-bold">Daftar langsung di game client</span> Seal Viking,
                    lalu login di sini menggunakan ID yang sama.
                </p>
                <a href="#" class="inline-block mt-3 text-[#D4AF37] font-bold text-xs hover:text-yellow-300 transition">
                    📥 Download Game Client →
                </a>
            </div>

            {{-- Admin note --}}
            <p class="text-center text-[10px] text-[#1e3a68] mt-6">
                GM / Admin? <a href="/admin/login" class="text-[#3a5580] hover:text-[#5b85cc] transition">Login via Panel Admin →</a>
            </p>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('login-password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endsection
