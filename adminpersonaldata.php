<?php

/**
*@package request
*@desc edit or show personal data
*@author Pantoja Hinojosa Quetzalcoatl
*/


include_once 'head.php';

switch ($_GET[action])
{
	case show:
		print("<h1>$lang[Admin_Personal_Data_Title]</h1>");
		if($_SESSION[User]->SysAdmin->EditOwnData)
		{
			print ('
			<table>
				<tr>
				<td><a href="adminpersonaldata.php?action=edit"><img src="images/icons/edit.png"><span>'.$GLOBALS['lang']['Edit_Message'].'</span></a></td>
				<td><a href="adminpersonaldata.php?action=changepasswd"><img src="images/icons/password.png"><span>'.$GLOBALS['lang']['Change_Password_Message'].'</span></a></td>
				</tr>
			</table>');
		}
		$_SESSION[User]->ShowUserData();
		break;

	case edit:
			print("<h1>$lang[Admin_Personal_Data_Title]</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if ($_POST!=null)
			{
				//edit data
				$error=$_SESSION[User]->EditOwnData($_POST[Name],$_POST[FatherLastname],$_POST[MotherLastname],$_POST[Phone],$_POST[Email],$_POST[Address],$_POST[Birthday],$_POST[Country],$_POST[State],$_POST[City]);
				
			}
			else 
			{
				//shows the form
				$user=new User($_SESSION[User]->UserID);
				//$data=array('Name'=>'text','FatherLastname'=>'text','MotherLastname'=>'text','Phone'=>'text','Email'=>'text','Address'=>'textarea','Birthday'=>'text','Country'=>'text','State'=>'text','City'=>'text');
				print('<form name="editowndata" method="post" action="adminpersonaldata.php?action=edit">
						<table class=std>');
				print('
				<tr><td>'.$lang[UserID].'</td><td><input type="text" name="UserID" value="'.$user->UserID.'"></td></tr>
				<tr class=std><td>'.$lang[Name].'</td><td><input type="text" name="Name" value="'.$user->Name.'"></td></tr>
    			<tr><td>'.$lang[FatherLastname].'</td><td><input type="text" name="FatherLastname" value="'.$user->FatherLastname.'"></td></tr>
    			<tr class=std><td>'.$lang[MotherLastname].'</td><td><input type="text" name="MotherLastname" value="'.$user->MotherLastname.'"></td></tr>
    			<tr><td>'.$lang[Phone].'</td><td><input type="text" name="Phone" value="'.$user->Phone.'"></td></tr>
    			<tr class=std><td>'.$lang[Email].'</td><td><input type="text" name="Email" value="'.$user->Email.'"></td></tr>
    			<tr><td>'.$lang[Address].'</td><td><textarea type="textarea" name="Address">'.$user->Address.'</textarea></td></tr>
    			<tr class=std>
    				<td>'.$lang[Birthday].' '.$lang[Date_Format].'</td>
    				<td>
    					<input type="text" name="Birthday" value="'.$user->Birthday.'">
    					<table><tr><td><A HREF="#" onClick="cal.select(document.forms[\'editowndata\'].Birthday,\'anchor1\',\'yyyy-MM-dd\'); return false;"NAME="anchor1" ID="anchor1"><img src="images/icons/calendar.png"></A></td></tr></table>
    				</td>
    			</tr>
    			<tr><td>'.$lang[Country].'</td>
    				<td><select name="Country" >
    					<option selected>'.$user->Country.'</option>');
    					foreach($lang['Country_List'] as $Country)
    					{
    							print("<option>$Country</option>");
    					}
    				print('
    				</select></td>
    			</tr>
    			<tr class=std><td>'.$lang[State].'</td><td><input type="text" name="State" value="'.$user->State.'"></td></tr>
    			<tr><td>'.$lang[City].'</td><td><input type="text" name="City" value="'.$user->City.'"></td></tr>
				');
    			print('
    				<td><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    				<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    				</table></form>');
				//prints the validator...
				print('<SCRIPT language="JavaScript">
				var validator = new Validator("editowndata");
				validator.addValidation("Name","alpha","'.$lang[Field_Alpha].' '.$lang[Name].'");
				validator.addValidation("Name","req","'.$lang[Field_Required].' '.$lang[Name].'");
				validator.addValidation("FatherLastname","alpha","'.$lang[Field_Alpha].' '.$lang[FatherLastname].'");
				validator.addValidation("FatherLastname","req","'.$lang[Field_Required].' '.$lang[FatherLastname].'");
				validator.addValidation("MotherLastname","alpha","'.$lang[Field_Alpha].' '.$lang[MotherLastname].'");
				validator.addValidation("MotherLastname","req","'.$lang[Field_Required].' '.$lang[MotherLastname].'");
				validator.addValidation("Birthday","date","'.$lang[Field_Date].'");
				validator.addValidation("Phone","num","'.$lang[Field_Numeric].' '.$lang[Phone].'");
				validator.addValidation("Email","email","'.$lang[Field_Email].'");
				validator.addValidation("Country","alpha","'.$lang[Field_Alpha].' '.$lang[Country].'");
				validator.addValidation("State","alpha","'.$lang[Field_Alpha].' '.$lang[State].'");
				validator.addValidation("City","alpha","'.$lang[Field_Alpha].' '.$lang[City].'");
				</SCRIPT>');
    			
			}
			break;
			
	case changepasswd:
		print("<h1>$lang[Admin_Personal_Data_Title]</h1>");
		print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
		//show the form for password...
		if ($_POST!=null)
		{
			$_SESSION[User]->ChangePassword($_POST[Old_Password],$_POST[New_Password],$_POST[UserID]);
		}
		else 
		{
			print ('
			<form name="changepasswd" method="post" action="adminpersonaldata.php?action=changepasswd" onsubmit="return v.exec()">
				<table class="std">
					<tr><td>'.$lang[Old_Password].'</td><td><input type="password" name="Old_Password" ></td></tr>
					<tr class=std><td>'.$lang[New_Password].'</td><td><input type="password" name="New_Password" ></td></tr>
					<tr><td>'.$lang[Confirmation].'</td><td><input type="password" name="Confirmation" ></td></tr>
					<td><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    				<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
				</table>
			</form>');
			//prints the validator...
			print('<SCRIPT language="JavaScript">
			var validator = new Validator("changepasswd");
			validator.addValidation("Old_Password","req","'.$lang[Field_Required].$lang[Old_Password].'");
			validator.addValidation("Old_Password","alphanum","'.$lang[Field_Alpha].' '.$lang[Old_Password].'");
			validator.addValidation("New_Password","req","'.$lang[Field_Required].$lang[New_Password].'");
			validator.addValidation("New_Password","alphanum","'.$lang[Field_Alpha].' '.$lang[New_Password].'");
			validator.addValidation("Confirmation","req","'.$lang[Field_Required].$lang[Confirmation].'");
			validator.addValidation("New_Password","password","'.$lang[Field_Pass_Diferent].'");
			</SCRIPT>');
		}
		break;

	default:
			trigger_error("Not existing action", E_USER_WARNING);
		break;
}

include_once 'foot.php';
?>