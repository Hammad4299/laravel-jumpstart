<?php

namespace App\Classes\Export;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class CSVExporter extends PHPSpreadsheetExporter {
    /**
     * @return string
     */
    function getOutputFileExtension() {
        return 'csv';
    }

    function save($absolutePath){
        $writer = new Csv($this->spreadsheet);
        $writer->save($absolutePath);
    }
}
