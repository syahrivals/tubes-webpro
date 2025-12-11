
@extends('layouts.app')

@section('title', 'Scan QR Absensi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body text-center">
                <h2 class="card-title mb-4">Scan QR untuk Absen</h2>

                <div id="reader" style="width:100%;max-width:480px;height:380px;margin:auto;"></div>

                <p class="mt-3 text-muted">Jika kamera tidak bekerja, Anda juga dapat menempelkan token QR di bawah ini:</p>
                <form id="manualForm" method="POST" action="{{ route('mahasiswa.scan.store') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input name="token" id="manualToken" class="form-control" placeholder="Tempel token QR di sini">
                        <button class="btn btn-primary">Kirim</button>
                    </div>
                </form>

                <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>

<script src="/vendor/html5-qrcode/html5-qrcode.min.js"></script>
<script>
    const html5QrCode = new Html5Qrcode("reader");

    function onScanSuccess(decodedText, decodedResult) {
        html5QrCode.stop().then(() => {
            // submit token to server
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('mahasiswa.scan.store') }}';

            const tokenInput = document.createElement('input');
            tokenInput.name = 'token';
            tokenInput.value = decodedText;
            form.appendChild(tokenInput);

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            document.body.appendChild(form);
            form.submit();
        });
    }

    function onScanError(errorMessage) {
        // ignore for now
    }

    Html5Qrcode.getCameras().then(cameras => {
        if (cameras && cameras.length) {
            let cameraId = cameras[0].id;
            // Jika di HP, cari kamera belakang
            if (cameras.length > 1) {
                const backCam = cameras.find(cam => cam.label.toLowerCase().includes('back'));
                if (backCam) cameraId = backCam.id;
            }
            html5QrCode.start(
                cameraId,
                { fps: 10, qrbox: { width: 320, height: 320 } },
                onScanSuccess,
                onScanError
            ).catch(err => {
                console.warn('Tidak bisa mulai kamera:', err);
            });
        }
    }).catch(err => console.warn(err));
</script>

@endsection
