<?php

namespace Phenomine\Support;

#[\AllowDynamicProperties]
class Model {

    protected $table;
    protected $primaryKey = 'id';
    protected $primaryType = 'int';
    protected $fillable = [];
    protected $hidden = [];

    private $first = false;

    protected $data;

    public function __construct() {
        $name = strtolower((new \ReflectionClass($this))->getShortName());
        $name = ngettext($name, $name.'s', 2);
        $this->table = $this->table ?? $name;
    }

    public function __get($name) {
        return $this->data->$name ?? null;
    }

    public function __set($name, $value) {
        if (!$this->data) {
            $this->data = new Collection();
        }

        if ($this->first) {
            $this->data->$name = $value;
        } else {
            $new_arr = [];
            $arr = $this->data->toArray();
            foreach($arr as $data) {
                if (is_array($data)) {
                    $data[$name] = $value;
                    $new_arr[] = $data;
                } else {
                    $data->$name = $value;
                    $new_arr[] = $data;
                }
            }

            $this->data = new Collection($new_arr);
        }
    }

    public static function all() {
        $model = new static;
        $model->data = db()->select($model->table, '*');
        $model->data = new Collection($model->data);
        return $model->data;
    }

    public static function find($id) {
        $model = new static;
        $model->data = db()->select($model->table, '*', [$model->primaryKey => $id]);
        $model->data = new Collection($model->data);
        $model->data = $model->data->first();
        return $model;
    }

    public static function create($data) {
        $model = new static;

        // only fillable allowed
        $data = array_filter($data, function($key) use ($model) {
            return in_array($key, $model->fillable);
        }, ARRAY_FILTER_USE_KEY);

        $result = db()->insert($model->table, $data, $model->primaryKey);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function first() {
        $this->first = true;
        $this->data = $this->data->first();
        return $this;
    }

    public static function where($column, $value) {
        $model = new static;
        $model->data = db()->select($model->table, '*', [$column => $value]);
        $model->data = new Collection($model->data);
        return $model;
    }

    public function get() {
        return $this->data;
    }

    public function update($data) {
        $arr_data = $this->data->toArray();
        foreach($arr_data as $data_want_to_update) {
            if (is_array($data_want_to_update)) {
                db()->update($this->table, $data, [$this->primaryKey => $data_want_to_update[$this->primaryKey]]);
            } else {
                db()->delete($this->table, $data, [$this->primaryKey => $data_want_to_update]);
            }
        }
        return true;
    }

    public function delete() {
        $arr_data = $this->data->toArray();
        foreach($arr_data as $data) {
            if (is_array($data)) {
                db()->delete($this->table, [$this->primaryKey => $data[$this->primaryKey]]);
            } else {
                db()->delete($this->table, [$this->primaryKey => $data]);
            }
        }
        return true;
    }

    public static function destroy($id) {
        $model = new static;
        return db()->delete($model->table, [$model->primaryKey => $id]);
    }

    public function save() {
        $data = $this->data->toArray();
        if ($this->first) {
            if (isset($data[$this->primaryKey])) {
                $id = $data[$this->primaryKey];
                db()->update($this->table, $data, [$this->primaryKey => $id]);
            } else {
                db()->insert($this->table, $data, $this->primaryKey);
            }
        } else {
            foreach($data as $d) {
                if (isset($d[$this->primaryKey])) {
                    $id = $d[$this->primaryKey];
                    db()->update($this->table, $d, [$this->primaryKey => $id]);
                } else {
                    db()->insert($this->table, $d, $this->primaryKey);
                }
            }
        }
    }

    public function __call($method, $parameters) {
        return db()->$method($this->table, ...$parameters);
    }

    public static function __callStatic($method, $parameters) {
        return db()->$method((new static)->table, ...$parameters);
    }

    public function __toString() {
        return json_encode($this->data);
    }

    public function toArray() {
        return (array) $this->data;
    }

    public function toJson() {
        return json_encode($this->data);
    }

    public function __invoke() {
        return $this->data;
    }
}
