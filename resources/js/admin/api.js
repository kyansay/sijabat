export async function fetchPejabat(token) {
    const response = await fetch("http://192.168.1.143:8000/api/pejabat", {
        headers: {
            Authorization: `Bearer ${token}`,
            Accept: "application/json",
        },
    });

    const result = await response.json();

    if (!response.ok) {
        throw new Error(result.message || "Gagal mengambil data");
    }

    return result.data;
}

export async function sendEmailApi(id, token) {
    const response = await fetch(
        `http://192.168.1.143:8000/api/pejabat/${id}/send-warning`,
        {
            method: "POST",
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
        },
    );

    const result = await response.json();

    if (!response.ok) {
        throw new Error(result.message || "Gagal kirim email");
    }

    return result;
}

export async function createPejabat(data, token) {
    const response = await fetch("http://192.168.1.143:8000/api/pejabat", {
        method: "POST",
        headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
            Accept: "application/json",
        },
        body: JSON.stringify(data),
    });

    const result = await response.json();

    if (!response.ok) {
        // 🔥 UBAH BARIS INI: Lempar langsung object JSON-nya
        // Jangan pakai 'new Error()'
        throw result;
    }

    return result;
}

export async function deletePejabat(id, token) {
    const response = await fetch(
        `http://192.168.1.143:8000/api/pejabat/${id}`,
        {
            method: "DELETE",
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
        },
    );

    const result = await response.json();

    if (!response.ok) {
        throw new Error(result.message || "Gagal menghapus pejabat");
    }

    return result;
}
