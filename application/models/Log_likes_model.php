<?php

class Log_likes_model extends CI_Emerald_Model {
    const CLASS_TABLE = 'log_likes';

    /**
     * @param array $data
     */
    public static function create(array $data)
    {
        App::get_ci()->s->from(self::CLASS_TABLE)->insert($data)->execute();
    }

}
