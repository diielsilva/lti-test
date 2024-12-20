<?php

namespace App\Controllers;

use App\Dtos\CustomResponse;
use App\Helpers\ResponseHelper;
use App\Models\UserModel;

class User extends BaseController
{
    public function update()
    {
        $form = json_decode($this->request->getBody(), true);
        $dto = new CustomResponse([], []);

        $name = $form["name"];
        $email = $form["email"];
        $password = $form["password"];

        //VERIFY IF THERE IS SOME MISSING FIELD
        if (!isset($name) || !isset($email) || !isset($password)) {
            $dto->errors[] = "Missing required fields";

            return ResponseHelper::send(400, $this->response, $dto);
        }

        //VERIFY IF THE EMAIL IS INVALID
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $dto->errors[] = "Invalid email";

            return ResponseHelper::send(400, $this->response, $dto);
        }

        //VERIFY IF PASSWORD LENGTH IS BELOW THE MINIMUM LENGTH
        if (strlen($password) < 6) {
            $dto->errors[] = "Password must have 6 characters";

            return ResponseHelper::send(400, $this->response, $dto);
        }

        $user = new UserModel();
        $onlineUser = session()->get("online_user");
        $hasUserWithNewEmail = $user->where("email", $email)->first();

        //VERIFY IF THE NEW EMAIL IS ALREADY IN USE BY ANOTHER USER
        if ($hasUserWithNewEmail && $hasUserWithNewEmail["id"] !== $onlineUser["id"]) {
            $dto->errors[] = "Email in use";

            return ResponseHelper::send(409, $this->response, $dto);
        }

        //UPDATE USER
        $user->save([
            "id" => $onlineUser["id"],
            "name" => $name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_BCRYPT),
            "updated_at" => date("")
        ]);

        //UPDATE SESSION AFTER A SUCCESSFUL USER UPDATE
        $onlineUser["name"] = $name;
        $onlineUser["email"] = $email;
        session()->set("online_user", $onlineUser);

        $dto->content[] = "User updated";

        return ResponseHelper::send(200, $this->response, $dto);
    }
}
