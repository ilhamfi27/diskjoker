<?php

namespace App\Http\Controllers\Traits;

use JD\Cloudder\Facades\Cloudder;

/**
 * Trait for uploading files
 */
trait FileUpload
{
    public function saveFiles($file, $folder)
    {
        if (env('APP_ENV') == 'local' || env('APP_ENV') == 'testing') {
            $destinationPath = "/uploads/".$folder;
    
            $fileName = time()."_".$file->getClientOriginalName();
            $file->move(public_path($destinationPath), $fileName);
    
            return $destinationPath."/".$fileName;
        } else if (env('APP_ENV') == 'staging' || env('APP_ENV') == 'production'){
            Cloudder::upload($file, null, [
                'folder' => 'user_avatar'
            ]);
            return Cloudder::getResult()['url']; // to direct get image url from cloudinary
        }
    }
}
