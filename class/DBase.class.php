<?php

/**
 * @author Pantoja Hinojosa Quetzalcoatl
 * @package class
**/

include_once ('DB.php');

class DBase
{
	private $Type='pgsql';
	private $User='suricata';
	private $Password='suricata';
	private $Host='localhost';
	private $Database='suricata';
	private $Pg_Bin='/usr/local/pgsql/bin/';
	private $DB;

	public function DBase()
	{
		$dsn = "$this->Type://$this->User:$this->Password@$this->Host/$this->Database";
		$options = array(
    			'debug'       => 2,
    			'portability' => DB_PORTABILITY_ALL,
			);
		$this->DB =& DB::connect($dsn, $options);
		if (PEAR::isError($this->DB))
		{
    		error_log("Error tring to connect to database!", 0); //0 -> phplog
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=index.php?message='.$this->DB->getMessage()." is the database up?".'&&class=error">');
			die();
		}
		$this->DB->setFetchMode(DB_FETCHMODE_ASSOC);
		$this->DB->autoCommit(true);
	}
	
	/**
    * @desc implements the backup metod
    */
   public function Backup() 
   {
    	if($_SESSION[User]->SysAdmin->Backup)
    	{
    		$filename='suricata.backup.'.date("Y-m-d").'.sql';
    		passthru ( $this->Pg_Bin.'pg_dump -i -U suricata -F p -c -D -v -f "db/'.$filename.'" suricata', $messages); //2>&1
    		if ($messages!=0)
    		{
    			print('<p class=error>'.$GLOBALS[lang][No_Backup].'</p>');
    			return false;
    		}
    		else 
    		{
    			print('<a href="db/'.$filename.'">'.$GLOBALS[lang][Download].'</a>');
    			return true;
    		}
    	}
    	else
    	{
    		print('not enougth privilegies');
    		return false;
    	}
   }
   
   /**
    * @desc implements the restore metod
    */
   public function Restore($File) 
   {
   		if($_SESSION[User]->SysAdmin->Restore)
   		{
   			print('<pre>'.$GLOBALS[lang][Restore_Result]."\n");
   			passthru ( $this->Pg_Bin.'dropdb suricata');
   			passthru ( $this->Pg_Bin.'createdb suricata');
   			passthru ( $this->Pg_Bin.'psql -U suricata suricata < db/'.$File.' 2>&1',$messages);
   			if($messages!=0)
   			{
   				print('<p class=error>'.$GLOBALS[lang][No_Restore].$messages.'</p>');
   			}
   			print('</pre>');
   		}
   		else
   		{
   			print('not enougth privilegies');
   		}
   }
	
	public function __destruct()
	{
		$this->DB->disconnect();
	}
	
	public function __get($get)
	{
		if($get==DB)
		{
			return ($this->$get);
		}
	}
}
?>