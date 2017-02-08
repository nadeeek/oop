<?php
class Input
{
    public function exist($type = 'post')
    {
        switch ($type){
            case 'post':
                return (!empty($_POST))? true : false;
                break;
            case 'get':
                return (!empty($_GET)) ? true : false;
                break;
            default:
                break;
        }
    }
}

