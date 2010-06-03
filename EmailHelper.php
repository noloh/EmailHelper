<?php
/**
* EmailHelper Nodule Class
* 
* Created by NOLOH LLC.
* This class can be used to send Email either through an instance, or directly
* through the static Email function. If you have a RichMessage (HTML), and no plain text
* EmailHelper will always send out multi-part message for you automatically.
* 
* Emails should be formatted according to standard PHP rules:
* user@example.com
* User <user@example.com
* 
* Note that To, CC, and BCC are Arraylists, this means that you can do ->Add,or ->AddRange:
* <code>
* $email = new EmailHelper();
* $email->To->Add('john@abc.com');
* $email->To->AddRange('mary@def.com', 'james@abc.com');
* $email->To->AddRange(array('jake@ghi.com', 'sam@jkl.com'));
* </code>
* 
* @link http://www.noloh.com
*/
class EmailHelper extends Object
{
	private $To;
	private $CC;
	private $BCC;
	private $Subject;
	private $Message;
	private $RichMessage;
	private $ReplyTo;
	private $From;
	private $DateTime;
	/**
	* EmailHelper Constructor
	* 
	* Creates an EmailHelper object. Note that parameters to contructor are NOT necessary,
	* and merely a convenience. It's preferable syntactically to set otions via properties 
	* and the To, CC, and BCC ArrayLists.
	* 
	* @param string|array $from Email "From". If array, then: array(FROM, REPLY-TO).
	* @param string|array|array(arrays) $to Email "To". If array then: array(TO, CC, BCC).
	* @param string $subject Email "Subject"
	* @param string|array $message Email "Message". If array then: array(TEXT, HTML).
	* @return EmailHelper
	*/
	function EmailHelper($from=null, $to=null, $subject=null, $message=null)
	{
		parent::Object();
		$this->To = new ArrayList();
		$this->CC = new ArrayList();
		$this->BCC = new ArrayList();
		
		if(is_array($from))
		{
			$this->SetFrom($from[0]);
			$this->SetReplyTo($from[1]);
		}
		else 
			$this->SetFrom($from);
		if(is_array($to))
		{
			if(isset($to[0]))
				$this->SetTo($to[0]);
			if(isset($to[1]))
				$this->SetCC($to[1]);
			if(isset($to[2]))
				$this->SetBCC($to[2]);
		}
		else
			$this->SetTo($to);
		$this->SetSubject($subject);
		if(is_array($message))
		{
			$this->SetMessage($message[0]);
			$this->SetRichMessage($message[1]);
		}
		else
			$this->SetMessage($message);
	}
	function SetFrom($email)            {$this->From = $email;}
	function GetFrom()                  {return $this->From;}	
	function SetReplyTo($email)         {$this->ReplyTo = $email;}
	function GetReplyTo()               {return $this->ReplyTo;}
	function SetSubject($subject)       {$this->Subject = $subject;}
	function GetSubject()               {return $this->Subject;}	
	function SetMessage($message)       {$this->Message = $message;}
	function GetMessage()               {return $this->Message;}	
	function SetRichMessage($message)   {$this->RichMessage = $message;}
	function GetRichMessage()           {return $this->RichMessage;}
	function SetTo($email)              {return $this->SetInitialEmail($email, $this->To);}
	function SetCC($email)              {return $this->SetInitialEmail($email, $this->CC);}
	function SetBCC($email)             {return $this->SetInitialEmail($email, $this->BCC);}
	function GetTo()                    {return $this->To;}
	function GetCC()                    {return $this->CC;}
	function GetBCC()                   {return $this->BCC;}
	/**
	* Allows you to set a different Date & Time for the e-mail date.
	* The default is the current date & time.
	* 
	* @param timestamp $dateTime The time & date you wish to have the e-mail date set
	*/
	function SetDateTime($dateTime)		{$this->DateTime = $dateTime;}
	function GetDateTime()				{return $this->DateTime;}
	private function SetInitialEmail($email, $arrayList)
	{
		$arrayList->Clear();
		if(is_string($email))
			$arrayList->Add($email);
		elseif(is_array($email))
			$arrayList->AddRange($email);
		else return false;
		return $arrayList;
	}
	function Send()
	{
		//Generate Headers
		$headers = '';
		if(isset($this->To) && $this->To->Count > 0)
			$to =  implode(', ', $this->To->Elements);
		if(isset($this->From))
			$headers .= "From: {$this->From}\r\n";
		if(isset($this->ReplyTo))
			$headers .= "Reply-To: {$this->ReplyTo}\r\n";
		if(isset($this->DateTime))
		{
			$dateTime = date('r', $this->DateTime);
			$headers .= "Date: {$dateTime}\r\n";
		}
		/*if(isset($this->To) && $this->To->Count > 0)
			$headers .= "To: " . implode(', ', $this->To->Elements) . "\r\n";*/
		if(isset($this->CC) && $this->CC->Count > 0)
			$headers .= "CC: " . implode(', ', $this->CC->Elements) . "\r\n";
		if(isset($this->BCC) && $this->BCC->Count > 0)
			$headers .= "BCC: " . implode(', ', $this->BCC->Elements) . "\r\n";
		//Check for RichMessage, convert to Text if no Message is set, and generate body
		if(isset($this->RichMessage))
		{
			$richMessage = $this->RichMessage;
			if(isset($this->Message))
				$textMessage = $this->Message;
			else
			{
				require_once(dirname(__FILE__) . '/Includes/class.html2text.inc');
				$convertor = new html2text($richMessage);
				$textMessage = $convertor->get_text();
			}
			$hash = $dateTime?$dateTime:date('r', time());
			$headers .= 'Content-Type: multipart/alternative; boundary="' . $hash . '"'; 
			$body = $this->GenerateRichBody($textMessage, $richMessage, $hash);
		}
		else
			$body = $this->Message;
		return mail($to, $this->Subject, $body, $headers);
	}
	/**
	* Generates the Body for a multi-part message
	* 
	* @param string $textMessage
	* @param string $richMessage
	* @param string $hash
	* @return body The generated mult-part body of the message
	*/
	private function GenerateRichBody($textMessage, $richMessage, $hash)
	{
		//Generate Body !important! do not indent HEREDOC
		$body = <<<EOT
--$hash
Content-Type: text/plain; charset="iso-8859-1" 
Content-Transfer-Encoding: 7bit

$textMessage

--$hash
Content-Type: text/html; charset="iso-8859-1" 
Content-Transfer-Encoding: 7bit

$richMessage

--$hash--
EOT;
		return $body;	
	}
	/**
	* Static Email function
	* 
	* Allows for an Email to be sent without the need to create an EmailHelper object
	* 
	* @param string|array $from Email "From". If array, then: array(FROM, REPLY-TO).
	* @param string|array|array(arrays) $to Email "To". If array then: array(TO, CC, BCC).
	* @param string $subject Email "Subject".
	* @param string|array $message Email "Message". If array then: array(TEXT, HTML).
	* @return bool Whether the Email was sent successfully.
	*/
	static function Email($from, $to, $subject, $message)
	{
		$email = new EmailHelper($from, $to, $subject, $message);
		return $email->Send();
	}
}
?>