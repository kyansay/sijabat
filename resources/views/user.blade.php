<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIJABAT - Dashboard</title>

    <!-- Tailwind dari Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/user.js'])


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
        <div class="bg-[#003d64]/80 backdrop-blur-md text-white py-8 px-4 shadow-lg">
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


            <!-- TABLE -->
            <!-- TABLE -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-[#003d64] text-white px-4 py-3">
                    <h2 class="text-xl font-bold">
                        <i class="fas fa-table mr-2"></i>Data Pejabat
                    </h2>
                </div>

                <!-- Responsive Wrapper -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">NIP</th>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Pangkat</th>
                                <th class="px-4 py-3">TMT</th>
                                <th class="px-4 py-3">Sisa Waktu</th>
                                <th class="px-4 py-3 text-center">Status</th>
                            </tr>
                        </thead>

                        <tbody id="data-tbody" class="divide-y">
                            <tr>
                                <td colspan="7" class="text-center py-6">
                                    <div class="flex flex-col items-center gap-2">
                                        <div
                                            class="w-8 h-8 border-4 border-gray-300 border-t-[#003d64] rounded-full animate-spin">
                                        </div>
                                        <p class="text-[#003d64] font-semibold">Loading...</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <script>
            document.addEventListener("DOMContentLoaded", async function() {
                const token = localStorage.getItem("token");

                if (!token) {
                    alert("Token tidak ditemukan, silakan login ulang");
                    window.location.href = "/";
                    return;
                }

                try {
                    const response = await fetch("http://192.168.1.143:8000/api/pejabat", {
                        method: "GET",
                        headers: {
                            "Authorization": "Bearer " + token,
                            "Accept": "application/json"
                        }
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.message || "Gagal mengambil data");
                    }

                    const data = result.data || [];

                    let aman = 0;
                    let kritis = 0;
                    let bahaya = 0;

                    const tbody = document.querySelector("#data-tbody");
                    tbody.innerHTML = "";

                    data.forEach(item => {

                        const nip = item.nip;
                        const nama = item.nama;
                        const email = item.email ?? "-";
                        const pangkat = item.pangkat;
                        const lama = item.lama_pangkat;
                        const rundown = item.rundown ?? "-";

                        let status = "";

                        // 🔥 LOGIC STATUS 3 LEVEL
                        if (item.perlu_kenaikan) {
                            status = `<span class="bg-red-500 text-white px-3 py-1 rounded">Bahaya</span>`;
                            bahaya++;
                        } else if (item.rundown && item.rundown <= 6) {
                            status =
                                `<span class="bg-yellow-500 text-white px-3 py-1 rounded">Kritis</span>`;
                            kritis++;
                        } else {
                            status = `<span class="bg-green-500 text-white px-3 py-1 rounded">Aman</span>`;
                            aman++;
                        }

                        tbody.innerHTML += `
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">${nip}</td>
                    <td class="p-3">${nama}</td>
                    <td class="p-3">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                            ${email}
                        </span>
                    </td>
                    <td class="p-3">${pangkat}</td>
                    <td class="p-3">${lama}</td>
                    <td class="p-3">${rundown}</td>
                    <td class="p-3 text-center">${status}</td>
                </tr>
            `;
                    });



                } catch (error) {
                    console.error("Error:", error);

                    document.querySelector("#data-tbody").innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-6 text-red-600">
                    ${error.message}
                </td>
            </tr>
        `;
                }
            });
        </script>

</body>

</html>
