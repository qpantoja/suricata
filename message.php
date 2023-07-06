<?php

/**
*@package request
*/

include_once 'head.php';

switch ($_GET[action])
{
	case show:
			print('<h1>'.$lang[Show_Messages_Title].'</h1>
				<a href="message.php?action=add"><img src="images/icons/message.png"><span>'.$lang['New_Message_Message'].'</span></a>
				<p>');
			Message::ListMessages();
			print('</p>');
		break;
		
	case add:
			print("<h1>$lang[Show_Messages_Title]</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if($_POST!=null)
			{
				if(Message::AddEntry($_POST['UserID'],$_POST['Title'],$_POST['Detail']))
				{
					print('<meta HTTP-EQUIV="REFRESH" content="0; url=message.php?action=show&&message='.$lang['Message_Sent'].'&&class=success">');
				}
				else 
				{
					print('<meta HTTP-EQUIV="REFRESH" content="0; url=message.php?action=show&&message='.$lang['Message_Not_Sent'].'&&class=error">');
				}
			}
			else 
			{
					print('<form name="newmessage" method="post" action="message.php?action=add">');
					print('
						<table class="std">
							<tr><td>'.$lang['User'].'</td>
								<td>
									<select name="UserID">');
									$db=new DBase();
									$roles=$db->DB->getAll('SELECT iduser FROM "user" ORDER BY iduser');
									if (PEAR::isError($roles))
										{print_r($roles->getDebugInfo());}
									print("\n".'<option selected>'.$lang['Select_Any'].'</option>');
									foreach ($roles as $key=>$value)
									{
										print('<option>'.$value[iduser].'</option>');
									}
    								print('
    								</select>
    							</td>
							</tr>
							<tr class="std"><td>'.$lang['Title'].'</td><td><input type="text" name="Title"></td></tr>
							<tr><td>'.$lang['Detail'].'</td><td><textarea type="textarea" name="Detail"></textarea></td></tr>
							<tr class="std">
								<td ><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    							<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    						</tr>
						</table></form>
					');
					print('<SCRIPT language="JavaScript">
					var validator = new Validator("newmessage");
					validator.addValidation("UserID","dontselect=0","'.$lang[Field_Select].' '.$lang['User'].'");
					validator.addValidation("Title","alpha","'.$lang[Field_Alpha].' '.$lang['Title'].'");
					validator.addValidation("Title","req","'.$lang[Field_Required].' '.$lang['Title'].'");
					validator.addValidation("Detail","alpha","'.$lang[Field_Alpha].' '.$lang['Detail'].'");
					validator.addValidation("Detail","req","'.$lang[Field_Required].' '.$lang['Detail'].'");
					</SCRIPT>');
			}
		break;
		
	case showdetails:
			print("<h1>$lang[Show_Messages_Title]</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			$message = new Message($_GET['MessageID']);
			print($message);
		break;
		
	case delete:
			Message::DelEntry($_GET['MessageID']);
		break;

	default:
		break;
}

include_once 'foot.php';
?>