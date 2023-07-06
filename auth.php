<?php

/**
*@package request
*/

include_once "config/config.php";
include_once $config[lang];
$class_req=array(Person,DBase,User,Menu,ChangesSheet,ProjectAdmin,SysAdmin);
foreach ($class_req as $name)
{
	include("class/$name.class.php");
}

session_start();

$db = new DBase();
$query='SELECT * FROM "user" WHERE "iduser"=\''.$_POST['User'].'\'';
$res=$db->DB->getAll($query);
if($res==array())
{
        $message=$lang[No_User];
        $class='error';
}
else{
        switch ($res[0]['password'])
        {
                case md5($_POST['Password']):
                        $message=$lang[User_Auth].$_POST['User'];
                        $class='success';
                        $_SESSION[User]=new User($_POST['User']);
                        break;
                default:
                        $message=$lang[Wrong_Password];
                        $class='error';
                        break;
        }
}

//authenticate and redirect to the begining (index)...
print('<meta HTTP-EQUIV="REFRESH" content="0; url=index.php?message='.$message.'&&class='.$class.'">');
?>