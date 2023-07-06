<?php

/**
 * @author Pantoja Hinojosa Quetzalcoatl
 * @package request
**/

include_once 'head.php';

switch ($_GET[action])
{
	case show:
			print("<h1>".$_SESSION[Nomenclature][Reports_Title]."</h1>");
			if($_SESSION[User]->ProjectAdmin->CreateReport)
			{
				print('<p><form name="form1" method="post" action="reports.php?action=generate">
  					<table class="std">
  						<tr><td>'.$_SESSION[Nomenclature][Human_Resource_Report].'</td><td><input type="checkbox" name="HumanResource"></td></tr>
  						<tr class="std"><td>'.$_SESSION[Nomenclature][Material].'</td><td><input type="checkbox" name="MaterialResource"></td></tr>
  						<tr><td>'.$_SESSION[Nomenclature][Budget].'</td><td><input type="checkbox" name="BudgetResource"></td></tr>
  						<tr class="std"><td>'.$_SESSION[Nomenclature][Task].'</td><td><input type="checkbox" name="Task"></td></tr>
  						<tr><td>'.$_SESSION[Nomenclature][Show_Advances].'</td><td><input type="checkbox" name="Advance"></td></tr>
  						<tr class="std"><td>'.$_SESSION[Nomenclature][Changes_Sheet].'</td><td><input type="checkbox" name="ChangesSheet"></td></tr>
  						<tr><td>'.$_SESSION[Nomenclature][Gantt].'</td><td><input type="checkbox" name="GanttDiagram"></td></tr>
  						<tr class="std"><td>'.$_SESSION[Nomenclature][Job_Entry].'</td><td><input type="checkbox" name="JobEntryDiagram"></td></tr>
  						<tr><td>'.$_SESSION[Nomenclature][Proposal_Report].'</td><td><input type="checkbox" name="Proposal"></td></tr>
  						<tr class="std"><td>'.$_SESSION[Nomenclature][Show_Asignation_Table].'</td><td><input type="checkbox" name="AsignationTable"></td></tr>
  						<tr><td>'.$_SESSION[Nomenclature][Show_Secuence_Table].'</td><td><input type="checkbox" name="SecuenceTable"></td></tr>
  						<tr class="std">
							<td ><input type="submit" name="submit" value="'.$lang[Ok_Button].'"></td>
    						<td><input type="reset" name="Reset" value="'.$lang[Cancel_Button].'"></td>
    					</tr>
  					</table>	
  					</form></p>');
			}
			else
			{
				print($lang[No_Privilegies]);
			}
		break;
		
	case generate:
			print("<h1>".$_SESSION[Nomenclature][Reports_Title]."</h1>");
			print ('<p><a href="javascript:window.history.back()"><img src="images/icons/back.png"><span>'.$GLOBALS['lang']['Back'].'</span></a></p>');
			if($_SESSION[User]->ProjectAdmin->CreateReport)
			{
				$report=new Report();
				$report->ProjectID=$_SESSION[Project]->ProjectID;
				foreach($_POST as $ReportAttribute=>$value)
				{
					if($ReportAttribute=='submit')
						{continue;}
					else {$report->$ReportAttribute=true;}
				}
				$report->Generate();
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