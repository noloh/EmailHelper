<?php
//Path To NOLOH - Use your path
require_once("/var/www/htdocs/Stable/NOLOH/NOLOH.php");
require_once("../EmailHelper.php");
/**
* Using EmailHelper as an Instance
*/
class EmailExample1 extends WebPage 
{	
	function EmailExample1()
	{
		parent::WebPage('EmailHelper Example 1');
		//Create EmailHelper
		$emailHelper1 = new EmailHelper();
		//From
		$emailHelper1->From = 'john@abc.com';
		//Reply-To - Not Required
		$emailHelper1->ReplyTo = 'john@xyz.com';
		/*
		* All To, CC, and BCC can be set directly or via ArrayList
		* methods such as Add, AddRange, Insert, etc.
		* 
		* $emailHelper1->To = 'john@abc.com';
		* or
		* $emailHelper1->To = array('john@abc.com', 'mary@abc.com');
		* or
		* $emailHelper1->To->Add('john@abc.com');
		* or
		* $emailHelper1->To->AddRange('john@abc.com', 'mary@abc.com');
		*/
		//To
		$emailHelper1->To->Add('sam@abc.com');
		//CC - Not Required
		$emailHelper1->CC->Add('gary@abc.com');
		//BCC - Not Required
		$emailHelper1->BCC->Add('david@xyz.com');
		//Subject
		$emailHelper1->Subject = 'ExampleHelper Test';
		/*Message: Only one is required. If only RichMessage is set, then
		 a Text version of Message will automatically be
		 created for you.*/
		$emailHelper1->Message = 'Some Test Message';
		$emailHelper1->RichMessage = '<b>Some Test Message</b>';
		//Sending EmailHelper1
		$emailHelper1->Send();
	}
}
?>