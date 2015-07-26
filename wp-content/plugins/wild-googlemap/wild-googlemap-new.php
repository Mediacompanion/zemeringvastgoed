<?php
	# Security check
	if(!defined('WiLD_GOOGLEMAP_CAPABILITY') || !current_user_can(WiLD_GOOGLEMAP_CAPABILITY)) die("You can'\ access to this page");

	# New googlemap
	$googlemap = new WiLD_Googlemap();

	# Save
	if(isset($_POST['save']))
	{
		if(isset($_POST['name']))	$googlemap->name = $_POST['name'];

		if($googlemap->save())
		{
			WiLD_Googlemap_redirect("admin.php?page=wild-googlemap&id={$googlemap->id}");
		}
	}
?>
<?php
	# Get admin header
	if(isset($_GET['noheader']) && $_GET['noheader'])
	{
		include (ABSPATH . 'wp-admin/admin-header.php');
	}
?>
<div class="wrap" id="wild_googlemap">
	<h2>New Googlemap</h2>

	<?php WiLD_Googlemap_MessageManager::getInstance()->output()?>

	<form method="post" enctype="multipart/form-data" action="admin.php?page=wild-googlemap-new&noheader=true">

		<h3>Googlemap data</h3>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">Map name</th>
					<td>
						<input type="text" name="name" maxlength="70" size="100" value="<?php echo WiLD_Googlemap_escape($googlemap->name)?>" />
					</td>
				</tr>
			</tbody>
		</table>

		<br />

		<input type="hidden" name="save" value="<?php echo $googlemap->id?>" />
		<input class="button-primary" type="submit" value="<?php _e("Create"); ?>" />

	</form>

</div>