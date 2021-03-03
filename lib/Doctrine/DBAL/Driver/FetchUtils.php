<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Driver;

use Doctrine\DBAL\Exception\NoKeyValue;
use Traversable;

/**
 * @internal
 */
final class FetchUtils
{
    /**
     * @return mixed|false
     *
     * @throws Exception
     */
    public static function fetchOne(Result $result)
    {
        $row = $result->fetchNumeric();

        if ($row === false) {
            return false;
        }

        return $row[0];
    }

    /**
     * @return array<int,array<int,mixed>>
     *
     * @throws Exception
     */
    public static function fetchAllNumeric(Result $result): array
    {
        $rows = [];

        while (($row = $result->fetchNumeric()) !== false) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * @return array<int,array<string,mixed>>
     *
     * @throws Exception
     */
    public static function fetchAllAssociative(Result $result): array
    {
        $rows = [];

        while (($row = $result->fetchAssociative()) !== false) {
            $rows[] = $row;
        }

        return $rows;
    }
    /**
     * Returns an array containing the values of the first column of the result.
     *
     * @return array<mixed,mixed>
     *
     * @throws Exception
     */
    public static function fetchAllAssociativeIndexed(Result $result): array
    {
        $data = [];

        foreach ($result->fetchAllAssociative() as $row) {
            $data[array_shift($row)] = $row;
        }

        return $data;
    }

    /**
     * Returns an associative array with the keys mapped to the first column and the values being
     * an associative array representing the rest of the columns and their values.
     *
     * @return array<mixed,array<string,mixed>>
     *
     * @throws Exception
     */
    public static function fetchAllKeyValue(Result $result): array
    {
        self::ensureHasKeyValue($result);
        $data = [];

        foreach ($result->fetchAllNumeric() as [$key, $value]) {
            $data[$key] = $value;
        }

        return $data;
    }

    /**
     * @return array<int,mixed>
     *
     * @throws Exception
     */
    public static function fetchFirstColumn(Result $result): array
    {
        $rows = [];

        while (($row = $result->fetchOne()) !== false) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * @return Traversable<int,array<int,mixed>>
     *
     * @throws Exception
     */
    public static function iterateNumeric(Result $result): Traversable
    {
        while (($row = $result->fetchNumeric()) !== false) {
            yield $row;
        }
    }

    /**
     * @return Traversable<int,array<string,mixed>>
     *
     * @throws Exception
     */
    public static function iterateAssociative(Result $result): Traversable
    {
        while (($row = $result->fetchAssociative()) !== false) {
            yield $row;
        }
    }

    /**
     * @return Traversable<int,mixed>
     *
     * @throws Exception
     */
    public static function iterateColumn(Result $result): Traversable
    {
        while (($value = $result->fetchOne()) !== false) {
            yield $value;
        }
    }

    private static function ensureHasKeyValue(Result $result): void
    {
        $columnCount = $result->columnCount();

        if ($columnCount < 2) {
            throw NoKeyValue::fromColumnCount($columnCount);
        }
    }
}
