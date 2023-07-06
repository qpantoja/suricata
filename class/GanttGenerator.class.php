<?PHP

/**
 * @author Quetzalcoatl Pantoja Hinojosa
 * @package class
**/

include_once('Gantt.class.php');
include_once('DBase.class.php');

class GanttGenerator
{
	private $Definitions=array();
	private $phase=0;
	private $Tphase=array();//contains $task_phase[Phasenumber]=TaskID
	
	public function __construct()
	{
	
	//definiciones estandar
	$this->Definitions['title_y'] = 10;
	$this->Definitions['planned']['y'] = 6;
	$this->Definitions['planned']['height']= 8;
	$this->Definitions['planned_adjusted']['y'] = 25;
	$this->Definitions['planned_adjusted']['height']= 8;
	$this->Definitions['real']['y']=26;
	$this->Definitions['real']['height']=5;
	$this->Definitions['progress']['y']=11;
	$this->Definitions['progress']['height']=2;
	$this->Definitions['text']['color'] = array(0, 0, 0);
	$this->Definitions['titulo_color_fondo'] = array(135, 98, 24);
	$this->Definitions['milestone']['title_bg_color'] = array(217, 178, 100);
	$this->Definitions['today']['color']=array(0, 204, 0);
	$this->Definitions['status_report']['color']=array(255, 50, 0);
	$this->Definitions['real']['hachured_color']=array(204,0, 0);
	$this->Definitions['groups']['color'] = array(0, 0, 0);
	$this->Definitions['groups']['bg_color'] = array(196,174,131);
	$this->Definitions['planned']['color']=array(255, 143, 4);
	$this->Definitions['planned_adjusted']['color']=array(0, 0, 204);
	$this->Definitions['real']['color']=array(255, 255,255);
	$this->Definitions['progress']['color']=array(0,255,0);
	$this->Definitions['milestones']['color'] = array(254, 0, 0);
	
	//fuentes de la grafica
	$this->Definitions['text_font'] = 2;
	$this->Definitions['title_font'] = 2;
	
	//intervalo de continuidad de las lineas 1=continua
	$this->Definitions['status_report']['pixels'] = 9;
	$this->Definitions['today']['pixels'] = 9;
	
	// colores de la lineas de dependencia (planeados y ajustdos)
	$this->Definitions['dependency_color'][END_TO_START]=array(0, 0, 0);
	$this->Definitions['dependency_color'][START_TO_START]=array(0, 0, 0);
	$this->Definitions['dependency_color'][END_TO_END]=array(0, 0, 0);
	$this->Definitions['dependency_color'][START_TO_END]=array(0, 0, 0);

	//transparencia de colores de barras, iconos y lineas de 0 - 100
	$this->Definitions['planned']['alpha'] = 40;
	$this->Definitions['planned_adjusted']['alpha'] = 40;
	$this->Definitions['real']['alpha'] = 0;
	$this->Definitions['progress']['alpha'] = 0;
	$this->Definitions['groups']['alpha'] = 40;
	$this->Definitions['today']['alpha']= 60;
	$this->Definitions['status_report']['alpha']= 10;
	$this->Definitions['dependency']['alpha']= 80;
	$this->Definitions['milestones']['alpha']= 40;

	//pone los titulos de la grafica
	$this->Definitions['planned']['legend'] = $_SESSION[Nomenclature]['Initial_Plan'];
	$this->Definitions['planned_adjusted']['legend'] = $_SESSION[Nomenclature]['Ajusted_Plan'];
	$this->Definitions['real']['legend'] = $_SESSION[Nomenclature]['Real'];
	$this->Definitions['progress']['legend'] = $_SESSION[Nomenclature]['Progress'];
	$this->Definitions['milestone']['legend'] = $_SESSION[Nomenclature]['Milestone'];
	$this->Definitions['today']['legend'] = $_SESSION[Nomenclature]['Today'];
	$this->Definitions['status_report']['legend'] = $_SESSION[Nomenclature]['Last_Report'];

	//establece la escala de la grafica para los dias semanas y meses
	//tamano de celda para cada dia
	$this->Definitions['limit']['cell']['m'] = '4';
	$this->Definitions['limit']['cell']['w'] = '8';
	$this->Definitions['limit']['cell']['d'] = '20';
	
	//posicion inicial de grid(x,y)
	$this->Definitions['grid']['x'] = 180;
	$this->Definitions['grid']['y'] = 40;

	//establece la altura de cada columna de fase/fase grupos y milestones tienen la mitad de esta altura
	$this->Definitions['row']['height'] = 40; //altura de cada columna

	$this->Definitions['legend']['y'] = 85; //posicion inicial de titulo(altura de imagen -y)
	$this->Definitions['legend']['x'] = 150; //distancia entre 2 col del la leyenda
	$this->Definitions['legend']['y_'] = 35; //distancia entre base de imagen y base de leyenda
	$this->Definitions['legend']['ydiff'] = 20; //diferencia entre 2 lineas de leyenda

	//otros
	$this->Definitions['progress']['bar_type']='planned';
	$this->Definitions["not_show_groups"] = false;
	$this->Definitions['locale'] = "es_MX";
	$this->Definitions['limit']['detail'] = 'w';//nivel de detalle w=week, m=mont, d=day
	$this->Definitions['image']['type']= 'png'; // can be png, jpg, gif  -> if not set default is png
	//$this->Definitions['image']['filename'] = "file.ext"'; // can be set if you prefer save image as a file
	$this->Definitions['image']['jpg_quality'] = 100; // quality value for jpeg imagens -> if not set default is 100
	
	}
	
