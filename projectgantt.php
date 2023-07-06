<?php

/**
*@package request
*/

include_once 'head.php';

switch ($_GET[action])
{
	case show:
		print("<h1>".$_SESSION[Nomenclature][Gantt_Diagram_Title]."</h1>");
		if ($_SESSION[User]->ProjectAdmin->ShowGantt) 
		{
			
			$g=new GanttGenerator();
			$res=$g->Data($_SESSION[Project]->ProjectID);
			if($res==false)
			{
				print('<p>'.$_SESSION[Nomenclature][No_Gantt].'</p>');
			}
			else {print('<p><a href="images/graph.php?action=Gantt&&ProjectID='.$_SESSION[Project]->ProjectID.'" target="popup" onClick="window.open(this.href, this.target, \'status=yes,resizable=yes,scrollbars=yes\'); return false;"><img src="images/graph.php?action=Gantt&&ProjectID='.$_SESSION[Project]->ProjectID.'"></a></p>');}
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