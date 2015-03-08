<?php


namespace Grimm\Search;


use Grimm\Search\Filters\Code;
use Grimm\Search\Filters\FilterValue;
use Grimm\Search\Filters\MatchFilter;
use Grimm\Search\Filters\OperatorFilter;

class FilterRequestParser {

    public function parse($json)
    {
        // TODO: Either limit recursion depth or convert to stack based Parser
        if (!array_key_exists('type', $json) || $json['type'] !== 'group') {
            throw new InvalidFilterRequestException('The filters must be enclosed in a group!');
        }

        $this->validateGroup($json);

        $operator = strtoupper($json['properties']['operator']);

        $fields = $json['fields'];

        $this->validateFields($fields);

        $currentFilter = null;

        foreach ($fields as $field) {
            $parsedField = $this->parseField($field);

            if ($currentFilter !== null) {
                $currentFilter = new OperatorFilter(
                    $currentFilter,
                    $operator,
                    $parsedField
                );
            } else {
                $currentFilter = $parsedField;
            }
        }

        return $currentFilter;
    }

    private function validateGroup($json)
    {
        if (!(is_array($json['properties']) && in_array(strtoupper($json['properties']['operator']), ['OR', 'AND']))) {
            throw new InvalidFilterRequestException('Group is not in valid format');
        }
    }

    private function validateFields($fields)
    {
        if (!is_array($fields) || count($fields) == 0) {
            throw new InvalidFilterRequestException('Invalid field structure in group');
        }
    }

    protected function parseField($field) {
        if (!array_key_exists('type', $field)) {
            throw new InvalidFilterRequestException('Type key is missing!');
        }
        if ($field['type'] == 'field') {
            return new MatchFilter(new Code($field['code']), $field['compare'], new FilterValue($field['value']));
        } else {
            return $this->parse($field);
        }
    }
}