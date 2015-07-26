<?php

class WiLD_Googlemap
{
	var $id;
	var $name;
	var $date;
	var $lat;
	var $lng;
	var $zoom;
	private $markers;
	private $options;

	function __construct($_id = 0)
	{
		$this->id 		= 0;
		$this->name 	= "";
		$this->w		= WiLD_GOOGLEMAP_WIDTH;
		$this->h		= WiLD_GOOGLEMAP_HEIGHT;
		$this->lat		= WiLD_GOOGLEMAP_LAT;
		$this->lng		= WiLD_GOOGLEMAP_LNG;
		$this->zoom		= WiLD_GOOGLEMAP_ZOOM;
		$this->markers	= array();
		$this->options	= array();

		// Default options
		$this->options['mapTypeId'] 			= "ROADMAP";
		$this->options['zoomControl']			= 1;
		$this->options['panControl']			= 1;
		$this->options['scaleControl']			= 0;
		$this->options['mapTypeControl'] 		= 1;
		$this->options['streetViewControl'] 	= 1;
		$this->options['rotateControl']			= 0;
		$this->options['overviewMapControl']	= 0;
		$this->options['draggable'] 			= 1;
		$this->options['scrollwheel'] 			= 1;
		$this->options['styles']				= '';
		$this->options['textColor']				= '';
		$this->options['linkColor']				= '';

		$this->load($_id);
	}

	private function load($_id)
	{
		global $wpdb;

		if($_id == 0) return;

		$sql = "SELECT *
				FROM `" . $wpdb->prefix . WiLD_GOOGLEMAP_TABLE . "`
				WHERE id = %d
				LIMIT 1";

		$data = $wpdb->get_row($wpdb->prepare($sql, $_id));
		$this->init_db($data);
	}


	public function init_db($_data)
	{
		if($_data === false) 	return;
		if(!isset($_data->id)) 	return;

		$this->id 		= $_data->id;
		$this->name		= $_data->name;
		$this->w		= $_data->w;
		$this->h		= $_data->h;
		$this->lat		= $_data->lat;
		$this->lng		= $_data->lng;
		$this->zoom		= $_data->zoom;
		$this->options	= (array)json_decode($_data->options);

		$markers = (array)json_decode($_data->markers);
		foreach($markers as $marker_item)
		{
			$config = array();
			$config['lat'] 				= isset($marker_item->lat) 				? $marker_item->lat 					: 0;
			$config['lng'] 				= isset($marker_item->lng) 				? $marker_item->lng 					: 0;
			$config['name'] 			= isset($marker_item->name) 			? $marker_item->name 					: '';
			$config['description'] 		= isset($marker_item->description) 		? $marker_item->description 			: '';
			$config['icon'] 			= isset($marker_item->icon) 			? $marker_item->icon 					: '';
			$config['directions_link'] 	= isset($marker_item->directions_link) 	? $marker_item->directions_link 		: 1;
			$config['directions_text'] 	= isset($marker_item->directions_text) 	? $marker_item->directions_text 		: 'Get directions';
			$config['behaviour'] 		= isset($marker_item->behaviour) 		? $marker_item->behaviour				: 1;
			$config['url'] 				= isset($marker_item->url) 				? $marker_item->url						: "http://";

			$marker = new WiLD_GooglemapMarker($config);
			$this->add_marker($marker);
		}
	}

	private function normalize()
	{
		$this->id 			= trim($this->id);
		$this->name			= trim($this->name);
		$this->w			= trim($this->w);
		$this->h			= trim($this->h);
		$this->lat			= trim($this->lat);
		$this->lng			= trim($this->lng);
		$this->zoom			= trim($this->zoom);

		foreach($this->options as $key => $value)
		{
			$this->options[$key] = trim($value);
		}
	}

