<?php

namespace Grimm\Assigner;

use Grimm\Contract\Assigner;
use Grimm\Models\Letter;
use Grimm\Models\Person;
use Illuminate\Database\Eloquent\Model;

use Grimm\Assigner\Exceptions\ObjectNotFoundException;
use Grimm\Assigner\Exceptions\ItemNotFoundException;
use Grimm\Assigner\Exceptions\ItemAlreadyAssignedException;

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
            throw new ObjectNotFoundException();
        }

        if (!($person instanceof Person) || !$person->exists) {
            throw new ItemNotFoundException();
        }

        if ($letter->receivers()->where('id', $person->id)->first()) {
            throw new ItemAlreadyAssignedException();
        }

        $letter->receivers()->attach($person);
        return true;
    }
}