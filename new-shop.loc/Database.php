<?php

class  Database
{
    private static $instance = null;
    private $pdo, $query, $error= false;

    private function __construct()
    {
        try {
           $this->pdo = new PDO('mysql:host=localhost;dbname=users', 'root', 'root');
        } catch (Exception $exception) {
            die($exception->getMessage());
        }
    }
    public static function getInstance()// с помощью метода создаем подклчюение к базе
    {
        if (!isset(self::$instance)){ //проверяем если свойство пустое создаем объект database тем самым подлючаемся к базе только один раз
            self::$instance =  new Database();
        }
        return self::$instance;

    }
    public function query($sql)
    {
        $this->error = false; // по умолчанию переменая error пустая
        $this->query = $this->pdo->prepare($sql);// подготовливаем запрос
        if (!$this->query->execute()){ // если запрос не выполнимся записываем в пре еную ошибку
            $this->error = true;
        }
        $result = $this->query->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    public function error()
    {
        $this->error; //при вызове возвращает св error

    }

}