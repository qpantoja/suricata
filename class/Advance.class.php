<?PHP

/**
 * Manages de advances declared by users
 * @author Quetzalcoatl Pantoja Hinojosa
 * @package class
 */

class Advance
{
	private $AdvanceID;
	private $Description;
	private $Date;
	private $Aproved;
	private $TaskPercent;
	
	public function Advance($AdvanceID)
	{
    	$db=new DBase();
    	$res=$db->DB->getAll('SELECT * FROM advance WHERE idadvance=\''.$AdvanceID.'\'');
   		$this->Description=$res[0][description];
   		$this->Date=$res[0][date];
   		if ($res[0][aproved]=='t')
   			{$this->Aproved=true;}
   		else {$this->Aproved=false;}
   		$this->TaskPorcent=$res[0][task_percent];
   		$this->AdvanceID=$AdvanceID;
	}
	
	
	public static function AddAdvance($Description,$TaskPerCent,$TaskID)
	{
		$table_name='advance';
		$fields_values = array(
			'idtask'=>$TaskID,
   			'description'=> $Description,
   			'task_percent' => $TaskPerCent,
   			);
   		$db=new DBase();
   		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_INSERT);
   		if (PEAR::isError($res))
		{
			return($res->getDebugInfo());
		}
		else
		{
			//send a message to the lider??
			//Message::AddEntry($Responsable,$_SESSION['Nomenclature']['New_Task_Assigned'],$_SESSION['Nomenclature']['New_Task_Message']);
			return(true);
		}
	}
	
	public function AceptAdvance()
	{
		$table_name='advance';
   		$fields_values = array(
   			'aproved' => 't',
   			);
   		$where='idadvance=\''.$this->AdvanceID.'\'';
		$db=new DBase();
		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_UPDATE,$where);
   		if (PEAR::isError($res))
		{
			return($res);
		}
		else
		{
			$this->Aproved=true;
			return (true);
		}
	}
	
	public static function __toString()
	{
		print(
		'<table>
			<tr>
				<td>'.$GLOBALS['Nomenclature']['Description'].'</td><td>'.$this->Description.'</td>
				<td>'.$GLOBALS['Nomenclature']['Aproved'].'</td>'.$this->Aproved.'<td>');
			if($this->Aproved)
				{print('<img src="/images/icons/green_dot">');}
			else {print('<img src="/images/icons/red_dot">');}
		print('</td>
				<td>'.$GLOBALS['Nomenclature']['Porcent'].'</td><td>'.$this->TaskPercent.'</td>
			</tr>
		</table>');
	}
}

?>