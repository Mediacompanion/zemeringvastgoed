/*
	WiLD Google Maps v.1.9.1
	http://codecanyon.net/item/wild-google-maps/4045150
*/
function WiLD_GooglemapManager()
{
	this.maps = [];

	// Add map
	this.add_map = function(_mapcode, _config)
	{
		// If the div doesn't exists, do nothing
		if(jQuery("#" + _mapcode).length == 0) return;

		// New map
		this.maps[_mapcode] = new WiLD_Googlemap(_mapcode, _config);
		this.maps[_mapcode].init();
	}

	// Add marker to map
	this.add_marker = function(_mapcode, _markercode, _config)
	{
		// New marker
		this.get_map(_mapcode).add_marker(_markercode, _config);
	}

	// Add infowindow to map marker
	this.add_infowindow = function(_mapcode, _markercode, _config)
	{
		// New infowindow
		this.get_map(_mapcode).add_infowindow(_markercode, _config);
	}

	// Get map
	this.get_map = function(_mapcode)
	{
		return this.maps[_mapcode];
	}
}

var WGM = new WiLD_GooglemapManager();


/*** WiLD_Googlemap ***/
function WiLD_Googlemap(_mapcode, _config)
{
	// Initialize map
	this.mapcode 		= _mapcode;
	this.markers 		= [];
	this.infowindows 	= [];

	if(_config == undefined) _config = [];
	this.lat		= _config['lat'] 	!= undefined ? _config['lat'] 	: 45.433628;
	this.lng		= _config['lng'] 	!= undefined ? _config['lng'] 	: 12.342002;
	this.zoom		= _config['zoom'] 	!= undefined ? _config['zoom'] 	: 6;

	this.mapTypeId 	= google.maps.MapTypeId.ROADMAP;
	if(_config['mapTypeId'] != undefined)
	{
		if(_config['mapTypeId'] == "SATELLITE") 	this.mapTypeId = google.maps.MapTypeId.SATELLITE;
		else if(_config['mapTypeId'] == "HYBRID") 	this.mapTypeId = google.maps.MapTypeId.HYBRID;
		else if(_config['mapTypeId'] == "TERRAIN") 	this.mapTypeId = google.maps.MapTypeId.TERRAIN;
	}

	this.mapTypeControl			= _config['mapTypeControl'] 		!= undefined ? _config['mapTypeControl'] 		: true;
	this.streetViewControl		= _config['streetViewControl'] 		!= undefined ? _config['streetViewControl'] 	: true;
	this.zoomControl			= _config['zoomControl'] 			!= undefined ? _config['zoomControl'] 			: true;
	this.panControl				= _config['panControl'] 			!= undefined ? _config['panControl'] 			: true;
	this.scaleControl			= _config['scaleControl'] 			!= undefined ? _config['scaleControl'] 			: false;
	this.rotateControl			= _config['rotateControl'] 			!= undefined ? _config['rotateControl'] 		: false;
	this.overviewMapControl		= _config['overviewMapControl']		!= undefined ? _config['overviewMapControl'] 	: false;
	this.draggable				= _config['draggable'] 				!= undefined ? _config['draggable'] 			: true;
	this.scrollwheel			= _config['scrollwheel'] 			!= undefined ? _config['scrollwheel'] 			: true;
	this.styles					= _config['styles'] 				!= undefined ? eval(_config['styles'])			: [];

	this.init = function()
	{
		// Render map
		var options =
		{
			zoom: 					this.zoom,
			center: 				new google.maps.LatLng(this.lat, this.lng),
			mapTypeId: 				this.mapTypeId,
			mapTypeControl:			this.mapTypeControl,
			streetViewControl: 		this.streetViewControl,
			zoomControl: 			this.zoomControl,
			panControl:				this.panControl,
			scaleControl: 			this.scaleControl,
			rotateControl: 			this.rotateControl,
			overviewMapControl:		this.overviewMapControl,
			draggable:				this.draggable,
			scrollwheel:			this.scrollwheel,
			styles:					this.styles
		};
		this.map = new google.maps.Map(document.getElementById(this.mapcode), options);
		this.map.mapcode = this.mapcode;
	}

	// Marker - Add marker
	this.add_marker = function(_markercode, _config)
	{
		this.markers[_markercode] = new WiLD_Googlemap_Marker(this.mapcode, _markercode, _config);
		this.markers[_markercode].init();
	}

	// Marker - Get marker
	this.get_marker = function(_markercode)
	{
		return this.markers[_markercode];
	}


	// Infowindow - Add infowindow
	this.add_infowindow = function(_markercode, _config)
	{
		this.infowindows[_markercode] = new WiLD_Googlemap_Infowindow(this.mapcode, _markercode, _config);
		this.infowindows[_markercode].init();
	}

	// Infowindow - Get infowindow
	this.get_infowindow = function(_markercode)
	{
		return this.infowindows[_markercode];
	}

	// Infowindow - Show specific infowindow
	this.show_infowindow = function(_markercode, _panTo)
	{
		// Close opened tooltips
		this.close_infowindows();

		// Show
		this.infowindows[_markercode].open(_panTo);
	}

	// Infowindow - Close all infowindows
	this.close_infowindows = function()
	{
		for (var markercode in this.infowindows)
		{
			if(this.infowindows[markercode] instanceof WiLD_Googlemap_Infowindow)
			{
				this.infowindows[markercode].close();
				this.infowindows[markercode].infowindow.disableAutoPan = false;
			}
		}
	}

	// Set map data
	this.set_center = function(_lat, _lng)
	{
		this.map.setCenter(new google.maps.LatLng(_lat, _lng));
	}

	this.set_zoom = function(_zoom)
	{
		this.map.setZoom(_zoom);
	}

	this.pan_to = function(_position)
	{
		this.map.panTo(_position);
	}

	this.center_marker = function(_markercode)
	{
		var position = this.markers[_markercode].get_position();
		this.map.setCenter(new google.maps.LatLng(position.lat(), position.lng()));
	}

	// Get map data
	this.get_position = function()
	{
		return this.map.getCenter();
	}

	this.get_zoom = function()
	{
		return this.map.getZoom();
	}
}


