<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seal Viking - Enter World</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Animasi Logo Melayang */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        /* CSS Spinner Uiverse.io */
        .spinner {
            width: 70.4px;
            height: 70.4px;
            --clr: rgb(247, 197, 159);
            --clr-alpha: rgba(247, 197, 159, 0.1); /* Diubah ke rgba agar stabil */
            animation: spinner 1.6s infinite ease;
            transform-style: preserve-3d;
        }
        .spinner > div {
            background-color: var(--clr-alpha);
            height: 100%;
            position: absolute;
            width: 100%;
            border: 3.5px solid var(--clr);
        }
        .spinner div:nth-of-type(1) { transform: translateZ(-35.2px) rotateY(180deg); }
        .spinner div:nth-of-type(2) { transform: rotateY(-270deg) translateX(50%); transform-origin: top right; }
        .spinner div:nth-of-type(3) { transform: rotateY(270deg) translateX(-50%); transform-origin: center left; }
        .spinner div:nth-of-type(4) { transform: rotateX(90deg) translateY(-50%); transform-origin: top center; }
        .spinner div:nth-of-type(5) { transform: rotateX(-90deg) translateY(50%); transform-origin: bottom center; }
        .spinner div:nth-of-type(6) { transform: translateZ(35.2px); }

        @keyframes spinner {
            0% { transform: rotate(45deg) rotateX(-25deg) rotateY(25deg); }
            50% { transform: rotate(45deg) rotateX(-385deg) rotateY(25deg); }
            100% { transform: rotate(45deg) rotateX(-385deg) rotateY(385deg); }
        }
    </style>
</head>
<body class="bg-gray-900 m-0 p-0 overflow-hidden">

    <div id="loader-overlay" class="fixed inset-0 z-50 hidden flex-col items-center justify-center bg-blue-900/80 backdrop-blur-sm transition-opacity duration-300">
        <div class="spinner mb-12">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        <p class="text-orange-200 font-semibold tracking-[0.2em] animate-pulse drop-shadow-md">
            CONNECTING TO SERVER...
        </p>
    </div>

    <div class="relative w-full h-screen bg-cover bg-center bg-no-repeat flex flex-col justify-center items-center" 
         style="background-image: url('{{ asset('img/bg.png') }}'); box-shadow: inset 0 0 100px rgba(0,0,0,0.8);">
        
        <div class="absolute inset-0 bg-black/30"></div>

        <div class="relative z-10 flex flex-col items-center">
            
            <img src="{{ asset('img/logo.png') }}" alt="Seal Viking Logo" class="w-80 md:w-96 mb-6 animate-float drop-shadow-2xl">
            
            <p class="text-white text-sm md:text-base font-semibold tracking-[0.3em] mb-8 drop-shadow-md">
                ADVENTURE AWAITS IN VIKING
            </p>

            <button onclick="enterWorld()" 
               class="bg-gradient-to-b from-orange-400 to-orange-600 hover:from-orange-300 hover:to-orange-500 text-white font-bold text-lg md:text-xl py-3 px-12 rounded-full shadow-[0_0_20px_rgba(249,115,22,0.6)] hover:shadow-[0_0_30px_rgba(249,115,22,0.9)] transition-all duration-300 transform hover:scale-105 uppercase tracking-wider border border-orange-300/50 cursor-pointer">
                Enter World
            </button>

        </div>

        <div class="absolute bottom-8 z-10 flex items-center justify-center w-full">
            <div class="w-16 h-[1px] bg-gray-500/50 mx-4"></div>
            <p class="text-gray-400 text-xs tracking-[0.2em] uppercase">Viking Server 2026</p>
            <div class="w-16 h-[1px] bg-gray-500/50 mx-4"></div>
        </div>

    </div>

    <script>
        function enterWorld() {
            // 1. Munculin loader overlay-nya (hapus class 'hidden', tambah class 'flex')
            const loader = document.getElementById('loader-overlay');
            loader.classList.remove('hidden');
            loader.classList.add('flex');
            
            // 2. Tahan loadingnya selama 1.5 detik (1500 ms), setelah itu redirect ke /home
            setTimeout(() => {
                window.location.href = '/home';
            }, 1500);
        }
    </script>
</body>
</html>