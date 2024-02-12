<?php

class Database
{
    private static $instance = null;
    private $pdo, $query, $error = false, $results, $count;

    private function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=" . Config::get('mysql.host') . ";dbname=" . Config::get('mysql.database'), Config::get('mysql.username'), Config::get('mysql.password'));
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance()
    {

        if (!isset(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function query($sql, $params = [])
    {
        $this->error = false;
        $this->query = $this->pdo->prepare($sql);

        if (count($params)) {
            $i = 1;
            foreach ($params as $param) {

                $this->query->bindValue($i, $param);
                $i++;
            }
        }
        //$this->query->bindValue(1, $params[0]);

        if (!$this->query->execute()) {
            $this->error = true;
        } else {
            $this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
            $this->count = $this->query->rowCount();
        }
        return $this;
    }

    public function error()
    {
        return $this->error;
    }

    public function results()
    {
        return $this->results;
    }

    public function count()
    {
        return $this->count;
    }

    public function get($table, $where = [])
    {
        return $this->action('SELECT *', $table, $where);

    }

    public function delete($table, $where = [])
    {
        return $this->action('DELETE', $table, $where);
    }

    public function action($action, $table, $where = [])
    {
        if (count($where) === 3) {
            $operators = ['=', '>', '<', '>=', '<='];
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field}{$operator}?";
                if (!$this->query($sql, [$value])->error()) {
                    return $this;
                }
            }

        }
        return false;
    }

    public function insert($table, $fields = [])
    {
        $keys = array_keys($fields);
        $values = '';
        foreach ($fields as $field) {
            $values .= "?,";
        }
        $values = rtrim($values, ',');

        $sql = "INSERT INTO {$table} (" . '`' . implode('`, `', $keys) . '`' . ") VALUES ({$values})";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }

    public function update($table, $id, $fields = [])
    {
        $set = '';
        foreach ($fields as $key => $field) {
            $set .= "$key = ?,";// , берет ключ ставит запятую и на след итерации добавляет след ключ
                                    //username, password и тд

        }
        $set = rtrim($set, ',');
        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";


        if (!$this->query($sql, $fields)->error()){
            return true;
        }
        return false;

    }
    public function first(){
       return $this->results();
    }
}