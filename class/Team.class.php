<?PHP
/**
*@package class
*@version 1.0
*@author Pantoja Hinojosa Quetzalcoatl
*@todo guardar en la base de datos
*/

class Team
{
	private $TeamUser=array();
	
	public function Team($ProjectID)
	{
		$db=new DBase();
		$users=$db->DB->getAll('
				SELECT
					*
				FROM
					project, project_has_user,"user"
				WHERE
					project.idproject=\''.$_GET[ProjectID].'\'
					AND project.idproject=project_has_user.idproject
					AND "user".iduser=project_has_user.iduser');
			if (PEAR::isError($roles))
				{print_r($roles->getDebugInfo());}
			foreach ($users as $key=>$value)
			{
				$this->TeamUser[$value[iduser][$value[name].' '.$value[father_lastname].' '.$value[mother_lastname]]]=$value[idproject_role];
			}
	}
	
	public static function EditUser($UserID,$ProjectID,$ProjectRole)
	{
		$table_name='project_has_user';
   		$fields_values = array(
   			'idproject_role'=> $ProjectRole,
   			);
   		$where='iduser=\''.$UserID.'\' AND idproject=\''.$ProjectID.'\'';
   		$db=new DBase();
   		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_UPDATE,$where);
   		if (PEAR::isError($res))
		{
			return ($res);
		}
		else
		{
			return (true);
		}
	}
	
	public static function AddUser($UserID,$ProjectID,$ProjecRole)
	{
		
			$table_name='project_has_user';
   			$fields_values = array(
   				'iduser'=>$UserID,
   				'idproject'=>$ProjectID,
   				'idproject_role'=> $ProjecRole,
   				);
   			$db=new DBase();
   			$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_INSERT);
   			if (PEAR::isError($res))
			{
				return ($res);
			}
			else{
				return (true);
			}
	}
	
	/**
	 * Deletes the relation of an user to a given project
	 *
	 * @param integrer $UserID
	 * @param integrer $ProjectID
	 * @return bool
	 */
	public static function DeleteUser($UserID,$ProjectID)
	{
			$db=new DBase();
   			$query='DELETE FROM project_has_user where iduser=\''.$UserID.'\' and idproject=\''.$ProjectID.'\'';
   			$res=$db->DB->query($query);
   			if (PEAR::isError($res))
   			{
   				print_r($res->getDebugInfo());
   				//print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminprojects.php?action=show&&message='.$res->getMessage().'&&class=error">');
   				return (false);
   			}
   			else 
   			{
   				//print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminprojects.php?action=show&&message='.$GLOBALS[lang]['Deleted_User'].": ".$UserID.'&&class=success">');
   				return(true);
   			}
	}
}
?>