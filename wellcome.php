<?php

/**
*@package request
*/

include_once 'head.php';

if($_SESSION['User'])
{
	$report=new Report();
	$report->UserReport($_SESSION['User']);
}
else
{
	print ('<div align="center" class="content"><h1>'.$lang[Wellcome].'</h1><br>
        <img src="'.$config[logo].'"></img>
        <form name="login" method="post" action="auth.php">
        <table>
        	<tr>
        		<td>'.$lang[Login].':</td>
        		<td><input type="text" name="User"></td>
        	</tr>
        	<tr>
        		<td>'.$lang[Password].':</td>
        		<td><input type="password" name="Password"></td>
        	</tr>
        	<tr>
        		<td><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
        		<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
        	</tr></table>
        	</form>');
	print('<SCRIPT language="JavaScript">
		var validator = new Validator("login");
		validator.addValidation("User","alphanum","'.$lang[Field_Alpha].' '.$lang['User'].'");
		validator.addValidation("User","req","'.$lang[Field_Required].' '.$lang['User'].'");
		validator.addValidation("Password","alphanum","'.$lang[Field_Alpha].' '.$lang['Password'].'");
		validator.addValidation("Password","req","'.$lang[Field_Required].' '.$lang['Password'].'");
	</SCRIPT></div>');
}
include_once('foot.php');
?>
