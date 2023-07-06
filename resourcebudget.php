<?php

/**
 * @author Pantoja Hinojosa Quetzalcoatl
 * @package request
**/

include_once 'head.php';

switch ($_GET[action])
{
	case show:
			print("<h1>".$_SESSION[Nomenclature][Resource_Budget_Title]."</h1>");
			if ($_SESSION[User]->ProjectAdmin->ModifyBudget)
				{print('<a href="resourcebudget.php?action=add"><img src="images/icons/add.png"><span>'.$lang['Add_Message'].'</span></a>');}
			print('<p>');Budget::ListBudget($_SESSION[Project]->ProjectID);print('</p>');
		break;
		
	case add:
			print("<h1>".$_SESSION[Nomenclature][Resource_Budget_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if ($_SESSION[User]->ProjectAdmin->ModifyBudget)
			{
				if($_POST!=null)
				{
					$res=Budget::AddBudget($_POST[Amount],$_POST[Description],$_POST[ExpireDate],$_SESSION[Project]->ProjectID);
					if($res==true)
					{
						print('<meta HTTP-EQUIV="REFRESH" content="0; url=resourcebudget.php?action=show&&message='.$_SESSION[Nomenclature][New_Budget].'&&class=success">');	
					}
					else 
					{
						print('<meta HTTP-EQUIV="REFRESH" content="0; url=resourcebudget.php?action=show&&message='.$res->getMessage().'&&class=error">');	
					}
				}
				else 
				{
					print('
					<form name="addbudget" method="post" action="resourcebudget.php?action=add">
					<table class="std">
						<tr><td>'.$_SESSION[Nomenclature][Amount].'</td><td><input type="text" name="Amount"></td></tr>
						<tr class=std><td>'.$_SESSION[Nomenclature][Description].'</td><td><input type="text" name="Description"></td></tr>
						<tr>
    						<td>'.$_SESSION[Nomenclature][Expire_Date].' '.$lang[Date_Format].'</td>
    						<td>
    						<input type="text" name="ExpireDate">
    						<table><tr><td><A HREF="#" onClick="cal.select(document.forms[\'addbudget\'].ExpireDate,\'anchor1\',\'yyyy-MM-dd\'); return false;"NAME="anchor1" ID="anchor1"><img src="images/icons/calendar.png"></A></td></tr></table>
    						</td>
    					</tr>
						<tr class=std><td><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td><td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td></tr>
					</table>
					</form>
					');
					print('<SCRIPT language="JavaScript">
					var validator = new Validator("addbudget");
					validator.addValidation("Amount","num","'.$lang[Field_Numeric].' '.$_SESSION[Nomenclature]['Amount'].'");
					validator.addValidation("Amount","req","'.$lang[Field_Required].' '.$_SESSION[Nomenclature]['Amount'].'");
					validator.addValidation("Description","alpha","'.$lang[Field_Alpha].' '.$_SESSION[Nomenclature]['Description'].'");
					validator.addValidation("Description","req","'.$lang[Field_Required].' '.$_SESSION[Nomenclature]['Description'].'");
					validator.addValidation("ExpireDate","date","'.$lang[Field_Date].' '.$_SESSION[Nomenclature]['ExpireDate'].'");
					validator.addValidation("ExpireDate","req","'.$lang[Field_Required].' '.$_SESSION[Nomenclature]['ExpireDate'].'");
					</SCRIPT>');
				}
			}
			else
			{
				print($lang[No_Privilegies]);	
			}
		break;

	case details:
			print("<h1>".$_SESSION[Nomenclature][Resource_Budget_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			$budget = new Budget($_GET[BudgetID]);
			print($budget);
		break;
		
	case 'use':
			print("<h1>".$_SESSION[Nomenclature][Resource_Budget_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			
			if($_SESSION[User]->ProjectAdmin->UseBudget)
			{
				if(!empty($_POST))
				{
					$budget=new Budget($_POST[BudgetID]);
					$res=$budget->UseBudget($_POST[Amount],$_POST[Description]);
					if($res==true)
					{
						print('<meta HTTP-EQUIV="REFRESH" content="0; url=resourcebudget.php?action=show&&message='.$_SESSION[Nomenclature][Budget_Used].'&&class=success">');
					}
					else 
					{
						print('<meta HTTP-EQUIV="REFRESH" content="0; url=resourcebudget.php?action=show&&message='.$res->getMessage().'&&class=error">');
					}
				}
				else
				{
					//Get how much he can use...
					$db=new DBase();
					$res=$db->DB->getAll('SELECT * FROM budget WHERE idbudget=\''.$_GET[BudgetID].'\'');
					if (PEAR::isError($res))
						{return($res->getDebugInfo());}
					$MaxUse=$res[0][available];
					print('
						<form name="usebudget" method="post" action="resourcebudget.php?action=use">
						<table class="std">
							<tr><td>'.$_SESSION[Nomenclature][Amount].'</td><td><input type="text" name="Amount"></td></tr>
							<tr class=std><td>'.$_SESSION[Nomenclature][Use_Description].'</td><td><textarea name="Description"></textarea></td></tr>
							<tr>
								<input type="hidden" name="BudgetID" value="'.$_GET[BudgetID].'">
								<td><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
								<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
							</tr>
						</table>
						<form>');
					print('<SCRIPT language="JavaScript">
						var validator = new Validator("usebudget");
						validator.addValidation("Amount","num","'.$lang[Field_Numeric].' '.$_SESSION[Nomenclature]['Amount'].'");
						validator.addValidation("Amount","req","'.$lang[Field_Required].' '.$_SESSION[Nomenclature]['Amount'].'");
						validator.addValidation("Amount","lessthan='.$MaxUse.'","'.$lang[Field_Greater].' '.$_SESSION[Nomenclature]['Amount'].'");
						validator.addValidation("Description","alpha","'.$lang[Field_Alpha].' '.$_SESSION[Nomenclature]['Description'].'");
						validator.addValidation("Description","req","'.$lang[Field_Required].' '.$_SESSION[Nomenclature]['Description'].'");
						</SCRIPT>');
				}
			}
			else 
			{
				print($lang[No_Privilegies]);
			}
		break;

	case delete:
			print("<h1>".$_SESSION[Nomenclature][Resource_Budget_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if ($_SESSION[User]->ProjectAdmin->ModifyBudget)
			{
				$res=Budget::Delete($_GET[BudgetID]);
				if($res==true)
				{
					print('<meta HTTP-EQUIV="REFRESH" content="0; url=resourcebudget.php?action=show&&message='.$_SESSION[Nomenclature][Delete_Budget].'&&class=success">');	
				}
				else 
				{
					print('<meta HTTP-EQUIV="REFRESH" content="0; url=resourcebudget.php?action=show&&message='.$res->getMessage().'&&class=error">');	
				}
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