<?php

namespace App\Services;

use App\Services\Interfaces\ExportServiceInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService implements ExportServiceInterface
{
    public function exportAsExcel(string $filename, array $header, array $content): Response
    {
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();

        for ($i = 0; $i < count($header); ++$i) {
            $coordinate = [$i + 1, 1];
            $activeWorksheet->getStyle($coordinate)->getFont()->setBold(true);
            $activeWorksheet->setCellValue($coordinate, $header[$i]);
        }

        for ($i = 0; $i < count($content); ++$i) {
            $row = $content[$i];
            for ($j = 0; $j < count($row); ++$j) {
                $activeWorksheet->setCellValue([$j + 1, $i + 2], $row[$j]);
            }
        }

        $writer = IOFactory::createWriter($spreadsheet, IOFactory::WRITER_XLSX);

        return $this->createResponse($writer, $filename);
    }

    public function createResponse(IWriter $writer, string $filename): StreamedResponse
    {
        $response = new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );

        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
