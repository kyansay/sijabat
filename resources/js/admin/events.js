import { sendEmailApi, createPejabat, deletePejabat } from "./api";
import {
    pejabatData,
    filteredData,
    setFilteredData,
    currentPage,
    setCurrentPage,
    itemsPerPage,
    updateDisplay,
    addPejabatToUI,
    removePejabatFromUI,
} from "./dashboard"; // import fungsi & variabel baru

export function initEvents() {
    document.addEventListener("click", async (e) => {
        // 🔥 Event untuk Kirim Email
        const btn = e.target.closest(".btn-email");
        if (btn) {
            const id = btn.dataset.id;
            const nama = btn.dataset.nama;
            await handleSendEmail(btn, id, nama);
            return;
        }

        // 🔥 EVENT UNTUK HAPUS PEJABAT
        const btnHapus = e.target.closest(".btn-hapus");
        if (btnHapus) {
            const id = btnHapus.dataset.id;
            await handleDeletePejabat(btnHapus, id);
            return;
        }
        // 🔥 Event untuk Pagination: PREV
        if (e.target.closest("#btn-prev")) {
            if (currentPage > 1) {
                setCurrentPage(currentPage - 1);
                updateDisplay();
            }
        }

        // 🔥 Event untuk Pagination: NEXT
        if (e.target.closest("#btn-next")) {
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            if (currentPage < totalPages) {
                setCurrentPage(currentPage + 1);
                updateDisplay();
            }
        }

        // 🔥 Event untuk Pagination: ANGKA HALAMAN
        const pageBtn = e.target.closest(".page-number");
        if (pageBtn) {
            const page = parseInt(pageBtn.dataset.page);
            setCurrentPage(page);
            updateDisplay();
        }
    });

    // 🔥 Event Tambah Pejabat
    const btnTambah = document.getElementById("btn-tambah");
    btnTambah?.addEventListener("click", handleTambahPejabat);

    // 🔥 Event Search
    const searchInput = document.getElementById("search-input");
    searchInput?.addEventListener("input", handleSearch);
}

// FUNGSI PENCARIAN
function handleSearch(e) {
    const keyword = e.target.value.toLowerCase().trim();

    // Filter dari master data
    const filtered = pejabatData.filter((pejabat) => {
        const nama = (pejabat.nama || "").toLowerCase();
        const nip = (pejabat.nip || "").toLowerCase();

        return nama.includes(keyword) || nip.includes(keyword);
    });

    // Simpan hasil filter ke state
    setFilteredData(filtered);

    // Setiap kali user mencari, paksa kembali ke halaman 1
    setCurrentPage(1);

    // Render ulang
    updateDisplay();
}

// ... Sisa fungsi handleSendEmail dan handleTambahPejabat tetap sama ...

async function handleSendEmail(btn, id, nama) {
    const token = localStorage.getItem("token");

    if (!token) {
        Swal.fire({
            icon: "error",
            title: "Session habis",
            text: "Silakan login ulang!",
        });
        return;
    }

    // 🔥 CONFIRM
    const confirmResult = await Swal.fire({
        title: "Kirim Email?",
        text: `Kirim notifikasi ke ${nama}?`,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Ya, kirim!",
        cancelButtonText: "Batal",
        confirmButtonColor: "#2563eb",
        cancelButtonColor: "#6b7280",
    });

    if (!confirmResult.isConfirmed) return;

    const original = btn.innerHTML;

    try {
        // 🔥 LOADING ALERT
        Swal.fire({
            title: "Mengirim...",
            text: "Mohon tunggu",
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });

        // 🔥 LOADING BUTTON
        btn.disabled = true;
        btn.innerHTML = `
            <i class="fas fa-spinner fa-spin mr-1"></i>
            Mengirim...
        `;

        const result = await sendEmailApi(id, token);

        // 🔥 SUCCESS ALERT
        await Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: result.message || "Email berhasil dikirim",
            timer: 2000,
            showConfirmButton: false,
        });

        // 🔥 UPDATE BUTTON
        btn.innerHTML = `
            <i class="fas fa-check mr-1"></i>
            Terkirim
        `;
        btn.classList.remove("bg-blue-600", "hover:bg-blue-700");
        btn.classList.add("bg-green-600", "hover:bg-green-700");
    } catch (error) {
        console.error(error);

        // 🔥 ERROR ALERT
        Swal.fire({
            icon: "error",
            title: "Gagal",
            text: error.message || "Terjadi kesalahan",
        });

        // 🔥 RESET BUTTON
        btn.innerHTML = original;
        btn.disabled = false;
    }
}

