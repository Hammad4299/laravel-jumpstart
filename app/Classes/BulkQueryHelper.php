<?php

namespace App\Classes;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

class BulkQueryHelper
{
    /**
     * @var \Illuminate\Database\Query\Builder
     */
    protected $tableName;
    protected $tablePrefix;

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
        $this->tablePrefix = '';
    }

    /**
     * Insert using mysql ON DUPLICATE KEY UPDATE.
     * @link http://dev.mysql.com/doc/refman/5.7/en/insert-on-duplicate.html
     *
     * Example:  $data = [
     *     ['id' => 1, 'name' => 'John'],
     *     ['id' => 2, 'name' => 'Mike'],
     * ];
     * 
     * $updateColumns = ['name','id'] update name and id based on their insert value
     * $updateColumns = ['name'=>'sd','id'] update name to 'sq' but id from insert value
     * @param array $data is an array of array.
     * @param array $updateColumns NULL means update all columns based on insert values
     *
     * @return bool
     */
    public function onDuplicateKeyUpdate(array $data, array $updateColumns = null)
    {
        if (empty($data)) {
            return false;
        }
        // Case where $data is not an array of arrays.
        if (!isset($data[0])) {
            $data = [$data];
        }
        $sql = $this->buildInsertOnDuplicateSql($data, $updateColumns);
        $data = $this->inLineArray($data);
        $additionalDataToBind = [];
        if($updateColumns!==null) {
            foreach ($updateColumns as $key => $value) {
                if(!is_numeric($key) && !($value instanceof Expression)) {
                    $additionalDataToBind[] = $value;
                }
            }
        }
        
        return DB::insert(DB::raw($sql), array_merge($data,$additionalDataToBind));
    }

    /**
     * Insert using mysql INSERT IGNORE INTO.
     *
     * @param array $data
     *
     * @return bool
     */
    public function insertIgnore(array $data)
    {
        if (empty($data)) {
            return false;
        }
        // Case where $data is not an array of arrays.
        if (!isset($data[0])) {
            $data = [$data];
        }
        $sql = $this->buildInsertIgnoreSql($data);
        $data = $this->inLineArray($data);
        return DB::insert(DB::raw($sql), $data);
    }

    /**
     * Insert using mysql REPLACE INTO.
     *
     * @param array $data
     *
     * @return bool
     */
    public function replace(array $data)
    {
        if (empty($data)) {
            return false;
        }
        // Case where $data is not an array of arrays.
        if (!isset($data[0])) {
            $data = [$data];
        }
        $sql = $this->buildReplaceSql($data);
        $data = $this->inLineArray($data);
        return DB::insert(DB::raw($sql), $data);
    }
    /**
     * Static function for getting table name.
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Get the table prefix.
     *
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }

    /**
     * Build the question mark placeholder.  Helper function for insertOnDuplicateKeyUpdate().
     * Helper function for insertOnDuplicateKeyUpdate().
     *
     * @param $data
     *
     * @return string
     */
    protected static function buildQuestionMarks($data)
    {
        $lines = [];
        foreach ($data as $row) {
            $count = count($row);
            $questions = [];
            for ($i = 0; $i < $count; ++$i) {
                $questions[] = '?';
            }
            $lines[] = '(' . implode(',', $questions) . ')';
        }
        return implode(', ', $lines);
    }
    /**
     * Get the first row of the $data array.
     *
     * @param array $data
     *
     * @return mixed
     */
    protected static function getFirstRow(array $data)
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Empty data.');
        }
        list($first) = $data;
        if (!is_array($first)) {
            throw new \InvalidArgumentException('$data is not an array of array.');
        }
        return $first;
    }
    /**
     * Build a value list.
     *
     * @param array $first
     *
     * @return string
     */
    protected static function getColumnList(array $first)
    {
        if (empty($first)) {
            throw new \InvalidArgumentException('Empty array.');
        }
        return '`' . implode('`,`', array_keys($first)) . '`';
    }
    /**
     * Build a value list.
     *
     * @param array $updatedColumns
     *
     * @return string
     */
    protected static function buildValuesList(array $updatedColumns)
    {
        $out = [];
        foreach ($updatedColumns as $key => $value) {
            if (is_numeric($key)) {
                $out[] = sprintf('`%s` = VALUES(`%s`)', $value, $value);
            } else {
                $v = $value;
                if(!($v instanceof Expression)){
                    $v = '?';
                }
                $out[] = sprintf('`%s` = %s', $key, $v);
            }
        }
        return implode(', ', $out);
    }
    /**
     * Inline a multiple dimensions array.
     *
     * @param $data
     *
     * @return array
     */
    protected static function inLineArray(array $data)
    {
        return call_user_func_array('array_merge', array_map('array_values', $data));
    }
    /**
     * Build the INSERT ON DUPLICATE KEY sql statement.
     *
     * @param array $data
     * @param array $updateColumns
     *
     * @return string
     */
    protected function buildInsertOnDuplicateSql(array $data, array $updateColumns = null)
    {
        $first = static::getFirstRow($data);
        $sql  = 'INSERT INTO `' . $this->getTablePrefix() . $this->getTableName() . '`(' . static::getColumnList($first) . ') VALUES' . PHP_EOL;
        $sql .=  static::buildQuestionMarks($data) . PHP_EOL;
        $sql .= 'ON DUPLICATE KEY UPDATE ';
        if (empty($updateColumns)) {
            $sql .= static::buildValuesList(array_keys($first));
        } else {
            $sql .= static::buildValuesList($updateColumns);
        }
        return $sql;
    }
    /**
     * Build the INSERT IGNORE sql statement.
     *
     * @param array $data
     *
     * @return string
     */
    protected function buildInsertIgnoreSql(array $data)
    {
        $first = static::getFirstRow($data);
        $sql  = 'INSERT IGNORE INTO `' . $this->getTablePrefix() . $this->getTableName() . '`(' . static::getColumnList($first) . ') VALUES' . PHP_EOL;
        $sql .=  static::buildQuestionMarks($data);
        return $sql;
    }
    /**
     * Build REPLACE sql statement.
     *
     * @param array $data
     *
     * @return string
     */
    protected function buildReplaceSql(array $data)
    {
        $first = static::getFirstRow($data);
        $sql  = 'REPLACE INTO `' . $this->getTablePrefix() . $this->getTableName() . '`(' . static::getColumnList($first) . ') VALUES' . PHP_EOL;
        $sql .=  static::buildQuestionMarks($data);
        return $sql;
    }
}