export function renderTable(data) {
    const tbody = document.getElementById("data-tbody");
    tbody.innerHTML = "";

    data.forEach((item) => {
        const status = item.perlu_kenaikan
            ? `<span class="bg-orange-100 text-orange-700 px-3 py-1 rounded font-semibold">
                <i class="fas fa-bell mr-1"></i>Perlu Notifikasi
               </span>`
            : `<span class="bg-green-100 text-green-700 px-3 py-1 rounded font-semibold">
                <i class="fas fa-check mr-1"></i>OK
               </span>`;

        tbody.insertAdjacentHTML(
            "beforeend",
            `
            <tr class="border-b hover:bg-gray-50 transition-colors">
                <td class="p-3 text-sm">${item.nip}</td>
                <td class="p-3 text-sm">${item.nama}</td>
                <td class="p-3 text-sm">
                    <a href="mailto:${item.email ?? "-"}" class="text-blue-600 hover:underline">
                        ${item.email ?? "-"}
                    </a>
                </td>
                <td class="p-3 text-sm">
                    <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                        <i class="fas fa-medal"></i>
                        ${item.pangkat ?? item.pangkat_sekarang}
                    </span>
                </td>
                <td class="p-3 text-sm">${item.lama_pangkat ?? item.tmt_pangkat}</td>
                <td class="p-3 text-sm">${status}</td>
                <td class="p-3">
                    <button 
                        class="btn-email bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded font-semibold transition-colors inline-flex items-center gap-2 text-sm"
                        data-id="${item.id}"
                        data-nama="${item.nama}"
                        data-email="${item.email}"
                    >
                        <i class="fas fa-envelope"></i>
                        
                    </button>
                    <button 
                        class="btn-hapus bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded font-semibold transition-colors inline-flex items-center gap-2 text-sm"
                        data-id="${item.id}"
                        
                    >
                        <i class="fas fa-trash"></i>
                        
                    </button>
                    
                </td>
            </tr>
            `,
        );
    });
}

export function updateSummary(data) {
    document.getElementById("count-total").textContent = data.length;
    document.getElementById("count-perlu-notif").textContent = data.filter(
        (d) => d.perlu_kenaikan,
    ).length;
}

export function showError(message) {
    document.getElementById("data-tbody").innerHTML = `
        <tr>
            <td colspan="7" class="text-red-600 text-center">
                ${message}
            </td>
        </tr>
    `;
}
