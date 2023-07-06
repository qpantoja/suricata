<?php

/**
*@package class
*/

class Project
{
	private $ProjectID;
	private $Name;
	private $State;
	private $Nomenclature;
	
	public function Project($ProjectID)
	{
		$db=new DBase();
		$res=$db->DB->getAll('SELECT * FROM project WHERE idproject=\''.$ProjectID.'\'');
		if (PEAR::isError($res))
			{print_r($res->getDebugInfo());}
		$this->ProjectID=$res[0][idproject];
		$this->Name=$res[0][name];
		$this->State=$res[0][state];
		$this->Nomenclature=$res[0][nomenclature];
	}
	
	public static function CreateProject($Name, $State, $Nomenclature)
	{
		$table_name='"project"';
		$fields_values = array(
			'name'=>$Name,
   			'state'=> $State,
   			'nomenclature' => $Nomenclature
   			);
   		$db=new DBase();
   		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_INSERT);
   		if (PEAR::isError($res))
		{
			print_r($res->getDebugInfo());
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminprojects.php?action=show&&message='.$res->getMessage().'&&class=error">');
		}
		else{print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminprojects.php?action=show&&message='.$GLOBALS[lang][Data_Actualized].'&&class=success">');}
	}
	
	public function EditMyProject($Name,$State,$Nomenclature)
	{
		$table_name='project';
   		$fields_values = array(
   			'name' => $Name,
   			'state' => $State,
   			'nomenclature' => $Nomenclature
   			);
   		$where='idproject=\''.$this->ProjectID.'\'';
		$db=new DBase();
		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_UPDATE,$where);
		if (PEAR::isError($res))
		{
			print_r($res->getDebugInfo());
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=project.php?action=showstatus&&message='.$res->getMessage().'&&class=error">');
		}
		else
		{
			$this->Name=$Name;
			$this->State=$State;
			$this->Nomenclature=$Nomenclature;
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=project.php?action=showstatus&&message='.$GLOBALS[lang][Data_Actualized].'&&class=success">');
		}
	}
	
	public static function EditProject($ProjectID, $Name, $State, $Nomenclature)
	{
		if ($_SESSION[User]->SysAdmin->EditProject)
		{
		$table_name='"project"';
   			$fields_values = array(
   				'name'=>$Name,
   				'state'=>$State,
   				'nomenclature'=>$Nomenclature
   				);
   		$where='idproject=\''.$ProjectID.'\'';
   		$db=new DBase();
   		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_UPDATE,$where);
		if (PEAR::isError($res))
			{
				print_r($res->getDebugInfo());
				print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminprojects.php?action=show&&message='.$res->getMessage().'&&class=error">');
			}
			else{print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminprojects.php?action=show&&message='.$GLOBALS[lang][Data_Actualized].'&&class=success">');}
	   	}
   		else
   		{trigger_error("Not enougth privilegies to edit projects", E_USER_WARNING);}
	}
	
	public static function DeleteProject($ProjectID)
	{
		if ($_SESSION[User]->SysAdmin->DeleteProject)
		{
			$db=new DBase();
   			$res=$db->DB->query('DELETE FROM "project" where idproject=\''.$ProjectID.'\'');
   			if (PEAR::isError($res))
   			{
	   			print_r($res->getDebugInfo());
   				print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminprojects.php?action=show&&message='.$res->getMessage().'&&class=error">');
   			}
   			else 
   			{
	   			print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminprojects.php?action=show&&message='.$GLOBALS[lang]['Deleted_Project'].'&&class=success">');
   			}
		}
		else
			{print ('not enougth privilegies to be here...');}
	}
	
	public static function ShowProjects()
	{
		$db=new DBase();
		$res=$db->DB->getAll('SELECT * FROM project ORDER BY name');
		if($_SESSION[User]->SysAdmin->CreateProject)
			{print('<p><a href="adminprojects.php?action=add"><img src="images/icons/add.png"><span>'.$GLOBALS['lang']['Add_Message'].'</span></a></p>');}
		if(empty($res))
		{
			print($GLOBALS['lang']['empty']);
			return true;
		}
		if($_SESSION[User]->SysAdmin->ShowProjects)
		{
			print("\n".'<table class=std>
						<tr class=std><td>'.$GLOBALS[lang][Project].'</td></tr>');
			foreach ($res as $key=>$value)
			{
				print("\n".'<tr');if($key%2){echo ' class=std ';}
   				print ('><td><a href="adminprojects.php?action=show&&ProjectID='.$value[idproject].'">'.$value[name].'</a></td>');
   				if($_SESSION[User]->SysAdmin->EditProject)
	   			{
   					print("\n".'<td  width=16px><a ');if($key%2){echo 'class=std ';} print('href="adminprojects.php?action=edit&&ProjectID='.$value[idproject].'"><img src="images/icons/edit.png"><span>'.$GLOBALS['lang']['Edit_Message'].'</span></a></td>');
   				}
   				if($_SESSION[User]->SysAdmin->DeleteProject)
	   			{
	   				print("\n".'<td  width=16px><a ');if($key%2){echo 'class=std ';} print('href="#" onclick="confirmation(\''.$GLOBALS['lang']['Delete_Confirm'].'\',\'adminprojects.php?action=delete&&ProjectID='.$value[idproject].'\')"><img src="images/icons/delete.png"><span>'.$GLOBALS['lang']['Delete_Message'].'</span></a></td>');
   				}
   				print("\n".'</tr>');
			}
			print("\n".'</table>');
		}
	}
	
	public static function ListMyProjects($User)
	{
		$db=new DBase();
		$query='
			SELECT * FROM 
				project, project_has_user 
			where 
				iduser=\''.$User.'\' 
				and project_has_user.idproject=project.idproject
			ORDER BY name;';
		$res=$db->DB->getAll($query);
		if (PEAR::isError($res))
		{
			print_r($res->getDebugInfo());
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=project.php?action=select&&message='.$res->getMessage().'&&class=error">');
		}
		if(empty($res))
		{
			print($GLOBALS['lang']['empty']);
			return true;
		}
		print('<table class=std>
				<tr class="std"><td>'.$GLOBALS['lang'][Project].'</td><td>'.$GLOBALS['lang'][ProjectRole].'</td><tr>');
		foreach ($res as $key=>$value)
		{
			print('<tr');if($key%2){echo ' class=std ';}print ('>
					<td><a href="project.php?action=select&&ProjectID='.$value[idproject].'">'.$value[name].'</td><td>'.$value[idproject_role].'</a></td>
				</tr>');
		}
		print('</table>');
	}
	
	public function ShowTasks()
	{
		print('<p><a href="admintasks.php?action=addthreadline"><img src="images/icons/add.png"><span>'.$_SESSION['Nomenclature']['Add_Thread_Message'].'</span></a></p>');
		//foreach tread, ListTasks
		$db=new DBase();
		$res=$db->DB->getAll('SELECT * FROM action_thread WHERE idproject=\''.$this->ProjectID.'\'');
		if (PEAR::isError($res))
			{print_r($res->getDebugInfo());}
		else
		{
			if(!empty($res))
			{
				foreach ($res as $Thread)
				{
					$thread=new ActionThread($Thread['idaction_thread']);
					$thread->ListThread();
					print('<br>');
				}
			}
			else 
			{
				print($GLOBALS['lang']['empty']);
			}
		}
		
	}
	
	public function __get($get)
	{
		return ($this->$get);
	}
	
	
	public function __toString()
	{
		$string='
			<table class="std">
				<tr class="std"><td>'.$GLOBALS[lang][Name].'</td><td>'.$this->Name.'</td></tr>
				<tr><td>'.$GLOBALS[lang][Nomenclature].'</td><td>'.$this->Nomenclature.'</td></tr>
				<tr class="std"><td>'.$GLOBALS[lang][State].'</td><td>'.$this->State.'</td></tr>
			</table>';
		return($string);
	}
}
?>