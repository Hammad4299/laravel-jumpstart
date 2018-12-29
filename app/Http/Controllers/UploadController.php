<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UploadRepository;
use App\Http\Middleware\RedirectIfNotAuthenticated;


class UploadController extends Controller
{
    /**
     * @var UploadRepository
     */
    protected $uploadRepository;

    public function __construct(){
        $this->middleware(RedirectIfNotAuthenticated::class);
        $this->uploadRepository = new UploadRepository();
    }

    public function upload(Request $request){
        $path = $request->get('path');
        $file = $request->file('file');
        $res = $this->uploadRepository->uploadFiles($file,Auth::user(),$path);
        if($res->getStatus()){
            $res->data = (new Collection($res->data))->map(function($item){
                return $item['model'];
            });
        }
        return response()->json($res);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function uploadBulk(Request $request)
    {
        $file_infos = json_decode($request->get('file_infos'),true);
        $resp = $this->uploadRepository->bulkUpload($request->get('kind'), $file_infos, $request->allFiles(), Auth::user());
        return response()->json($resp);
    }

    public function uploadBase64(Request $request){
        $path = $request->get('path');
        $data = $request->get('file');
        $res = $this->uploadRepository->uploadFilesBase64($data,Auth::user(),$path);
        if($res->getStatus()){
            $res->data = (new Collection($res->data))->map(function($item){
                return $item['model'];
            });
        }
        return response()->json($res);
    }
}
