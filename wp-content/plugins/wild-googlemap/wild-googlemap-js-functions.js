// Input placeholder
jQuery(document).ready(function()
{
	jQuery("#wild_googlemap input[data-default], #wild_googlemap textarea[data-default]").each(function()
	{
		if(jQuery(this).val() == "")
		{
			jQuery(this).val(jQuery(this).attr("data-default"));
		}
	});

	jQuery("#wild_googlemap input[data-default], #wild_googlemap textarea[data-default]").focus(function()
	{
		if(jQuery(this).val() == jQuery(this).attr("data-default"))
		{
			jQuery(this).val("");
		}
	});

	jQuery("#wild_googlemap input[data-default], #wild_googlemap textarea[data-default]").blur(function()
	{
		if(jQuery(this).val() == "")
		{
			jQuery(this).val(jQuery(this).attr("data-default"));
		}
	});
});

// List stuff
function wild_googlemap_table_form_actions()
{
	if(jQuery('select[name=action]', '#form_table_actions').val() == "") return false;
	if(jQuery('input[name="id[]"]:checked', '#form_table_actions').length == 0) return false;
	if(jQuery('select[name=action]', '#form_table_actions').find(":selected").attr('data-check') != undefined)
		return (confirm(jQuery('select[name=action]', '#form_table_actions').find(":selected").attr('data-check')));
	else
		return true;
}