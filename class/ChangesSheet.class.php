<?PHP
/**
*@package class
*@version 1.0
*@author Pantoja Hinojosa Quetzalcoatl
*@todo guardar en la base de datos
*/

class ChangesSheet 
{
   private $SheetID;
   private $Owner;
   private $Date;
   private $ChageProposed;
   private $Description;
   private $Reason;
   private $PlanImpact;
   private $TenicalImplication;
   

   public function ChangesSheet($SheetID) 
   {
       	$db=new DBase();
		$query='SELECT * FROM changes_sheet WHERE idchanges_sheet=\''.$SheetID.'\'';
		$res=$db->DB->getAll($query);
		if (PEAR::isError($res))
		{print_r($res->getDebugInfo());die();}
		$this->Owner=$res[0]['owner'];
		$this->Date=$res[0]['date'];
		$this->ChageProposal=$res[0]['change_proposal'];
		$this->Description=$res[0]['description'];
		$this->Reason=$res[0]['reason'];
		$this->SheetID=$res[0]['sheetid'];
		$this->PlanImpact=$res[0]['plan_impact'];
		$this->TenicalImplication=$res[0]['tecnical_implication'];
   }
   
   public static function AddSheet($ProjectID,$Owner,$Date,$ChageProposal,$Description,$Reason,$PlanImpact,$TenicalImplication)
   {
   		$table_name='changes_sheet';
		$fields_values = array(
			'idproject' => $ProjectID,
			'owner'=>$Owner,
   			'date'=> $Date,
   			'change_proposal' => $ChageProposal,
   			'description' => $Description,
   			'reason' => $Reason,
   			'plan_impact' => $PlanImpact,
   			'tecnical_implication' => $TenicalImplication
   			);
   		$db=new DBase();
   		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_INSERT);
   		if (PEAR::isError($res))
		{
			print_r($res->getDebugInfo());
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=projectchangesh.php?action=show&&message='.$res->getMessage().'&&class=error">');
		}
		else
		{
			Message::ProjectBroadcast($ProjectID,$_SESSION['Nomenclature']['New_ChangeSh_Title'],$_SESSION['Nomenclature']['New_ChangeSh_Message']);
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=projectchangesh.php?action=show&&message='.$GLOBALS[lang][Data_Actualized].'&&class=success">');
		}
   }

   public static function EditSheet($SheetID,$Owner,$Date,$ChageProposal,$Description,$Reason,$PlanImpact,$TenicalImplication) 
   {
   		$table_name='changes_sheet';
   		$fields_values = array(
			'owner'=>$Owner,
   			'date'=> $Date,
   			'change_proposal' => $ChageProposal,
   			'description' => $Description,
   			'reason' => $Reason,
   			'plan_impact' => $PlanImpact,
   			'tecnical_implication' => $TenicalImplication
   			);
   		$where='idchanges_sheet=\''.$SheetID.'\'';
   		$db=new DBase();
   		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_UPDATE,$where);
   		if (PEAR::isError($res))
		{
			print_r($res->getDebugInfo());
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=projectchangesh.php?action=show&&message='.$res->getMessage().'&&class=error">');
		}
		else
		{
			Message::ProjectBroadcast($ProjectID,$_SESSION['Nomenclature']['Edited_ChangeSh_Title'],$_SESSION['Nomenclature']['Edited_ChangeSh_Message']);
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=projectchangesh.php?action=show&&message='.$GLOBALS[lang][Data_Actualized].'&&class=success">');
		}
   }
   
   public static function DeleteSheet($SheetID)
   {
   			$db=new DBase();
   			$query='DELETE FROM changes_sheet where idchanges_sheet=\''.$SheetID.'\'';
   			$res=$db->DB->query($query);
   			if (PEAR::isError($res))
   			{
   				print_r($res->getDebugInfo());
   				print('<meta HTTP-EQUIV="REFRESH" content="0; url=projectchangesh.php?action=show&&message='.$res->getMessage().'&&class=error">');
   			}
   			else 
   			{
   				print('<meta HTTP-EQUIV="REFRESH" content="0; url=projectchangesh.php?action=show&&message='.$GLOBALS[lang]['Deleted_Sheet'].'&&class=success">');
   			}
   }

   public static function SheetsList($projectid) 
   {
   	if($_SESSION[User]->ProjectAdmin->CreateChangesSheet)
		{print('<p><a href="projectchangesh.php?action=add"><img src="images/icons/add.png"><span>'.$GLOBALS['lang']['Add_Message'].'</span></a></p>');}
	$db=new DBase();
	$res=$db->DB->getAll('SELECT * FROM changes_sheet WHERE "idproject"=\''.$projectid.'\' ORDER BY date DESC');
	if(empty($res))
	{
		print($GLOBALS['lang']['empty']);
		return true;
	}
	print('<table class=std>
			<tr class="std"><td>'.$GLOBALS[lang][Date].'</td><td>'.$_SESSION[Nomenclature][Changes_Sheet].'</td></tr>');
   	foreach ($res as $key=>$value)
   	{
		print('<tr');if($key%2){echo ' class=std ';}
   		print ('>
   			<td>'.$value['date'].'</td>
			<td><a ');if($key%2){echo 'class=std ';}
		print('href="projectchangesh.php?action=show&&SheetID='.$value[idchanges_sheet].'">'.$value['change_proposal'].'</a></td>');
		if($_SESSION[User]->ProjectAdmin->EditChangesSheet)
			{print('<td width=16px><a ');if($key%2){echo 'class=std ';} print('href="projectchangesh.php?action=edit&&SheetID='.$value[idchanges_sheet].'"><img src="images/icons/edit.png"><span>'.$GLOBALS['lang']['Edit_Message'].'</span></a></td>');}
   		if($_SESSION[User]->ProjectAdmin->CreateChangesSheet)
   			{print("<td width=16px><a ");if($key%2){echo 'class=std ';} print('href="projectchangesh.php?action=delete&&SheetID='.$value[idchanges_sheet].'"><img src="images/icons/delete.png"><span>'.$GLOBALS['lang']['Delete_Message'].'</span></a></td>');}
   		print("</tr>");
   	}
   	print('</table>');
	return true;
   }
   
   public function __toString()
   {
   	return('
   			<table class="std">
   				<tr><td>'.$GLOBALS['lang']['Owner'].'</td><td>'.$this->Owner.'</td></tr>
   				<tr class="std"><td>'.$GLOBALS['lang']['Date'].'</td><td>'.$this->Date.'</td></tr>
   				<tr><td>'.$GLOBALS['lang']['Change_Proposal'].'</td><td>'.$this->ChageProposal.'</td></tr>
   				<tr class="std"><td>'.$GLOBALS['lang']['Description'].'</td><td>'.$this->Description.'</td></tr>
   				<tr><td>'.$GLOBALS['lang']['Reason'].'</td><td>'.$this->Reason.'</td></tr>
   				<tr class="std"><td>'.$GLOBALS['lang']['Plan_Impact'].'</td><td>'.$this->PlanImpact.'</td></tr>
   				<tr><td>'.$GLOBALS['lang']['Tecnical_Implication'].'</td><td>'.$this->TenicalImplication.'</td></tr>
   			</table>
   			');
   }
   
}
?>