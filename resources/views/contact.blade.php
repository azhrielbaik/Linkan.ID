<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Linkan</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Font & Tailwind -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #e3f3f6;
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

        .orange {
            color: #ff6619;
        }

        .bg-orange {
            background-color: #ff6619;
        }

        .bg-orange:hover {
            background-color: #e35b17;
        }
    </style>
</head>
<body>
    <div class="background-gradient"></div>

    <div class="max-w-2xl mx-auto p-8 mt-20 bg-white shadow-lg rounded-xl relative">
        <!-- Close button -->
        <a href="{{ url('/') }}" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl font-bold">
            &times;
        </a>

        <h2 class="text-4xl font-bold orange mb-8 text-center">Contact Us</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6 text-center">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block mb-2 font-medium text-gray-700">Your Name</label>
                <input type="text" name="name" id="name" placeholder="Nama Anda"
                    class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400"
                    required>
            </div>

            <div>
                <label for="email" class="block mb-2 font-medium text-gray-700">Your Email</label>
                <input type="email" name="email" id="email" placeholder="you@example.com"
                    class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400"
                    required>
            </div>

            <div>
                <label for="message" class="block mb-2 font-medium text-gray-700">Your Message</label>
                <textarea name="message" id="message" rows="5" placeholder="Type your message..."
                    class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400"
                    required></textarea>
            </div>

            <div class="text-center">
                <button type="submit"
                    class="bg-orange text-white font-semibold px-6 py-3 rounded-lg transition duration-200">
                    Send Message
                </button>
            </div>
        </form>
    </div>
</body>
</html>
