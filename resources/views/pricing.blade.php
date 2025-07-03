
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - LINKAN</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
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

        .background-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(240,244,255,0.8) 0%, rgba(255,255,255,0.9) 100%);
            z-index: -1;
        }


        /* Pricing Section */
        .pricing-container {
            max-width: 800px;
            margin: 2rem auto;
            margin-top: 20px;
            padding: 2rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .pricing-title {
            text-align: center;
            color: #FF7A00;
            font-size: 28px;
            margin-bottom: 2rem;
        }

        .feature-list {
            list-style: none;
        }

        .feature-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }

        .feature-name {
            color: #666;
        }

        .check-icon {
            color: #FF7A00;
            font-size: 20px;
        }

        .transaction-fee {
            color: #FF7A00;
        }

        .get-started-btn {
            display: block;
            width: fit-content;
            margin: 2rem auto 0;
            padding: 0.8rem 2rem;
            background-color: #FF7A00;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        
    </style>
</head>
<body>
<div class="background-gradient"></div>
    <div class="pricing-container">
        <h1 class="pricing-title">Starter Free Forever</h1>
        
        <ul class="feature-list">
            <li class="feature-item">
                <span class="feature-name">Unlimited Link</span>
                <span class="check-icon">✓</span>
            </li>
            <li class="feature-item">
                <span class="feature-name">Digital Product Store</span>
                <span class="check-icon">✓</span>
            </li>
            <li class="feature-item">
                <span class="feature-name">Statistic / Traffic</span>
                <span class="check-icon">✓</span>
            </li>
            <li class="feature-item">
                <span class="feature-name">Link Thumbnails</span>
                <span class="check-icon">✓</span>
            </li>
            <li class="feature-item">
                <span class="feature-name">Templates</span>
                <span class="check-icon">✓</span>
            </li>
            <li class="feature-item">
                <span class="feature-name">Custom Background</span>
                <span class="check-icon">✓</span>
            </li>
            <li class="feature-item">
                <span class="feature-name">About Me</span>
                <span class="check-icon">✓</span>
            </li>
            <li class="feature-item">
                <span class="feature-name">Email Notification</span>
                <span class="check-icon">✓</span>
            </li>
            <li class="feature-item">
                <span class="feature-name">Donation Place</span>
                <span class="check-icon">✓</span>
            </li>
            <li class="feature-item">
                <span class="feature-name">Transaction Fee</span>
                <span class="transaction-fee">5%</span>
            </li>
        </ul>

        <a href="/register" class="get-started-btn">Get Started</a>
    </div>

</body>
</html>
@include('layout.footer')