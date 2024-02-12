<?php

class User
{
    private $db, $data, $session_name, $isLoggedIn;

    public function __construct($user = null)
    {
        $this->db = Database::getInstance();
        $this->session_name = Config::get('session.user_session');

        if (!$user) {
            if (Session::exists($this->session_name)) {
                $user = Session::get($this->session_name);

                if ($this->find($user)){
                    $this->isLoggedIn = true;
                }
            }
        } else {
            $this->find($user);
        }
    }

    public function create($fields = [])
    {
        $this->db->insert('users', $fields);
    }

    public function login($email = null, $password = null): bool
    {
        if ($email){
            $user = $this->find($email);
            if ($user) {
                if (password_verify($password, $this->data()->password)) {
                    Session::put($this->session_name, $this->data()->id);
                    return true;
                }
            }
        }
        return false;
    }

    public function find($value = null)
    {
        if (is_numeric($value)){
            $this->data = $this->db->get('users', ['id', '=', $value])->first();
        }else{
            $this->data = $this->db->get('users', ['email', '=', $value])->first();
        }

        if ($this->data) {
            return true;
        }
        return false;
    }

    public function data()
    {
        return $this->data;
    }

    public function isLoggedIn(): true
    {
        return $this->isLoggedIn;
    }

}