async function handleTambahPejabat() {
    const { value: formValues } = await Swal.fire({
        title: "Tambah Pejabat",
        width: 600,
        showCancelButton: true,
        confirmButtonText: "Simpan",
        cancelButtonText: "Batal",
        buttonsStyling: false,
        customClass: {
            popup: "rounded-2xl",
            confirmButton:
                "bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold mr-2",
            cancelButton:
                "bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold",
        },
        focusConfirm: false,
        html: `
            <div class="space-y-4 text-left">
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-1">
                        <i class="fas fa-id-card"></i> NIP
                    </label>
                    <input id="swal-nip" 
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Masukkan NIP">
                </div>
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-1">
                        <i class="fas fa-user"></i> Nama
                    </label>
                    <input id="swal-nama" 
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Masukkan Nama">
                </div>
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-1">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input id="swal-email" type="email"
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Masukkan Email">
                </div>
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-1">
                        <i class="fas fa-medal"></i> Pangkat
                    </label>
                    <input id="swal-pangkat" 
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Contoh: IV/a">
                </div>
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-1">
                        <i class="fas fa-calendar"></i> TMT
                    </label>
                    <input id="swal-tmt" type="date"
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
        `,
        preConfirm: () => {
            const nip = document.getElementById("swal-nip").value.trim();
            const nama = document.getElementById("swal-nama").value.trim();
            const email = document.getElementById("swal-email").value.trim();
            const pangkat = document
                .getElementById("swal-pangkat")
                .value.trim();
            const tmt = document.getElementById("swal-tmt").value;

            // 🔥 VALIDASI DALAM MODAL (tidak keluar popup baru)
            if (!nip || !nama) {
                Swal.showValidationMessage("NIP dan Nama wajib diisi");
                return false;
            }

            return {
                nip,
                nama,
                email,
                pangkat_sekarang: pangkat,
                tmt_pangkat: tmt,
            };
        },
    });

    if (!formValues) return;

    const token = localStorage.getItem("token");

    try {
        Swal.fire({
            title: "Menyimpan...",
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading(),
        });

        const result = await createPejabat(formValues, token);

        // 🔥 TAMBAHKAN KE UI TANPA RELOAD
        addPejabatToUI(result.data);

        await Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil ditambahkan",
            timer: 1500,
            showConfirmButton: false,
        });
    } catch (error) {
        // 1. Ambil pesan utama dari API, atau gunakan pesan default
        let titleMessage = error.message || "Gagal Menyimpan";
        let htmlErrorMessage = "Terjadi kesalahan yang tidak diketahui.";

        // 2. Jika ada detail error dari Laravel (error.errors)
        if (error.errors) {
            // Ubah object error menjadi elemen <li> html
            const errorList = Object.values(error.errors)
                .flat()
                .map((msg) => `<li class="text-red-500 mb-1">${msg}</li>`)
                .join("");

            // Bungkus ke dalam <ul> dengan styling Tailwind
            htmlErrorMessage = `
                <ul class="text-left list-disc pl-5 text-sm">
                    ${errorList}
                </ul>
            `;
        }

        // 3. Tampilkan di SweetAlert
        Swal.fire({
            icon: "error",
            title: titleMessage,
            html: htmlErrorMessage,
            confirmButtonColor: "#3b82f6", // Warna biru Tailwind (blue-500)
            customClass: {
                popup: "rounded-2xl",
            },
        });
    }
}

// 🔥 UBAH NAMA FUNGSI JADI handleDeletePejabat (agar tidak bentrok dengan fungsi dari api.js)
async function handleDeletePejabat(btn, id) {
    const token = localStorage.getItem("token");

    if (!token) {
        Swal.fire({
            icon: "error",
            title: "Session habis",
            text: "Silakan login ulang!",
        });
        return;
    }

    const confirmDelete = await Swal.fire({
        title: "Hapus Pejabat?",
        text: "Data yang sudah dihapus tidak bisa dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    });

    if (!confirmDelete.isConfirmed) return;

    // Simpan tampilan awal tombol
    const originalHtml = btn.innerHTML;

    try {
        Swal.fire({
            title: "Menghapus...",
            text: "Mohon tunggu",
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });

        // Ubah tombol jadi loading
        btn.disabled = true;
        btn.innerHTML = `<i class="fas fa-spinner fa-spin mr-1"></i> Menghapus...`;

        // 🔥 Panggil API dan MASUKKAN TOKEN
        const result = await deletePejabat(id, token);

        await Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: result.message || "Data berhasil dihapus",
            timer: 1500,
            showConfirmButton: false,
        });

        // 🔥 Hapus data dari UI tabel tanpa perlu refresh halaman
        removePejabatFromUI(id);
    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: "error",
            title: "Gagal",
            text: error.message || "Terjadi kesalahan saat menghapus data.",
        });

        // Kembalikan tombol seperti semula jika gagal
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    }
}
