<?php

namespace Grimm\Controller\Admin;

use Response;
use Input;

class FileController extends \Controller {
    

    private function initUploadFile()
    {
        $config = new \Flow\Config();
        $config->setTempDir(storage_path() . '/upload_tmp');
        $file = new \Flow\File($config);

        return $file;
    }

    public function uploadGet() {
        $file = $this->initUploadFile();
        if ($file->checkChunk()) {
            return $this->checkUploadStatus($file);
        } else {
            return Response::make('Not Found', 404);
        }
    }

    public function uploadPost() {
        $file = $this->initUploadFile();
        if ($file->validateChunk()) {
            $file->saveChunk();
            return $this->checkUploadStatus($file);
        } else {
            // error, invalid chunk upload request, retry
            return Response::make('Bad Request', 400);
        }
    }

    private function checkUploadStatus($file)
    {
        $filename = Input::get('flowRelativePath');

        if ($file->validateFile() && $file->save(storage_path() . '/upload/' . $filename)) {
            // File upload was completed
            return Response::make('Completed', 200);
        } else {
            // This is not a final chunk, continue to upload
            return Response::make('Chunk ok', 200);
        }
    }
}
