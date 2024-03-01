<?php

namespace Phenomine\Support\Database;

use Phenomine\Contracts\Migration\Blueprint;
use Phenomine\Contracts\Migration\ColumnType;

class Migration
{
    protected $table;
    private $columns = [];

    private function checkColumn($name)
    {
        if (array_key_exists($name, $this->columns)) {
            throw new \Exception("Column $name already exists in table $this->table");
        }
    }

    private function addColumn($name, $type, $length = null)
    {
        $this->checkColumn($name);
        $blueprint = new Blueprint($name, $type, $length);
        $this->columns[] = $blueprint;

        return $this->column($name);
    }

    private function column($name)
    {
        foreach ($this->columns as $column) {
            if ($column->name == $name) {
                return $column;
            }
        }
    }

    public function id()
    {
        return $this->addColumn('id', ColumnType::BIGINT, 20)
            ->unsigned()
            ->autoIncrement()
            ->primary();
    }

    public function string($name, $length = 255)
    {
        return $this->addColumn($name, ColumnType::STRING, $length);
    }

    public function integer($name, $length = 12)
    {
        return $this->addColumn($name, ColumnType::INTEGER, $length);
    }

    public function char($name, $length = 1)
    {
        return $this->addColumn($name, ColumnType::CHAR, $length);
    }

    public function bigint($name, $length = 20)
    {
        return $this->addColumn($name, ColumnType::BIGINT, $length);
    }

    public function date($name)
    {
        return $this->addColumn($name, ColumnType::DATE);
    }

    public function datetime($name)
    {
        return $this->addColumn($name, ColumnType::DATETIME);
    }

    public function time($name)
    {
        return $this->addColumn($name, ColumnType::TIME);
    }

    public function timestamp($name)
    {
        return $this->addColumn($name, ColumnType::TIMESTAMP);
    }

    public function boolean($name)
    {
        return $this->addColumn($name, ColumnType::BOOLEAN);
    }

    public function decimal($name, $length = [10, 2])
    {
        return $this->addColumn($name, ColumnType::DECIMAL, $length);
    }

    public function double($name, $length = [10, 2])
    {
        return $this->addColumn($name, ColumnType::DOUBLE, $length);
    }

    public function float($name, $length = [10, 2])
    {
        return $this->addColumn($name, ColumnType::FLOAT, $length);
    }

    public function text($name)
    {
        return $this->addColumn($name, ColumnType::TEXT);
    }

    public function dropTableIfExists()
    {
        db()->drop($this->table);
    }

    public function getColumns()
    {
        $query = [];
        foreach ($this->columns as $column) {
            $query[$column->name] = $column->getColumnQuery();
        }

        return $query;
    }

    public function getTable()
    {
        return $this->table;
    }
}
