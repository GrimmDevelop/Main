<?php

namespace Grimm\Contract;

interface Assigner {

    /**
     * Assigns an item to an object
     * @param $objectId
     * @param $itemId
     * @return bool
     * @throws ItemAlreadyAssignedException
     * @throws ItemNotFoundException
     * @throws ObjectNotFoundException
     */
    public function assign($objectId, $itemId);

} 