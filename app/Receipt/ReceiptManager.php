<?php

namespace App\Receipt;

use App\Models\Receipt;
use App\Receipt\ReceiptContext;
use Illuminate\Support\Collection;
use App\Repositories\ReceiptRepository;
use App\Receipt\Factory\ReceiptTypeFactory;
use App\Receipt\Builders\ReceiptBuilderContract;

class ReceiptManager  {
    /**
     * @var ReceiptContext
     */
    protected $context;
    /**
     * @var ReceiptRepository
     */
    protected $repo;
    /**
     * @var ReceiptTypeFactory
     */
    protected $factory;

    public function __construct(ReceiptContext $context)
    {
        $this->context = $context;
        $this->repo = new ReceiptRepository();
        $this->factory = new ReceiptTypeFactory();
    }

    /**
     * @return ReceiptBuilderContract[]
     */
    function getReceiptBuilderFor($types){
        $toRet = new Collection();
        foreach ($types as $type) {
            $toRet = $toRet->concat($this->factory->getReceiptBuilderManagerFor($type,$this->context)->getReceiptBuilder());
        }
        return $toRet;
    }

    /**
     * @return AppResponse (Receipt)
     */
    function persist($identification_info){
        $info = $identification_info;
        
        $type = $info['type'];
        
        $builder = $this->factory->getReceiptBuildersFor($type,$identification_info);
        $builderManager = $this->factory->getReceiptBuilderManagerFor($type,$this->context);
        $builderManager->persist([$builder]);

        $resp = $this->repo->create([
            'type'=>$type,
            'data_id'=>$builder->getRegistrableId()
        ]);
        return $resp;
    }


    /**
     * @param integer[] $ids
     * @return [$receipt_id=>ReceiptBuilderContract]
     */
    public function getReceiptBuilderForIds($ids) {
        /**
         * @var Collection $receipts
         */
        
        $receipts = $this->repo->getByIds($ids)->data;
        
        $groupedByType = $receipts->mapToGroups(function(Receipt $item) {
            return [$item->type => $item];
        });

        $toRet = [];
        foreach ($groupedByType as $type => $type_receipts) {
            $receiptBuilderManagerContract = $this->factory->getReceiptBuilderManagerFor($type, $this->context);
            $builders = $receiptBuilderManagerContract->getByRegistrableIds($type_receipts->pluck('data_id'));
            
            $dataIdtoReceiptIdsMap = $type_receipts->mapToGroups(function(Receipt $item) {
                return [$item->data_id=>$item->id];
            });

            foreach ($builders as $builder) {
                $receiptIds = $dataIdtoReceiptIdsMap[$builder->getRegistrableId()] ?? [];
                foreach ($receiptIds as $id) {
                    $toRet[$id] = $builder;
                }
            }
        }
        return $toRet;
    }
}