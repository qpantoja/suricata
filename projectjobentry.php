<?php

/**
*@package request
*/

include_once 'head.php';

switch ($_GET[action])
{
	case show:
		print("<h1>".$_SESSION[Nomenclature][Job_Entry_Title]."</h1>");
		if ($_SESSION[User]->ProjectAdmin->ShowJobEntry) 
		{
			print('<a href="images/graph.php?action=JobEntry&&ProjectID='.$_SESSION[Project]->ProjectID.'" target="popup" onClick="window.open(this.href, this.target, \'status=yes,resizable=yes\'); return false;"><img width="600" src="images/graph.php?action=JobEntry&&ProjectID='.$_SESSION[Project]->ProjectID.'"></a>');
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