<?php

namespace AOP\AspectServiceFilter;

use AOP\TestCase;
use ReflectionClass;

require_once __DIR__ . '/PhpNativeAspectServiceFilterTest/FooAspect.php';
require_once __DIR__ . '/PhpNativeAspectServiceFilterTest/Foo.php';

class PhpNativeAspectServiceFilterTest extends TestCase {

	/** @var PhpNativeAspectServiceFilter */
	private $phpNativeAspectServiceFilter;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $annotationResolverMock;

	protected function setUp() {
		$this->annotationResolverMock = $this->createMock('\Reflection\AnnotationResolver');
		$this->phpNativeAspectServiceFilter = new PhpNativeAspectServiceFilter(
			$this->annotationResolverMock
		);
	}

	public function testFilterAspectServices() {
		require_once __DIR__ . '/PhpNativeAspectServiceFilterTest/Foo.php';
		require_once __DIR__ . '/PhpNativeAspectServiceFilterTest/FooAspect.php';

		/** @var $serviceDefinitions \DI\Definition\ServiceDefinition[] */
		$serviceDefinitions = array(
			'fooService' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(
				array(
					'serviceId' => 'fooService',
					'class' => '\AOP\AspectServiceFilter\PhpNativeAspectServiceFilterTest\Foo',
					'arguments' => array()
				)
			),
			'fooAspectService' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(
				array(
					'serviceId' => 'fooAspectService',
					'class' => '\AOP\AspectServiceFilter\PhpNativeAspectServiceFilterTest\FooAspect',
					'arguments' => array()
				)
			)
		);

		$expectedAspectServices = array(
			'fooAspectService' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(
				array(
					'serviceId' => 'fooAspectService',
					'class' => '\AOP\AspectServiceFilter\PhpNativeAspectServiceFilterTest\FooAspect',
					'arguments' => array()
				)
			)
		);

		$this->annotationResolverMock->expects($this->at(0))
			->method('hasClassAnnotation')
			->with(new ReflectionClass('\AOP\AspectServiceFilter\PhpNativeAspectServiceFilterTest\Foo'))
			->will($this->returnValue(false));

		$this->annotationResolverMock->expects($this->at(1))
			->method('hasClassAnnotation')
			->with(new ReflectionClass('\AOP\AspectServiceFilter\PhpNativeAspectServiceFilterTest\FooAspect'))
			->will($this->returnValue(true));

		$filteredAspectServices = $this->phpNativeAspectServiceFilter->filterAspectServices($serviceDefinitions);

		$this->assertEquals($expectedAspectServices, $filteredAspectServices);
	}

}
