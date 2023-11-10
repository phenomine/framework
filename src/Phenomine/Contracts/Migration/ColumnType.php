<?php

namespace Phenomine\Contracts\Migration;

class ColumnType
{
    // String Types
    public const TEXT = 'TEXT';
    public const STRING = 'VARCHAR';
    public const CHAR = 'CHAR';

    // Numeric Types
    public const BIGINT = 'BIGINT';
    public const BOOLEAN = 'BOOL';
    public const DECIMAL = 'DECIMAL';
    public const DOUBLE = 'DOUBLE';
    public const FLOAT = 'FLOAT';
    public const INTEGER = 'INT';

    // Date Types
    public const DATE = 'DATE';
    public const DATETIME = 'DATETIME';
    public const TIME = 'TIME';
    public const TIMESTAMP = 'TIMESTAMP';
}
