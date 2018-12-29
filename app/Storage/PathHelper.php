<?php

namespace App\Storage;

class PathHelper {
    const FOLDER_UPLOADS = 'uploads';
    
    public static function getPath($base, $fileName) {
        if(!empty($base)) {
            if($base[strlen($base)-1]!=='/')
                $base .= "/";
        }

        return $base.$fileName;
    }
    public static function random($extension, $base = '') {
        if($extension[0]!=='.')
            $extension = '.'.$extension;

        $fileName = uniqid().$extension;
        return self::getPath($base,$fileName);
    }

    public static function changeExtension($pre, $extension) {
        $path_parts = pathinfo($pre);
        $toRet = '';
        if(isset($path_parts['dirname']) && $path_parts['dirname']!=='.')
            $toRet .= $path_parts['dirname'].'/';
        if(isset($path_parts['filename']))
            $toRet .= $path_parts['filename'];
        $toRet .= '.';
        $toRet .= $extension;
        return $toRet;
    }

    public static function randomWithSourceExtension($sourceName, $base = '', $defaultExtension = '') {
        $extension = '.'.pathinfo($sourceName,PATHINFO_EXTENSION);
        if($extension === '.')
            $extension .= $defaultExtension;
        $fileName = uniqid().$extension;
        return self::getPath($base,$fileName);
    }
}