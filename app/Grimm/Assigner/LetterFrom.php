<?php

namespace Grimm\Assigner;

use Grimm\Contract\Assigner;
use Grimm\Models\Letter;
use Grimm\Models\Location;

use Grimm\Assigner\Exceptions\ObjectNotFoundException;
use Grimm\Assigner\Exceptions\ItemNotFoundException;

class LetterFrom implements Assigner {

    /**
     * Assigns an item to an object
     * @param $object_id
     * @param $item_id
     * @return \Illuminate\Http\Response
     */
    public function assign($object_id, $item_id)
    {
        $letter = Letter::find($object_id);
        $location = Location::find($item_id);

        if (!($letter instanceof Letter) || !$letter->exists) {
            throw new ObjectNotFoundException();
        }

        if (!($location instanceof Location) || !$location->exists) {
            throw new ItemNotFoundException();
        }

        $letter->from()->associate($location);
        return (bool)$letter->save();
    }
}