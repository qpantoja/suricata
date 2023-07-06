<?php

/**
*@package request
*/

include_once 'head.php';

switch ($_GET[action])
{
	case showstatus:
			print('<h1>'.$_SESSION[Nomenclature][Project_Status_Title].'</h1>');
			if ($_SESSION[User]->ProjectAdmin->EditProyectStatus)
				{print('<p><a href="project.php?action=edit"><img src="images/icons/edit.png"><span>'.$lang['Edit_Message'].'</span></a></p>');}
			if($_SESSION[User]->ProjectAdmin->ShowProjectStatus)
			{
				print($_SESSION[Project]);
			}
		break;
		
	case select:
			print('<h1>'.$lang[Select_Proyect_Title].'</h1>');
			if($_GET[ProjectID]!=null)
			{
				//como evitar la injection de un projectid no valido??
				if ($_SESSION[User]->SetProjRole($_GET[ProjectID]))
				{
					$_SESSION[Project]=new Project($_GET[ProjectID]);
				}
			}
			else 
			{
				print('<p>');Project::ListMyProjects($_SESSION[User]->UserID);print('</p>');
				if($_SESSION[Project]!=null)
				{
					print('<p>'.$_SESSION['Nomenclature']['Project_Selected']);print($_SESSION[Project]);print('</p>');
				}
			}
		break;
		
	case edit:
		print("<h1>".$_SESSION[Nomenclature][Project_Status_Title]."</h1>");
		print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
		if ($_SESSION[User]->ProjectAdmin->EditProyectStatus)
		{
			if($_POST!=null)
			{
				
				$_SESSION[Project]->EditMyProject($_POST[Name],$_POST[State],$_POST[Nomenclature]);
			}
			else 
			{
				print('
					<form name="editproject" method="post" action="project.php?action=edit">
					<table class="std">
						<tr><td>'.$lang[Name].'</td><td><input type="text" name="Name" value="'.$_SESSION[Project]->Name.'"></td>
						<tr class="std"><td>'.$lang[State].'</td><td><input type="text" name="State" value="'.$_SESSION[Project]->State.'"></td></tr>
						<tr><td>'.$lang[Nomenclature].'</td>
							<td>
								<select name="Nomenclature">
									<option selected>'.$_SESSION[Project]->Nomenclature.'</option>');
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
						<tr class="std"><td><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td><td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td></tr>
					</table>
					</form>
				');
				print('<SCRIPT language="JavaScript">
				var validator = new Validator("editproject");
				validator.addValidation("Name","alpha","'.$lang[Field_Alpha].' '.$lang['Name'].'");
				validator.addValidation("Name","req","'.$lang[Field_Required].' '.$lang['Name'].'");
				validator.addValidation("State","alpha","'.$lang[Field_Alpha].' '.$lang['State'].'");
				validator.addValidation("State","req","'.$lang[Field_Required].' '.$lang['State'].'");
				</SCRIPT>');
			}
		}
		else 
			{print($lang[No_Privilegies]);}
		break;

	default:
			print('Action does not exist');
		break;
}

include_once 'foot.php';
?>