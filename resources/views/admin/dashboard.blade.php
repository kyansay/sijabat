<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIJABAT - Dashboard</title>

    <!-- Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
                            Tambah Pejabatp
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

        <script>
            document.addEventListener("DOMContentLoaded", async function() {
                const token = localStorage.getItem("token");
                console.log("Token:", token);

                if (!token) {
                    alert("Token tidak ditemukan, silakan login ulang");
                    window.location.href = "/";
                    return;
                }

                try {
                    const response = await fetch("http://192.168.1.143:8000/api/pejabat", {
                        method: "GET",
                        headers: {
                            "Authorization": `Bearer ${token}`,
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        }
                    });

                    const result = await response.json();
                    console.log("Response:", result);

                    if (!response.ok) {
                        throw new Error(result.message || 'Gagal mengambil data pejabat');
                    }

                    const data = result.data;

                    if (!data || !Array.isArray(data)) {
                        throw new Error('Data tidak valid');
                    }

                    let totalPejabat = 0,
                        perluNotif = 0;
                    const tbody = document.querySelector("#data-tbody");
                    tbody.innerHTML = "";

                    data.forEach(item => {
                        let status = "";
                        const id = item.id;
                        const nip = item.nip;
                        const nama = item.nama;
                        const email = item.email ?? '-';
                        const pangkat = item.pangkat;
                        const lama = item.lama_pangkat;

                        totalPejabat++;

                        if (item.perlu_kenaikan) {
                            status = `<span class="bg-orange-100 text-orange-700 px-3 py-1 rounded font-semibold"><i class="fas fa-bell mr-1"></i>Perlu Notifikasi</span>`;
                            perluNotif++;
                        } else {
                            status = `<span class="bg-green-100 text-green-700 px-3 py-1 rounded font-semibold"><i class="fas fa-check mr-1"></i>OK</span>`;
                        }

                        tbody.innerHTML += `
                    <tr class="border-b hover:bg-gray-50 transition-colors">
                    <td class="p-3 text-sm">${nip}</td>
                    <td class="p-3 text-sm">${nama}</td>
                    <td class="p-3 text-sm">
                        <a href="mailto:${email}" class="text-blue-600 hover:underline">${email}</a>
                    </td>
                    <td class="p-3 text-sm">
                        <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                            <i class="fas fa-medal"></i>
                            ${pangkat}
                        </span>
                    </td>
                    <td class="p-3 text-sm">${lama}</td>
                    <td class="p-3 text-sm">${status}</td>
                    <td class="p-3">
                        <button onclick="sendEmail('${id}', '${nama}', '${email}')" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded font-semibold transition-colors inline-flex items-center gap-2 text-sm">
                            <i class="fas fa-envelope"></i>
                            Kirim Email
                        </button>
                    </td>
                </tr>
            `;
                    });

                    document.getElementById('count-total').textContent = totalPejabat;
                    document.getElementById('count-perlu-notif').textContent = perluNotif;

                } catch (error) {
                    console.error("Error:", error);
                    document.querySelector("#data-tbody").innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center p-6 text-red-600">
                                <i class="fas fa-exclamation-circle mr-2"></i>Error: ${error.message}
                            </td>
                        </tr>
                    `;
                }
            });

            // Fungsi untuk mengirim email
            async function sendEmail(id) {
                if (!confirm(`Apakah Anda yakin ingin mengirim email notifikasi kenaikan pangkat ke ID ${id}?`)) {
                    return;
                }

                const btn = event.target.closest('button');
                const originalContent = btn.innerHTML;
                
                try {
                    const token = localStorage.getItem("token");
                    
                    if (!token) {
                        alert("❌ Token tidak ditemukan. Silakan login ulang!");
                        return;
                    }
                    
                    console.log("Token:", token);
                    console.log("Mengirim email ke ID:", id);
                    
                    // Tampilkan loading state
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';

                    const response = await fetch(`http://192.168.1.143:8000/api/pejabat/${id}/send-warning`, {
                        method: "POST",
                        headers: {
                            "Authorization": `Bearer ${token}`,
                            "Content-Type": "application/json",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({})
                    });

                    const result = await response.json();
                    console.log("Response:", result);

                    if (response.ok) {
                        
                        btn.innerHTML = '<i class="fas fa-check mr-2"></i>Terkirim';
                        btn.classList.add('bg-green-600', 'hover:bg-green-700');
                        btn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                        btn.disabled = true;
                    } else {
                        alert(`❌ Gagal mengirim email: ${result.message || 'Error tidak diketahui'}`);
                        console.error("Error response:", result);
                        btn.innerHTML = originalContent;
                        btn.disabled = false;
                    }
                } catch (error) {
                    console.error("Network error:", error);
                    alert(`❌ Terjadi kesalahan jaringan: ${error.message}`);
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                }
            }
        </script>

</body>

</html>
