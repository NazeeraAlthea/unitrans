{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login - UniTrans</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white min-h-screen flex items-center justify-center">
    @if ($errors->any())
        <div class="mb-4">
            <div class="fixed right-0 top-0 mt-3 mr-3 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-center"
                role="alert">
                {{ $errors->first() }}
            </div>
        </div>
    @endif

    <div class="fixed top-10 px-10 text-xl font-bold text-gray-900 flex justify-between w-full">
        <a href="{{ route('home') }}">Unitrans</a>
    </div>


    <div class="w-full max-w-5xl flex flex-col md:flex-row shadow-lg rounded-2xl overflow-hidden bg-white">
        <!-- LEFT SIDE: Login Form -->
        <div class="flex-1 flex flex-col justify-center p-12">
            <h2 class="text-3xl font-bold mb-2">Sign In</h2>
            <p class="text-gray-500 mb-4">
                Don't have an account?
                <a href="{{ route('register-mahasiswa') }}" class="text-blue-500 hover:underline">Create now</a>
            </p>
            <form method="POST" action="{{ route('login-mahasiswa') }}" class="space-y-5">
                @csrf
                <!-- Email -->
                <input name="email" type="email" placeholder="Email"
                    class="w-full border border-gray-300 rounded-full px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    required>
                <!-- Password -->
                <input name="password" type="password" placeholder="Password"
                    class="w-full border border-gray-300 rounded-full px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    required>

                <div class="g-recaptcha" data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"></div>
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                @if ($errors->has('g-recaptcha-response'))
                    <div class="text-red-500 text-sm mt-1">
                        {{ $errors->first('g-recaptcha-response') }}
                    </div>
                @endif

                <button type="submit"
                    class="w-full bg-gray-800 text-white rounded-full py-3 font-semibold text-lg shadow hover:bg-gray-900 transition">
                    Sign In
                </button>
            </form>

            <!-- Divider -->
            <div class="flex items-center my-6">
                <hr class="flex-1 border-t border-gray-300">
                <span class="mx-3 text-gray-400 text-sm">or</span>
                <hr class="flex-1 border-t border-gray-300">
            </div>
            <!-- Google button (DISABLED) -->
            <a href="{{ route('google.redirect') }}"
                class="w-full flex items-center justify-center border border-gray-400 rounded-full py-3 font-semibold text-base bg-white shadow-sm hover:bg-gray-50">
                <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-5 h-5 mr-3" alt="Google logo">
                Sign In with Google
            </a>

        </div>
        <!-- RIGHT SIDE: Image -->
        <div class="flex-1 flex items-center justify-center relative p-10">
            <div class="relative">
                <!-- Main image -->
                <img src="{{ asset('images/homeImage.png') }}" alt="Transport">
            </div>
        </div>

    </div>

</body>

</html>
