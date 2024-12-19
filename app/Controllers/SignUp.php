<?php

namespace App\Controllers;

use App\Dtos\CustomResponse;
use App\Helpers\ResponseHelper;
use App\Models\UserModel;

class SignUp extends BaseController
{
    public function form(): string
    {
        return view("pages/sign_up");
    }

    public function store()
    {
        //GET SIGNUP REQUEST BODY
        $form = json_decode($this->request->getBody(), true);
        $dto = new CustomResponse([], []);

        //SEPARETE THE FORM IN VARIABLES
        $name = $form["name"];
        $email = $form["email"];
        $password = $form["password"];

        //VERIFY IF THERE IS SOME MISSING FIELD
        if (!isset($name) || !isset($email) || !isset($password)) {
            $dto->errors[] = "Missing required fields";

            return ResponseHelper::send(400, $this->response, $dto);
        }

        //VERIFY IF THE EMAIL IS A VALID EMAIL
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $dto->errors[] = "Invalid email";

            return ResponseHelper::send(400, $this->response, $dto);
        }

        //VERIFY IF THE PASSWORD HAS THE MINIMUM LENGTH
        if (strlen($password) < 6) {
            $dto->errors[] = "Password must have at least 6 characters";

            return ResponseHelper::send(400, $this->response, $dto);
        }

        $user = model(UserModel::class);

        $isEmailInUse = $user->where("email", $email)->first();

        //VERIFY IF EMAIL IS IN USE
        if ($isEmailInUse) {
            $dto->errors[] = "Email already in use";

            return ResponseHelper::send(409, $this->response, $dto);
        }

        //CREATE USER WHILE ENCRYPT HIS PASSWORD AND GET THE CURRENT DATETIME
        $user->save([
            "name" => $name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_BCRYPT),
            "created_at" => date('Y-m-d H:i:s')
        ]);

        $dto->content[] = "User created successfully";

        return ResponseHelper::send(201, $this->response, $dto);
    }
}
