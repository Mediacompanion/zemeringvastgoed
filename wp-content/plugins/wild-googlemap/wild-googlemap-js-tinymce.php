<?php
	// Db
	require_once("../../../wp-config.php");
	global $wpdb;

	// Requirements
	require_once(realpath(dirname(__FILE__)) . "/config.php");
	require_once(realpath(dirname(__FILE__)) . "/class-googlemap.php");
	require_once(realpath(dirname(__FILE__)) . "/class-backend-googlemap-manager.php");

	// Header
	header("Content-type: application/x-javascript");
?>
(function() {
	tinymce.create('tinymce.plugins.wild_googlemap',
	{
		init : function(ed, url) {
			ed.addButton('wild_googlemap', {
				title : 'Googlemap',
				image : url+'/img/icon.png',
				onclick : function()
				{
					tb_show( 'Googlemap', '#TB_inline?inlineId=wild-googlemap-form' );
				}
			});
		},
		createControl : function(n, cm)
		{
			return null;
		},
		getInfo : function()
		{
			return {
				longname 	: "Googlemap",
				author 		: 'Studio WiLD',
				authorurl 	: 'http://themeforest.net/user/Studio_WiLD',
				infourl 	: 'http://themeforest.net/user/Studio_WiLD',
				version		: "1.0"
			};
		}
	});

	tinymce.PluginManager.add('wild_googlemap', tinymce.plugins.wild_googlemap);

	jQuery(function()
	{
		var form = jQuery('<div id="wild-googlemap-form"><table class="form-table">\
			<tr>\
				<td colspan="2"><b>Select the Googlemap you wish to add</b></td>\
			</tr>\
			<tr>\
				<th scope="row" style="width:150px"><label for="wild_googlemap_id">Googlemap</label></th>\
				<td><select name="wild_googlemap_id" style="width:25em"><?php echo str_replace("'", "\'", WiLD_BackendGooglemapManager::getInstance()->get_googlemap_select())?></select></td>\
			<tr>\
				<td colspan="2"><b>Advanced settings</b></td>\
			</tr>\
			<tr>\
				<th scope="row" style="width:150px"><label for="wild_googlemap_div">Div id</label></th>\
				<td><input type="text" name="wild_googlemap_div" value="" style="width:25em" /><br /><small>The Id of the div you wish to use to display this map.<br />Use this parameter if your map is not part of the content. Leave empty if unsure.</small>\</td>\
			</tr>\
			<tr>\
				<th scope="row" style="width:150px"><label for="wild_googlemap_class">Class</label></th>\
				<td><input type="text" name="wild_googlemap_class" value="" style="width:25em" /><br /><small>Class names you want to add to the div containing the map. Leave empty if unsure.</small>\</td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="wild-googlemap-submit" class="button-primary" value="Add Googlemap" name="submit" />\
		</p>\
		</div>');

		var table = form.find('table');
		form.appendTo('body').hide();

		form.find('#wild-googlemap-submit').click(function()
		{
			var shortcode = '[<?php echo WiLD_GOOGLEMAP_SHORTCODE?> id="' + jQuery("select[name=wild_googlemap_id]").val() + '"';

			if(jQuery("input[name=wild_googlemap_div]").val() != "")
			{
				shortcode += ' div="' + jQuery("input[name=wild_googlemap_div]").val() + '"';
			}
			else if(jQuery("input[name=wild_googlemap_class]").val() != "")
			{
				shortcode += ' class="' + jQuery("input[name=wild_googlemap_class]").val() + '"';
			}

			shortcode += ']';

			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			tb_remove();
		});
	});
})();