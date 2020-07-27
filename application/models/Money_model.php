<?php

class Money_model extends CI_Model {

    /**
     * @param float $amount
     */
    public static function add_money(float $amount)
    {
        $user = User_model::get_user();

        App::get_ci()->s->start_trans();
        $user->reload(true);
        $user->set_wallet_balance($user->get_wallet_balance() + $amount);
        $user->set_wallet_total_refilled($user->get_wallet_total_refilled() + $amount);
        App::get_ci()->s->commit();
        
        App::get_ci()->load->model('Log_add_money_model');
        Log_add_money_model::create([
            'user_id' => User_model::get_user()->get_id(),
            'amount' => $amount
        ]);
    }

}
