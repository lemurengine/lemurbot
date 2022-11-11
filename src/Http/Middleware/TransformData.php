<?php
namespace LemurEngine\LemurBot\Http\Middleware;

use Exception;
use Illuminate\Http\Request;
use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Models\BotAllowedSite;
use LemurEngine\LemurBot\Models\CategoryGroup;
use LemurEngine\LemurBot\Models\Client;
use LemurEngine\LemurBot\Models\ClientCategory;
use LemurEngine\LemurBot\Models\Conversation;
use LemurEngine\LemurBot\Models\EmptyResponse;
use LemurEngine\LemurBot\Models\Language;
use LemurEngine\LemurBot\Models\MachineLearntCategory;
use LemurEngine\LemurBot\Models\Map;
use LemurEngine\LemurBot\Models\Section;
use LemurEngine\LemurBot\Models\Set;
use LemurEngine\LemurBot\Models\Turn;
use LemurEngine\LemurBot\Models\WordSpelling;
use LemurEngine\LemurBot\Models\WordSpellingGroup;
use Closure;

use Symfony\Component\HttpFoundation\ParameterBag;

class TransformData
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->isJson()) {
            $this->clean($request->json());
        } else {
            $this->clean($request->request);
        }

        return $next($request);
    }

    /**
     * Clean the request's data by removing mask from phonenumber.
     *
     * @param ParameterBag $bag
     * @return void
     */
    private function clean(ParameterBag $bag)
    {
        $bag->replace($this->cleanData($bag->all()));
    }

    /**
     * Check the parameters and clean the number
     *
     * @param  array  $data
     * @return array
     */
    private function cleanData(array $data)
    {
        return collect($data)->map(function ($value, $key) {


            //if this is an array of value...
            //just return it
            if (is_array($value)) {
                return $value;
            }elseif($key == 'section_id' && empty($value) ){
                return $value;
            }

            if (substr($key, -3)=='_id') {
                $item = false;
                if ($key == 'bot_id') {
                    $item = Bot::where('slug', $value)->firstOrFail();
                } elseif ($key == 'section_id') {
                    $item = Section::where('slug', $value)->firstOrFail();
                } elseif ($key == 'bot_allowed_site_id') {
                    $item = BotAllowedSite::where('slug', $value)->firstOrFail();
                } elseif ($key == 'language_id') {
                    $item = Language::where('slug', $value)->firstOrFail();
                } elseif ($key == 'category_group_id' && !is_array($value)) {
                    $item = CategoryGroup::where('slug', $value)->firstOrFail();
                } elseif ($key == 'set_id') {
                    $item = Set::where('slug', $value)->firstOrFail();
                } elseif ($key == 'map_id') {
                    $item = Map::where('slug', $value)->firstOrFail();
                } elseif ($key == 'word_spelling_group_id') {
                    $item = WordSpellingGroup::where('slug', $value)->firstOrFail();
                } elseif ($key == 'word_spelling_id') {
                    $item = WordSpelling::where('slug', $value)->firstOrFail();
                } elseif ($key == 'client_id') {
                    $item = Client::where('slug', $value)->firstOrFail();
                } elseif ($key == 'conversation_id') {
                    $item = Conversation::where('slug', $value)->firstOrFail();
                } elseif ($key == 'conversation_id') {
                    $item = Conversation::where('slug', $value)->firstOrFail();
                } elseif ($key == 'turn_id') {
                    $item = Turn::where('slug', $value)->firstOrFail();
                } elseif ($key == 'empty_response_id') {
                    $item = EmptyResponse::where('slug', $value)->firstOrFail();
                } elseif ($key == 'client_category_id') {
                    $item = ClientCategory::where('slug', $value)->firstOrFail();
                } elseif ($key == 'machine_learnt_category_id') {
                    $item = MachineLearntCategory::where('slug', $value)->firstOrFail();
                }
                else {
                    if (!is_array($value)) {
                        throw new Exception('Unknown id item: ' . $key);
                    }
                }

                if ($item) {
                    return $item->id;
                }
            } else {
                return $value;
            }
        })->all();
    }
}
