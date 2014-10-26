<?php

namespace Grimm\Controller\Admin;

use Grimm\Models\MailingList;

class MailingListController extends \Controller {

    public function sendMail()
    {
        //
    }

    public function mailList()
    {
        return MailingList::all();
    }
}
