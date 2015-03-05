<?php


namespace Grimm\Import\Persistence;

use Grimm\Models\Person as Model;
use Grimm\Models\Person\Information;

class PersonRecordPersistence implements RecordPersistence
{
    public function persist($record)
    {
        $person = Model::where('name_2013', $record['name_2013'])->first();

        if ($person == null) {
            $person = Model::create(array(
                'name_2013' => $record['name_2013']
            ));
        } else {
            $person->name_2013 = $record['name_2013'];
            $person->save();
        }

        $person->information()->delete();

        foreach ($record['information'] as $index => $value) {
            $this->attachInfoToPerson($person, $index, $value);
        }

        return $person;
    }

    protected function attachInfoToPerson($person, $code, $data)
    {
        if (is_array($data)) {
            foreach ($data as $item) {
                $this->attachInfoToPerson($person, $code, $item);
            }
        } else {
            $person->information()->save(new Information(array(
                'code' => $code,
                'data' => $data
            )));
        }
    }
}