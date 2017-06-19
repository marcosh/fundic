<?php

declare(strict_types=1);

namespace FundicTest\DataStructure;

use Fundic\DataStructure\Dictionary;
use Fundic\DataStructure\Maybe;
use PHPUnit\Framework\TestCase;

final class DictionaryTest extends TestCase
{
    public function testEmptyDoesNotRegisterAnyKey() : void
    {
        $dictionary = Dictionary::empty();

        $reflectionDictionary = new \ReflectionObject($dictionary);
        $valuesProperty = $reflectionDictionary->getProperty('values');
        $valuesProperty->setAccessible(true);

        self::assertEmpty($valuesProperty->getValue($dictionary));
    }

    public function testAddRegistersNewKey() : void
    {
        $dictionary = Dictionary::empty();

        $dictionary = $dictionary->add('gigi', 73);

        self::assertTrue($dictionary->has('gigi'));
        self::assertEquals(Maybe::just(73), $dictionary->get('gigi'));
    }

    public function testAddPreservesImmutability() : void
    {
        $dictionary = Dictionary::empty();

        $dictionary->add('gigi', 73);

        self::assertFalse($dictionary->has('gigi'));
    }
}
