<?php

namespace Grimm\Contract;

interface Unassigner {

    /**
     * unassigns a relatied item from an object
     * @param $objectId
     * @param $itemId
     * @return \Illuminate\Http\Response
     */
    public function unassign($objectId, $itemId);

}