<?php

namespace Grimm\Assigner;

use Grimm\Contract\Assigner;
use Grimm\Models\Letter;
use Grimm\Models\Person;
use Illuminate\Database\Eloquent\Model;

class LetterReceiver implements Assigner {

    /**
     * Assigns an item to an object
     * @param $object_id
     * @param $item_id
     * @return \Illuminate\Http\Response
     */
    public function assign($object_id, $item_id)
    {
        $letter = ($object_id instanceof Letter) ? $object_id : Letter::find($object_id);
        $person = ($item_id instanceof Person) ? $item_id : Person::find($item_id);

        if (!($letter instanceof Letter) || !$letter->exists) {
            return \Response::json(array('type' => 'danger', 'message' => 'Letter not found'), 404);
        }

        if (!($person instanceof Person) || !$person->exists) {
            return \Response::json(array('type' => 'danger', 'message' => 'Person not found'), 404);
        }

        if ($letter->receivers()->where('id', $person->id)->first()) {
            return \Response::make(array('type' => 'warning', 'message' => 'Person already assigned'), 200);
        }

        $letter->receivers()->attach($person);
        return \Response::json(array('type' => 'success', 'message' => 'Person assigned to letter'), 200);
    }
}