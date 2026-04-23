<!DOCTYPE html>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIJABAT - Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            margin: 0;
        }
        
    .container {
        width: 90%;
        margin: 20px auto;
    }

    h1 {
        color: #2c3e50;
    }

    /* CARD DASHBOARD */
    .cards {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .card {
        flex: 1;
        padding: 20px;
        border-radius: 12px;
        color: white;
    }

    .aman { background: #3498db; }
    .kritis { background: #f1c40f; color: #333; }
    .bahaya { background: #e74c3c; }

    /* TABLE */
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
    }

    th, td {
        padding: 12px;
        text-align: left;
    }

    th {
        background: #2c3e50;
        color: white;
    }

    tr:nth-child(even) {
        background: #f9f9f9;
    }

    /* STATUS BADGE */
    .badge {
        padding: 6px 10px;
        border-radius: 8px;
        color: white;
        font-size: 12px;
    }

    .badge-aman { background: #3498db; }
    .badge-kritis { background: #f1c40f; color: black; }
    .badge-bahaya { background: #e74c3c; }

    /* COUNTDOWN */
    .countdown {
        font-weight: bold;
        color: #2c3e50;
    }

    /* RESPONSIVE */
    @media(max-width: 768px) {
        .cards {
            flex-direction: column;
        }
    }
</style>

</head>
<body>

<div class="container">
    <h1>📊 SIJABAT Dashboard</h1>

<!-- CARDS -->
<div class="cards">
    <div class="card aman">
        <h3>Zona Aman</h3>
        <p>12 Orang</p>
    </div>
    <div class="card kritis">
        <h3>Masa Kritis</h3>
        <p>5 Orang</p>
    </div>
    <div class="card bahaya">
        <h3>Wajib Peninjauan</h3>
        <p>3 Orang</p>
    </div>
</div>

<!-- TABLE -->
<table>
    <thead>
        <tr>
            <th>Nip</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Jabatan Sekarang</th>
            <th>TMT</th>
            <th>Status</th>
            <th>Countdown</th>
        </tr>
    </thead>
    <tbody>
       <script>
document.addEventListener("DOMContentLoaded", async function () {
    const token = localStorage.getItem("token");

    // if (!token) {
    //     window.location.href = "/login";
    //     return;
    // }

    try {
        const response = await fetch("http://192.168.1.143:8000/api/pejabats", {
            headers: {
                "Authorization": "Bearer " + token,
                "Accept": "application/json"
            }
        });

        if (!response.ok) {
            localStorage.removeItem("token");
            return;
        }

        const result = await response.json();
        const data = result.data;

        // tampilkan ke tabel
        const tbody = document.querySelector("tbody");
        tbody.innerHTML = "";

        data.forEach(item => {
            let statusClass = '';
            if (item.perlu_kenaikan) {
                statusClass = 'badge-bahaya';
            } else {
                statusClass = 'badge-aman';
            }

            tbody.innerHTML += `
                <tr>
                    <td>${item.nip}</td>
                    <td>${item.nama}</td>
                    td>${item.email}</td>
                    <td>${item.jabatan}</td>
                    <td>${item.tmt}</td>
                    <td><span class="badge ${statusClass}">${item.pesan}</span></td>
                    <td>${item.lama_menjabat}</td>
                </tr>
            `;
        });

    } catch (error) {
        console.error(error);
        window.location.href = "/login";
    }
});
</script>
    </tbody>
</table>

</div>

</body>
</html>
