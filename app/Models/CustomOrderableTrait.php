<?php
/**
 * Created by PhpStorm.
 * User: talha
 * Date: 12/17/2016
 * Time: 4:07 PM
 */

namespace App\Models;

use App\Classes\AppResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Can apply on model accessor (service)
 * Trait CustomOrderableTrait
 * @package App\Models
 */
trait CustomOrderableTrait
{
    protected function getNewOrder($data) {
        $m = $this->getModel();
        $q = $this->addOrderFilters($m::query(),$data);
        $order = $q->selectRaw('max('.$this->customOrderColumnName().') as cus_order')->first();
        $o = -1;
        if($order != null && $order->cus_order !== null) {
            $o = $order->cus_order;
        }

        return $o+1;
    }

    /**
     * @return string
     */
    protected abstract function customOrderColumnName();

    /**
     * return example User::class
     * @return mixed
     */
    public abstract function getModel();

    /**
     * @param $query
     * @param $currentModelData array|Model
     * @return Builder
     */
    protected abstract function addOrderFilters($query, $currentModelData);

    public function adjustOrder($data, $adjustBy, $equalOrHigherThan = null,$lessThanEqual = null){
        $m = $this->getModel();
        $q = $this->addOrderFilters($m::query(),$data);
        $colName = $this->customOrderColumnName();
        if($equalOrHigherThan !== null){
            $q = $q->where($colName,'>=',$equalOrHigherThan);
        }

        if($lessThanEqual !== null) {
            $q = $q->where($colName,'<=',$lessThanEqual);
        }

        $q->update([
            $colName=>DB::raw("$colName+$adjustBy")
        ]);
    }

    public function editOrder($id, $new_order){
        $m = $this->getModel();
        if(is_int($id)){
            $list = $m::where('id',$id)->first();
        } else {
            $list = $id;
        }

        $colName = $this->customOrderColumnName();
        $currentOrder = $list->{$colName};
        $q = $this->addOrderFilters($m::query(),$list);

        if($list!=null){
            if($new_order<$list->{$colName}) {
                $q->where($colName,'>=',$new_order)
                    ->update([
                        $colName=>DB::raw("$colName+1")
                    ]);
            } else {
                $q
                    ->where($colName,'<=',$new_order)
                    ->where($colName,'>',$currentOrder)
                    ->update([
                        $colName=>DB::raw("$colName-1")
                    ]);
            }
        }

        $list->{$colName} = $new_order;
        return new AppResponse(true,$list);
    }
}
