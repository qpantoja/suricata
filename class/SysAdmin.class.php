<?php

/**
*@package class
*/

class SysAdmin
{
   private $ShowProjects;
   private $CreateProject;
   private $EditProject;
   private $DeleteProject;
   private $ShowUsers;
   private $CreateUser;
   private $EditUser;
   private $DeleteUser;
   private $EditOwnData;
   private $RelateUsersProject;
   private $ChangePassword;
   private $Backup;
   private $Restore;
   
   /**
    * @desc fullfils propieties
    */
   public function SysAdmin($UserRole) 
   {
   	$db=new DBase();
    $query='SELECT * FROM "sysrole" WHERE "idsysrole"=\''.$UserRole.'\'';
   	$res=$db->DB->getAll($query);
	$this->ShowProjects=$res[0][show_projects];
	$this->CreateProject=$res[0][create_project];
	$this->EditProject=$res[0][edit_project];
	$this->DeleteProject=$res[0][delete_project];
	$this->CreateUser=$res[0][create_user];
   	$this->EditUser=$res[0][edit_user];
   	$this->DeleteUser=$res[0][delete_user];
   	$this->EditOwnData=$res[0][edit_own_data];
   	$this->RelateUsersProject=$res[0][relate_user_project];
   	$this->ChangePassword=$res[0][change_password];
   	$this->ShowUsers=$res[0][show_users];
   	$this->Backup=$res[0][backup];
   	$this->Restore=$res[0][restore];
   }
   
   
   public function __get($get)
   {
   	//provided that the database returns f or t, it should be changed to a bolean
   	if ($this->$get=='t')
   		{return (true);}
   	else
   		{return (false);}
   }
}

?>