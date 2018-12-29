<?php

namespace App\Trigger;

use App\Classes\Helper;
use App\Models\Trigger;
use App\Classes\AppResponse;
use Illuminate\Support\Collection;
use App\Repositories\TriggerRepository;
use App\Trigger\Models\TriggerContract;
use App\Trigger\Factory\TriggerTypeFactory;

class TriggerManager {
    /**
     * @var TriggerContext
     */
    protected $context;
    /**
     * @var TriggerRepository
     */
    protected $repo;
    /**
     * @var TriggerTypeFactory
     */
    protected $factory;

    public function __construct(TriggerContext $context)
    {
        $this->context = $context;
        $this->repo = new TriggerRepository();
        $this->factory = new TriggerTypeFactory();
    }

    
    public function getTriggered($type, $data) {
        $typeContract = $this->factory->getForType($type);
        $triggerData = $typeContract->getTriggered($data);
        $triggerData = Helper::toCollection($triggerData);
        $registeredIds = $triggerData->map(function(TriggerContract $item){
            return $item->getRegisterableId();
        });
        $triggers = $this->repo->getByData($registeredIds, $type);
        return $triggers->data;
    }

    /**
     * @param string[] $types
     * @return Collection|TriggerContract[]
     */
    public function getAvailableTriggersForTypes($types) {
        $toRet = new Collection();
        foreach ($types as $type) {
            $toRet = $toRet->concat($this->factory->getForType($type,$this->context)->getAvailableTriggers());
        }
        return $toRet;
    }

    /**
     * @param integer[] $ids
     * @return [$id=>TriggerContract]
     */
    public function getTriggerDetailsForIds($ids) {
        /**
         * @var Collection $triggers
         */
        
        $triggers = $this->repo->getByIds($ids)->data;
        $groupedByType = $triggers->mapToGroups(function(Trigger $item) {
            return [$item->trigger_type => $item];
        });

        $dataIdtoTriggerIdsMap = $triggers->mapToGroups(function(Trigger $item) {
            return [$item->trigger_data_id=>$item->id];
        });

        
        $toRet = [];
        foreach ($groupedByType as $type => $triggers) {
            $typeContract = $this->factory->getForType($type,$this->context);
            $loadedTriggers = $typeContract->getByIds($triggers->pluck('trigger_data_id'));
            foreach ($loadedTriggers as $t) {
                $tids = $dataIdtoTriggerIdsMap[$t->getRegisterableId()];
                foreach ($tids as $tid) {
                    $toRet[$tid] = $t;
                }
            }
        }

        return $toRet;
    }

    /**
     * @param string $identification_info
     * @return AppResponse
     */
    public function persist($identification_info) {
        $info = json_decode($identification_info,true);
        $type = $info['type'];
        
        $contract = $this->factory->getTrigger($type,$identification_info);
        $triggerType = $this->factory->getForType($type,$this->context);
        $triggerType->persist($contract);

        $resp = $this->repo->create([
            'trigger_type'=>$type,
            'trigger_data_id'=>$contract->getRegisterableId()
        ]);
        return $resp;
    }

    public function deleteTriggers($ids) {
        $col = Helper::toCollection($ids);
        
        /**
         * @var Collection $triggers
         */
        $triggers = $this->repo->getByIds($ids)->data;
        $groupedByType = $triggers->mapToGroups(function(Trigger $item) {
            return [$item->trigger_type => $item];
        });

        //delete trigger data
        foreach ($groupedByType as $type => $triggers) {
            $typeContract = $this->factory->getForType($type,$this->context);
            $typeContract->delete($triggers->pluck('trigger_data_id'));
        }

        //now delete trigger itself.
        $this->simplyDeleteTriggers($ids);
    }

    protected function simplyDeleteTriggers($ids) {
        $this->repo->bulkDelete($ids);
    }

    public function deleteTriggerForVisitorType($ids) {
        $triggers = $this->repo->getByData($ids, TriggerType::VisitorTypeCheckin)->data;
        $ids = $triggers->pluck('id');
        $this->deleteTriggers($ids);
    }

    public function deleteTriggerForEmployees($ids) {
        $triggers = $this->repo->getByData($ids, TriggerType::CheckinToSeeHost)->data;
        $ids = $triggers->pluck('id');
        $this->deleteTriggers($ids);
    }
}