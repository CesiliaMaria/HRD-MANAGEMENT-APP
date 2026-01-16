<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HRD Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .welcome-container {
            max-width: 450px;
            width: 100%;
            padding: 0 20px;
        }
        .welcome-card {
            background: white;
            border-radius: 25px;
            padding: 3rem 2.5rem;
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
            text-align: center;
            animation: fadeInUp 0.6s ease-out;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .icon-wrapper {
            width: 100px;
            height: 100px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        .icon-wrapper i {
            font-size: 2.5rem;
            color: white;
        }
        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.75rem;
        }
        .subtitle {
            color: #718096;
            font-size: 0.95rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            padding: 14px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        .btn-outline-primary:hover {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-color: transparent;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }
        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #cbd5e0;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }
        .divider span {
            padding: 0 1rem;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-card">
            <div class="icon-wrapper">
                <i class="fas fa-users-cog"></i>
            </div>
            <h1>HRD Management</h1>
            <p class="subtitle">
                Sistem manajemen sumber daya manusia yang lengkap dan terintegrasi
            </p>
            <div class="d-grid gap-3">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i> Login ke Sistem
                </a>
                <div class="divider">
                    <span>atau</span>
                </div>
                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-user-plus me-2"></i> Daftar Akun Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>