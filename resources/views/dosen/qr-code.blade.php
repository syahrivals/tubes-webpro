@extends('layouts.app')

@section('title', 'QR Code Absensi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body text-center">
                <h2 class="card-title mb-4">QR Code Absensi</h2>
                <p class="text-muted mb-4">QR Code ini akan expired dalam <span id="countdown" class="fw-bold text-danger">15:00</span></p>
                
                <div class="mb-4">
                    <div id="qrcode" class="d-inline-block p-4 bg-white border rounded"></div>
                </div>
                
                <div class="mb-3">
                    <p class="text-muted mb-2">Token:</p>
                    <code class="fs-6">{{ $token }}</code>
                </div>
                
                <div class="mb-3">
                    <p class="text-muted mb-2">Expired Hingga:</p>
                    <p class="mb-0">{{ $expiresAt->format('d M Y H:i:s') }}</p>
                </div>
                
                <a href="{{ route('dosen.dashboard') }}" class="btn btn-primary">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    const token = @json($token);
    const expiresAt = new Date(@json($expiresAtTimestamp));
    
    new QRCode(document.getElementById("qrcode"), {
        text: token,
        width: 300,
        height: 300
    });
    
    function updateCountdown() {
        const now = new Date().getTime();
        const distance = expiresAt.getTime() - now;
        
        if (distance < 0) {
            document.getElementById("countdown").textContent = "EXPIRED";
            document.getElementById("countdown").classList.add("text-danger");
            return;
        }
        
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        document.getElementById("countdown").textContent = 
            String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
    }
    
    updateCountdown();
    setInterval(updateCountdown, 1000);
</script>
@endsection

