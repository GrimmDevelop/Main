<?php


namespace Grimm\Search;


use Exception;
use Grimm\Search\Filters\Code;
use Grimm\Search\Filters\EmptyFilter;
use Grimm\Search\Filters\FilterValue;
use Grimm\Search\Filters\LetterField;
use Grimm\Search\Filters\LetterFilter;
use Grimm\Search\Filters\MatchFilter;
use Grimm\Search\Filters\OperatorFilter;
use SplStack;

class FilterRequestParser {

    public function parse($json)
    {
        // TODO: Either limit recursion depth or convert to stack based Parser
        if (!array_key_exists('type', $json) || $json['type'] !== 'group') {
            throw new InvalidFilterRequestException('The filters must be enclosed in a group!');
        }

        $parserStack = new SplStack();

        $this->validateGroup($json);

        $operator = strtoupper($json['properties']['operator']);

        $stackItem = new FilterParserStackItem($json['fields'], $operator);
        $parserStack->push($stackItem);

        $result = null;

        while (!$parserStack->isEmpty()) {
            $stackItem = $parserStack->top();
            $this->validateFields($stackItem->fields);

            while (count($stackItem->fields) > 0) {
                $field = $stackItem->fields[0];

                if ($field['type'] == 'group') {

                    try {
                        // If it is a valid group, push it on the stack, remove it from the fields and start parsing
                        $this->validateGroup($field);
                        $operator = strtoupper($field['properties']['operator']);

                        $parserStack->push(new FilterParserStackItem($field['fields'], $operator));
                        array_splice($stackItem->fields, 0, 1);
                        continue 2;
                    } catch (InvalidFilterRequestException $e) {
                        dd($e);
                    }
                } else {
                    $filter = $this->parseField($field);
                    if ($stackItem->filter !== null) {
                        $stackItem->filter = new OperatorFilter($stackItem->filter, $stackItem->operator, $filter);
                    } else {
                        $stackItem->filter = $filter;
                    }
                    array_splice($stackItem->fields, 0, 1);
                }
            }

            $oldTop = $parserStack->pop();
            if (!$parserStack->isEmpty()) {
                $newTop = $parserStack->top();
                if ($newTop->filter !== null) {
                    $newTop->filter = new OperatorFilter(
                        $newTop->filter,
                        $newTop->operator,
                        $oldTop->filter
                    );
                } else {
                    $newTop->filter = $oldTop->filter;
                }
            } else {
                $result = $oldTop->filter;
            }
        }
        return $result;
    }

    private function validateGroup($json)
    {
        if (!(is_array($json['properties']) && in_array(strtoupper($json['properties']['operator']), ['OR', 'AND']))) {
            throw new InvalidFilterRequestException('Group is not in valid format');
        }
    }

    private function validateFields($fields)
    {
        if (!is_array($fields)) {
            throw new InvalidFilterRequestException('Invalid field structure in group');
        }
    }

    protected function parseField($field) {
        if (!array_key_exists('type', $field)) {
            throw new InvalidFilterRequestException('Type key is missing!');
        }
        if ($field['type'] == 'field') {
            try {
                return new MatchFilter(new Code($field['code']), $field['compare'], new FilterValue($field['value']));
            } catch (Exception $e) {
                return new EmptyFilter();
            }
        } else if ($field['type'] == 'letterfield') {
            try {
                return new LetterFilter(new LetterField($field['code']), $field['compare'], new FilterValue($field['value']));
            } catch (Exception $e) {
                return new EmptyFilter();
            }
        } else {
            throw new InvalidFilterRequestException('Invalid field type');
        }
    }
}