	public function save()
	{
		global $wpdb;

		$this->normalize();

		# Check name
		if(preg_replace("/[^a-zA-Z0-9\s]/", "", $this->name) == "")
		{
			if(defined("WiLD_DEBUG") && WiLD_DEBUG) trigger_error("Name not defined", E_USER_WARNING);
			WiLD_Googlemap_MessageManager::getInstance()->add("Insert a valid name", WiLD_MESSAGE_ERROR);
			return false;
		}

		# Check if name already used
		$sql = "SELECT count(*) AS count
				FROM `" . $wpdb->prefix . WiLD_GOOGLEMAP_TABLE . "`
				WHERE name LIKE %s AND id != %d";

		$data = $wpdb->get_row($wpdb->prepare($sql, $this->name, $this->id));
		if($data->count != 0)
		{
			if(defined("WiLD_DEBUG") && WiLD_DEBUG) trigger_error("Map name '{$this->name}' already exists", E_USER_WARNING);
			WiLD_Googlemap_MessageManager::getInstance()->add("A map with this name already exists", WiLD_MESSAGE_ERROR);
			return false;
		}

		# Check size
		if(!preg_match("/^([0-9]+)(\%?)$/", $this->w))
		{
			if(defined("WiLD_DEBUG") && WiLD_DEBUG) trigger_error("Width not valid", E_USER_WARNING);
			WiLD_Googlemap_MessageManager::getInstance()->add("Invalid map size", WiLD_MESSAGE_ERROR);
			return false;
		}

		if(!preg_match("/^([0-9]+)(\%?)$/", $this->h))
		{
			if(defined("WiLD_DEBUG") && WiLD_DEBUG) trigger_error("Height not valid", E_USER_WARNING);
			WiLD_Googlemap_MessageManager::getInstance()->add("Invalid map size", WiLD_MESSAGE_ERROR);
			return false;
		}

		# New googlemap
		if($this->id == 0)
		{
			$sql = "INSERT INTO `" . $wpdb->prefix . WiLD_GOOGLEMAP_TABLE . "` (name, w, h, lat, lng, zoom, options)
					VALUES (%s, %s, %s, %s, %s, %d, %s)";

			$wpdb->query($wpdb->prepare($sql,	$this->name,
												$this->w,
												$this->h,
												$this->lat,
												$this->lng,
												$this->zoom,
												json_encode($this->options)
										));

			# Update id
			$this->id = $wpdb->insert_id;

			# Msg
			WiLD_Googlemap_MessageManager::getInstance()->add("Map created", WiLD_MESSAGE_OK);

			# Return
			return true;
		}
		# Update gallery
		else
		{
			$sql = "UPDATE `" . $wpdb->prefix . WiLD_GOOGLEMAP_TABLE . "`
					SET
						name			= %s,
						w				= %s,
						h				= %s,
						lat				= %s,
						lng				= %s,
						zoom			= %d,
						options			= %s,
						markers			= %s
					WHERE id = %d";

			$wpdb->query($wpdb->prepare($sql,	$this->name,
												$this->w,
												$this->h,
												$this->lat,
												$this->lng,
												$this->zoom,
												json_encode($this->options),
												$this->encode_markers(),
												$this->id
										));

			WiLD_Googlemap_MessageManager::getInstance()->add("Map updated", WiLD_MESSAGE_OK);

			# Return
			return true;
		}
	}

	public function delete()
	{
		global $wpdb;

		# Delete googlemap
		$sql = "DELETE FROM `" . $wpdb->prefix . WiLD_GOOGLEMAP_TABLE . "`
				WHERE id = %d";

		$wpdb->query($wpdb->prepare($sql, $this->id));

		# Msg
		WiLD_Googlemap_MessageManager::getInstance()->add("Googlemap deleted", WiLD_MESSAGE_OK);
	}

	public function get_option($_var)
	{
		if(!isset($this->options[$_var])) return NULL;
		return $this->options[$_var];
	}

	public function set_option($_var, $_value)
	{
		$this->options[$_var] = $_value;
	}

	public function get_css_width()
	{
		if(preg_match("/^([0-9]+)(\%)$/", $this->w))
			return $this->w;
		elseif(preg_match("/^([0-9]+)$/", $this->w))
			return $this->w . "px";
		else
			return WiLD_GOOGLEMAP_WIDTH;
	}

