<?php

namespace Grimm\Values;

class NameSet {

    /**
     * @var string|null
     */
    protected $last_name = null;

    /**
     * @var string|null
     */
    protected $first_name = null;

    /**
     * @var string|null
     */
    protected $birth_name = null;

    /**
     * @var string|null
     */
    protected $widowed = null;

    /**
     * @var NameSet|null
     */
    protected $in_name_of = null;

    /**
     * @var NameSet|null
     */
    protected $as_member_of = null;

    /**
     * @var bool
     */
    protected $is_organisation = false;

    /**
     * @param $last_name
     * @param null $first_name
     * @param null $birth_name
     * @param null $widowed
     * @param null $in_name_of
     * @param null $as_member_of
     */
    public function __construct($last_name, $first_name = null, $birth_name = null, $widowed = null, $in_name_of = null, $as_member_of = null)
    {
        $this->last_name = $last_name;
        $this->first_name = $first_name;
        $this->birth_name = $birth_name;
        $this->widowed = $widowed;
        $this->in_name_of = $in_name_of;
        $this->as_member_of = $as_member_of;
    }

    /**
     * @return null|string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @return null|string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @return null|string
     */
    public function getBirthName()
    {
        return $this->birth_name;
    }

    /**
     * @return null|string
     */
    public function getWidowed()
    {
        return $this->widowed;
    }

    /**
     * @return null|string
     */
    public function getInNameOf()
    {
        return $this->in_name_of;
    }

    /**
     * @return null|string
     */
    public function getAsMemberOf()
    {
        return $this->as_member_of;
    }
}