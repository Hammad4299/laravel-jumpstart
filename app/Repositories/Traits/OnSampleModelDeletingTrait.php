<?php
namespace App\Repositories\Traits;

use App\Classes\AppResponse;

trait OnSampleModelDeletingTrait {
    public function sampleModelDeleting($ids, $user = null) {
        $resp = new AppResponse(true);
        $ids = $this->getModel()::in($this->badgeForeignKeyColumnName(),$ids)->pluck($this->badgePrimaryKeyName());
        if(count($ids)>0) {
            $this->onModelsDeleting($ids, $user);
            $this->getModel()::whereIn($this->badgePrimaryKeyName(),$ids)->delete();
        }
        return $resp;
    }

    /**
     * e.g. User::class
     *
     * @return void
     */
    public abstract function getModel();

    protected function sampleModelPrimaryKeyName() {
        return 'id';
    }

    protected function sampleModelForeignKeyColumnName() {
        return 'badge_id';
    }

    abstract function onModelsDeleting($id, $user = null);
}