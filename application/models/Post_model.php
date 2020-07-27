<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 27.01.2020
 * Time: 10:10
 */
class Post_model extends CI_Emerald_Model implements Likeable_interface
{
    const CLASS_TABLE = 'post';


    /** @var int */
    protected $user_id;
    /** @var string */
    protected $text;
    /** @var string */
    protected $img;

    /** @var string */
    protected $time_created;
    /** @var string */
    protected $time_updated;

    // generated
    protected $comments;
    protected $likes;
    protected $user;


    /**
     * @return int
     */
    public function get_user_id(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     *
     * @return bool
     */
    public function set_user_id(int $user_id)
    {
        $this->user_id = $user_id;
        return $this->save('user_id', $user_id);
    }

    /**
     * @return string
     */
    public function get_text(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return bool
     */
    public function set_text(string $text)
    {
        $this->text = $text;
        return $this->save('text', $text);
    }

    /**
     * @return string
     */
    public function get_img(): string
    {
        return $this->img;
    }

    /**
     * @param string $img
     *
     * @return bool
     */
    public function set_img(string $img)
    {
        $this->img = $img;
        return $this->save('img', $img);
    }


    /**
     * @return string
     */
    public function get_time_created(): string
    {
        return $this->time_created;
    }

    /**
     * @param string $time_created
     *
     * @return bool
     */
    public function set_time_created(string $time_created)
    {
        $this->time_created = $time_created;
        return $this->save('time_created', $time_created);
    }

    /**
     * @return string
     */
    public function get_time_updated(): string
    {
        return $this->time_updated;
    }

    /**
     * @param string $time_updated
     *
     * @return bool
     */
    public function set_time_updated(int $time_updated)
    {
        $this->time_updated = $time_updated;
        return $this->save('time_updated', $time_updated);
    }

    /**
     * @return int
     */
    public function get_likes()
    {
        return $this->likes;
    }
/*
был непонятный комментарий «generated» (в итоге я кажется понял, что он значит, но только по коду)
@return mixed и название метода — вводит в заблуждение
я бы сделал название get_cnt_likes/get_num_likes
а то likes больше похоже на список этих лайков (по аналогии с comments)
*/

    /**
     * @param int $likes
     * 
     * @return bool
     */
    public function set_likes(int $likes)
    {
        $this->likes = $likes;
        return $this->save('likes', $likes);
    }

    /**
     * @return string
     */
    public function get_entity()
    {
        return self::CLASS_TABLE;
    }

    /**
     * @return Comment_model[]
     */
    public function get_comments()
    {
        $this->is_loaded(TRUE);

        if (empty($this->comments))
        {
            $this->comments = Comment_model::get_all_by_assign_id($this->get_id());
        }
        return $this->comments;

    }

    /**
     * @return User_model
     */
    public function get_user():User_model
    {
        $this->is_loaded(TRUE);

        if (empty($this->user))
        {
            try {
                $this->user = new User_model($this->get_user_id());
            } catch (Exception $exception)
            {
                $this->user = new User_model();
            }
        }
        return $this->user;
    }
/*
не сильно критично, но я бы в new User_model генерил «NullUser», чтоб не было try catch (мне очень сильно кажется, что тут не очень правильное использование этой конструкции)
*/

    function __construct($id = NULL)
    {
        parent::__construct();

        App::get_ci()->load->model('Comment_model');

        $this->set_id($id);
    }

    public function reload(bool $for_update = FALSE)
    {
        parent::reload($for_update);

        return $this;
    }

    public static function create(array $data)
    {
        App::get_ci()->s->from(self::CLASS_TABLE)->insert($data)->execute();
        return new static(App::get_ci()->s->get_insert_id());
    }

/*
App::get_ci()->s — непонятное имя у объекта
чтоб инкапсулировать и удобнее было пользоваться, можно сделать функцию
**
 * @return Sparrow
 *
function db()
{
    return App::get_ci()->s;
}
будет тогда хотя бы
db()->from(self::CLASS_TABLE)
вместо
App::get_ci()->s->from(self::CLASS_TABLE)

(а если взять laravel, то вообще просто Post::where() :) )
*/

    public function delete()
    {
        $this->is_loaded(TRUE);
        App::get_ci()->s->from(self::CLASS_TABLE)->where(['id' => $this->get_id()])->delete()->execute();
        return (App::get_ci()->s->get_affected_rows() > 0);
    }

    /**
     * @return self[]
     * @throws Exception
     */
    public static function get_all()
    {

        $data = App::get_ci()->s->from(self::CLASS_TABLE)->many();
        $ret = [];
        foreach ($data as $i)
        {
            $ret[] = (new self())->set($i);
        }
        return $ret;
    }

    /**
     * @param Post_model|Post_model[] $data
     * @param string $preparation
     * @return stdClass|stdClass[]
     * @throws Exception
     */
    public static function preparation($data, $preparation = 'default')
    {
        switch ($preparation)
        {
            case 'main_page':
                return self::_preparation_main_page($data);
            case 'full_info':
                return self::_preparation_full_info($data);
            default:
                throw new Exception('undefined preparation type');
        }
    }

/*
дефолтное значение кидает нас в exception?

_preparation_* — дублирование кода

я бы сделал с параметрами что-то вроде:

private static function _preparation($data, $fields = [])
{
    foreach {
        if (in_array('text', $fields)) 
            $o->text = $d->get_text();
        if (in_array('user', $fields)) 
            $o->user = ...
        ...
    }
}
public static function preparation_main_page($data)
{
    return _preparation($data, ['id', 'img', 'text', ...])
}

или можно сразу фильтровать нужные поля при SELECT из базы, а в preparation уже обрабатывать все not null поля (жертва удобством кода для лучшей производительности)

в любом случае (даже текущий вариант), я бы вынес этот код в отдельный класс

еще можно сделать для этих целей универсальный способ, где будет что-то вроде
$method_name = 'get_' . $param;
$o->{$param} = $d->{$method_name}();
*/

    /**
     * @param Post_model[] $data
     * @return stdClass[]
     */
    private static function _preparation_main_page($data)
    {
        $ret = [];

        foreach ($data as $d){
            $o = new stdClass();

            $o->id = $d->get_id();
            $o->img = $d->get_img();

            $o->text = $d->get_text();

            $o->user = User_model::preparation($d->get_user(),'main_page');

            $o->time_created = $d->get_time_created();
            $o->time_updated = $d->get_time_updated();

            $ret[] = $o;
        }


        return $ret;
    }


    /**
     * @param Post_model $data
     * @return stdClass
     */
    private static function _preparation_full_info(Post_model $data)
    {
        $o = new stdClass();

        $o->id = $data->get_id();
        $o->img = $data->get_img();

        $o->user = User_model::preparation($data->get_user(),'main_page');
        App::get_ci()->load->helper('build_tree');
        $coments = Comment_model::preparation($data->get_comments(),'full_info');
        $o->coments = build_tree($coments);
//        $o->coments = Comment_model::preparation($data->get_comments(),'full_info'); - эту строку можно вернуть, чтоб выводились все комментарии в массиве, а не в дереве

        $o->likes = $data->get_likes();

        $o->time_created = $data->get_time_created();
        $o->time_updated = $data->get_time_updated();

        return $o;
    }

/*
Не понял, почему 2. задача со звездочкой именно "со звездочкой", поэтому сделал вывод дерева (без постраничника)
Постраничник для комментов, например, можно реализовать так
1. SELECT FROM comment WHERE assign_id = [id] LIMIT 21
2.а. если 21 строки вернет, то делить на страницы, делая запросы WHERE parent_id = [comment_id], пока не наберется нужное число для страницы (в зависимости от логики разбиения на страницы)
2.б. если меньше 21 строки, то выдавать сразу дерево полностью
*/


}
