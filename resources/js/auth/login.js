document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginForm");
    const submitBtn = form.querySelector('button[type="submit"]');

    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        // 🔥 Disable button + ubah teks
        submitBtn.disabled = true;
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = "Loading...";

        // 🔥 SweetAlert loading
        Swal.fire({
            title: "Loading...",
            text: "Sedang memproses login",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });

        try {
            const response = await fetch(
                "http://192.168.1.143:8000/api/login",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        email,
                        password,
                    }),
                },
            );

            const data = await response.json();
            console.log(data);

            if (response.ok) {
                localStorage.setItem("token", data.data.token);

                Swal.fire({
                    icon: "success",
                    title: "Login Berhasil",
                    text: "Anda akan dialihkan ke dashboard",
                    timer: 2000,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.href = "/admin/dashboard";
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Login Gagal",
                    text: data.message || "Email atau password salah",
                });
            }
        } catch (error) {
            console.error("Error:", error);

            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Terjadi kesalahan saat login",
            });
        } finally {
            // 🔥 Kembalikan button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
});

// 🔥 Toggle Password (WAJIB global)
window.togglePassword = function () {
    const input = document.getElementById("password");
    const eyeOpen = document.getElementById("eyeOpen");
    const eyeClose = document.getElementById("eyeClose");

    if (input.type === "password") {
        input.type = "text";
        eyeOpen.classList.add("hidden");
        eyeClose.classList.remove("hidden");
    } else {
        input.type = "password";
        eyeOpen.classList.remove("hidden");
        eyeClose.classList.add("hidden");
    }
};
