<?php

/**
 * @author Pantoja Hinojosa Quetzalcoatl
 * @package class
**/
include_once('Resource.class.php');

class Budget extends Resource 
{
	private $Amount;
	private $BudgetID;
	private $Description;
	private $Available;
	
	public function Budget($BudgetID)
	{
		$db=new DBase();
		$res=$db->DB->getAll('SELECT * FROM budget WHERE idbudget=\''.$BudgetID.'\'');
		if (PEAR::isError($res))
			{return($res->getDebugInfo());}
		else 
		{
			$this->Amount=$res[0][amount];
			$this->BudgetID=$res[0][idbudget];
			$this->ExpireDate=$res[0][expires];
			$this->Description=$res[0][description];
			$this->Available=$res[0][available];
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
	
	public function UseBudget($Amount, $Reason)
	{
		$newAmount=$this->Available-$Amount;
		$table_name='budget';
   		$fields_values = array(
			'available'=>$newAmount
   			);
   		$where='idbudget=\''.$this->BudgetID.'\'';
   		$db=new DBase();
   		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_UPDATE,$where);
   		if (PEAR::isError($res))
		{
			print_r($res->getDebugInfo());
			return($res->getDebugInfo());
		}
		else
		{
			$this->Amount=$newAmount;
			$this->Log($Reason,$Amount);
			return(true);
		}
	}
	
	public function Log($Reason, $Amount)
	{
		$table_name='budget_log';
		$fields_values = array(
			'idbudget' => $this->BudgetID,
			'date' => date("Y-m-d"),
			'reason'=>$Reason,
			'amount'=>$Amount,
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
	
	public static function ListBudget($ProjectID)
	{
		$Total=0;
		$TotalAvailable=0;
		$db=new DBase();
    	$res=$db->DB->getAll('SELECT * FROM "budget" WHERE idproject=\''.$ProjectID.'\' ORDER BY expires DESC');
    	if (PEAR::isError($res))
			{return($res->getDebugInfo());}
		if (empty($res))
		{
			print($_SESSION[Nomenclature][No_Budget]);
			return (true);
		}
		print('<table class="std">
				<tr class="std"><td></td><td>'.$_SESSION[Nomenclature][Expire_Date].'</td><td>'.$_SESSION[Nomenclature][Budget].'</td><td>'.$_SESSION[Nomenclature][Amount].'</td><td>'.$_SESSION[Nomenclature][Avaible].'</td></tr>');
		foreach ($res as $Budget)
		{
			$myBudget=new Budget($Budget[idbudget]);
			$semaphore=new Semaphore($myBudget);
			print('
			<tr>
				<td width=16px><img src="');print($semaphore);print('"></td>
				<td>'.$myBudget->ExpireDate.'</td>
				<td><a href="resourcebudget.php?action=details&&BudgetID='.$myBudget->BudgetID.'">'.$myBudget->Description.'</a></td>
				<td>'.$myBudget->Amount.'</td>
				<td>'.$myBudget->Available.'</td>');
			$Total+=$myBudget->Amount;
			$TotalAvailable+=$myBudget->Available;
			if ($_SESSION[User]->ProjectAdmin->ModifyBudget)
			{
				print('<td width=16px><a href="resourcebudget.php?action=delete&&BudgetID='.$myBudget->BudgetID.'"><img src="images/icons/delete.png"><span>'.$GLOBALS[lang][Delete_Message].'</span></a></td>');
			}
			if ($_SESSION[User]->ProjectAdmin->UseBudget&&($semaphore->color==green))
			{
				print('<td width=16px><a href="resourcebudget.php?action=use&&BudgetID='.$myBudget->BudgetID.'"><img src="images/icons/use_budget.png"><span>'.$GLOBALS['Nomenclature'][Use_Budget_Message].'</span></a></td>');
			}
			print('</tr>
			');
		}
		print('
		<tr class="std">
			<td colspan="3">'.$_SESSION[Nomenclature][Total_Budget].'</td><td>'.$Total.'</td><td>'.$TotalAvailable.'</td>
		</tr>
		');
		print('</table>');
		return (true);
	}
	
	public static function Delete($BudgetID)
	{
		$db=new DBase();
   		$query='DELETE FROM "budget" where idbudget=\''.$BudgetID.'\'';
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

	
	public static function AddBudget($Amount,$Description, $ExpireDate,$ProjectID)
	{
		$table_name='budget';
		$fields_values = array(
			'idproject'=>$ProjectID,
			'amount'=>$Amount,
			'description'=>$Description,
   			'expires'=> $ExpireDate,
   			'available'=>$Amount
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
			<tr class="std"><td colspan="5">'.$_SESSION[Nomenclature][Budget].'</td></tr>
			<tr>
				<td width=16px><img src="'.$semaphore->__toString().'"></td>
				<td>'.$this->ExpireDate.'</td>
				<td>'.$this->Description.'</td>
				<td>'.$this->Amount.'</td>
				<td>'.$this->Available.'</td>
			</tr>
		</table>
		</p>
		';
		//now get the log for this budget...
		$db=new DBase();
    	$res=$db->DB->getAll('SELECT * FROM "budget_log" WHERE idbudget=\''.$this->BudgetID.'\' ORDER BY date DESC');
    	if (PEAR::isError($res))
			{return($res->getDebugInfo());}
		else 
		{
			$report.='
				<p>
				<table class="std">
				<tr class="std"><td colspan="4">'.$_SESSION[Nomenclature][Budget_Log].'</td></tr>';
			foreach ($res as $log)
			{
				$report.='				
					<tr>
						<td>'.$log[date].'</td>
						<td>'.$log[reason].'</td>
						<td>'.$log[amount].'</td>
					</tr>';
			}
			$report.='</table>
				</p>';
		}
		return($report);
	}
}
?>