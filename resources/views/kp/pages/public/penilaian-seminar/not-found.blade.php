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
                        <i class="fas fa-exclamation-triangle text-danger" style="font-size: 5rem;"></i>
                        <h2 class="mt-4">{{ $title }}</h2>
                        <p class="text-muted">{{ $message }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>




