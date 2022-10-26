<?php

namespace LemurEngine\LemurBot\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Laravolt\Avatar\Facade as Avatar;

trait ImageTrait
{

    public function getImageUrlAttribute()
    {

        //how are the images for this model stored?
        $imageStorage = env('PUBLIC_STORAGE_ADAPTER', 'local');

        if (Storage::disk($imageStorage)->exists($this->image)) {
            if ($imageStorage=='s3') {
                $imageUrl = Storage::disk($imageStorage)->temporaryUrl(
                    $this->image,
                    Carbon::now()->addMinutes(20)
                );
            } else {

                $imageUrl = asset('avatars/'.$this->image);
            }
        } else {
            if (strpos($this->image, 'widgets/')!==false) {
                $imageUrl = url($this->image);
            } else {
                $imageUrl = self::getDefaultImageUrl(false);
            }
        }

        return $imageUrl;
    }

    public static function getDefaultImageUrl($string = false)
    {

        if ($string) {
            return Avatar::create($string)->toBase64();
        } else {
            return asset('avatars/missing.png');
        }
    }
}
