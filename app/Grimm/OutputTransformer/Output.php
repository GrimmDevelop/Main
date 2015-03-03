<?php

namespace Grimm\OutputTransformer;


interface Output {
    public function transform($data);
}