<?PHP

/**
*@package class
* 
*/

class User extends Person 
{
   private $UserID;
   private $SysRole;
   private $ProjRole;
   private $SysAdmin;
   private $ProjectAdmin; //ProjectsAdmin
   

   public function User($UserID) 
   {
    $this->UserID=$UserID;
    $this->LoadData();
   }
   
   public function LoadData()
   {
   	$query='SELECT * FROM "user" WHERE "iduser"=\''.$this->UserID.'\'';
    $db=new DBase();
    $res=$db->DB->getAll($query);
   	$this->SysRole=$res[0][idsysrole];
   	$this->Name=$res[0][name];
   	$this->FatherLastname=$res[0][father_lastname];
   	$this->MotherLastname=$res[0][mother_lastname];
   	$this->Birthday=$res[0][birthday];
   	$this->Address=$res[0][address];
   	$this->Phone=$res[0][phone];
   	$this->Email=$res[0][email];
   	$this->Country=$res[0][country];
   	$this->State=$res[0][state];
   	$this->City=$res[0][city];
   	//put the roles...
    $this->SysAdmin=new SysAdmin($this->SysRole);
   }
   
   public function SetProjRole($ProjectID) 
   {
   		$db=new DBase();
   		$query='
			SELECT * FROM 
				project, project_has_user 
			where 
				iduser=\''.$this->UserID.'\' 
				and project_has_user.idproject=project.idproject
				and project_has_user.idproject=\''.$ProjectID.'\'
			ORDER BY name;';
   		$res=$db->DB->getAll($query);
   		if (PEAR::isError($res))
		{
			print_r($res->getDebugInfo());
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=project.php?action=select&&message='.$res->getMessage().'&&class=error">');
			return false;
		}
		if(empty($res))
		{
			print($GLOBALS['lang']['empty']);
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=project.php?action=select&&message=you are not in that project&&class=error">');
			return false;
		}
       	$this->ProjectAdmin=new ProjectAdmin($res[0][idproject_role]);
       	print('<meta HTTP-EQUIV="REFRESH" content="0; url=project.php?action=select&&message='.$GLOBALS['lang']['Selected_Project'].' '.$res[0][name].'&&class=success">');
       	return true;
   }

