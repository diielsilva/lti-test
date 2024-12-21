<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Dtos\CustomResponse;
use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        return view("pages/login/index");
    }

    public function authenticate()
    {
        $requestBody = $this->getRequestBody();
        $responseBody = new CustomResponse([], []);

        $email = $requestBody["email"];
        $password = $requestBody["password"];

        //Verifying if a required field is missing 
        if (!isset($email) || !isset($password)) {
            $responseBody->errors[] = "Missing required fields";

            return $this->json(400, $responseBody);
        }

        //Verifying if email is invalid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $responseBody->errors[] = "Invalid email";

            return $this->json(400, $responseBody);
        }

        //Verifying if password length is smaller than the minium length
        if (strlen($password) < 6) {
            $responseBody->errors[] = "Password must have at least 6 characters";

            return $this->json(400, $responseBody);
        }

        //Trying to find the user by his email
        $model = new UserModel();
        $user = $model->where("email", $email)->first();

        //Verifying if the received email is invalid
        if (!$user) {
            $responseBody->errors[] = "Email or password invalid";

            return $this->json(400, $responseBody);
        }

        //Verifying if the received password is invalid
        if (!password_verify($password, $user["password"])) {
            $responseBody->errors[] = "Email or password invalid";

            return $this->json(400, $responseBody);
        }

        //Destroying the password property, because we don't want to put it into the session
        unset($user["password"]);

        //Creating user session
        session()->set("online_user", $user);

        return $this->json(200, $responseBody);
    }
}
