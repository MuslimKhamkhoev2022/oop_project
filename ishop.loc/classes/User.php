<?php

class User
{
    private $db, $data, $session_name, $isLoggedIn;

    public function __construct($user = null)
    {
        $this->db = Database::getInstance();
        $this->session_name = Config::get('session.user_session');

        if (!$user) {
            if (Session::exists($this->session_name)){
            $user = Session::get($this->session_name);

            if ($this->find($user)) {
                $this->isLoggedIn = true;
            } else {
                //logout
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

    public function login($email = null, $password = null, $remember = false)
    {
        if (!$email && !$password && $this->exists()){
            Session::put($this->session_name, $this->data()[0]->id);
        }else{
            $user = $this->find($email);
            if ($user) {
                if (password_verify($password, $this->data()[0]->password)) {
                    Session::put($this->session_name, $this->data()[0]->id);

                    if ($remember){
                        $hash = hash('sha256', uniqid());

                        $hashCheck = $this->db->get('user_sessions', ['user_id', '=', $this->data()[0]->id]);
                        if (!$hashCheck->count()){
                            $this->db->insert('user_sessions', [
                                'user_id' => $this->data()[0]->id,
                                'hash' => $hash
                            ]);
                        }else{
                            $hash = $hashCheck->first()[0]->hash;
                        }
                        Cookie::put($this->cookieName, $hash, Config::get('cookie.cookie_expiry'));
                    }
                    return true;
                }
            }
        }
        return false;
    }

    public function find($value = null)
    {
        if (is_numeric($value)) {
            $this->data = $this->db->get('users', ['id', '=', $value])->first();
        } else {
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

    public function isLoggedIn()
    {
        return $this->isLoggedIn;
    }

    public function logout()
    {
       return Session::delete($this->session_name);
    }
}