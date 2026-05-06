import { deletePejabat, fetchPejabat } from "./api";
import { renderTable, updateSummary, showError } from "./ui";
import { initEvents } from "./events";

// 🔥 State Global untuk Module
export let pejabatData = [];
export let filteredData = []; // Menyimpan data hasil pencarian/filter
export let currentPage = 1;
export const itemsPerPage = 5;

document.addEventListener("DOMContentLoaded", async () => {
    initEvents();
    const token = localStorage.getItem("token");

    // 🔥 Perketat pengecekan: antisipasi string "null" atau "undefined"
    if (!token || token === "null" || token === "undefined") {
        // Opsional: Tampilkan SweetAlert sejenak lalu redirect otomatis
        await Swal.fire({
            icon: "warning",
            title: "Sesi Habis",
            text: "Silakan login terlebih dahulu.",
            timer: 5000, // Popup akan hilang otomatis dalam 1,5 detik
            showConfirmButton: false,
            allowOutsideClick: false,
        }).then(() => {
            // 🔥 Gunakan replace() agar user tidak bisa menekan tombol 'Back' ke halaman ini
            window.location.replace("/");
        });

        return; // Hentikan eksekusi script di bawahnya
    }

    try {
        const data = await fetchPejabat(token);
        pejabatData = data;
        filteredData = data; // Awalnya, data filter sama dengan semua data

        updateDisplay(); // Panggil fungsi pagination
        updateSummary(data);
    } catch (error) {
        // Jika error dari server menyatakan token tidak valid/expired (misal error 401)
        if (
            error.message &&
            error.message.toLowerCase().includes("unauthenticated")
        ) {
            localStorage.removeItem("token");
            window.location.replace("/");
        } else {
            showError(error.message);
        }
    }
});

// 🔥 Fungsi Inti Pagination
export function updateDisplay() {
    // 1. Hitung titik potong array
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;

    // 2. Potong data sesuai halaman saat ini
    const paginatedData = filteredData.slice(startIndex, endIndex);

    // 3. Render tabel dengan data yang sudah dipotong
    renderTable(paginatedData);

    // 4. Render tombol navigasinya
    renderPaginationControls(filteredData.length);
}

// 🔥 Fungsi Menggambar Tombol Navigasi dengan Styling Tailwind Modern
function renderPaginationControls(totalItems) {
    const container = document.getElementById("pagination-container");
    if (!container) return;

    // Pastikan container memiliki styling yang rapi
    container.className =
        "flex flex-wrap items-center justify-center gap-2 mt-8 mb-4";

    const totalPages = Math.ceil(totalItems / itemsPerPage);
    let html = "";

    // Cek status disabled untuk tombol navigasi
    const isPrevDisabled = currentPage === 1;
    const isNextDisabled = currentPage === totalPages || totalPages === 0;

    // ==========================================
    // 1. TOMBOL PREV
    // ==========================================
    html += `
        <button id="btn-prev" 
            class="flex items-center gap-2 px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg border shadow-sm
            ${
                isPrevDisabled
                    ? "bg-gray-50 text-gray-400 border-gray-200 cursor-not-allowed"
                    : "bg-white text-gray-700 border-gray-300 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 active:scale-95"
            }" 
            ${isPrevDisabled ? "disabled" : ""}>
            <i class="fas fa-chevron-left text-xs"></i> Sebelumnya
        </button>
    `;

    // ==========================================
    // 2. ANGKA HALAMAN (PAGE NUMBERS)
    // ==========================================
    // Bungkus angka agar rapi di layar kecil (mobile-friendly)
    html += `<div class="flex items-center gap-1">`;
    for (let i = 1; i <= totalPages; i++) {
        const isActive = currentPage === i;
        html += `
            <button class="page-number min-w-[40px] h-9 flex items-center justify-center text-sm font-semibold transition-all duration-200 rounded-lg border shadow-sm
                ${
                    isActive
                        ? "bg-blue-600 text-white border-blue-600 ring-2 ring-blue-600/20" // Style saat aktif (Biru)
                        : "bg-white text-gray-700 border-gray-300 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 active:scale-95"
                }" // Style normal
                data-page="${i}">
                ${i}
            </button>
        `;
    }
    html += `</div>`;

    // ==========================================
    // 3. TOMBOL NEXT
    // ==========================================
    html += `
        <button id="btn-next" 
            class="flex items-center gap-2 px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg border shadow-sm
            ${
                isNextDisabled
                    ? "bg-gray-50 text-gray-400 border-gray-200 cursor-not-allowed"
                    : "bg-white text-gray-700 border-gray-300 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 active:scale-95"
            }" 
            ${isNextDisabled ? "disabled" : ""}>
            Selanjutnya <i class="fas fa-chevron-right text-xs"></i>
        </button>
    `;

    container.innerHTML = html;
}

export function addPejabatToUI(newData) {
    pejabatData.push(newData);
    filteredData = [...pejabatData];
    currentPage = 1;

    updateDisplay();
    updateSummary(pejabatData);
}

// 🔥 TAMBAHKAN FUNGSI INI UNTUK MENGHAPUS BARIS DI TABEL TANPA RELOAD
export function removePejabatFromUI(id) {
    // Filter/buang data yang id-nya cocok dengan yang dihapus
    pejabatData = pejabatData.filter((pejabat) => pejabat.id != id);
    filteredData = filteredData.filter((pejabat) => pejabat.id != id);

    // Cek jika halaman saat ini jadi kosong (karena datanya dihapus semua di halaman itu)
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    if (currentPage > totalPages && totalPages > 0) {
        currentPage = totalPages; // Mundur 1 halaman
    } else if (totalPages === 0) {
        currentPage = 1;
    }

    updateDisplay();
    updateSummary(pejabatData);
}

// 🔥 Setter untuk diakses dari events.js
export function setFilteredData(data) {
    filteredData = data;
}

export function setCurrentPage(page) {
    currentPage = page;
}
