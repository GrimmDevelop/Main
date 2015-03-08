<?php


use Grimm\Search\FilterRequestParser;
use Grimm\Search\Filters\Code;
use Grimm\Search\Filters\EmptyFilter;
use Grimm\Search\Filters\FilterValue;
use Grimm\Search\Filters\MatchFilter;
use Grimm\Search\Filters\OperatorFilter;

class FilterRequestParserTest extends PHPUnit_Framework_TestCase
{

    public function testParsesSimpleRequest()
    {
        $data = [
            'id' => null,
            'filter_key' => null,
            'type' => 'group',
            'properties' => [
                'operator' => 'OR'
            ],
            'fields' => [
                [
                    'type' => 'field',
                    'code' => 'empfaenger',
                    'compare' => 'starts with',
                    'value' => 'Grimm'
                ],
                [
                    'type' => 'field',
                    'code' => 'absendeort',
                    'compare' => 'contains',
                    'value' => 'Hanau'
                ]
            ]
        ];

        $expected = new OperatorFilter(
            new MatchFilter(new Code('empfaenger'), 'starts with', new FilterValue('Grimm')),
            'OR',
            new MatchFilter(new Code('absendeort'), 'contains', new FilterValue('Hanau'))
        );

        $parser = new FilterRequestParser();

        $actual = $parser->parse($data);

        $this->assertEquals($expected, $actual);
    }

    public function testParsesSingleFieldRequest()
    {
        $data = [
            'id' => null,
            'filter_key' => null,
            'type' => 'group',
            'properties' => [
                'operator' => 'OR'
            ],
            'fields' => [
                [
                    'type' => 'field',
                    'code' => 'empfaenger',
                    'compare' => 'starts with',
                    'value' => 'Grimm'
                ]
            ]
        ];

        $expected = new MatchFilter(new Code('empfaenger'), 'starts with', new FilterValue('Grimm'));

        $actual = (new FilterRequestParser())->parse($data);

        $this->assertEquals($expected, $actual);
    }

    public function testParsesMoreThanTwoFieldsInGroup()
    {
        $data = [
            'id' => null,
            'filter_key' => null,
            'type' => 'group',
            'properties' => [
                'operator' => 'OR'
            ],
            'fields' => [
                [
                    'type' => 'field',
                    'code' => 'empfaenger',
                    'compare' => 'starts with',
                    'value' => 'Grimm'
                ],
                [
                    'type' => 'field',
                    'code' => 'absendeort',
                    'compare' => 'contains',
                    'value' => 'Hanau'
                ],
                [
                    'type' => 'field',
                    'code' => 'empf_ort',
                    'compare' => 'contains',
                    'value' => 'Kassel'
                ]
            ]
        ];

        $expected = new OperatorFilter(
            new OperatorFilter(
                new MatchFilter(new Code('empfaenger'), 'starts with', new FilterValue('Grimm')),
                'OR',
                new MatchFilter(
                    new Code('absendeort'),
                    'contains',
                    new FilterValue('Hanau')
                )
            ),
            'OR',
            new MatchFilter(
                new Code('empf_ort'),
                'contains',
                new FilterValue('Kassel')
            )
        );

        $actual = (new FilterRequestParser())->parse($data);

        $this->assertEquals($expected, $actual);
    }

    public function testParsesNestedGroups()
    {
        $data = [
            'id' => null,
            'filter_key' => null,
            'type' => 'group',
            'properties' => [
                'operator' => 'OR'
            ],
            'fields' => [
                [
                    'type' => 'group',
                    'properties' => [
                        'operator' => 'AND'
                    ],
                    'fields' => [
                        [
                            'type' => 'field',
                            'code' => 'empfaenger',
                            'compare' => 'starts with',
                            'value' => 'Grimm'
                        ],
                        [
                            'type' => 'field',
                            'code' => 'absendeort',
                            'compare' => 'contains',
                            'value' => 'Hanau'
                        ]
                    ]
                ],
                [
                    'type' => 'field',
                    'code' => 'empf_ort',
                    'compare' => 'contains',
                    'value' => 'Kassel'
                ]
            ]
        ];

        $expected = new OperatorFilter(
            new OperatorFilter(
                new MatchFilter(new Code('empfaenger'), 'starts with', new FilterValue('Grimm')),
                'AND',
                new MatchFilter(
                    new Code('absendeort'),
                    'contains',
                    new FilterValue('Hanau')
                )
            ),
            'OR',
            new MatchFilter(
                new Code('empf_ort'),
                'contains',
                new FilterValue('Kassel')
            )
        );

        $actual = (new FilterRequestParser())->parse($data);

        $this->assertEquals($expected, $actual);
    }

    public function testIncompleteField()
    {
        $data = [
            "id" => null,
            "filter_key" => null,
            "type" => "group",
            "properties" => [
                "operator" =>"AND"
            ],
            "fields" => [
                [
                    "code" => "",
                    "compare" => "equals",
                    "value" => "",
                    "type" => "field"
                ]
            ]
        ];

        $expected = new EmptyFilter();

        $actual = (new FilterRequestParser())->parse($data);

        $this->assertEquals($expected, $actual);
    }


}
