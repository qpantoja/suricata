<?php

/**
*@package request
*/

include_once 'head.php';

switch ($_GET[action])
{
	case show:
			print("<h1>".$_SESSION[Nomenclature][Changes_Sheet_Title]."</h1>");
			if($_SESSION[User]->ProjectAdmin->ShowChangesSheet)
			{
				if($_GET[SheetID]!=null)
				{
					$ChangeSheet= new ChangesSheet($_GET[SheetID]);
					print ($ChangeSheet);
				}
				else 
				{
					ChangesSheet::SheetsList($_SESSION[Project]->ProjectID);
				}
			}
			else
			{
				print($lang[No_Privilegies]);
			}
		break;
		
	case add:
			print("<h1>".$_SESSION[Nomenclature][Changes_Sheet_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if($_POST!=null && $_SESSION[User]->ProjectAdmin->CreateChangesSheet)
			{
				ChangesSheet::AddSheet($_SESSION[Project]->ProjectID,$_POST[Owner],$_POST[Date],$_POST[ChangeProposal],$_POST[Description],$_POST[Reason],$_POST[PlanImpact],$_POST[TecnicalImplication]);
			}
			else 
			{
				if($_SESSION[User]->ProjectAdmin->CreateChangesSheet)
				{
					print('<form name="createchange" method="post" action="projectchangesh.php?action=add">
					   	<table class="std">
					   		<input type="hidden" name="Owner" value="'.$_SESSION[User]->UserID.'">
   							<tr class="std"><td>'.$GLOBALS['lang']['Date'].'</td><td><input type="text" name="Date" value="'.date("Y-m-d").'" readonly="true"></td></tr>
   							<tr><td>'.$GLOBALS['lang']['Change_Proposal'].'</td><td><textarea type="textarea" name="ChangeProposal"></textarea></td></tr>
   							<tr class="std"><td>'.$GLOBALS['lang']['Description'].'</td><td><textarea type="textarea" name="Description"></textarea></td></tr>
   							<tr><td>'.$GLOBALS['lang']['Reason'].'</td><td><textarea type="textarea" name="Reason"></textarea></td></tr>
   							<tr class="std"><td>'.$GLOBALS['lang']['Plan_Impact'].'</td><td><textarea type="textarea" name="PlanImpact"></textarea></td></tr>
   							<tr><td>'.$GLOBALS['lang']['Tecnical_Implication'].'</td><td><textarea type="textarea" name="TecnicalImplication"></textarea></td></tr>
   							<tr class="std">
								<td ><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    							<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    						</tr>
						</table></form>
					');
					print('<SCRIPT language="JavaScript">
					var validator = new Validator("createchange");
					validator.addValidation("Date","date","'.$lang[Field_Date].'");
					validator.addValidation("ChangeProposal","req","'.$lang[Field_Required].' '.$lang['Change_Proposal'].'");
					validator.addValidation("ChangeProposal","alphanum","'.$lang[Field_Alpha].' '.$lang['Change_Proposal'].'");
					validator.addValidation("Description","req","'.$lang[Field_Required].' '.$lang['Description'].'");
					validator.addValidation("Description","alphanum","'.$lang[Field_Alpha].' '.$lang['Description'].'");
					validator.addValidation("Reason","req","'.$lang[Field_Required].' '.$lang['Reason'].'");
					validator.addValidation("Reason","alphanum","'.$lang[Field_Alpha].' '.$lang['Reason'].'");
					validator.addValidation("PlanImpact","req","'.$lang[Field_Required].' '.$lang['Plan_Impact'].'");
					validator.addValidation("PlanImpact","alphanum","'.$lang[Field_Alpha].' '.$lang['Plan_Impact'].'");
					validator.addValidation("TecnicalImplication","req","'.$lang[Field_Required].' '.$lang['Tecnical_Implication'].'");
					validator.addValidation("TecnicalImplication","alphanum","'.$lang[Field_Alpha].' '.$lang['Tecnical_Implication'].'");
					</SCRIPT>');
				}
				else 
					{print($lang[No_Privilegies]);}
			}
		break;

	case edit:
			print("<h1>".$_SESSION[Nomenclature][Changes_Sheet_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if ($_POST!=null)
			{
				ChangesSheet::EditSheet($_POST[SheetID],$_POST[Owner],$_POST[Date],$_POST[ChangeProposal],$_POST[Description],$_POST[Reason],$_POST[PlanImpact],$_POST[TecnicalImplication]);
			}
			if($_SESSION[User]->ProjectAdmin->EditChangesSheet && ($_GET[SheetID]!=null))
			{
				$db=new DBase();
				$query='SELECT * FROM changes_sheet WHERE idchanges_sheet=\''.$_GET[SheetID].'\'';
				$res=$db->DB->getAll($query);
				if (PEAR::isError($res))
					{print_r($res->getDebugInfo());die();}
				print('<form name="createchange" method="post" action="projectchangesh.php?action=edit">
					   	<table class="std">
					   		<input type="hidden" name="Owner" value="'.$_SESSION[User]->UserID.'">
					   		<input type="hidden" name="SheetID" value="'.$_GET[SheetID].'">
   							<tr class="std"><td>'.$GLOBALS['lang']['Date'].'</td><td><input type="text" name="Date" value="'.date("Y-m-d").'" readonly="true"></td></tr>
   							<tr><td>'.$GLOBALS['lang']['Change_Proposal'].'</td><td><textarea type="textarea" name="ChangeProposal">'.$res[0]['change_proposal'].'</textarea></td></tr>
   							<tr class="std"><td>'.$GLOBALS['lang']['Description'].'</td><td><textarea type="textarea" name="Description">'.$res[0]['description'].'</textarea></td></tr>
   							<tr><td>'.$GLOBALS['lang']['Reason'].'</td><td><textarea type="textarea" name="Reason">'.$res[0]['reason'].'</textarea></td></tr>
   							<tr class="std"><td>'.$GLOBALS['lang']['Plan_Impact'].'</td><td><textarea type="textarea" name="PlanImpact">'.$res[0]['plan_impact'].'</textarea></td></tr>
   							<tr><td>'.$GLOBALS['lang']['Tecnical_Implication'].'</td><td><textarea type="textarea" name="TecnicalImplication">'.$res[0]['tecnical_implication'].'</textarea></td></tr>
   							<tr class="std">
								<td ><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    							<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    						</tr>
						</table></form>
					');
					print('<SCRIPT language="JavaScript">
					var validator = new Validator("createchange");
					validator.addValidation("Date","date","'.$lang[Field_Date].'");
					validator.addValidation("ChangeProposal","req","'.$lang[Field_Required].' '.$lang['Change_Proposal'].'");
					validator.addValidation("ChangeProposal","alphanum","'.$lang[Field_Alpha].' '.$lang['Change_Proposal'].'");
					validator.addValidation("Description","req","'.$lang[Field_Required].' '.$lang['Description'].'");
					validator.addValidation("Description","alphanum","'.$lang[Field_Alpha].' '.$lang['Description'].'");
					validator.addValidation("Reason","req","'.$lang[Field_Required].' '.$lang['Reason'].'");
					validator.addValidation("Reason","alphanum","'.$lang[Field_Alpha].' '.$lang['Reason'].'");
					validator.addValidation("PlanImpact","req","'.$lang[Field_Required].' '.$lang['Plan_Impact'].'");
					validator.addValidation("PlanImpact","alphanum","'.$lang[Field_Alpha].' '.$lang['Plan_Impact'].'");
					validator.addValidation("TecnicalImplication","req","'.$lang[Field_Required].' '.$lang['Tecnical_Implication'].'");
					validator.addValidation("TecnicalImplication","alphanum","'.$lang[Field_Alpha].' '.$lang['Tecnical_Implication'].'");
					</SCRIPT>');
			}
			else
			{
				print($lang[No_Privilegies]);
			}
		break;

	case delete:
			if($_SESSION[User]->ProjectAdmin->DeleteChangesSheet)
			{
				ChangesSheet::DeleteSheet($_GET[SheetID]);
			}
			else
			{
				print($lang[No_Privilegies]);
			}
		break;

	default:
		break;
}

include_once 'foot.php';
?>