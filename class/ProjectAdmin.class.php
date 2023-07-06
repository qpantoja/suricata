<?php

/**
*@package class
*/

class ProjectAdmin 
{
   private $ShowProjectStatus;
   private $EditProyectStatus;
   private $ShowAdvances;
   private $GenAdvances;
   private $EvaluateAdvance;
   private $ShowChangesSheet;
   private $CreateChangesSheet;
   private $EditChangesSheet;
   private $DeleteChangesSheet;
   private $ShowGantt;
   private $ShowTSecuence;
   private $ShowTAsignation;
   private $AdminTasks;
   private $ShowPSolicitude;
   private $CreatePSolicitude;
   private $EditPSolicitude;
   private $DeletePSolicitude;
   private $ModifyBudget;
   private $UseBudget;
   private $AdminMaterial;
   private $AdminHumanR;
   private $CreateReport;
   private $ShowJobEntry;
   
   /**
    * @desc class constructor
    */
   public function ProjectAdmin($ProjectRole) 
   {
    $db=new DBase();
    $query='SELECT * FROM "project_role" WHERE "idproject_role"=\''.$ProjectRole.'\'';
   	$res=$db->DB->getAll($query);
   	$this->ShowProjectStatus=$res[0][show_project_status];
   	$this->EditProyectStatus=$res[0][edit_project_status];
   	$this->ShowAdvances=$res[0][show_advances];
   	$this->GenAdvances=$res[0][generate_advances];
   	$this->EvaluateAdvance=$res[0][evaluate_advance];
   	$this->ShowChangesSheet=$res[0][show_changes_sheets];
   	$this->CreateChangesSheet=$res[0][create_changes_sheet];
   	$this->EditChangesSheet=$res[0][edit_changes_sheet];
   	$this->DeleteChangesSheet=$res[0][delete_changes_sheet];
   	$this->ShowJobEntry=$res[0][show_job_entry];
   	$this->ShowGantt=$res[0][show_gantt];
   	$this->ShowTSecuence=$res[0][show_task_secuence];
   	$this->ShowTAsignation=$res[0][show_task_asignation];
   	$this->AdminTasks=$res[0][admin_tasks];
   	$this->ShowPSolicitude=$res[0][show_p_solicitude];
   	$this->CreatePSolicitude=$res[0][create_p_solicitude];
   	$this->EditPSolicitude=$res[0][create_p_solicitude];
   	$this->DeletePSolicitude=$res[0][delete_p_solicitude];
   	$this->ModifyBudget=$res[0][modify_budget];
   	$this->UseBudget=$res[0][use_budget];
   	$this->AdminMaterial=$res[0][admin_material];
   	$this->AdminHumanR=$res[0][admin_human_r];
   	$this->CreateReport=$res[0][create_report];
   }
   
   public function __get($get)
   {
   	//provided that the database returns f or t, it should be changed to a bolean
   	if ($this->$get=='t')
   		{return (true);}
   	else
   		{return (false);}
   }
}
?>