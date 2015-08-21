<?php

namespace Grimm\Letter;

interface LetterService {

    /**
     * @param int $id
     * @return mixed
     */
    public function findById($id);

    /**
     * Lists trashed letters
     * @return \Illuminate\Pagination\Paginator
     */
    public function findTrashed();

    /**
     * Counts letter updated after given date
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
     * restores trashed letter
     * @param $id
     * @return bool
     */
    public function restore($id);
}