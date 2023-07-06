<?PHP

/**
* @package class
* @author Quetzalcoatl Pantoja Hinojosa
*/

class Task
{
	private $TaskID;
	private $ActionThreadID;
	private $Name;//
	private $Description;
	private $Responsable;
	private $Cost;
	private $BeginDate;
	private $EndDate;
	private $ProgramedBegin;
	private $ProgramedEnd;
	private $Deliverable;
	//private $Finished;
	
	public function Task($TaskID)
	{
		$db=new DBase();
		$res=$db->DB->getAll('SELECT * FROM task WHERE idtask=\''.$TaskID.'\'');
		if (PEAR::isError($res))
			{print_r($res->getDebugInfo());}
		$this->TaskID=$res[0]['idtask'];
		$this->ActionThreadID=$res[0]['idaction_thread'];
		$this->Name=$res[0]['name'];
		$this->Description=$res[0]['description'];
		$this->Responsable=$res[0]['responsable'];
		$this->Cost=$res[0]['cost'];
		$this->BeginDate=$res[0]['begin_date'];
		$this->EndDate=$res[0]['end_date'];
		$this->ProgramedBegin=$res[0]['programed_begin'];
		$this->ProgramedEnd=$res[0]['programed_end'];
		$this->Deliverable=$res[0]['deliverable'];
		/*if($res[0]['finished']==t)
			{$this->Finished=true;}
		else 
			{$this->Finished=false;}*/
	}
	
