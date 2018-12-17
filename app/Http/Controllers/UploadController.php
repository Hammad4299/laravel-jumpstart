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
        parent::__construct();
        $this->middleware(RedirectIfNotAuthenticated::class);
        $this->uploadRepository = new UploadRepository();
    }

    public function upload(Request $request){
        $path = $request->get('path');
        $file = $request->file('file');
        $res = $this->uploadRepository->uploadFiles($file,Auth::user()->id,$path);
        if($res->getStatus()){
            $res->data = (new Collection($res->data))->map(function($item){
                return $item['model'];
            });
        }
        return response()->json($res);
    }

    public function uploadBase64(Request $request){
        $path = $request->get('path');
        $data = $request->get('file');
        $res = $this->uploadRepository->uploadFilesBase64($data,Auth::user()->id,$path);
        if($res->getStatus()){
            $res->data = (new Collection($res->data))->map(function($item){
                return $item['model'];
            });
        }
        return response()->json($res);
    }
}
