<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentExportController extends Controller
{
    public function export(Request $request)
    {
        $doc = $request->all();
        $templatePath = base_path("logistics-template.xlsx");

        if (!file_exists($templatePath)) {
            return response()->json(["error" => "Template not found"], 404);
        }

        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Mapping data dari frontend ke Template Excel
        // Sesuaikan koordinat cell dengan template logistics-template.xlsx Anda

        // INFORMASI CUSTOMER
        $sheet->setCellValue("B5", $doc["customer_name"] ?? "");
        $sheet->setCellValue("B6", $doc["address"] ?? "");
        $sheet->setCellValue("B7", $doc["muat_name"] ?? "");
        $sheet->setCellValue("B8", $doc["muat_pic"] ?? "");
        $sheet->setCellValue("B9", $doc["muat_phone"] ?? "");
        $sheet->setCellValue("B10", $doc["bongkar_name"] ?? "");
        $sheet->setCellValue("B11", $doc["bongkar_pic"] ?? "");
        $sheet->setCellValue("B12", $doc["bongkar_phone"] ?? "");
        $sheet->setCellValue("B13", $doc["distance_manual"] ?? "");
        $sheet->setCellValue("B14", $doc["cargo_name"] ?? "");

        // DETAIL CARGO
        $sheet->setCellValue("E5", $doc["length"] ?? 0);
        $sheet->setCellValue("E6", $doc["width"] ?? 0);
        $sheet->setCellValue("E7", $doc["height"] ?? 0);
        $sheet->setCellValue("E8", $doc["cubication"] ?? 0);
        $sheet->setCellValue("E9", $doc["unit_weight"] ?? 0);
        $sheet->setCellValue("E10", $doc["quantity"] ?? 0);
        $sheet->setCellValue("E11", "=E9*E10");
        $sheet->setCellValue("E12", "=E8*E10");

        // KENDARAAN
        $sheet->setCellValue("H5", $doc["vehicle_brand"] ?? "");
        $sheet->setCellValue("H6", $doc["vehicle_plate"] ?? "");
        $sheet->setCellValue("H7", $doc["vehicle_type"] ?? "");

        // MUATAN
        $sheet->setCellValue("C18", $doc["distance_muatan"] ?? 0);
        $sheet->setCellValue("C19", $doc["speed_muatan"] ?? 0);
        $sheet->setCellValue("C20", $doc["work_hours_muatan"] ?? 0);
        $sheet->setCellValue("C23", $doc["muat_days"] ?? 0);
        $sheet->setCellValue("C24", $doc["bongkar_days"] ?? 0);

        $sheet->setCellValue("C21", "=C19*C20");
        $sheet->setCellValue("C22", "=IF(C21>0,C18/C21,0)");
        $sheet->setCellValue("C25", "=C22*(C20+C23+C24)");

        // KOSONGAN
        $sheet->setCellValue("G18", $doc["distance_kosongan"] ?? 0);
        $sheet->setCellValue("G19", $doc["speed_kosongan"] ?? 0);
        $sheet->setCellValue("G20", $doc["work_hours_kosongan"] ?? 0);

        $sheet->setCellValue("G21", "=G19*G20");
        $sheet->setCellValue("G22", "=IF(G21>0,G18/G21,0)");
        $sheet->setCellValue("G23", "=G22*G20");

        // BBM
        $sheet->setCellValue("E30", $doc["bbm_ratio_muatan"] ?? 1);
        $sheet->setCellValue("E29", "=C18+G18");
        $sheet->setCellValue("E31", "=IF(E30>0,E29/E30,0)");

        $fileName =
            "TJA-" .
            ($doc["customer_name"] ?? "Export") .
            "-" .
            ($doc["id"] ?? time()) .
            ".xlsx";

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {
                $writer->save("php://output");
            },
            $fileName,
            [
                "Content-Type" =>
                    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                "Content-Disposition" =>
                    'attachment; filename="' . $fileName . '"',
            ],
        );
    }
}
