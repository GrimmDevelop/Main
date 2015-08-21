<?php

namespace Grimm\Location;


use Carbon\Carbon;
use Grimm\Models\Location;

class EloquentLocationService implements LocationService {

    /**
     * @param int $id
     * @return mixed
     */
    public function findById($id)
    {
        return Location::find($id);
    }

    /**
     * Lists trashed persons
     * @return \Illuminate\Pagination\Paginator
     */
    public function findTrashed()
    {
        return null;
    }

    /**
     * Counts person updated after given date
     * @param null $updated_after
     * @return int
     */
    public function count($updated_after = null)
    {
        if($updated_after !== null) {
            return Location::where('updated_at', '>=', Carbon::parse($updated_after))->count();
        }

        return Location::count();
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