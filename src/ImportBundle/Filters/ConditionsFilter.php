<?php
/**
 *  * Created by PhpStorm.
 * User: litr4
 * Date: 23.7.16
 * Time: 19.21
 */

namespace ImportBundle\Filters;

use Ddeboer\DataImport\Exception\WriterException;

class ConditionsFilter extends Filter
{

    const COST_MIN_TRESHOLD = 5; //min items price to import
    const COST_MAX_TRESHOLD = 1000; //max items price to import
    const STOCK_MIN_TRESHOLD = 10; //min items stock amount to import

    /**@var \Closure*/
    protected $conditionsCallback;

    /**@var string*/
    protected $message;

    /**
     * ConditionsFilter constructor.
     * @param string $name
     * @param \Closure $conditionCallback
     * @param string $message
     */
    public function __construct($name, \Closure $conditionCallback, $message)
    {
        parent::__construct($name);
        $this->conditionsCallback = $conditionCallback;
        $this->message = $message;
    }

    /**
     * checks for stock and cost
     *
     * @return \Closure
     */
    public function getCallable()
    {
        return function ($data) {
            $this->checkValue($data);

            if (!$this->isValid($data)) {
                throw new WriterException(
                    str_replace('[name]', $this->getValue($data), $this->message)
                );
            } else {
                $this->addValue($data);
            }

            return true;
        };
    }

    /**
     * @param array $data
     * @return bool
     */
    public function isValid($data)
    {
        $callback = $this->conditionsCallback;

        return $callback($data);
    }
}
