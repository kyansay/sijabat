<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIJABAT - Dashboard</title>

    <!-- Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin/dashboard.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="relative min-h-screen">

    <!-- BACKGROUND -->
    <div class="absolute inset-0">
        <img src="/images/foto-pejabat.jpeg"
            class="w-full h-full object-cover scale-100 blur-[1px] brightness-100">
    </div>

    <!-- OVERLAY -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-900/20 via-blue-800/20 to-blue-900/20"></div>

    <!-- CONTENT -->
    <div class="relative z-10">

        <!-- HEADER -->
        <header class="bg-[#003d64]/90 backdrop-blur-md text-white py-6 px-4 shadow-lg">
            <div class="max-w-6xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-3 flex justify-center items-center gap-3">
                    <i class="fas fa-passport"></i>
                    SIJABAT
                </h1>
                <p class="text-lg opacity-95">
                    Sistem Informasi Pangkat - Dashboard Monitoring
                </p>
            </div>
        </header>

        <!-- MAIN -->
        <main class="max-w-6xl mx-auto px-4 py-20 mt-6">

            <!-- CARDS -->
            <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-14">

                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-[#003d64]">
                    <h3 class="font-bold mb-2 text-[#003d64] flex items-center gap-2">
                        <i class="fas fa-users"></i>
                        Total Pejabat
                    </h3>
                    <p id="count-total" class="text-4xl font-bold text-[#003d64]">0</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
                    <h3 class="font-bold mb-2 text-[#003d64] flex items-center gap-2">
                        <i class="fas fa-bell"></i>
                        Perlu Notifikasi
                    </h3>
                    <p id="count-perlu-notif" class="text-4xl font-bold text-orange-600">0</p>
                </div>

            </section>

            <!-- TABLE -->
            <section class="bg-white rounded-2xl shadow-lg overflow-hidden">

                <!-- TABLE HEADER -->
                <div class="bg-[#003d64] text-white p-4 items-center flex justify-between">
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <i class="fas fa-table"></i>
                        Data Pejabat
                    </h2>
                        <h2 class="text-xl font-bold flex items-center gap-2" onclick="" style="cursor: hand;">
                            <i class="fas fa-plus"></i>
                            Tambah Pejabat
                        </h2>
                </div>

                <table class="w-full text-center">

                    <!-- HEAD -->
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 font-bold text-[#003d64]">
                                <div class="flex justify-center items-center gap-2">
                                    <i class="fas fa-id-card"></i> NIP
                                </div>
                            </th>
                            <th class="p-3 font-bold text-[#003d64]">
                                <div class="flex justify-center items-center gap-2">
                                    <i class="fas fa-user"></i> Nama
                                </div>
                            </th>
                            <th class="p-3 font-bold text-[#003d64]">
                                <div class="flex justify-center items-center gap-2">
                                    <i class="fas fa-envelope"></i> Email
                                </div>
                            </th>
                            <th class="p-3 font-bold text-[#003d64]">
                                <div class="flex justify-center items-center gap-2">
                                    <i class="fas fa-user-tie"></i> Pangkat
                                </div>
                            </th>
                            <th class="p-3 font-bold text-[#003d64]">
                                <div class="flex justify-center items-center gap-2">
                                    <i class="fas fa-calendar"></i> TMT
                                </div>
                            </th>
                            <th class="p-3 font-bold text-[#003d64]">
                                <div class="flex justify-center items-center gap-2">
                                    <i class="fas fa-info-circle"></i> Status
                                </div>
                            </th>
                            <th class="p-3 font-bold text-[#003d64]">
                                <div class="flex justify-center items-center gap-2">
                                    <i class="fas fa-cogs"></i> Action
                                </div>
                            </th>
                        </tr>
                    </thead>

                    <!-- BODY -->
                    <tbody id="data-tbody">
                        <tr>
                            <td colspan="7" class="text-center p-6">
                                <div class="w-8 h-8 border-4 border-gray-300 border-t-[#003d64] rounded-full mx-auto animate-spin mb-3"></div>
                                <p class="text-[#003d64] font-semibold">Loading...</p>
                            </td>
                        </tr>
                    </tbody>

                </table>

            </section>

        </main>

    </div>

</body>
</html>

</body>

</html>
