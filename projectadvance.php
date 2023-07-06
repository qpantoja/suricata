<?php

/**
 * @author Pantoja Hinojosa Quetzalcoatl
 * @package request
**/

include_once 'head.php';

switch ($_GET[action])
{		
	case show:
			print("<h1>".$_SESSION[Nomenclature][Show_Advances_Title]."</h1>");
			$db=new DBase();
			$res=$db->DB->getAll('SELECT * FROM task, action_thread 
			WHERE task.idaction_thread=action_thread.idaction_thread 
			AND action_thread.idproject=\''.$_SESSION[Project]->ProjectID.'\' ORDER BY task.programed_begin ASC');
			
			foreach ($res as $task)
			{
				$t=new Task($task[idtask]);
				$semaphore=new Semaphore($t);
				print('<p>
					<table class="std">
					<tr class="std">
						<td width=16px><img src="');print($semaphore);print('"></td>
						<td colspan="4"><a href="projectadvance.php?action=showtask&&TaskID='.$t->TaskID.'">'.$t->Name.'</a></td>
					</tr>');
				$db2=new DBase();
				$res2=$db2->DB->getAll('SELECT * FROM advance WHERE idtask=\''.$t->TaskID.'\' ORDER BY aproved ASC');
				if (!empty($res2))
				{
					foreach ($res2 as $advance)
					{
						print('<tr><td></td>
						<td>'.$advance[description].'</td>
						<td>'.$advance[task_percent].'%</td>
						');
						if($advance[aproved]=='t')
						{print('<td width=16px><a><img src="images/icons/green_dot.png"><span>'.$_SESSION[Nomenclature][Advance_Acepted].'</span></a></td>');}
						else
						{print('<td width=16px><a href="projectadvance.php?action=acept&&AdvanceID='.$advance[idadvance].'"><img src="images/icons/tick.png"><span>'.$_SESSION[Nomenclature][Acept_Advance].'</span></a></td>');}
						print('
							<td width=16px><a href="projectadvance.php?action=sendmessage&&UserID='.$t->Responsable.'"><img src="images/icons/message.png"><span>'.$lang['New_Message_Message'].'</span></a></td>
						</tr>');
					}
				}
				else 
				{
					print('<tr><td></td><td>'.$lang['empty'].'</td></tr>');
				}
				print('
					</table></p>');
			}
			break;

	case acept:
			$Advance=new Advance($_GET[AdvanceID]);
			$res=$Advance->AceptAdvance();
			if ($res==true)
				print('<meta HTTP-EQUIV="REFRESH" content="0; url=projectadvance.php?action=show&&message='.$_SESSION[Nomenclature][Advance_Acepted].'&&class=success">');	
			else 
				print('<meta HTTP-EQUIV="REFRESH" content="0; url=projectadvance.php?action=show&&message='.$res->getMessage().'&&class=error">');		
		break;
		
	case generate:
			print('<h1>'.$_SESSION[Nomenclature][Generate_Advances_Title].'</h1>');
			//Mostrar las tareas
			$db=new DBase();
			$res=$db->DB->getAll('SELECT * FROM task, action_thread 
			WHERE task.responsable=\''.$_SESSION[User]->UserID.'\' 
			AND task.idaction_thread=action_thread.idaction_thread 
			AND action_thread.idproject=\''.$_SESSION[Project]->ProjectID.'\' ORDER BY task.programed_begin ASC');
			
			if($res!=null)
			{
				$asigned='<p>
					<table class="std">
					<tr class="std"><td></td><td>'.$_SESSION[Nomenclature][Task].'</td><td>'.$_SESSION[Nomenclature][Description].'</td></tr>';
				$ended='<p>'.$_SESSION[Nomenclature][Ended_Task].'
					<table class="std">
					<tr class="std"><td></td><td>'.$_SESSION[Nomenclature][Task].'</td><td>'.$_SESSION[Nomenclature][Description].'</td></tr>';
				foreach ($res as $tasks)
				{
					$task=new Task($tasks[idtask]);
					$semaphore=new Semaphore($task);
					if($tasks[end_date]!='')
					{
							$ended.='<tr>
								<td><img src="'.$semaphore->__toString().'"></td>
								<td><a href="projectadvance.php?action=showtask&&TaskID='.$task->TaskID.'">'.$task->Name.'</a></td>
								<td>'.$task->Description.'</td>
								</tr>';
					}
					else 
					{
						$asigned.='<tr>
							<td><img src="'.$semaphore->__toString().'"></td>
							<td><a href="projectadvance.php?action=showtask&&TaskID='.$task->TaskID.'">'.$task->Name.'</a></td>
							<td>'.$task->Description.'</td>
							';
						if ($task->BeginDate==null)
							{$asigned.='<td><a href="projectadvance.php?action=realbegin&&TaskID='.$task->TaskID.'"><img src="images/icons/start.png"><span>'.$_SESSION[Nomenclature][Start_Task].'</span></a></td></tr>';}
						else
							{$asigned.='<td><a href="projectadvance.php?action=realend&&TaskID='.$task->TaskID.'"><img src="images/icons/end.png"><span>'.$_SESSION[Nomenclature][End_Task].'</span></a></td>
									<td><a href="projectadvance.php?action=reportadvance&&TaskID='.$task->TaskID.'"><img src="images/icons/advance.png"><span>'.$_SESSION[Nomenclature][Add_Advance].'</span></a></td></tr>';}
					}
				}
				$asigned.='
						</table></p>';
				$ended.='
						</table></p>';
			}
			else 
			{print($_SESSION[Nomenclature][No_Asigned_Tasks]);}
			print($asigned.$ended);
		break;
		
	case reportadvance:
			print('<h1>'.$_SESSION[Nomenclature][Generate_Advances_Title].'</h1>');
			if($_SESSION[User]->ProjectAdmin->GenAdvances)
			{
				if($_POST!=null)
				{
					$res=Advance::AddAdvance($_POST[Description],$_POST[Task_Percent],$_POST[TaskID]);
				if ($res==true)
					print('<meta HTTP-EQUIV="REFRESH" content="0; url=projectadvance.php?action=generate&&message='.$_SESSION[Nomenclature][Advance_Reported].'&&class=success">');	
				else 
					print('<meta HTTP-EQUIV="REFRESH" content="0; url=projectadvance.php?action=generate&&message='.$res->getMessage().'&&class=error">');		
				}
				else 
				{
					$db=new DBase();
					$res=$db->DB->getAll('select sum(task_percent) from advance where idtask=\''.$_GET[TaskID].'\'');
					$MaxPercent=100-$res[0][sum];
					print('
					<form name="reportadvance" method="post" action="projectadvance.php?action=reportadvance">
					<table class="std">
						<tr><td>'.$_SESSION[Nomenclature][Task_Percent].' (0-'.$MaxPercent.')</td><td><input type="text" name="Task_Percent"></td></tr>
						<tr class=std><td>'.$_SESSION[Nomenclature][Description].'</td><td><textarea name="Description"></textarea></td></tr>
						<tr>
						<input name="TaskID" type="hidden" value="'.$_GET[TaskID].'">
						<td><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
						<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
						</tr>
					</table>
					</form>
					');
					$MaxPercent+=0.1;//validation says its strictly less
					print('<SCRIPT language="JavaScript">
					var validator = new Validator("reportadvance");
					validator.addValidation("Task_Percent","num","'.$lang[Field_Numeric].' '.$_SESSION[Nomenclature]['Task_Percent'].'");
					validator.addValidation("Task_Percent","lessthan='.$MaxPercent.'","'.$lang[Field_Greater].' '.$_SESSION[Nomenclature]['Task_Percent'].'");
					validator.addValidation("Task_Percent","req","'.$lang[Field_Required].' '.$_SESSION[Nomenclature]['Task_Percent'].'");
					validator.addValidation("Description","alphanum","'.$lang[Field_Alpha].' '.$_SESSION[Nomenclature]['Description'].'");
					validator.addValidation("Description","req","'.$lang[Field_Required].' '.$_SESSION[Nomenclature]['Description'].'");
					</SCRIPT>');
				}
			}
			else 
			{
				print($lang[No_Privilegies]);
			}
		break;
		
	case realbegin:
			if($_SESSION[User]->ProjectAdmin->GenAdvances)
			{
				$task=new Task($_GET[TaskID]);
				$res=$task->DeclareRealBegin();
				if ($res==true)
					print('<meta HTTP-EQUIV="REFRESH" content="0; url=projectadvance.php?action=generate&&message='.$_SESSION[Nomenclature][Task_Begin_Reported].'&&class=success">');	
				else
					print('<meta HTTP-EQUIV="REFRESH" content="0; url=projectadvance.php?action=generate&&message='.$res->getMessage().'&&class=error">');	
			}
			else 
			{
				print($lang[No_Privilegies]);
			}
		break;
		
	case realend:
			if($_SESSION[User]->ProjectAdmin->GenAdvances)
			{
				$task=new Task($_GET[TaskID]);
				$res=$task->DeclareRealEnd();
				if ($res==true)
					print('<meta HTTP-EQUIV="REFRESH" content="0; url=projectadvance.php?action=generate&&message='.$_SESSION[Nomenclature][Task_End_Reported].'&&class=success">');	
				else 
					print('<meta HTTP-EQUIV="REFRESH" content="0; url=projectadvance.php?action=generate&&message='.$res->getMessage().'&&class=error">');		
			}
			else 
			{
				print($lang[No_Privilegies]);
			}
		break;
		
	case sendmessage:
		print("<h1>$lang[Show_Messages_Title]</h1>");
		print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
		if($_POST!=null)
		{
			if(Message::AddEntry($_POST['UserID'],$_POST['Title'],$_POST['Detail']))
				{print('<meta HTTP-EQUIV="REFRESH" content="0; url=projectadvance.php?action=show&&message='.$lang['Message_Sent'].'&&class=success">');}
			else 
				{print('<meta HTTP-EQUIV="REFRESH" content="0; url=projectadvance.php?action=show&&message='.$lang['Message_Not_Sent'].'&&class=error">');}
		}
		else 
		{
				print('<form name="newmessage" method="post" action="projectadvance.php?action=sendmessage">');
				print('
					<table class="std">
						<tr><td>'.$lang['User'].'</td>
							<td>
								<input type="hidden" name="UserID" value="'.$_GET[UserID].'">
								'.$_GET[UserID].'
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
				validator.addValidation("Title","alpha","'.$lang[Field_Alpha].' '.$lang['Title'].'");
				validator.addValidation("Title","req","'.$lang[Field_Required].' '.$lang['Title'].'");
				validator.addValidation("Detail","alpha","'.$lang[Field_Alpha].' '.$lang['Detail'].'");
				validator.addValidation("Detail","req","'.$lang[Field_Required].' '.$lang['Detail'].'");
				</SCRIPT>');
		}
		break;
		
	case showtask:
			print('<h1>'.$_SESSION[Nomenclature][Show_Advances_Title].'</h1>
			<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			$task=new Task($_GET[TaskID]);
			print($task);
		break;
		
	default:
		break;
}

include_once 'foot.php';
?>