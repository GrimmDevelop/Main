<?php


use Grimm\Import\Records\LetterRecord;
use Grimm\Import\Validation\LetterValidation;

class LetterValidationTest extends TestCase {

    /**
     * @var LetterValidation
     */
    protected $letterValidation;

    public function setUp()
    {
        parent::setUp();

        $this->letterValidation = new LetterValidation();
    }


    public function testRejectInvalidCode()
    {
        $letter1 = new LetterRecord(6879, '1830.00', '', '15. Juli 1830', []);
        $letter2 = new LetterRecord(13420, '1851132.00', 'sw', 'November 1851', []);
        $letter3 = new LetterRecord(8535, '183701180.00', '', '18. Januar 1837', []);
        $letter4 = new LetterRecord(16230, '18630814.000', '', '14. August 1863', []);
        $letter5 = new LetterRecord(16230, '1863081400', '', '14. August 1863', []);

        $this->assertFalse($this->letterValidation->validate($letter1));
        $this->assertFalse($this->letterValidation->validate($letter2));
        $this->assertFalse($this->letterValidation->validate($letter3));
        $this->assertFalse($this->letterValidation->validate($letter4));
        $this->assertFalse($this->letterValidation->validate($letter5));
    }

    public function testAcceptValidCode()
    {
        $letter1 = new LetterRecord(16230, '18630814.00', '', '14. August 1863', []);
        $letter2 = new LetterRecord(16230, '18630814.01', '', '14. August 1863', []);
        $letter3 = new LetterRecord(16230, '18700000.00', '', '14. August 1863', []);
        $letter4 = new LetterRecord(16230, '18700000', '', '14. August 1863', []);

        $this->assertTrue($this->letterValidation->validate($letter1));
        $this->assertTrue($this->letterValidation->validate($letter2));
        $this->assertTrue($this->letterValidation->validate($letter3));
        $this->assertTrue($this->letterValidation->validate($letter4));
    }

    public function testTest()
    {
        $code = '18511236.11';
        $newCode = preg_replace('/^([0-9]{8}\.1)0([0-9])$/', '$1$2', $code);
        // var_dump($newCode);
    }


}
