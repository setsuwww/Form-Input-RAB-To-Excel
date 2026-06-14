@extends('layouts.app')

@section('content')
<div x-data="backupRestore()">
    <h1 class="text-3xl font-bold text-slate-800 mb-8">Backup & Restore Data</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Backup -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            </div>
            <h2 class="text-xl font-bold text-slate-800 mb-2">Export ke JSON</h2>
            <p class="text-slate-500 mb-6">Cadangkan seluruh data dokumen dan master kendaraan ke dalam satu file JSON.</p>
            <button @click="exportData()" class="w-full bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition">
                Download Backup (.json)
            </button>
        </div>

        <!-- Restore -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            </div>
            <h2 class="text-xl font-bold text-slate-800 mb-2">Import dari JSON</h2>
            <p class="text-slate-500 mb-6">Kembalikan data dari file cadangan. <span class="text-red-500 font-bold italic">Perhatian: Ini akan menimpa data saat ini!</span></p>

            <input type="file" id="importFile" class="hidden" @change="importData($event)" accept=".json">
            <button @click="document.getElementById('importFile').click()" class="w-full bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-emerald-700 transition">
                Pilih File & Restore
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function backupRestore() {
        return {
            exportData() {
                const data = {
                    documents: JSON.parse(localStorage.getItem('documents') || '[]'),
                    vehicle_brands: JSON.parse(localStorage.getItem('vehicle_brands') || '[]'),
                    vehicle_types: JSON.parse(localStorage.getItem('vehicle_types') || '[]'),
                    settings: JSON.parse(localStorage.getItem('settings') || '{}'),
                    timestamp: new Date().toISOString()
                };

                const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `backup-tja-${new Date().toISOString().split('T')[0]}.json`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            },

            importData(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (e) => {
                    try {
                        const data = JSON.parse(e.target.result);
                        if (confirm('Apakah Anda yakin ingin menimpa seluruh data lokal dengan file backup ini?')) {
                            if (data.documents) localStorage.setItem('documents', JSON.stringify(data.documents));
                            if (data.vehicle_brands) localStorage.setItem('vehicle_brands', JSON.stringify(data.vehicle_brands));
                            if (data.vehicle_types) localStorage.setItem('vehicle_types', JSON.stringify(data.vehicle_types));
                            if (data.settings) localStorage.setItem('settings', JSON.stringify(data.settings));

                            Swal.fire('Berhasil!', 'Data telah dipulihkan.', 'success').then(() => location.reload());
                        }
                    } catch (err) {
                        Swal.fire('Error', 'File JSON tidak valid.', 'error');
                    }
                };
                reader.readAsText(file);
            }
        }
    }
</script>
@endpush
@endsection
