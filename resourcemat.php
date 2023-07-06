<?php

/**
*@package request
*/

include_once 'head.php';

switch ($_GET[action])
{
	case show:
			print("<h1>".$_SESSION[Nomenclature][Resource_Material_Title]."</h1>");
			if ($_SESSION[User]->ProjectAdmin->AdminMaterial)
				{print('<a href="resourcemat.php?action=add"><img src="images/icons/add.png"><span>'.$lang['Add_Message'].'</span></a>');}
			print('<p>');Material::ListMaterial($_SESSION[Project]->ProjectID);print('</p>');
		break;
		
	case add:
			print("<h1>".$_SESSION[Nomenclature][Resource_Material_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if ($_SESSION[User]->ProjectAdmin->AdminMaterial)
			{
				if($_POST!=null)
				{
					$res=Material::AddMaterial($_POST[Description],$_POST[SerialNumber],$_POST[ExpireDate],$_SESSION[Project]->ProjectID);
					if($res==true)
					{
						print('<meta HTTP-EQUIV="REFRESH" content="0; url=resourcemat.php?action=show&&message='.$_SESSION[Nomenclature][New_Material].'&&class=success">');	
					}
					else 
					{
						print('<meta HTTP-EQUIV="REFRESH" content="0; url=resourcemat.php?action=show&&message='.$res->getMessage().'&&class=error">');	
					}
				}
				else 
				{
					print('
					<form name="addmaterial" method="post" action="resourcemat.php?action=add">
					<table class="std">
						<tr><td>'.$_SESSION[Nomenclature][Serial].'</td><td><input type="text" name="SerialNumber"></td></tr>
						<tr class=std><td>'.$_SESSION[Nomenclature][Description].'</td><td><input type="text" name="Description"></td></tr>
						<tr>
    						<td>'.$_SESSION[Nomenclature][Expire_Date].' '.$lang[Date_Format].'</td>
    						<td>
    						<input type="text" name="ExpireDate">
    						<table><tr><td><A HREF="#" onClick="cal.select(document.forms[\'addmaterial\'].ExpireDate,\'anchor1\',\'yyyy-MM-dd\'); return false;"NAME="anchor1" ID="anchor1"><img src="images/icons/calendar.png"></A></td></tr></table>
    						</td>
    					</tr>
						<tr class=std><td><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td><td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td></tr>
					</table>
					</form>
					');
					print('<SCRIPT language="JavaScript">
					var validator = new Validator("addmaterial");
					validator.addValidation("SerialNumber","alphanum","'.$lang[Field_Alpha].' '.$_SESSION[Nomenclature]['Serial'].'");
					validator.addValidation("SerialNumber","req","'.$lang[Field_Required].' '.$_SESSION[Nomenclature]['Serial'].'");
					validator.addValidation("Description","alpha","'.$lang[Field_Alpha].' '.$_SESSION[Nomenclature]['Description'].'");
					validator.addValidation("Description","req","'.$lang[Field_Required].' '.$_SESSION[Nomenclature]['Description'].'");
					validator.addValidation("ExpireDate","date","'.$lang[Field_Date].' '.$_SESSION[Nomenclature]['ExpireDate'].'");
					validator.addValidation("ExpireDate","req","'.$lang[Field_Required].' '.$_SESSION[Nomenclature]['ExpireDate'].'");
					</SCRIPT>');
				}
			}
			else
			{
				print($lang[No_Privilegies]);	
			}
		break;
		
	case details:
			print("<h1>".$_SESSION[Nomenclature][Resource_Material_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			$material = new Material($_GET[MaterialID]);
			print($material);
		break;

	case edit:
			print("<h1>".$_SESSION[Nomenclature][Resource_Material_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
		break;

	case delete:
			print("<h1>".$_SESSION[Nomenclature][Resource_Material_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			
			if ($_SESSION[User]->ProjectAdmin->AdminMaterial)
			{
				$res=Material::Delete($_GET[MaterialID]);
				if($res==true)
				{
					print('<meta HTTP-EQUIV="REFRESH" content="0; url=resourcemat.php?action=show&&message='.$_SESSION[Nomenclature][Delete_Material].'&&class=success">');	
				}
				else 
				{
					print('<meta HTTP-EQUIV="REFRESH" content="0; url=resourcemat.php?action=show&&message='.$res->getMessage().'&&class=error">');	
				}
			}
			else
			{
				print($lang[No_Privilegies]);
			}
		break;

	default:
		break;
}

include_once 'foot.php';
?>