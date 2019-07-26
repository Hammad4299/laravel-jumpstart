<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\UploadService;
use App\Http\Middleware\RedirectIfNotAuthenticated;

class UploadController extends Controller
{
    /**
     * @var UploadService
     */
    protected $uploadService;

    public function __construct(){
        $this->middleware(RedirectIfNotAuthenticated::class);
        $this->uploadService = new UploadService();
    }

    public function upload(Request $request){
        $file = $request->file('file');
        $res = $this->uploadService->uploadFiles($file);
        return response()->json($res);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function uploadBulk(Request $request)
    {
        $file_infos = json_decode($request->get('file_infos'),true);
        $resp = $this->uploadService->bulkUpload($request->get('kind'), $file_infos, $request->allFiles());
        return response()->json($resp);
    }

    public function uploadBase64(Request $request){
        $path = $request->get('path');
        $data = $request->get('file');
        $res = $this->uploadService->uploadFilesBase64($data);
        return response()->json($res);
    }
}
