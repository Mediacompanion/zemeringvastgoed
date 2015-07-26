<?php
	# Security check
	if(!defined('WiLD_GOOGLEMAP_CAPABILITY') || !current_user_can(WiLD_GOOGLEMAP_CAPABILITY)) die("You can'\ access to this page");

	# Delete
	if(isset($_GET['id']))
	{
		if(!is_array($_GET['id']))
			$ids = explode(",", rtrim($_GET['id'], ","));
		else
			$ids = $_GET['id'];

		for($i = 0; $i < sizeof($ids); $i++)
		{
			$item = new WiLD_Googlemap($ids[$i]);
			$item->delete();
		}
	}

	# Redirect
	WiLD_Googlemap_redirect("admin.php?page=wild-googlemap");
?>