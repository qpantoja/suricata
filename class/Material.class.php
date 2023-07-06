<?php

/**
 * @author Pantoja Hinojosa Quetzalcoatl
 * @package class
**/
include_once('Resource.class.php');

class Material extends Resource 
{
	private $MaterialID;
	private $Description;
	private $SerialNumber;
	
	public function Material($MaterialID)
	{
		$db=new DBase();
		$res=$db->DB->getAll('SELECT * FROM material WHERE idmaterial=\''.$MaterialID.'\'');
		if (PEAR::isError($res))
			{return($res->getDebugInfo());}
		else 
		{
			$this->MaterialID=$res[0][idmaterial];
			$this->ExpireDate=$res[0][expires];
			$this->Description=$res[0][description];
			$this->SerialNumber=$res[0][serial_number];
			$this->Expire();
		}
	}
	
	public function Expire()
	{
		$ExpireDate=explode("-", $this->ExpireDate);
		$ExpireDate=mktime(0, 0, 0, $ExpireDate[1],$ExpireDate[2],$ExpireDate[0]);
		if(mktime()>$ExpireDate)
		{
			$this->Expired=true;
		}
		else 
		{
			$this->Expired=false;
		}
	}
	
	
	public function Log($Reason)
	{
		$table_name='material_log';
		$fields_values = array(
			'idmaterial' => $this->MaterialID,
			'date' => date("Y-m-d"),
			'reason'=>$Reason,
   			);
   		$db=new DBase();
   		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_INSERT);
   		if (PEAR::isError($res))
		{
			print_r($res->getDebugInfo());
			return ($res->getDebugInfo());
		}
		else
		{
			return (true);
		}
	}
	
	public static function ListMaterial($ProjectID)
	{
		$db=new DBase();
    	$res=$db->DB->getAll('SELECT * FROM "material" WHERE idproject=\''.$ProjectID.'\' ORDER BY expires DESC');
    	if (PEAR::isError($res))
			{return($res->getDebugInfo());}
		if (empty($res))
		{
			print($_SESSION[Nomenclature][No_Material]);
			return (true);
		}
		print('<table class="std">
				<tr class="std"><td></td><td>'.$_SESSION[Nomenclature][Expire_Date].'</td><td>'.$_SESSION[Nomenclature][Material].'</td><td>'.$_SESSION[Nomenclature][Serial].'</td></tr>');
		foreach ($res as $Material)
		{
			$myMaterial=new Material($Material[idmaterial]);
			$semaphore=new Semaphore($myMaterial);
			print('
			<tr>
				<td width=16px><img src="');print($semaphore);print('"></td>
				<td>'.$myMaterial->ExpireDate.'</td>
				<td><a href="resourcemat.php?action=details&&MaterialID='.$myMaterial->MaterialID.'">'.$myMaterial->Description.'</a></td>
				<td>'.$myMaterial->SerialNumber.'</td>');
			if ($_SESSION[User]->ProjectAdmin->AdminMaterial)
			{
				print('<td width=16px><a href="resourcemat.php?action=delete&&MaterialID='.$myMaterial->MaterialID.'"><img src="images/icons/delete.png"><span>'.$GLOBALS[lang][Delete_Message].'</span></a></td>');
			}
			/*if ($_SESSION[User]->ProjectAdmin->AdminMaterial&&($semaphore->color==green))  //May be in a future to asign to a task
			{
				print('<td width=16px><a href="resourcebudget.php?action=use&&BudgetID='.$myMaterial->MaterialID.'"><img src="images/icons/use_budget.png"><span>'.$GLOBALS['Nomenclature'][Use_Budget_Message].'</span></a></td>');
			}*/
			print('</tr>
			');
		}
		print('</table>');
		return (true);
	}
	
	public static function Delete($MaterialID)
	{
		$db=new DBase();
   		$query='DELETE FROM "material" where idmaterial=\''.$MaterialID.'\'';
   		$res=$db->DB->query($query);
   		if (PEAR::isError($res))
   		{
   			return($res->getDebugInfo());
   		}
   		else 
   		{
   			return (true);
   		}
	}

	
	public static function AddMaterial($Description,$SerialNumber,$ExpireDate,$ProjectID)
	{
		$table_name='material';
		$fields_values = array(
			'idproject'=>$ProjectID,
			'serial_number'=>$SerialNumber,
			'description'=>$Description,
   			'expires'=> $ExpireDate
   			);
   		$db=new DBase();
   		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_INSERT);
   		if (PEAR::isError($res))
		{
			return($res->getDebugInfo());
		}
		else
		{
			return (true);
		}
	}
	
	public function __get($attribute)
	{
		return ($this->$attribute);
	}
	
	public function __toString()
	{
		$semaphore=new Semaphore($this);
		$report='
		<p>
		<table class="std">
			<tr class="std"><td colspan="4">'.$_SESSION[Nomenclature][Material].'</td></tr>
			<tr>
				<td width=16px><img src="'.$semaphore->__toString().'"></td>
				<td>'.$this->ExpireDate.'</td>
				<td>'.$this->Description.'</td>
				<td>'.$this->SerialNumber.'</td>
			</tr>
		</table>
		</p>
		';
		//now get the log for this budget... NO LOG FOR NOW
		/* 
		$db=new DBase();
    	$res=$db->DB->getAll('SELECT * FROM "material_log" WHERE idmaterial=\''.$this->MaterialID.'\' ORDER BY date DESC');
    	if (PEAR::isError($res))
			{return($res->getDebugInfo());}
		else 
		{
			$report.='
				<p>
				<table class="std">
				<tr class="std"><td colspan="2">'.$_SESSION[Nomenclature][Material_Log].'</td></tr>';
			foreach ($res as $log)
			{
				$report.='				
					<tr>
						<td>'.$log[date].'</td>
						<td>'.$log[reason].'</td>
					</tr>';
			}
			$report.='</table>
				</p>';
		}*/
		return($report);
	}
}
?>