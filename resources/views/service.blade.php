
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linkan - Powering Creators Economy</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    @include('layout.header')
    <style>
                * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color:#e3f3f6;
            overflow-x: hidden;
            padding-top: 80px;
        }

        .logo {
            font-weight: bold;
            font-size: 24px;
            text-decoration: none;
            color: black;
        }

        .logo span {
            color: #FF7A00;
        }
        
        .Line {
            display: block;
            width: 60%;
            max-width: 450px; /* Maksimal panjang garis */
            height: 10px;
            background-color: #CFDEE1;
            margin: 10px auto;
            border-radius: 3px;
        }

        .background-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(240,244,255,0.8) 0%, rgba(255,255,255,0.9) 100%);
            z-index: -1;
        }

        .Line-hero {
            display: block;
            width: 60%;
            max-width: 450px;
            height: 10px;
            background-color: #ced6d6;
            margin: 10px auto;
            border-radius: 3px;
            position: relative;
            left: -100px; /* Geser ke kiri sejauh 30px */
        }
        
    </style>   
</head>
<body>
<div class="background-gradient"></div>
<main class="hero">
        <div class="hero-content" id="heroContent">
            <h1 class="hero-title">Digital marketing <span>for </span> your business</h1>
            <hr class="Line-hero">
            <p class="hero-description">optimize digital marketing in your business through billions of users on various effective internet marketing channels</p>
            <div class="create-section">
                <a href="{{ route('register') }}">
                    <button class="create-button">Get Service</button>
                </a>
            </div>

        </div>
        <div class="hero-image" id="heroImage">
            <div class="phone-mockup">
                <img src="{{ asset('images/service.png') }}" alt="Mobile App Preview">
            </div>
        </div>
</main>
</body>
</html>
@include('layout.footer')