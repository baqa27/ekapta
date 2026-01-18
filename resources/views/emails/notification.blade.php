<!DOCTYPE html>
<html>
<head>
    <title>EKAPTA</title>
</head>
<body>
<h1>{{ $details['title'] }}</h1>

{!! nl2br($details['message']) !!}

<p>
    Terima kasih,
    <br>
    {{-- {{ config('app.name') }} --}}
    <a href="{{ URL::to('/') }}">{{ URL::to('') }}</a>
    <br><br>
    Pesan ini terkirim secara otomatis, tidak perlu dibalas.
</p>
</body>
</html>
