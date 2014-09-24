<?php

namespace Grimm\Controller\Queue;

use Grimm\Converter\Person as Converter;
use Grimm\Models\Person as Model;
use Grimm\Models\Person\Information;

class Person extends \Controller {

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function import($job, $data)
    {
        if (!isset($data['source']) || !file_exists(storage_path('upload') . $data['source'])) {
            throw new \InvalidArgumentException('Cannot find source file ' . storage_path('upload') . $data['source']);
        }

        $this->converter->setSource(storage_path('upload') . $data['source']);

        \Eloquent::unguard();

        foreach ($this->converter->parse() as $record) {
            if ($person = $this->firstOrCreateAndUpdate($record)) {
                // person created and/or updated
            }
        }

        \Eloquent::reguard();

        $job->delete();
    }

    protected function firstOrCreateAndUpdate($record)
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

        $person->informations()->delete();

        foreach ($record['informations'] as $index => $value) {
            $this->attachInformationToPerson($person, $index, $value);
        }

        return $person;
    }

    protected function attachInformationToPerson($person, $code, $data)
    {
        if (is_array($data)) {
            foreach ($data as $item) {
                $this->attachInformationToPerson($person, $code, $item);
            }
        } else {
            $person->informations()->save(new Information(array(
                'code' => $code,
                'data' => $data
            )));
        }
    }
}
