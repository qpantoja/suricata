<?php
/**
* @package class
* @TODO call the menu for that user, nomenclature?
*/

class Menu
{
	private $Menu;

	public function Menu(User $User)
        {		
                $this->Menu="var menu =
				[
					['&nbsp;&nbsp;','".$GLOBALS['lang']['Administration']."','','_self','',";
                		if($User->SysAdmin->ShowUsers)
	                		{$this->Menu.="\n\t\t\t\t\t\t['','".$GLOBALS['lang']['Users']."','adminusers.php?action=show','_self',''],";}
                		if($User->SysAdmin->EditOwnData)
	                		{$this->Menu.="\n\t\t\t\t\t\t['','".$GLOBALS['lang']['Personal_Data']."','adminpersonaldata.php?action=show','_self',''],";}
                		if($User->SysAdmin->ShowProjects)
	                		{$this->Menu.="\n\t\t\t\t\t\t['','".$GLOBALS['lang']['Projects_Admin']."','adminprojects.php?action=show','_self',''],";}
                		if($User->SysAdmin->Backup||$User->SysAdmin->Restore)
	                		{$this->Menu.="\n\t\t\t\t\t\t['','".$GLOBALS['lang']['Data_Base']."','admindatabase.php?action=show','_self',''],";}
                		$this->Menu.="\n\t\t\t\t\t\t['','".$GLOBALS['lang']['Logout']."','logout.php','_self','']
					],
					['&nbsp;&nbsp;','".$GLOBALS['lang']['Project']."','','_self','',";
                		if($User->ProjectAdmin->ShowProjectStatus)
                			{$this->Menu.="\n\t\t\t\t\t\t['','".$GLOBALS['lang']['Status']."','project.php?action=showstatus','_self',''],";}
                			//may be advances shall be allowed by anyone
                		if($User->ProjectAdmin->ShowAdvances||$User->ProjectAdmin->Gen_Advances)
                			{
                				$this->Menu.="\n\t\t\t\t\t\t['','".$_SESSION['Nomenclature']['Advance']."','','_self','',";
                				if($User->ProjectAdmin->ShowAdvances)
                				{
                					$this->Menu.="\n\t\t\t\t\t\t\t['','".$_SESSION['Nomenclature']['Show_Advances']."','projectadvance.php?action=show','_self','']";
                					if($User->ProjectAdmin->GenAdvances)
                					{$this->Menu.=",";}
                				}
								if($User->ProjectAdmin->GenAdvances)
									{$this->Menu.="\n\t\t\t\t\t\t\t['','".$_SESSION['Nomenclature']['Gen_Advance']."','projectadvance.php?action=generate','_self','']";}
								$this->Menu.="\n\t\t\t\t\t\t],";
                			}
                		if($User->ProjectAdmin->ShowChangesSheet)
                			{$this->Menu.="\n\t\t\t\t\t\t['','".$_SESSION['Nomenclature']['Changes_Sheet']."','projectchangesh.php?action=show','_self',''],";}
						if($User->ProjectAdmin->ShowGantt||$User->ProjectAdmin->ShowJobEntry)
							{
								$this->Menu.="\n\t\t\t\t\t\t['','".$GLOBALS['lang']['Diagrams']."','','_self','',";
								if($User->ProjectAdmin->ShowGantt)
									{
										$this->Menu.="\n\t\t\t\t\t\t\t['','".$_SESSION['Nomenclature']['Gantt']."','projectgantt.php?action=show','_self','']";
										if($User->ProjectAdmin->ShowJobEntry){$this->Menu.=",\n\t\t\t\t\t\t\t['','".$_SESSION['Nomenclature']['Job_Entry']."','projectjobentry.php?action=show','_self','']";}
									}
								else
									{$this->Menu.="\n\t\t\t\t\t\t\t['','".$_SESSION['Nomenclature']['Job_Entry']."','projectjobentry.php?action=show','_self','']";}
								$this->Menu.="\n\t\t\t\t\t\t],";
							}
						if ($User->ProjectAdmin->ShowPSolicitude)
							{$this->Menu.="\n\t\t\t\t\t\t['','".$_SESSION['Nomenclature']['Proposal_Solicitude']."','proposalsolicitude.php?action=show','_self',''],";}
						if($User->ProjectAdmin->ShowTAsignation||$User->ProjectAdmin->ShowTSecuence||$User->ProjectAdmin->AdminTasks)
							{
								$ban=false;
								$this->Menu.="\n\t\t\t\t\t\t['','".$GLOBALS['lang']['Tasks']."','','_self','',";
								if ($User->ProjectAdmin->ShowTSecuence)
									{
										$this->Menu.="\n\t\t\t\t\t\t\t['','".$_SESSION['Nomenclature']['Show_Secuence_Table']."','showtasksec.php?action=show','_self','']";
										$ban=true;
									}
								if ($User->ProjectAdmin->ShowTAsignation)
									{
										if($ban){$this->Menu.=",";}
										$this->Menu.="\n\t\t\t\t\t\t\t['','".$_SESSION['Nomenclature']['Show_Asignation_Table']."','showtaskasig.php?action=show','_self','']";
										$ban=true;
									}
								if ($User->ProjectAdmin->AdminTasks)
									{
										if($ban){$this->Menu.=",";}
										$this->Menu.="\n\t\t\t\t\t\t\t['','".$GLOBALS['lang']['Admin_Tasks']."','admintasks.php?action=show','_self','']";
									}
								$this->Menu.="\n\t\t\t\t\t\t],";
							}
						//everyone can choose a project...
						$this->Menu.="
						['','".$GLOBALS['lang']['Select_Project']."','project.php?action=select','_self','']
					],
					['&nbsp;&nbsp;','".$GLOBALS['lang']['Tracking']."','','_self','',";
					if ($User->ProjectAdmin->CreateReport)
						{$this->Menu.="\n\t\t\t\t\t\t['','".$GLOBALS['lang']['Reports']."','reports.php?action=show','_self',''],";}
					
					if ($User->ProjectAdmin->AdminMaterial||$User->ProjectAdmin->AdminHumanR||$User->ProjectAdmin->ModifyBudget||$User->ProjectAdmin->UseBudget)	
					{
						$this->Menu.="\n\t\t\t\t\t\t['','".$GLOBALS['lang']['Resources']."','','_self','administracion',";
						$ban=false;
						if($User->ProjectAdmin->AdminMaterial)
						{
							$this->Menu.="['','".$GLOBALS['lang']['Material']."','resourcemat.php?action=show','_self','']";
							$ban=true;
						}
						if($User->ProjectAdmin->ModifyBudget||$User->ProjectAdmin->UseBudget)
						{
							if($ban){$this->Menu.=",";}
							$this->Menu.="['','".$GLOBALS['lang']['Budget']."','resourcebudget.php?action=show','_self','']";
							$ban=true;
						}
						if($User->ProjectAdmin->AdminHumanR)
						{
							if($ban){$this->Menu.=",";}
							$this->Menu.="['','".$GLOBALS['lang']['Human']."','resourcehuman.php?action=show','_self','']";
						}
						$this->Menu.="],";
					}
					
						
					$this->Menu.="
						['','".$GLOBALS['lang']['Show_Messages']."','message.php?action=show','_self','']
					]
				];";
						
        }
        
        /**
        *Shows the menu, printing the javascript code
        *@todo implementar algo mas bonito :)
        */
        public function Show()
        {
                print('<SCRIPT LANGUAGE="JavaScript"><!--
				'.$this->Menu.'
				--></SCRIPT>');
                print('<div align="left" id="MenuPlace"></div>
                <script type="text/javascript"><!--
                cmDraw (\'MenuPlace\', menu, \'hbr\', cmThemeMiniBlack, \'ThemeMiniBlack\');
                --></script>');
        }
}
?>