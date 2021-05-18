<?php

namespace Model\traits;

use App;
use Exception;
use Model\User_model;

trait Add_likes
{
    public static function add_like($id)
    {
        $post = new self($id);
        $post->is_loaded(TRUE);

        $user = User_model::get_user();

        if ($user->get_likes_balance() === 0) {
            throw new Exception("User haven't likes");
        }

        App::get_s()->set_transaction_repeatable_read()->execute();
        App::get_s()->start_trans()->execute();

        try {
            $post->set_likes($post->get_likes() + 1);
            $is_affected_comment = App::get_s()->is_affected();

            $user->set_likes_balance($user->get_likes_balance() - 1);
            $is_affected_user = App::get_s()->is_affected();

            if ($is_affected_comment && $is_affected_user)
            {
                App::get_s()->commit()->execute();
            } else
            {
                App::get_s()->rollback()->execute();
                return FALSE;
            }

            return $post->reload()->get_likes();

        } catch (\Throwable $exception) {
            App::get_s()->rollback()->execute();
            return FALSE;
        }
    }
}