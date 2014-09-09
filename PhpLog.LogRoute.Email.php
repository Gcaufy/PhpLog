<?php
/**
 * CEmailLogRoute sends selected log messages to email addresses.
 *
 * The target email addresses may be specified via {@link setEmails emails} property.
 * Optionally, you may set the email {@link setSubject subject}, the
 * {@link setSentFrom sentFrom} address and any additional {@link setHeaders headers}.
 *
 * @property array $emails List of destination email addresses.
 * @property string $subject Email subject. Defaults to CEmailLogRoute::DEFAULT_SUBJECT.
 * @property string $sentFrom Send from address of the email.
 * @property array $headers Additional headers to use when sending an email.
 *
 * @author Qiang Xue <qiang.xue@gmail.com> / Gcaufy <gongweiyue@163.com>
 * @package PhpLog.LogRoute
 * @link http://www.madcoder.cn
 */
class CEmailLogRoute extends CLogRoute
{
	/**
	 * @var boolean set this property to true value in case log data you're going to send through emails contains
	 * non-latin or UTF-8 characters. Emails would be UTF-8 encoded.
	 * @since 1.1.13
	 */
	public $utf8=false;
	/**
	 * @var array list of destination email addresses.
	 */
	protected $email=array();
	/**
	 * @var string email subject
	 */
	protected $subject;
	/**
	 * @var string email sent from address
	 */
	protected $from;
	/**
	 * @var array list of additional headers to use when sending an email.
	 */
	protected $headers=array();

	/**
	 * Sends log messages to specified email addresses.
	 * @param array $logs list of log messages
	 */
	protected function processLogs($logs)
	{
		$message='';
		foreach($logs as $log)
			$message.=$this->formatLogMessage($log[0],$log[1],$log[2],$log[3]);
		$message=wordwrap($message,70);
		$subject=$this->getSubject();
		if($subject===null)
			$subject=PhpLog::t('Application Log');
		foreach($this->getEmails() as $email)
			$this->sendEmail($email,$subject,$message);
	}

	/**
	 * Sends an email.
	 * @param string $email single email address
	 * @param string $subject email subject
	 * @param string $message email content
	 */
	protected function sendEmail($email,$subject,$message)
	{
		$headers=$this->getHeaders();
		if($this->utf8)
		{
			$headers[]="MIME-Version: 1.0";
			$headers[]="Content-type: text/plain; charset=UTF-8";
			$subject='=?UTF-8?B?'.base64_encode($subject).'?=';
		}
		if(($from=$this->getSentFrom())!==null)
		{
			$matches=array();
			preg_match_all('/([^<]*)<([^>]*)>/iu',$from,$matches);
			if(isset($matches[1][0],$matches[2][0]))
			{
				$name=$this->utf8 ? '=?UTF-8?B?'.base64_encode(trim($matches[1][0])).'?=' : trim($matches[1][0]);
				$from=trim($matches[2][0]);
				$headers[]="From: {$name} <{$from}>";
			}
			else
				$headers[]="From: {$from}";
			$headers[]="Reply-To: {$from}";
		}
		$rst = mail($email,$subject,$message,implode("\r\n",$headers));
	}

	/**
	 * @return array list of destination email addresses
	 */
	public function getEmails()
	{
		return $this->email;
	}

	/**
	 * @param mixed $value list of destination email addresses. If the value is
	 * a string, it is assumed to be comma-separated email addresses.
	 */
	public function setEmails($value)
	{
		if(is_array($value))
			$this->email=$value;
		else
			$this->email=preg_split('/[\s,]+/',$value,-1,PREG_SPLIT_NO_EMPTY);
	}

	/**
	 * @return string email subject. Defaults to CEmailLogRoute::DEFAULT_SUBJECT
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * @param string $value email subject.
	 */
	public function setSubject($value)
	{
		$this->subject=$value;
	}

	/**
	 * @return string send from address of the email
	 */
	public function getSentFrom()
	{
		return $this->from;
	}

	/**
	 * @param string $value send from address of the email
	 */
	public function setSentFrom($value)
	{
		$this->from=$value;
	}

	/**
	 * @return array additional headers to use when sending an email.
	 * @since 1.1.4
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * @param mixed $value list of additional headers to use when sending an email.
	 * If the value is a string, it is assumed to be line break separated headers.
	 * @since 1.1.4
	 */
	public function setHeaders($value)
	{
		if (is_array($value))
			$this->headers=$value;
		else
			$this->headers=preg_split('/\r\n|\n/',$value,-1,PREG_SPLIT_NO_EMPTY);
	}
}