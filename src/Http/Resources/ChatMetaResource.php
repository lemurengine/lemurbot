<?php

namespace LemurEngine\LemurBot\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMetaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {


        if (!empty($this->lemurtar_url)) {
            $lemurtarFields = ['width'=>'width',
                'height'=>'height',
                'avatarStyle'=>'style',
                'background'=>'background',
                'svgBackground'=>'svgBackground',
                'skinColor'=>'skin',
                'topType'=>'top',
                'hairColor'=>'hairColor',
                'hatColor'=>'hatColor',
                'accessoriesType'=>'accessories',
                'accessoriesColor'=>'accessoriesColor',
                'facialHairType'=>'facialHair',
                'facialHairColor'=>'facialHairColor',
                'clotheType'=>'clothing',
                'graphicType'=>'clothingGraphic',
                'clotheColor'=>'clothingColor',
                'eyesType'=>'eyes',
                'eyebrowType'=>'eyebrows',
                'mouthType'=> 'mouth'];

            $queryStr = parse_url($this->lemurtar_url, PHP_URL_QUERY);
            parse_str($queryStr, $queryParams);
            $cleanFields=[];

            foreach ($lemurtarFields as $urlField => $objField) {
                if (!empty($queryParams[$urlField])) {
                    $cleanFields[$objField]=lcfirst($queryParams[$urlField]);
                }
                $cleanFields['style']='transparent';
            }

        } else {
            $cleanFields['style']='transparent';
        }
        $additionalBotProperties = $this->lemurTarAdditionalProperties;

        if(!empty($additionalBotProperties)){
            foreach($additionalBotProperties as $additionalBotProperty){
                $cleanFields[$additionalBotProperty->name]=$additionalBotProperty->value;
            }
        }


        return [
            'bot' => [
                'id'=> $this->slug,
                'name'=> $this->name,
                'image'=> $this->imageUrl,
                'lemurtar' => $cleanFields,
            ],
            'client' => [
                'id' => $this->clientId,
                'image'=> url(config('lemurbot.portal.default_client_image'))
            ],
            'conversation' => [
                'id' => $this->conversationId
            ]
        ];
    }
}
