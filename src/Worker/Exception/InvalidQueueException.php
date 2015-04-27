<?php

namespace SlmQueueAmq\Worker\Exception;

use SlmQueueAmq\Exception\ExceptionInterface;
use InvalidArgumentException;

class InvalidQueueException extends InvalidArgumentException implements ExceptionInterface
{

}
