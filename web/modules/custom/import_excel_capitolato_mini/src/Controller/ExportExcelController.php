<?php

namespace Drupal\import_excel_capitolato_mini\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



class ExportExcelController extends ControllerBase {

  public function export() {
    $connection = \Drupal::database();
    $results = $connection->select('dati_importati', 'd')
      ->fields('d', ['nome', 'email'])
      ->execute()
      ->fetchAll();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Nome');
    $sheet->setCellValue('B1', 'Email');

    $rowCount = 2;
    foreach ($results as $row) {
      $sheet->setCellValue('A' . $rowCount, $row->nome);
      $sheet->setCellValue('B' . $rowCount, $row->email);
      $rowCount++;
    }

    $response = new StreamedResponse(function() use ($spreadsheet) {
      $writer = new Xlsx($spreadsheet);
      $writer->save('php://output');
    });

    $fileName = 'export_capitolato_' . date('Y-m-d') . '.xlsx';
    $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
    
    return $response;
  }
}