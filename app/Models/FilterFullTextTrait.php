<?php
/**
 * Created by PhpStorm.
 * User: talha
 * Date: 12/17/2016
 * Time: 4:07 PM
 */

namespace App\Models;

trait FilterFullTextTrait
{
    abstract protected function fullTextFilterColumns($queryType = null);

    public function scopeFilterByFullText($query, $term, $queryType = null){
        $term = preg_replace('/[^\p{L}\p{N}_]+/u', ' ', $term);
        $words = explode(" ", $term);

        $match = "";
        foreach ($words as $word) {
            if(!empty($word))
                $match = $match.$word."* ";
        }

        $cols = $this->fullTextFilterColumns($queryType);
        $query->whereRaw("match($cols) against (? in boolean mode)",[$match]);

        return $query;
    }
}
