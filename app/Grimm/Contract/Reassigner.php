<?php

namespace Grimm\Contract;

interface Reassigner {

    /**
     * unassigns current item from given object and assign new item
     * @param $objectId
     * @param $currentItemId
     * @param $newItemId
     * @return \Illuminate\Http\Response
     */
    public function reassign($objectId, $currentItemId, $newItemId);

}