   public function ShowUser($UserID) 
   {
        $user=new User($UserID);
		$data=array('Name','FatherLastname','MotherLastname','Phone','Email','Address','Birthday','Country','State','City','SysRole');
    	print('<table class=std>');
    	foreach ($data as $key=>$name)
    	{
    		print('<tr');if($key%2){echo " class=std";}
    		print('>
    				<td>'.$GLOBALS[lang][$name].'</td><td>'.$user->$name.'</td></tr>');
    	}
    	print('
    		</table>');
   }
   
   public function ShowUserData()
   {
    //display personal data
    $this->LoadData();
    $data=array('Name','FatherLastname','MotherLastname','Phone','Email','Address','Birthday','Country','State','City');
    print('<table class=std>');
    foreach ($data as $key=>$value)
    {
    	print('<tr');if($key%2){echo " class=std";}
    	print('><td>'.$GLOBALS[lang][$value].'</td>
    			<td>'.$this->$value.'</td>
  			</tr>');
    }
    print('</table>');
   }
   
   /////////////////////////////////////////////////////////////////////////////////////
   //OPERATIONS...............
   
   /**
    * @return boolean
    */
   public function CreateUser($UserID,$Name,$FatherLastname,$MotherLastname,$Phone,$Email,$Address,$Birthday,$Country, $State,$City, $Sysrole) 
   {
   		if($this->SysAdmin->CreateUser)
   		{
   			$table_name='"user"';
   			$fields_values = array(
   				'iduser'=>$UserID,
   				'idsysrole'=> $Sysrole,
   				'name' => $Name,
   				'father_lastname'=>$FatherLastname,
   				'mother_lastname'=>$MotherLastname,
   				'email'=>$Email,
   				'address'=>$Address,
   				'birthday'=>$Birthday
   				);
   			if (!empty($Phone))
   				{$fields_values['phone']=$Phone;}
   			if (!empty($Country))
   				{$fields_values['country']=$Country;}
   			if (!empty($State))
   				{$fields_values['state']=$State;}
   			if (!empty($City))
   				{$fields_values['city']=$City;}
   			$db=new DBase();
   			$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_INSERT);
   			if (PEAR::isError($res))
			{
				print_r($res->getDebugInfo());
				print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminusers.php?action=show&&message='.$res->getMessage().'&&class=error">');
			}
			else{print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminusers.php?action=show&&message='.$GLOBALS[lang][Data_Actualized].'&&class=success">');}
   		}
   		else 
   		{
   			print($lang[No_Privilegies]);
   			return false;
   		}
   }
   
   /**
    * edit any user, includes the one in the sesion
    * @return boolean
    */
   public function EditUser($Sysrole,$NewUserID,$UserID,$Name,$FatherLastname,$MotherLastname,$Phone,$Email,$Address,$Birthday,$Country,$State,$City) 
   {
   		if($this->SysAdmin->EditUser)
   		{
   			$table_name='"user"';
   			$fields_values = array(
   				'iduser'=> $NewUserID,
   				'idsysrole'=> $Sysrole,
   				'name' => $Name,
   				'father_lastname'=>$FatherLastname,
   				'mother_lastname'=>$MotherLastname,
   				'email'=>$Email,
   				'address'=>$Address,
   				'birthday'=>$Birthday
   				);
   			if (!empty($Phone))
   				{$fields_values['phone']=$Phone;}
   			if (!empty($Country))
   				{$fields_values['country']=$Country;}
   			if (!empty($State))
   				{$fields_values['state']=$State;}
   			if (!empty($City))
   				{$fields_values['city']=$City;}
   			$where='iduser=\''.$UserID.'\'';
   			$db=new DBase();
   			$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_UPDATE,$where);
   			if (PEAR::isError($res))
			{
				print_r($res->getDebugInfo());
				print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminusers.php?action=show&&message='.$res->getMessage().'&&class=error">');
			}
			else{print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminusers.php?action=show&&message='.$GLOBALS[lang][Data_Actualized].'&&class=success">');}
	   	}
   		else
   		{
   			print($lang[No_Privilegies]);
   			return false;
   		}
    
   }
   
   /**
    * deletes an user
    * @return boolean
    */
   public function DeleteUser($UserID) 
   {
   		if($this->SysAdmin->DeleteUser)
   		{
   			$db=new DBase();
   			$query='DELETE FROM "user" where iduser=\''.$UserID.'\'';
   			$res=$db->DB->query($query);
   			if (PEAR::isError($res))
   			{
   				print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminusers.php?action=show&&message='.$GLOBALS[lang]['User_Delete_Error'].'&&class=error">');
   			}
   			else 
   			{
   				print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminusers.php?action=show&&message='.$GLOBALS[lang]['Deleted_User'].": ".$UserID.'&&class=success">');
   			}
   		}
   		else 
   		{
   			print($lang[No_Privilegies]);
   			return false;
   		}
   }
   
   /**
    * edit the sesion user data
    * @return boolean
    */
   public function EditOwnData($Name,$FatherLastname,$MotherLastname,$Phone,$Email,$Address,$Birthday,$Country,$State,$City) 
   {
     if($this->SysAdmin->EditOwnData)
   	{
   		$table_name='"user"';
   		$fields_values = array(
   			'name' => $Name,
   			'father_lastname'=>$FatherLastname,
   			'mother_lastname'=>$MotherLastname,
   			'email'=>$Email,
   			'address'=>$Address,
   			'birthday'=>$Birthday
   			);
   		if (!empty($Phone))
   				{$fields_values['phone']=$Phone;}
   		if (!empty($Country))
   				{$fields_values['country']=$Country;}
   		if (!empty($State))
   				{$fields_values['state']=$State;}
   		if (!empty($City))
   				{$fields_values['city']=$City;}
   		$where='iduser=\''.$_SESSION[User]->UserID.'\'';
   		$db=new DBase();
   		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_UPDATE,$where);
   		if (PEAR::isError($res))
		{
			print_r($res->getDebugInfo());
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminpersonaldata.php?action=show&&message='.$res->getMessage().'&&class=error">');
			return false;
		}
		else{print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminpersonaldata.php?action=show&&message='.$GLOBALS[lang][Data_Actualized].'&&class=success">');}
   	}
   	else 
   	{
   		print($lang[No_Privilegies]);
   		return (false);
   	}
   }
   
   /**
   *Changes the password for any user
   *@return boolean
   */
   public function ChangePassword($OldPassword,$NewPassword,$UserID) 
   {
		if($this->SysAdmin->EditUser)
   		{
   			$db=new DBase();
   			$query='UPDATE "user" SET password=\''.md5($NewPassword).'\' WHERE iduser=\''.$UserID.'\'';
			$res=$db->DB->query($query);
			if (PEAR::isError($res))
					{
						print_r($res->getDebugInfo());
						print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminusers.php?action=show&&message='.$res->getMessage().'&&class=error">');
					}
			else{print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminusers.php?action=show&&message='.$GLOBALS[lang][Changed_Password].'&&class=success">');}
   		}
   		else
   		{
   			if($this->SysAdmin->ChangePassword && ($this->UserID==$UserID))
			{
   			$db=new DBase();
    		$query='SELECT password FROM "user" WHERE iduser=\''.$UserID.'\'';
   			$res=$db->DB->getAll($query);
   			switch ($res[0]['password'])
	        	{
                	case md5($OldPassword):
							$query='UPDATE "user" SET password=\''.md5($NewPassword).'\' WHERE iduser=\''.$UserID.'\'';
							$res=$db->DB->query($query);
							if (PEAR::isError($res))
							{
								print_r($res->getDebugInfo());
								print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminpersonaldata.php?action=show&&message='.$res->getMessage().'&&class=error">');
							}
							else{print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminpersonaldata.php?action=show&&message='.$lang[Changed_Password].'&&class=success">');}
                        	break;
                	default:
							print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminpersonaldata.php?action=show&&message='.$lang[Wrong_Password].'&&class=error">');
                        	break;
        		}
			}
			else 
   			{print($lang[No_Privilegies]);
   			return (false);}
   		}
   }
   
   /**
   *Shows any user in the system
   *@return bool
   */
   public function ShowUsers() 
   {
   	if($this->SysAdmin->ShowUsers)
   	{

   		$db=new DBase();
   		$query='SELECT * FROM "user" order by iduser';
   		$res=$db->DB->getAll($query);
   		if(empty($res))//probably never shown.. because.. who is making the query?
			{
				print($GLOBALS['lang']['empty']);
				return true;
			}
   		print('<table class=std>
   				<tr class="std"><td>'.$GLOBALS[lang][UserID].'</td><td>'.$GLOBALS[lang][User].'</td></tr>');
   		foreach ($res as $key=>$value)
   		{
	   		print('<tr');if($key%2){echo ' class=std ';}
   			print ('>
	   			<td>'.$value[iduser].'</td>
   				<td><a ');if($key%2){echo 'class=std ';}
   				print('href="adminusers.php?action=show&&UserID='.$value[iduser].'">'.$value[name].' '.$value[father_lastname].' '.$value[mother_lastname].'</a></td>');
   			if($this->SysAdmin->EditUser)
	   			{
   					print('<td width=16px><a ');if($key%2){echo 'class=std ';} print('href="adminusers.php?action=edit&&UserID='.$value[iduser].'"><img src="images/icons/edit.png"><span>'.$GLOBALS['lang']['Edit_Message'].'</span></a></td>');
   					print('<td width=16px><a ');if($key%2){echo 'class=std ';} print('href="adminusers.php?action=changepassword&&UserID='.$value[iduser].'"><img src="images/icons/password.png"><span>'.$GLOBALS['lang']['Change_Password_Message'].'</span></a></td>');
   				}
   			if($this->SysAdmin->DeleteUser){print("<td width=16px><a ");if($key%2){echo 'class=std ';} print('href="#" onclick="confirmation(\''.$GLOBALS['lang']['Delete_Confirm'].'\',\'adminusers.php?action=delete&&UserID='.$value[iduser].'\')"><img src="images/icons/delete.png"><span>'.$GLOBALS['lang']['Delete_Message'].'</span></a></td>');}
   			print("</tr>");
   		}
   		print('</table>');
   	}
   	else 
   	{
   		print($lang[No_Privilegies]);
   		return false;
   	}
   }

   public function __toString()
   {
   	
   }
   
   public function __get($get)
   {
   	return $this->$get;
   }
}
?>