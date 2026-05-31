@extends('layouts.app')

@section('content')
<div class="relative w-full min-h-screen bg-cover bg-center overflow-hidden pt-32 pb-24 px-4" 
     style="background-image: url('{{ asset('img/hero-bg.jpg') }}');">
    
    <!-- Dark Viking Overlay -->
    <div class="absolute inset-0 bg-[#060c18]/85 backdrop-blur-sm z-0"></div>

    <div class="relative z-10 container mx-auto max-w-5xl mt-12">
        <!-- Dashboard Header -->
        <div class="relative bg-[#152744]/75 border-2 border-[#1e3a68] rounded-2xl shadow-xl p-6 mb-8 backdrop-blur-lg flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="absolute -top-5 left-6 bg-gradient-to-b from-[#213f6d] to-[#152744] border-2 border-[#1e3a68] rounded-t-xl px-4 py-1.5 flex items-center shadow-sm">
                <span class="text-blue-100 font-bold italic text-sm">WARRIOR DASHBOARD</span>
            </div>

            <div>
                <h2 class="text-white text-2xl font-black italic tracking-wide uppercase">WARRIOR PORTAL</h2>
                <p class="text-[#5b85cc] text-xs font-mono mt-1">LOGGED IN AS: <span class="text-white font-bold">{{ auth()->user()->name }}</span> ({{ auth()->user()->email }})</p>
            </div>

            <div class="flex items-center gap-6 bg-[#0b192c] border border-[#1e3a68] p-4 rounded-xl">
                <div class="flex flex-col items-center">
                    <span class="text-[10px] text-[#5b85cc] font-black uppercase tracking-wider">Gold Coins</span>
                    <span class="text-yellow-400 text-2xl font-black italic mt-1 drop-shadow-[0_0_8px_rgba(234,179,8,0.4)]">
                        🟡 {{ number_format(auth()->user()->coins) }}
                    </span>
                </div>
                <div class="w-px h-10 bg-[#1e3a68]"></div>
                <div class="flex flex-col items-center">
                    <span class="text-[10px] text-[#5b85cc] font-black uppercase tracking-wider">Crystal Diamonds</span>
                    <span class="text-blue-400 text-2xl font-black italic mt-1 drop-shadow-[0_0_8px_rgba(59,130,246,0.4)]">
                        💎 {{ number_format(auth()->user()->diamonds) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Coin Packages -->
        <div class="relative bg-[#152744]/40 border-2 border-[#1e3a68] rounded-2xl shadow-lg p-6 pt-10 mb-10 backdrop-blur-md">
            <div class="absolute -top-5 left-6 bg-gradient-to-b from-yellow-600 to-yellow-800 border-2 border-yellow-500 rounded-t-xl px-4 py-1.5 flex items-center shadow-sm">
                <span class="text-white font-black italic text-sm tracking-wider">🟡 GOLD COIN PACKAGES</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($packages['coins'] as $package)
                    <div class="relative overflow-hidden rounded-xl border border-[#1e3a68] bg-[#0b192c]/85 p-5 flex flex-col justify-between group hover:border-yellow-500 transition-all duration-300">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-yellow-500/5 rounded-full blur-2xl group-hover:bg-yellow-500/10 transition duration-300"></div>
                        
                        <div>
                            <div class="text-4xl mb-4 group-hover:scale-110 transition duration-300 inline-block">{{ $package['icon'] }}</div>
                            <h3 class="text-white font-black italic text-xl uppercase tracking-wide group-hover:text-yellow-400 transition">{{ $package['name'] }}</h3>
                            <p class="text-gray-400 text-xs mt-2 leading-relaxed min-h-[40px]">{{ $package['description'] }}</p>
                            
                            <div class="text-yellow-400 text-3xl font-black mt-4 italic">
                                +{{ number_format($package['qty']) }} <span class="text-xs uppercase font-bold text-gray-400">Coins</span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="border-t border-[#1e3a68] pt-4 mb-4 flex justify-between items-center">
                                <span class="text-gray-400 text-[10px] font-black uppercase tracking-wider">Price</span>
                                <span class="text-white font-bold font-mono">Rp {{ number_format($package['price']) }}</span>
                            </div>
                            <button onclick="payTopUp('{{ $package['id'] }}', 'coin')" class="w-full bg-gradient-to-r from-yellow-600 to-yellow-500 hover:from-yellow-500 hover:to-yellow-400 text-white font-black italic uppercase tracking-widest py-2.5 rounded-lg shadow-[0_0_10px_rgba(234,179,8,0.2)] hover:shadow-[0_0_15px_rgba(234,179,8,0.4)] transition duration-300">
                                Purchase Now
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Diamond Packages -->
        <div class="relative bg-[#152744]/40 border-2 border-[#1e3a68] rounded-2xl shadow-lg p-6 pt-10 backdrop-blur-md">
            <div class="absolute -top-5 left-6 bg-gradient-to-b from-blue-600 to-blue-800 border-2 border-blue-500 rounded-t-xl px-4 py-1.5 flex items-center shadow-sm">
                <span class="text-white font-black italic text-sm tracking-wider">💎 CRYSTAL DIAMOND PACKAGES</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($packages['diamonds'] as $package)
                    <div class="relative overflow-hidden rounded-xl border border-[#1e3a68] bg-[#0b192c]/85 p-5 flex flex-col justify-between group hover:border-blue-500 transition-all duration-300">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl group-hover:bg-blue-500/10 transition duration-300"></div>
                        
                        <div>
                            <div class="text-4xl mb-4 group-hover:scale-110 transition duration-300 inline-block">{{ $package['icon'] }}</div>
                            <h3 class="text-white font-black italic text-xl uppercase tracking-wide group-hover:text-blue-400 transition">{{ $package['name'] }}</h3>
                            <p class="text-gray-400 text-xs mt-2 leading-relaxed min-h-[40px]">{{ $package['description'] }}</p>
                            
                            <div class="text-blue-400 text-3xl font-black mt-4 italic">
                                +{{ number_format($package['qty']) }} <span class="text-xs uppercase font-bold text-gray-400">Diamonds</span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="border-t border-[#1e3a68] pt-4 mb-4 flex justify-between items-center">
                                <span class="text-gray-400 text-[10px] font-black uppercase tracking-wider">Price</span>
                                <span class="text-white font-bold font-mono">Rp {{ number_format($package['price']) }}</span>
                            </div>
                            <button onclick="payTopUp('{{ $package['id'] }}', 'diamond')" class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-400 text-white font-black italic uppercase tracking-widest py-2.5 rounded-lg shadow-[0_0_10px_rgba(59,130,246,0.2)] hover:shadow-[0_0_15px_rgba(59,130,246,0.4)] transition duration-300">
                                Purchase Now
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Load Midtrans Snap JS SDK -->
@if(config('midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif

<script>
    function payTopUp(packageId, type) {
        // Trigger checkout request to the backend
        fetch('{{ route("topup.checkout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: json = JSON.stringify({
                package_id: packageId,
                type: type
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Checkout Error: ' + data.error);
                return;
            }

            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    // Send local success notification to instantly credit user in sandbox/development mode
                    fetch('{{ route("topup.claim") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: result.order_id || data.order_id,
                            status: 'success'
                        })
                    })
                    .then(res => res.json())
                    .then(() => {
                        alert("⚔️ Payment complete! Gold/Diamonds credited to your account.");
                        location.reload();
                    })
                    .catch(err => {
                        console.error('Local claim failed, will reload:', err);
                        location.reload();
                    });
                },
                onPending: function(result) {
                    alert("🛡️ Payment pending! Please complete the transaction following instructions.");
                    location.reload();
                },
                onError: function(result) {
                    alert("❌ Payment failed! Transaction cancelled.");
                },
                onClose: function() {
                    alert("🛡️ Transaction closed before completing payment.");
                }
            });
        })
        .catch(err => {
            console.error(err);
            alert('Failed to initiate payment. Please check console logs.');
        });
    }
</script>
@endsection
