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

    public function from($take)
    {
        $failedLocations = [];
        $counter = 0;

        foreach ($this->getLetters('from', $take) as $letter)
        {
            foreach ($letter->information as $infor)
            {
                if ($infor->code == 'absendeort' || $infor->code == 'absort_ers')
                {
                    if ($location = $this->getLocation($infor->data))
                    {
                        try
                        {
                            $this->assigner['from']->assign($letter, $location);
                            $counter ++;
                        } catch (\Exception $e)
                        {
                        }
                    } else
                    {
                        $failedLocations[] = [
                            'name'   => $infor->data,
                            'letter' => $letter->id
                        ];
                    }
                }
            }
        }

        return \Response::json(array('type' => $counter > 0 ? 'success' : 'danger', 'message' => $counter . ' assignments done.', 'failed' => $failedLocations));
    }

    public function to($take)
    {
        $failedLocations = [];
        $counter = 0;
        foreach ($this->getLetters('to', $take) as $letter)
        {
            foreach ($letter->information as $info)
            {
                if ($info->code == 'empf_ort')
                {
                    if ($location = $this->getLocation($info->data))
                    {
                        try
                        {
                            $this->assigner['to']->assign($letter, $location);
                            $counter ++;
                        } catch (\Exception $e)
                        {
                        }
                    } else
                    {
                        $failedLocations[] = [
                            'name'   => $info->data,
                            'letter' => $letter->id
                        ];
                    }
                }
            }
        }

        return \Response::json(array('type' => $counter > 0 ? 'success' : 'danger', 'message' => $counter . ' assignments done.', 'failed' => $failedLocations));
    }

    public function senders($take)
    {
        $failedPersons = [];
        $counter = 0;
        foreach ($this->getLetters('senders', $take) as $letter)
        {
            foreach ($letter->information as $info)
            {
                if ($info->code == 'senders')
                {
                    if ($person = $this->getPerson($info->data))
                    {
                        try
                        {
                            $this->assigner['senders']->assign($letter, $person);
                            $counter ++;
                        } catch (\Exception $e)
                        {
                        }
                    } else
                    {
                        $failedPersons[] = [
                            'name'   => $info->data,
                            'letter' => $letter->id
                        ];
                    }
                }
            }
        }

        return \Response::json(array('type' => $counter > 0 ? 'success' : 'danger', 'message' => $counter . ' assignments done.', 'failed' => $failedPersons));
    }

    public function receivers($take)
    {
        $failedPersons = [];
        $counter = 0;
        foreach ($this->getLetters('receivers', $take) as $letter)
        {
            foreach ($letter->information as $info)
            {
                if ($info->code == 'receivers')
                {
                    if ($person = $this->getPerson($info->data))
                    {
                        try
                        {
                            $this->assigner['receivers']->assign($letter, $person);
                            $counter ++;
                        } catch (\Exception $e)
                        {
                        }
                    } else
                    {
                        $failedPersons[] = [
                            'name'   => $info->data,
                            'letter' => $letter->id
                        ];
                    }
                }
            }
        }

        return \Response::json(array('type' => $counter > 0 ? 'success' : 'danger', 'message' => $counter . ' assignments done.', 'failed' => $failedPersons));
    }

    protected function getLetters($mode, $take)
    {
        if($take <= 0) {
            return [];
        }

        $builder = Letter::query();

        switch ($mode)
        {
            case 'from':
                $builder->where('from_id', null);
                $builder->whereRaw('(select count(*) from letter_information where letters.id = letter_information.letter_id and (letter_information.code = "absendeort" or letter_information.code = "absort_ers") and data != "") > 0');
                break;
            case 'to':
                $builder->where('to_id', null);
                $builder->whereRaw('(select count(*) from letter_information where letters.id = letter_information.letter_id and letter_information.code = "empf_ort" and data != "") > 0');
                break;
            case 'senders':
                $builder->whereRaw('(select count(*) from letter_information where letters.id = letter_information.letter_id and letter_information.code = "senders" and data != "") != (select count(*) from letter_sender where letters.id = letter_sender.letter_id)');
                break;
            case 'receivers':
                $builder->whereRaw('(select count(*) from letter_information where letters.id = letter_information.letter_id and letter_information.code = "receivers" and data != "") != (select count(*) from letter_receiver where letters.id = letter_receiver.letter_id)');
                break;
        }

        $builder->take(abs((int) $take));

        $builder->with('information');

        return $builder->get();
    }

    protected function getPerson($name)
    {
        if (!isset($this->cache[$name]))
        {
            $this->cache[$name] = Person::where('name_2013', $name)->first();

            if (!$this->cache[$name])
            {
                if ($tmp = PersonCache::where('name', $name)->first())
                {
                    $this->cache[$name] = $tmp->person;
                }
            }
        }

        return $this->cache[$name];
    }

    protected function getLocation($name)
    {
        if (!isset($this->cache[$name]))
        {
            $location = GeoCache::where('name', $name)->with('geo')->first();

            if (!$location)
            {
                $locations = Location::where('name', $name)->orWhere('asciiname', $name)->orWhere('alternatenames', 'like', '%,' . $name . ',%')->get();
                if ($locations->count() == 1)
                {
                    $this->cache[$name] = $locations[0];
                    GeoCache::create([
                        'name'   => $name,
                        'geo_id' => $locations[0]->id
                    ]);
                } else
                {
                    $this->cache[$name] = null;
                }
            } else
            {
                $this->cache[$name] = $location->geo;
            }
        }

        return $this->cache[$name];
    }

    public function cacheLocation()
    {
        GeoCache::create(Input::only(['name', 'geo_id']));
    }

    public function cachePerson()
    {
        PersonCache::create(Input::only(['name', 'person_id']));
    }
}