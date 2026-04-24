<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIJABAT - Dashboard</title>

    <!-- Tailwind dari Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="relative min-h-screen">

    <!-- Background Image (PASTI MUNCUL) -->
    <div class="absolute inset-0">
        <img src="/images/kantor.jpeg" class="w-full h-full object-cover">
    </div>

    <!-- Overlay (dibikin lebih transparan) -->
    <div class="absolute inset-0 bg-blue-900/40"></div>

    <!-- Content -->
    <div class="relative z-10">

        <!-- HEADER -->
        <div class="bg-[#003d64]/80 backdrop-blur-md text-white py-12 px-4 shadow-lg">
            <div class="max-w-6xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-3">
                    <i class="fas fa-passport mr-3"></i>SIJABAT
                </h1>
                <p class="text-lg opacity-95">Sistem Informasi Pangkat - Dashboard Monitoring</p>
            </div>
        </div>

        <!-- MAIN -->
        <div class="max-w-6xl mx-auto px-4 py-8">

            <!-- CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-[#003d64]">
                    <h3 class="font-bold mb-2 text-[#003d64]">Zona Aman</h3>
                    <p id="count-aman" class="text-4xl font-bold text-[#003d64]">0</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
                    <h3 class="font-bold mb-2 text-[#003d64]">Masa Kritis</h3>
                    <p id="count-kritis" class="text-4xl font-bold text-yellow-600">0</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
                    <h3 class="font-bold mb-2 text-[#003d64]">Wajib Peninjauan</h3>
                    <p id="count-bahaya" class="text-4xl font-bold text-red-600">0</p>
                </div>

            </div>

            <!-- TABLE -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-[#003d64] text-white p-4">
                    <h2 class="text-xl font-bold"><i class="fas fa-table mr-2"></i>Data Pejabat</h2>
                </div>

                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left text-[#003d64] font-bold"><i class="fas fa-id-card mr-2"></i>NIP
                            </th>
                            <th class="p-3 text-left text-[#003d64] font-bold"><i class="fas fa-user mr-2"></i>Nama</th>
                            <th class="p-3 text-left text-[#003d64] font-bold"><i class="fas fa-envelope mr-2"></i>Email
                            </th>
                            <th class="p-3 text-left text-[#003d64] font-bold"><i class="fas fa-badge mr-2"></i>Pangkat
                                Sekarang</th>
                            <th class="p-3 text-left text-[#003d64] font-bold"><i class="fas fa-calendar mr-2"></i>TMT
                            </th>
                            <th class="p-3 text-left text-[#003d64] font-bold"><i
                                    class="fas fa-hourglass-end mr-2"></i>Status</th>
                        </tr>
                    </thead>
                    <tbody id="data-tbody">
                        <tr>
                            <td colspan="6" class="text-center p-6">
                                <div
                                    class="w-8 h-8 border-4 border-gray-300 border-t-[#003d64] rounded-full mx-auto animate-spin mb-3">
                                </div>
                                <p class="text-[#003d64] font-semibold">Loading...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        <script>
            document.addEventListener("DOMContentLoaded", async function() {
                const token = localStorage.getItem("token");
                console.log("Token:", token);


                try {
                    const response = await fetch("http://192.168.1.143:8000/api/pejabats", {
                        headers: {
                            "Authorization": "Bearer " + token,
                            "Accept": "application/json"
                        }
                    });

<<<<<<< Updated upstream
                    const result = await response.json();
                    const data = result.data;
=======
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left text-[#003d64] font-bold"><i class="fas fa-id-card mr-2"></i>NIP</th>
                    <th class="p-3 text-left text-[#003d64] font-bold"><i class="fas fa-user mr-2"></i>Nama</th>
                    <th class="p-3 text-left text-[#003d64] font-bold"><i class="fas fa-medal mr-2"></i>Pangkat Sekarang</th>
                    <th class="p-3 text-left text-[#003d64] font-bold"><i class="fas fa-hourglass-end mr-2"></i>Lama Pangkat</th>
                    <th class="p-3 text-left text-[#003d64] font-bold"><i class="fas fa-clipboard-list mr-2"></i>Rundown</th>
                    <th class="p-3 text-left text-[#003d64] font-bold"><i class="fas fa-comment-dots mr-2"></i>Pesan</th>
                </tr>
            </thead>
            <tbody id="data-tbody">
                <tr>
                    <td colspan="6" class="text-center p-6">
                        <div class="w-8 h-8 border-4 border-gray-300 border-t-[#003d64] rounded-full mx-auto animate-spin mb-3"></div>
                        <p class="text-[#003d64] font-semibold">Loading...</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
>>>>>>> Stashed changes

                    let aman = 0,
                        bahaya = 0;
                    const tbody = document.querySelector("#data-tbody");
                    tbody.innerHTML = "";

                    data.forEach(item => {
                        let status = '';

                        if (item.perlu_kenaikan) {
                            status = `<span class="bg-red-500 text-white px-3 py-1 rounded">Bahaya</span>`;
                            bahaya++;
                        } else {
                            status = `<span class="bg-green-500 text-white px-3 py-1 rounded">Aman</span>`;
                            aman++;
                        }

                        tbody.innerHTML += `
                <tr class="border-b">
                    <td class="p-3">${item.nip}</td>
                    <td class="p-3">${item.nama}</td>
                    <td class="p-3">${item.email}</td>
                    <td class="p-3">${item.pangkat_sekarang}</td>
                    <td class="p-3">${item.tmt_pangkat}</td>
                    <td class="p-3">${status}</td>
    try {
        const response = await fetch(`http://192.168.1.143:8000/api/pejabat`, {
            method : "GET", 
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });

        const result = await response.json();
        const data = result.data;

        let aman = 0, bahaya = 0;
        const tbody = document.querySelector("#data-tbody");
        tbody.innerHTML = "";

        data.forEach(item => {

            // ✅ ambil data dari API
            const nip = item.nip;
            const nama = item.nama;
            const pangkat = item.pangkat;
            const lama = item.lama_pangkat;
            const rundown = item.rundown ?? '-';
            const pesan = item.pesan;

            if (item.perlu_kenaikan) {
                status = `<span class="bg-red-500 text-white px-3 py-1 rounded">Bahaya</span>`;
                bahaya++;
            } else {
                status = `<span class="bg-green-500 text-white px-3 py-1 rounded">Aman</span>`;
                aman++;
            }

            tbody.innerHTML += `
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">${nip}</td>
                    <td class="p-3">${nama}</td>

                    <td class="p-3">
                        <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                            <i class="fas fa-medal"></i>
                            ${pangkat}
                        </span>
                    </td>

                    <td class="p-3">${lama}</td>
                    <td class="p-3">${rundown}</td>
                    <td class="p-3">${pesan}</td>
                </tr>
            `;
                    });

                    document.getElementById('count-aman').textContent = aman;
                    document.getElementById('count-bahaya').textContent = bahaya;

                } catch (error) {
                    console.error(error);
                }
            });
        </script>

</body>

</html>
