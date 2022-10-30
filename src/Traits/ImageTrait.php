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
        $imageStorage = config('filesystems.default');


        if (strpos($this->image, 'widgets/')!==false) { //if this is a widget image
            $imageUrl = url($this->image);
        }elseif ($imageStorage=='s3') { //if we are getting the details from s3
            $imageUrl = Storage::disk($imageStorage)->temporaryUrl(
                $this->image,
                Carbon::now()->addMinutes(20)
            );
        }elseif (Storage::disk($imageStorage)->exists('public/avatars/'.$this->image)) { //if this is a local image
            $imageUrl = asset('storage/avatars/'.$this->image);
        }else {
            $imageUrl = self::getDefaultImageUrl(false); //get the default image
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
