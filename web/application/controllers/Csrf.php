<?php


class Csrf extends MY_Controller
{
    public function get_token ()
    {
        return $this->response_success(['token' => $this->security->get_csrf_hash()]);
    }
}