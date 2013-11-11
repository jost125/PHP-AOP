<?php

namespace Example\Aspect;

use Koala\AOP\Aspect;
use Koala\AOP\Around;
use Koala\AOP\Joinpoint;
use Example\Logger;

/**
 * @Aspect
 */
class ExecutionLogging {

	private $logger;
	
	public function __construct(Logger $logger) {
		$this->logger = $logger;
	}

	/**
	 * @Around("execution(public *::*Action(..))")
	 */
	public function logExecution(Joinpoint $joinpoint) {
		$result = $joinpoint->proceed();

		$this->logger->log(
			sprintf('class: %s, method: %s, arguments: %s, returned: %s',
				$joinpoint->getClassName(),
				$joinpoint->getMethodName(),
				serialize($joinpoint->getArguments()),
				serialize($result)
			),
			'info'
		);

		return $result;
	}
}
