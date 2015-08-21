<?php

namespace Grimm\Person;

interface PersonService {

    /**
     * @param int $id
     * @return mixed
     */
    public function findById($id);

    /**
     * Lists trashed persons
     * @return \Illuminate\Pagination\Paginator
     */
    public function findTrashed();

    /**
     * Counts person updated after given date
     * @param null $updated_after
     * @return int
     */
    public function count($updated_after = null);

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param int $id
     * @param array $data
     * @return true|false
     */
    public function update($id, array $data);

    /**
     * @param mixed $idOrModel
     * @return true|false
     */
    public function delete($idOrModel);

    /**
     * restores trashed person
     * @param $id
     * @return bool
     */
    public function restore($id);
}