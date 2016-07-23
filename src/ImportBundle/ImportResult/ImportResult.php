<?php
/**
 * Created by PhpStorm.
 * User: Sergey Folitar
 * Date: 7/22/16
 * Time: 7:06 PM
 */

namespace ImportBundle\ImportResult;

/**
 * Class ImportResult
 * @package ImportBundle\ImportResult
 */
class ImportResult
{
    /**
     * @var string
     */
    protected $endTime;
    /**
     * @var array
     */
    protected $errors;
    /**
     * @var \SplObjectStorage
     */
    protected $exceptions;
    /**
     * @var int
     */
    protected $successCount;

    /**
     * @return string
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @param string $endTime
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return \SplObjectStorage
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }

    /**
     * @param \SplObjectStorage $exceptions
     */
    public function setExceptions(\SplObjectStorage $exceptions)
    {
        $this->exceptions = $exceptions;
    }

    /**
     * @return int
     */
    public function getSuccessCount()
    {
        return $this->successCount;
    }

    /**
     * @param int $successCount
     */
    public function setSuccessCount($successCount)
    {
        $this->successCount = $successCount;
    }

    /**
     * @return int
     */
    public function getCountErrors()
    {
        return count($this->errors) + count($this->exceptions);
    }
}
