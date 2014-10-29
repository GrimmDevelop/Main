<?php

namespace Grimm\Assigner;

use Grimm\Assigner\Exceptions\ItemNotAssignedException;
use Grimm\Contract\Assigner;
use Grimm\Contract\Unassigner;
use Grimm\Models\Letter;
use Grimm\Models\Person;
use Illuminate\Database\Eloquent\Model;

use Grimm\Assigner\Exceptions\ObjectNotFoundException;
use Grimm\Assigner\Exceptions\ItemNotFoundException;
use Grimm\Assigner\Exceptions\ItemAlreadyAssignedException;


class LetterSender implements Assigner, Unassigner {

    /**
     * Assigns an item to an object
     * @param $objectId
     * @param $itemId
     * @throws ItemAlreadyAssignedException
     * @throws ItemNotFoundException
     * @throws ObjectNotFoundException
     * @return \Illuminate\Http\Response
     */
    public function assign($objectId, $itemId)
    {
        $letter = ($objectId instanceof Letter) ? $objectId : Letter::find($objectId);
        $person = ($itemId instanceof Person) ? $itemId : Person::find($itemId);

        if (!($letter instanceof Letter) || !$letter->exists)
        {
            throw new ObjectNotFoundException();
        }

        if (!($person instanceof Person) || !$person->exists)
        {
            throw new ItemNotFoundException();
        }

        if ($letter->senders()->where('id', $person->id)->first())
        {
            throw new ItemAlreadyAssignedException();
        }

        $letter->senders()->attach($person);

        return true;
    }

    /**
     * unassigns a relatied item from an object
     * @param $objectId
     * @param $itemId
     * @throws ItemNotAssignedException
     * @throws ItemNotFoundException
     * @throws ObjectNotFoundException
     * @return \Illuminate\Http\Response
     */
    public function unassign($objectId, $itemId)
    {
        $letter = ($objectId instanceof Letter) ? $objectId : Letter::find($objectId);
        $person = ($itemId instanceof Person) ? $itemId : Person::find($itemId);

        if (!($letter instanceof Letter) || !$letter->exists)
        {
            throw new ObjectNotFoundException();
        }

        if (!($person instanceof Person) || !$person->exists)
        {
            throw new ItemNotFoundException();
        }

        if (!$letter->senders()->where('id', $person->id)->first())
        {
            throw new ItemNotAssignedException();
        }

        $letter->senders()->detach([$person->id]);

        return true;
    }
}