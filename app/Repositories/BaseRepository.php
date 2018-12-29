<?php
/**
 * Created by PhpStorm.
 * User: talha
 * Date: 5/17/2018
 * Time: 3:46 PM
 */

namespace App\Repositories;

use App\Classes\Helper;
use App\Classes\AppResponse;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

/**
 * Supports basic Crud
 */
abstract class BaseRepository
{
    const ERROR_RESOURCE_NOT_FOUND = 'resource_not_found';

    /**
     * e.g. User::class
     *
     * @return void
     */
    public abstract function getModel();

    /**
     * @param mixed $key
     * @param mixed $data
     * @return string|mixed
     */
    protected function errorMessage($key, $data = null) {
        if($key === self::ERROR_RESOURCE_NOT_FOUND) {
            return 'Resource not found';
        }
    }

    protected function resourceErrorKey() {
        return Helper::RESOURCE_ERROR_KEY;
    }

    /**
     * @param string $purpose One of policy action names
     * @return Builder
     */
    public function getModelQueryBuilder($purpose = null, $queryOptions = null) {
        return $this->getModel()::query();
    }

    /**
     * @param array $filter
     * @param array $queryOptions Compatible with ModelTrait scopeQueryData
     * @param AuthorizableContract $user
     * @return AppResponse
     */
    public function index($filter = [], $queryOptions = [], $user = null) {
        $resp = new AppResponse(true);
        $q = $this->getModelQueryBuilder('index', $queryOptions);
        $data = $this->applyFilter($q, $filter)->queryData($queryOptions);
        $resp->data = $data;
        return $resp;
    }

    protected function applyFilter($query, $filter = []) {
        return $query->where($filter);
    }

    /**
     * @param $id
     * @param AuthorizableContract $user
     * @return AppResponse
     */
    public function get($id, $user = null) {
        $resp = new AppResponse(true);
        $model = $this->getModelQueryBuilder('get')->find($id);
        if(!$model) {
            $resp->addError($this->resourceErrorKey(),$this->errorMessage(self::ERROR_RESOURCE_NOT_FOUND, $id));
        } else {
            $resp->data = $model;
        }

        return $resp;
    }

    /**
     * @param $data
     * @param AuthorizableContract $user
     * @return AppResponse
     */
    public function create($data, $user = null) {
        $resp = new AppResponse(true);
        $resp->data = $this->getModel()::create($this->getCreationData($data));
        return $resp;
    }

    protected function getCreationData($data) {
        return $data;
    }

    /**
     * @param $id
     * @param $data
     * @param AuthorizableContract $user
     * @return AppResponse
     */
    public function update($id, $data, $user = null) {
        $resp = new AppResponse(true);
        $model = $this->getModelQueryBuilder('update')->find($id);
        if(!$model) {
            $resp->addError($this->resourceErrorKey(),$this->errorMessage(self::ERROR_RESOURCE_NOT_FOUND, $id));
        } else {
            $model->update($data);
        }

        $resp->data = $model;
        return $resp;
    }

    /**
     * @return Collection|null
     */
    protected function toCollection($data) {
        return Helper::toCollection($data);
    }

    /** 
     * @return AppResponse
     */
    protected function onModelsDeleting($ids, $user = null) {
        return new AppResponse(true);
    }

    /** 
     * @return AppResponse
     */
    protected function onModelDeleted($id, $model = null, $user = null) {
        $resp = new AppResponse(true);
        $resp->data = $model;
        return $resp;
    }

    /**
     * @param $id
     * @param AuthorizableContract $user
     * @return AppResponse
     */
    public function delete($id, $user = null) {
        $resp = new AppResponse(true);
        $model = $this->getModelQueryBuilder('delete')->find($id);
        if(!$model) {
            $resp->addError($this->resourceErrorKey(),$this->errorMessage(self::ERROR_RESOURCE_NOT_FOUND, $id));
        } else {
            $resp = $this->onModelsDeleting($id, $model);
            if($resp->getStatus()) {
                $model->delete();
                $resp = $this->onModelDeleted($id, $model);
            }
        }

        return $resp;
    }
}