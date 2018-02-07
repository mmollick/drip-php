<?php

namespace MMollick\Drip\Errors;

use Throwable;

class ApiException extends GeneralException
{
    /**
     * @var array
     */
    protected $errors;

    /**
     * ApiException constructor.
     * @param $errors
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        $errors,
        $message = '',
        $code = 0,
        Throwable $previous = null
    ) {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
