<?php

namespace App\Trigger\TriggerType;

use App\Trigger\Models\TriggerContract;

interface TriggerTypeContract {
    /**
     * Using $data, return triggered TriggerContract e.g. based on $data return triggers that are triggered due to event info represented by data
     * @param mixed $data
     * @return TriggerContract[]|Collection
     */
    function getTriggered($data);

    /**
     * Allows user to select a trigger for a action
     * @return TriggerContract[]
     */
    function getAvailableTriggers();

    /**
     * @param number[] $dataIds
     * @return TriggerContract[]
     */
    function getByIds($dataIds);

    /**
     * Persist data for corresponding triggers and fills registerable ids function
     *
     * @param Collection|TriggerContract[] $triggers
     * @return void
     */
    function persist($triggers);

    /**
     * Delete data
     *
     * @param Collection|number[] $ids
     * @return void
     */
    function delete($ids);
}