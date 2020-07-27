<?php

class Boosterpack_bye_model extends CI_Model {

    /**
     * @param int $boosterpack_id
     * @return boolean
     */
    public static function bye(int $boosterpack_id)
    {
        $user = User_model::get_user();
        App::get_ci()->load->model('Boosterpack_model');
        $boosterpack = new Boosterpack_model($boosterpack_id);

        App::get_ci()->s->start_trans();

        $user->reload(true);
        if ($user->get_wallet_balance() < $boosterpack->get_price()) {
            return false;
        }
        $boosterpack->reload(true);
        $user->set_wallet_balance($user->get_wallet_balance() - $boosterpack->get_price());
        $user->set_wallet_total_withdrawn($user->get_wallet_total_withdrawn() + $boosterpack->get_price());
        // для каждого поля запрос в базу на UPDATE user - жаль :)

        $win_likes = static::get_win_likes($boosterpack);
        $boosterpack_bank_old = $boosterpack->get_bank();
        $boosterpack->set_bank($boosterpack_bank_old + $boosterpack->get_price() - $win_likes);
        $user->set_rights($user->get_rights() + $win_likes);

        App::get_ci()->s->commit();

        App::get_ci()->load->model('Log_buy_boosterpack');
        Log_buy_boosterpack::create([
            'user_id' => $user->get_id(),
            'boosterpack_id' => $boosterpack->get_id(),
            'boosterpack_price' => $boosterpack->get_price(),
            'boosterpack_bank' => $boosterpack_bank_old,
            'win_likes' => $win_likes
        ]);
        
        return true;
    }

    /**
     * @param Boosterpack_model $boosterpack
     * @return int
     */
    protected static function get_win_likes(Boosterpack_model $boosterpack): int
    {
        return rand(1, $boosterpack->get_price() + $boosterpack->get_bank());
    }

}
