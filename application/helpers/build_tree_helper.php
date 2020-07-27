<?php

function build_tree($data)
{
    $first = new stdClass;
    $first->id = 0;
    $first->parent_id = 0;

    $tree = array(0 => $first);
    $temp = array(0 => &$tree[0]);

    foreach ($data as $val) {
        $parent = &$temp[$val->parent_id];
        if (!isset($parent->childs)) {
            $parent->childs = array();
        }
        $parent->childs[$val->id] = $val;
        $temp[$val->id] = &$parent->childs[$val->id];
    }

    return $tree[0]->childs;
}
