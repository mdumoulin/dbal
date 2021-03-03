<?php
namespace Doctrine\Tests\DBAL\Driver;

use Doctrine\DBAL\Cache\ArrayStatement;
use Doctrine\DBAL\Driver\FetchUtils;
use Doctrine\DBAL\Driver\Result;
use PHPUnit\Framework\TestCase;

class FetchUtilsTest extends TestCase
{
    /**
     * @var Result
     */
    private $result;

    public function setUp(): void
    {
        $this->result = new ArrayStatement([
            [
                'row1col1' => 'row1col1value',
                'row1col2' => 'row1col2value',
                'row1col3' => 'row1col3value',
            ],
            [
                'row2col1' => 'row2col1value',
                'row2col2' => 'row2col2value',
                'row2col3' => 'row2col3value',
            ],
            [
                'row3col1' => 'row3col1value',
                'row3col2' => 'row3col2value',
                'row3col3' => 'row3col3value',
            ],
        ]);
    }

    public function testFetchAllAssociativeIndexed(): void
    {
        $this->assertSame(
            [
                'row1col1value' => [
                    'row1col2' => 'row1col2value',
                    'row1col3' => 'row1col3value',
                ],
                'row2col1value' => [
                    'row2col2' => 'row2col2value',
                    'row2col3' => 'row2col3value',
                ],
                'row3col1value' => [
                    'row3col2' => 'row3col2value',
                    'row3col3' => 'row3col3value',
                ],
            ],
            FetchUtils::fetchAllAssociativeIndexed($this->result)
        );
    }

    public function testFetchAllKeyValue(): void
    {
        $this->assertSame(
            [
                'row1col1value' => 'row1col2value',
                'row2col1value' => 'row2col2value',
                'row3col1value' => 'row3col2value',
            ],
            FetchUtils::fetchAllKeyValue($this->result)
        );
    }

    public function testIterateNumeric(): void
    {
        $this->assertSame(
            [
                [
                    0 => 'row1col1value',
                    1 => 'row1col2value',
                    2 => 'row1col3value',
                ],
                [
                    0 => 'row2col1value',
                    1 => 'row2col2value',
                    2 => 'row2col3value',
                ],
                [
                    0 => 'row3col1value',
                    1 => 'row3col2value',
                    2 => 'row3col3value',
                ],
            ],
            iterator_to_array(FetchUtils::iterateNumeric($this->result))
        );
    }

    public function testIterateAssociative(): void
    {
        $this->assertSame(
            [
                [
                    'row1col1' => 'row1col1value',
                    'row1col2' => 'row1col2value',
                    'row1col3' => 'row1col3value',
                ],
                [
                    'row2col1' => 'row2col1value',
                    'row2col2' => 'row2col2value',
                    'row2col3' => 'row2col3value',
                ],
                [
                    'row3col1' => 'row3col1value',
                    'row3col2' => 'row3col2value',
                    'row3col3' => 'row3col3value',
                ],
            ],
            iterator_to_array(FetchUtils::iterateAssociative($this->result))
        );
    }

    public function testIterateColumn(): void
    {
        $this->assertSame(
            [
                'row1col1value',
                'row2col1value',
                'row3col1value',
            ],
            iterator_to_array(FetchUtils::iterateColumn($this->result))
        );
    }
}
