<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class GenerateLogisticsForm extends Command
{
    /**
     * Nama dan signature dari perintah artisan.
     *
     * @var string
     */
    protected $signature = "app:generate-logistics-form";

    /**
     * Deskripsi perintah artisan.
     *
     * @var string
     */
    protected $description = "Isi data dan formula ke template logistics-template.xlsx";

    /**
     * Eksekusi perintah artisan.
     */
    public function handle()
    {
        // 1. Tentukan Path
        $templatePath = base_path("logistics-template.xlsx");
        $outputPath = storage_path("app/public/Form_Logistik_Final.xlsx");

        $this->info("🔍 Mencari template di: $templatePath");

        if (!file_exists($templatePath)) {
            $this->error(
                "❌ ERROR: File 'logistics-template.xlsx' tidak ditemukan di root project.",
            );
            $this->line(
                "💡 Pastikan file tersebut ada di folder: " . base_path(),
            );
            return;
        }

        try {
            // 2. Load Template
            $this->info("📂 Memuat template...");
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getActiveSheet();

            // 3. Isi Formula ke Cell yang sesuai
            // Pastikan koordinat ini sesuai dengan layout template Anda
            $this->info("✍️ Menyisipkan formula...");

            // DETAIL CARGO
            $sheet->setCellValue("E11", "=E9*E10"); // Total Berat
            $sheet->setCellValue("E12", "=E8*E10"); // Total Kubik

            // MUATAN
            $sheet->setCellValue("C21", "=C19*C20"); // Jarak Tempuh
            $sheet->setCellValue("C22", "=IF(C21>0,C18/C21,0)"); // Trip
            $sheet->setCellValue("C25", "=C22*(C20+C23+C24)"); // Total Jam

            // KOSONGAN
            $sheet->setCellValue("G21", "=G19*G20"); // Jarak Tempuh
            $sheet->setCellValue("G22", "=IF(G21>0,G18/G21,0)"); // Trip
            $sheet->setCellValue("G23", "=G22*G20"); // Total Jam

            // BBM
            $sheet->setCellValue("E29", "=C18+G18"); // Total Jarak
            $sheet->setCellValue("E31", "=IF(E30>0,E29/E30,0)"); // Total Liter

            // 4. Format Angka (Elegance)
            $numberFormat = ["numberFormat" => ["formatCode" => "#,##0.00"]];
            $styleCells = ["E11:E12", "C21:C25", "G21:G23", "E29:E31"];
            foreach ($styleCells as $range) {
                $sheet->getStyle($range)->applyFromArray($numberFormat);
            }

            // 5. Simpan File
            $this->info("💾 Menyimpan file ke storage...");
            $writer = new Xlsx($spreadsheet);
            $writer->setPreCalculateFormulas(true); // Paksa kalkulasi saat simpan

            // Pastikan folder public ada
            if (!is_dir(storage_path("app/public"))) {
                mkdir(storage_path("app/public"), 0755, true);
            }

            $writer->save($outputPath);

            $this->info("✅ BERHASIL!");
            $this->line("--------------------------------------------------");
            $this->info("📄 File telah dibuat di: $outputPath");
            $this->line("--------------------------------------------------");
            $this->comment(
                "Tips: Jika Anda menggunakan server lokal, Anda bisa mengaksesnya di storage/app/public/ atau buat link dengan 'php artisan storage:link'",
            );
        } catch (\Exception $e) {
            $this->error("❌ TERJADI KESALAHAN: " . $e->getMessage());
            $this->line($e->getTraceAsString());
        }
    }
}