	public function ConstructPhases($key_th,$thread)
	{
		$db2=new DBase();
		$res=$db2->DB->getAll('SELECT * FROM task WHERE task.idaction_thread=\''.$thread[idaction_thread].'\'');
		if (PEAR::isError($res))
			{print_r($res->getDebugInfo());return (false);}
		if(!empty($res))
		{
			$B=explode("-",$res[0][programed_begin]);  // WE need something to begin to compare
			$B=mktime(0,0,0,$B[1],$B[2],$B[0]);
			$E=explode("-",$res[0][programed_end]);
			$E=mktime(0,0,0,$E[1],$E[2],$E[0]);
			$Th_Begin=$B;
			$Th_End=E;
			foreach($res as $task)
			{
				array_push($this->Tphase,$task[idtask]);
				//relaciono el grupo con la phase
				$this->Definitions['groups']['group'][$key_th]['phase'][$this->phase] = $this->phase;
				$this->Definitions['planned']['phase'][$this->phase]['name'] = $task[name];
				//--$this->Definitions['groups']['group'][$key_th]['phase'][key($this->Tphase)] = key($this->Tphase);
				//--$this->Definitions['planned']['phase'][key($this->Tphase)]['name'] = $task[name];
				//hacer un explode de $task[begin] y end
				$Begin=explode("-",$task[programed_begin]);
				$Begin=mktime(0,0,0,$Begin[1],$Begin[2],$Begin[0]);
				$End=explode("-",$task[programed_end]);
				$End=mktime(0,0,0,$End[1],$End[2],$End[0]);
        		$this->Definitions['planned']['phase'][$this->phase]['start'] = $Begin;
        		$this->Definitions['planned']['phase'][$this->phase]['end'] = $End;
				//calcular el progreso
        		$this->Definitions['progress']['phase'][$this->phase]['progress']=$this->CalculateProgress($task[idtask]);
        		//--$this->Definitions['planned']['phase'][key($this->Tphase)]['start'] = $Begin;
        		//--$this->Definitions['planned']['phase'][key($this->Tphase)]['end'] = $End;
        		//--$this->Definitions['progress']['phase'][key($this->Tphase)]['progress']=$this->CalculateProgress($task[idtask]);
				if($Begin<$Th_Begin)
				{
					$Th_Begin=$Begin;	
				}
				if($End>$Th_End)
				{
					$Th_End=$End;
				}
				$this->phase+=1;
			}
			//aqui meto que tan grande es el thread
			$this->Definitions['groups']['group'][$key_th]['name'] = $thread[name];
			$this->Definitions['groups']['group'][$key_th]['start'] = $Th_Begin;
			$this->Definitions['groups']['group'][$key_th]['end'] = $Th_End;
		}
	}
	
	private function CalculateProgress($TaskID)
	{
		$db=new DBase();
		$data=$db->DB->getAll('SELECT sum(task_percent) FROM advance WHERE idtask=\''.$TaskID.'\'');
		if (PEAR::isError($data))
			{print_r($data->getDebugInfo());return(0);}
		if (!empty($data))
		{
			return($data[0][sum]);
		}
		else {return (0);}
	}
	
