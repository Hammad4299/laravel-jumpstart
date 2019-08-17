<?php

namespace App\Receipt\ReceiptManagers;

use Illuminate\Support\Collection;
use App\Receipt\Builders\ReceiptBuilderContract;

interface ReceiptBuilderManagerContract {
    /**
     * @param ReceiptBuilderContract[] $receiptBuilders
     */
    function persist($receiptBuilders);


    /**
     * @param integer[]|Collection $ids
     * @return ReceiptBuilderContract
     */
    function getByRegistrableIds($ids);
    
    /**
     * @return ReceiptBuilderContract[]
     */
    function getReceiptBuilder();
}