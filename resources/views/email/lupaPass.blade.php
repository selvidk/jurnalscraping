<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Toko Kue Kiyowolicious</title>
    <style>
        body {
            background-color:#cbced0;
            margin:0;
        }
        /* .card {
            background-color:#fff;
            padding:20px;
            margin:20%;
            text-align:center;
            margin:0px auto;
            width: 580px;
            max-width: 580px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        } */
        .garis {
            width: 75%;
        }
        .button {
            background-color: #3b7ddd;
            border: none;
            color: white;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px
        }

    </style>
</head>
<body>
    <div class="w3-container" align="center">
        <h3 class="">Lupa Password</h3>
        <hr class="garis">
        <p>Hallo <strong>{{ $nama_admin }}</strong></p>
        <p>Silahkan klik tombol dibawah ini untuk mengatur ulang password Anda</p>
        <a href="{{ route('auth.password.reset', $token) }}"><button class="button">Atur Password</button></a>
    </div>
</body>
</html>