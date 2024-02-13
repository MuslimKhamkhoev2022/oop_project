<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($fields = [])
    {
        $this->db->insert('users', $fields);
    }

    public function login($email = null, $password = null)
    {
        if ($email) {
            $user = $this->db->get('users', ['email', '=', $email])->first();
            var_dump($user->password);
            exit;
        }
    }
}