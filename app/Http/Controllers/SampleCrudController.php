<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\CreateRequest;
use App\Http\Requests\Sample\DeleteRequest;
use App\Http\Requests\Sample\GetRequest;
use App\Http\Requests\Sample\IndexRequest;
use App\Http\Requests\Sample\UpdateRequest;
use App\Repositories\BaseRepository;

class SampleCrudController extends Controller
{
    use TraitCrudController {
        create as traitCreate;
        update as traitUpdate;
        get as traitGet;
        delete as traitDelete;
        index as traitIndex;
    }

    /**
     * @var BaseRepository
     */
    protected $repo;

    public function __construct()
    {
        $this->repo = new BaseRepository();
    }

    public function index(IndexRequest $request)
    {
        return $this->traitIndex($request);
    }

    public function get(GetRequest $request)
    {
        return $this->traitGet($request);
    }

    public function delete(DeleteRequest $request)
    {
        return $this->traitDelete($request);
    }

    public function create(CreateRequest $request)
    {
        return $this->traitCreate($request);
    }

    public function update(UpdateRequest $request)
    {
        return $this->traitUpdate($request);
    }

    /**
     * @return BaseRepository
     */
    protected function getRepository() {
        return $this->repo;
    }
}
