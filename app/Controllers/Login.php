<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Dtos\ResponseBody;
use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        return view("pages/login/index");
    }

    public function authenticate()
    {
        $request = $this->getRequestBody();
        $response = new ResponseBody([]);

        //Verifying if a required field is missing 
        if (empty($request["email"]) || empty($request["password"])) {
            $response->message = "Missing required fields";
            return $this->json(400, $response);
        }

        $email = $request["email"];
        $password = $request["password"];

        //Verifying if email is invalid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response->message = "Invalid email";
            return $this->json(400, $response);
        }

        //Verifying if password length is smaller than the minium length
        if (strlen($password) < 6) {
            $response->message = "Password must have at least 6 characters";
            return $this->json(400, $response);
        }

        //Trying to find the user by his email
        $model = new UserModel();
        $user = $model->where("email", $email)->first();

        //Verifying was not found by his email
        if (!$user) {
            $response->message = "Email or password invalid";
            return $this->json(400, $response);
        }

        //Verifying if the received password is invalid
        if (!password_verify($password, $user["password"])) {
            $response->message = "Email or password invalid";
            return $this->json(400, $response);
        }

        //Destroying the password property, because we don't want to put it into the session
        unset($user["password"]);

        //Creating user session
        session()->set("online_user", $user);

        return $this->json(200, $response);
    }
}