/*** WiLD_Googlemap_Marker ***/
function WiLD_Googlemap_Marker(_mapcode, _markercode, _config)
{
	// Initialize marker
	this.mapcode	= _mapcode;
	this.markercode = _markercode;

	if(_config == undefined) _config = [];
	this.title			= _config['title'] 		!= undefined ? _config['title'] 	: '';
	this.lat			= _config['lat'] 		!= undefined ? _config['lat'] 		: 0;
	this.lng			= _config['lng'] 		!= undefined ? _config['lng'] 		: 0;
	this.icon			= _config['icon'] 		!= undefined ? _config['icon'] 		: '';
	this.shadow			= _config['shadow'] 	!= undefined ? _config['shadow'] 	: '';
	this.draggable		= _config['draggable'] 	!= undefined ? _config['draggable'] : false;
	this.panOnDrag		= _config['panOnDrag'] 	!= undefined ? _config['panOnDrag'] : false;
	this.url			= '';

	this.init = function()
	{
		// Render marker
		var options =
		{
			position: 		new google.maps.LatLng(this.lat, this.lng),
			title: 			this.title,
			icon: 			this.icon,
			shadow:			this.shadow
		};

		this.marker = new google.maps.Marker(options);
		this.marker.setMap(WGM.get_map(this.mapcode).map);

		if(this.draggable)
		{
			this.marker.setDraggable(true);

			if(this.panOnDrag)
			{
				var mapcode		= this.mapcode;
				var markercode 	= this.markercode;
				google.maps.event.addListener(WGM.get_map(mapcode).get_marker(markercode).marker, "dragend", function(event)
				{
					var position = WGM.get_map(mapcode).get_marker(markercode).get_position();
					WGM.get_map(mapcode).pan_to(position);
					jQuery("input[name=lat]").val(position.lat());
					jQuery("input[name=lng]").val(position.lng());
				});
			}
		}
	}

	// Set
	this.set_address = function(_address, _config)
	{
		if(_config == undefined) _config = [];
		if(_config['region'] 	== undefined)	_config['region'] 	= '';
		if(_config['zoom'] 		== undefined)	_config['zoom'] 	= false;
		if(_config['panTo'] 	== undefined)	_config['panTo'] 	= false;

		var mapcode 	= this.mapcode;
		var markercode 	= this.markercode;
		var lat	= 0;
		var lng	= 0;

		var geocoder 	= new google.maps.Geocoder();
		geocoder.geocode({'address': _address, 'region': _config['region']}, function(results, status)
		{
			if (status == google.maps.GeocoderStatus.OK)
			{
				lat = results[0].geometry.location.lat();
				lng = results[0].geometry.location.lng();

				var position = new google.maps.LatLng(lat, lng);
				WGM.get_map(mapcode).get_marker(markercode).set_position(position);

				if(_config['panTo'] === true)
					WGM.get_map(mapcode).pan_to(position);

				if(_config['zoom'] !== false)
					WGM.get_map(mapcode).set_zoom(_config['zoom']);

				jQuery("input[name=lat]").val(position.lat());
				jQuery("input[name=lng]").val(position.lng());
				return true;
			}
			else
			{
				alert("Address not found");
				return false;
			}
		});
	}

	this.set_position = function(_position, _config)
	{
		if(_config == undefined) _config = [];
		if(_config['zoom'] 		== undefined)	_config['zoom'] 	= false;
		if(_config['panTo'] 	== undefined)	_config['panTo'] 	= false;

		var mapcode 	= this.mapcode;
		var markercode 	= this.markercode;

		this.marker.setPosition(_position);

		if(_config['panTo'] === true)
			WGM.get_map(mapcode).pan_to(_position);

		if(_config['zoom'] !== false)
			WGM.get_map(mapcode).set_zoom(_config['zoom']);
	}

	this.set_icon = function(_icon)
	{
		if(_icon != undefined) 		this.marker.setIcon(_icon);
	}

	this.set_shadow = function(_shadow)
	{
		if(_shadow != undefined) 	this.marker.setShadow(_shadow);
	}

	this.set_link = function(_url)
	{
		this.url = _url;

		var mapcode 	= this.mapcode;
		var markercode 	= this.markercode;
		var url			= this.url;

		google.maps.event.addListener(WGM.get_map(mapcode).get_marker(markercode).marker, "click", function(event)
		{
			window.open(url, '_blank');
			window.focus();
		});
	}

	// Get
	this.get_position = function()
	{
		return this.marker.getPosition();
	}
}


