<?php
declare(strict_types=1);

namespace App\Application\Exception;

use Throwable;

/**
 * Class ValidationException
 * @package App\Application
 */
class ValidationException extends ApplicationException
{
    /**
     * @var array
     */
    protected $errors;

    /**
     * ValidationException constructor.
     * @param string $message
     * @param int $code
     * @param array $errors
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 400, ?Throwable $previous = null, array $errors = [])
    {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return string
     */
    public function getErrorsAsString(): string
    {
        $result = '';
        foreach ($this->errors as $key => $values) {
            $result .= sprintf('[%s: %s]', $key, implode(' ', $values));
        }

        return $result;
    }
}
