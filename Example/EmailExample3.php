<?php
//Path To NOLOH - Use your path
require_once("/var/www/htdocs/Stable/NOLOH/NOLOH.php");
require_once("../EmailHelper.php");
/**
* Using EmailHelper's static Email Function
*/
class EmailExample3 extends WebPage 
{	
	function EmailExample3()
	{
		parent::WebPage('EmailHelper Example 3');
		//Basic Example
		EmailHelper::Email('john@abc.com',  			//From
		                   'gary@abc.com',				//To
		                   'EmailHelper Basic', 		//Subject
		                   'Some Test Message');		//Message
		//Advanced Example
		EmailHelper::Email(array('mary@xyz.com',		//From
		                         'sarah@abc.com'), 	//Reply-To
		//Note that each index can also be an array of strings
		                   array('jake@abc.com',		//To
		                         'joe@xyz.com',			//CC
		                         'max@abc.com'),			//BCC
		                   'EmailHelper Advanced', 			//Subject
		                   array('Some Message',			//Message
		                         '<b>Some Message</b>'));	//RichMessage
	}
}
?>