<?php

namespace Grimm\Controller\Admin;

use Grimm\Assigner\LetterFrom;
use Grimm\Assigner\LetterReceiver;
use Grimm\Assigner\LetterSender;
use Grimm\Assigner\LetterTo;
use Grimm\Models\GeoCache;
use Grimm\Models\Letter;
use Grimm\Models\Location;
use Grimm\Models\Person;
use Grimm\Models\PersonCache;
use Input;
use Sentry;

class AssignController extends \Controller {

    protected $cache = [];

    public function __construct(LetterSender $letterSenderAssigner, LetterReceiver $letterReceiverAssigner, LetterFrom $letterFromAssigner, LetterTo $letterToAssigner)
    {
        $this->assigner['senders'] = $letterSenderAssigner;
        $this->assigner['receivers'] = $letterReceiverAssigner;
        $this->assigner['from'] = $letterFromAssigner;
        $this->assigner['to'] = $letterToAssigner;
    }

    public function from()
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('assign'))) {
            return \App::make('grimm.unauthorized');
        }


        $failedLocations = [];
        $counter = 0;
        foreach ($this->getLetters('from') as $letter) {
            foreach ($letter->informations as $information) {
                if ($information->code == 'absendeort' || $information->code == 'absort_ers') {
                    if ($location = $this->getLocation($information->data)) {
                        $letter->from()->associate($location);
                        $letter->save();
                        $counter ++;
                    } else {
                        $failedLocations[] = [
                            'name' => $information->data,
                            'letter' => $letter->id
                        ];
                    }
                }
            }
        }

        return \Response::json(array('type' => $counter > 0 ? 'success' : 'danger', 'message' => $counter . ' assignments done.', 'failed' => $failedLocations));
    }

    public function to()
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('assign'))) {
            return \App::make('grimm.unauthorized');
        }

        $failedLocations = [];
        $counter = 0;
        foreach ($this->getLetters('to') as $letter) {
            foreach ($letter->informations as $information) {
                if ($information->code == 'empf_ort') {
                    if ($location = $this->getLocation($information->data)) {
                        $letter->to()->associate($location);
                        $letter->save();
                        $counter ++;
                    } else {
                        $failedLocations[] = [
                            'name' => $information->data,
                            'letter' => $letter->id
                        ];
                    }
                }
            }
        }

        return \Response::json(array('type' => $counter > 0 ? 'success' : 'danger', 'message' => $counter . ' assignments done.', 'failed' => $failedLocations));
    }

    public function senders()
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('assign'))) {
            return \App::make('grimm.unauthorized');
        }

        $failedPersons = [];
        $counter = 0;
        foreach ($this->getLetters('senders') as $letter) {
            foreach ($letter->informations as $information) {
                if ($information->code == 'senders') {
                    if ($person = $this->getPerson($information->data)) {
                        $this->assigner['senders']->assign($letter, $person);
                        $counter ++;
                    } else {
                        $failedPersons[] = [
                            'name' => $information->data,
                            'letter' => $letter->id
                        ];
                    }
                }
            }
        }

        return \Response::json(array('type' => $counter > 0 ? 'success' : 'danger', 'message' => $counter . ' assignments done.', 'failed' => $failedPersons));
    }

    public function receivers()
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('assign'))) {
            return \App::make('grimm.unauthorized');
        }

        $failedPersons = [];
        $counter = 0;
        foreach ($this->getLetters('receivers') as $letter) {
            foreach ($letter->informations as $information) {
                if ($information->code == 'receivers') {
                    if ($person = $this->getPerson($information->data)) {
                        $this->assigner['receivers']->assign($letter, $person);
                        $counter ++;
                    } else {
                        $failedPersons[] = [
                            'name' => $information->data,
                            'letter' => $letter->id
                        ];
                    }
                }
            }
        }

        return \Response::json(array('type' => $counter > 0 ? 'success' : 'danger', 'message' => $counter . ' assignments done.', 'failed' => $failedPersons));
    }

    protected function getLetters($mode)
    {
        $builder = Letter::query();

        switch ($mode) {
            case 'from':
                $builder->where('from_id', null);
                $builder->whereRaw('(select count(*) from letter_informations where letters.id = letter_informations.letter_id and (letter_informations.code = "absendeort" or letter_informations.code = "absort_ers") and data != "") > 0');
                break;
            case 'to':
                $builder->where('to_id', null);
                $builder->whereRaw('(select count(*) from letter_informations where letters.id = letter_informations.letter_id and letter_informations.code = "empf_ort" and data != "") > 0');
                break;
            case 'senders':
                $builder->whereRaw('(select count(*) from letter_informations where letters.id = letter_informations.letter_id and letter_informations.code = "senders" and data != "") != (select count(*) from letter_sender where letters.id = letter_sender.letter_id)');
                break;
            case 'receivers':
                $builder->whereRaw('(select count(*) from letter_informations where letters.id = letter_informations.letter_id and letter_informations.code = "receivers" and data != "") != (select count(*) from letter_receiver where letters.id = letter_receiver.letter_id)');
                break;
        }

        $builder->take((int)Input::get('take', 100));

        $builder->with('informations');

        return $builder->get();
    }

    protected function getPerson($name)
    {
        if (!isset($this->cache[$name])) {
            $this->cache[$name] = Person::where('name_2013', $name)->first();

            if(!$this->cache[$name]) {
                if($tmp = PersonCache::where('name', $name)->first()) {
                    $this->cache[$name] = $tmp->person;
                }
            }
        }
        return $this->cache[$name];
    }

    protected function getLocation($name)
    {
        if (!isset($this->cache[$name])) {
            $location = GeoCache::where('name', $name)->with('geo')->first();

            if (!$location) {
                $locations = Location::where('name', $name)->orWhere('asciiname', $name)->orWhere('alternatenames', 'like', '%,' . $name . ',%')->get();
                if ($locations->count() == 1) {
                    $this->cache[$name] = $locations[0];
                    GeoCache::create([
                        'name' => $name,
                        'geo_id' => $locations[0]->id
                    ]);
                } else {
                    $this->cache[$name] = null;
                }
            } else {
                $this->cache[$name] = $location->geo;
            }
        }
        return $this->cache[$name];
    }

    public function cacheLocation()
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('assign'))) {
            return \App::make('grimm.unauthorized');
        }

        GeoCache::create(Input::only(['name', 'geo_id']));
    }

    public function cachePerson() {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('assign'))) {
            return \App::make('grimm.unauthorized');
        }

        PersonCache::create(Input::only(['name', 'person_id']));
    }
}