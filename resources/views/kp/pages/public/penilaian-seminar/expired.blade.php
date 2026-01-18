<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/dist/css/adminlte.min.css">
    <style>
        body { background: #f4f6f9; min-height: 100vh; display: flex; align-items: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body py-5">
                        <i class="fas fa-clock text-warning" style="font-size: 5rem;"></i>
                        <h2 class="mt-4">{{ $title }}</h2>
                        <p class="text-muted">{{ $message }}</p>
                        @if($used_at)
                        <p class="small text-secondary">Digunakan pada: {{ $used_at->translatedFormat('d F Y, H:i') }} WIB</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>




