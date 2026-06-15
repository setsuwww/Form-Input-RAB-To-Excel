<?php
require 'vendor/autoload.php';
$spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load('logistics-template.xlsx');
echo "Sheet names: " . implode(', ', $spreadsheet->getSheetNames()) . "\n";
$sheet = $spreadsheet->getActiveSheet();
echo "Highest row: " . $sheet->getHighestRow() . "\n";
echo "Highest column: " . $sheet->getHighestColumn() . "\n";
