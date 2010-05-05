<?php
//Path To NOLOH - Use your path
require_once("/var/www/htdocs/Stable/NOLOH/NOLOH.php");
require_once("../EmailHelper.php");
/**
* Using EmailHelper as an Instance with constructor options
*/
class EmailExample2 extends WebPage 
{	
	function EmailExample2()
	{
		parent::WebPage('EmailHelper Example 2');
		//Basic Example
		$emailHelper1 = new EmailHelper(
							'john@abc.com',  		//From
							'gary@abc.com',			//To
							'EmailHelper Basic', 	//Subject
							'Some Test Message');	//Message
		$emailHelper1->Send();
		//Advanced Example
		$emailHelper2 = new EmailHelper(
							array('john@xyz.com',	//From
								  'mary@abc.com'), 	//Reply-To
		//Note that each index can also be an array of strings
							array('jake@abc.com',	//To
							      'sim@abc.com',	//CC
							      'joe@xyz.com'),	//BCC
							'EmailHelper Advanced', 		//Subject
							array('Some Message',			//Message
							      '<b>Some Message</b>'));	//RichMessage
		//From
		$emailHelper2->Send();
	}
}
?>