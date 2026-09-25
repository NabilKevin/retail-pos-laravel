<!DOCTYPE html>
<html lang="id" class="h-full">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Kasir - Toko Obat')</title>
    <title>@yield('title', 'Dashboard Admin - Toko Obat')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Dashboard Admin Toko Obat">
    <meta name="keywords" content="toko obat, apotek, kesehatan, farmasi, obat, vitamin, suplemen, kesehatan tubuh">
    <meta name="author"
            content="Toko Obat">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('css/global.css') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        background: 'hsl(var(--background))',
                        foreground: 'hsl(var(--foreground))',
                        muted: 'hsl(var(--muted))',
                        'muted-foreground': 'hsl(var(--muted-foreground))',
                        border: 'hsl(var(--border))',
                        primary: 'hsl(var(--primary))',
                        'primary-foreground': 'hsl(var(--primary-foreground))',
                        secondary: 'hsl(var(--secondary))',
                        'secondary-foreground': 'hsl(var(--secondary-foreground))',
                        accent: 'hsl(var(--accent))',
                        'accent-foreground': 'hsl(var(--accent-foreground))',
                        sidebar: 'hsl(var(--sidebar))',
                        'sidebar-foreground': 'hsl(var(--sidebar-foreground))',
                        'sidebar-border': 'hsl(var(--sidebar-border))',
                        'sidebar-accent': 'hsl(var(--sidebar-accent))',
                        'sidebar-accent-foreground': 'hsl(var(--sidebar-accent-foreground))',
                        destructive: 'hsl(var(--destructive))',
                        'destructive-foreground': 'hsl(var(--destructive-foreground))',
                        success: 'hsl(var(--success))',
                        'success-foreground': 'hsl(var(--success-foreground))',
                        card: 'hsl(var(--card))',
                        'card-foreground': 'hsl(var(--card-foreground))',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'medium': '0 4px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 30px -5px rgba(0, 0, 0, 0.05)',
                        'large': '0 10px 40px -10px rgba(0, 0, 0, 0.15), 0 20px 50px -10px rgba(0, 0, 0, 0.1)',
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
      const BASE_URL = "{{ url('/') }}/";
    </script>
    <script src="{{ asset('js/global.js') }}"></script>
  </head>
  <body class="h-full bg-background text-foreground font-sans">
    <div class="min-h-screen flex">
      <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>
      @include('kasir.partials.sidebar')
      <div class="flex-1 flex flex-col min-w-0">
        @include('kasir.partials.header')
        <main class="p-4 md:p-6 lg:p-8">
          @yield('content')
        </main>
      </div>
    </div>

    @stack('scripts')
  </body>
</html>
