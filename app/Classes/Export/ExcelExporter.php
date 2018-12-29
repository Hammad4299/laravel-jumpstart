<?php

namespace App\Classes\Export;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelExporter extends PHPSpreadsheetExporter {
    /**
     * @return string
     */
    function getOutputFileExtension() {
        return 'xlsx';
    }

    function save($absolutePath){
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($absolutePath);
    }
}