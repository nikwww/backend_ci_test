<?php

class Log_buy_boosterpack extends CI_Emerald_Model {
    const CLASS_TABLE = 'log_buy_boosterpack';

    /**
     * @param array $data
     */
    public static function create(array $data)
    {
        App::get_ci()->s->from(self::CLASS_TABLE)->insert($data)->execute();
    }

}
