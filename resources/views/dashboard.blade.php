@extends('layouts.app')

@section('content')
<div x-data="dashboard()">
    <h1 class="text-3xl font-bold text-slate-800 mb-8">Dashboard Operasional</h1>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Dokumen</p>
            <p class="text-3xl font-black text-indigo-600 mt-1" x-text="stats.totalDocs"></p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Berat</p>
            <p class="text-3xl font-black text-indigo-600 mt-1" x-text="stats.totalWeight + ' kg'"></p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Jarak</p>
            <p class="text-3xl font-black text-indigo-600 mt-1" x-text="stats.totalDistance + ' km'"></p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total BBM</p>
            <p class="text-3xl font-black text-indigo-600 mt-1" x-text="stats.totalBBM + ' L'"></p>
        </div>
    </div>

    <!-- Recent Documents -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h2 class="font-bold text-slate-800 uppercase tracking-tight">Dokumen Terbaru</h2>
            <a href="{{ route('rekap') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-400 text-xs font-bold uppercase">
                    <tr>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Cargo</th>
                        <th class="px-6 py-4">Berat</th>
                        <th class="px-6 py-4">Total Hari</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="doc in recentDocs" :key="doc.id">
                        <tr class="hover:bg-slate-50/50 transition cursor-default">
                            <td class="px-6 py-4 font-bold text-slate-700" x-text="doc.customer_name"></td>
                            <td class="px-6 py-4 text-slate-600" x-text="doc.cargo_name"></td>
                            <td class="px-6 py-4 text-slate-600" x-text="doc.total_weight + ' kg'"></td>
                            <td class="px-6 py-4 text-slate-600" x-text="doc.total_days_muatan"></td>
                            <td class="px-6 py-4 text-slate-500 text-sm" x-text="new Date(doc.created_at).toLocaleDateString('id-ID')"></td>
                            <td class="px-6 py-4 text-right space-x-3">
                                <a :href="'/documents/' + doc.id + '/edit'" class="text-indigo-600 hover:underline font-bold text-sm">Edit</a>
                                <a :href="'/documents/' + doc.id + '/edit?duplicate=true'" class="text-amber-600 hover:underline font-bold text-sm">Duplicate</a>
                                <button @click="deleteDoc(doc.id)" class="text-red-500 hover:underline font-bold text-sm">Hapus</button>
                            </td>
                        </tr>
                    </template>
                    <template x-if="recentDocs.length === 0">
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">Belum ada dokumen operasional.</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function dashboard() {
        return {
            recentDocs: [],
            stats: { totalDocs: 0, totalWeight: 0, totalDistance: 0, totalBBM: 0 },

            init() {
                const docs = JSON.parse(localStorage.getItem('documents') || '[]');
                this.recentDocs = docs.sort((a,b) => b.id - a.id).slice(0, 5);

                this.stats.totalDocs = docs.length;
                this.stats.totalWeight = docs.reduce((acc, d) => acc + (parseFloat(d.total_weight) || 0), 0).toFixed(2);
                this.stats.totalDistance = docs.reduce((acc, d) => acc + (parseFloat(d.distance_muatan) || 0) + (parseFloat(d.distance_kosongan) || 0), 0).toFixed(2);
                this.stats.totalBBM = docs.reduce((acc, d) => acc + (parseFloat(d.bbm_usage_muatan) || 0) + (parseFloat(d.bbm_usage_kosongan) || 0), 0).toFixed(2);
            },

            deleteDoc(id) {
                if (confirm('Hapus dokumen ini?')) {
                    const docs = JSON.parse(localStorage.getItem('documents') || '[]');
                    const filtered = docs.filter(d => d.id !== id);
                    localStorage.setItem('documents', JSON.stringify(filtered));
                    this.init();
                }
            }
        }
    }
</script>
@endpush
@endsection
