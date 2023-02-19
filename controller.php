<?php 
require_once "dbc_all.php";
global $uid;
class controller
{
    public function GetUserID()
    {
        return $uid;//$modx->user->getOne('Profile')->get('id');
    }
}

?>