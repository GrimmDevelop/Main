<?php


namespace Grimm\Import\Records;


class LetterRecord implements Record {

    /**
     * The id of the letter
     * @var int
     */
    protected $id;

    /**
     * The code of the letter, which is stored as a big decimal
     * @var string
     */
    protected $code;

    /**
     * The two character language code of the letter
     * @var string
     */
    protected $language;

    /**
     * Human readable date of the letter
     * @var string
     */
    protected $date;

    /**
     * The associated information of a letter
     * TODO: Might be good to extract this into own object
     * @var array
     */
    protected $information;

    function __construct($id, $code, $language, $date, $information)
    {
        $this->id = $id;
        $this->code = trim($code);
        $this->language = $language;
        $this->date = $date;
        $this->information = $information;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id'            => $this->getId(),
            'code'          => $this->getCode(),
            'language'      => $this->getLanguage(),
            'date'          => $this->getDate(),
            'information'   => $this->getInformation()
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return array
     */
    public function getInformation()
    {
        return $this->information;
    }
}