<?php

class Users
{

    private $_db,
            $_data,
            $_session_name,
            $_cookie_name,
            $_isLoggedIn = false;


    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();
        $this->_session_name = Config::get('session/session_name');
        $this->_cookie_name = Config::get('remember/cookie_name');

        if(!$user){
            if(Session::exists($this->_session_name)){
                $user = Session::get($this->_session_name);

                if($this->find($user)){
                    $this->_isLoggedIn = true;
                }else{
                    $this->logout();
                }

            }

        }else{
            $this->find($user);
        }
    }

    public function update($fields= array(), $id= null)
    {
        if(!$id && $this->_isLoggedIn){
            $id = $this->data()->id;
        }

        if(!$this->_db->update('users', $id, $fields)){
            throw new Exception('There was a problem updating..');
        }
    }

    public function create($fields = array())
    {
        if(! $this->_db->insert('users', $fields)){
            throw new Exception("There was a problem when creating new account");
        }
    }

    public function find($user = null)
    {
        if($user){
            $field = (is_numeric($user)) ? 'id' : 'username';
            $data = $this->_db->get('users', array($field, '=', $user));

            if($data->count()){
                $this->_data =$data->first();
                return true;
            }
        }
    }

    public function hasPermissions($key)
    {
        $group = $this->_db->get('groups', array('id', '=', $this->data()->grup));

        if($group->count()){
             $permission = json_decode($group->first()->permissions, true);

            if($permission[$key] == true){
                return true;
            }
        }
        return false;
    }

    public function login($username = null, $password = null, $remember = false)
    {
        if(!$username && !$password && $this->exsits()){
            //process login
            Session::put($this->_session_name, $this->data()->id);
        }else{
            $user = $this->find($username);

            if($user){
                if($this->data()->password === Hash::make($password, $this->data()->salt)){
                    Session::put($this->_session_name, $this->data()->id);

                    if($remember){

                        $hash = Hash::unique();
                        $hashCheck = $this->_db->get('users_sessions', array('user_id', '=', $this->data()->id));
//                    print_r($hashCheck);
                        if(!$hashCheck->count()){
                            $this->_db->insert('users_sessions', array(
                                'user_id'=> $this->data()->id,
                                'hash' => $hash
                            ));
                        }else{
                            $hash = $hashCheck->first()->hash;
                        }
                        Cookie::put($this->_cookie_name, $hash, Config::get('remember/cookie_expiry'));
                    }
                    return true;
                }
            }

        }

        return false;
    }

    public function exsits()
    {
        return (!empty($this->_data))? true : false;
    }

    public function data()
    {
        return $this->_data;
    }

    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }

    public function logout()
    {
        $this->_db->delete('users_sessions', array('user_id', '=', $this->data()->id));
        Session::delete($this->_session_name);
        Cookie::delete($this->_cookie_name);
    }
}