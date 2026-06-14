@extends('layouts.app')

@section('content')
<div x-data="brandMaster()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Master Merk Kendaraan</h1>
        <button @click="openModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold shadow hover:bg-indigo-700 transition">
            + Tambah Merk
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-sm font-bold text-slate-600 uppercase">Nama Merk</th>
                    <th class="px-6 py-4 text-sm font-bold text-slate-600 uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <template x-for="(brand, index) in brands" :key="index">
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-slate-700 font-medium" x-text="brand"></td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button @click="editBrand(index)" class="text-indigo-600 hover:text-indigo-900 font-bold">Edit</button>
                            <button @click="deleteBrand(index)" class="text-red-600 hover:text-red-900 font-bold">Hapus</button>
                        </td>
                    </tr>
                </template>
                <template x-if="brands.length === 0">
                    <tr>
                        <td colspan="2" class="px-6 py-8 text-center text-slate-400 italic">Belum ada data merk kendaraan.</td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div x-show="modalOpen" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="modalOpen = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 py-6">
                    <h3 class="text-xl font-bold text-slate-800 mb-4" x-text="editIndex === null ? 'Tambah Merk Kendaraan' : 'Edit Merk Kendaraan'"></h3>
                    <input type="text" x-model="currentName" placeholder="Contoh: Volvo FM12" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end space-x-3">
                    <button @click="modalOpen = false" class="px-4 py-2 text-slate-600 font-bold hover:text-slate-800 transition">Batal</button>
                    <button @click="saveBrand()" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold shadow hover:bg-indigo-700 transition">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function brandMaster() {
        return {
            brands: [],
            modalOpen: false,
            editIndex: null,
            currentName: '',

            init() {
                this.brands = JSON.parse(localStorage.getItem('vehicle_brands') || '[]');
            },

            openModal() {
                this.editIndex = null;
                this.currentName = '';
                this.modalOpen = true;
            },

            editBrand(index) {
                this.editIndex = index;
                this.currentName = this.brands[index];
                this.modalOpen = true;
            },

            saveBrand() {
                if (!this.currentName.trim()) return;

                if (this.editIndex === null) {
                    this.brands.push(this.currentName.trim());
                } else {
                    this.brands[this.editIndex] = this.currentName.trim();
                }

                this.persist();
                this.modalOpen = false;
                Swal.fire({ icon: 'success', title: 'Tersimpan', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
            },

            deleteBrand(index) {
                if (confirm('Hapus merk ini?')) {
                    this.brands.splice(index, 1);
                    this.persist();
                }
            },

            persist() {
                localStorage.setItem('vehicle_brands', JSON.stringify(this.brands));
            }
        }
    }
</script>
@endpush
@endsection
