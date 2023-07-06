<?PHP

/**
 * Manages de advances declared by users
 * @author Quetzalcoatl Pantoja Hinojosa
 * @package class
**/

class Semaphore
{
	private $color;
	private $red='images/icons/red_dot.png';
	private $green='images/icons/green_dot.png';
	private $yellow='images/icons/yellow_dot.png';
	
	public function Semaphore($Object)
	{
		switch (get_class($Object))
		{
			case "Task":
				$this->TaskSemaphore($Object);
				break;
				
			case "Budget":
				$this->BudgetSemaphore($Object);
				break;
				
			case "Material":
				$this->MaterialSemaphore($Object);
				break;
				
			case "Message":
				$this->MaterialSemaphore($Object);
				break;
		}
	}
	
	private function TaskSemaphore($Task)
	{
		$ProgramedBegin=explode("-", $Task->ProgramedBegin);
		$ProgramedBegin=mktime(0, 0, 0, $ProgramedBegin[1],$ProgramedBegin[2],$ProgramedBegin[0]);
		
		$ProgramedEnd=explode("-", $Task->ProgramedEnd);
		$ProgramedEnd=mktime(0, 0, 0, $ProgramedEnd[1],$ProgramedEnd[2],$ProgramedEnd[0]);
		
		if($Task->BeginDate!=null)
		{
			$BeginDate=explode("-", $Task->BeginDate);
			$BeginDate=mktime(0, 0, 0, $BeginDate[1],$BeginDate[2],$BeginDate[0]);
		}
		else {$BeginDate=null;}
		
		if($Task->EndDate!=null)
		{
			$EndDate=explode("-", $Task->EndDate);
			$EndDate=mktime(0, 0, 0, $EndDate[1],$EndDate[2],$EndDate[0]);
		}
		else{$EndDate=null;}
		
		$Today=mktime();

		if(empty($BeginDate))
		{ //existe fecha inicial?
			if($Today<=$ProgramedBegin)
			{
				$this->color='green';
			}
			else 
			{
				$this->color='red';
			}
		}
		else
		{ //
			if(empty($EndDate))
			{ //
				if($BeginDate<$ProgramedBegin)
				{
					$this->color='yellow';
				}
				if($BeginDate==$ProgramedBegin)
				{
					$this->color='green';
				}
				if ($BeginDate>$ProgramedBegin)
				{
					$this->color='red';
				}
			}
			else 
			{
				if($BeginDate<=$ProgramedBegin && $EndDate<=$ProgramedBegin)
				{
					$this->color='green';
				}
				if($BeginDate<=$ProgramedBegin && $EndDate>$ProgramedBegin)
				{
					$this->color='yellow';
				}
				if($BeginDate>$ProgramedBegin && $EndDate>$ProgramedBegin)
				{
					$this->color='red';
				}
				if($BeginDate>$ProgramedBegin && $EndDate<=$ProgramedBegin)
				{
					$this->color='yellow';
				}
			}
		}		
		return($this->ReturnColor());
	}
	
	private function BudgetSemaphore($Budget)
	{
		if($Budget->Expired)
		{
			$this->color='red';
		}
		else 
		{
			$this->color='green';
		}
		return($this->ReturnColor());
	}
	
	private function MaterialSemaphore($Material)
	{
		if($Material->Expired)
		{
			$this->color='red';
		}
		else 
		{
			$this->color='green';
		}
		return($this->ReturnColor());
	}
	
	private function MessageSemaphore($Message)
	{
		if ($Message->Read)
		{
			$this->color='green';
		}
		else 
			$this->color='red';
		return($this->ReturnColor());
	}
	
	private function ReturnColor()
	{
		switch ($this->color)
		{
			case "green":
					return($this->green);
				break;
				
			case "yellow":
					return($this->yellow);
				break;
				
			case "red":
					return($this->red);
				break;
		}
	}
	
	public function __get($attribute)
	{
		return ($this->$attribute);
	}
	
	public function __toString()
	{
		return ($this->ReturnColor());
	}
}

?>