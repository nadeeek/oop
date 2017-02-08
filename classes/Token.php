<?php

class Token
{
    public static function generate(){
        return Session::put(Config::get('session/token_name'), md5(uniqid()));
    }

    public static function check($token)
    {
        $taken_name = Config::get('session/token_name');

        Session::get($taken_name);
        if(Session::exists($taken_name) && $token === Session::get($taken_name)){
            Session::delete($taken_name);
            return true;
        }
        return false;
    }

}