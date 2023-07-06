<?PHP

/**
* @package class
* @author Quetzalcoatl Pantoja Hinojosa
*/

class ActionThread
{
	private $ActionThreadID;
	private $Responsable;
	private $Name;
	private $Deliverable;

	public function ActionThread($ActionThreadID)
	{
		$db=new DBase();
		$res=$db->DB->getAll('SELECT * FROM action_thread WHERE idaction_thread=\''.$ActionThreadID.'\'');
		if (PEAR::isError($res))
			{print_r($res->getDebugInfo());}
		$this->ActionThreadID=$res[0]['idaction_thread'];
		$this->Responsable=$res[0]['responsable'];
		$this->Name=$res[0]['name'];
		$this->Deliverable=$res[0]['deliverable'];
	}

	public static function EditActionThread($ActionThreadID,$Name,$Responsable,$Deliverable)
	{
		$table_name='action_thread';
   		$fields_values = array(
   			'name' => $Name,
   			'responsable' => $Responsable,
   			'deliverable' => $Deliverable
   			);
   		$where='idaction_thread=\''.$ActionThreadID.'\'';
		$db=new DBase();
		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_UPDATE,$where);
		if (PEAR::isError($res))
		{
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=admintasks.php?action=show&&message='.$res->getMessage().'&&class=error">');
		}
		else
		{
			Message::AddEntry($Responsable,$_SESSION['Nomenclature']['Edited_Thread'],$_SESSION['Nomenclature']['Edited_Thread_Message']);
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=admintasks.php?action=show&&message='.$GLOBALS[lang][Data_Actualized].'&&class=success">');
		}
	}
	
	public static function AddActionThread($Name,$Responsable,$Deliverables,$ProjectID)
	{
		$table_name='action_thread';
		$fields_values = array(
			'idproject'=>$ProjectID,
			'name'=>$Name,
   			'responsable'=> $Responsable,
   			'deliverable' => $Deliverables
   			);
   		$db=new DBase();
   		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_INSERT);
   		if (PEAR::isError($res))
		{
			print_r($res->getDebugInfo());
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=admintasks.php?action=show&&message='.$res->getMessage().'&&class=error">');
		}
		else
		{
			Message::AddEntry($Responsable,$_SESSION['Nomenclature']['New_Thread_Assigned'],$_SESSION['Nomenclature']['New_Thread_Message']);
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=admintasks.php?action=show&&message='.$GLOBALS[lang][Data_Actualized].'&&class=success">');
		}
	}
	
	public static function DeleteThread($ActionTreadID)
	{
		$db=new DBase();
   		$res=$db->DB->query('DELETE FROM action_thread where idaction_thread=\''.$ActionTreadID.'\'');
   		if (PEAR::isError($res))
   		{
			print_r($res->getDebugInfo());
   			print('<meta HTTP-EQUIV="REFRESH" content="0; url=admintasks.php?action=show&&message='.$res->getMessage().'&&class=error">');
   		}
   		else
   		{
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=admintasks.php?action=show&&message='.$_SESSION['Nomenclature']['Deleted_ActionThread'].'&&class=success">');
   		}
	}
	
	public function ListThread()
	{
		$string='
			<table class="std">
				<tr class="std">
					<td width=135px>'.$this->Name.'</td>
					<td  width=209px>'.$this->Deliverable.'</td>
					<td>'.$this->Responsable.'</td>
					<td width=16px><a href="admintasks.php?action=addtask&&ActionThreadID='.$this->ActionThreadID.'"><img src="images/icons/add.png"><span>'.$_SESSION['Nomenclature']['Add_Task_Message'].'</span></a></td>
					<td width=16px><a href="admintasks.php?action=editthreadline&&ActionThreadID='.$this->ActionThreadID.'"><img src="images/icons/edit.png"><span>'.$_SESSION['Nomenclature']['Edit_Thread_Message'].'</span></a></td>
					<td width=16px><a href="admintasks.php?action=deletethreadline&&ActionThreadID='.$this->ActionThreadID.'"><img src="images/icons/delete.png"><span>'.$_SESSION['Nomenclature']['Delete_Thread_Message'].'</span></a></td>
				</tr>';
		$db=new DBase();
		$res=$db->DB->getAll('SELECT * FROM task WHERE idaction_thread=\''.$this->ActionThreadID.'\'');
		if (PEAR::isError($res))
			{print_r($res->getDebugInfo());}
		else
		{
			if(!empty($res))
			{
				foreach ($res as $Task)
				{
					$task=new Task($Task['idtask']);
					$semaphore=new Semaphore($task);
					$string.='
					<tr>
						<td width=135px>
							<a href="admintasks.php?action=showtask&&TaskID='.$task->TaskID.'">'.$task->Name.'</a>
						</td>
						<td width=209px>
							<a href="admintasks.php?action=showtask&&TaskID='.$task->TaskID.'">'.$task->Description.'</a>
						</td>
						<td>'.$task->Responsable.'</td>
						<td width=16px><img src="'.$semaphore->__toString().'"></td>
						<td width=16px><a href="admintasks.php?action=edittask&&TaskID='.$task->TaskID.'"><img src="images/icons/edit.png"><span>'.$GLOBALS['lang']['Edit_Message'].'</span></a></td>
						<td width=16px><a href="admintasks.php?action=deletetask&&TaskID='.$task->TaskID.'"><img src="images/icons/delete.png"><span>'.$GLOBALS['lang']['Delete_Message'].'</span></a></td>
					</tr>';	
				}
			}
			else 
			{
				$string.='
				<tr>
					<td>'.$GLOBALS['lang']['empty'].'</td>
				</tr>';
			}
		}
		$string.='
			</table>';
		print($string);
	}
	
	public function __get($get)
	{
		return($this->$get);
	}
	
	public function __toString()
	{
		$string='
			<table class="std">
				<tr class="std">
					<td width=135px>'.$this->Name.'</td>
					<td  width=209px>'.$this->Deliverable.'</td>
					<td colspan="2">'.$this->Responsable.'</td>
				</tr>';
		$db=new DBase();
		$res=$db->DB->getAll('SELECT * FROM task WHERE idaction_thread=\''.$this->ActionThreadID.'\'');
		if (PEAR::isError($res))
			{print_r($res->getDebugInfo());}
		else
		{
			if(!empty($res))
			{
				foreach ($res as $Task)
				{
					$task=new Task($Task['idtask']);
					$semaphore=new Semaphore($task);
					$string.='
					<tr>
						<td width=135px>
							<a href="admintasks.php?action=showtask&&TaskID='.$task->TaskID.'">'.$task->Name.'</a>
						</td>
						<td width=209px>
							<a href="admintasks.php?action=showtask&&TaskID='.$task->TaskID.'">'.$task->Description.'</a>
						</td>
						<td>'.$task->Responsable.'</td>
						<td width=16px><img src="'.$semaphore->__toString().'"></td>
					</tr>';	
				}
			}
			else 
			{
				$string.='
				<tr>
					<td>'.$GLOBALS['lang']['empty'].'</td>
				</tr>';
			}
		}
		$string.='
			</table>';
		return($string);
	}
}
?>