/*** WiLD_Googlemap_Infowindow ***/
function WiLD_Googlemap_Infowindow(_mapcode, _markercode, _config)
{
	this.mapcode 		= _mapcode;
	this.markercode		= _markercode;

	if(_config == undefined) _config = [];
	this.content		= _config['content'] 		!= undefined ? _config['content'] 			: '';
	this.zoom			= _config['zoom'] 			!= undefined ? _config['zoom'] 				: false;	// false or integer
	this.disableAutoPan	= _config['disableAutoPan'] != undefined ? _config['disableAutoPan'] 	: false;	// bool
	this.centerOnClick	= _config['centerOnClick'] 	!= undefined ? _config['centerOnClick'] 	: false;	// bool

	this.init = function()
	{
		// Render Infowindow
		var options =
		{
			content: 		this.content,
			disableAutoPan: this.disableAutoPan
		};

		this.infowindow = new google.maps.InfoWindow(options);

		// Bind marker click
		var mapcode 		= this.mapcode;
		var markercode 		= this.markercode;
		var zoom			= this.zoom;
		var centerOnClick 	= this.centerOnClick
		google.maps.event.addListener(WGM.get_map(mapcode).get_marker(markercode).marker, "click", function(event)
		{
			if(zoom !== false)
			{
				WGM.get_map(mapcode).set_zoom(zoom);
			}
			WGM.get_map(mapcode).show_infowindow(_markercode, centerOnClick);
		});

		google.maps.event.addListener(WGM.get_map(mapcode).get_infowindow(markercode).infowindow, "closeclick", function(event)
		{
			WGM.get_map(mapcode).get_infowindow(markercode).infowindow.disableAutoPan = false;
		});
	}

	this.open = function(_panTo)
	{
		this.infowindow.open(WGM.get_map(this.mapcode).map, WGM.get_map(this.mapcode).get_marker(this.markercode).marker);

		if(_panTo != undefined && _panTo)
		{
			WGM.get_map(this.mapcode).center_marker(this.markercode);
		}
	}

	this.close = function()
	{
		this.infowindow.close();
	}
}