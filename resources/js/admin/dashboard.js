document.addEventListener("DOMContentLoaded", async function () {
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
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
                "Content-Type": "application/json",
            },
        });

        const result = await response.json();
        console.log("Response:", result);

        if (!response.ok) {
            throw new Error(result.message || "Gagal mengambil data pejabat");
        }

        const data = result.data;

        if (!data || !Array.isArray(data)) {
            throw new Error("Data tidak valid");
        }

        let totalPejabat = 0,
            perluNotif = 0;
        const tbody = document.querySelector("#data-tbody");
        tbody.innerHTML = "";

        data.forEach((item) => {
            let status = "";
            const id = item.id;
            const nip = item.nip;
            const nama = item.nama;
            const email = item.email ?? "-";
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
                        <button onclick="sendEmail(event, '${id}', '${nama}', '${email}')" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded font-semibold transition-colors inline-flex items-center gap-2 text-sm">
                            <i class="fas fa-envelope"></i>
                            Kirim Email
                        </button>
                    </td>
                </tr>
            `;
        });

        document.getElementById("count-total").textContent = totalPejabat;
        document.getElementById("count-perlu-notif").textContent = perluNotif;
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
window.sendEmail = async function (event, id, nama, email) {
    const token = localStorage.getItem("token");

    if (!token) {
        Swal.fire({
            icon: "error",
            title: "Token tidak ditemukan",
            text: "Silakan login ulang!",
        });
        return;
    }

    const btn = event.target.closest("button");
    const originalContent = btn.innerHTML;
    // 🔥 Confirm
    const confirmResult = await Swal.fire({
        title: "Kirim Email?",
        text: `Kirim notifikasi ke email ${nama}?`,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Ya, kirim!",
        cancelButtonText: "Batal",
        confirmButtonColor: "#2563eb",
        cancelButtonColor: "#6b7280",
    });

    if (!confirmResult.isConfirmed) return;

    // 🔥 Loading SweetAlert
    Swal.fire({
        title: "Mengirim...",
        text: "Mohon tunggu, email sedang dikirim",
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    try {
        console.log("Token:", token);
        console.log("Mengirim email ke ID:", id);

        // 🔥 Loading button
        btn.disabled = true;
        btn.innerHTML =
            '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';

        const response = await fetch(
            `http://192.168.1.143:8000/api/pejabat/${id}/send-warning`,
            {
                method: "POST",
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify({}),
            },
        );

        const result = await response.json();
        console.log("Response:", result);

        if (response.ok) {
            // ✅ Success SweetAlert
            await Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: result.message || "Email berhasil dikirim",
                timer: 2000,
                showConfirmButton: false,
            });

            btn.innerHTML = '<i class="fas fa-check mr-2"></i>Terkirim';
            btn.classList.add("bg-green-600", "hover:bg-green-700");
            btn.classList.remove("bg-blue-600", "hover:bg-blue-700");
            btn.disabled = true;
        } else {
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: result.message || "Terjadi kesalahan",
            });

            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    } catch (error) {
        console.error("Network error:", error);

        Swal.fire({
            icon: "error",
            title: "Error Jaringan",
            text: error.message,
        });

        btn.innerHTML = originalContent;
        btn.disabled = false;
    }
};
