<?php
/**
 * Created by PhpStorm.
 * User: talha
 * Date: 12/17/2016
 * Time: 4:07 PM
 */

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Classes\Helper;

trait ModelTrait
{
    public function scopeIn($q, $colum, $val) {
        if(is_array($val) || $val instanceOf Collection) {
            $q->whereIn($colum,$val);
        } else {
            $q->where($colum, $val);
        }
        return $q;
    }

    public function scopeNotIn($q, $colum, $val) {
        if(is_array($val) || $val instanceOf Collection) {
            $q->whereNotIn($colum,$val);
        } else {
            $q->where($colum, '!=', $val);
        }
        return $q;
    }

    public function isRelationLoaded($name){
        return isset($this->relations[$name]);
    }

    public function scopeQueryData($query, $options = [])
    {
        $d = $query;

        if(isset($options['order'])) {
            foreach ($options['order'] as $order) {
                $order = Helper::toDecoded($order);
                $col = $order['col'];
                $dir = $order['dir'];
                if($dir === 'asc' || $dir === 'desc') {
                    $d = $d->orderBy($col,$dir);
                }
            }
        }

        if (isset($options['limit'])) {
            $d = $query->limit($options['limit'])->get();
        } else if (isset($options['paginate'])) {
            $alias = 'page';
            if(isset($options['page_param']))
                $alias = $options['page_param'];

            $d = $query->paginate($options['paginate'],['*'],$alias);
        } else if (isset($options['simplepaginate'])) {
            $alias = 'page';
            if(isset($options['page_param']))
                $alias = $options['page_param'];

            $d = $query->simplepaginate($options['simplepaginate'],['*'],$alias);
        } else {
            $d = $query->get();
        }

        return $d;
    }

    /**
     * query scope nPerGroup
     *
     * @return void
     */
    public function scopeNPerGroup($query, $group, $n = 10,$table = null)
    {
        // queried table
        $table = $table == null ? ($this->getTable()) : $table;

        // initialize MySQL variables inline
        $query->from( DB::raw("(SELECT @rank:=0, @group:=0) as vars, {$table}") );

        // if no columns already selected, let's select *
        if ( ! $query->getQuery()->columns)
        {
            $query->select("{$table}.*");
        }

        // make sure column aliases are unique
        $groupAlias = 'group_'.md5(time());
        $rankAlias  = 'rank_'.md5(time());

        // apply mysql variables
        $query->addSelect(DB::raw(
            "@rank := IF(@group = {$group}, @rank+1, 1) as {$rankAlias}, @group := {$group} as {$groupAlias}"
        ));

        // make sure first order clause is the group order
        $query->getQuery()->orders = (array) $query->getQuery()->orders;
        array_unshift($query->getQuery()->orders, ['column' => $group, 'direction' => 'asc']);

        // prepare subquery
        $subQuery = $query->toSql();

        // prepare new main base Query\Builder
        $newBase = $this->newQuery()
            ->from(DB::raw("({$subQuery}) as {$table}"))
            ->mergeBindings($query->getQuery())
            ->where($rankAlias, '<=', $n)
            ->getQuery();

        // replace underlying builder to get rid of previous clauses
        $query->setQuery($newBase);
    }
}
