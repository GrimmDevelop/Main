<?php
/**
 * Created by PhpStorm.
 * User: davidbohn
 * Date: 03.03.15
 * Time: 16:30
 */

namespace Grimm\OutputTransformer;


interface Output {
    public function transform($data);
}