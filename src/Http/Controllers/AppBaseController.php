<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use Response;

/**
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        return Response::json(self::makeResponse($message, $result));
    }

    public function extractSendError($e)
    {
        $shortName = class_basename(get_class($e));

        switch ($shortName) {
            case 'AuthorizationException':
                return $this->sendError($e->getMessage(), '403');
                break;
            case 'ModelNotFoundException':
                return $this->sendError($e->getMessage(), '404');
                break;
            default:
                return $this->sendError($shortName.": ".$e->getMessage(), '500');
        }
    }

    public function sendError($error, $code = 404)
    {
        return Response::json(self::makeError($error), $code);
    }

    public function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message
        ], 200);
    }

    /**
     * @param string $message
     * @param mixed  $data
     *
     * @return array
     */
    public static function makeResponse($message, $data)
    {
        return [
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ];
    }

    /**
     * @param string $message
     * @param array  $data
     *
     * @return array
     */
    public static function makeError($message, array $data = [])
    {
        $res = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $res['data'] = $data;
        }

        return $res;
    }
}
