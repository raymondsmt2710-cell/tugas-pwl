<!DOCTYPE html>
<html>
<head>
    <title>TUBES PWL</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body{
            font-family: Arial;
            background-color: #f5f5f5;
            margin:0;
            padding:0;
        }

        .navbar{
            background-color: #2563eb;
            color:white;
            padding:20px;
        }

        .container{
            padding:40px;
        }

        .card{
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        button{
            background:#2563eb;
            color:white;
            border:none;
            padding:10px 20px;
            border-radius:5px;
            cursor:pointer;
        }
    </style>

</head>
<body>

    <div class="navbar">
        <h2>TUBES PWL</h2>
    </div>

    <div class="container">

        <div class="card">

            <h1>Selamat Datang</h1>

            <p>
                Ini adalah homepage project kelompok kami.
            </p>

            <button>
                Lihat Campaign
            </button>

        </div>

    </div>

</body>
</html>