	public function DeclareRealBegin()
	{
		//$this->BeginDate=date("YYYY-mm-dd");
		$table_name='task';
   		$fields_values = array(
   			'begin_date' => date("Y-m-d"),
   			);
   		$where='idtask=\''.$this->TaskID.'\'';
		$db=new DBase();
		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_UPDATE,$where);
   		if (PEAR::isError($res))
		{
			return($res);
		}
		else
		{
			return (true);
		}
	}
	
	public function DeclareRealEnd()
	{
		//$this->EndDate=date("YYYY-mm-dd");
		$table_name='task';
   		$fields_values = array(
   			'end_date' => date("Y-m-d"),
   			);
   		$where='idtask=\''.$this->TaskID.'\'';
		$db=new DBase();
		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_UPDATE,$where);
   		if (PEAR::isError($res))
		{
			return($res);
		}
		else
		{
			return (true);
		}
	}
	

	public static function AddTask($ActionThreadID,$Name,$Description,$Responsable,$Cost,$ProgramedBegin,$ProgramedEnd,$Deliverable)
	{
		$table_name='task';
		$fields_values = array(
			'idaction_thread'=>$ActionThreadID,
			'name'=>$Name,
   			'description'=> $Description,
   			'responsable'=> $Responsable,
   			'programed_begin' => $ProgramedBegin,
   			'programed_end' => $ProgramedEnd,
   			'deliverable' => $Deliverable
   			);
   		//optional fields
   		if (!empty($Cost))
   			{$fields_values['cost']= $Cost;}
   		$db=new DBase();
   		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_INSERT);
   		if (PEAR::isError($res))
		{
			print_r($res->getDebugInfo());
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=admintasks.php?action=show&&message='.$res->getMessage().'&&class=error">');
		}
		else
		{
			//send a message to that user
			Message::AddEntry($Responsable,$_SESSION['Nomenclature']['New_Task_Assigned'],$_SESSION['Nomenclature']['New_Task_Message']);
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=admintasks.php?action=show&&message='.$GLOBALS[lang][Data_Actualized].'&&class=success">');
		}
	}
	
	public static function EditTask($TaskID,$Name,$Description,$Responsable,$Cost=0,$ProgramedBegin,$ProgramedEnd,$Deliverable)
	{
		$table_name='task';
   		$fields_values = array(
			'name'=>$Name,
   			'description'=> $Description,
   			'responsable'=> $Responsable,
   			'programed_begin' => $ProgramedBegin,
   			'programed_end' => $ProgramedEnd,
   			'deliverable' => $Deliverable
   			);
   		if (!empty($Cost))
   			{$fields_values['cost']= $Cost;}
   		$where='idtask=\''.$TaskID.'\'';
		$db=new DBase();
		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_UPDATE,$where);
   		if (PEAR::isError($res))
		{
			print_r($res->getDebugInfo());
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=admintasks.php?action=show&&message='.$res->getMessage().'&&class=error">');
		}
		else
		{
			Message::AddEntry($Responsable,$_SESSION['Nomenclature']['Edited_Task'],$_SESSION['Nomenclature']['Edited_Task_Message']);
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=admintasks.php?action=show&&message='.$GLOBALS[lang][Data_Actualized].'&&class=success">');
		}
	}
	
	public static function DeleteTask($TaskID)
	{
		$db=new DBase();
   		$res=$db->DB->query('DELETE FROM task where idtask=\''.$TaskID.'\'');
   		if (PEAR::isError($res))
   		{
			print_r($res->getDebugInfo());
   			print('<meta HTTP-EQUIV="REFRESH" content="0; url=admintasks.php?action=show&&message='.$res->getMessage().'&&class=error">');
   		}
   		else
   		{
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=admintasks.php?action=show&&message='.$_SESSION['Nomenclature']['Deleted_Task'].'&&class=success">');
   		}
	}
	
	public static function DeleteDependency($TaskID,$Needs)
	{
		$db=new DBase();
   		$res=$db->DB->query('DELETE FROM dependency where idtask=\''.$TaskID.'\' AND needs=\''.$Needs.'\'');
   		if (PEAR::isError($res))
   		{
			print_r($res->getDebugInfo());
   			print('<meta HTTP-EQUIV="REFRESH" content="0; url=admintasks.php?action=edittask&&TaskID='.$TaskID.'&&message='.$res->getMessage().'&&class=error">');
   		}
   		else
   		{
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=admintasks.php?action=edittask&&TaskID='.$TaskID.'&&message='.$_SESSION['Nomenclature']['Deleted_Dependency'].'&&class=success">');
   		}
	}
	
	public static function AddDependency($TaskID,$Needs)
	{
		$table_name='dependency';
		$fields_values = array(
			'idtask'=>$TaskID,
			'needs'=>$Needs,
   			);
   		$db=new DBase();
   		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_INSERT);
   		if (PEAR::isError($res))
		{
			print_r($res->getDebugInfo());
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=admintasks.php?action=edittask&&TaskID='.$TaskID.'&&message='.$res->getMessage().'&&class=error">');
		}
		else{print('<meta HTTP-EQUIV="REFRESH" content="0; url=admintasks.php?action=edittask&&TaskID='.$TaskID.'&&message='.$GLOBALS[lang][Data_Actualized].'&&class=success">');}
	}
	
	public function __toString()
	{
		$string='
			<table class="std">
				<tr class="std"><td>'.$_SESSION['Nomenclature']['Name'].'</td><td>'.$this->Name.'</td></tr>
				<tr><td>'.$_SESSION['Nomenclature']['Description'].'</td><td>'.$this->Description.'</td></tr>
				<tr><td>'.$_SESSION['Nomenclature']['Responsable'].'</td><td>'.$this->Responsable.'</td></tr>
				<tr><td>'.$_SESSION['Nomenclature']['Cost'].'</td><td>'.$this->Cost.'</td></tr>
				<tr><td>'.$_SESSION['Nomenclature']['Begin_Date'].'</td><td>'.$this->BeginDate.'</td></tr>
				<tr><td>'.$_SESSION['Nomenclature']['End_Date'].'</td><td>'.$this->EndDate.'</td></tr>
				<tr><td>'.$_SESSION['Nomenclature']['Programed_Begin'].'</td><td>'.$this->ProgramedBegin.'</td></tr>
				<tr><td>'.$_SESSION['Nomenclature']['Programed_End'].'</td><td>'.$this->ProgramedEnd.'</td></tr>
				<tr><td>'.$_SESSION['Nomenclature']['Deliverable'].'</td><td>'.$this->Deliverable.'</td></tr>
			</table>
			';
		$db=new DBase();
		$res=$db->DB->getAll('SELECT * FROM advance WHERE idtask=\''.$this->TaskID.'\'');
		if(!empty($res))
		{
			$string.='<p>
			<table class="std">
			<tr class="std"><td>'.$_SESSION[Nomenclature][Description].'</td><td>'.$_SESSION[Nomenclature][Task_Percent].'</td></tr>';
			$Total=0;
			foreach($res as $advance)
			{
				$Total+=$advance[task_percent];
				$string.='<tr>
				<td>'.$advance[description].'</td>
				<td>'.$advance[task_percent].'%</td>
				<td>';
				if($advance[aproved]=='t')
					{$string.='<img src="images/icons/green_dot.png">';}
				else
					{$string.='<img src="images/icons/red_dot.png">';}
				$string.='</td>
				</tr>';
			}
			$string.='
			<tr><td>'.$_SESSION[Nomenclature][Total_Advance].'</td><td>'.$Total.'%</td></tr>
			</table></p>';
		}
		$dependency=$db->DB->getAll('
							select 
								dependency.idtask,needs,name,description
							from 
								dependency, task 
							where 
								dependency.idtask='.$this->TaskID.' 
								and dependency.needs=task.idtask');
		if (PEAR::isError($dependency))
								{return($string);}
		if(!empty($dependency))
		{
		$string.='<p>
					<table class="std">';
					foreach ($dependency as $key=>$value)
					{
						$string.='<tr';if($key%2){$string.=" class=std";}$string.='>
							<td>['.$value[name].'] '.$value[description].'</td>
							</tr>';
					}
					$string.='</table>
				</p>';
		}
		return($string);
	}
	
	public function __get($get)
	{
		return ($this->$get);
	}
}
?>