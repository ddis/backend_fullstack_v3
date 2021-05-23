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
        $user_name = App::get_ci()->input->post("email");
        $user_password = App::get_ci()->input->post('password');

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

    /**
     * @return object|string|void
     * @throws Throwable
     */
    public function add_money(){
        if ( ! User_model::is_logged())
        {
            return $this->response_error(System\Libraries\Core::RESPONSE_GENERIC_NEED_AUTH);
        }

        $sum = (float)App::get_ci()->input->post('sum');

        try {
            $user_model = User_model::get_user();

            $new_balance = $user_model->add_money($sum);

            return $this->response_success(['new_balance' => $new_balance]);
        } catch (Exception $exception) {
            return $this->response_error($exception->getMessage(), [], $exception->getCode());
        }

    }
}