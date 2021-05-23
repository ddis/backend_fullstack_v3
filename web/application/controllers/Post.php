<?php

use Model\Post_model;
use Model\traits\Add_likes;
use Model\User_model;

/**
 * Class Post
 */
class Post extends MY_Controller
{
    use Add_likes;
    /**
     * @param int $post_id
     * @return object|string|void
     * @throws Exception
     */
    public function get_post(int $post_id)
    {
        $post = Post_model::preparation(new Post_model($post_id), 'full_info');

        if (!$post) {
            throw new Exception("Post with id {$post_id} not found", 404);
        }

        return $this->response_success(['post' => $post]);
    }

    /**
     * @return object|string|void
     * @throws Exception
     */
    public function get_all_posts()
    {
        $posts =  Post_model::preparation_many(Post_model::get_all(), 'default');
        return $this->response_success(['posts' => $posts]);
    }

    /**
     * @return object|string|void
     * @throws Throwable
     */
    public function add_like()
    {
        if (!User_model::is_logged()) {
            return $this->response_error(\System\Libraries\Core::RESPONSE_GENERIC_NEED_AUTH);
        }

        $entity_id = (int)App::get_ci()->input->post("id");

        try {
            $new_likes_count = Post_model::add_like($entity_id);
        } catch (Exception $exception) {
            return $this->response_error($exception->getMessage(), [], $exception->getCode());
        }

        return $this->response_success(['likes_count' => $new_likes_count]);
    }
}