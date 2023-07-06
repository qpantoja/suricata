<?php

/**
*@package request
*/

include_once 'head.php';

switch ($_GET[action])
{
	case show:
			print("<h1>$lang[Admin_Proyect_Title]</h1>");
			if($_SESSION[User]->SysAdmin->ShowProjects)
			{
				if($_GET[ProjectID]!=null)
				{
					$project=new Project($_GET[ProjectID]);
					print($project);
				}
				else 
				{
					Project::ShowProjects();
				}
			}
			else 
				{print($lang[No_Privilegies]);}
		break;
		
	case add:
			print("<h1>$lang[Admin_Proyect_Title]</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if($_POST!=null && $_SESSION[User]->SysAdmin->CreateProject)
			{
				Project::CreateProject($_POST[Name],$_POST[State],$_POST[Nomenclature]);
			}
			else 
			{
				if($_SESSION[User]->SysAdmin->CreateProject)
				{
					print('<form name="createproject" method="post" action="adminprojects.php?action=add">');
					print('
						<table class="std">
							<tr><td>'.$GLOBALS['lang']['Name'].'</td><td><input type="text" name="Name"></td></tr>
							<tr class="std"><td>'.$GLOBALS['lang']['State'].'</td><td><input type="text" name="State"></td></tr>
							<tr>
							<td>'.$GLOBALS['lang']['Nomenclature'].'</td>
							<td>
								<select name="Nomenclature">
									<option selected>'.$GLOBALS['lang']['Select_Any'].'</option>');
							if ($manager = opendir('./config/nomenclature'))
							{
								while (false !== ($file = readdir($manager)))
								{
								if ($file != "." && $file != "..")
									{
									print('<option>'.basename($file, ".php").'</option>');
									}
								}
								closedir($manager);
							}
							print('
								</select>
							</td>
							</tr>
							<tr class="std">
								<td ><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    							<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    						</tr>
						</table></form>
					');
					print('<SCRIPT language="JavaScript">
					var validator = new Validator("createproject");
					validator.addValidation("Name","alphanum","'.$lang[Field_Alpha].' '.$lang['Name'].'");
					validator.addValidation("Name","req","'.$lang[Field_Required].' '.$lang['Name'].'");
					validator.addValidation("State","alphanum","'.$lang[Field_Alpha].' '.$lang['State'].'");
					validator.addValidation("State","req","'.$lang[Field_Required].' '.$lang['State'].'");
					validator.addValidation("Nomenclature","dontselect=0","'.$lang[Field_Select].' '.$_SESSION['Nomenclature']['Nomenclature'].'");
					</SCRIPT>');
				}
				else 
					{print($lang[No_Privilegies]);}
			}
		break;

	case edit:
			print("<h1>$lang[Admin_Proyect_Title]</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if ($_POST!=null)
			{
				Project::EditProject($_POST[ProjectID],$_POST[Name],$_POST[State],$_POST[Nomenclature]);
			}
			else 
			{
				//shows the form
				$project=new Project(($_GET[ProjectID]));
				print('
				<form name="editproject" method="post" action="adminprojects.php?action=edit">
				<table class=std>');
    			print('
					<tr><td>'.$lang[Name].'</td><td><input type="text" name="Name" value="'.$project->Name.'"></td>
						<tr class="std"><td>'.$lang[State].'</td><td><input type="text" name="State" value="'.$project->State.'"></td></tr>
						<tr><td>'.$lang[Nomenclature].'</td>
							<td>
								<select name="Nomenclature">
									<option selected>'.$project->Nomenclature.'</option>');
							if ($manager = opendir('./config/nomenclature'))
							{
								while (false !== ($file = readdir($manager)))
								{
								if ($file != "." && $file != "..")
									{
									print('<option>'.basename($file, ".php").'</option>');
									}
								}
								closedir($manager);
							}
							print('
								</select>
							</td>
						</tr>');
    			print('
    				<tr class=std>
    					<input name="ProjectID" type="hidden" value="'.$_GET[ProjectID].'">
    					<td><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    					<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    				</tr>
    			</table></form>');
				//prints the validator...
				print('<SCRIPT language="JavaScript">
				var validator = new Validator("editproject");
				validator.addValidation("Name","alpha","'.$lang[Field_Alpha].' '.$lang['Name'].'");
					validator.addValidation("Name","req","'.$lang[Field_Required].' '.$lang['Name'].'");
					validator.addValidation("State","alpha","'.$lang[Field_Alpha].' '.$lang['State'].'");
					validator.addValidation("State","req","'.$lang[Field_Required].' '.$lang['State'].'");
				</SCRIPT>');
				
				//print users related in this moment in the project
				print('<p>
						<a href="adminprojects.php?action=adduser&&ProjectID='.$_GET[ProjectID].'"><img src="images/icons/add.png"><span>'.$lang['Add_User'].'</span></a>
						<table class="std">');
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
							print('<tr');if($key%2){echo " class=std";}print('>
									<td>['.$value[iduser].'] '.$value[name].' '.$value[father_lastname].' '.$value[mother_lastname].' ('.$value[idproject_role].')</td>
									<td width=16px><a href="adminprojects.php?action=edituser&&UserID='.$value[iduser].'&&ProjectID='.$_GET[ProjectID].'"><img src="images/icons/edit.png"><span>'.$lang['Edit_Message'].'</span></a></td>
									<td width=16px><a href="adminprojects.php?action=deluser&&UserID='.$value[iduser].'&&ProjectID='.$_GET[ProjectID].'"><img src="images/icons/delete.png"><span>'.$lang['Delete_Message'].'</span></a></td>
								</tr>');
							}
						print('</table>
						</p>');
			}
		break;

	case delete:
				Project::DeleteProject($_GET[ProjectID]);
		break;
		
	case adduser:
			print("<h1>$lang[Admin_Proyect_Title]</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if ($_SESSION[User]->SysAdmin->EditProject)
			{
				if($_POST!=null)
				{
					$res=Team::AddUser($_POST[UserID],$_POST[ProjectID],$_POST[ProjectRole]);
					if(PEAR::isError($res))
					{
						print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminprojects.php?action=edit&&ProjectID='.$_POST[ProjectID].'&&message='.$res->getMessage().'&&class=error">');
					}
					else 
					{print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminprojects.php?action=edit&&ProjectID='.$_POST[ProjectID].'&&message='.$lang['Data_Actualized'].'&&class=success">');}
				}
				else 
				{
					print('<form name="adduser" method="post" action="adminprojects.php?action=adduser">');
						print('
							<table class="std">
								<tr>
									<td>'.$GLOBALS['lang']['User'].'</td>
									<td>
										<select name="UserID">');
											$db=new DBase();
											$roles=$db->DB->getAll('SELECT iduser FROM "user" ORDER BY iduser ASC');
											if (PEAR::isError($roles))
												{print_r($roles->getDebugInfo());}
											print('<option selected>'.$GLOBALS['lang']['Select_Any'].'</option>');
											foreach ($roles as $key=>$value)
											{
												print('<option>'.$value[iduser].'</option>');
											}
    								print('
    									</select>
									</td>
								</tr>
								<tr class="std">
									<td>'.$GLOBALS['lang']['ProjectRole'].'</td>
									<td>
										<select name="ProjectRole">');
											$roles=$db->DB->getAll('SELECT idproject_role FROM project_role');
											if (PEAR::isError($roles))
												{print_r($roles->getDebugInfo());}
											print('<option selected>'.$GLOBALS['lang']['Select_Any'].'</option>');
											print_r($roles);
											foreach ($roles as $key=>$value)
											{
												print('<option>'.$value[idproject_role].'</option>');
											}
										print('
										</select>
									</td>
								</tr>
								<tr>
									<input name="ProjectID" type="hidden" value="'.$_GET[ProjectID].'">
									<td ><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    								<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    							</tr>
							</table></form>
						');
						print('<SCRIPT language="JavaScript">
						var validator = new Validator("adduser");
						validator.addValidation("UserID","dontselect=0","'.$lang[Field_Select].' '.$lang[SysRole].'");
						validator.addValidation("ProjectRole","dontselect=0","'.$lang[Field_Select].' '.$lang[SysRole].'");
						</SCRIPT>');
				}
			}
			else 
			{
				print($lang[No_Privilegies]);
			}
		break;

	case edituser:
			print("<h1>$lang[Admin_Proyect_Title]</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if ($_SESSION[User]->SysAdmin->EditProject) //has rigths to change project
			{
				if($_POST!=null)
				{
					$res=Team::EditUser($_POST[UserID],$_POST[ProjectID],$_POST[ProjectRole]);
					if(PEAR::isError($res))
					{
						print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminprojects.php?action=edit&&ProjectID='.$_POST[ProjectID].'&&message='.$res->getMessage().'&&class=error">');
					}
					else
					{
						print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminprojects.php?action=edit&&ProjectID='.$_POST[ProjectID].'&&message='.$lang['Modified_User'].": ".$_POST[UserID].'&&class=success">');
					}
				}
				else 
				{
					$db=new DBase();
					print('<form name="edituser" method="post" action="adminprojects.php?action=edituser">');
						print('
							<table class="std">
								<tr>
									<td>'.$GLOBALS['lang']['User'].'</td>
									<td>');
											$user=$db->DB->getAll('SELECT * FROM "user" WHERE iduser=\''.$_GET[UserID].'\' ORDER BY iduser ASC');
											if (PEAR::isError($user))
												{print_r($user->getDebugInfo());}
											print ($user[0]['name'].' '.$user[0]['father_lastname'].' '.$user[0]['mother_lastname']);
    								print('
									</td>
								</tr>
								<tr class="std">
									<td>'.$GLOBALS['lang']['ProjectRole'].'</td>
									<td>
										<select name="ProjectRole">');
											$roles=$db->DB->getAll('SELECT idproject_role FROM project_role');
											if (PEAR::isError($roles))
												{print_r($roles->getDebugInfo());}
											print('<option selected>'.$GLOBALS['lang']['Select_Any'].'</option>');
											foreach ($roles as $key=>$value)
											{
												print('<option>'.$value[idproject_role].'</option>');
											}
										print('
										</select>
									</td>
								</tr>
								<tr>
									<input name="UserID" type="hidden" value="'.$_GET[UserID].'">
									<input name="ProjectID" type="hidden" value="'.$_GET[ProjectID].'">
									<td ><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    								<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    							</tr>
							</table></form>
						');
						print('<SCRIPT language="JavaScript">
						var validator = new Validator("edituser");
						validator.addValidation("ProjectRole","dontselect=0","'.$lang[Field_Select].' '.$lang[ProjectRole].'");
						</SCRIPT>');
				}
			}
			else
			{print($lang[No_Privilegies]);}
		break;
		
		
		
	case deluser:
		if ($_SESSION[User]->SysAdmin->EditProject) //has rigths to change project
		{
				if(Team::DeleteUser($_GET[UserID],$_GET[ProjectID]))
				{
					print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminprojects.php?action=show&&message='.$lang['Deleted_User'].": ".$_GET[UserID].'&&class=success">');
				}
				else
				{
					print('<meta HTTP-EQUIV="REFRESH" content="0; url=adminprojects.php?action=show&&message='.$lang['DB_Error'].'&&class=error">');
				}
		}
		else
			{print ('Not enougth rigths to delete users form any project');}
		break;
		
	default:
		print ('Not such action');
		break;
}

include_once 'foot.php';
?>