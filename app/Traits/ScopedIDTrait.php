<?php
/**
 * Created by PhpStorm.
 * User: talha
 * Date: 12/17/2016
 * Time: 4:07 PM
 */

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Can apply on model accessor (service)
 * Trait CustomOrderableTrait
 * @package App\Models
 */
trait ScopedIDTrait
{
    /**
     * @param array|mixed $data Passed to filters function. Pass any data required for filtering in it.
     * @return integer
     */
    public function getNewID($data) {
        $m = $this->getModel();
        $q = $this->addCustomIDFilters($m::query(),$data);
        $order = $q->selectRaw('max('.$this->customIDColumnName().') as max_cus_id')->first();
        $o = 0;
        if($order != null && $order->max_cus_id !== null) {
            $o = $order->max_cus_id;
        }

        return $o+1;
    }

    /**
     * @return string
     */
    protected function customIDColumnName() {
        return 'custom_id';
    }

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
    protected abstract function addCustomIDFilters($query, $currentModelData);
}