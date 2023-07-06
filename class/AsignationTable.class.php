<?php

/**
*@author Quetzalcoatl Pantoja Hinojosa
*@package class
*/

class AsignationTable
{
	private $Table;
	
	public function AsignationTable($ProjectID)
	{
		//TASK - Responsable - COST
		$db=new DBase();
		
		$res=$db->DB->getAll('
		SELECT 
			task.name, task.responsable, cost 
		FROM 
			task,action_thread 
		WHERE 
			task.idaction_thread=action_thread.idaction_thread 
			and action_thread.idproject=\''.$ProjectID.'\'');
		if (PEAR::isError($res))
			{print_r($res->getDebugInfo());}
		$this->Table='<table class="std">
				<tr class="std"><td>'.$_SESSION['Nomenclature'][Task].'</td><td>'.$_SESSION['Nomenclature'][Responsable].'</td><td>'.$_SESSION['Nomenclature'][Cost].'</td><tr>';
			$TotalCost=0;
			foreach ($res as $key=>$tasks)
			{
				$this->Table.='<tr><td>'.$tasks[name].'</td><td>'.$tasks[responsable].'</td><td>'.$tasks[cost].'</td></tr>';
				$TotalCost+=$tasks[cost];
			}
			$this->Table.='<tr><td>'.$GLOBALS['Nomenclature']['Total_Cost'].'</td><td></td><td>'.$TotalCost.'</td></tr>';
		$this->Table.='</table>';
	}
	
	public function __toString()
	{
		return ($this->Table);
	}
}

?>