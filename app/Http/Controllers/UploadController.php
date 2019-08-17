<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\StorageRepository;
use App\Http\Middleware\RedirectIfNotAuthenticated;

class UploadController extends Controller
{
    /**
     * @var StorageRepository
     */
    protected $StorageRepository;

    public function __construct(){
        $this->middleware(RedirectIfNotAuthenticated::class);
        $this->StorageRepository = new StorageRepository();
    }

    public function upload(Request $request){
        $file = $request->file('file');
        $res = $this->StorageRepository->uploadFiles($file);
        return response()->json($res);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function uploadBulk(Request $request)
    {
        $file_infos = json_decode($request->get('file_infos'),true);
        $resp = $this->StorageRepository->bulkUpload($request->get('kind'), $file_infos, $request->allFiles());
        return response()->json($resp);
    }

    public function uploadBase64(Request $request){
        $path = $request->get('path');
        $data = $request->get('file');
        $res = $this->StorageRepository->uploadFilesBase64($data);
        return response()->json($res);
    }
}
