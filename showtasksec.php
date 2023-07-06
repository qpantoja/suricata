<?php

/**
*@package request
*/

include_once 'head.php';

switch ($_GET[action])
{
	case show:
			print("<h1>".$_SESSION[Nomenclature][Secuence_Table_Title]."</h1>");
			$table=new SecuenceTable($_SESSION[Project]->ProjectID);
			print($table);
		break;

	default:
		break;
}

include_once 'foot.php';
?>