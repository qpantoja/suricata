<?php

 /**
 * @desc includes class files, calls the menu and diplay messages
 * @package request
 */

//includes de class directory...UserAdmin,DataBaseAdmin
$class_req=array(ActionThread,Advance,AsignationTable,Budget,ChangesSheet,DBase,Gantt,
	GanttGenerator,JobEntry,JobEntryGenerator,Menu,Message,Person,Project,ProjectAdmin,
	ProposalSolicitude,Report,Resource,SecuenceTable,Semaphore,SysAdmin,Task,Team,User,Material);

//Person,DBase,User,Menu,ChangesSheet,ProjectAdmin,SysAdmin,Message,Project,Team,ProposalSolicitude,Task,SecuenceTable,JobEntry,JobEntryGenerator);

foreach ($class_req as $name)
{
	include_once("class/$name.class.php");
}
session_start();
include_once "config/config.php";
include_once $config[lang];
//if user has chosen a project, load nomenclature
if($_SESSION[Project]->Nomenclature!=null)
	{include_once "config/nomenclature/".$_SESSION[Project]->Nomenclature.".php";}

//send the head of the page
print("
<html>
        <head>
        <title>$config[title]</title>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
        <link href=\"$config[main_css]\" rel=\"stylesheet\" type=\"text/css\" media=\"all\">
        <link rel=\"icon\" href=\"$config[nav_icon]\" type=\"image/png\">
        <link rel=\"shortcut icon\" href=\"$config[nav_icon]\" type=\"image/png\">
        
        <script type=\"text/javascript\" src=\"JSCookMenu.js\"></script>
        <link rel=\"stylesheet\" href=\"config/css/menu_theme.css\" type=\"text/css\" />
        <script type=\"text/javascript\" src=\"menu_theme.js\"></script>
        <script type=\"text/javascript\" src=\"validator.js\" language=\"JavaScript\"></script>
        <SCRIPT LANGUAGE=\"JavaScript\" SRC=\"$lang[Calendar]\"></SCRIPT>
        <SCRIPT LANGUAGE=\"JavaScript\">var cal = new CalendarPopup();</SCRIPT>

        </head>
        <body>
        <div align=\"left\" id=\"header\" class=\"header\">
                <a href=\"./\"><img name=\"header\" src=\"$config[logo_head]\" border=\"0\" ></a>
        ");

//check session
if($_SESSION['User'])
        {
        $menu=new Menu($_SESSION['User']);
        $menu->show();
        }
else
	{
		//what if not registred in any other page than the wellcome?
		if(($_GET['class']!='')&&($_GET['message']!=''))
			{print("<p class=$_GET[class]>$_GET[message]</p>");}
		include('wellcome.php');
		die();
	}
//is there any message to display
if(($_GET['class']!='')&&($_GET['message']!=''))
{
	print("<p class=$_GET[class]>$_GET[message]</p>");
}
else{print ('<br>');}
print('
		</div>
		<div align="center">');
?>
