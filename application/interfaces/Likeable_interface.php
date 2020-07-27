<?php

interface Likeable_interface {

    public function get_likes();
    public function set_likes(int $likes);
    public function get_entity();
    public function get_id();
    public function reload(bool $for_update = FALSE); // не хватает интерфейса для Emerald моделей, поэтому такой костыль

}
