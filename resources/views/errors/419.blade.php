<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="0;url={{ route('login') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pāradresācija uz pieslēgšanos...</title>
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
    <style>
        body {
            background-color: #0f172a;
            color: #94a3b8;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .box {
            background: #1e293b;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            border: 1px solid #334155;
            max-width: 400px;
        }
        a {
            color: #6366f1;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="box">
        <h3 style="color:#fff; margin-top:0;">Sesija beigusies</h3>
        <p>Jūsu lapas sesija ir beigusies. Notiek pāradresācija uz pieslēgšanās lapu...</p>
        <p><a href="{{ route('login') }}">Klikšķiniet šeit, ja netiekat pāradresēts automātiski</a></p>
    </div>
</body>
</html>
