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

        if ($imageStorage=='s3') {
            $imageUrl = Storage::disk($imageStorage)->temporaryUrl(
                $this->image,
                Carbon::now()->addMinutes(20)
            );
        }elseif (strpos($this->image, 'widgets/')!==false) {
            $imageUrl = url($this->image);
        }elseif (Storage::disk($imageStorage)->exists('public/avatars/'.$this->image)) {
            $imageUrl = asset('storage/avatars/'.$this->image);
        }else {
            $imageUrl = self::getDefaultImageUrl(false);
        }

        return $imageUrl;
    }

    public static function getDefaultImageUrl($string = false)
    {

        if ($string) {
            return Avatar::create($string)->toBase64();
        } else {
            return asset('widgets/images/missing.png');
        }
    }
}