	public function get_css_height()
	{
		if(preg_match("/^([0-9]+)(\%)$/", $this->h))
			return $this->h;
		elseif(preg_match("/^([0-9]+)$/", $this->h))
			return $this->h . "px";
		else
			return WiLD_GOOGLEMAP_HEIGHT;
	}

	# Markers
	public function get_markers()
	{
		return $this->markers;
	}

	public function clear_markers()
	{
		$this->markers = array();
	}

	public function add_marker($_marker)
	{
		array_push($this->markers, $_marker);
	}

	private function encode_markers()
	{
		$out = array();

		foreach($this->markers as $marker)
		{
			array_push($out, (array)$marker);
		}

		return json_encode($out);
	}

	# Frontend
	public function render($_id, $_settings = array())
	{
		if(!isset($_settings['class'])) $_settings['class'] = '';
		if(!isset($_settings['div'])) 	$_settings['div'] 	= NULL;

		if($_settings['div'] !== NULL)
		{
			$_id = $_settings['div'];
		}

		$map = array();
		$map['id'] 						= $_id;
		$map['class'] 					= $_settings['class'];
		$map['lat'] 					= $this->lat;
		$map['lng'] 					= $this->lng;
		$map['zoom'] 					= $this->zoom;
		$map['mapTypeId'] 				= $this->get_option('mapTypeId');
		$map['mapTypeControl'] 			= $this->get_option('mapTypeControl') 		? "true" : "false";
		$map['streetViewControl']		= $this->get_option('streetViewControl')	? "true" : "false";
		$map['zoomControl']				= $this->get_option('zoomControl')			? "true" : "false";
		$map['panControl']				= $this->get_option('panControl')			? "true" : "false";
		$map['scaleControl']			= $this->get_option('scaleControl')			? "true" : "false";
		$map['overviewMapControl']	 	= $this->get_option('overviewMapControl')	? "true" : "false";
		$map['draggable']				= $this->get_option('draggable')			? "true" : "false";
		$map['scrollwheel']				= $this->get_option('scrollwheel')			? "true" : "false";
		$map['styles']					= $this->get_option('styles') != ""			? $this->options['styles'] : "[]";
		$map['markers']					= sizeof($this->markers);

		# Markers
		$map_markers = array();

		foreach($this->markers as $marker)
		{
			$map_marker = array();

			$map_marker['lat'] 		= $marker->lat;
			$map_marker['lng'] 		= $marker->lng;
			$map_marker['title'] 	= $marker->name;

			if($marker->icon != '')
			{
				$map_marker['icon'] = plugins_url('/icons/'. $marker->icon, __FILE__);

				if(file_exists(plugin_dir_path( __FILE__ ) . '/icons/'. $marker->shadow))
				{
					$map_marker['shadow'] = plugins_url('/icons/'. $marker->shadow, __FILE__) ;
				}
				else
				{
					$map_marker['shadow'] = "";
				}
			}
			else
			{
				$map_marker['icon'] = "";
				$map_marker['shadow'] = "";
			}

			# Tooltip
			$map_marker['behaviour'] = $marker->behaviour;

			if(in_array($marker->behaviour, array(1, 2)))
			{
				$map_marker['content'] = "<div class='gmap_infowindow'>";
				$map_marker['content'] .= "<p class='gmap_name'><strong>{$marker->name}</strong></p>";
				if($marker->description != '')
				{
					$map_marker['content'] .= "<p class='gmap_description'>{$marker->description}</p>";
				}
				if($marker->directions_link == 1)
				{
					$map_marker['content'] .= "<p class='gmap_directions'><a href='https://maps.google.com/?daddr=". urlencode($marker->name) ."%40{$marker->lat},{$marker->lng}' target='_blank'>{$marker->directions_text}</a></p>";
				}
				$map_marker['content'] .= "</div>";
				$map_marker['content'] = str_replace(array("<", ">"), array("&lt;", "&gt;"), $map_marker['content']);
			}
			else if($marker->behaviour == 3)
			{
				$map_marker['link'] = $marker->url;
			}

			$map_markers []= $map_marker;
		}

		# Create call div
		$html = "\n";
		$html .= '<div class="gmap_call" ';

		foreach($map as $key => $value)
		{
			$html .= 'data-' . $key . '="' . WiLD_Googlemap_escape($value) . '" ';
		}

		for($i = 0; $i < sizeof($map_markers); $i++)
		{
			foreach($map_markers[$i] as $key => $value)
			{
				$html .= 'data-marker' . $i . '-' . $key . '="' . WiLD_Googlemap_escape($value) . '" ';
			}
		}

		$html .= '></div>';

		if($_settings['div'] === NULL)
		{
			$html .= "\n" . '<div id="'. $_id .'" class="gmap ' . $_settings['class'] . '" style="width:'. $this->get_css_width() .';height:'. $this->get_css_height() .'"></div>';
		}

		# CSS
		if($this->get_option("textColor") != "" || $this->get_option("linkColor") != "")
		{
			$html .= "\n" . '<style type="text/css">';

				if($this->get_option("textColor") != "")
				{
					$html .= "\n" . 'html #' . $_id . ' div.gmap_infowindow{color:' . $this->get_option("textColor") . ' !important;}';
				}

				if($this->get_option("linkColor") != "")
				{
					$html .= "\n" . 'html #' . $_id . ' div.gmap_infowindow a{color:' . $this->get_option("linkColor") . ' !important; text-decoration:none}';
					$html .= "\n" . 'html #' . $_id . ' div.gmap_infowindow a:hover{text-decoration:underline}';
				}

			$html .= "\n" . '</style>';
		}

		return $html;
	}
}

