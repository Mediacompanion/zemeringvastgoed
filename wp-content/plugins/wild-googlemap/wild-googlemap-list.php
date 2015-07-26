<?php
	# Security check
	if(!defined('WiLD_GOOGLEMAP_CAPABILITY') || !current_user_can(WiLD_GOOGLEMAP_CAPABILITY)) die("You can'\ access to this page");

	# Fetch maps
	$googlemaps = WiLD_BackendGooglemapManager::getInstance()->get_maps();
?>
<div class="wrap">
	<h2>Googlemap <a class="add-new-h2" href="admin.php?page=wild-googlemap-new">Add New</a></h2>

	<?php WiLD_Googlemap_MessageManager::getInstance()->output()?>

	<form id="form_table_actions" method="get" enctype="multipart/form-data" action="admin.php" onsubmit="javascript:return wild_googlemap_table_form_actions()">

		<input type="hidden" name="page" value="wild-googlemap" />
		<input type="hidden" name="noheader" value="true" />

		<div class="tablenav top">
			<div class="alignleft actions">
				<select name="action">
					<option selected="selected" value="">Bulk Actions</option>
					<option value="delete" data-check="Do you want to delete the selected items?">Delete</option>
				</select>
				<input type="submit" value="Apply" class="button action" id="doaction" name="">
			</div>
			<br class="clear">
		</div>

		<table class="widefat wild">
			<thead>
				<tr>
					<th class="check-column"><input type="checkbox" name="check_all" scope="col" /></th>
					<th scope="col" width="20">Id</th>
					<th scope="col">Googlemap</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($googlemaps as $googlemap):?>
				<tr>
					<th class="check-column">
						<input type="checkbox" name="id[]" value="<?php echo $googlemap->id?>" scope="row" />
					</th>
					<td>
						<?php echo $googlemap->id?>
					</td>
					<td>
						<strong>
							<a href="admin.php?page=wild-googlemap&id=<?php echo $googlemap->id?>" class="row-title">
								<?php echo $googlemap->name?>
							</a>
						</strong>

						<div class="row-actions">
							<span class="edit">
								<a href="admin.php?page=wild-googlemap&id=<?php echo $googlemap->id?>" title="Edit this item">Edit</a>
							</span> |
							<span class="delete">
								<a href="javascript:if(confirm('Do you want to delete this item?')) window.location = 'admin.php?page=wild-googlemap&id=<?php echo $googlemap->id?>&action=delete&noheader=true'" title="Delete this item" class="submitdelete">Delete</a>
							</span>
						</div>
					</td>
				</tr>
				<?php endforeach;?>
				<?php if(sizeof($googlemaps) == 0):?>
				<tr>
					<td colspan="3"><p>No googlemap found</p></td>
				</tr>
				<?php endif;?>
			</tbody>
		</table>

		<div class="tablenav bottom">
			<div class="tablenav-pages one-page"><span class="displaying-num"><?php echo sizeof($googlemaps)?> item<?php echo sizeof($googlemaps)!= 1 ? 's' : ''?></span></div>
			<br class="clear">
		</div>

	</form>
</div>