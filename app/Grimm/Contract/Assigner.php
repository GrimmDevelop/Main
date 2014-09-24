<?php

namespace Grimm\Contract;

interface Assigner {

    /**
     * Assigns an item to an object
     * @param $object_id
     * @param $item_id
     * @return \Illuminate\Http\Response
     */
    public function assign($object_id, $item_id);

} 