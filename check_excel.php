<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$file = 'Form_Logistik_Final.xlsx';
if (!file_exists($file)) {
    echo "File not found\n";
    exit;
}

$spreadsheet = IOFactory::load($file);
$sheet = $spreadsheet->getActiveSheet();

$cells = ['E11', 'E12', 'C21', 'C22', 'C25', 'G21', 'G22', 'G23', 'E29', 'E31'];

foreach ($cells as $cell) {
    echo "Cell $cell: " . $sheet->getCell($cell)->getValue() . "\n";
}
