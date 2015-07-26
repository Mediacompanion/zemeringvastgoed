<?php
	# Security check
	if(!defined('WiLD_GOOGLEMAP_CAPABILITY') || !current_user_can(WiLD_GOOGLEMAP_CAPABILITY)) die("You can'\ access to this page");

	# New map
	if(!isset($_GET['id'])) die("This element doesn't exist");
	$googlemap = new WiLD_Googlemap($_GET['id']);
	if($googlemap->id == 0) die("This element doesn't exist");

	# Save
	if(isset($_POST['save']))
	{
		if(isset($_POST['name']))				$googlemap->name 	= $_POST['name'];
		if(isset($_POST['w'])) 					$googlemap->w		= WiLD_Googlemap_unescape($_POST['w']);
		if(isset($_POST['h'])) 					$googlemap->h		= WiLD_Googlemap_unescape($_POST['h']);
		if(isset($_POST['lat'])) 				$googlemap->lat		= WiLD_Googlemap_unescape($_POST['lat']);
		if(isset($_POST['lng'])) 				$googlemap->lng		= WiLD_Googlemap_unescape($_POST['lng']);
		if(isset($_POST['zoom'])) 				$googlemap->zoom	= WiLD_Googlemap_unescape($_POST['zoom']);

		if(isset($_POST['option_mapTypeId']))			$googlemap->set_option('mapTypeId', 			$_POST['option_mapTypeId']);
		if(isset($_POST['option_mapTypeControl']))		$googlemap->set_option('mapTypeControl', 		$_POST['option_mapTypeControl']);
		if(isset($_POST['option_streetViewControl']))	$googlemap->set_option('streetViewControl', 	$_POST['option_streetViewControl']);
		if(isset($_POST['option_zoomControl']))			$googlemap->set_option('zoomControl',			$_POST['option_zoomControl']);
		if(isset($_POST['option_panControl']))			$googlemap->set_option('panControl',			$_POST['option_panControl']);
		if(isset($_POST['option_scaleControl']))		$googlemap->set_option('scaleControl',			$_POST['option_scaleControl']);
		if(isset($_POST['option_rotateControl']))		$googlemap->set_option('rotateControl',			$_POST['option_rotateControl']);
		if(isset($_POST['option_overviewMapControl']))	$googlemap->set_option('overviewMapControl', 	$_POST['option_overviewMapControl']);
		if(isset($_POST['option_draggable']))			$googlemap->set_option('draggable', 			$_POST['option_draggable']);
		if(isset($_POST['option_scrollwheel']))			$googlemap->set_option('scrollwheel', 			$_POST['option_scrollwheel']);
		if(isset($_POST['option_styles']))				$googlemap->set_option('styles', 				$_POST['option_styles']);

		if(isset($_POST['option_textColor']))			$googlemap->set_option('textColor', 			$_POST['option_textColor']);
		if(isset($_POST['option_linkColor']))			$googlemap->set_option('linkColor', 			$_POST['option_linkColor']);

		if(isset($_POST['markers']))
		{
			$googlemap->clear_markers();

			if($_POST['markers'] != '')
			{
				$markers = explode("", rtrim($_POST['markers'], ""));

				foreach($markers as $marker_item)
				{
					$marker_data = explode("", $marker_item);
					$marker_config = array();
					if(isset($marker_data[0]))	$marker_config['lat'] 				= $marker_data[0];
					if(isset($marker_data[1]))	$marker_config['lng'] 				= $marker_data[1];
					if(isset($marker_data[2]))	$marker_config['name'] 				= $marker_data[2];
					if(isset($marker_data[3]))	$marker_config['description'] 		= $marker_data[3];
					if(isset($marker_data[4]))	$marker_config['icon']				= $marker_data[4];
					if(isset($marker_data[5]))	$marker_config['directions_link'] 	= $marker_data[5];
					if(isset($marker_data[6]))	$marker_config['directions_text'] 	= $marker_data[6];
					if(isset($marker_data[7]))	$marker_config['behaviour'] 		= $marker_data[7];
					if(isset($marker_data[8]))	$marker_config['url'] 				= $marker_data[8];

					$marker = new WiLD_GooglemapMarker($marker_config);
					$googlemap->add_marker($marker);
				}
			}
		}

		if($googlemap->save())
		{
			WiLD_Googlemap_redirect("admin.php?page=wild-googlemap&id={$googlemap->id}");
		}
	}

	# Icons
	$icons = WiLD_BackendGooglemapManager::getInstance()->get_icons();

	# Styles
	$styles = WiLD_BackendGooglemapManager::getInstance()->get_styles();
?>
<?php
	# Get admin header
	if(isset($_GET['noheader']) && $_GET['noheader'])
	{
		include (ABSPATH . 'wp-admin/admin-header.php');
	}
