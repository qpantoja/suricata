<?PHP
include_once "../class/JobEntryGenerator.class.php";
include_once "../class/GanttGenerator.class.php";

session_start();
if($_SESSION['User'])
{
	switch ($_GET[action])
	{
		case JobEntry:
			$g=new JobEntryGenerator($_GET[ProjectID]);
			$g->Draw();
		break;
		
		case Gantt:
			//print 'implementar grafica de gantt';
			$g=new GanttGenerator();
			$res=$g->Data($_GET[ProjectID]);
			if($res==false)
			{
				print('NO SE PUEDE MOSTRAR LA GRAFICA');
			}
			else {$g->Draw();}
			//$g->Draw();
		break;
	}
}
else 
{
	include_once("wellcome.php");
}

?>