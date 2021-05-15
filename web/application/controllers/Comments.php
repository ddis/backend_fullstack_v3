<?php


use Model\Comment_model;
use Model\User_model;

class Comments extends MY_Controller
{
    /**
     * @return object|string|void
     * @throws Exception
     */
    public function add()
    {
        if (!User_model::is_logged()) {
            return $this->response_error(System\Libraries\Core::RESPONSE_GENERIC_NEED_AUTH);
        }

        $post_id = (int)$this->input->post("post_id");
        $comment_text = htmlspecialchars($this->input->post('comment_ext'));
        $reply_id = (int)$this->input->post("reply_id");

        $res = Comment_model::add_comment($post_id, $comment_text, $reply_id);

        if ($res === TRUE) {
            return $this->response_success([
                'comments' => Comment_model::preparation_many(\Model\Comment_model::get_all_by_assign_id($post_id), 'group_with_child')
            ]);
        }

        return $this->response_error("Can't create comment");
    }

}