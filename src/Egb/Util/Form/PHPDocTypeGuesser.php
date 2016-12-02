<?php
namespace Egb\Util\Form;

use Symfony\Component\Form\Guess;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormTypeGuesserInterface;

class PHPDocTypeGuesser implements FormTypeGuesserInterface {
	private static $cache;
	public function guessType($class, $property) {
		// read annotations
		$type = $this->readPhpDocVarAnnotation($class, $property);
		// try to guess the type on the @var annotation...
		switch ($type) {
			case 'string':
				// there is a high confidence that the type is text when
				// @var string is used
				return new Guess\TypeGuess(Type\TextType::class, array(), Guess\Guess::HIGH_CONFIDENCE);

			case 'int':
			case 'integer':
				// integers can also be the id of an entity or a checkbox (0 or 1)
				return new Guess\TypeGuess(Type\IntegerType::class, array(), Guess\Guess::MEDIUM_CONFIDENCE);

			case 'float':
			case 'double':
			case 'real':
				return new Guess\TypeGuess(Type\NumberType::class, array(), Guess\Guess::MEDIUM_CONFIDENCE);

			case 'boolean':
			case 'bool':
				return new Guess\TypeGuess(Type\CheckboxType::class, array(), Guess\Guess::HIGH_CONFIDENCE);
			default:
				// there is a very low confidence that this one is correct
				return new Guess\TypeGuess(Type\TextType::class, array(), Guess\Guess::LOW_CONFIDENCE);
		}
	}

	public function guessPattern($class, $property) {
		return new Guess\ValueGuess(null, Guess\Guess::LOW_CONFIDENCE);
	}

	public function guessMaxLength($class, $property) {
		return new Guess\ValueGuess(null, Guess\Guess::LOW_CONFIDENCE);
	}

	public function guessRequired($class, $property) {
		return new Guess\ValueGuess(true, Guess\Guess::LOW_CONFIDENCE);
	}


	protected function readPhpDocVarAnnotation($class, $property) {
		if (!isset(self::$cache[$class])) self::$cache[$class] = array();
		if (!isset(self::$cache[$class][$property])) {
			$reflectionProperty = new \ReflectionProperty($class, $property);
			$propertyComment = $reflectionProperty->getDocComment();
			if (!$propertyComment) return 'mixed';
			self::$cache[$class][$property] = (false !== strpos($propertyComment, '@var') && preg_match('/@var\s+([^\s]+)/', $propertyComment, $matches)) ? $matches[1] : 'mixed';
		}
		return self::$cache[$class][$property];
	}

	// ...
}
