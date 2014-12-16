<?php

namespace Grimm\Controller\Admin;

use Controller;
use Grimm\Models\Letter\Information;

class ExportController extends Controller {

    public function letterCodes() {
        return Information::selectRaw('DISTINCT(`code`)')->lists('code');
    }

}
