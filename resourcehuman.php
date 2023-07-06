<?php

/**
*@package request
*/

include_once 'head.php';

switch ($_GET[action])
{
	case show:
		print("<h1>".$_SESSION[Nomenclature][Resource_Human_Title]."</h1>");
		if ($_SESSION[User]->ProjectAdmin->AdminHumanR) //has rigths to change project
		{
			print($_SESSION[Project]);
			//then print the users of this project
			print('<p><a href="resourcehuman.php?action=add"><img src="images/icons/add.png"><span>'.$GLOBALS['lang']['Add_User'].'</span></a></p>
						<p>
						<table class="std">
							<tr class="std"><td>'.$lang[User].'</td></tr>');
							$db=new DBase();
							$users=$db->DB->getAll('
								SELECT
									*
								FROM
									project, project_has_user,"user"
								WHERE
									project.idproject=\''.$_SESSION[Project]->ProjectID.'\'
									AND project.idproject=project_has_user.idproject
									AND "user".iduser=project_has_user.iduser');
							if (PEAR::isError($roles))
								{print_r($roles->getDebugInfo());}
							foreach ($users as $key=>$value)
							{
							print('<tr');if($key%2){echo " class=std";}print('>
									<td>['.$value[iduser].'] '.$value[name].' '.$value[father_lastname].' '.$value[mother_lastname].' ('.$value[idproject_role].')</td>
									<td width=16px><a href="resourcehuman.php?action=edit&&UserID='.$value[iduser].'"><img src="images/icons/edit.png"><span>'.$lang['Edit_Message'].'</span></a></td>
									<td width=16px><a href="resourcehuman.php?action=delete&&UserID='.$value[iduser].'"><img src="images/icons/delete.png"><span>'.$lang['Delete_Message'].'</span></a></td>
								</tr>');
							}
						print('</table>
						</p>');
			}
		else
			{print ('Not enougth rigths to delete users form this project');}
		break;
		
	case add:
		print("<h1>".$_SESSION[Nomenclature][Resource_Human_Title]."</h1>");
		print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
		if ($_SESSION[User]->ProjectAdmin->AdminHumanR) //has rigths to change project
		{
				if($_POST!=null)
				{
					$res=Team::AddUser($_POST[UserID],$_SESSION[Project]->ProjectID,$_POST[ProjectRole]);
					if(PEAR::isError($res))
					{
						print('<meta HTTP-EQUIV="REFRESH" content="0; url=resourcehuman.php?action=show&&message='.$res->getMessage().'&&class=error">');
					}
					else 
					{
						print('<meta HTTP-EQUIV="REFRESH" content="0; url=resourcehuman.php?action=show&&message='.$lang['Data_Actualized'].'&&class=success">');
					}
				}
				else 
				{
					print('<form name="adduser" method="post" action="resourcehuman.php?action=add">');
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
			{print ('Not enougth rigths to delete users form this project');}
		break;

	case edit:
			print("<h1>".$_SESSION[Nomenclature][Resource_Human_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if ($_SESSION[User]->ProjectAdmin->AdminHumanR) //has rigths to change project
			{
				if($_POST!=null)
				{
					$res=Team::EditUser($_POST[UserID],$_SESSION[Project]->ProjectID,$_POST[ProjectRole]);
					if(PEAR::isError($res))
					{
						print('<meta HTTP-EQUIV="REFRESH" content="0; url=resourcehuman.php?action=show&&message='.$res->getMessage().'&&class=error">');
					}
					else
					{
						print('<meta HTTP-EQUIV="REFRESH" content="0; url=resourcehuman.php?action=show&&message='.$lang['Modified_User'].": ".$_POST[UserID].'&&class=success">');
					}
				}
				else 
				{
					$db=new DBase();
					print('<form name="edituser" method="post" action="resourcehuman.php?action=edit">');
						print('
							<table class="std">
								<tr>
									<td>'.$GLOBALS['lang']['User'].'</td>
									<td>');
											$user=$db->DB->getAll('SELECT * FROM "user" WHERE iduser=\''.$_GET[UserID].'\'');
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
			{print ('Not enougth rigths to delete users form this project');}
		break;

	case delete:
		if ($_SESSION[User]->ProjectAdmin->AdminHumanR) //has rigths to change project
		{
				if(Team::DeleteUser($_GET[UserID],$_SESSION[Project]->ProjectID))
				{
					print('<meta HTTP-EQUIV="REFRESH" content="0; url=resourcehuman.php?action=show&&message='.$lang['Deleted_User'].": ".$_GET[UserID].'&&class=success">');
				}
				else
				{
					print('<meta HTTP-EQUIV="REFRESH" content="0; url=resourcehuman.php?action=show&&message='.$lang['DB_Error'].'&&class=error">');
				}
		}
		else
			{print ('Not enougth rigths to delete users form this project');}
		break;

	default:
			print('Not such action');
		break;
}

include_once 'foot.php';
?>