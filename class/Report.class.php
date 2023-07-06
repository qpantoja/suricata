<?PHP

/**
 * @author Pantoja Hinojosa Quetzalcoatl
 * @package class
**/

class Report
{
	private $ProjectID;
	private $HumanResource=false;
	private $MaterialResource=false;
	private $BudgetResource=false;
	private $Task=false;
	private $Advance=false;
	private $ChangesSheet=false;
	private $GanttDiagram=false;
	private $JobEntryDiagram=false;
	private $Proposal=false;
	private $AsignationTable=false;
	private $SecuenceTable=false;
	private $Project=false;
	
	
	public function Report()
	{}
	
	public function Generate()
	{
		$this->ProjectReport();
		if($this->HumanResource)
		{
			print('<h1>'.$_SESSION[Nomenclature][Human_Resource_Report].'<h1>');
			$this->HumanResource();
		}
		
		if($this->MaterialResource)
		{
			print('<h1>'.$_SESSION[Nomenclature][Material].'<h1>');
			$this->MaterialResource();
		}
		
		if($this->BudgetResource)
		{
			print('<h1>'.$_SESSION[Nomenclature][Budget].'<h1>');
			$this->BudgetResource();
		}
		
		if($this->Task)
		{
			print('<h1>'.$_SESSION[Nomenclature][Task].'<h1>');
			$this->Task();
		}
		
		if($this->Advance)
		{
			print('<h1>'.$_SESSION[Nomenclature][Show_Advances].'<h1>');
			$this->Advance();
		}
		
		if($this->ChangesSheet)
		{
			print('<h1>'.$_SESSION[Nomenclature][Changes_Sheet].'<h1>');
			$this->ChangesSheet();
		}
		
		if($this->GanttDiagram)
		{
			print('<h1>'.$_SESSION[Nomenclature][Gantt].'<h1>');
			$this->GanttDiagram();
		}
		
		if($this->JobEntryDiagram)
		{
			print('<h1>'.$_SESSION[Nomenclature][Job_Entry].'<h1>');
			$this->JobEntryDiagram();
		}
		
		if($this->Proposal)
		{
			print('<h1>'.$_SESSION[Nomenclature][Proposal_Report].'<h1>');
			$this->Proposal();
		}
		
		if($this->AsignationTable)
		{
			print('<h1>'.$_SESSION[Nomenclature][Show_Asignation_Table].'<h1>');
			$this->AsignationTable();
		}
		
		if($this->SecuenceTable)
		{
			print('<h1>'.$_SESSION[Nomenclature][Show_Secuence_Table].'<h1>');
			$this->SecuenceTable();
		}
	}
	
