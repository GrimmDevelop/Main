<?php

use Grimm\Transformer\NameEntry;
use Grimm\Values\NameSet;

class NameEntryTransformerTest extends PHPUnit_Framework_TestCase {

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
            new NameSet('Lastname', 'Firstname Secondfirstname'),
            new NameSet('Lastname Secondlastname', 'Firstname'),
            new NameSet('Lastname Secondlastname', 'Firstname Secondfirstname'),
            new NameSet('Onlylastname'),
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
            'Lastname1, Firstname1 Secondfirstname1 ; Lastname2, Firstname2 ;Lastname3 ; Lastname4, Firstname4',
        ];
        $expectedDataArray = [
            [
                new NameSet('Lastname1', 'Firstname1'),
                new NameSet('Lastname2', 'Firstname2')
            ],
            [
                new NameSet('Lastname1', 'Firstname1 Secondfirstname1'),
                new NameSet('Lastname2', 'Firstname2'),
                new NameSet('Lastname3'),
                new NameSet('Lastname4', 'Firstname4'),
            ]
        ];

        $names = [];
        $result = [];
        for ($i = 1; $i <= 10; $i ++) {
            $names[] = "Lastname$i, Firstname$i";
            $result[] = new NameSet("Lastname$i", "Firstname$i");
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

    /**
     * @ignore
     */
    public function testInNameOf()
    {
        $testName = 'Lastname, Firstname ;> Beliebige Institution';
        $expectedResult = new NameSet('Lastname', 'Firstname', null, null, new NameSet('Beliebige Institution'));

        $result = $this->getNameEntryTransformer()->extractData($testName);

        $this->markTestSkipped();

        $this->assertEquals($expectedResult, $result);
    }
}