class WiLD_GooglemapMarker
{
	var $lat;
	var $lng;
	var $name;
	var $description;
	var $icon;
	var $shadow;
	var $directions_link;
	var $directions_text;
	var $behaviour;
	var $url;

	function __construct($_config)
	{
		$this->lat					= $_config['lat'];
		$this->lng					= $_config['lng'];
		$this->name 				= $_config['name'];
		$this->description 			= $_config['description'];
		$this->icon					= $_config['icon'];
		$this->shadow				= $this->icon == "" ? "" : pathinfo($this->icon, PATHINFO_FILENAME) . ".shadow." . pathinfo($this->icon, PATHINFO_EXTENSION);
		$this->directions_link		= $_config['directions_link'];
		$this->directions_text		= $_config['directions_text'];
		$this->behaviour			= $_config['behaviour'];
		$this->url					= $_config['url'];

		// Description new line
		$this->description = str_replace("\r", "", $this->description);
		$this->description = str_replace("\n", "<br \/>", $this->description);

		// Remove reverse slash
		$this->name 			= str_replace(array("\\"), array(""), $this->name);
		$this->description 		= str_replace(array("\\"), array(""), $this->description);
		$this->directions_text 	= str_replace(array("\\"), array(""), $this->directions_text);
		$this->url 				= str_replace(array("\\"), array(""), $this->url);
	}
}

class WiLD_GooglemapManager
{
	# Vars
	private static $instance = null;
	private $maps	= array();

	private function __construct() {}
	private function __clone() {}

	# Shortcode function
	function render($atts, $content = null)
	{
		// Extract shortcode parameters
		extract(shortcode_atts(array(
			'id' 	=> 0,
			'div' 	=> NULL,
			'class'	=> ''
		), $atts ));

		// Users can set the id in the content field
		if($content !== NULL && $content != '')
		{
			$id = $content;
		}

		// If already shown, do nothing
		if(isset($this->maps[$id])) return;

		// Fetch the googlemap, if it doesn't exists do nothing
		if($id == 0) return;
		$googlemap = new WiLD_Googlemap($id);
		if($googlemap->id == 0) return;
		$this->maps[$googlemap->id] = $googlemap;

		// Config
		$config = array();
		$config['class'] 	= $class;
		$config['div']		= $div;

		return $googlemap->render("gmap_{$googlemap->id}", $config);
	}

	# Singleton
	public static function getInstance()
	{
		if(self::$instance == null)
		{
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}
}

?>