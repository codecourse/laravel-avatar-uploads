<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\StoreAvatarFormRequest;
use App\Image;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;

class AvatarController extends Controller
{
    protected $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function store(StoreAvatarFormRequest $request)
    {
        $processedImage = $this->imageManager->make($request->file('image')->getPathName())
            ->fit(100, 100, function ($c) {
                $c->aspectRatio();
            })
            ->encode('png')
            ->save(config('image.path.absolute') . $path = '/' . uniqid(true) . '.png');

        $image = new Image;
        $image->path = $path;
        $image->user()->associate($request->user());
        $image->save();
        
        return response([
            'data' => [
                'id' => $image->id,
                'path' => $image->path(),
            ]
        ], 200);
    }
}
