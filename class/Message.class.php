<?PHP

/**
 * @desc Manages the messages around the system
*@package class
*/

class Message 
{
   private $MessageID;
   private $Title;
   private $Date;
   private $Detail;
   private $Read;
   
   public function Message($MessageID) 
   {
        $db=new DBase();
    	$query='SELECT * FROM "message" WHERE idmessage=\''.$MessageID.'\'';
    	$res=$db->DB->getAll($query);
    	if (PEAR::isError($res))
		{
			print_r($res->getDebugInfo());
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=index.php?message='.$res->getMessage().'&&class=error">');
			return false;
		}
		if(empty($res))
			{print($GLOBALS[lang][No_Messages]);}
		$this->MessageID=$res[0]['idmessage'];
		$this->Title=$res[0]['title'];
		$this->Date=$res[0]['date'];
		$this->Detail=$res[0]['detail'];
		if ($res[0]['read']=='t'){$this->Read=true;}
		else {$this->Read=false;}
   }
   
   
   /**
    * @desc shows the status
    */
   public static function ListMessages() 
   {
    	$db=new DBase();
    	$query='SELECT * FROM "message" WHERE iduser=\''.$_SESSION[User]->UserID.'\' ORDER BY date DESC';
    	$res=$db->DB->getAll($query);
    	if (PEAR::isError($res))
		{
			print_r($res->getDebugInfo());
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=index.php?message='.$res->getMessage().'&&class=error">');
			return false;
		}
		if(empty($res))
			{print($GLOBALS[lang][No_Messages]);return (true);}
		print('<p><table class="std">
				<tr class="std"><td>'.$GLOBALS[lang][Date].'</td><td>'.$GLOBALS[lang][Title].'</td></tr>');
    	foreach ($res as $key=>$entry)
    	{
    		print('<tr ');if ($key%2){print('class="std"');} print('>
    					<td width="15%">'.$entry['date'].'</td>
    					<td><a href="message.php?action=showdetails&&MessageID='.$entry['idmessage'].'">'.$entry['title'].'</a></td>
    					<td width="5%">');
    						if($entry['read']=='t')
    							{print('<img src="images/icons/green_dot.png">');}
    						else{print('<img src="images/icons/red_dot.png">');}
    					print('</td>
    				</tr>');
    	}
    	print('</table></p>');
   }
      
   /**
    * @desc adds an entry
    */
   public static function AddEntry($UserID,$Title,$Detail) 
   {
    	$db=new DBase();
    	$table_name = 'message';
    	$fields_values = array(
    		'iduser'=> $UserID,
    		'title'	=> $Title,
			'detail'=> $Detail,
			'date'	=> date("Y-m-d")
			);
    	$res = $db->DB->autoExecute($table_name, $fields_values,DB_AUTOQUERY_INSERT);
    	if (PEAR::isError($res))
    	{
			print_r($res->getDebugInfo());
			print('<meta HTTP-EQUIV="REFRESH" content="0; url=index.php?message='.$res->getMessage().'&&class=error">');
			return false;
		}
		else 
		{return true;}
   }
   
    /**
    * @desc deletes a message entry
    */
   public static function DelEntry($MessageID) 
   {
   		$db=new DBase();
    	$query='SELECT * FROM message WHERE idmessage='.$MessageID;
    	$res=$db->DB->getAll($query);
   		if($res[0][iduser]==$_SESSION[User]->UserID)
   		{
   			$query='DELETE FROM message WHERE idmessage='.$MessageID;
   			$res=$db->DB->query($query);
   			if (PEAR::isError($res))
    		{
	    		print_r($res->getDebugInfo());
				print('<meta HTTP-EQUIV="REFRESH" content="0; url=index.php?message='.$res->getMessage().'&&class=error">');
				return false;
    		}
    		print('<meta HTTP-EQUIV="REFRESH" content="0; url=message.php?action=show&&message='.$GLOBALS[lang][Message_Deleted].'&&class=success">');
		}
		else 
		{print ('<meta HTTP-EQUIV="REFRESH" content="0; url=message.php?action=show&&message='.$GLOBALS[lang][Message_Not_Deleted].'&&class=success">');}
   }
   
   /**
    * Sends a message to everyone in a given project
    * @param Integer $ProjectID
    * @param String $Title
    * @param String $Detail
    * @return bool
    */
   public static function ProjectBroadcast($ProjectID,$Title,$Detail)
   {
   	$table_name = 'message';
    $fields_values = array(
    		'title'	=> $Title,
			'detail'=> $Detail,
			'date'	=> date("Y-m-d")
			);
   	$db=new DBase();
   	$res=$db->DB->getAll('SELECT * FROM project_has_user WHERE idproject=\''.$ProjectID.'\'');
   	foreach ($res as $user)
   	{
   		$fields_values['iduser']=$user['iduser'];
    	$result = $db->DB->autoExecute($table_name, $fields_values,DB_AUTOQUERY_INSERT);
    	if (PEAR::isError($result))
    	{
			return $result;
		}
   	}
	return true;
   }
   
   /**
    * Sends a message to everyone in the system
    *
    * @param String $Title
    * @param String $Detail
    * @return bool
    */
   public static function SystemBroadcast($Title,$Detail)
   {
   	$table_name = 'message';
    $fields_values = array(
    		'title'	=> $Title,
			'detail'=> $Detail,
			'date'	=> date("Y-m-d")
			);
   	$db=new DBase();
   	$res=$db->DB->getAll('SELECT * FROM "user"');
   	foreach ($res as $user)
   	{
   		$fields_values['iduser']=$user['iduser'];
    	$result = $db->DB->autoExecute($table_name, $fields_values,DB_AUTOQUERY_INSERT);
    	if (PEAR::isError($result))
    	{
			return $result;
		}
   	}
	return true;
   }
   
   public function __toString()
   {
   	//mark this status as read
   	$table_name='message';
   	$fields_values = array(
   		'read' => 't'
   		);
   	$where='idmessage=\''.$this->MessageID.'\'';
	$db=new DBase();
	$res=$db->DB->autoExecute($table_name,$fields_values,DB_AUTOQUERY_UPDATE,$where);
	if (PEAR::isError($res))
		{print($res->getMessage());}
   	$details='<p><table class="std">
    				<tr class="std">
    					<td>'.$this->Title.'</td>
    					<td width="15%">'.$this->Date.'</td>
    					<td  width=16px><a href="message.php?action=delete&&MessageID='.$this->MessageID.'"><img src="images/icons/delete.png"></a></td>
    				</tr>
    				<tr><td colspan="3">'.$this->Detail.'</td></tr>
    			</table></p>';
   	return($details);
   }
   
   public function __get($attribute)
   {
   	return($this->$attribute);
   }
}
?>