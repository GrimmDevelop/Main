<?php

namespace Grimm\Controller;

use Input;
use Session;

class DisplayController extends \Controller {

    public function viewsAndDefault($section)
    {
        return \Response::json([
            'views' => $this->views($section),
            'default' => $this->defaultView($section),
        ]);
    }

    public function views($section)
    {
        if (in_array($section, ['letters', 'locations', 'persons'])) {
            $base = 'admin/partials/';
        } else {
            $base = 'partials/';
        }

        $views = ['data', 'overview'];

        return array_map(function ($item) use ($section, $base) {
            return $base . 'views.' . $section . '.' . $item;
        }, $views);
    }

    public function defaultView($section)
    {
        return Session::get('defaultView.' . $section, $this->views($section)[0]);
    }

    public function changeView($section)
    {
        $view = Input::get('view');
        $views = $this->views($section);

        if (in_array($view, $views)) {
            Session::put('defaultView.' . $section, $view);
        }

        // required to trigger Session::put
        return \Response::make($this->defaultView($section));
    }
}
