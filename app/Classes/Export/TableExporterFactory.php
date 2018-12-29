<?php

namespace App\Classes\Export;

class TableExporterFactory {
    const TYPE_PDF = 'pdf';
    const TYPE_CSV = 'csv';
    const TYPE_EXCEL = 'excel';

    /**
     * @param string $type (e.g. TableExporterFactory::TYPE_PDF)
     * @return TableExporterContract|null
     */
    public function build($type) {
        $exporter = null;
        if($type === self::TYPE_PDF) {
            $exporter = new PDFExporter();
        } else if($type === self::TYPE_EXCEL) {
            $exporter = new ExcelExporter();
        } else if($type === self::TYPE_CSV) {
            $exporter = new CSVExporter();
        }
        
        return $exporter;
    }
}