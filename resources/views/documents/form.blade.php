@extends('layouts.app')

@section('content')
<div x-data="documentForm()" x-init="init()">
    <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight" x-text="isEdit ? 'Edit Dokumen' : 'Dokumen Baru'"></h1>
            <p class="text-slate-500 text-sm font-medium">Pastikan data sesuai dengan template Excel PT. TJA</p>
        </div>
        <div class="flex space-x-3">
            <button @click="resetForm()" class="px-6 py-2 text-slate-500 font-bold hover:text-slate-700 transition">Reset Draft</button>
            <button @click="saveDocument()" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition">SIMPAN PERMANEN</button>
        </div>
    </div>

    <div class="space-y-6 pb-24">
        <!-- 1. INFORMASI CUSTOMER & LOKASI -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-slate-50 px-8 py-4 border-b border-slate-100 font-black text-slate-400 text-xs uppercase tracking-widest">
                Informasi Customer & Alamat
            </div>
            <div class="p-8 space-y-6">
                <!-- Row: Customer & Perusahaan -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase mb-2">Customer</label>
                        <input type="text" x-model="form.customer_name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none font-medium">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-700 uppercase mb-2">Nama Perusahaan</label>
                        <input type="text" x-model="form.company_name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none font-medium">
                    </div>
                </div>

                <!-- Row: Alamat -->
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase mb-2">Alamat</label>
                    <textarea x-model="form.address" rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none font-medium"></textarea>
                </div>

                <hr class="border-slate-100">

                <!-- Section: Muat -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-indigo-600 text-sm">ALAMAT MUAT</h3>
                            <div class="relative w-2/3">
                                <input type="text" placeholder="Cari Lokasi Muat..." @input.debounce.500ms="searchLocation($event.target.value, 'muat')" class="w-full px-4 py-2 text-xs rounded-lg border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                                <div x-show="results.muat.length" class="absolute z-50 w-full mt-1 bg-white border border-slate-100 rounded-lg shadow-xl max-h-40 overflow-y-auto">
                                    <template x-for="res in results.muat">
                                        <button @click="selectLocation(res, 'muat')" class="w-full text-left px-3 py-2 hover:bg-indigo-50 text-[10px] border-b border-slate-50 last:border-0" x-text="res.display_name"></button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-black text-slate-400 uppercase">Nama Tempat</label>
                                <input type="text" x-model="form.muat_name" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm font-bold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase">Kota</label>
                                <input type="text" x-model="form.muat_city" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase">Provinsi</label>
                                <input type="text" x-model="form.muat_province" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase">PIC Muat</label>
                                <input type="text" x-model="form.muat_pic" class="w-full bg-transparent border-b-2 border-slate-200 focus:border-indigo-500 outline-none py-1 text-sm font-bold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase">No Tlp</label>
                                <input type="text" x-model="form.muat_phone" class="w-full bg-transparent border-b-2 border-slate-200 focus:border-indigo-500 outline-none py-1 text-sm font-bold">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Bongkar -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-orange-600 text-sm">ALAMAT BONGKAR</h3>
                            <div class="relative w-2/3">
                                <input type="text" placeholder="Cari Lokasi Bongkar..." @input.debounce.500ms="searchLocation($event.target.value, 'bongkar')" class="w-full px-4 py-2 text-xs rounded-lg border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                                <div x-show="results.bongkar.length" class="absolute z-50 w-full mt-1 bg-white border border-slate-100 rounded-lg shadow-xl max-h-40 overflow-y-auto">
                                    <template x-for="res in results.bongkar">
                                        <button @click="selectLocation(res, 'bongkar')" class="w-full text-left px-3 py-2 hover:bg-indigo-50 text-[10px] border-b border-slate-50 last:border-0" x-text="res.display_name"></button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-black text-slate-400 uppercase">Nama Tempat</label>
                                <input type="text" x-model="form.bongkar_name" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm font-bold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase">Kota</label>
                                <input type="text" x-model="form.bongkar_city" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase">Provinsi</label>
                                <input type="text" x-model="form.bongkar_province" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase">PIC Bongkar</label>
                                <input type="text" x-model="form.bongkar_pic" class="w-full bg-transparent border-b-2 border-slate-200 focus:border-indigo-500 outline-none py-1 text-sm font-bold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase">No Tlp</label>
                                <input type="text" x-model="form.bongkar_phone" class="w-full bg-transparent border-b-2 border-slate-200 focus:border-indigo-500 outline-none py-1 text-sm font-bold">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                    <div id="map-muat" class="map-container border border-slate-200 shadow-inner"></div>
                    <div id="map-bongkar" class="map-container border border-slate-200 shadow-inner"></div>
                </div>

                <div class="bg-indigo-900 text-white p-6 rounded-2xl flex items-center justify-between shadow-lg">
                    <div class="flex items-center space-x-4">
                        <div class="bg-indigo-800 p-3 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-tighter text-indigo-300">Estimasi Jarak Tempuh (OSRM)</p>
                            <p class="text-2xl font-black" x-text="form.jarak_tempuh_estimasi + ' Km'"></p>
                        </div>
                    </div>
                    <div class="w-1/3">
                        <label class="block text-[10px] font-black uppercase text-indigo-300 mb-1">Jarak Tempuh Manual (Km)</label>
                        <input type="number" x-model.number="form.distance_manual" class="w-full bg-indigo-800 border-none rounded-xl px-4 py-2 text-xl font-black outline-none focus:ring-2 focus:ring-white">
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. CARGO & DIMENSI -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-slate-50 px-8 py-4 border-b border-slate-100 font-black text-slate-400 text-xs uppercase tracking-widest">
                Detail Cargo & Kendaraan
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-slate-700 uppercase mb-2">Nama Cargo</label>
                            <input type="text" x-model="form.cargo_name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none font-bold text-slate-800">
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase">Panjang (m)</label>
                                <input type="number" step="0.01" x-model.number="form.length" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-bold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase">Lebar (m)</label>
                                <input type="number" step="0.01" x-model.number="form.width" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-bold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase">Tinggi (m)</label>
                                <input type="number" step="0.01" x-model.number="form.height" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-bold">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <p class="text-[10px] font-black text-slate-400 uppercase">Kubikasi (M3)</p>
                                <p class="text-xl font-black text-slate-800" x-text="form.cubication"></p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <!-- Hint Total Kubik -->
                                <div class="bg-indigo-50 p-2 mb-2 rounded-lg border border-indigo-100">
                                    <p class="text-[9px] text-indigo-800 leading-tight">
                                        <span class="font-black">ℹ️ Rumus:</span><br>
                                        Kubikasi × Qty<br><br>
                                        <span class="font-black">Contoh:</span><br>
                                        <span x-text="form.cubication"></span> × <span x-text="form.quantity"></span> = <span x-text="form.total_cubication"></span> M3
                                    </p>
                                </div>
                                <p class="text-[10px] font-black text-slate-400 uppercase">Total Kubik (M3)</p>
                                <p class="text-xl font-black text-indigo-600" x-text="form.total_cubication"></p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase">Berat Satuan (Kg)</label>
                                <input type="number" step="0.01" x-model.number="form.unit_weight" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-bold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase">Qty (Lembar)</label>
                                <input type="number" x-model.number="form.quantity" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-bold">
                            </div>
                        </div>
                        <div class="bg-indigo-600 p-4 rounded-xl text-white shadow-lg">
                            <!-- Hint Total Berat -->
                            <div class="bg-indigo-400/30 p-2 mb-2 rounded-lg border border-indigo-400/50">
                                <p class="text-[9px] text-white leading-tight">
                                    <span class="font-black">ℹ️ Rumus:</span><br>
                                    Berat Satuan × Qty<br><br>
                                    <span class="font-black">Contoh:</span><br>
                                    <span x-text="form.unit_weight"></span> Kg × <span x-text="form.quantity"></span> = <span x-text="form.total_weight"></span> Kg
                                </p>
                            </div>
                            <p class="text-[10px] font-black uppercase text-indigo-300">Total Berat (Kg)</p>
                            <p class="text-3xl font-black" x-text="form.total_weight"></p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-xs font-black text-slate-700 uppercase mb-2">Merk Kendaraan</label>
                                <select x-model="form.vehicle_brand" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none font-bold">
                                    <option value="">Pilih Merk</option>
                                    <template x-for="brand in master.brands">
                                        <option :value="brand" x-text="brand"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 uppercase mb-2">No Mobil</label>
                                <input type="text" x-model="form.vehicle_plate" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none font-bold uppercase">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 uppercase mb-2">Jenis Kendaraan</label>
                                <select x-model="form.vehicle_type" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none font-bold">
                                    <option value="">Pilih Jenis</option>
                                    <template x-for="type in master.types">
                                        <option :value="type" x-text="type"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="bg-slate-900 text-white p-6 rounded-2xl">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">WAKTU PERJALANAN</p>
                            <div>
                                <label class="block text-xs font-bold mb-2">Jarak dari garasi ketempat muat (Km)</label>
                                <input type="number" x-model.number="form.distance_garage_to_muat" class="w-full bg-slate-800 border-none rounded-xl px-4 py-3 text-2xl font-black outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. MUATAN & KOSONGAN (SIDE BY SIDE) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- MUATAN -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-indigo-600 px-8 py-4 border-b border-indigo-700 font-black text-white text-xs uppercase tracking-widest">
                    MUATAN
                </div>
                <div class="p-8 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-600 uppercase">Jarak (Km)</span>
                        <input type="number" x-model.number="form.distance_muatan" class="w-32 text-right px-3 py-2 rounded-lg border border-slate-200 font-bold focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-600 uppercase">Kecepatan Rata2 (Km Perjam)</span>
                        <input type="number" x-model.number="form.speed_muatan" class="w-32 text-right px-3 py-2 rounded-lg border border-slate-200 font-bold">
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-600 uppercase">Jam Kerja (Jam Perhari)</span>
                        <input type="number" x-model.number="form.work_hours_muatan" class="w-32 text-right px-3 py-2 rounded-lg border border-slate-200 font-bold">
                    </div>
                    <div class="bg-indigo-50 p-4 rounded-xl space-y-2 border border-indigo-100">
                        <!-- Hint Jarak Tempuh Muatan -->
                        <div class="bg-indigo-100/50 p-2 rounded-lg border border-indigo-200">
                            <p class="text-[9px] text-indigo-900 leading-tight">
                                <span class="font-black">ℹ️ Rumus:</span><br>
                                Kecepatan Rata-rata × Jam Kerja<br><br>
                                <span class="font-black">Contoh:</span><br>
                                <span x-text="form.speed_muatan"></span> Km/Jam × <span x-text="form.work_hours_muatan"></span> Jam/Hari = <span x-text="form.daily_dist_muatan"></span> Km/Hari
                            </p>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-black text-indigo-400 uppercase">Jarak Tempuh (Km Perhari)</span>
                            <span class="text-xl font-black text-indigo-900" x-text="form.daily_dist_muatan"></span>
                        </div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl space-y-2 border border-slate-100">
                        <!-- Hint Total Perjalanan Muatan -->
                        <div class="bg-indigo-50 p-2 rounded-lg border border-indigo-100">
                            <p class="text-[9px] text-indigo-800 leading-tight">
                                <span class="font-black">ℹ️ Rumus:</span><br>
                                Jarak ÷ Jarak Tempuh<br><br>
                                <span class="font-black">Contoh:</span><br>
                                <span x-text="form.distance_muatan"></span> Km ÷ <span x-text="form.daily_dist_muatan"></span> Km/Hari = <span x-text="form.total_perjalanan_muatan"></span> Hari
                            </p>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-black text-slate-400 uppercase">Total Perjalanan (Hari)</span>
                            <span class="text-xl font-black text-slate-800" x-text="form.total_perjalanan_muatan"></span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between px-2">
                        <span class="text-sm font-bold text-slate-600 uppercase">Muat (Hari)</span>
                        <input type="number" x-model.number="form.muat_days" class="w-32 text-right px-3 py-2 rounded-lg border border-slate-200 font-bold">
                    </div>
                    <div class="flex items-center justify-between px-2">
                        <span class="text-sm font-bold text-slate-600 uppercase">Bongkar (Hari)</span>
                        <input type="number" x-model.number="form.bongkar_days" class="w-32 text-right px-3 py-2 rounded-lg border border-slate-200 font-bold">
                    </div>
                    <div class="bg-indigo-600 p-6 rounded-2xl text-white shadow-xl mt-4 space-y-2">
                        <!-- Hint Total Muatan -->
                        <div class="bg-indigo-400/30 p-2 rounded-lg border border-indigo-400/50">
                            <p class="text-[9px] text-indigo-100 leading-tight">
                                <span class="font-black">ℹ️ Rumus:</span><br>
                                Total Perjalanan + Muat + Bongkar<br><br>
                                <span class="font-black">Contoh:</span><br>
                                <span x-text="form.total_perjalanan_muatan"></span> + <span x-text="form.muat_days"></span> + <span x-text="form.bongkar_days"></span> = <span x-text="form.total_hari_muatan"></span> Hari
                            </p>
                        </div>
                        <p class="text-[10px] font-black uppercase text-indigo-200">TOTAL (Hari)</p>
                        <p class="text-3xl font-black" x-text="form.total_hari_muatan"></p>
                    </div>
                </div>
            </div>

            <!-- KOSONGAN -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-slate-800 px-8 py-4 border-b border-slate-900 font-black text-white text-xs uppercase tracking-widest">
                    KOSONGAN
                </div>
                <div class="p-8 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-600 uppercase">Jarak (Km)</span>
                        <input type="number" x-model.number="form.distance_kosongan" class="w-32 text-right px-3 py-2 rounded-lg border border-slate-200 font-bold focus:ring-2 focus:ring-slate-800">
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-600 uppercase">Kecepatan Rata2 (Km Perjam)</span>
                        <input type="number" x-model.number="form.speed_kosongan" class="w-32 text-right px-3 py-2 rounded-lg border border-slate-200 font-bold">
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-600 uppercase">Jam Kerja (Jam Perhari)</span>
                        <input type="number" x-model.number="form.work_hours_kosongan" class="w-32 text-right px-3 py-2 rounded-lg border border-slate-200 font-bold">
                    </div>
                    <div class="bg-slate-100 p-4 rounded-xl space-y-2 border border-slate-200">
                        <!-- Hint Jarak Tempuh Kosongan -->
                        <div class="bg-indigo-50 p-2 rounded-lg border border-indigo-100">
                            <p class="text-[9px] text-indigo-800 leading-tight">
                                <span class="font-black">ℹ️ Rumus:</span><br>
                                Kecepatan Rata-rata × Jam Kerja<br><br>
                                <span class="font-black">Contoh:</span><br>
                                <span x-text="form.speed_kosongan"></span> Km/Jam × <span x-text="form.work_hours_kosongan"></span> Jam/Hari = <span x-text="form.daily_dist_kosongan"></span> Km/Hari
                            </p>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-black text-slate-400 uppercase">Jarak Tempuh (Km Perhari)</span>
                            <span class="text-xl font-black text-slate-800" x-text="form.daily_dist_kosongan"></span>
                        </div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl space-y-2 border border-slate-100">
                        <!-- Hint Total Perjalanan Kosongan -->
                        <div class="bg-indigo-50 p-2 rounded-lg border border-indigo-100">
                            <p class="text-[9px] text-indigo-800 leading-tight">
                                <span class="font-black">ℹ️ Rumus:</span><br>
                                Jarak ÷ Jarak Tempuh<br><br>
                                <span class="font-black">Contoh:</span><br>
                                <span x-text="form.distance_kosongan"></span> Km ÷ <span x-text="form.daily_dist_kosongan"></span> Km/Hari = <span x-text="form.total_perjalanan_kosongan"></span> Hari
                            </p>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-black text-slate-400 uppercase">Total Perjalanan (Hari)</span>
                            <span class="text-xl font-black text-slate-800" x-text="form.total_perjalanan_kosongan"></span>
                        </div>
                    </div>
                    <!-- Empty space to align with Muatan height -->
                    <div class="h-[108px]"></div>
                    <div class="bg-slate-800 p-6 rounded-2xl text-white shadow-xl mt-4 space-y-2">
                        <!-- Hint Total Kosongan -->
                        <div class="bg-slate-700/50 p-2 rounded-lg border border-slate-600">
                            <p class="text-[9px] text-slate-300 leading-tight">
                                <span class="font-black">ℹ️ Rumus:</span><br>
                                Total Perjalanan<br><br>
                                <span class="font-black">Contoh:</span><br>
                                <span x-text="form.total_perjalanan_kosongan"></span> = <span x-text="form.total_hari_kosongan"></span> Hari
                            </p>
                        </div>
                        <p class="text-[10px] font-black uppercase text-slate-400">TOTAL (Hari)</p>
                        <p class="text-3xl font-black" x-text="form.total_hari_kosongan"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. BAHAN BAKAR MINYAK (SIDE BY SIDE) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-slate-50 px-8 py-4 border-b border-slate-100 font-black text-slate-400 text-xs uppercase tracking-widest">
                BAHAN BAKAR MINYAK (BBM)
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- BBM MUATAN -->
                <div class="space-y-4">
                    <h3 class="font-black text-indigo-600 text-xs uppercase tracking-widest mb-6 border-b pb-2">BBM MUATAN</h3>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-600 uppercase">Jarak (Km)</span>
                        <input type="number" x-model.number="form.bbm_dist_muatan" class="w-32 text-right px-3 py-2 rounded-lg border border-slate-200 font-bold">
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-600 uppercase tracking-tighter">Rasio BBM (1 Liter Bbm : Km)</span>
                        <input type="number" step="0.1" x-model.number="form.bbm_ratio_muatan" class="w-32 text-right px-3 py-2 rounded-lg border border-slate-200 font-bold">
                    </div>
                    <div class="bg-emerald-600 p-4 rounded-xl text-white shadow-lg mt-4 flex justify-between items-center">
                        <span class="text-xs font-black uppercase text-emerald-200">PEMAKAIAN (Liter)</span>
                        <span class="text-2xl font-black" x-text="form.bbm_usage_muatan"></span>
                    </div>
                </div>

                <!-- BBM KOSONGAN -->
                <div class="space-y-4">
                    <h3 class="font-black text-slate-600 text-xs uppercase tracking-widest mb-6 border-b pb-2">BBM KOSONGAN</h3>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-600 uppercase">Jarak (Km)</span>
                        <input type="number" x-model.number="form.bbm_dist_kosongan" class="w-32 text-right px-3 py-2 rounded-lg border border-slate-200 font-bold">
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-600 uppercase tracking-tighter">Rasio BBM (1 Liter Bbm : Km)</span>
                        <input type="number" step="0.1" x-model.number="form.bbm_ratio_kosongan" class="w-32 text-right px-3 py-2 rounded-lg border border-slate-200 font-bold">
                    </div>
                    <div class="bg-emerald-800 p-4 rounded-xl text-white shadow-lg mt-4 flex justify-between items-center">
                        <span class="text-xs font-black uppercase text-emerald-400">PEMAKAIAN (Liter)</span>
                        <span class="text-2xl font-black" x-text="form.bbm_usage_kosongan"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function documentForm() {
        return {
            isEdit: false,
            editId: null,
            results: { muat: [], bongkar: [] },
            maps: { muat: null, bongkar: null },
            markers: { muat: null, bongkar: null },
            master: { brands: [], types: [] },
            form: {
                id: null,
                customer_name: '', company_name: '', address: '',
                // Muat
                muat_name: '', muat_city: '', muat_province: '', muat_pic: '', muat_phone: '',
                muat_lat: null, muat_lon: null, muat_address: '',
                // Bongkar
                bongkar_name: '', bongkar_city: '', bongkar_province: '', bongkar_pic: '', bongkar_phone: '',
                bongkar_lat: null, bongkar_lon: null, bongkar_address: '',

                jarak_tempuh_estimasi: 0,
                distance_manual: 0, // In Km

                cargo_name: '', length: 0, width: 0, height: 0, cubication: 0, unit_weight: 0, quantity: 0,
                total_weight: 0, total_cubication: 0,

                vehicle_brand: '', vehicle_plate: '', vehicle_type: '',
                distance_garage_to_muat: 0,

                // Muatan Logic
                distance_muatan: 0, speed_muatan: 30, work_hours_muatan: 10, muat_days: 1, bongkar_days: 1,
                daily_dist_muatan: 0, total_perjalanan_muatan: 0, total_hari_muatan: 0,

                // Kosongan Logic
                distance_kosongan: 0, speed_kosongan: 45, work_hours_kosongan: 10,
                daily_dist_kosongan: 0, total_perjalanan_kosongan: 0, total_hari_kosongan: 0,

                // BBM Logic
                bbm_dist_muatan: 0, bbm_ratio_muatan: 2.5, bbm_usage_muatan: 0,
                bbm_dist_kosongan: 0, bbm_ratio_kosongan: 3.0, bbm_usage_kosongan: 0,

                created_at: new Date().toISOString()
            },

            init() {
                this.master.brands = JSON.parse(localStorage.getItem('vehicle_brands') || '[]');
                this.master.types = JSON.parse(localStorage.getItem('vehicle_types') || '[]');

                this.initMaps();

                const path = window.location.pathname;
                const match = path.match(/\/documents\/(\d+)\/edit/);
                const isDuplicate = new URLSearchParams(window.location.search).get('duplicate');

                if (match) {
                    this.isEdit = true;
                    this.editId = parseInt(match[1]);
                    this.loadDocument(this.editId);
                    if (isDuplicate) {
                        this.isEdit = false;
                        this.editId = null;
                        this.form.id = null;
                        this.form.customer_name += ' (Copy)';
                    }
                } else {
                    const draft = localStorage.getItem('draft_document');
                    if (draft) this.form = JSON.parse(draft);
                }

                this.$watch('form', () => this.calculateAll(), { deep: true });
            },

            initMaps() {
                this.maps.muat = L.map('map-muat').setView([-6.2088, 106.8456], 5);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.maps.muat);

                this.maps.bongkar = L.map('map-bongkar').setView([-6.2088, 106.8456], 5);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.maps.bongkar);
            },

            loadDocument(id) {
                const docs = JSON.parse(localStorage.getItem('documents') || '[]');
                const doc = docs.find(d => d.id === id);
                if (doc) {
                    this.form = { ...this.form, ...doc };
                    if (this.form.muat_lat) this.setMarker(this.form.muat_lat, this.form.muat_lon, 'muat');
                    if (this.form.bongkar_lat) this.setMarker(this.form.bongkar_lat, this.form.bongkar_lon, 'bongkar');
                }
            },

            calculateAll() {
                // Cargo
                this.form.cubication = (this.form.length * this.form.width * this.form.height).toFixed(3);
                this.form.total_weight = (this.form.unit_weight * this.form.quantity).toFixed(1);
                this.form.total_cubication = (this.form.cubication * this.form.quantity).toFixed(1);

                // Muatan
                this.form.daily_dist_muatan = this.form.speed_muatan * this.form.work_hours_muatan;
                this.form.total_perjalanan_muatan = (this.form.daily_dist_muatan > 0 ? this.form.distance_muatan / this.form.daily_dist_muatan : 0).toFixed(3);
                this.form.total_hari_muatan = (parseFloat(this.form.total_perjalanan_muatan) + this.form.muat_days + this.form.bongkar_days).toFixed(3);

                // Kosongan
                this.form.daily_dist_kosongan = this.form.speed_kosongan * this.form.work_hours_kosongan;
                this.form.total_perjalanan_kosongan = (this.form.daily_dist_kosongan > 0 ? this.form.distance_kosongan / this.form.daily_dist_kosongan : 0).toFixed(3);
                this.form.total_hari_kosongan = parseFloat(this.form.total_perjalanan_kosongan).toFixed(3);

                // BBM
                this.form.bbm_usage_muatan = (this.form.bbm_ratio_muatan > 0 ? this.form.bbm_dist_muatan / this.form.bbm_ratio_muatan : 0).toFixed(1);
                this.form.bbm_usage_kosongan = (this.form.bbm_ratio_kosongan > 0 ? this.form.bbm_dist_kosongan / this.form.bbm_ratio_kosongan : 0).toFixed(2);

                if (!this.isEdit) localStorage.setItem('draft_document', JSON.stringify(this.form));
            },

            async searchLocation(q, type) {
                if (!q || q.length < 3) return;
                const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${q}&countrycodes=id`);
                this.results[type] = await res.json();
            },

            selectLocation(loc, type) {
                this.form[`${type}_lat`] = loc.lat;
                this.form[`${type}_lon`] = loc.lon;
                this.form[`${type}_address`] = loc.display_name;

                // Parse city and province from address if possible
                const parts = loc.display_name.split(',');
                if (parts.length > 1) {
                    this.form[`${type}_city`] = parts[parts.length - 3]?.trim();
                    this.form[`${type}_province`] = parts[parts.length - 2]?.trim();
                }

                this.results[type] = [];
                this.setMarker(loc.lat, loc.lon, type);
                this.calculateRoute();
            },

            setMarker(lat, lon, type) {
                if (this.markers[type]) this.maps[type].removeLayer(this.markers[type]);
                this.markers[type] = L.marker([lat, lon]).addTo(this.maps[type]);
                this.maps[type].setView([lat, lon], 12);
            },

            async calculateRoute() {
                if (this.form.muat_lat && this.form.bongkar_lat) {
                    const url = `https://router.project-osrm.org/route/v1/driving/${this.form.muat_lon},${this.form.muat_lat};${this.form.bongkar_lon},${this.form.bongkar_lat}?overview=false`;
                    const res = await fetch(url);
                    const data = await res.json();
                    if (data.routes && data.routes.length) {
                        const distKm = Math.round(data.routes[0].distance / 1000);
                        this.form.jarak_tempuh_estimasi = distKm;
                        this.form.distance_manual = distKm;
                        this.form.distance_muatan = distKm;
                        this.form.distance_kosongan = distKm;
                        this.form.bbm_dist_muatan = distKm;
                        this.form.bbm_dist_kosongan = distKm;
                    }
                }
            },

            saveDocument() {
                let docs = JSON.parse(localStorage.getItem('documents') || '[]');
                if (this.isEdit) {
                    const idx = docs.findIndex(d => d.id === this.editId);
                    docs[idx] = { ...this.form };
                } else {
                    this.form.id = Date.now();
                    docs.push({ ...this.form });
                    localStorage.removeItem('draft_document');
                }
                localStorage.setItem('documents', JSON.stringify(docs));
                Swal.fire('Berhasil', 'Dokumen disimpan.', 'success').then(() => {
                    window.location.href = '{{ route("dashboard") }}';
                });
            },

            resetForm() {
                if (confirm('Hapus draf ini?')) {
                    localStorage.removeItem('draft_document');
                    location.reload();
                }
            }
        }
    }
</script>
@endpush
@endsection
