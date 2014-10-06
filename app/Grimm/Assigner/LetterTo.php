<?php

namespace Grimm\Assigner;

use Grimm\Contract\Assigner;
use Grimm\Models\Letter;
use Grimm\Models\Location;

class LetterTo implements Assigner {

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
            return \Response::json(array('type' => 'danger', 'message' => 'Letter not found'), 404);
        }

        if (!($location instanceof Location) || !$location->exists) {
            return \Response::json(array('type' => 'danger', 'message' => 'Person not found'), 404);
        }

        $letter->to()->associate($location);
        $letter->save();

        return \Response::json(array('type' => 'success', 'message' => 'Location assigned to letter'), 200);
    }
}