<?php

/**
 * @author Pantoja Hinojosa Quetzalcoatl
 * @package request
**/

//print ('<a href="javascript:window.history.back()"><img src="images/icons/back"></a>');

print ('</div>
		<div align="right">');
	//if there is a project selected, show it's name on the foot...
	if(!empty($_SESSION[Project]))
	{
		print('
		<p align="center" class="message">'.$_SESSION['Nomenclature']['Project_Selected'].$_SESSION[Project]->Name.'</p>');
	}
print ('
		</div>
	</body>
</html>');

?>
