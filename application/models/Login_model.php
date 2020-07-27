<?php

/*
Models are PHP classes that are designed to work with information in your database. (с) CI docs
Login_model — с базой не работает, не по феншую :)
по докам, нужно было бы заморачиваться с libraries или drivers
но, по аналогии с Active Record и Модель в yii2, в общем я не против, а только за :)
было хорошо документировать такое, а то я сначала пытался по мануалам CI делать
*/

class Login_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();

    }

    public static function logout()
    {
        App::get_ci()->session->unset_userdata('id');
    }

    public static function start_session(int $user_id)
    {
        // если перенедан пользователь
        if (empty($user_id))
        {
            throw new CriticalException('No id provided!');
        }

        App::get_ci()->session->set_userdata('id', $user_id);
    }

    public static function login($email, $password)
    {
        $user_id = User_model::get_id_by_email_and_password($email, $password);
        
        if ($user_id) {
            static::start_session($user_id);

            return $user_id;
        }
        
        return false;
    }

}
