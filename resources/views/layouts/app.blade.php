<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Halo MANAP')</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1E3A8A">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/pwa-icons/icon-192.png">
    
    <!-- Fonts (Geist + Geist Mono for premium dashboards) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=Geist+Mono:wght@500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=Geist+Mono:wght@500;600;700&display=swap" rel="stylesheet"></noscript>

    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    
    <!-- Tailwind CSS (CDN for prototype) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Geist', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        heading: ['Geist', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        mono: ['Geist Mono', 'ui-monospace', 'monospace'],
                    },
                    colors: {
                        primary: '#1E3A8A', // Blue dark
                        secondary: '#3B82F6', // Blue light
                        success: '#10B981', // Green
                        warning: '#F59E0B', // Yellow
                        danger: '#EF4444', // Red
                        'gray-bg': '#F9FAFB'
                    }
                }
            }
        }
    </script>
    
    <style>
        :root {
            --admin-ease: cubic-bezier(0.16, 1, 0.3, 1);
        }

        body {
            background-color: #F8FAFC;
            font-family: 'Geist', sans-serif;
        }
        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Geist', sans-serif;
        }
        /* Hide scrollbar for clean PWA look */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes admin-rise {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes admin-breathe {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .58; transform: scale(.76); }
        }

        @keyframes admin-shimmer {
            0% { background-position: -420px 0; }
            100% { background-position: 420px 0; }
        }

        .admin-rise {
            animation: admin-rise .52s var(--admin-ease) both;
            animation-delay: calc(var(--index, 0) * 70ms);
        }

        .admin-breathe {
            animation: admin-breathe 2.4s ease-in-out infinite;
        }

        .admin-shimmer {
            background: linear-gradient(90deg, #f1f5f9 8%, #e2e8f0 18%, #f1f5f9 33%);
            background-size: 840px 100%;
            animation: admin-shimmer 1.35s linear infinite;
        }

        .admin-nav-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: .75rem;
            border-radius: 1rem;
            padding: .7rem .8rem;
            color: rgb(203 213 225);
            transition: color .28s var(--admin-ease), background-color .28s var(--admin-ease), transform .28s var(--admin-ease);
        }

        .admin-nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, .07);
            transform: translateX(2px);
        }

        .admin-nav-active {
            color: white;
            background: linear-gradient(135deg, rgba(37, 99, 235, .95), rgba(30, 64, 175, .95));
            box-shadow: inset 0 1px 0 rgba(255,255,255,.16), 0 16px 32px -22px rgba(37,99,235,.75);
        }

        .admin-glass {
            background: linear-gradient(135deg, rgba(255,255,255,.88), rgba(255,255,255,.62));
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255,255,255,.56);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.56), 0 22px 50px -28px rgba(15,23,42,.35);
        }

        #sidebar nav a {
            border-radius: 1rem;
            transition: color .28s var(--admin-ease), background-color .28s var(--admin-ease), transform .28s var(--admin-ease), box-shadow .28s var(--admin-ease);
        }

        #sidebar nav a:hover {
            transform: translateX(2px);
            background-color: rgba(255, 255, 255, .075) !important;
        }

        #sidebar nav a.bg-blue-600 {
            background: linear-gradient(135deg, rgba(37, 99, 235, .98), rgba(30, 64, 175, .96)) !important;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.16), 0 16px 32px -22px rgba(37,99,235,.75);
        }

        #sidebar nav p {
            letter-spacing: .18em;
        }

        #sidebar nav button[type="submit"] {
            border-radius: 1rem;
            transition: background-color .28s var(--admin-ease), color .28s var(--admin-ease), transform .28s var(--admin-ease);
        }

        #sidebar nav button[type="submit"]:active,
        #sidebar nav a:active {
            transform: scale(.985);
        }
    </style>
    @stack('styles')
</head>
<body class="text-gray-800 font-sans antialiased overflow-x-hidden">

    @yield('content')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    @stack('scripts')

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
</body>
</html>
