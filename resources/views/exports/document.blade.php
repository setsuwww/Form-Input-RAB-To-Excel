<table>
    <thead>
        <tr>
            <th colspan="10" style="text-align: center;">WAKTU DAN BBM PT. TAMBUN JAYA ABADI</th>
        </tr>
        <tr></tr>
    </thead>
    <tbody>
        <!-- Customer Info -->
        <tr>
            <td colspan="2"><b>INFORMASI CUSTOMER</b></td>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td>Customer</td>
            <td colspan="4">{{ $document->customer_name }}</td>
            <td>PIC Muat</td>
            <td colspan="4">{{ $document->muat_pic }}</td>
        </tr>
        <tr>
            <td>Nama Perusahaan</td>
            <td colspan="4">{{ $document->company_name }}</td>
            <td>Telepon Muat</td>
            <td colspan="4">{{ $document->muat_phone }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td colspan="4">{{ $document->address }}</td>
            <td>PIC Bongkar</td>
            <td colspan="4">{{ $document->bongkar_pic }}</td>
        </tr>
        <tr>
            <td>Alamat Muat</td>
            <td colspan="4">{{ $document->muat_address }}</td>
            <td>Telepon Bongkar</td>
            <td colspan="4">{{ $document->bongkar_phone }}</td>
        </tr>
        <tr>
            <td>Kota/Provinsi Muat</td>
            <td colspan="4">{{ $document->muat_city }}, {{ $document->muat_province }}</td>
            <td></td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td>Alamat Bongkar</td>
            <td colspan="4">{{ $document->bongkar_address }}</td>
            <td></td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td>Kota/Provinsi Bongkar</td>
            <td colspan="4">{{ $document->bongkar_city }}, {{ $document->bongkar_province }}</td>
            <td></td>
            <td colspan="4"></td>
        </tr>
        <tr></tr>

        <!-- Cargo -->
        <tr>
            <td colspan="2"><b>CARGO</b></td>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td>Nama Cargo</td>
            <td colspan="2">{{ $document->cargo_name }}</td>
            <td>P/L/T</td>
            <td colspan="2">{{ $document->length }} / {{ $document->width }} / {{ $document->height }}</td>
            <td>Kubikasi</td>
            <td>{{ $document->cubication }}</td>
            <td>Total Kubik</td>
            <td>{{ $document->total_cubication }}</td>
        </tr>
        <tr>
            <td>Berat Satuan</td>
            <td>{{ $document->unit_weight }}</td>
            <td>Qty</td>
            <td>{{ $document->quantity }}</td>
            <td>Total Berat</td>
            <td>{{ $document->total_weight }}</td>
            <td colspan="4"></td>
        </tr>
        <tr></tr>

        <!-- Vehicle -->
        <tr>
            <td colspan="2"><b>KENDARAAN</b></td>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td>Merk</td>
            <td colspan="2">{{ $document->vehicle_brand }}</td>
            <td>Nomor Mobil</td>
            <td colspan="2">{{ $document->vehicle_plate }}</td>
            <td>Jenis</td>
            <td colspan="3">{{ $document->vehicle_type }}</td>
        </tr>
        <tr></tr>

        <!-- Perjalanan -->
        <tr>
            <td colspan="2"><b>PERJALANAN</b></td>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td>Jarak Garasi ke Muat</td>
            <td>{{ $document->distance_garage_to_muat }} km</td>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td><b>MUATAN</b></td>
            <td colspan="9"></td>
        </tr>
        <tr>
            <td>Jarak Muatan</td>
            <td>{{ $document->distance_muatan }} km</td>
            <td>Kecepatan</td>
            <td>{{ $document->speed_muatan }} km/jam</td>
            <td>Jam Kerja</td>
            <td>{{ $document->work_hours_muatan }} jam</td>
            <td>Hari Perjalanan</td>
            <td>{{ number_format($document->travel_days_muatan, 2) }}</td>
            <td>Total Hari</td>
            <td>{{ number_format($document->total_days_muatan, 2) }}</td>
        </tr>
        <tr>
            <td>Hari Muat</td>
            <td>{{ $document->muat_days }}</td>
            <td>Hari Bongkar</td>
            <td>{{ $document->bongkar_days }}</td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td><b>KOSONGAN</b></td>
            <td colspan="9"></td>
        </tr>
        <tr>
            <td>Jarak Kosongan</td>
            <td>{{ $document->distance_kosongan }} km</td>
            <td>Kecepatan</td>
            <td>{{ $document->speed_kosongan }} km/jam</td>
            <td>Jam Kerja</td>
            <td>{{ $document->work_hours_kosongan }} jam</td>
            <td>Hari Perjalanan</td>
            <td>{{ number_format($document->travel_days_kosongan, 2) }}</td>
            <td colspan="2"></td>
        </tr>
        <tr></tr>

        <!-- BBM -->
        <tr>
            <td colspan="2"><b>BBM</b></td>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td>BBM Muatan (Jarak/Rasio)</td>
            <td colspan="2">{{ $document->bbm_distance_muatan }} / {{ $document->bbm_ratio_muatan }}</td>
            <td>Pemakaian</td>
            <td>{{ number_format($document->bbm_usage_muatan, 2) }} L</td>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td>BBM Kosongan (Jarak/Rasio)</td>
            <td colspan="2">{{ $document->bbm_distance_kosongan }} / {{ $document->bbm_ratio_kosongan }}</td>
            <td>Pemakaian</td>
            <td>{{ number_format($document->bbm_usage_kosongan, 2) }} L</td>
            <td colspan="5"></td>
        </tr>
    </tbody>
</table>
