<?php
require_once __DIR__ . '/auth.php';

// If already logged in, redirect to dashboard
if (isAdminLoggedIn()) {
    header('Location: index.php');
    exit;
}

$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Admin - EduMarket</title>
    <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary: #1E2A5A;
            --primary-dark: #16204A;
            --secondary: #D97706;
            --dark: #1C2340;
            --dark-2: #16204A;
            --gray-100: #F7F3EC;
            --gray-200: #E3DDCF;
            --gray-300: #D8D2C2;
            --gray-600: #5A6178;
            --gray-800: #1C2340;
            --white: #FFFFFF;
            --hero-solid: #16204A;
            --brand-solid: #1E2A5A;
            --shadow-xl: 0 4px 16px rgba(28,35,64,0.10);
            --border-radius: 10px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #F7F3EC;
            line-height: 1.65;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            position: relative;
            overflow-x: hidden;
        }


        .login-wrapper {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 10;
        }

        .login-card {
            background: #FFFFFF;
            border-radius: var(--border-radius);
            padding: 40px 32px;
            box-shadow: var(--shadow-xl);
            border: 1px solid #E3DDCF;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            margin-bottom: 12px;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            background: var(--brand-solid);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.5rem;
            box-shadow: none;
        }

        .logo-title {
            font-family: 'Fraunces', serif;
            letter-spacing: -0.02em;
            line-height: 1.15;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--dark);
        }

        .logo-title span {
            color: var(--secondary);
        }

        .portal-badge {
            display: inline-block;
            background: #E8EAF4;
            color: var(--primary);
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .14em;
            border: 1px solid #D8D2C2;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 8px;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            color: var(--gray-600);
            font-size: 1rem;
            pointer-events: none;
            transition: color 0.2s ease;
        }

        .form-control {
            width: 100%;
            padding: 14px 44px 14px 44px;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 0.95rem;
            color: var(--dark);
            background: var(--white);
            transition: all 0.25s ease;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 42, 90, 0.12);
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: var(--gray-600);
            cursor: pointer;
            padding: 6px;
            font-size: 0.95rem;
        }

        .toggle-password:hover {
            color: var(--primary);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: var(--brand-solid);
            color: var(--white);
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: none;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            background: var(--primary-dark);
            box-shadow: var(--shadow-xl);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .demo-box {
            margin-top: 24px;
            padding: 14px;
            background: #F7F3EC;
            border: 1px solid #E3DDCF;
            border-radius: 8px;
            font-size: 0.82rem;
            color: var(--gray-800);
        }

        .demo-box strong {
            color: var(--primary);
        }

        .quick-fill-btn {
            display: inline-block;
            margin-top: 6px;
            padding: 4px 10px;
            background: var(--primary);
            color: var(--white);
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: background 0.2s;
        }

        .quick-fill-btn:hover {
            background: var(--primary-dark);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #5A6178;
            font-size: 0.88rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--primary);
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="brand-header">
                <a href="../index.html" class="brand-logo" id="loginBrandLink">
                    <div class="logo-icon" id="loginLogoIcon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <img src="" alt="Logo" id="loginLogoImg" style="display: none; height: 48px; max-width: 160px; object-fit: contain; border-radius: 8px;">
                    <div class="logo-title" id="loginBrandText">Edu<span>Market</span></div>
                </a>
                <br>
                <span class="portal-badge"><i class="fas fa-shield-alt"></i> Panel Admin</span>
            </div>

            <form id="adminLoginForm">
                <div class="form-group">
                    <label class="form-label" for="username">Username atau Email</label>
                    <div class="input-group">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="username" class="form-control" placeholder="admin" required autofocus autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Kata Sandi (Password)</label>
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                        <button type="button" class="toggle-password" id="togglePassword" aria-label="Lihat Password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login" id="btnLogin">
                    <span id="btnText"><i class="fas fa-sign-in-alt"></i> Masuk ke Dashboard</span>
                    <span id="btnSpinner" style="display: none;"><i class="fas fa-spinner fa-spin"></i> Memvalidasi...</span>
                </button>
            </form>

            <div class="demo-box">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong><i class="fas fa-key"></i> Kredensial Default:</strong><br>
                        User: <code>admin</code> | Pass: <code>admin123</code>
                    </div>
                    <button type="button" class="quick-fill-btn" id="btnQuickFill">
                        <i class="fas fa-magic"></i> Auto Fill
                    </button>
                </div>
            </div>
        </div>

        <a href="../index.html" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke halaman utama
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const form = document.getElementById('adminLoginForm');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const btnLogin = document.getElementById('btnLogin');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        const btnQuickFill = document.getElementById('btnQuickFill');

        // Toggle eye password visibility
        togglePassword.addEventListener('click', () => {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            togglePassword.innerHTML = isPassword ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        });

        // Quick fill demo account
        btnQuickFill.addEventListener('click', () => {
            usernameInput.value = 'admin';
            passwordInput.value = 'admin123';
            usernameInput.focus();
        });

        // Handle logout notification parameter
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('msg') === 'logged_out') {
            Swal.fire({
                icon: 'info',
                title: 'Telah Keluar',
                text: 'Sesi Anda telah diakhiri dengan aman.',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        // Handle form submission
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const username = usernameInput.value.trim();
            const password = passwordInput.value.trim();

            if (!username || !password) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Username dan password wajib diisi!',
                    confirmButtonColor: '#1E2A5A'
                });
                return;
            }

            // Set loading state
            btnLogin.disabled = true;
            btnText.style.display = 'none';
            btnSpinner.style.display = 'inline-block';

            try {
                const response = await fetch('api/auth.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username, password })
                });

                const res = await response.json();

                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Berhasil!',
                        text: res.message || 'Selamat datang kembali di panel EduMarket.',
                        showConfirmButton: false,
                        timer: 1200
                    }).then(() => {
                        window.location.href = res.redirect || 'index.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Masuk',
                        text: res.message || 'Username atau password tidak sesuai.',
                        confirmButtonColor: '#1E2A5A'
                    });
                    btnLogin.disabled = false;
                    btnText.style.display = 'inline-block';
                    btnSpinner.style.display = 'none';
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Gangguan',
                    text: 'Tidak dapat menghubungi server auth: ' + err.message,
                    confirmButtonColor: '#1E2A5A'
                });
                btnLogin.disabled = false;
                btnText.style.display = 'inline-block';
                btnSpinner.style.display = 'none';
            }
        });
        // Load Dynamic Store Brand
        (async function loadBrand() {
            try {
                const res = await fetch('../api/store-profile.php?v=' + Date.now());
                const json = await res.json();
                if (json.success && json.data) {
                    const d = json.data;
                    const textEl = document.getElementById('loginBrandText');
                    const imgEl = document.getElementById('loginLogoImg');
                    const iconEl = document.getElementById('loginLogoIcon');

                    if (d.store_name && textEl) {
                        const parts = d.store_name.trim().split(/\s+/);
                        if (parts.length > 1) {
                            const last = parts.pop();
                            textEl.innerHTML = `${parts.join(' ')}<span>${last}</span>`;
                        } else if (d.store_name.length >= 6) {
                            const mid = Math.ceil(d.store_name.length / 2);
                            textEl.innerHTML = `${d.store_name.substring(0, mid)}<span>${d.store_name.substring(mid)}</span>`;
                        } else {
                            textEl.textContent = d.store_name;
                        }
                    }

                    if (d.logo && !d.logo.endsWith('favicon.svg') && imgEl && iconEl) {
                        const src = d.logo.startsWith('http') ? d.logo : '../' + d.logo;
                        imgEl.src = src;
                        imgEl.style.display = 'inline-block';
                        iconEl.style.display = 'none';
                        imgEl.onerror = () => {
                            imgEl.style.display = 'none';
                            iconEl.style.display = 'flex';
                        };
                    }
                }
            } catch (e) {}
        })();
    </script>
</body>
</html>
