<?php

namespace App\Http\Controllers;

use App\Classes\AppResponse;
use App\Interfaces\ICreateRequest;
use App\Interfaces\IDeleteRequest;
use App\Interfaces\IGetRequest;
use App\Interfaces\IIndexRequest;
use App\Interfaces\IUpdateRequest;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait TraitCrudController
{    
    /**
     * @return BaseRepository
     */
    protected abstract function getRepository();

    /**
     * @param Request $request
     * @param AppResponse|mixed $response
     * @return \Illuminate\Http\Response
     */
    protected function deleteResponse(Request $request, $response) {
        return response()->json($response);
    }

    /**
     * @param Request $request
     * @param AppResponse|mixed $response
     * @return \Illuminate\Http\Response
     */
    protected function indexResponse(Request $request, $response) {
        return response()->json($response);
    }

    /**
     * @param Request $request
     * @param AppResponse|mixed $response
     * @return \Illuminate\Http\Response
     */
    protected function getResponse(Request $request, $response) {
        return response()->json($response);
    }

    /**
     * @param Request $request
     * @param AppResponse|mixed $response
     * @return \Illuminate\Http\Response
     */
    protected function createResponse(Request $request, $response) {
        return response()->json($response);
    }

    /**
     * @param Request $request
     * @param AppResponse|mixed $response
     * @return \Illuminate\Http\Response
     */
    protected function updateResponse(Request $request, $response) {
        return response()->json($response);
    }

    public function delete(IDeleteRequest $request) {
        $user = Auth::user();
        return $this->deleteResponse($request,$this->getRepository()->delete($request->getId(), $user));
    }


    public function index(IIndexRequest $request) {
        $user = Auth::user();
        return $this->indexResponse($request,$this->getRepository()->index($request->getFilters(),$request->getOptions(), $user));
    }

    public function get(IGetRequest $request) {
        $user = Auth::user();
        return $this->getResponse($request,$this->getRepository()->get($request->getId(),$user));
    }

    public function update(IUpdateRequest $request) {
        $user = Auth::user();
        return $this->updateResponse($request,$this->getRepository()->update($request->getId(), $request->getData(), $user));
    }

    public function create(ICreateRequest $request) {
        $user = Auth::user();
        return $this->createResponse($request,$this->getRepository()->create($request->getData(), $user));
    }
}
