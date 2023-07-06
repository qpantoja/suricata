<?PHP

/**
*@author Quetzalcoatl Pantoja Hinojosa
*@package class
* 
*/
include_once "DBase.class.php";
include_once "JobEntryGenerator.class.php";
include_once "JobEntry.class.php";

class JobEntryGenerator
{
	private $tree;
	
	public function JobEntryGenerator($ProjectID)
	{
		$data=array();
		$thread_list=array();
		$db=new DBase();
		//is PEAR::Error()
		$action_thread=$db->DB->getAll('select * from action_thread where idproject='.$ProjectID);
		foreach($action_thread as $a)
		{
			$task=$db->DB->getAll('select * from task where idaction_thread='.$a['idaction_thread']);
			$task_list=array();
			foreach($task as $t)
			{
				array_push($task_list,$t['name']);
			}
			$thread_list[$a['name']]=$task_list;
		}
		$project=$db->DB->getAll('select * from project where idproject='.$ProjectID);
		$data[$project[0]['name']]=$thread_list;
		$this->tree=$data;
	}
	
	public function Draw($file="")
	{
		$g = new JobEntry($this->tree);
		$g->SetRectangleBorderColor(134, 83, 32);
		$g->SetRectangleBackgroundColor(204, 153, 51);
		$g->SetFontColor(0, 0, 0);
		$g->SetBorderWidth(1);
		$g->Draw($file);
	}
}

?>