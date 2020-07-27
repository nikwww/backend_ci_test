<?php

/*
- Code style не соблюдается. Не совпадает с https://codeigniter.com/userguide3/general/styleguide.html или psr. Я старался, чтоб мой код не выделялся на общем фоне проекта
- Created by PhpStorm... - мусорный коммент, я такое обычное убираю
- Main_page: в теории, можно было и не в один контроллер пихать всё ;)
- class MY_Controller extends CI_Controller - не понял, зачем вообще нужен в данном приложении.
- SELECT * - дорогая операция для highload: например, нам нужны только часть параметров, без text, которые весят несколько десятков байт, а с неиспользуемым в дальнейшем text, данных из базы будет доставаться на порядки больше. (Нету функционала для фильтрации полей в orm моделях.)

- Защита от падения сервера? Сделать отдельную очередь, в кроне обрабатывать транзакции (я не в курсе, как обычно делают защиту критически важные транзакции, не было еще опыта в банковской сфере :) )
*/

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 21:36
 */
class Main_page extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        App::get_ci()->load->model('User_model');
        App::get_ci()->load->model('Login_model');
        App::get_ci()->load->model('Post_model');

        if (is_prod())
        {
            die('In production it will be hard to debug! Run as development environment!');
        }
    }

    public function index()
    {
        $user = User_model::get_user();



        App::get_ci()->load->view('main_page', ['user' => User_model::preparation($user, 'default')]);
    }

/*
App::get_ci()->load->view('main_page', ['user' => User_model::preparation($user, 'default')]);
плохо воспринимается, на мой взгляд, когда все в одну строку
лучше:
$data = [
    'user' => User_model::preparation($user, 'default')
];
$this->response_view('main_page', $data);
*/
    
    public function get_all_posts()
    {
        $posts =  Post_model::preparation(Post_model::get_all(), 'main_page');
        return $this->response_success(['posts' => $posts]);
    }

    public function get_post($post_id){ // or can be $this->input->post('news_id') , but better for GET REQUEST USE THIS

        $post_id = intval($post_id);

        if (empty($post_id)){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try
        {
            $post = new Post_model($post_id);
        } catch (EmeraldModelNoDataException $ex){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }


        $posts =  Post_model::preparation($post, 'full_info');
        return $this->response_success(['post' => $posts]);
    }

    // http://ci_test.local/main_page/comment/1/0/test_comment
    public function comment(int $post_id, int $parent_id, string $text){ // or can be App::get_ci()->input->post('news_id') , but better for GET REQUEST USE THIS ( tests )

        if (!User_model::is_logged()){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);
        }

        if (empty($post_id) || empty($text)){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try
        {
            $post = new Post_model($post_id);
        } catch (EmeraldModelNoDataException $ex){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }

        if ($parent_id > 0){
            try
            {
                new Comment_model($parent_id); // просто валидируем, что такой коммент есть
            } catch (EmeraldModelNoDataException $ex){
                return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
            }
        }
        
        $comment_data = [
            'user_id' => User_model::get_session_id(),
            'assign_id' => $post_id,
            'parent_id' => $parent_id,
            'text' => $this->security->xss_clean($text)
        ];
        $comment_id = Comment_model::create($comment_data);

        $data = [
            'post' => Post_model::preparation($post, 'full_info'),
            'comment_id' => $comment_id
        ];
        return $this->response_success($data);
        
        // http://ci_test.local/main_page/get_post/1
        // реализовал древовидную структуру для комментариев: массив "childs" у объект в comments
        // на стороне клиента нету логики вывода, поэтому эти комментарии не показываются
    }

/* session_start(); — добавил в начало index.php, т.к. не работали сессии (не коммитил) */
    public function login()
    {
        $input_data = json_decode($this->input->raw_input_stream, true);
        $user_id = Login_model::login($input_data['login'], $input_data['password']);

        if (!$user_id){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        return $this->response_success(['user' => $user_id]);
    }


    public function logout()
    {
        Login_model::logout();
        redirect(site_url('/'));
    }

    // http://ci_test.local/main_page/add_money/10
    public function add_money(float $amount){

        App::get_ci()->load->model('Money_model');

        try
        {
            Money_model::add_money($amount);
        } catch (EmeraldModelNoDataException $ex){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }
        
        return $this->response_success(['amount' => User_model::get_user()->get_wallet_balance()]);
    }

    public function buy_boosterpack(int $boosterpack_id){

        App::get_ci()->load->model('Boosterpack_bye_model');

        try
        {
            $is_success = Boosterpack_bye_model::bye($boosterpack_id);
        } catch (EmeraldModelNoDataException $ex){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }

        if ($is_success) {
            return $this->response_success([
                'amount' => User_model::get_user()->get_rights()
            ]);
        } else {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_UNAVAILABLE);
        }
    }

    // http://ci_test.local/main_page/like/post/1
    // http://ci_test.local/main_page/like/comment/1
    public function like(string $entity, int $id){

        if (!User_model::is_logged()){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);
        }
        if (User_model::get_user()->get_rights() < 1){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_UNAVAILABLE);
        }

        App::get_ci()->load->model('Like_model');
        
        try
        {
            $entity_model = Like_model::like_entity($entity, $id);
        } catch (EmeraldModelNoDataException $ex){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }

        return $this->response_success(['likes' => $entity_model->get_likes()]);
    }

}
