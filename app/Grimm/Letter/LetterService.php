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

}