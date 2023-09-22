<?php

/*
| The Phenomine Framework
| Copyright (c) 2023 Muhammad Fahli Saputra
| https://github.com/phenomine/framework
|
*/

namespace Phenomine\Support;

use PDO;
use PDOException;

class DB
{
    protected $host;
    protected $username;
    protected $password;
    protected $database;
    protected $port;

    protected $connection;

    public $connected = false;

    private $errors = true;

    public function __construct()
    {
        try {
            $this->host = config('database.host');
            $this->database = config('database.database');
            $this->username = config('database.username');
            $this->password = config('database.password');
            $this->port = config('database.port');
            $this->connected = true;

            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database . ";port=" . $this->port, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->connected = false;
            if ($this->errors === true) {
                return $this->error($e->getMessage());
            } else {
                return false;
            }
        }
    }

    function __destruct()
    {
        $this->connected = false;
        $this->connection = null;
    }

    public function error($error)
    {
        echo $error;
    }

    public function fetch($query, $parameters = array())
    {
        if ($this->connected === true) {
            try {
                $query = $this->connection->prepare($query);
                $query->execute($parameters);
                return $query->fetch();
            } catch (PDOException $e) {
                if ($this->errors === true) {
                    return $this->error($e->getMessage());
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function fetchAll($query, $parameters = array())
    {
        if ($this->connected === true) {
            try {
                $query = $this->connection->prepare($query);
                $query->execute($parameters);
                return $query->fetchAll();
            } catch (PDOException $e) {
                if ($this->errors === true) {
                    return $this->error($e->getMessage());
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function count($query, $parameters = array())
    {
        if ($this->connected === true) {
            try {
                $query = $this->connection->prepare($query);
                $query->execute($parameters);
                return $query->rowCount();
            } catch (PDOException $e) {
                if ($this->errors === true) {
                    return $this->error($e->getMessage());
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function insert($query, $parameters = array())
    {
        if ($this->connected === true) {
            try {
                $query = $this->connection->prepare($query);
                $query->execute($parameters);
            } catch (PDOException $e) {
                if ($this->errors === true) {
                    return $this->error($e->getMessage());
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function update($query, $parameters = array())
    {
        if ($this->connected === true) {
            return $this->insert($query, $parameters);
        } else {
            return false;
        }
    }

    public function delete($query, $parameters = array())
    {
        if ($this->connected === true) {
            return $this->insert($query, $parameters);
        } else {
            return false;
        }
    }

    public function tableExists($table)
    {
        if ($this->connected === true) {
            try {
                $query = $this->count("SHOW TABLES LIKE '$table'");
                return ($query > 0) ? true : false;
            } catch (PDOException $e) {
                if ($this->errors === true) {
                    return $this->error($e->getMessage());
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
}
