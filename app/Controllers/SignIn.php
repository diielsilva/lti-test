<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Dtos\CustomResponse;
use App\Helpers\ResponseHelper;
use App\Models\UserModel;

class SignIn extends BaseController
{
    public function index()
    {
        return view("pages/sign_in");
    }

    public function home()
    {
        return view("pages/home");
    }

    public function authenticate()
    {

        $form = json_decode($this->request->getBody(), true);
        $dto = new CustomResponse([], []);

        $email = $form["email"];
        $password = $form["password"];

        //VERIFY IF THERE IS SOME MISSING FIELD
        if (!isset($email) || !isset($password)) {
            $dto->errors[] = "Missing required fields";

            return ResponseHelper::send(400, $this->response, $dto);
        }

        //VERIFY IF THE EMAIL IS INVALID
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $dto->errors[] = "Invalid email";

            return ResponseHelper::send(400, $this->response, $dto);
        }

        //VERIFY IF PASSWORD DOES NOT HAVE THE MINIMUM LENGTH
        if (strlen($password) < 6) {
            $dto->errors[] = "Password must have at least 6 characters";

            return ResponseHelper::send(400, $this->response, $dto);
        }

        $model = new UserModel();
        $user = $model->where("email", $email)->first();

        //VERIFY IF THE USER EMAIL DOES NOT EXIST
        if ($user === null) {
            $dto->errors[] = "Email or password invalid";

            return ResponseHelper::send(400, $this->response, $dto);
        }

        //VERIFY IF THE PASSWORD IS INCORRECT
        if (!password_verify($password, $user["password"])) {
            $dto->errors[] = "Email or password invalid";

            return ResponseHelper::send(400, $this->response, $dto);
        }

        unset($user["password"]);

        session()->set("online_user", $user);

        return ResponseHelper::send(200, $this->response, $dto);
    }
}
