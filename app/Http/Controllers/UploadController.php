<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\storageRepository;
use App\Http\Middleware\RedirectIfNotAuthenticated;

class UploadController extends Controller
{
    /**
     * @var StorageRepository
     */
    protected $storageRepository;

    public function __construct(){
        $this->middleware(RedirectIfNotAuthenticated::class);
        $this->storageRepository = new StorageRepository();
    }

    public function upload(Request $request){
        $file = $request->file('file');
        $res = $this->storageRepository->uploadFiles($file);
        return response()->json($res);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function uploadBulk(Request $request)
    {
        $file_infos = json_decode($request->get('file_infos'),true);
        $resp = $this->storageRepository->bulkUpload($request->get('kind'), $file_infos, $request->allFiles());
        return response()->json($resp);
    }

    public function uploadBase64(Request $request){
        $path = $request->get('path');
        $data = $request->get('file');
        $res = $this->storageRepository->uploadFilesBase64($data);
        return response()->json($res);
    }
}
