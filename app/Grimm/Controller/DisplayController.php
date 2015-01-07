<?php

namespace Grimm\Controller;

use Input;
use Session;

class DisplayController extends \Controller {

    public function views($section = null)
    {
        if (in_array($section, ['letters', 'locations', 'persons'])) {
            $base = 'admin/partials/';
        } else {
            $base = 'partials/';
        }

        $views = ['overview', 'data'];

        return array_map(function ($item) use ($section, $base) {
            return $base . 'views.' . $section . '.' . $item;
        }, $views);
    }

    public function defaultView($section = null)
    {
        if (Session::has('defaultView.' . $section)) {
            return Session::get('defaultView.' . $section);
        }

        return $this->views($section)[0];
    }

    public function changeView($section = null)
    {
        $view = Input::get('view');
        $views = $this->views($section);

        if (in_array($view, $views)) {
            Session::set('defaultView.' . $section, $view);
            return $view;
        } else {
            return $this->defaultView($section);
        }
    }
}
