<?php

namespace App\Classes\Export;

use App\Classes\Helper;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class PHPSpreadsheetExporter implements TableExporterContract {
    protected $spreadsheet;
    /**
     * @var Worksheet
     */
    protected $sheet;
    protected $row = 2; // Start adding data from 2nd row since first is header row

    function init(){
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    function addHeader($cols)
    {
        $this->setRow(1,$cols);
    }

    /**
     * @param integer $rowNum
     * @param ColumnValue[] $rowData
     * @return void
     */
    protected function setRow($rowNum, $rowData) {
        $col = 'A';
        foreach($rowData as $key=>$value) {
            $cellValue = $value->value;
            $cell = $col . $rowNum;
            $style = $this->sheet->getStyle($cell);
            if($value->type == ColumnValue::TYPE_DATE) {
                
                // Get the current date/time and convert to an Excel date/time
                $dateTimeNow = $value->raw + intval($value->value*60);   //adding $value to convert to appropiate timezone. Otherwise it isn't clear how to tell spreadsheet to format time in correct timezone from stamp.
                
                $cellValue = Date::PHPToExcel($dateTimeNow);
                
                // Set the number format mask so that the excel timestamp will be displayed as a human-readable date/time
                $style->getNumberFormat()
                    ->setFormatCode(
                        NumberFormat::FORMAT_DATE_DATETIME
                    );
            }

            $styleArray = [];
            if(isset($value->bold) && $value->bold) {
                $styleArray['font'] = [
                    'bold' => true,
                ];
            }

            $style->applyFromArray($styleArray);
            $this->sheet->getColumnDimension($col)->setAutoSize(true);
            
            $this->sheet->setCellValue($cell, $cellValue);
            $col++;
        }
    }

    function addRow($row_data){
        $this->setRow($this->row,$row_data);
        $this->row++;
    }
}