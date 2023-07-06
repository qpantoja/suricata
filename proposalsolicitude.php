<?php

/**
*@package request
*/

include_once 'head.php';

switch ($_GET[action])
{
	case show:
			print("<h1>".$_SESSION[Nomenclature][Proposal_Solicitude_Title]."</h1>");
			if($_SESSION[User]->ProjectAdmin->ShowPSolicitude)
			{
				$proposal=new ProposalSolicitude($_SESSION[Project]->ProjectID);
				if($proposal->ProposalSolicitudeID!=null)
				{
					print('<table><tr>');
					if($_SESSION[User]->ProjectAdmin->EditPSolicitude)
						{print ('<td><a href="proposalsolicitude.php?action=edit&&ProposalSolicitudeID='.$proposal->ProposalSolicitudeID.'"><img src="images/icons/edit.png"><span>'.$lang['Edit_Message'].'</span></a></td>');}
					if($_SESSION[User]->ProjectAdmin->DeletePSolicitude)
						{print ('<td><a href="proposalsolicitude.php?action=delete&&ProposalSolicitudeID='.$proposal->ProposalSolicitudeID.'"><img src="images/icons/delete.png"><span>'.$lang['Delete_Message'].'</span></a></td>');}
					print('</tr></table><p>');print ($proposal);print('</p>');
				}
				else 
				{
					if($_SESSION[User]->ProjectAdmin->CreatePSolicitude)
					{print ('<p><a href="proposalsolicitude.php?action=add"><img src="images/icons/add.png"><span>'.$lang['Add_Message'].'</span></a></p>');}
				}
			}
			else 
			{print('Not enougth permisions');}
		break;
		
	case add:
			print("<h1>".$_SESSION[Nomenclature][Proposal_Solicitude_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if($_SESSION[User]->ProjectAdmin->CreatePSolicitude)
			{
				if($_POST!=null)
				{
					ProposalSolicitude::AddProposal($_POST['JobDescription'],$_POST['ClientRequest'],$_POST['Deliverable'],$_POST['Supplies'],$_POST['AprovalsRequired'],$_POST['ContractType'],$_POST['PayConditions'],$_POST['ProjectProgram'],$_POST['InstructionsContentFormat'],$_POST['ExpiralDate']);
				}
				else 
				{
					print('<form name="createpsolicitude" method="post" action="proposalsolicitude.php?action=add">');
					print('
						<table class="std">
							<tr><td>'.$_SESSION['Nomenclature']['Job_Description'].'</td><td><textarea type="textarea" name="JobDescription"></textarea></td></tr>
							<tr class="std"><td>'.$_SESSION['Nomenclature']['ClientRequest'].'</td><td><textarea type="textarea" name="ClientRequest"></textarea></td></tr>
							<tr><td>'.$_SESSION['Nomenclature']['Deliverable'].'</td><td><textarea type="textarea" name="Deliverable"></textarea></td></tr>
							<tr class="std"><td>'.$_SESSION['Nomenclature']['Supplies'].'</td><td><textarea type="textarea" name="Supplies"></textarea></td></tr>
							<tr><td>'.$_SESSION['Nomenclature']['AprovalsRequired'].'</td><td><textarea type="textarea" name="AprovalsRequired"></textarea></td></tr>
							<tr class="std"><td>'.$_SESSION['Nomenclature']['ContractType'].'</td><td><textarea type="textarea" name="ContractType"></textarea></td></tr>
							<tr><td>'.$_SESSION['Nomenclature']['PayConditions'].'</td><td><textarea type="textarea" name="PayConditions"></textarea></td></tr>
							<tr class="std"><td>'.$_SESSION['Nomenclature']['ProjectProgram'].'</td><td><textarea type="textarea" name="ProjectProgram"></textarea></td></tr>
							<tr><td>'.$_SESSION['Nomenclature']['InstructionsContentFormat'].'</td><td><textarea type="textarea" name="InstructionsContentFormat"></textarea></td></tr>
							<tr class="std">
								<td>'.$_SESSION['Nomenclature']['ExpiralDate'].'</td>
								<td>
									<input type="text" name="ExpiralDate"><table><tr><td><A HREF="#" onClick="cal.select(document.forms[\'createpsolicitude\'].ExpiralDate,\'anchor1\',\'yyyy-MM-dd\'); return false;"NAME="anchor1" ID="anchor1"><img src="images/icons/calendar.png"></A></td></tr></table>
								</td>
								</tr>
							<tr>
								<td ><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    							<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    						</tr>
						</table></form>
					');
					print('<SCRIPT language="JavaScript">
					var validator = new Validator("createpsolicitude");
					validator.addValidation("JobDescription","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['Job_Description'].'");
					validator.addValidation("JobDescription","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Job_Description'].'");
					validator.addValidation("ClientRequest","alphanum","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['ClientRequest'].'");
					validator.addValidation("ClientRequest","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['ClientRequest'].'");
					validator.addValidation("Deliverable","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['Deliverable'].'");
					validator.addValidation("Deliverable","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Deliverable'].'");
					validator.addValidation("Supplies","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['Supplies'].'");
					validator.addValidation("Supplies","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Supplies'].'");
					validator.addValidation("AprovalsRequired","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['AprovalsRequired'].'");
					validator.addValidation("AprovalsRequired","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['AprovalsRequired'].'");
					validator.addValidation("ContractType","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['ContractType'].'");
					validator.addValidation("ContractType","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['ContractType'].'");
					validator.addValidation("PayConditions","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['PayConditions'].'");
					validator.addValidation("PayConditions","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['PayConditions'].'");
					validator.addValidation("ProjectProgram","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['ProjectProgram'].'");
					validator.addValidation("ProjectProgram","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['ProjectProgram'].'");
					validator.addValidation("InstructionsContentFormat","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['InstructionsContentFormat'].'");
					validator.addValidation("InstructionsContentFormat","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['InstructionsContentFormat'].'");
					validator.addValidation("ExpiralDate","date","'.$lang[Field_Date].'");
					validator.addValidation("ExpiralDate","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['ExpiralDate'].'");
					</SCRIPT>');
				}
			}
			else 
			{print('Not enougth permisions');}
		break;

	case edit:
			print("<h1>".$_SESSION[Nomenclature][Proposal_Solicitude_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if($_SESSION[User]->ProjectAdmin->EditPSolicitude)
			{
				if($_POST!=null)
				{
					ProposalSolicitude::EditProposal($_POST['ProposalSolicitudeID'],$_POST['JobDescription'],$_POST['ClientRequest'],$_POST['Deliverable'],$_POST['Supplies'],$_POST['AprovalsRequired'],$_POST['ContractType'],$_POST['PayConditions'],$_POST['ProjectProgram'],$_POST['InstructionsContentFormat'],$_POST['ExpiralDate']);
				}
				else
				{
					$proposal=new ProposalSolicitude($_SESSION[Project]->ProjectID);
					print('<form name="editpsolicitude" method="post" action="proposalsolicitude.php?action=edit">');
					print('
						<table class="std">
							<tr><td>'.$_SESSION['Nomenclature']['Job_Description'].'</td><td><textarea type="textarea" name="JobDescription">'.$proposal->JobDescription.'</textarea></td></tr>
							<tr class="std"><td>'.$_SESSION['Nomenclature']['ClientRequest'].'</td><td><textarea type="textarea" name="ClientRequest">'.$proposal->ClientRequest.'</textarea></td></tr>
							<tr><td>'.$_SESSION['Nomenclature']['Deliverable'].'</td><td><textarea type="textarea" name="Deliverable">'.$proposal->Deliverables.'</textarea></td></tr>
							<tr class="std"><td>'.$_SESSION['Nomenclature']['Supplies'].'</td><td><textarea type="textarea" name="Supplies">'.$proposal->Supplies.'</textarea></td></tr>
							<tr><td>'.$_SESSION['Nomenclature']['AprovalsRequired'].'</td><td><textarea type="textarea" name="AprovalsRequired">'.$proposal->AprovalsRequired.'</textarea></td></tr>
							<tr class="std"><td>'.$_SESSION['Nomenclature']['ContractType'].'</td><td><textarea type="textarea" name="ContractType">'.$proposal->ContractType.'</textarea></td></tr>
							<tr><td>'.$_SESSION['Nomenclature']['PayConditions'].'</td><td><textarea type="textarea" name="PayConditions">'.$proposal->PayConditions.'</textarea></td></tr>
							<tr class="std"><td>'.$_SESSION['Nomenclature']['ProjectProgram'].'</td><td><textarea type="textarea" name="ProjectProgram">'.$proposal->ProjectProgram.'</textarea></td></tr>
							<tr><td>'.$_SESSION['Nomenclature']['InstructionsContentFormat'].'</td><td><textarea type="textarea" name="InstructionsContentFormat">'.$proposal->InstructionsContentFormat.'</textarea></td></tr>
							<tr class="std"><td>'.$_SESSION['Nomenclature']['ExpiralDate'].'</td>
								<td>
    								<input type="text" name="ExpiralDate" value="'.$proposal->ExpiralDate.'">
    								<table><tr><td><A HREF="#" onClick="cal.select(document.forms[\'editpsolicitude\'].ExpiralDate,\'anchor1\',\'yyyy-MM-dd\'); return false;"NAME="anchor1" ID="anchor1"><img src="images/icons/calendar.png"></A></td></tr></table>
    							</td>
							</tr>
							<tr>
							<input name="ProposalSolicitudeID" type="hidden" value="'.$proposal->ProposalSolicitudeID.'">
								<td ><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    							<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    						</tr>
						</table></form>
					');
					print('<SCRIPT language="JavaScript">
					var validator = new Validator("editpsolicitude");
					validator.addValidation("JobDescription","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['Job_Description'].'");
					validator.addValidation("JobDescription","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Job_Description'].'");
					validator.addValidation("ClientRequest","alphanum","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['ClientRequest'].'");
					validator.addValidation("ClientRequest","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['ClientRequest'].'");
					validator.addValidation("Deliverable","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['Deliverable'].'");
					validator.addValidation("Deliverable","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Deliverable'].'");
					validator.addValidation("Supplies","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['Supplies'].'");
					validator.addValidation("Supplies","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['Supplies'].'");
					validator.addValidation("AprovalsRequired","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['AprovalsRequired'].'");
					validator.addValidation("AprovalsRequired","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['AprovalsRequired'].'");
					validator.addValidation("ContractType","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['ContractType'].'");
					validator.addValidation("ContractType","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['ContractType'].'");
					validator.addValidation("PayConditions","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['PayConditions'].'");
					validator.addValidation("PayConditions","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['PayConditions'].'");
					validator.addValidation("ProjectProgram","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['ProjectProgram'].'");
					validator.addValidation("ProjectProgram","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['ProjectProgram'].'");
					validator.addValidation("InstructionsContentFormat","alphanum","'.$lang[Field_Alpha].' '.$_SESSION['Nomenclature']['InstructionsContentFormat'].'");
					validator.addValidation("InstructionsContentFormat","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['InstructionsContentFormat'].'");
					validator.addValidation("ExpiralDate","date","'.$lang[Field_Date].'");
					validator.addValidation("ExpiralDate","req","'.$lang[Field_Required].' '.$_SESSION['Nomenclature']['ExpiralDate'].'");
					</SCRIPT>');
				}
			}
			else 
			{print('Not enougth permisions');}
		break;

	case delete:
			if($_SESSION[User]->ProjectAdmin->DeletePSolicitude)
			{
				ProposalSolicitude::DeleteProposal($_GET['ProposalSolicitudeID']);
			}
			else 
			{print('Not enougth permisions');}
		break;
		
	default:
		break;
}

include_once 'foot.php';
?>