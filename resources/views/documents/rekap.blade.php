@extends('layouts.app')

@section('content')
<div x-data="rekap()">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Rekap Operasional</h1>
        <button @click="exportMultiple()" class="bg-emerald-600 text-white px-6 py-2 rounded-lg font-bold shadow hover:bg-emerald-700 transition">
            Export Excel (Batch)
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Cari Dokumen</label>
            <input type="text" x-model="filter.search" placeholder="Customer / Cargo..." class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Filter Customer</label>
            <select x-model="filter.customer" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                <option value="">Semua Customer</option>
                <template x-for="c in uniqueCustomers">
                    <option :value="c" x-text="c"></option>
                </template>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Urutkan</label>
            <select x-model="filter.sort" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                <option value="newest">Terbaru</option>
                <option value="oldest">Terlama</option>
                <option value="weight">Berat Terbesar</option>
            </select>
        </div>
        <div class="flex items-end">
            <button @click="resetFilter()" class="text-slate-500 font-bold text-sm hover:underline">Reset Filter</button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-400 text-xs font-bold uppercase">
                    <tr>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Cargo</th>
                        <th class="px-6 py-4">Berat</th>
                        <th class="px-6 py-4">Jarak</th>
                        <th class="px-6 py-4">Total Hari</th>
                        <th class="px-6 py-4">BBM Muat</th>
                        <th class="px-6 py-4">BBM Kosong</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="doc in filteredDocs" :key="doc.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800" x-text="doc.customer_name"></p>
                                <p class="text-[10px] text-slate-400" x-text="new Date(doc.created_at).toLocaleDateString()"></p>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium" x-text="doc.cargo_name"></td>
                            <td class="px-6 py-4 text-slate-600" x-text="doc.total_weight + ' kg'"></td>
                            <td class="px-6 py-4 text-slate-600" x-text="(parseFloat(doc.distance_muatan) + parseFloat(doc.distance_kosongan)) + ' km'"></td>
                            <td class="px-6 py-4 text-slate-600 font-bold" x-text="doc.total_days_muatan"></td>
                            <td class="px-6 py-4 text-emerald-600 font-bold" x-text="doc.bbm_usage_muatan + ' L'"></td>
                            <td class="px-6 py-4 text-orange-600 font-bold" x-text="doc.bbm_usage_kosongan + ' L'"></td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button @click="exportSingle(doc)" class="text-emerald-600 font-bold hover:underline">Excel</button>
                                <a :href="'/documents/' + doc.id + '/edit'" class="text-indigo-600 font-bold hover:underline">Edit</a>
                                <a :href="'/documents/' + doc.id + '/edit?duplicate=true'" class="text-amber-600 font-bold hover:underline">Duplicate</a>
                                <button @click="deleteDoc(doc.id)" class="text-red-500 font-bold hover:underline">Hapus</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function rekap() {
        return {
            docs: [],
            uniqueCustomers: [],
            filter: { search: '', customer: '', sort: 'newest' },

            init() {
                this.load();
            },

            load() {
                this.docs = JSON.parse(localStorage.getItem('documents') || '[]');
                this.uniqueCustomers = [...new Set(this.docs.map(d => d.customer_name))];
            },

            get filteredDocs() {
                let filtered = this.docs.filter(d => {
                    const searchMatch = d.customer_name.toLowerCase().includes(this.filter.search.toLowerCase()) ||
                                      d.cargo_name.toLowerCase().includes(this.filter.search.toLowerCase());
                    const customerMatch = this.filter.customer === '' || d.customer_name === this.filter.customer;
                    return searchMatch && customerMatch;
                });

                if (this.filter.sort === 'newest') filtered.sort((a,b) => b.id - a.id);
                if (this.filter.sort === 'oldest') filtered.sort((a,b) => a.id - b.id);
                if (this.filter.sort === 'weight') filtered.sort((a,b) => b.total_weight - a.total_weight);

                return filtered;
            },

            resetFilter() {
                this.filter = { search: '', customer: '', sort: 'newest' };
            },

            deleteDoc(id) {
                if (confirm('Hapus dokumen ini?')) {
                    const filtered = this.docs.filter(d => d.id !== id);
                    localStorage.setItem('documents', JSON.stringify(filtered));
                    this.load();
                }
            },

            exportSingle(doc) {
                // Kirim data ke backend untuk diproses menggunakan template logistics-template.xlsx
                fetch('{{ route("export.excel") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(doc)
                })
                .then(response => {
                    if (!response.ok) throw new Error('Gagal export file');
                    return response.blob();
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `TJA-${doc.customer_name}-${doc.id}.xlsx`;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal mengekspor file Excel. Pastikan template sudah tersedia.');
                });
            },

            exportMultiple() {
                const docs = this.filteredDocs;
                if (docs.length === 0) return alert('Tidak ada data untuk dieksport.');
                if (confirm(`Export ${docs.length} file sekaligus?`)) {
                    docs.forEach((doc, index) => {
                        setTimeout(() => {
                            this.exportSingle(doc);
                        }, index * 1000); // Beri jeda 1 detik tiap file agar tidak diblokir browser
                    });
                }
            }
        }
    }
</script>
@endpush
@endsection
