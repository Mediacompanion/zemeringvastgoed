/*
	WiLD Google Maps
	http://codecanyon.net/item/wild-google-maps/4045150
*/

jQuery(document).ready(function()
{
	jQuery(".gmap_call").each(function()
	{
		// Map
		var id = jQuery(this).attr("data-id");

		// Force class gmap to custom divs, to fix css conflicts issues
		jQuery("#" + id).addClass("gmap");

		// Class
		if(jQuery(this).attr("data-class") != "" && jQuery(this).attr("data-class") != undefined)
		{
			jQuery("#" + id).addClass(jQuery(this).attr("data-class"));
		}

		var map_config = new Array();
		map_config["lat"] 					= parseFloat(jQuery(this).attr("data-lat"));
		map_config["lng"]					= parseFloat(jQuery(this).attr("data-lng"));
		map_config["zoom"]					= parseInt(jQuery(this).attr("data-zoom"));
		map_config["mapTypeId"]				= jQuery(this).attr("data-mapTypeId");
		map_config["mapTypeControl"]		= jQuery(this).attr("data-mapTypeControl") == "true";
		map_config["streetViewControl"]		= jQuery(this).attr("data-streetViewControl") == "true";
		map_config["zoomControl"]			= jQuery(this).attr("data-zoomControl") == "true";
		map_config["panControl"]			= jQuery(this).attr("data-panControl") == "true";
		map_config["scaleControl"]			= jQuery(this).attr("data-scaleControl") == "true";
		map_config["overviewMapControl"] 	= jQuery(this).attr("data-overviewMapControl") == "true";
		map_config["draggable"]				= jQuery(this).attr("data-draggable") == "true";
		map_config["scrollwheel"]			= jQuery(this).attr("data-scrollwheel") == "true";
		map_config["styles"]				= jQuery(this).attr("data-styles");

		WGM.add_map(jQuery(this).attr("data-id"), map_config);

		// Markers
		var nMarkers = parseInt(jQuery(this).attr("data-markers"));
		for(var i = 0; i < nMarkers; i++)
		{
			var marker_id  = id + '_marker_' + i;

			var marker_config = new Array();
			marker_config["lat"] 	= parseFloat(jQuery(this).attr("data-marker" + i + "-lat"));
			marker_config["lng"] 	= parseFloat(jQuery(this).attr("data-marker" + i + "-lng"));
			marker_config["title"] 	= jQuery(this).attr("data-marker" + i + "-title");

			if(jQuery(this).attr("data-marker" + i + "-icon") != "")
			{
				marker_config["icon"] = jQuery(this).attr("data-marker" + i + "-icon");
				if(jQuery(this).attr("data-marker" + i + "-shadow") != "")
				{
					marker_config["shadow"] = jQuery(this).attr("data-marker" + i + "-shadow");
				}
			}

			WGM.add_marker(id, marker_id, marker_config);

			// Tooltip
			var behaviour = jQuery(this).attr("data-marker" + i + "-behaviour");
			if(behaviour == 1 || behaviour == 2)
			{
				var infowindow_config = new Array();
				infowindow_config["content"] = jQuery(this).attr("data-marker" + i + "-content");
				infowindow_config["disableAutoPan"] = behaviour == 2 ? true : false;
				WGM.add_infowindow(id, marker_id, infowindow_config);

				if(behaviour == 2)
				{
					WGM.get_map(id).get_infowindow(marker_id).open();
				}
			}
			else if(behaviour == 3)
			{
				WGM.get_map(id).get_marker(marker_id).set_link(jQuery(this).attr("data-marker" + i + "-link"));
			}
		}
	});
});