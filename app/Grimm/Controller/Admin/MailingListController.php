<?php

namespace Grimm\Controller\Admin;

use Grimm\Models\MailingList;

class MailingListController extends \Controller {

    public function sendMail() {
        if(!(\Sentry::check() && \Sentry::getUser()->hasAccess('mailinglist'))) {
            return Response::json('Unauthorized action.', 403);
        }

        //
    }

    public function mailList() {
        if(!(\Sentry::check() && \Sentry::getUser()->hasAccess('mailinglist'))) {
            return Response::json('Unauthorized action.', 403);
        }

        return MailingList::all();
    }
}
