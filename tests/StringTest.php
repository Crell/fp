<?php

declare(strict_types=1);

namespace Crell\fp;

use PHPUnit\Framework\TestCase;

class StringTest extends TestCase
{
    /**
     * @test
     * @dataProvider replaceProvider
     */
    // @phpstan-ignore-next-line
    public function replace(string|array $find, string|array $replace, string $in, string $expected): void
    {
        self::assertEquals($expected, replace($find, $replace)($in));
    }

    /**
     * @return iterable<array>
     */
    public static function replaceProvider(): iterable
    {
        yield [
            'find' => '',
            'replace' => '',
            'in' => 'beep',
            'expected' => 'beep',
        ];
        yield [
            'find' => 'e',
            'replace' => '3',
            'in' => 'beep',
            'expected' => 'b33p',
        ];
        yield [
            'find' => ['hello', 'world'],
            'replace' => ['goodbye', 'everyone'],
            'in' => 'hello world',
            'expected' => 'goodbye everyone',
        ];
    }

    /**
     * @test
     * @dataProvider implodeProvider
     *
     * @param string $glue
     * @param array $in
     * @param string $expected
     */
    // @phpstan-ignore-next-line
    public function implode(string $glue, array $in, string $expected): void
    {
        self::assertEquals($expected, implode($glue)($in));
    }

    /**
     * @return iterable<array>
     */
    public static function implodeProvider(): iterable
    {
        yield [
            'glue' => '',
            'in' => array('b', 'e', 'e', 'p'),
            'expected' => 'beep',
        ];
        yield [
            'glue' => '-',
            'in' => array('b', 'e', 'e', 'p'),
            'expected' => 'b-e-e-p',
        ];
    }

    /**
     * @test
     * @dataProvider explodeProvider
     *
     * @param non-empty-string $delimiter
     * @param string $in
     * @param array<mixed> $expected
     */
    public function explode(string $delimiter, string $in, array $expected): void
    {
        self::assertEquals($expected, explode($delimiter)($in));
    }

    /**
     * @return iterable<array>
     */
    public static function explodeProvider(): iterable
    {
        yield [
            'delimiter' => '-',
            'in' => 'b-e-e-p',
            'expected' => array('b', 'e', 'e', 'p'),
        ];
    }
}
