<?php

namespace App\Http\Controllers\Traits;

/**
 * Trait for uploading files
 */
trait FileUpload
{
    public function saveFiles($file, $folder)
    {
        $destinationPath = "/uploads/".$folder;

        $fileName = time()."_".$file->getClientOriginalName();
        $file->move(public_path($destinationPath), $fileName);

        return $destinationPath."/".$fileName;
    }
}
