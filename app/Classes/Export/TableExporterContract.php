<?php

namespace App\Classes\Export;

use Illuminate\Support\Collection;

interface TableExporterContract {
    function init();

    /**
     * @return string
     */
    function getOutputFileExtension();

    /**
     * @param Collection|ColumnValue[] $cols
     * @return void
     */
    function addHeader($cols);

    /**
     * @param Collection|ColumnValue[] $cols
     * @return void
     */
    function addRow($cols);

    /**
     * @param string $absolutePath
     */
    function save($absolutePath);
}