<?php

/**
*@package class
*/

class SecuenceTable
{
	private $table;
	
	public function SecuenceTable($ProjectID)
	{
		//TASK - BEGIN - END - Needs
		$db=new DBase();
		$res=$db->DB->getAll('
		SELECT 
			task.idtask, task.name, task.programed_begin, task.programed_end
		FROM 
			task,action_thread 
		WHERE 
			task.idaction_thread=action_thread.idaction_thread 
			and action_thread.idproject=\''.$ProjectID.'\'');
		if (PEAR::isError($res))
			{print_r($res->getDebugInfo());}
		$this->table='<table class="std">
				<tr class="std"><td>'.$_SESSION[Nomenclature][Task].'</td><td>'.$_SESSION[Nomenclature][Begin_Date].'</td><td>'.$_SESSION[Nomenclature][End_Date].'</td><td>'.$_SESSION[Nomenclature][Needs].'</td><tr>';
			foreach ($res as $key=>$tasks)
			{
				$this->table.='
					<tr>
						<td>'.$tasks[name].'</td>
						<td>'.$tasks[programed_begin].'</td>
						<td>'.$tasks[programed_end].'</td>
						<td><table class="sub">';
						
						$dep=$db->DB->getAll('
						SELECT
							dependency.idtask,needs,name,description
						FROM
							dependency, task
						WHERE
							dependency.idtask=\''.$tasks[idtask].'\'
							AND dependency.needs=task.idtask');
						foreach($dep as $dependency)
						{
							$this->table.='<tr><td>['.$dependency[name].'] '.$dependency[description].'</td></tr>';
						}
						
						
				$this->table.='</table></td></tr>';
			}
		$this->table.='</table>';
	}
	
	public function __toString()
	{
		return ($this->table);
	}
}
?>