?>
<div class="wrap" id="wild_googlemap">
	<h2>Edit Googlemap "<?php echo $googlemap->name?>" (#<?php echo $googlemap->id?>)</h2>

	<?php WiLD_Googlemap_MessageManager::getInstance()->output()?>

	<form method="post" enctype="multipart/form-data" action="admin.php?page=wild-googlemap&id=<?php echo $googlemap->id?>&noheader=true">


		<h3>Googlemap data</h3>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">Map name</th>
					<td>
						<input type="text" name="name" maxlength="70" size="100" value="<?php echo WiLD_Googlemap_escape($googlemap->name)?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">New marker</th>
					<td id="new_marker">
						<input type="text" maxlength="70" size="75" data-default="Type an address..." />
						<input type="submit" value="Add a new marker" class="button action">
					</td>
				</tr>
			</tbody>
		</table>

		<h3>Map</h3>
		<table class="form-table">
			<tbody>
				<tr>
					<td>
						<div id="map" style="width:<?php echo $googlemap->get_css_width()?>; height:<?php echo $googlemap->get_css_height();?>;"></div>
						<input type="hidden" name="lat" value="<?php echo $googlemap->lat?>" />
						<input type="hidden" name="lng" value="<?php echo $googlemap->lng?>" />
						<input type="hidden" name="zoom" value="<?php echo $googlemap->zoom?>" />
						<input type="hidden" name="markers" value="" size="180" />
					</td>
				</tr>
			</tbody>
		</table>

		<h3>Advanced settings</h3>
		<table class="form-table" id="advanced_settings">
			<tbody>
				<tr>
					<th scope="row">Type</th>
					<td>
						<select name="option_mapTypeId">
							<option value="ROADMAP" 	<?php if($googlemap->get_option("mapTypeId") == "ROADMAP"):?>selected="selected"<?php endif;?>>Roadmap</option>
							<option value="SATELLITE" 	<?php if($googlemap->get_option("mapTypeId") == "SATELLITE"):?>selected="selected"<?php endif;?>>Satellite</option>
							<option value="HYBRID" 		<?php if($googlemap->get_option("mapTypeId") == "HYBRID"):?>selected="selected"<?php endif;?>>Hybrid</option>
							<option value="TERRAIN" 	<?php if($googlemap->get_option("mapTypeId") == "TERRAIN"):?>selected="selected"<?php endif;?>>Terrain</option>
						</select>
					</td>
					<td rowspan="13"><img src="<?php echo plugins_url("/img/wgm.png", __FILE__)?>" class="show_more screenshot" /></td>
				</tr>
				<tr>
					<th scope="row">Width x Height</th>
					<td>
						<input type="text" name="w" value="<?php echo WiLD_Googlemap_escape($googlemap->w)?>" maxlength="5" size="5" />
						x
						<input type="text" name="h" value="<?php echo WiLD_Googlemap_escape($googlemap->h)?>" maxlength="5" size="5" />
					</td>
				</tr>
				<tr class="show_more show_more_button">
					<th scope="row"><a onclick="javascript:jQuery('.show_more').toggle()">Show more options &raquo;</a></th>
					<td></td>
				</tr>
				<tr class="show_more">
					<th scope="row">Color style</th>
					<td>
						<select name="option_styles">
							<option value="">Default</option>
							<?php foreach($styles as $name => $code):?>
							<option value="<?php echo $code?>" <?php if($googlemap->get_option("styles") == $code):?>selected="selected"<?php endif;?>><?php echo $name?></option>
							<?php endforeach;?>
						</select>
					</td>
				</tr>
				<tr class="show_more">
					<th scope="row">Zoom controls <span>1</span></th>
					<td>
						<select name="option_zoomControl" class="select-small">
							<option value="1" <?php if($googlemap->get_option("zoomControl") == 1):?>selected="selected"<?php endif;?>>Yes</option>
							<option value="0" <?php if($googlemap->get_option("zoomControl") == 0):?>selected="selected"<?php endif;?>>No</option>
						</select>
					</td>
				</tr>
				<tr class="show_more">
					<th scope="row">Pan controls <span>2</span></th>
					<td>
						<select name="option_panControl" class="select-small">
							<option value="1" <?php if($googlemap->get_option("panControl") == 1):?>selected="selected"<?php endif;?>>Yes</option>
							<option value="0" <?php if($googlemap->get_option("panControl") == 0):?>selected="selected"<?php endif;?>>No</option>
						</select>
					</td>
				</tr>
				<tr class="show_more">
					<th scope="row">Scale controls <span>3</span></th>
					<td>
						<select name="option_scaleControl" class="select-small">
							<option value="1" <?php if($googlemap->get_option("scaleControl") == 1):?>selected="selected"<?php endif;?>>Yes</option>
							<option value="0" <?php if($googlemap->get_option("scaleControl") == 0):?>selected="selected"<?php endif;?>>No</option>
						</select>
					</td>
				</tr>
				<tr class="show_more">
					<th scope="row">Map type controls <span>4</span></th>
					<td>
						<select name="option_mapTypeControl" class="select-small">
							<option value="1" <?php if($googlemap->get_option("mapTypeControl") == 1):?>selected="selected"<?php endif;?>>Yes</option>
							<option value="0" <?php if($googlemap->get_option("mapTypeControl") == 0):?>selected="selected"<?php endif;?>>No</option>
						</select>
					</td>
				</tr>
				<tr class="show_more">
					<th scope="row">Streetview controls <span>5</span></th>
					<td>
						<select name="option_streetViewControl" class="select-small">
							<option value="1" <?php if($googlemap->get_option("streetViewControl") == 1):?>selected="selected"<?php endif;?>>Yes</option>
							<option value="0" <?php if($googlemap->get_option("streetViewControl") == 0):?>selected="selected"<?php endif;?>>No</option>
						</select>
					</td>
				</tr>
				<tr class="show_more">
					<th scope="row">Rotate streetview controls <span>2</span></th>
					<td>
						<select name="option_rotateControl" class="select-small">
							<option value="1" <?php if($googlemap->get_option("rotateControl") == 1):?>selected="selected"<?php endif;?>>Yes</option>
							<option value="0" <?php if($googlemap->get_option("rotateControl") == 0):?>selected="selected"<?php endif;?>>No</option>
						</select>
					</td>
				</tr>
				<tr class="show_more">
					<th scope="row">Overview controls <span>6</span></th>
					<td>
						<select name="option_overviewMapControl" class="select-small">
							<option value="1" <?php if($googlemap->get_option("overviewMapControl") == 1):?>selected="selected"<?php endif;?>>Yes</option>
							<option value="0" <?php if($googlemap->get_option("overviewMapControl") == 0):?>selected="selected"<?php endif;?>>No</option>
						</select>
					</td>
				</tr>
				<tr class="show_more">
					<th scope="row">Draggable</th>
					<td>
						<select name="option_draggable" class="select-small">
							<option value="1" <?php if($googlemap->get_option("draggable") == 1):?>selected="selected"<?php endif;?>>Yes</option>
							<option value="0" <?php if($googlemap->get_option("draggable") == 0):?>selected="selected"<?php endif;?>>No</option>
						</select>
					</td>
				</tr>
				<tr class="show_more">
					<th scope="row">Scrollwheel</th>
					<td>
						<select name="option_scrollwheel" class="select-small">
							<option value="1" <?php if($googlemap->get_option("scrollwheel") == 1):?>selected="selected"<?php endif;?>>Yes</option>
							<option value="0" <?php if($googlemap->get_option("scrollwheel") == 0):?>selected="selected"<?php endif;?>>No</option>
						</select>
					</td>
				</tr>
				<tr class="show_more">
					<th scope="row">Text Color<br /><small>Leave empty to use the theme's color</small></th>
					<td>
						<input type="text" name="option_textColor" value="<?php echo $googlemap->get_option("textColor")?>" size="7" maxlength="7" />

						<script type="text/javascript">
							jQuery(document).ready(function()
							{
								jQuery('#wild_googlemap input[name="option_textColor"]').ColorPicker(
								{
									"color" : "<?php echo $googlemap->get_option("textColor")?>",
									"onChange" : function(hsb, hex, rgb) {
										jQuery('#wild_googlemap input[name="option_textColor"]').val("#" + hex);
									}
								});
							});
						</script>
					</td>
				</tr>
				<tr class="show_more">
					<th scope="row">Links Color<br /><small>Leave empty to use the theme's color</small></th>
					<td>
						<input type="text" name="option_linkColor" value="<?php echo $googlemap->get_option("linkColor")?>" size="7" maxlength="7" />
						<script type="text/javascript">
							jQuery(document).ready(function()
							{
								jQuery('#wild_googlemap input[name="option_linkColor"]').ColorPicker(
								{
									"color" : "<?php echo $googlemap->get_option("linkColor")?>",
									"onChange" : function(hsb, hex, rgb) {
										jQuery('#wild_googlemap input[name="option_linkColor"]').val("#" + hex);
									}
								});
							});
						</script>
					</td>
				</tr>
			</tbody>
		</table>

		<br />

		<input type="hidden" name="save" value="<?php echo $googlemap->id?>" />
		<input class="button-primary" type="submit" value="<?php _e("Save"); ?>" />

	</form>

</div>


<script type="text/javascript">

function WiLD_BK_Map(_code)
{
	this.code 			= _code;
	this.lat			= <?php echo WiLD_GOOGLEMAP_LAT ?>;
	this.lng			= <?php echo WiLD_GOOGLEMAP_LNG ?>;
	this.zoom			= <?php echo WiLD_GOOGLEMAP_ZOOM ?>;
	this.mapTypeId 		= "ROADMAP";
	this.markers		= [];
	this.markers_count 	= 0;
	this.styles			= null;

	this.init = function()
	{
		// Initialize map values
		this.lat 		= parseFloat(jQuery("input[name=lat]").val());
		this.lng 		= parseFloat(jQuery("input[name=lng]").val());
		this.zoom		= parseInt(jQuery("input[name=zoom]").val());
		this.mapTypeId 	= jQuery("select[name=option_mapTypeId]").val();
		this.styles 	= jQuery("select[name=option_styles]").val();
	}

	this.update = function()
	{
		// Update map values
		this.lat 		= WGM.get_map(this.code).get_position().lat();
		this.lng 		= WGM.get_map(this.code).get_position().lng();
		this.zoom 		= WGM.get_map(this.code).get_zoom();
		this.mapTypeId 	= jQuery("select[name=option_mapTypeId]").val();
		this.styles 	= jQuery("select[name=option_styles]").val();

		// Update map input
		jQuery("input[name=lat]").val(this.lat);
		jQuery("input[name=lng]").val(this.lng);
		jQuery("input[name=zoom]").val(this.zoom);

		// Update map markers
		jQuery("input[name=markers]").val("");
		for(var marker_code in this.markers)
		{
			this.markers[marker_code].update();
		}
	}

	this.render = function()
	{
		// Draw map
		var map_config = [];
		map_config['lat'] 				= this.lat;
		map_config['lng'] 				= this.lng;
		map_config['zoom'] 				= this.zoom;
		map_config['mapTypeId'] 		= this.mapTypeId;
		map_config['draggable'] 		= true;
		map_config['scrollwheel']	 	= false;
		map_config['streetViewControl'] = false;
		map_config['styles']			= this.styles;
		WGM.add_map(this.code, map_config);

		// Draw markers
		for (var marker_code in this.markers)
		{
			this.markers[marker_code].render();
		}
	}

	this.detach = function()
	{
		// Remove map
		this.update();
		jQuery("#" + this.code).html("");
	}

	this.init_markers = function()
	{
		<?php foreach($googlemap->get_markers() as $marker):?>
		var marker_config = [];
		marker_config['title'] 				= "<?php echo str_replace('"', '\"', $marker->name)?>";
		marker_config['lat'] 				= "<?php echo str_replace('"', '\"', $marker->lat)?>";
		marker_config['lng'] 				= "<?php echo str_replace('"', '\"', $marker->lng)?>";
		marker_config['description'] 		= "<?php echo str_replace('"', '\"', $marker->description)?>";
		marker_config['icon'] 				= "<?php echo str_replace('"', '\"', $marker->icon)?>";
		marker_config['directions_link'] 	= "<?php echo str_replace('"', '\"', $marker->directions_link)?>";
		marker_config['directions_text'] 	= "<?php echo str_replace('"', '\"', $marker->directions_text)?>";
		marker_config['behaviour']			= "<?php echo str_replace('"', '\"', $marker->behaviour)?>";
		marker_config['url']				= "<?php echo str_replace('"', '\"', $marker->url)?>";
		this.add_marker(marker_config);
		<?php endforeach;?>
	}

	// SET
	this.set_width = function(_width)
	{
		var width = 0;
		if(_width.match(/[0-9]\%/g))	width = _width;
		else if(_width.match(/[0-9]/g)) width = _width + "px";
		else
		{
			_width	= "<?php echo WiLD_GOOGLEMAP_WIDTH?>";
			width 	= "<?php echo WiLD_GOOGLEMAP_WIDTH?>px";
		}
		jQuery("#" + this.code).css("width", width);
		return _width;
	}

	this.set_height = function(_height)
	{
		var height = 0;
		if(_height.match(/[0-9]\%/g))
		{
			height = _height;
			jQuery("#" + this.code).css("height", "400px");
		}
		else if(_height.match(/[0-9]/g))
		{
			height = _height + "px";
			jQuery("#" + this.code).css("height", height);
		}
		else
		{
			_height = "<?php echo WiLD_GOOGLEMAP_WIDTH?>";
			height 	= "<?php echo WiLD_GOOGLEMAP_WIDTH?>px";
			jQuery("#" + this.code).css("height", height);
		}
		return _height;
	}

	this.add_marker = function(_vars)
	{
		var marker_code = "marker_" + this.markers_count++;
		this.markers[marker_code] = new WiLD_BK_Marker(this.code, marker_code);

		if(_vars == undefined) _vars = [];
		if(_vars['title'] != undefined)				this.markers[marker_code].title 			= _vars['title'];
		if(_vars['description'] != undefined)		this.markers[marker_code].description 		= _vars['description'];
		if(_vars['lat'] != undefined)				this.markers[marker_code].lat 				= _vars['lat'];
		if(_vars['lng'] != undefined)				this.markers[marker_code].lng 				= _vars['lng'];
		if(_vars['icon'] != undefined)				this.markers[marker_code].icon 				= _vars['icon'];
		if(_vars['directions_link'] != undefined)	this.markers[marker_code].directions_link 	= _vars['directions_link'];
		if(_vars['directions_text'] != undefined)	this.markers[marker_code].directions_text 	= _vars['directions_text'];
		if(_vars['behaviour'] != undefined)			this.markers[marker_code].behaviour 		= _vars['behaviour'];
		if(_vars['url'] != undefined)				this.markers[marker_code].url 				= _vars['url'];

		this.markers[marker_code].render();
		this.markers[marker_code].init();

		return marker_code;
	}

	this.delete_marker = function(_code)
	{
		for (var key in this.markers)
		{
			if (key == _code)
			{
				this.markers.splice(key, 1);
				delete this.markers[key];
			}
		}
	}

	// GET
	this.get_lat = function()
	{
		return this.lat;
	}

	this.get_lng = function()
	{
		return this.lng;
	}

	this.get_marker = function(_code)
	{
		return this.markers[_code];
	}
}

function WiLD_BK_Marker(_map, _code)
{
	this.map				= _map;
	this.code				= _code;
	this.lat 				= 0;
	this.lng 				= 0;
	this.icon 				= "";
	this.title				= "";
	this.description 		= "";
	this.directions_link 	= 0;
	this.directions_text 	= "Get directions";
	this.behaviour			= 1;
	this.url				= "http://";

	this.init = function()
	{
	}

	this.update = function()
	{
		// Update marker values
		this.lat = WGM.get_map(this.map).get_marker(this.code).get_position().lat();
		this.lng = WGM.get_map(this.map).get_marker(this.code).get_position().lng();

		// Update marker input
		var sep = "";
		var end = "";
		var data = this.lat + sep + this.lng + sep + this.title + sep + this.description + sep + this.icon + sep + this.directions_link + sep + this.directions_text + sep + this.behaviour + sep + this.url + end;
		jQuery("input[name=markers]").val(jQuery("input[name=markers]").val() + data);
	}

	this.render = function()
	{
		var marker_config = [];
		marker_config['lat'] 				= this.lat;
		marker_config['lng'] 				= this.lng;
		marker_config['icon'] 				= this.get_icon_url();
		marker_config['draggable'] 			= true;
		marker_config['panOnDrag'] 			= false;
		marker_config['directions_link'] 	= this.directions_link;
		marker_config['directions_text'] 	= this.directions_text;
		marker_config['behaviour'] 			= this.behaviour;
		marker_config['url'] 				= this.url;

		WGM.add_marker(this.map, this.code, marker_config);
		this.set_icon_url(this.get_icon_url());

		this.init_infowindow();
	}

	this.init_infowindow = function(_what)
	{
		var html = '';
		html += '<div class="wgm_infowindow" id="infowindow_' + this.code + '" data-map="' + this.map + '" data-marker="' + this.code + '">';
			switch(_what)
			{
				case "preview":
				default:
					// Preview with commands
					html += '<div class="preview">';
						html += '<div class="title">' + this.title + '</div>';
						if(this.description != '')
						{
							html += '<div class="description">' + this.description.replace(/\n/g, '\<br \/\>') + '</div>';
						}
						if(this.directions_link == 1)
						{
							html += '<div class="directions"><a href="javascript:alert(\'This link will open a Googlemap page targetting this location\')">' + this.directions_text + '</a></div>';
						}
						html += '<div class="commands">';
							html += '<a href="javascript:void(0)" class="command_data">Edit</a> | ';
							html += '<a href="javascript:void(0)" class="command_settings">Settings</a> | ';
							html += '<a href="javascript:void(0)" class="command_delete">Delete</a> | ';
							html += '<a href="javascript:void(0)" class="command_center">Center</a></div>';
					html += '</div>';
				break;

				case "data":
					// Edit data
					html += '<div class="data">';
						html += '<p>Tooltip contents & icon</p>';
						html += '<input type="text" name="' + this.code + '_title" value="' + this.title + '" />';

						html += '<div class="icon_preview">';
							html += '<img src="' + this.get_icon_url() + '" />';
						html += '</div>';
						html += '<textarea name="' + this.code + '_description">' + this.description.replace("<br \/>", "\n") + '</textarea>';

						html += '<input type="submit" value="Apply" class="button action" />';
					html += '</div>';
					break;

				case "settings":
					// Edit settings
					html += '<div class="settings">';

						html += '<p>Behaviour</p>';
						html += '<select name="' + this.code + '_behaviour" onchange="javascript:if(jQuery(this).val() == 3) jQuery(\'input[name=' + this.code + '_url]\').removeAttr(\'disabled\'); else jQuery(\'input[name=' + this.code + '_url]\').attr(\'disabled\',\'disabled\');">';
							html += '<option value="0" ' + (this.behaviour == 0 ? 'selected="selected"' : '') + '>Never show this  bubble window</option>';
							html += '<option value="1" ' + (this.behaviour == 1 ? 'selected="selected"' : '') + '>Show this bubble window when clicked</option>';
							html += '<option value="2" ' + (this.behaviour == 2 ? 'selected="selected"' : '') + '>Show this bubble window when map loads</option>';
							html += '<option value="3" ' + (this.behaviour == 3 ? 'selected="selected"' : '') + '>Open a new page when clicked</option>';
						html += '</select>';
						html += '<input type="text" name="' + this.code + '_url" value="' + this.url + '" ' + (this.behaviour != 3 ? 'disabled="disabled"' : '') + ' />';



						html += '<p>Directions link</p>';
						html += '<select name="' + this.code + '_directions_link" onchange="javascript:if(jQuery(this).val() == 0) jQuery(\'input[name=' + this.code + '_directions_text]\').attr(\'disabled\',\'disabled\'); else jQuery(\'input[name=' + this.code + '_directions_text]\').removeAttr(\'disabled\');">';
							html += '<option value="1" ' + (this.directions_link == 1 ? 'selected="selected"' : '') + '>Enable directions link</option>';
							html += '<option value="0" ' + (this.directions_link == 0 ? 'selected="selected"' : '') + '>Disable directions link</option>';
						html += '</select>';
						html += '<input type="text" name="' + this.code + '_directions_text" value="' + this.directions_text + '" ' + (this.directions_link == 0 ? 'disabled="disabled"' : '') + ' />';

						html += '<input type="submit" value="Apply" class="button action" />';

				html += '</div>';
				break;

			case "icons":
				// Edit icon
				html += '<div class="icons">';
					<?php foreach($icons as $icon):?>
					html += '<div ' + ((this.icon == '<?php echo $icon?>' || (this.icon == '' && '<?php echo $icon?>' == 'default.png')) ? 'class="active"' : '') + ' data-icon="<?php echo $icon == "default.red.png" ? "" : $icon?>">';
						html += '<img src="<?php echo plugins_url('/icons/' . $icon, __FILE__)?>" />';
					html += '</div>';
					<?php endforeach;?>
					html += '<input type="submit" value="Use this icon" class="button action" />';
				html += '</div>';
				break;
			}

		html += '</div>';

		infowindow_config = [];
		infowindow_config['content'] 	= html;
		infowindow_config['zoom'] 		= false;
		WGM.add_infowindow(this.map, this.code, infowindow_config);

		// Infowindow close -> preview
		var mapcode 	= this.map;
		var markercode 	= this.code;
		google.maps.event.addListener(WGM.get_map(mapcode).get_infowindow(markercode).infowindow, "closeclick", function(event)
		{
			WBKMM.map.get_marker(markercode).show_infowindow("preview");
			WBKMM.map.get_marker(markercode).close_infowindow();
		});
	}

	this.update_infowindow = function(_what)
	{
 		var map_code 		= this.map;
		var marker_code 	= this.code;

		if(_what == "data")
		{
			this.title				= jQuery("#infowindow_" + this.code + " input[name=" + this.code + "_title]").val();
			this.description		= jQuery("#infowindow_" + this.code + " textarea[name=" + this.code + "_description]").val();

			// Update the marker data
			WBKMM.map.get_marker(marker_code).title 			= this.title;
			WBKMM.map.get_marker(marker_code).description 		= this.description;
		}
		else if(_what == "settings")
		{
			this.directions_link 	= jQuery("#infowindow_" + this.code + " select[name=" + this.code + "_directions_link]").val();
			this.directions_text 	= jQuery("#infowindow_" + this.code + " input[name=" + this.code + "_directions_text]").val();
			if(this.directions_text == "") this.directions_text = "Get directions";
			this.behaviour	 		= jQuery("#infowindow_" + this.code + " select[name=" + this.code + "_behaviour]").val();
			this.url 				= jQuery("#infowindow_" + this.code + " input[name=" + this.code + "_url]").val();
			if(this.url == "") this.url = "http://";

			// Update infowindow settings
			WBKMM.map.get_marker(marker_code).directions_link 	= this.directions_link;
			WBKMM.map.get_marker(marker_code).directions_text 	= this.directions_text;
			WBKMM.map.get_marker(marker_code).behaviour 		= this.behaviour;
			WBKMM.map.get_marker(marker_code).url 				= this.url;
		}
		else if(_what == "icon")
		{
			// Update the icon value
			this.icon = jQuery("#infowindow_" + this.code + " .icons div.active").eq(0).attr("data-icon");
			WBKMM.map.get_marker(marker_code).icon = this.icon;

			// Update the marker
			WBKMM.map.get_marker(marker_code).set_icon_url(this.get_icon_url());
		}
	}

	this.show_infowindow = function(_what)
	{
		this.init_infowindow(_what);
		WGM.get_map(this.map).show_infowindow(this.code, false);
	}

	this.close_infowindow = function()
	{
 		var map_code 		= this.map;
		var marker_code 	= this.code;

		// Close infowindow
		WGM.get_map(map_code).close_infowindows();
	}

	// Set marker data
	this.set_address = function(_address)
	{
		var config = [];
		config['panTo'] = true;

		WGM.get_map(this.map).get_marker(this.code).set_address(_address, config);
	}

	this.set_position = function(_position)
	{
		var config = [];
		config['panTo'] = true;

		WGM.get_map(this.map).get_marker(this.code).set_position(_position, config);
	}

	this.set_icon_url = function(_icon)
	{
		WGM.get_map(this.map).get_marker(this.code).set_icon("");
		WGM.get_map(this.map).get_marker(this.code).set_shadow("");

		// Add icons and shadows only when loaded
		var map_code 		= this.map;
		var marker_code 	= this.code;

		var icon 	= new Image();
		icon.onload = function()
		{
			WGM.get_map(map_code).get_marker(marker_code).set_icon(this.src);
		}
		icon.src 	= _icon; // Must be after .onload otherwise if cached some browser won't fire onload

		var shadow 	= new Image();
		shadow.onload = function()
		{
			WGM.get_map(map_code).get_marker(marker_code).set_shadow(this.src);
		}
		shadow.src 	= this.get_shadow_url(); // Must be after .onload otherwise if cached some browser won't fire onload
	}

	// Get marker data
	this.get_code = function()
	{
		return this.code;
	}

	this.get_icon_url = function()
	{
		if(this.icon == "")
			return '<?php echo plugins_url('/icons/default.png', __FILE__)?>';	// Default icon
		else
			return '<?php echo plugins_url('/icons/', __FILE__)?>' + this.icon;
	}

	this.get_shadow_url = function()
	{
		return this.get_icon_url().substr(0, this.get_icon_url().lastIndexOf('.')) + ".shadow." + this.get_icon_url().substr(this.get_icon_url().lastIndexOf('.') + 1);
	}
}

function WiLD_BK_MapManager()
{
	this.init = function()
	{
		// Initialize map
		this.map = new WiLD_BK_Map("map");
		this.map.init();
		this.map.render();

		// Initialize markers
		this.map.init_markers();
	}
}

// Init
var WBKMM = new WiLD_BK_MapManager();

jQuery(document).ready(function()
{
	WBKMM.init();
});

// Input width listener
jQuery("input[name=w]").blur(function()
{
	WBKMM.map.detach();
	jQuery(this).val(WBKMM.map.set_width(jQuery("input[name=w]").val()));
	WBKMM.map.render();
});

// Input height listener
jQuery("input[name=h]").blur(function()
{
	WBKMM.map.detach();
	jQuery(this).val(WBKMM.map.set_height(jQuery("input[name=h]").val()));
	WBKMM.map.render();
});

// Map settings listeners
jQuery("#map").on("mouseout", function()
{
	WBKMM.map.update();
});

// Map type listener
jQuery("select[name=option_mapTypeId]").change(function()
{
	WBKMM.map.detach();
	WBKMM.map.render();
});

// Map styles listener
jQuery("select[name=option_styles]").change(function()
{
	WBKMM.map.detach();
	WBKMM.map.render();
});

// Add marker listener
jQuery("#new_marker input[type=submit]").on("click", function(e)
{
	// Input
	e.preventDefault();
	var query = jQuery("#new_marker input[type=text]").val();

	// Input check
	if(query == "" || query == jQuery("#new_marker input[type=text]").attr("data-default"))
	{
		return;
	}

	// New marker
	if(match = query.match(/^(([0-9]+)(\.[0-9]+)?);(([0-9]+)(\.[0-9]+)?)$/))
	{
		// By coordinates
		var position = new google.maps.LatLng(parseFloat(match[1]), parseFloat(match[4]));
		var marker_code = WBKMM.map.add_marker({"title" : query.charAt(0).toUpperCase() + query.slice(1), "description" : ""});
		WBKMM.map.get_marker(marker_code).set_position(position);
	}
	else
	{
		// By address
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({'address': query, 'region': ''}, function(results, status)
		{
			if (status == google.maps.GeocoderStatus.OK)
			{
				lat = results[0].geometry.location.lat();
				lng = results[0].geometry.location.lng();

				var position = new google.maps.LatLng(lat, lng);
				var marker_code = WBKMM.map.add_marker({"title" : query.charAt(0).toUpperCase() + query.slice(1), "description" : ""});
				WBKMM.map.get_marker(marker_code).set_position(position);
			}
			else
			{
				alert("Address not found");
			}
		});
	}
});

// Infowindow tooltip commands: preview -> edit
jQuery("body").on("click", ".wgm_infowindow .command_data", function(e)
{
	var id			= jQuery(this).parents('.wgm_infowindow').attr("id");
	var map_code	= jQuery("#" + id).attr("data-map");
	var marker_code = jQuery("#" + id).attr("data-marker");

	// Go to form
	WBKMM.map.get_marker(marker_code).close_infowindow();		// Close and rewrite infowindow html
	WBKMM.map.get_marker(marker_code).show_infowindow("data");	// Show infowindow
});

// Infowindow tooltip commands: preview -> settings
jQuery("body").on("click", ".wgm_infowindow .command_settings", function(e)
{
	var id			= jQuery(this).parents('.wgm_infowindow').attr("id");
	var map_code	= jQuery("#" + id).attr("data-map");
	var marker_code = jQuery("#" + id).attr("data-marker");

	// Go to form
	WBKMM.map.get_marker(marker_code).close_infowindow();			// Close and rewrite infowindow html
	WBKMM.map.get_marker(marker_code).show_infowindow("settings");	// Show infowindow
});


// Infowindow tooltip commands: preview -> delete
jQuery("body").on("click", ".wgm_infowindow .command_delete", function(e)
{
	var id			= jQuery(this).parents('.wgm_infowindow').attr("id");
	var map_code	= jQuery("#" + id).attr("data-map");
	var marker_code = jQuery("#" + id).attr("data-marker");

	// Delete marker
	WBKMM.map.delete_marker(marker_code);
	WBKMM.map.detach();
	WBKMM.map.render();
});

// Infowindow tooltip commands: preview -> center
jQuery("body").on("click", ".wgm_infowindow .command_center", function(e)
{
	var id			= jQuery(this).parents('.wgm_infowindow').attr("id");
	var map_code	= jQuery("#" + id).attr("data-map");
	var marker_code = jQuery("#" + id).attr("data-marker");

	// Exit
	WGM.get_map(map_code).center_marker(marker_code);
});

// Infowindow tooltip commands: data -> icon
jQuery('body').on("click", '.wgm_infowindow .data .icon_preview', function(e)
{
	e.preventDefault();
	var id			= jQuery(this).parents('.wgm_infowindow').attr("id");
	var map_code	= jQuery("#" + id).attr("data-map");
	var marker_code = jQuery("#" + id).attr("data-marker");

	WBKMM.map.get_marker(marker_code).update_infowindow("data");

	// Go to icons
	WBKMM.map.get_marker(marker_code).close_infowindow();				// Close and rewrite infowindow html
	WBKMM.map.get_marker(marker_code).show_infowindow("icons");			// Show infowindow
});

// Infowindow tooltip commands: icon update
jQuery('body').on("click", '.wgm_infowindow .icons div', function(e)
{
	var id			= jQuery(this).parents('.wgm_infowindow').attr("id");
	var map_code	= jQuery("#" + id).attr("data-map");
	var marker_code = jQuery("#" + id).attr("data-marker");

	// Select current
	jQuery("#infowindow_" + marker_code + " .icons div").removeClass("active");
	jQuery(this).addClass("active");

	// Update
	WBKMM.map.get_marker(marker_code).update_infowindow("icon");
});

// Infowindow tooltip commands: icon -> data
jQuery('body').on("click", '.wgm_infowindow .icons input[type=submit]', function(e)
{
	e.preventDefault();
	var id			= jQuery(this).parents('.wgm_infowindow').attr("id");
	var map_code	= jQuery("#" + id).attr("data-map");
	var marker_code = jQuery("#" + id).attr("data-marker");

	// Go back to form
	WBKMM.map.get_marker(marker_code).close_infowindow();			// Close and rewrite infowindow html
	WBKMM.map.get_marker(marker_code).show_infowindow("data");		// Show infowindow
});

// Infowindow tooltip commands: data -> preview
jQuery('body').on("click", '.wgm_infowindow .data input[type=submit]', function(e)
{
	e.preventDefault();
	var id			= jQuery(this).parents('.wgm_infowindow').attr("id");
	var map_code	= jQuery("#" + id).attr("data-map");
	var marker_code = jQuery("#" + id).attr("data-marker");

	// Exit
	WBKMM.map.get_marker(marker_code).update_infowindow("data");		// Update data
	WBKMM.map.get_marker(marker_code).close_infowindow();				// Close and rewrite infowindow html
	WBKMM.map.get_marker(marker_code).show_infowindow("preview");		// Show infowindow
});


// Infowindow tooltip commands: settings -> preview
jQuery('body').on("click", '.wgm_infowindow .settings input[type=submit]', function(e)
{
	e.preventDefault();
	var id			= jQuery(this).parents('.wgm_infowindow').attr("id");
	var map_code	= jQuery("#" + id).attr("data-map");
	var marker_code = jQuery("#" + id).attr("data-marker");

	// Exit
	WBKMM.map.get_marker(marker_code).update_infowindow("settings");	// Update data
	WBKMM.map.get_marker(marker_code).close_infowindow();				// Close and rewrite infowindow html
	WBKMM.map.get_marker(marker_code).show_infowindow("preview");		// Show infowindow
});

// Update map on save
jQuery("#wild_googlemap form").on("submit", function(e)
{
	WBKMM.map.update();
});
</script>