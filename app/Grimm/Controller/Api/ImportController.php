<?php

class ImportController extends \Controller {

    protected $queue;

    public function __construct(\Queue $queue) {
        $this->queue = $queue;
    }

    public function startLetterImport() {
        if(!(Sentry::check() && Sentry::getUser()->hasPermission('import.letters'))) {
            return \Response::json(array('message' => 'Unauthorized action.'), 403);
        }

        $this->queue->push('importLetters', 'Grimm\Controller\Queue\Letter@importLetters');

        return \Response::json(array('message' => 'Start importing letters.'));
    }

    public function startLocationImport() {
        if(!(Sentry::check() && Sentry::getUser()->hasPermission('import.locations'))) {
            return \Response::json(array('message' => 'Unauthorized action.'), 403);
        }

        // start locations import

        return \Response::json(array('message' => 'Start importing geo locations.'));
    }

}