	private function CalculateDependency($ProjectID)
	{
		//get dependency
		$db2=new DBase();
		$res=$db2->DB->getAll('
							SELECT 
								* 
							FROM 
								dependency, task, action_thread
							WHERE dependency.idtask=task.idtask
								AND task.idaction_thread=action_thread.idaction_thread
								AND action_thread.idproject=\''.$ProjectID.'\'');
		if (PEAR::isError($res))
			{print_r($res->getDebugInfo());return (false);}
		if(!empty($res))
		{
			foreach ($res as $key=>$dependency)
			{
				$to=array_search($dependency[idtask], $this->Tphase);
				$from=array_search($dependency[needs], $this->Tphase);
				$this->Definitions['dependency_planned'][$key]['type']= END_TO_START;
				$this->Definitions['dependency_planned'][$key]['phase_from']=$from;//0;
				$this->Definitions['dependency_planned'][$key]['phase_to']=$to;//1;
			}
		}
		else{return (false);}
	}
	
	private function GetStart($ProjectID)
	{
		$Start=0;
		$db=new DBase();
		$data=$db->DB->getAll('SELECT * FROM action_thread,task WHERE idproject=\''.$ProjectID.'\' AND task.idaction_thread=action_thread.idaction_thread');
		if (PEAR::isError($data))
			{print_r($data->getDebugInfo());return($Start);}
		if (!empty($data))
		{
			$date=explode("-",$data[0][programed_begin]);
			$date=mktime(0,0,0,$date[1],$date[2],$date[0]);
			$Start=$date;
			foreach ($data as $task)
			{
				$date=explode("-",$task[programed_begin]);
				$date=mktime(0,0,0,$date[1],$date[2],$date[0]);
				if($Start>$date)
				{
					$Start=$date;
				}
			}
			return($Start);
		}
		else {return ($Start);}
	}
	
	private function GetEnd($ProjectID)
	{
		$End=0;
		$db=new DBase();
		$data=$db->DB->getAll('SELECT * FROM action_thread,task WHERE idproject=\''.$ProjectID.'\' AND task.idaction_thread=action_thread.idaction_thread');
		if (PEAR::isError($data))
			{print_r($data->getDebugInfo());return($End);}
		if (!empty($data))
		{
			$date=explode("-",$data[0][programed_end]);
			$date=mktime(0,0,0,$date[1],$date[2],$date[0]);
			$End=$date;
			foreach ($data as $task)
			{
				$date=explode("-",$task[programed_end]);
				$date=mktime(0,0,0,$date[1],$date[2],$date[0]);
				if($End<$date)
				{
					$End=$date;
				}
			}
			return($End);
		}
		else {return ($End);}
	}
	
	public function Data($ProjectID)
	{
		$db=new DBase();
		$data=$db->DB->getAll('
		SELECT 
			project.idproject, project.name as project_name, state, nomenclature, idaction_thread, action_thread.idproject, action_thread.name, responsable, father_thread, deliverable 
		FROM 
			project, action_thread 
		WHERE project.idproject='.$ProjectID.' 
			AND action_thread.idproject=project.idproject');
		if (PEAR::isError($data))
			{print_r($data->getDebugInfo());return (false);}
		if (!empty($data))
		{
			foreach($data as $key_th=>$thread)
			{
				$this->ConstructPhases($key_th,$thread);
			}
			//inicio y final de la grafica.. que tan grande es el proyecto
			$this->Definitions['title_string'] =  $data[0]['project_name'];//"Suricata Development ";
			$this->Definitions['limit']['start'] = $this->GetStart($ProjectID);//mktime(0,0,0,4,1,2006); //these settings will define the size of the graph
			$this->Definitions['limit']['end'] = $this->GetEnd($ProjectID);//mktime(23,59,59,11,28,2006);
			$this->Definitions['today']['data']= mktime(0,0,0,date("m,d,Y")); //draws the today line
			$this->CalculateDependency($ProjectID);
			return(true);
		}
		//else{return (false);}		
	}
	
	public function Draw()
	{
		//calls de gantt class to generate and draw the data...
		new Gantt($this->Definitions);
	}
}
?>
