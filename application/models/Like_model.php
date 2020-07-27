<?php

class Like_model extends CI_Model {

    /**
     * @param string $entity
     * @param int $id
     * @return Likeable_interface
     */
    public static function like_entity(string $entity, int $id)
    {
        App::get_ci()->load->model('Like_factory');
        $model = Like_factory::create($entity, $id);
        static::like($model);
        
        return $model;
    }

    /**
     * @param Likeable_interface $model
     * @return boolean
     */
    public static function like(Likeable_interface $model)
    {
        $user = User_model::get_user();

        App::get_ci()->s->start_trans();
        $user->reload(true);
        if ($user->get_rights() < 1) {
            return false;
        }
        $user->set_rights($user->get_rights() - 1);

        // для оптимизации производительности можно было бы просто выполнить запрос UPDATE `$entity` SET `likes` = `likes` + 1 WHERE `id` = $id
        $model->reload(true);
        $model->set_likes($model->get_likes() + 1);

        App::get_ci()->s->commit();

        App::get_ci()->load->model('Log_likes_model');
        Log_likes_model::create([
            'user_id' => User_model::get_user()->get_id(),
            'entity' => $model->get_entity(),
            'entity_id' => $model->get_id()
        ]);

        return true;
    }

}
