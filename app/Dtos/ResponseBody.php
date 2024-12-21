<?php

namespace App\Dtos;

class ResponseBody
{
    public string $message;
    public mixed $content;

    public function __construct( mixed $content, string $message = "",)
    {
        $this->message = $message;
        $this->content = $content;
    }
}
