<?php

namespace Grimm\Assigner;

use Grimm\Contract\Assigner;
use Grimm\Contract\Unassigner;
use Grimm\Models\Letter;
use Grimm\Models\Location;

use Grimm\Assigner\Exceptions\ObjectNotFoundException;
use Grimm\Assigner\Exceptions\ItemNotFoundException;

class LetterFrom implements Assigner, Unassigner {

    /**
     * Assigns an item to an object
     * @param $object_id
     * @param $item_id
     * @throws ItemNotFoundException
     * @throws ObjectNotFoundException
     * @return \Illuminate\Http\Response
     */
    public function assign($object_id, $item_id)
    {
        $letter = ($object_id instanceof Letter) ? $object_id : Letter::find($object_id);
        $location = ($item_id instanceof Location) ? $item_id : Location::find($item_id);

        if (!($letter instanceof Letter) || !$letter->exists)
        {
            throw new ObjectNotFoundException();
        }

        if (!($location instanceof Location) || !$location->exists)
        {
            throw new ItemNotFoundException();
        }

        $letter->from()->associate($location);

        return (bool) $letter->save();
    }

    /**
     * unassigns a relatied item from an object
     * @param $objectId
     * @param $itemId
     * @throws ItemNotFoundException
     * @throws ObjectNotFoundException
     * @return \Illuminate\Http\Response
     */
    public function unassign($objectId, $itemId)
    {
        $letter = ($object_id instanceof Letter) ? $object_id : Letter::find($object_id);
        $location = ($item_id instanceof Location) ? $item_id : Location::find($item_id);

        if (!($letter instanceof Letter) || !$letter->exists)
        {
            throw new ObjectNotFoundException();
        }

        if (!($location instanceof Location) || !$location->exists)
        {
            throw new ItemNotFoundException();
        }

        $letter->from()->dissociate();
        $letter->save();
    }
}