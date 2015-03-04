<?php

namespace Grimm\Letter;

use Event;
use Grimm\Models\Letter;
use Grimm\Models\Letter\Information;

class EloquentLetterService implements LetterService {

    /**
     * Returns an letter object with given id
     * @param int $id
     * @return \Illuminate\Support\Collection|null|\Grimm\Models\Letter
     */
    public function findById($id)
    {
        return Letter::find($id);
    }

    /**
     * Returns an information object with given id
     * @param $id
     * @return \Illuminate\Support\Collection|null|\Grimm\Models\Letter\Information
     */
    public function findLetterInformationById($id)
    {
        return Information::find($id);
    }

    /**
     * Creates an empty letter and updates information
     * Fires "letter.created" and "letter.saved" on success
     * @param array $data
     * @return \Grimm\Models\Letter
     */
    public function create(array $data)
    {
        \Eloquent::unguard();

        if(!preg_match('/^[0-9]{8}\.[0-9]{2}$/', $data['code'])) {
            return false;
        }

        $letter = new Letter([
            'code' => 0,
            'date' => '',
            'language' => ''
        ]);

        $letter->save();

        Event::fire('letter.created', [
            'letter' => $letter->id
        ]);

        $this->update($letter->id, $data);

        return $letter;
    }

    /**
     * Updated a letter with given information
     * Fires "letter.saved" on success
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data)
    {
        $letter = $this->findById($id);

        if (!$letter) {
            return false;
        }

        if(!preg_match('/^[0-9]{8}\.[0-9]{2}$/', $data['code'])) {
            return false;
        }

        $this->updateInformationSet($letter, $data['information']);

        $this->updateColumn($letter, 'code', str_pad($data['code'], 11, '0', STR_PAD_LEFT));
        $this->updateColumn($letter, 'date', $data['date']);

        if ($letter->save()) {
            Event::fire('letter.saved', [
                'letter' => $letter->id
            ]);

            return true;
        }

        return false;
    }

    /**
     * updates given column from letter object (if values differ)
     * if, event "letter.updated.column" will be fired
     * @param Letter $letter
     * @param $column
     * @param $value
     */
    protected function updateColumn(Letter $letter, $column, $value)
    {
        $newValue = $value;
        if ($newValue != $letter->$column) {
            Event::fire('letter.updated.column', [
                'letter' => $letter->id,
                'field' => 'code',
                'data_old' => $letter->$column,
                'data_new' => $newValue
            ]);

            $letter->$column = $newValue;
        }
    }

    /**
     * Updated given set off information to letter
     * @param Letter $letter
     * @param array $information
     */
    protected function updateInformationSet(Letter $letter, array $information)
    {
        foreach ($information as $info) {
            $info = (object)$info;

            if ($info->state == 'add') {
                $this->addInformation($letter, $info);
            } else if ($info->state == 'keep') {
                if ($infoObj = $this->findLetterInformationById($info->id)) {
                    if ($infoObj->data != $info->data) {
                        $this->updateInformation($letter, $infoObj, $info->data);
                    }
                }
            } else if ($info->state == 'remove') {
                if ($info = $this->findLetterInformationById($info->id)) {
                    $this->removeInformation($letter, $info);
                }
            }
        }
    }

    /**
     * Adds a information to a letter
     * Fires "letters.updated.information.added"
     * @param Letter $letter
     * @param object $info
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function addInformation(Letter $letter, $info)
    {
        Event::fire('letters.updated.information.added', [
            'letter' => $letter->id,
            'code' => $info->code,
            'data' => $info->data
        ]);

        return $letter->information()->save(new Information([
            'code' => $info->code,
            'data' => $info->data
        ]));
    }

    /**
     * Updates a information from given letter and touches the letter
     * Fires "letters.updated.information.changed"
     * @param Letter $letter
     * @param Information $info
     * @param $newData
     */
    protected function updateInformation(Letter $letter, Information $info, $newData)
    {
        Event::fire('letters.updated.information.changed', [
            'letter' => $letter->id,
            'code' => $info->code,
            'data_old' => $info->data,
            'data_new' => $newData
        ]);

        $letter->touch();
        $info->data = $newData;
        $info->save();
    }

    /**
     * Remove the information from the letter and touches the letter
     * Fires "letters.updated.information.removed"
     * @param Letter $letter
     * @param Information $info
     * @return bool|null
     * @throws \Exception
     */
    protected function removeInformation(Letter $letter, Information $info)
    {
        Event::fire('letters.updated.information.removed', [
            'letter' => $letter->id,
            'code' => $info->code,
            'data' => $info->data
        ]);

        $letter->touch();

        return $info->delete();
    }

    /**
     * Deletes a letter
     * Fires "letters.deleted" on success
     * @param mixed $idOrModel
     * @return bool
     * @throws \Exception
     */
    public function delete($idOrModel)
    {
        if (!($idOrModel instanceof Letter)) {
            $idOrModel = $this->findById($idOrModel);
        }

        $model = $idOrModel;

        $oldId = $model->id;
        if ($model->delete()) {
            Event::fire('letter.deleted', [
                'letter' => $oldId,
                'user' => \Sentry::getUser(),
            ]);

            return true;
        }

        return false;
    }
}