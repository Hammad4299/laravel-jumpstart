<?php

namespace App\Trigger\TriggerType;

use App\Trigger\TriggerContext;
use App\Trigger\Models\VisitorTypeCheckIn;
use Illuminate\Support\Collection;
use App\Repositories\VisitorTypeRepository;
use App\Models\VisitorType;

class VisitorCheckIn implements TriggerTypeContract {
    /**
     * @var TriggerContext
     */
    protected $context;
    /**
     * @var VisitorTypeRepository
     */
    protected $repo;
    public function __construct($context)
    {
        $this->context = $context;
        $this->repo = new VisitorTypeRepository();
    }

    /**
     * Using $data, return triggered TriggerContract
     * @param mixed $data
     * @return array|Collection VisitorTypeCheckIn[]
     */
    // function getTriggered($data) {

    // }

    /**
     * @return VisitorTypeCheckIn[]
     */
    function getAvailableTriggers() {
        $col = null;
        $resp = $this->repo->index([
            'location_id'=>$this->context->getLocationId()
        ]);
        
        if($resp->getStatus()) {
            $col = $resp->data->map(function(VisitorType $item) {
                return new VisitorTypeCheckIn($item);
            });
        }
        
        return $col;
    }

    /**
     * @param number[] $dataIds
     * @return TriggerContract[]
     */
    function getByIds($dataIds) {
        $col = null;
        $resp = $this->repo->getByIds($dataIds);
        if($resp->getStatus()) {
            $col = $resp->data->map(function(VisitorType $item) {
                return new VisitorTypeCheckIn($item);
            });
        }
        return $col;
    }

    /**
     * Using $data, return triggered TriggerContract
     * @param VisitorType $data
     * @return TriggerContract[]|Collection
     */
    function getTriggered($data) {
        $collection = new Collection();
        $collection->push(new VisitorTypeCheckIn($data));
        return $collection;
    }

    /**
     * Persist data for corresponding triggers and fills registerable ids function
     *
     * @param Collection|VisitorCheckInForHost[] $triggers
     * @return void
     */
    function persist($triggers) {
        //No need to do anything since visitortype should already be in db.
    }

    /**
     * Delete data
     *
     * @param Collection|number[] $ids
     * @return void
     */
    function delete($ids) {
        //No need to do anything since visitortype isn't just being used for trigger purposes.
    }
}