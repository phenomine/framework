<?php

namespace Phenomine\Contracts\Migration;

class Blueprint {

    public $name;

    public $type;

    public $length;

    public $nullable = false;

    public $default = null;

    public $autoIncrement = false;

    public $unsigned = false;

    public $unique = false;

    public $primary = false;

    public $comment = null;

    public function __construct($name, $type, $length = null) {
        $this->name = $name;
        $this->type = $type;
        $length ? $this->length = $length : null;

        return $this;
    }

    public function nullable() {
        $this->nullable = true;

        return $this;
    }

    public function default($value) {
        $this->default = $value;

        return $this;
    }

    public function autoIncrement() {
        $this->autoIncrement = true;

        return $this;
    }

    public function unsigned() {
        $this->unsigned = true;

        return $this;
    }

    public function unique() {
        $this->unique = true;

        return $this;
    }

    public function primary() {
        $this->primary = true;

        return $this;
    }

    public function comment($value) {
        $this->comment = $value;

        return $this;
    }

    public function __toString() {
        return $this->name;
    }

    public function getColumnQuery() {

        $query = [];

        $type = $this->type;
        if ($this->length) {
            $type .= "({$this->length})";
        }

        $query[] = $type;

        if ($this->unsigned) {
            $query[] = 'UNSIGNED';
        }
        
        $query[] = $this->nullable ? 'NULL' : 'NOT NULL';

        if ($this->default) {
            $query[] = "DEFAULT '{$this->default}'";
        }

        if ($this->autoIncrement) {
            $query[] = 'AUTO_INCREMENT';
        }

        if ($this->unique) {
            $query[] = 'UNIQUE';
        }

        if ($this->primary) {
            $query[] = 'PRIMARY KEY';
        }

        if ($this->comment) {
            $query[] = "COMMENT '{$this->comment}'";
        }

        return $query;
    }

}
