<?php

/**
*@package request
*/

include_once 'head.php';

switch ($_GET[action])
{
	case show:
			print("<h1>$lang[Admin_DataBase_Title]</h1>");
			if($_SESSION[User]->SysAdmin->Restore || $_SESSION[User]->SysAdmin->Backup)
			{
				if($_SESSION[User]->SysAdmin->Backup)
				{
				print('
				<form action="admindatabase.php?action=backup" method="post">
					<p>'.$lang[Backup_Message].'</p>
					<input type="submit" name="submit" value="'.$lang[Backup_Button].'">
				</form>');
				}
				if ($_SESSION[User]->SysAdmin->Restore)
				{
				print('
					<form action="admindatabase.php?action=restore" enctype="multipart/form-data" method="post">
					<p>'.$lang[Restore_Message].'</p>
						<input type="file" name="UploadFile" size="40">
						<p><input type="submit" value="'.$lang[Restore_Button].'"></p>
					</form>');
				}
			}
			else 
			{
				print($lang[No_Privilegies]);
			}
		break;
		
	case backup:
			print("<h1>$lang[Admin_DataBase_Title]</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			$db=new DBase();
			$db->Backup();
			print('<p><a href="admindatabase.php?action=purge">'.$lang['Purge_Temporal'].'</a></p>');
		break;

	case restore:
			print("<h1>$lang[Admin_DataBase_Title]</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			move_uploaded_file ($_FILES['UploadFile'] ['tmp_name'],"db/{$_FILES['UploadFile'] ['name']}");
			$db=new DBase();
			$db->Restore($_FILES['UploadFile'] ['name']);
		break;

	case purge:
		print("<h1>$lang[Admin_DataBase_Title]</h1>");
		print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
		if ($_SESSION[User]->SysAdmin->Backup)
		{
			foreach(glob('db/suricata.backup*') as $fn)
			{
				unlink($fn);
			}
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=index.php?message='.$lang['Temporal_Purged'].'&&class=success">');
		}
		break;
		
	default:
			print($lang[No_Privilegies]);
		break;
}

include_once 'foot.php';
?>