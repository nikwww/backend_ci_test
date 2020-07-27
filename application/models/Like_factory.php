<?php

class Like_factory {

    /**
     * @param string $entity
     * @param int $id
     * @return Likeable_interface
     * @throws Exception
     */
    public static function create(string $entity, int $id)
    {
        switch ($entity) {
            case 'post':
                return new Post_model($id);
            case 'comment':
                return new Comment_model($id);
            default:
                throw new Exception('undefined Likeable entity');
        }
    }

}
