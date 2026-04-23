<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Tambahkan SweetAlert2 -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form id="loginForm">
            @csrf
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('loginForm');
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            // Ambil data dari form
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            try {
                const response = await fetch("http://192.168.1.143:8000/api/login", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();
              if (response.ok) {
                        // Simpan token
                        localStorage.setItem("token", data.token);
                        console.log(data.token);

                        // Tampilkan SweetAlert sukses
                        Swal.fire({
                            icon: "success",
                            title: "Login Berhasil",
                            text: "Anda akan dialihkan ke dashboard",
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(() => {
                            window.location.href = "/dashboard"; // Redirect
                        });
                    } else {
                        // SweetAlert untuk error
                        Swal.fire({
                            icon: "error",
                            title: "Login Gagal",
                            text: data.message || "Username atau password salah",
                        });
                    }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat login.');
            }
        });
    });
</script>
</body>
</html>