<?PHP

/**
 * Manages de advances declared by users
 * @author Quetzalcoatl Pantoja Hinojosa
 * @package class
**/

abstract class Resource
{
	protected $ExpireDate;
	protected $Expired;
	
	public function Resource()
	{
		
	}
	
	abstract protected function Expire();
}

?>