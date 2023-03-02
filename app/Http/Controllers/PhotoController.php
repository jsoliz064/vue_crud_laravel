<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function store(Request $request)
    {
        $photo = $request->input('photo');
        $photo = str_replace('data:image/png;base64,', '', $photo);
        $photo = str_replace(' ', '+', $photo);
        $photoData = base64_decode($photo);
        // $photoData=$this->resizeImage($photoData,300,300);

        // $file = new UploadedFile($photoData, 'filename.jpg');
        // Storage::put('photos/photo.jpg', $file->getContent());
        Storage::put('photos/photo.jpg', $photoData);

        return response()->json([
        'message' => 'Photo saved successfully'
        ]);
    }
    
    public function resizeImage($photoData,$width,$height){
        $img = Image::make($photoData);
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
    
        $imgData = (string) $img->encode();

        return $imgData;
    }
}
