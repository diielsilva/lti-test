<?php

namespace App\Controllers;

use App\Dtos\ResponseBody;
use App\Models\UserModel;

class Register extends BaseController
{
    public function index(): string
    {
        return view("pages/register/index");
    }

    public function create()
    {
        $request = $this->getRequestBody();
        $response = new ResponseBody([]);

        //Verifying if it is missing a required field
        if (empty($request["name"]) || empty($request["email"]) || empty($request["password"])) {
            $response->message = "Missing required fields";
            return $this->json(400, $response);
        }

        $name = $request["name"];
        $email = $request["email"];
        $password = $request["password"];

        //Verifying if email is invalid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response->message = "Invalid email";
            return $this->json(400, $response);
        }

        //Verifying if the password is below minimum length
        if (strlen($password) < 6) {
            $response->message = "Password must have at least 6 characters";
            return $this->json(400, $response);
        }

        $model = new UserModel();
        $hasUserWithEmail = $model->where("email", $email)->first();

        //Verifying if there is an user with same email
        if ($hasUserWithEmail) {
            $response->message = "Email already in use";
            return $this->json(409, $response);
        }

        //Creating user
        $model->save([
            "name" => $name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_BCRYPT),
            "created_at" => date('Y-m-d H:i:s')
        ]);

        $response->message = "User created successfully";
        return $this->json(201, $response);
    }
}
