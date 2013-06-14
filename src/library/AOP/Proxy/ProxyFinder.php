<?php

namespace AOP\Proxy;

use AOP\Abstraction\ProxyList;
use DI\Definition\Configuration\ServiceDefinition;

interface ProxyFinder {

	/**
	 * @param ServiceDefinition[] $aspectDefinitions
	 * @param ServiceDefinition[] $targetDefinitions
	 * @return ProxyList
	 */
	public function findProxies(array $aspectDefinitions, array $targetDefinitions);
}