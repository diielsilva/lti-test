<?php

namespace App\Helpers;

use CodeIgniter\HTTP\Response;

class ResponseHelper
{
    public static function send(int $status, Response $response, mixed $body)
    {
        $response->setStatusCode($status);
        $response->setContentType("application/json; charset=utf-8");
        $response->setBody(json_encode($body));
        $response->send();
    }
}
