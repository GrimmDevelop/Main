<?php

namespace Grimm\Person;

use Carbon\Carbon;
use Grimm\Models\Person;

class EloquentPersonService implements PersonService {

    /**
     * @param int $id
     * @return mixed
     */
    public function findById($id)
    {
        return Person::find($id);
    }

    /**
     * Lists trashed persons
     * @return \Illuminate\Pagination\Paginator
     */
    public function findTrashed()
    {
        return Person::onlyTrashed()->with('information')->paginate(150);
    }

    /**
     * Count persons updated after given date
     * @param null $updated_after
     * @return int
     */
    public function count($updated_after = null)
    {
        if($updated_after !== null) {
            return Person::where('updated_at', '>=', Carbon::parse($updated_after))->count();
        }

        return Person::count();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    /**
     * @param int $id
     * @param array $data
     * @return true|false
     */
    public function update($id, array $data)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param mixed $idOrModel
     * @return true|false
     */
    public function delete($idOrModel)
    {
        // TODO: Implement delete() method.
    }

    /**
     * restores trashed person
     * @param $id
     * @return bool
     */
    public function restore($id)
    {
        // TODO: Implement restore() method.
    }
}