	private function HumanResource()
	{
		$db=new DBase();
		$res=$db->DB->getAll('
			SELECT
				*
			FROM
				project, project_has_user,"user"
			WHERE
				project.idproject=\''.$this->ProjectID.'\'
				AND project.idproject=project_has_user.idproject
				AND "user".iduser=project_has_user.iduser');
		if (PEAR::isError($res))
			{
			print_r($res->getDebugInfo())
			;return false;
			}
		print('<p><table class="std">');
		foreach ($res as $key=>$users)
			{
				print('<tr');if($key%2){echo " class=std";}print('>
					<td>['.$users[iduser].'] '.$users[name].' '.$users[father_lastname].' '.$users[mother_lastname].' ('.$users[idproject_role].')</td>
				</tr>');
			}
		print('<p></table>');
	}
	
	private function MaterialResource()
	{
		$db=new DBase();
    	$res=$db->DB->getAll('SELECT * FROM "material" WHERE idproject=\''.$this->ProjectID.'\' ORDER BY expires DESC');
    	if (PEAR::isError($res))
			{return($res->getDebugInfo());}
		if (empty($res))
		{
			print($_SESSION[Nomenclature][No_Material]);
			return (true);
		}
    	foreach($res as $material)
    	{
    		$myMaterial=new Material($material[idmaterial]);
    		print($myMaterial);
    	}
	}
	
	private function BudgetResource()
	{
		$db=new DBase();
    	$res=$db->DB->getAll('SELECT * FROM "budget" WHERE idproject=\''.$this->ProjectID.'\' ORDER BY expires DESC');
    	if (PEAR::isError($res))
			{return($res->getDebugInfo());}
		if (empty($res))
		{
			print($_SESSION[Nomenclature][No_Material]);
			return (true);
		}
    	foreach($res as $budget)
    	{
    		$myBudget=new Budget($budget[idbudget]);
    		print($myBudget);
    	}
	}
	
	private function Task()
	{
		//foreach tread.. print it
		$db=new DBase();
		$res=$db->DB->getAll('SELECT * FROM action_thread WHERE idproject=\''.$this->ProjectID.'\'');
		if (PEAR::isError($res))
			{print_r($res->getDebugInfo());}
		else
		{
			if(!empty($res))
			{
				foreach ($res as $Thread)
				{
					$thread=new ActionThread($Thread['idaction_thread']);
					print('<p>');print($thread);print('</p>');
				}
			}
			else 
			{
				print($GLOBALS['lang']['empty']);
			}
		}
	}
	
	private function Advance()
	{
		//foreach tread, ListTasks and print them
		$db=new DBase();
		$res=$db->DB->getAll('SELECT * FROM action_thread WHERE idproject=\''.$this->ProjectID.'\'');
		if (PEAR::isError($res))
			{print_r($res->getDebugInfo());}
		else
		{
			if(!empty($res))
			{
				foreach ($res as $Thread)
				{
					$db2=new DBase();
					$res2=$db2->DB->getAll('SELECT * FROM task WHERE idaction_thread=\''.$Thread['idaction_thread'].'\'');
					if (PEAR::isError($res2))
						{print_r($res2->getDebugInfo());}
					else
					{
						if(!empty($res2))
						{
							foreach ($res2 as $Task)
							{
								$task=new Task($Task['idtask']);
								$semaphore=new Semaphore($task);
								print('<p>');print($task);print('</p>');
							}
						}
						else 
						{
							print('
							<tr>
								<td>'.$GLOBALS['lang']['empty'].'</td>
							</tr>');
						}
					}
					
				}
			}
			else 
			{
				print($GLOBALS['lang']['empty']);
			}
		}
	}
	
	private function ChangesSheet()
	{
		//foreach tread.. print it
		$db=new DBase();
		$res=$db->DB->getAll('SELECT * FROM changes_sheet WHERE idproject=\''.$this->ProjectID.'\'');
		if (PEAR::isError($res))
			{print_r($res->getDebugInfo());}
		else
		{
			if(!empty($res))
			{
				foreach ($res as $Sheet)
				{
					$ChangesSheet=new ChangesSheet($Sheet['idchanges_sheet']);
					print('<p>');print($ChangesSheet);print('</p>');
				}
			}
			else 
			{
				print($GLOBALS['lang']['empty']);
			}
		}
	}
	
	private function GanttDiagram()
	{
		print('<a href="images/graph.php?action=Gantt&&ProjectID='.$_SESSION[Project]->ProjectID.'" target="popup" onClick="window.open(this.href, this.target, \'status=yes,resizable=yes\'); return false;"><img width="600" src="images/graph.php?action=Gantt&&ProjectID='.$_SESSION[Project]->ProjectID.'"></a>');	
	}
	
	private function JobEntryDiagram()
	{
		print('<a href="images/graph.php?action=JobEntry&&ProjectID='.$_SESSION[Project]->ProjectID.'" target="popup" onClick="window.open(this.href, this.target, \'status=yes,resizable=yes\'); return false;"><img width="600" src="images/graph.php?action=JobEntry&&ProjectID='.$_SESSION[Project]->ProjectID.'"></a>');
	}
	
	private function Proposal()
	{
		$Proposal=new ProposalSolicitude($this->ProjectID);
		print('<p>');print($Proposal);print('</p>');
	}
	
	private function AsignationTable()
	{
		$table=new AsignationTable($this->ProjectID);
		print($table);
	}
	
	private function SecuenceTable()
	{
		$table=new SecuenceTable($this->ProjectID);
		print($table);
	}
	
	public function ProjectReport()
	{
		$Project=new Project($this->ProjectID);
		print('<p>');print($Project);print('</p>');
	}
	
	public function UserReport($User)
	{
		//show the messages table report
		print('<p>');
		$db=new DBase();
		$res=$db->DB->getAll('select count(*) as messages from message where iduser=\''.$_SESSION[User]->UserID.'\'');
		if (PEAR::isError($res))
			{print_r($res->getDebugInfo());}
		else
		{
			print('<table class="std"><tr class="std"><td>'.$GLOBALS[lang][Messages].'</td></tr>');
			if($res[0][messages]>0)
				{
					print('<tr><td>'.$GLOBALS[lang][Total_Messages].$res[0][messages].'<td></tr>');
					$unread=$db->DB->getAll('select count(*) as unread_messages from message where iduser=\''.$_SESSION[User]->UserID.'\' and read=\'f\'');
					if(!empty($unread))
						{print('<tr><td>'.$GLOBALS[lang][Unread_Messages].' '.$unread[0][unread_messages].'<td></tr>');}
					else 
						{print('<tr><td>'.$GLOBALS[lang][Unread_Messages].'0<td></tr>');}
				}
			else 
				{print('<tr><td>'.$GLOBALS[lang][No_Messages].'<td></tr>');}
			print('</table>');
		}
		print('</p>');
		
		//show the projects table report
		print('<p>');
		$res=$db->DB->getAll('select count(*) as projects_related from project_has_user where iduser=\''.$_SESSION['User']->UserID.'\'');
		if (PEAR::isError($res))
			{print_r($res->getDebugInfo());}
		else
		{
			print('<table class="std">');
			if($res[0][projects_related]>0)
				{
					print('
						<tr class="std"><td colspan="3">'.$GLOBALS[lang][Projects_Related].$res[0][projects_related].'</td>');
					$proj=$db->DB->getAll('select * from project_has_user, project where iduser=\''.$_SESSION['User']->UserID.'\' and  project_has_user.idproject=project.idproject');
					foreach ($proj as $project)
					{
						print('<tr><td>'.$project[name].'</td><td>'.$project[state].'</td><td>'.$project[idproject_role].'</td></tr>');
					}
				}
			else 
				{print($GLOBALS[lang][No_Projects_Related]);}
			print('</table>');
		}
		print('</p>');	 
	}
	
	public function __set($attribute,$value)
	{
		$this->$attribute=$value;
		return (true);
	}
}


?>