<?php

use Model\Login_model;
use Model\User_model;

/**
 * Class User
 */
class User extends MY_Controller
{
    /**
     * @return object|string|void
     * @throws Exception
     */
    public function load()
    {
        if (User_model::is_logged()) {
            return $this->response_success([
                'user_data' => User_model::preparation(User_model::get_user(), 'full')
            ]);
        }

        return $this->response_error("User is not authorized");
    }

    /**
     * Method for login users in our system
     *
     * @return object|string|void
     */
    public function login()
    {
        $user_name = $this->input->post("email");
        $user_password = $this->input->post('password');

        if (!$user_password OR !$user_name) {
            return $this->response_error("Email or Password can't be empty");
        }

        try {
            $user = Login_model::login($user_name, $user_password);
        } catch (Exception $exception) {
            return $this->response_error($exception->getMessage());
        }

        return $this->response_success([
            'user_data' => User_model::preparation($user, 'full')
        ]);
    }

    public function logout()
    {
        if ( ! User_model::is_logged())
        {
            $this->go_back();
        }

        Login_model::logout();

        return $this->response_success();
    }
}