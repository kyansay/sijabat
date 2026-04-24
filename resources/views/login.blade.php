<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SI-JABAT</title>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/auth/login.js', 'resources/css/base.css'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body class="font-[Plus Jakarta Sans]">
    <div class="bg-blue-300/20">
        <img src="images/bg-login.jpeg" alt="bg-login" class="fixed top-0 left-0 w-full h-full object-cover z-[-1]">

        <div class="min-h-screen flex items-center justify-center p-4">

            <div class="w-full max-w-xl rounded-2xl shadow-2xl overflow-hidden backdrop-blur-md border border-white/30">

                {{-- Header --}}

                <div class="bg-white/40 p-8">
                    <div class="mb-2 items-center justify-center text-center">
                        <img src="images/Logo with Text.png" alt="logo Imigrasi"
                            class=" h-auto item-center content-center mx-auto">
                        <h3 class="text-xl font-semibold text-black ">Selamat Datang</h3>
                        <p class="text-md text-black font-semibold">Silakan masuk ke akun Anda</p>
                    </div>

                    <form id="loginForm" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-md font-semibold text-black mb-1 ">Alamat Email</label>
                            <input type="email" name="email" id="email"
                                placeholder="Contoh: admin@imigrasi.go.id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white/90 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all"
                                required>
                        </div>

                        <div class="relative">
                            <label class="block text-md font-semibold text-black mb-1">Kata Sandi</label>

                            <input type="password" name="password" id="password" placeholder="Masukkan kata sandi Anda"
                                class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg bg-white/90 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all"
                                required>

                            <!-- Icon -->
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-3 top-12 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">

                                <!-- Eye (default) -->
                                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5
                   c4.477 0 8.268 2.943 9.542 7
                   -1.274 4.057-5.065 7-9.542 7
                   -4.477 0-8.268-2.943-9.542-7z" />
                                </svg>

                                <!-- Eye Slash -->
                                <svg id="eyeClose" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19
                   c-4.478 0-8.268-2.943-9.543-7
                   a9.97 9.97 0 012.042-3.368M6.223 6.223
                   A9.956 9.956 0 0112 5c4.478 0 8.268
                   2.943 9.543 7a9.97 9.97 0 01-4.132
                   5.411M15 12a3 3 0 00-3-3m0 0a3 3 0
                   00-2.121.879M12 12l-9 9" />
                                </svg>

                            </button>
                        </div>

                        {{-- Button Lupa Password --}}
                        <div class="justify-end flex ">

                            <a href="/forgot-password" class="text-primary hover:text-blue-500 text-md font-medium">
                                Lupa Kata Sandi?
                            </a>
                        </div>

                        {{-- Button Masuk --}}
                        <button type="submit"
                            class="w-full btn-primary text-white py-2.5 rounded-lg font-medium flex items-center justify-center gap-2">

                            <span id="btnText">Masuk</span>

                            <svg id="btnSpinner" class="w-5 h-5 animate-spin hidden" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
