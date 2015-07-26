<?php

if(!defined('WiLD_MESSAGE_ERROR')) 	define("WiLD_MESSAGE_ERROR", 	1);
if(!defined('WiLD_MESSAGE_OK'))		define("WiLD_MESSAGE_OK", 		2);
if(!defined('WiLD_MESSAGE_NOTICE'))	define("WiLD_MESSAGE_NOTICE", 	3);

class WiLD_Googlemap_Message
{
	var $type;
	var $message;

	function __construct($_message, $_type)
	{
		$this->type 	= $_type;
		$this->message 	= $_message;
	}

	public function __toString()
	{
	  return $this->type . $this->message;
	}

	function output()
	{
		switch($this->type)
		{
			case WiLD_MESSAGE_ERROR:
				$prefix = "ERROR";
				$class 	= "error";
				break;

			case WiLD_MESSAGE_OK:
				$prefix = "MESSAGE";
				$class 	= "updated ok";
				break;

			case WiLD_MESSAGE_NOTICE:
			default:
				$prefix = "WARNING";
				$class 	= "updated ";
				break;
		}

		echo '<div id="message" class="'. $class .'">';
			echo '<p>';
				if($prefix != '') echo '<b>' . $prefix . '</b>: ';
				echo $this->message;
			echo '</p>';
		echo '</div>';
	}
}


class WiLD_Googlemap_MessageManager
{
	# Vars
	private static $instance 	= null;
	private $messages_in 		= array();
	private $messages_out 		= array();

	# Constructor
	private function __construct() {}
	private function __clone() {}

	# Init (loads messages from session)
	private function init()
	{
		if(!isset($_SESSION["WiLD_messages"])) return;
		$messages = json_decode($_SESSION["WiLD_messages"]);

		foreach($messages as $message)
		{
			$message = new WiLD_Googlemap_Message($message->message, $message->type);
			array_push($this->messages_in, $message);
		}
		unset($_SESSION["WiLD_messages"]);
	}

	# Output
	public function output()
	{
		$this->remove_duplicates();
		foreach($this->messages_in 	as $message) $message->output();
		foreach($this->messages_out as $message) $message->output();
	}

	# Add
	public function add($_message, $_type = WiLD_MESSAGE_NOTICE)
	{
		$message = new WiLD_Googlemap_Message($_message, $_type);
		array_push($this->messages_out, $message);
	}

	# Clean
	public function clean()
	{
		$this->messages_out = array();
	}

	# Remove duplicates
	public function remove_duplicates()
	{
		$this->messages_in 	= array_unique($this->messages_in);
		$this->messages_out = array_unique($this->messages_out);
	}

	# Register (writes messages in session);
	public function register()
	{
		$temp = array();
		foreach($this->messages_out as $message)
		{
			array_push($temp, (array)$message);
		}
		$_SESSION["WiLD_messages"] = json_encode($temp);
	}

	# Singleton
	public static function getInstance()
	{
		if(self::$instance == null)
		{
			$c = __CLASS__;
			self::$instance = new $c;
			self::$instance->init();
		}
		return self::$instance;
	}
}

?>