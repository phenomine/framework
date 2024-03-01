<?php

namespace Phenomine\Support;

#[\AllowDynamicProperties]
class Collection {
    protected $items;

    public function __construct($items = []) {
        $this->items = $items;
        return $this;
    }

    public function __get($name) {
        // remove scalar type
        if (is_array($this->items)) {
            return (object) $this->items;
        } else {
            return $this->items[$name] ?? null;
        }
    }

    public function __set($name, $value) {
        if (!$this->items) {
            $this->items = [];
        }

        $this->items[$name] = $value;
    }

    public function count() {
        return count($this->items);
    }

    public function first() {
        if (empty($this->items)) {
            return null;
        }
        if (is_array($this->items)) {
            $first = reset($this->items);
            if (is_array($first)) {
                return new static($first);
            } else {
                return $first;
            }
        }
        return null;
    }

    public function last() {
        if (empty($this->items)) {
            return null;
        }
        if (is_array($this->items)) {
            $last = end($this->items);
            if (is_array($last)) {
                return new static($last);
            } else {
                return $last;
            }
        }
        return null;
    }

    public function pop() {
        return array_pop($this->items);
    }

    public function shift() {
        return array_shift($this->items);
    }

    public function unshift($item) {
        array_unshift($this->items, $item);
    }

    public function has($key) {
        return isset($this->items[$key]);
    }

    public function remove($key) {
        unset($this->items[$key]);
    }

    public function keys() {
        return array_keys($this->items);
    }

    public function values() {
        return array_values($this->items);
    }

    public function map($callback) {
        return new static(array_map($callback, $this->items));
    }

    public function filter($callback) {
        return new static(array_filter($this->items, $callback));
    }

    public function each($callback) {
        foreach ($this->items as $key => $item) {
            $callback($item, $key);
        }
    }

    public function merge($items) {
        return new static(array_merge($this->items, $items));
    }

    public function toArray() {
        return $this->items;
    }
}
