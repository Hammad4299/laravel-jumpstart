<?php

namespace App\Trigger\TriggerType;

use App\Models\Employee;
use App\Trigger\TriggerContext;
use Illuminate\Support\Collection;
use App\Trigger\Models\VisitorCheckInForHost;
use App\Repositories\EmployeeRepository;
use App\Repositories\TriggerRepository;

class CheckInForHost implements TriggerTypeContract {
    /**
     * @var TriggerContext
     */
    protected $context;
    /**
     * @var EmployeeRepository
     */
    protected $repo;
    public function __construct($context)
    {
        $this->context = $context;
        $this->repo = new EmployeeRepository();
    }

    /**
     * Using $data, return triggered TriggerContract
     * @param mixed $data
     * @return array|Collection VisitorCheckInForHost[]
     */
    // function getTriggered($data) {

    // }

    /**
     * @return VisitorCheckInForHost[]
     */
    function getAvailableTriggers() {
        $col = null;
        $resp = $this->repo->index([
            'location_id'=>$this->context->getLocationId()
        ]);
        if($resp->getStatus()) {
            $col = $resp->data->map(function(Employee $item) {
                return new VisitorCheckInForHost($item);
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
            $col = $resp->data->map(function(Employee $item) {
                return new VisitorCheckInForHost($item);
            });
        }
        return $col;
    }

    /**
     * Using $data, return triggered TriggerContract
     * @param Employee $data
     * @return TriggerContract[]|Collection
     */
    function getTriggered($data) {
        $collection = new Collection();
        $collection->push(new VisitorCheckInForHost($data));
        return $collection;
    }

    /**
     * Persist data for corresponding triggers and fills registerable ids function
     *
     * @param Collection|VisitorCheckInForHost[] $triggers
     * @return void
     */
    function persist($triggers) {
        //No need to do anything since host/employee should already be in db so no additional data needs to persist. it suffices to persist host id as registrable id
    }

    /**
     * Delete data
     *
     * @param Collection|number[] $ids
     * @return void
     */
    function delete($ids) {
        //No need to do anything since host/employee isn't just being used for trigger purposes.
    }
}