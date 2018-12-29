<?php

namespace App\Classes\Export;

use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ColumnValue{
    const TYPE_STRING = DataType::TYPE_STRING;
    const TYPE_INTEGER = DataType::TYPE_NUMERIC;
    const TYPE_DATE = 'date';
    public $value;
    public $type;
    public $raw;
    public $bold;

    public function __construct($value = '', $type = self::TYPE_STRING, $raw = null, $bold = false)
    {
        $this->value = $value;
        $this->type = $type;
        $this->bold = $bold;
        $this->raw = $raw;
    }
}