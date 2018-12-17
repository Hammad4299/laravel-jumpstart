<?php

$decodedAssets = null;

function assetUrl($assetName){
    global $decodedAssets;
    if($decodedAssets == null){
        $json = file_get_contents(public_path('/webpack-manifest.json'));
        $decodedAssets = json_decode($json,true);
    }

    $obj = \App\Classes\Helper::getKeyValue($decodedAssets,$assetName);
    return $obj;
}