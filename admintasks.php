<?php

/**
*@package request
*/

include_once 'head.php';

switch ($_GET[action])
{
	case show:
			print("<h1>".$_SESSION[Nomenclature][Task_Admin_Title]."</h1>");
			if($_SESSION['User']->ProjectAdmin->AdminTasks)
			{
				$_SESSION['Project']->ShowTasks();
			}
			else 
			{
				print($lang[No_Privilegies]);
			}
		break;
		
	case showtask:
			print("<h1>".$_SESSION[Nomenclature][Task_Admin_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if($_SESSION['User']->ProjectAdmin->AdminTasks)
			{
				$task=new Task($_GET['TaskID']);
				print($task);
			}
			else 
			{
				print($lang[No_Privilegies]);
			}
		break;

/******************* ADD TASK **************/
	case addtask:
			print("<h1>".$_SESSION[Nomenclature][Task_Admin_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if($_SESSION['User']->ProjectAdmin->AdminTasks)
			{
				if($_POST!=null)
				{
					Task::AddTask($_POST[ActionThreadID],$_POST[Name],$_POST[Description],$_POST[Responsable],$_POST[Cost],$_POST[ProgramedBegin],$_POST[ProgramedEnd],$_POST[Deliverable]);
				}
				else 
				{
					print('<form name="addtask" method="post" action="admintasks.php?action=addtask">');
					print('
						<table class="std">
							<tr><td>'.$_SESSION['Nomenclature']['Name'].'</td><td><input type="text" name="Name"></td></tr>
							<tr class="std"><td>'.$_SESSION['Nomenclature']['Description'].'</td><td><textarea type="textarea" name="Description"></textarea></tr>
							<tr><td>'.$_SESSION['Nomenclature']['Responsable'].'</td>
								<td>
									<select name="Responsable">');
									$db=new DBase();
									$roles=$db->DB->getAll('select iduser from project_has_user where project_has_user.idproject='.$_SESSION[Project]->ProjectID.' ORDER BY iduser ASC');
									if (PEAR::isError($roles))
										{print_r($roles->getDebugInfo());}
									print("\n".'<option selected>'.$GLOBALS['lang']['Select_Any'].'</option>');
									foreach ($roles as $key=>$value)
									{
										print('<option>'.$value[iduser].'</option>');
									}
    								print('
    								</select>
    							</td>
							</tr>
							<tr class="std"><td>'.$_SESSION['Nomenclature']['Cost'].'</td><td><input type="text" name="Cost"></td></tr>
							<tr><td>'.$_SESSION['Nomenclature']['Programed_Begin'].'</td>
								<td>
    								<input type="text" name="ProgramedBegin" value="">
    								<table><tr><td><A HREF="#" onClick="cal.select(document.forms[\'addtask\'].ProgramedBegin,\'anchor1\',\'yyyy-MM-dd\'); return false;"NAME="anchor1" ID="anchor1"><img src="images/icons/calendar.png"></A></td></tr></table>
    							</td>
							</tr>
							<tr class="std"><td>'.$_SESSION['Nomenclature']['Programed_End'].'</td>
								<td>
									<input type="hidden" name="ActionThreadID" value="'.$_GET['ActionThreadID'].'">
    								<input type="text" name="ProgramedEnd" value="">
    								<table><tr><td><A HREF="#" onClick="cal.select(document.forms[\'addtask\'].ProgramedEnd,\'anchor1\',\'yyyy-MM-dd\'); return false;"NAME="anchor1" ID="anchor1"><img src="images/icons/calendar.png"></A></td></tr></table>
    							</td>
							</tr>
							<tr><td>'.$_SESSION['Nomenclature']['Deliverable'].'</td><td><textarea type="textarea" name="Deliverable"></textarea></td></tr>
							<tr class="std">
								<td ><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    							<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    						</tr>
						</table></form>
					');
					print('<SCRIPT language="JavaScript">
					var validator = new Validator("addtask");
					validator.addValidation("Name","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['Name'].'");
					validator.addValidation("Name","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Name'].'");
					validator.addValidation("Description","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Description'].'");
					validator.addValidation("Description","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['Description'].'");
					validator.addValidation("Responsable","dontselect=0","'.$lang[Field_Select].' '.$_SESSION['Nomenclature']['Responsable'].'");
					validator.addValidation("Cost","numeric","'.$lang[Field_Numeric].' '.$_SESSION['Nomenclature']['Cost'].'");
					validator.addValidation("ProgramedBegin","date","'.$lang[Field_Date].' '.$_SESSION['Nomenclature']['Programed_Begin'].'");
					validator.addValidation("ProgramedBegin","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Programed_Begin'].'");
					validator.addValidation("ProgramedEnd","date","'.$lang[Field_Date].' '.$_SESSION['Nomenclature']['Programed_End'].'");
					validator.addValidation("ProgramedEnd","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Programed_End'].'");
					validator.addValidation("Deliverable","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['Deliverables'].'");
					validator.addValidation("Deliverable","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Deliverables'].'");
					validator.addValidation("ProgramedBegin","datecomp=ProgramedEnd","'.$lang['Invalid_Dates'].'");
					</SCRIPT>');
				}
			}
			else 
			{
				print($lang[No_Privilegies]);
			}
		break;
/******************* ADD THREAD LINE **************/
	case addthreadline:
			print("<h1>".$_SESSION[Nomenclature][Task_Admin_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if($_SESSION['User']->ProjectAdmin->AdminTasks)
			{
				if($_POST!=null)
				{
					ActionThread::AddActionThread($_POST['Name'],$_POST['Responsable'],$_POST['Deliverable'],$_SESSION['Project']->ProjectID);
				}
				else 
				{
					print('<form name="createactionthread" method="post" action="admintasks.php?action=addthreadline">');
					print('
						<table class="std">
							<tr><td>'.$_SESSION['Nomenclature']['Name'].'</td><td><input type="text" name="Name"></td></tr>
							<tr class="std"><td>'.$_SESSION['Nomenclature']['Responsable'].'</td>
								<td>
									<select name="Responsable">');
									$db=new DBase();
									$roles=$db->DB->getAll('select iduser from project_has_user where project_has_user.idproject='.$_SESSION[Project]->ProjectID.' ORDER BY iduser ASC');
									if (PEAR::isError($roles))
										{print_r($roles->getDebugInfo());}
									print("\n".'<option selected>'.$GLOBALS['lang']['Select_Any'].'</option>');
									foreach ($roles as $key=>$value)
									{
										print('<option>'.$value[iduser].'</option>');
									}
    								print('
    								</select>
    							</td>
							</tr>
							<tr><td>'.$_SESSION['Nomenclature']['Deliverable'].'</td><td><textarea type="textarea" name="Deliverable"></textarea></td></tr>
							<tr class="std">
								<td ><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    							<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    						</tr>
						</table></form>
					');
					print('<SCRIPT language="JavaScript">
					var validator = new Validator("createactionthread");
					validator.addValidation("Name","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['Name'].'");
					validator.addValidation("Name","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Name'].'");
					validator.addValidation("Responsable","dontselect=0","'.$lang[Field_Select].' '.$_SESSION['Nomenclature']['Responsable'].'");
					validator.addValidation("Deliverable","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['Deliverable'].'");
					validator.addValidation("Deliverable","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Deliverable'].'");
					</SCRIPT>');
				}
			}
			else 
			{
				print($lang[No_Privilegies]);
			}
		break;

/********************** ADD A DEPENDENCY TO THE TASK ********************/
		case adddependency:
			print("<h1>".$_SESSION[Nomenclature][Task_Admin_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if($_SESSION['User']->ProjectAdmin->AdminTasks)
			{
				if($_POST!=null)
				{
					Task::AddDependency($_POST[TaskID],$_POST[Needs]);
				}
				else 
				{
					$db=new DBase();
					print('<form name="adddependecy" method="post" action="admintasks.php?action=adddependency">');
						print('
							<table class="std">
								<tr>
									<td>'.$_SESSION[Nomenclature][Task].'</td>
									<td>'.$_GET[Name].'</td>
								</tr>
								<tr class="std">
									<td>'.$_SESSION[Nomenclature][Needs].'</td>
									<td>
										<select name="Needs">');
											$tasks=$db->DB->getAll('
											SELECT
												idtask,task.name, action_thread.name as action_thread_name
											FROM
												task,action_thread
											WHERE
												action_thread.idproject=\''.$_SESSION[Project]->ProjectID.'\' 
												AND task.idaction_thread=action_thread.idaction_thread;
												');
											if (PEAR::isError($tasks))
												{print_r($tasks->getDebugInfo());}
											print('<option selected>'.$GLOBALS['lang']['Select_Any'].'</option>');
											foreach ($tasks as $key=>$value)
											{
												print('<option value="'.$value[idtask].'">'.$value[name].' ['.$value[action_thread_name].']</option>');
											}
										print('
										</select>
									</td>
								</tr>
								<tr>
									<input name="TaskID" type="hidden" value="'.$_GET[TaskID].'">
									<td ><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    								<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    							</tr>
							</table></form>
						');
						print('<SCRIPT language="JavaScript">
						var validator = new Validator("adddependecy");
						validator.addValidation("Needs","dontselect=0","'.$lang[Field_Select].' '.$lang[SysRole].'");
						</SCRIPT>');
				}
			}
			else 
			{
				print($lang[No_Privilegies]);
			}
		break;
		
/********************** EDIT TASK ********************/
	case edittask:
			print("<h1>".$_SESSION[Nomenclature][Task_Admin_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if($_SESSION['User']->ProjectAdmin->AdminTasks)
			{
				if($_POST!=null)
				{
					Task::EditTask($_POST[TaskID],$_POST[Name],$_POST[Description],$_POST[Responsable],$_POST[Cost],$_POST[ProgramedBegin],$_POST[ProgramedEnd],$_POST[Deliverable]);
				}
				else 
				{
					$task=new Task($_GET['TaskID']);
					print('<form name="edittask" method="post" action="admintasks.php?action=edittask">');
					print('
						<table class="std">
							<tr><td>'.$_SESSION['Nomenclature']['Name'].'</td><td><input type="text" name="Name" value="'.$task->Name.'"></td></tr>
							<tr class="std"><td>'.$_SESSION['Nomenclature']['Description'].'</td><td><textarea type="textarea" name="Description">'.$task->Description.'</textarea></tr>
							<tr><td>'.$_SESSION['Nomenclature']['Responsable'].'</td>
								<td>
									<select name="Responsable">');
									$db=new DBase();
									$roles=$db->DB->getAll('select iduser from project_has_user where project_has_user.idproject='.$_SESSION[Project]->ProjectID.' ORDER BY iduser ASC');
									if (PEAR::isError($roles))
										{print_r($roles->getDebugInfo());}
									print("\n".'<option selected>'.$task->Responsable.'</option>');
									foreach ($roles as $key=>$value)
									{
										print('<option>'.$value[iduser].'</option>');
									}
    								print('
    								</select>
    							</td>
							</tr>
							<tr class="std"><td>'.$_SESSION['Nomenclature']['Cost'].'</td><td><input type="text" name="Cost" value="'.$task->Cost.'"></td></tr>
							<tr><td>'.$_SESSION['Nomenclature']['Programed_Begin'].'</td>
								<td>
    								<input type="text" name="ProgramedBegin" value="'.$task->ProgramedBegin.'">
    								<table><tr><td><A HREF="#" onClick="cal.select(document.forms[\'edittask\'].ProgramedBegin,\'anchor1\',\'yyyy-MM-dd\'); return false;"NAME="anchor1" ID="anchor1"><img src="images/icons/calendar.png"></A></td></tr></table>
    							</td>
							</tr>
							<tr class="std"><td>'.$_SESSION['Nomenclature']['Programed_End'].'</td>
								<td>
    								<input type="text" name="ProgramedEnd" value="'.$task->ProgramedEnd.'">
    								<table><tr><td><A HREF="#" onClick="cal.select(document.forms[\'edittask\'].ProgramedEnd,\'anchor1\',\'yyyy-MM-dd\'); return false;"NAME="anchor1" ID="anchor1"><img src="images/icons/calendar.png"></A></td></tr></table>
    							</td>
							</tr>
							<tr><td>'.$_SESSION['Nomenclature']['Deliverable'].'</td><td><textarea type="textarea" name="Deliverable">'.$task->Deliverable.'</textarea></td></tr>
							<tr class="std">
								<input type="hidden" name="TaskID" value="'.$task->TaskID.'">
								<td ><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    							<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    						</tr>
						</table></form>
					');
					print('<SCRIPT language="JavaScript">
					var validator = new Validator("edittask");
					validator.addValidation("Name","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['Name'].'");
					validator.addValidation("Name","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Name'].'");
					validator.addValidation("Description","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Description'].'");
					validator.addValidation("Description","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['Description'].'");
					validator.addValidation("Cost","numeric","'.$lang[Field_Numeric].' '.$_SESSION['Nomenclature']['Cost'].'");
					validator.addValidation("ProgramedBegin","date","'.$lang[Field_Date].' '.$_SESSION['Nomenclature']['Programed_Begin'].'");
					validator.addValidation("ProgramedBegin","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Programed_Begin'].'");
					validator.addValidation("ProgramedEnd","date","'.$lang[Field_Date].' '.$_SESSION['Nomenclature']['Programed_End'].'");
					validator.addValidation("ProgramedEnd","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Programed_End'].'");
					validator.addValidation("Deliverable","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['Deliverables'].'");
					validator.addValidation("Deliverable","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Deliverables'].'");
					validator.addValidation("ProgramedBegin","datecomp=ProgramedEnd","'.$lang['Invalid_Dates'].'");
					</SCRIPT>');
					
					
					print('<p>
						<a href="admintasks.php?action=adddependency&&TaskID='.$_GET[TaskID].'&&Name='.$task->Name.'"><img src="images/icons/add.png"><span>'.$_SESSION['Nomenclature']['Add_Dependency'].'</span></a>
						<table class="std">');
							$dependency=$db->DB->getAll('
							select 
								dependency.idtask,needs,name,description
							from 
								dependency, task 
							where 
								dependency.idtask='.$_GET[TaskID].' 
								and dependency.needs=task.idtask');
							if (PEAR::isError($dependency))
								{print_r($dependency->getDebugInfo());}
							foreach ($dependency as $key=>$value)
							{
							print('<tr');if($key%2){echo " class=std";}print('>
									<td>['.$value[name].'] '.$value[description].'</td>
									<td width=16px><a href="admintasks.php?action=deldependency&&TaskID='.$value[idtask].'&&Needs='.$value[needs].'"><img src="images/icons/delete.png"><span>'.$lang['Delete_Message'].'</span></a></td>
								</tr>');
							}
						print('</table>
						</p>');
					
				}
			}
			else 
			{
				print($lang[No_Privilegies]);
			}
		break;
		
/******************* EDIT THREAD LINE **************************/		
	case editthreadline:
			print("<h1>".$_SESSION[Nomenclature][Task_Admin_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if($_SESSION['User']->ProjectAdmin->AdminTasks)
			{
				if($_POST!=null)
				{
					ActionThread::EditActionThread($_POST['ActionThreadID'],$_POST['Name'],$_POST['Responsable'],$_POST['Deliverable']);
				}
				else 
				{
					$ActionThread=new ActionThread($_GET['ActionThreadID']);
					print('<form name="editactionthread" method="post" action="admintasks.php?action=editthreadline">');
					print('
						<table class="std">
							<tr><td>'.$_SESSION['Nomenclature']['Name'].'</td><td><input type="text" name="Name" value="'.$ActionThread->Name.'"></td></tr>
							<tr class="std"><td>'.$_SESSION['Nomenclature']['Responsable'].'</td>
								<td>
									<select name="Responsable">');
									$db=new DBase();
									$roles=$db->DB->getAll('select iduser from project_has_user where project_has_user.idproject='.$_SESSION[Project]->ProjectID.' ORDER BY iduser ASC');
									if (PEAR::isError($roles))
										{print_r($roles->getDebugInfo());}
									print("\n".'<option selected>'.$ActionThread->Responsable.'</option>');
									foreach ($roles as $key=>$value)
									{
										print('<option>'.$value[iduser].'</option>');
									}
    								print('
    								</select>
    							</td>
							</tr>
							<tr><td>'.$_SESSION['Nomenclature']['Deliverable'].'</td><td><textarea type="textarea" name="Deliverable">'.$ActionThread->Deliverable.'</textarea></td></tr>
							<tr class="std">
								<input name="ActionThreadID" type="hidden" value="'.$_GET['ActionThreadID'].'">
								<td ><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    							<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    						</tr>
						</table></form>
					');
					print('<SCRIPT language="JavaScript">
					var validator = new Validator("editactionthread");
					validator.addValidation("Name","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['Name'].'");
					validator.addValidation("Name","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Name'].'");
					validator.addValidation("Deliverable","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['Deliverable'].'");
					validator.addValidation("Deliverable","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Deliverable'].'");
					</SCRIPT>');
				}
			}
			else 
			{
				print('Not enouhgth privilegies');
			}
		break;

	case deletetask:
			if($_SESSION['User']->ProjectAdmin->AdminTasks)
			{
				Task::DeleteTask($_GET[TaskID]);
			}
			else 
			{
				print($lang[No_Privilegies]);
			}
		break;
		
	case deletethreadline:
			if($_SESSION['User']->ProjectAdmin->AdminTasks)
			{
				ActionThread::DeleteThread($_GET[ActionThreadID]);
			}
			else 
			{
				print($lang[No_Privilegies]);
			}
		break;
	
	case deldependency:
			if($_SESSION['User']->ProjectAdmin->AdminTasks)
			{
				Task::DeleteDependency($_GET[TaskID],$_GET[Needs]);
			}
			else 
			{
				print($lang[No_Privilegies]);
			}
		break;

	default:
		print('Action not found');
		break;
}

include_once 'foot.php';
?>