<?php

namespace Grimm\OutputTransformer;


class JsonPaginationOutput implements Output {

    public function transform($data)
    {
        $return = new \stdClass();

        $return->total = $data->getTotal();
        $return->per_page = $data->getPerPage();
        $return->current_page = $data->getCurrentPage();
        $return->last_page = $data->getLastPage();
        $return->from = $data->getFrom();
        $return->to = $data->getTo();
        $return->data = $data->getCollection()->toArray();

        return $data;
    }
}