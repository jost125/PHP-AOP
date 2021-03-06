<?php

namespace Koala\Reflection\Annotation\Parsing;

use Koala\Reflection\Annotation\Annotation;

class SimpleAnnotationExpressionMatcher implements AnnotationExpressionMatcher {

	/**
	 * @param AnnotationExpression $annotationExpression
	 * @param Annotation $annotation
	 * @return string
	 */
	public function match(AnnotationExpression $annotationExpression, Annotation $annotation) {
		$regexp = $this->toRegexp($annotationExpression);
		return (bool)preg_match($regexp, $annotation->toExpression());
	}

	private function toRegexp(AnnotationExpression $annotationExpression) {
		$expression = $annotationExpression->getExpression();
		$regexp = ltrim($expression, '\\');
		$regexp = preg_quote($regexp, '~');
		$regexp = preg_replace('~\\\\\(\\\\\.\\\\\.\\\\\)$~', '\(.*\)', $regexp);

		return '~^' . $regexp . '$~s';
	}
}
