<?php

namespace App\Classes\Export;

use Carbon\Carbon;
use App\Classes\Helper;
use App\Storage\FileHandle;
use mikehaertl\wkhtmlto\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf;

class PDFExporter implements TableExporterContract {
    protected $header;
    protected $data;

    function init() {
        $this->header = null;
        $this->data = [];
    }

    /**
     * @param Collection|ColumnValue[] $cols
     * @return void
     */
    function addHeader($cols) {
        $this->header = $cols;
    }

    /**
     * @param Collection|ColumnValue[] $cols
     * @return void
     */
    function addRow($cols) {
        $toIns = [];
        foreach ($cols as $col) {
            if($col->type === ColumnValue::TYPE_DATE) {
                $col = clone $col;
                
                $col->value = Helper::unixTimestampToTimezone($col->raw,intval($col->value))
                    ->format('d/m/Y h:i:s A');
            }
            $toIns[] = $col;
        }
        $this->data[] = $toIns;
    }

    /**
     * @return string
     */
    function getOutputFileExtension() {
        return 'pdf';
    }

    function save($absolutePath){
        $html = View::make('exports.visitor-checkin',['header'=>$this->header,'data'=>$this->data])->render();
        $handle = new FileHandle(FileHandle::PROVIDER_DEFAULT,'exports/'.uniqid('pdf_export_tmp').'.html',true);
        $handle->saveContent($html);
        $pdf = new Pdf($handle->getAbsolutePath());
        
        if (!$pdf->saveAs($absolutePath)) {
            $error = $pdf->getError();
        }
    }
}
