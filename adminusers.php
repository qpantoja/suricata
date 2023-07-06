<?php

/**
*@package request
*/

include_once 'head.php';

switch ($_GET[action])
{
	case show:
			print("<h1>$lang[Admin_Users_Title]</h1>");
			if ($_GET[UserID]!=null)
			{
				$_SESSION[User]->ShowUser($_GET[UserID]);
			}
			else
			{
				if($_SESSION[User]->SysAdmin->CreateUser)
   					{print('<p><a href="adminusers.php?action=add"><img src="images/icons/add.png"><span>'.$GLOBALS['lang']['Add_User'].'</span></a></p>');}
				$_SESSION[User]->ShowUsers();
			}
		break;
		
	case add:
			print("<h1>$lang[Admin_Users_Title]</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if ($_POST!=null)
			{
				$_SESSION[User]->CreateUser($_POST[UserID],$_POST[Name],$_POST[FatherLastname],$_POST[MotherLastname],
					$_POST[Phone],$_POST[Email],$_POST[Address],$_POST[Birthday],$_POST[Country], $_POST[State],
					$_POST[City], $_POST[Sysrole]);
			}
			else
			{
				$data=array('UserID'=>'text','Name'=>'text','FatherLastname'=>'text','MotherLastname'=>'text','Phone'=>'text','Email'=>'text','Address'=>'textarea','Birthday'=>'text','Country'=>'text','State'=>'text','City'=>'text');
				print('
				<form name="editowndata" method="post" action="adminusers.php?action=add">
				<table class=std>				
				<tr><td>'.$lang[UserID].'</td><td><input type="text" name="UserID" value=""></td></tr>
				<tr class=std><td>'.$lang[Name].'</td><td><input type="text" name="Name" value=""></td></tr>
    			<tr><td>'.$lang[FatherLastname].'</td><td><input type="text" name="FatherLastname" value=""></td></tr>
    			<tr class=std><td>'.$lang[MotherLastname].'</td><td><input type="text" name="MotherLastname" value=""></td></tr>
    			<tr><td>'.$lang[Phone].'</td><td><input type="text" name="Phone" value=""></td></tr>
    			<tr class=std><td>'.$lang[Email].'</td><td><input type="text" name="Email" value=""></td></tr>
    			<tr><td>'.$lang[Address].'</td><td><textarea type="textarea" name="Address"></textarea></td></tr>
    			<tr class=std>
    				<td>'.$lang[Birthday].' '.$lang[Date_Format].'</td>
    				<td>
    					<input type="text" name="Birthday" value="">
    					<table><tr><td><A HREF="#" onClick="cal.select(document.forms[\'editowndata\'].Birthday,\'anchor1\',\'yyyy-MM-dd\'); return false;"NAME="anchor1" ID="anchor1"><img src="images/icons/calendar.png"></A></td></tr></table>
    				</td>
    			</tr>
    			<tr><td>'.$lang[Country].'</td>
    				<td><select name="Country" >');
    					foreach($lang['Country_List'] as $country)
    					{
    							print("<option>$country</option>");
    					}
    				print('
    				</select></td>
    			</tr>
    			<tr class=std><td>'.$lang[State].'</td><td><input type="text" name="State" value=""></td></tr>
    			<tr><td>'.$lang[City].'</td><td><input type="text" name="City" value=""></td></tr>
    				<tr class=std>
    					<td>'.$lang[SysRole].'</td>
    					<td>
							<select name="Sysrole" >');
    							$db=new DBase();
    							$roles=$db->DB->getAll('SELECT idsysrole FROM sysrole');
    							if (PEAR::isError($roles))
    								{print_r($roles->getDebugInfo());}
    							print('<option selected>'.$GLOBALS['lang']['Select_Any'].'</option>');
    							foreach ($roles as $key=>$value)
    							{
    								print('<option>'.$value[idsysrole].'</option>');
    							}
							print('</select>
    					</td>
    				</tr>
    				<tr>
    					<td><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    					<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    				</tr>
    			</table></form>');
				//prints the validator...
				print('<SCRIPT language="JavaScript">
				var validator = new Validator("editowndata");
				validator.addValidation("UserID","alphanum","'.$lang[Field_Alpha].' '.$lang[Name].'");
				validator.addValidation("UserID","req","'.$lang[Field_Required].' '.$lang[Name].'");
				validator.addValidation("Name","alpha","'.$lang[Field_Alpha].' '.$lang[Name].'");
				validator.addValidation("Name","req","'.$lang[Field_Required].' '.$lang[Name].'");
				validator.addValidation("FatherLastname","alpha","'.$lang[Field_Alpha].' '.$lang[FatherLastname].'");
				validator.addValidation("FatherLastname","req","'.$lang[Field_Required].' '.$lang[FatherLastname].'");
				validator.addValidation("MotherLastname","alpha","'.$lang[Field_Alpha].' '.$lang[MotherLastname].'");
				validator.addValidation("MotherLastname","req","'.$lang[Field_Required].' '.$lang[MotherLastname].'");
				validator.addValidation("Birthday","date","'.$lang[Field_Date].' '.$lang[Birthday].'");
				validator.addValidation("Birthday","req","'.$lang[Field_Required].' '.$lang[Birthday].'");
				validator.addValidation("Phone","num","'.$lang[Field_Numeric].' '.$lang[Phone].'");
				validator.addValidation("Email","email","'.$lang[Field_Email].' '.$lang[Email].'");
				validator.addValidation("State","alpha","'.$lang[Field_Alpha].' '.$lang[State].'");
				validator.addValidation("City","alpha","'.$lang[Field_Alpha].' '.$lang[City].'");
				validator.addValidation("Sysrole","dontselect=0","'.$lang[Field_Select].' '.$lang[SysRole].'");
				</SCRIPT>');
			}
		break;

	case edit:
			print("<h1>$lang[Admin_Users_Title]</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if ($_POST!=null)
			{
				$error=$_SESSION[User]->EditUser($_POST[Sysrole],$_POST[NewUserID],$_POST[UserID],$_POST[Name],$_POST[FatherLastname],$_POST[MotherLastname],$_POST[Phone],$_POST[Email],$_POST[Address],$_POST[Birthday],$_POST[Country],$_POST[State],$_POST[City]);
			}
			else 
			{
				//shows the form
				$user=new User($_GET[UserID]);
				//$data=array('Name'=>'text','FatherLastname'=>'text','MotherLastname'=>'text','Phone'=>'text','Email'=>'text','Address'=>'textarea','Birthday'=>'text','Country'=>'text','State'=>'text','City'=>'text');
				print('
				<form name="editowndata" method="post" action="adminusers.php?action=edit">
				<table class=std>');
				print('
				<tr><td>'.$lang[UserID].'</td><td><input type="text" name="NewUserID" value="'.$user->UserID.'"></td></tr>
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
    				<input name="UserID" type="hidden" value="'.$_GET[UserID].'">
    				<tr>
    					<td>'.$lang[SysRole].'</td>
    					<td>
							<select name="Sysrole" >');
    							$db=new DBase();
    							$roles=$db->DB->getAll('SELECT idsysrole FROM sysrole');
    							if (PEAR::isError($roles))
    								{print_r($roles->getDebugInfo());}
    							print('<option selected>'.$user->SysRole.'</option>');
    							foreach ($roles as $key=>$value)
    							{
    								if ($user->SysRole!=$value[idsysrole])
    									print('<option>'.$value[idsysrole].'</option>');
    							}
							print('</select>
    					</td>
    				</tr>
    				<tr class=std>
    					<td><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    					<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    				</tr>
    			</table></form>');
				//prints the validator...
				print('<SCRIPT language="JavaScript">
				var validator = new Validator("editowndata");
				validator.addValidation("NewUserID","alphanum","'.$lang[Field_Alpha].' '.$lang[Name].'");
				validator.addValidation("NewUserID","req","'.$lang[Field_Required].' '.$lang[Name].'");
				validator.addValidation("Name","alpha","'.$lang[Field_Alpha].' '.$lang[Name].'");
				validator.addValidation("Name","req","'.$lang[Field_Required].' '.$lang[Name].'");
				validator.addValidation("FatherLastname","alpha","'.$lang[Field_Alpha].' '.$lang[FatherLastname].'");
				validator.addValidation("FatherLastname","req","'.$lang[Field_Required].' '.$lang[FatherLastname].'");
				validator.addValidation("MotherLastname","alpha","'.$lang[Field_Alpha].' '.$lang[MotherLastname].'");
				validator.addValidation("MotherLastname","req","'.$lang[Field_Required].' '.$lang[MotherLastname].'");
				validator.addValidation("Birthday","date","'.$lang[Field_Date].' '.$lang[Birthday].'");
				validator.addValidation("Birthday","req","'.$lang[Field_Required].' '.$lang[Birthday].'");
				validator.addValidation("Phone","num","'.$lang[Field_Numeric].' '.$lang[Phone].'");
				validator.addValidation("Email","email","'.$lang[Field_Email].' '.$lang[Email].'");
				validator.addValidation("State","alpha","'.$lang[Field_Alpha].' '.$lang[State].'");
				validator.addValidation("City","alpha","'.$lang[Field_Alpha].' '.$lang[City].'");
				</SCRIPT>');
			}
		break;
	
	case changepassword:
			print("<h1>$lang[Admin_Users_Title]</h1>");
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
							<tr><td>'.$lang[New_Password].'</td><td><input type="password" name="New_Password" ></td></tr>
							<tr class=std><td>'.$lang[Confirmation].'</td><td><input type="password" name="Confirmation" ></td></tr>
							<td><input name="UserID" type="hidden" value="'.$_GET[UserID].'"><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    						<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
						</table></form>
					<SCRIPT language="JavaScript">
					var validator = new Validator("changepasswd");
					validator.addValidation("New_Password","req","'.$lang[Field_Required].' '.$lang[New_Password].'");
					validator.addValidation("New_Password","alphanum","'.$lang[Field_Alpha].' '.$lang[New_Password].'");
					validator.addValidation("Confirmation","req","'.$lang[Field_Required].' '.$lang[Confirmation].'");
					validator.addValidation("New_Password","password","'.$lang[Field_Pass_Diferent].'");
					</SCRIPT>');
			}
		break;

	case delete:
			$_SESSION[User]->DeleteUser($_GET[UserID]);
		break;

	default:
		break;
}

include_once 'foot.php';
?>