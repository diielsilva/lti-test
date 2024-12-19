<?php

namespace App\Controllers;

class SignUp extends BaseController
{
    public function form(): string
    {
        return view('pages/sign_up');
    }
}
