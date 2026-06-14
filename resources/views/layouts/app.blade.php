<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple RAB Form</title>

    <!-- CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- JS Dependencies -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] { display: none !important; }
        .map-container { height: 300px; width: 100%; border-radius: 0.5rem; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased" x-data="globalApp()">

    <!-- Sidebar / Nav -->
    <nav class="bg-indigo-900 text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <div class="flex items-center space-x-20">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-yellow-500">TJA OPERASIONAL</a>
                <div class="hidden md:flex space-x-6 text-sm font-medium">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-200 transition">Dashboard</a>
                    <a href="{{ route('rekap') }}" class="hover:text-indigo-200 transition">Rekap</a>
                    <div class="relative group" x-data="{ open: false }">
                        <button @click="open = !open" class="hover:text-indigo-200 transition flex items-center">
                            Master Data <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white text-slate-800 rounded-md shadow-xl py-2 z-50">
                            <a href="{{ route('master.brands') }}" class="block px-4 py-2 hover:bg-indigo-50">Merk Kendaraan</a>
                            <a href="{{ route('master.types') }}" class="block px-4 py-2 hover:bg-indigo-50">Jenis Kendaraan</a>
                        </div>
                    </div>
                    <a href="{{ route('backup') }}" class="hover:text-indigo-200 transition">Backup & Restore</a>
                </div>
            </div>
            <a href="{{ route('documents.create') }}" class="bg-indigo-500 hover:bg-indigo-600 px-4 py-2 rounded-lg text-sm font-bold transition shadow-md">
                + DOKUMEN BARU
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Global Storage Manager -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('globalApp', () => ({
                // Common utility for LocalStorage
                storage: {
                    get(key, fallback = []) {
                        const data = localStorage.getItem(key);
                        return data ? JSON.parse(data) : fallback;
                    },
                    set(key, val) {
                        localStorage.setItem(key, JSON.stringify(val));
                    }
                },

                toast(title, icon = 'success') {
                    Swal.fire({
                        title: title,
                        icon: icon,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            }));
        });
    </script>

    @stack('scripts')
</body>
</html>
