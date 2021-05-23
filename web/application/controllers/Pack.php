<?php


use Model\Boosterpack_model;
use Model\User_model;

class Pack extends MY_Controller
{
    /**
     * @return object|string|void
     */
    public function get_boosterpacks()
    {
        $posts =  Boosterpack_model::preparation_many(Boosterpack_model::get_all(), 'default');
        return $this->response_success(['boosterpacks' => $posts]);
    }

    /**
     * @return object|string|void
     */
    public function buy_boosterpack()
    {
        // Check user is authorize
        if ( ! User_model::is_logged())
        {
            return $this->response_error(System\Libraries\Core::RESPONSE_GENERIC_NEED_AUTH);
        }

        $booster_pack_id = (int)App::get_ci()->input->post('id');
        $booster_pack_model = new Boosterpack_model($booster_pack_id);
        $user_model = User_model::get_user();

        try {
            $booster_pack_model->is_loaded(TRUE);

            $booster_pack_logic = new \Model\Open_pack($user_model, $booster_pack_model);

            if (!$booster_pack_logic->validate()) {
                return $this->response_error("You haven't money for this pack");
            }

            if ($item = $booster_pack_logic->open()) {
                return $this->response_success([
                    'user_balance' => $user_model->get_wallet_balance(),
                    'user_likes' => $user_model->get_likes_balance(),
                    'likes_win' => $item->get_price()
                ]);
            }

        } catch (Exception $exception) {
            return $this->response_error($exception->getMessage());
        }
    }

    /**
     * @return object|string|void
     */
    public function get_boosterpack_info(int $bootserpack_info)
    {
        // Check user is authorize
        if ( ! User_model::is_logged())
        {
            return $this->response_error(System\Libraries\Core::RESPONSE_GENERIC_NEED_AUTH);
        }


        //TODO получить содержимое бустерпак
    }

}