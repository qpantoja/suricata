<?php

/**
*@package class
*/

class ProposalSolicitude
{
	private $ProposalSolicitudeID;
	private $JobDescription;
	private $ClientRequest;
	private $Deliverables;
	private $Supplies;
	private $AprovalsRequired;
	private $ContractType;
	private $PayConditions;
	private $ProjectProgram;
	private $InstructionsContentFormat;
	private $ExpiralDate;

	
	public function ProposalSolicitude($ProjectID)
	{
		$db= new DBase();
		$res=$db->DB->getAll('SELECT * FROM proposal_solicitude WHERE idproject=\''.$ProjectID.'\'');
		if(empty($res))
			{
				print($GLOBALS[lang]['empty']);
				return;
			}
		$this->ProposalSolicitudeID=$res[0][idproposal_solicitude];
		$this->JobDescription=$res[0][job_description];
		$this->ClientRequest=$res[0][client_request];
		$this->Deliverables=$res[0][deliverables];
		$this->Supplies=$res[0][supplies];
		$this->AprovalsRequired=$res[0][aprovals_required];
		$this->ContractType=$res[0][contract_type];
		$this->PayConditions=$res[0][pay_conditions];
		$this->ProjectProgram=$res[0][project_program];
		$this->InstructionsContentFormat=$res[0][instructions_content_format];
		$this->ExpiralDate=$res[0][expiral_date];
	}
	
	public static function AddProposal($JobDescription,$ClientRequest,$Deliverables,$Supplies,$AprovalsRequired,$ContractType,$PayConditions,$ProjectProgram,$InstructionsContentFormat,$ExpiralDate)
	{
		$table_name='proposal_solicitude';
		$fields_values = array(
			'idproject'=>$_SESSION[Project]->ProjectID,
			'job_description'=>$JobDescription,
   			'client_request'=> $ClientRequest,
   			'deliverables' => $Deliverables,
   			'supplies'=>$Supplies,
   			'aprovals_required'=>$AprovalsRequired,
   			'contract_type'=>$ContractType,
   			'pay_conditions'=>$PayConditions,
   			'project_program'=>$ProjectProgram,
   			'instructions_content_format'=>$InstructionsContentFormat,
   			'expiral_date'=>$ExpiralDate,
   			);
   		$db=new DBase();
   		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_INSERT);
   		if (PEAR::isError($res))
		{
			print_r($res->getDebugInfo());
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=proposalsolicitude.php?action=show&&message='.$res->getMessage().'&&class=error">');
		}
		else{print('<meta HTTP-EQUIV="REFRESH" content="0; url=proposalsolicitude.php?action=show&&message='.$GLOBALS[lang][Data_Actualized].'&&class=success">');}
	}
	
	public static function EditProposal($ProposalSolicitudeID,$JobDescription,$ClientRequest,$Deliverables,$Supplies,$AprovalsRequired,$ContractType,$PayConditions,$ProjectProgram,$InstructionsContentFormat,$ExpiralDate)
	{
   		$table_name='proposal_solicitude';
   		$fields_values = array(
			'idproject'=>$_SESSION[Project]->ProjectID,
			'job_description'=>$JobDescription,
   			'client_request'=> $ClientRequest,
   			'deliverables' => $Deliverables,
   			'supplies'=>$Supplies,
   			'aprovals_required'=>$AprovalsRequired,
   			'contract_type'=>$ContractType,
   			'pay_conditions'=>$PayConditions,
   			'project_program'=>$ProjectProgram,
   			'instructions_content_format'=>$InstructionsContentFormat,
   			'expiral_date'=>$ExpiralDate,
   			);
   		$where='idproposal_solicitude=\''.$ProposalSolicitudeID.'\'';
   		$db=new DBase();
   		$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_UPDATE,$where);
   		if (PEAR::isError($res))
		{
			print_r($res->getDebugInfo());
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=proposalsolicitude.php?action=show&&message='.$res->getMessage().'&&class=error">');
		}
		else{print('<meta HTTP-EQUIV="REFRESH" content="0; url=proposalsolicitude.php?action=show&&message='.$GLOBALS[lang][Data_Actualized].'&&class=success">');}
	}
	
	Public static function DeleteProposal($ProposalSolicitudeID)
	{
   		$db=new DBase();
   		$query='DELETE FROM proposal_solicitude where idproposal_solicitude=\''.$ProposalSolicitudeID.'\'';
   		$res=$db->DB->query($query);
   		if (PEAR::isError($res))
   		{
   			print_r($res->getDebugInfo());
   			print('<meta HTTP-EQUIV="REFRESH" content="0; url=proposalsolicitude.php?action=show&&message='.$res->getMessage().'&&class=error">');
   		}
   		else 
   		{
   			print('<meta HTTP-EQUIV="REFRESH" content="0; url=proposalsolicitude.php?action=show&&message='.$_SESSION['Nomenclature']['Proposal_Sheet_Deleted'].": ".$UserID.'&&class=success">');
   		}
	}
	
	public function __toString()
	{
		$string='
		<table class="std">
		<tr><td>'.$_SESSION['Nomenclature']['Job_Description'].'</td><td>'.$this->JobDescription.'</td></tr>
		<tr class="std"><td>'.$_SESSION['Nomenclature']['ClientRequest'].'</td><td>'.$this->ClientRequest.'</td></tr>
		<tr><td>'.$_SESSION['Nomenclature']['Deliverable'].'</td><td>'.$this->Deliverables.'</td></tr>
		<tr class="std"><td>'.$_SESSION['Nomenclature']['Supplies'].'</td><td>'.$this->Supplies.'</td></tr>
		<tr><td>'.$_SESSION['Nomenclature']['AprovalsRequired'].'</td><td>'.$this->AprovalsRequired.'</td></tr>
		<tr class="std"><td>'.$_SESSION['Nomenclature']['ContractType'].'</td><td>'.$this->ContractType.'</td></tr>
		<tr><td>'.$_SESSION['Nomenclature']['PayConditions'].'</td><td>'.$this->PayConditions.'</td></tr>
		<tr class="std"><td>'.$_SESSION['Nomenclature']['ProjectProgram'].'</td><td>'.$this->ProjectProgram.'</td></tr>
		<tr><td>'.$_SESSION['Nomenclature']['InstructionsContentFormat'].'</td><td>'.$this->InstructionsContentFormat.'</td></tr>
		<tr class="std"><td>'.$_SESSION['Nomenclature']['ExpiralDate'].'</td><td>'.$this->ExpiralDate.'</td></tr>
		</table>
		';
		return ($string);
	}
	
	public function __get($attribute)
	{
		return $this->$attribute;
	}
}

?>