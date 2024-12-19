<?php

namespace App\Dtos;

class CustomResponse
{
    public array $errors;
    public mixed $content;

    public function __construct(array $errors, mixed $content)
    {
        $this->errors = $errors;
        $this->content = $content;
    }
}
