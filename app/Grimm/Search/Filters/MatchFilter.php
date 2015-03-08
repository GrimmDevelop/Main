<?php


namespace Grimm\Search\Filters;


use Illuminate\Database\Eloquent\Builder;

class MatchFilter extends BaseFilter {

    /**
     * @var Code
     */
    protected $code;

    /**
     * @var FilterValue
     */
    protected $val;
    protected $matcher;

    protected $matchers = [
        'starts with',
        'ends with',
        'equals',
        'not equals',
        'contains'
    ];

    function __construct(Code $code, $matcher, FilterValue $val)
    {
        $this->code = $code;
        $this->val = $val;

        if (!in_array($matcher, $this->matchers)) {
            throw new \InvalidArgumentException('Invalid matcher provided');
        }

        $this->matcher = $matcher;
    }


    public function compile(Builder $query)
    {
        list ($comparator, $value) = $this->decodeMatcher();
        return $query->whereHas('information', function($q) use ($comparator, $value) {
            $q->where('code', $this->code->get())->where('data', $comparator, $value);
        });
    }

    private function decodeMatcher()
    {
        switch ($this->matcher) {
            case 'starts with':
                return ['like', $this->val->get() . '%'];
            case 'ends with':
                return ['like', '%' . $this->val->get()];
            case 'contains':
                return ['like', '%' . $this->val->get() . '%'];
            case 'equals':
                return ['=', $this->val->get()];
            case 'not equals':
                return ['!=', $this->val->get()];
            default:
                return ['=', $this->val->get()];
        }
    }
}