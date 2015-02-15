<?php

use Grimm\Transformer\NameEntry;

class NameEntryTransformerTest extends TestCase {

    /**
     * @return NameEntryTransformer
     */
    protected function getNameEntryTransformer()
    {
        return new NameEntry();
    }

    public function testFirstAndLastNameExtraction()
    {
        $testDataArray = [
            'Lastname, Firstname Secondfirstname',
            'Lastname Secondlastname, Firstname',
            'Lastname Secondlastname, Firstname Secondfirstname',
            'Onlylastname',
        ];

        $expectedDataArray = [
            [
                'last_name' => 'Lastname',
                'first_name' => 'Firstname Secondfirstname',
            ],
            [
                'last_name' => 'Lastname Secondlastname',
                'first_name' => 'Firstname',
            ],
            [
                'last_name' => 'Lastname Secondlastname',
                'first_name' => 'Firstname Secondfirstname',
            ],
            [
                'last_name' => 'Onlylastname',
            ],
        ];

        // Test

        $nameEntryTransformer = $this->getNameEntryTransformer();

        $result = [];
        foreach ($testDataArray as $testData) {
            $result[] = $nameEntryTransformer->extractData($testData);
        }

        $this->assertEquals($expectedDataArray, $result);
    }

    public function testMultipleNames()
    {
        $testDataArray = [
            'Lastname1, Firstname1; Lastname2, Firstname2',
            'Lastname1, Firstname1 Secondfirstname1; Lastname2, Firstname2;Lastname3 ; Lastname4, Firstname4',
        ];
        $expectedDataArray = [
            [
                [
                    'last_name' => 'Lastname1',
                    'first_name' => 'Firstname1',
                ],
                [
                    'last_name' => 'Lastname2',
                    'first_name' => 'Firstname2',
                ]
            ],
            [
                [
                    'last_name' => 'Lastname1',
                    'first_name' => 'Firstname1 Secondfirstname1',
                ],
                [
                    'last_name' => 'Lastname2',
                    'first_name' => 'Firstname2',
                ],
                [
                    'last_name' => 'Lastname3',
                ],
                [
                    'last_name' => 'Lastname4',
                    'first_name' => 'Firstname4',
                ]
            ]
        ];

        $names = [];
        $result = [];
        for ($i = 1; $i <= 10; $i ++) {
            $names[] = "Lastname$i, Firstname$i";
            $result[] = [
                'last_name' => "Lastname$i",
                'first_name' => "Firstname$i",
            ];
        }
        $testDataArray[] = implode('; ', $names);
        $expectedDataArray[] = $result;

        // Test

        $nameEntryTransformer = $this->getNameEntryTransformer();

        $result = [];
        foreach ($testDataArray as $testData) {
            $result[] = $nameEntryTransformer->transform($testData);
        }

        $this->assertEquals($expectedDataArray, $result);
    }

}