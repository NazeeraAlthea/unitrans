{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Create Account - TransQuest</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen flex items-center justify-center">
    <div class="w-full max-w-5xl flex flex-col md:flex-row shadow-lg rounded-2xl overflow-hidden bg-white">
        <!-- LEFT SIDE: Register Form -->
        <div class="flex-1 flex flex-col justify-center p-12">
            <div class="mb-8">
                <h1 class="font-bold text-2xl mb-2">TransQuest</h1>
            </div>
            <h2 class="text-3xl font-bold mb-2">Create an account</h2>
            <p class="mt-4 text-sm">Already have an account? <a href="{{ route('login-mahasiswa') }}" class="text-blue-500 hover:underline">Sign in</a></p>
            <form method="POST" action="{{ route('register-mahasiswa') }}" class="space-y-4 mt-4">
                @csrf
                <input type="text" name="nama" placeholder="Name" value="{{ old('nama') }}"
                    class="w-full border border-gray-300 rounded-full px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    required>
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded-full px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    required>
                <input type="password" name="password" placeholder="Password"
                    class="w-full border border-gray-300 rounded-full px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    required>
                <input type="password" name="password_confirmation" placeholder="Confirm Password"
                    class="w-full border border-gray-300 rounded-full px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    required>

                @if ($errors->any())
                    <div class="text-red-500 text-sm">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <button type="submit"
                    class="w-full bg-black text-white rounded-full py-3 font-semibold text-lg shadow hover:bg-gray-900 transition">
                    Create Account
                </button>
            </form>
            <!-- Divider -->
            <div class="flex items-center my-6">
                <hr class="flex-1 border-t border-gray-300">
                <span class="mx-3 text-gray-400 text-sm">or</span>
                <hr class="flex-1 border-t border-gray-300">
            </div>
            <!-- Google Sign Up (DISABLED/PLACEHOLDER) -->
            {{-- <button class="w-full flex items-center justify-center border border-gray-400 rounded-full py-3 font-semibold text-base bg-white shadow-sm hover:bg-gray-50">
                <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-5 h-5 mr-3" alt="Google logo">
                Sign Up with Google
            </button> --}}
        </div>
        <!-- RIGHT SIDE: Image -->
        <div class="flex-1 flex items-center justify-center relative p-10">
            <div class="relative">
                <!-- Main image -->
                <img
                    src="{{ asset('images/homeImage.png') }}"
                    alt="Transport"
                    
                >
            </div>
        </div>
    </div>
</body>
</html>
