<?php
/**
 * Created by PhpStorm.
 * User: talha
 * Date: 11/16/2017
 * Time: 6:32 PM
 */

namespace App\Classes;


/**
 * can be passed around for reusability e.g. I can add selection, pass it to grouping function which can group by week, month etc. That function can add its own selection as well as specify grouping criteria within same object
 */
class QueryBuilderHelper
{
    public $selection;
    public $selectionParam;
    public $grouping;

    public function __construct()
    {
        $this->grouping = [];
        $this->selection = [];
        $this->selectionParam = [];
    }

    /**
     * @param $columnName
     * @param $expression
     * Prevent multiple select clauses
     */
    public function addSelection($columnName, $expression){
        $this->selection[$columnName]  = $expression;
    }

    public function build($query){
        $expressions = [];
        foreach ($this->selection as $colName => $expression){
            $expressions[] = $expression;
        }

        $query->selectRaw(implode(',',$expressions),$this->selectionParam);
    }
}