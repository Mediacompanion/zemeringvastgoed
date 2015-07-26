<?php

class WiLD_BackendGooglemapManager
{
	# Vars
	private static $instance = null;
	private $maps	= array();
	private $params = array();
	private $styles	= array();

	private function __construct() {}
	private function __clone() {}

	# Init
	public function init()
	{
		// Here you can add your own custom json styles
		// Editor: http://gmaps-samples-v3.googlecode.com/svn/trunk/styledmaps/wizard/index.html
		$this->styles['Simple Red'] 	= "[{featureType:'all',stylers:[{hue:'#ff0000'}]}]";
		$this->styles['Simple Blue']	= "[{featureType:'all',stylers:[{hue:'#0000ff'}]}]";
		$this->styles['Simple Green']	= "[{featureType:'all',stylers:[{hue:'#00ff00'}]}]";
		$this->styles['Simple Purple']	= "[{featureType:'all',stylers:[{hue:'#ff00ff'}]}]";
		$this->styles['Night']			= "[{featureType:'all',stylers:[{invert_lightness:'true'}]}]";
		$this->styles['Dark Night']		= "[{stylers:[{hue:'#00aeff'},{invert_lightness:true}]},{featureType:'water',stylers:[{hue:'#0095ff'},{saturation:75},{lightness:-64}]},{featureType:'administrative',elementType:'geometry',stylers:[{visibility:'off'}]},{featureType:'administrative',elementType:'labels',stylers:[{hue:'#00bbff'},{saturation:38},{lightness:-50}]},{featureType:'administrative.province',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'landscape.man_made',stylers:[{hue:'#00bbff'},{saturation:48},{lightness:-5}]},{featureType:'landscape.natural',stylers:[{hue:'#00bbff'},{saturation:48},{lightness:5}]},{featureType:'administrative.land_parcel',stylers:[{visibility:'off'}]},{featureType:'administrative.neighborhood',stylers:[{visibility:'off'}]},{featureType:'landscape.man_made',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'poi',elementType:'geometry',stylers:[{hue:'#00bbff'},{saturation:18},{lightness:-99},{gamma:4}]},{featureType:'poi.medical',elementType:'geometry',stylers:[{visibility:'on'},{lightness:-10}]},{featureType:'poi.government',elementType:'geometry',stylers:[{hue:'#00bbff'},{saturation:28},{lightness:4}]},{featureType:'poi.attraction',stylers:[{visibility:'off'}]},{featureType:'poi.business',stylers:[{visibility:'off'}]},{featureType:'poi.government',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'poi.government',elementType:'geometry',stylers:[{hue:'#00bbff'},{saturation:28},{lightness:-30}]},{featureType:'poi.park',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'poi.park',elementType:'geometry',stylers:[{hue:'#00bbff'},{saturation:28},{lightness:-30}]},{featureType:'poi.place_of_worship',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'poi.school',stylers:[{visibility:'off'}]},{featureType:'poi.sports_complex',stylers:[{visibility:'off'}]},{featureType:'road.local',elementType:'labels',stylers:[{hue:'#00bbff'},{saturation:48},{lightness:-60}]},{featureType:'road.local',elementType:'geometry',stylers:[{visibility:'simplified'}]},{featureType:'road.arterial',elementType:'labels',stylers:[{hue:'#00bbff'},{saturation:28},{lightness:-60}]},{featureType:'road.arterial',elementType:'geometry',stylers:[{visibility:'simplified'},{hue:'#00bbff'},{saturation:28},{lightness:-70}]},{featureType:'road.highway',elementType:'geometry',stylers:[{visibility:'simplified'},{hue:'#00bbff'},{saturation:38},{lightness:-60}]},{featureType:'road.highway',elementType:'labels',stylers:[{hue:'#00bbff'},{saturation:28},{lightness:-70}]},{featureType:'transit',elementType:'geometry',stylers:[{visibility:'off'}]},{featureType:'transit',elementType:'labels',stylers:[{visibility:'simplified'},{hue:'#00bbff'},{saturation:18},{lightness:-50}]}]";
		$this->styles['Blue'] 			= "[{featureType:'all',stylers:[{hue:'#0000b0'},{invert_lightness:'true'},{saturation:-30}]}]";
		$this->styles['Light']			= "[{stylers:[{hue:'#2DB5E2'},{saturation:-20}]},{featureType:'road',elementType:'geometry',stylers:[{lightness:100},{visibility:'simplified'}]},{featureType:'road',elementType:'labels',stylers:[{visibility:'off'}]}]";
		$this->styles['Mixed'] 			= "[{featureType:'landscape',stylers:[{hue:'#00dd00'}]},{featureType:'road',stylers:[{hue:'#dd0000'}]},{featureType:'water',stylers:[{hue:'#000040'}]},{featureType:'poi.park',stylers:[{visibility:'off'}]},{featureType:'road.arterial',stylers:[{hue:'#ffff00'}]},{featureType:'road.local',stylers:[{visibility:'off'}]}]";
		$this->styles['Chilled'] 		= "[{featureType:'road',elementType:'geometry',stylers:[{'visibility':'simplified'}]},{featureType:'road.arterial',stylers:[{hue:149},{saturation:-78},{lightness:0}]},{featureType:'road.highway',stylers:[{hue:-31},{saturation:-40},{lightness:2.8}]},{featureType:'poi',elementType:'label',stylers:[{'visibility':'off'}]},{featureType:'landscape',stylers:[{hue:163},{saturation:-26},{lightness:-1.1}]},{featureType:'transit',stylers:[{'visibility':'off'}]},{featureType:'water',stylers:[{hue:3},{saturation:-24.24},{lightness:-38.57}]}]";
		$this->styles['Vivid'] 			= "[{featureType:'water',stylers:[{hue:'#0091ff'},{saturation:39},{lightness:-8}]},{featureType:'landscape.man_made',elementType:'geometry',stylers:[{visibility:'on'},{hue:'#ff8400'},{saturation:2},{lightness:-4}]},{featureType:'administrative.land_parcel',stylers:[{visibility:'off'}]},{featureType:'administrative.neighborhood',stylers:[{visibility:'off'}]},{featureType:'landscape.man_made',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'poi.park',stylers:[{hue:'#8cff00'},{saturation:15},{lightness:0}]},{featureType:'poi.business',stylers:[{visibility:'off'}]},{featureType:'poi.sports_complex',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'poi.government',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'poi.medical',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'road',elementType:'labels',stylers:[{hue:'#000000'},{saturation:-100},{gamma:2},{lightness:'10'}]},{featureType:'road.arterial',elementType:'geometry',stylers:[{visibility:'simplified'},{saturation:59},{hue:'#00fffb'},{lightness:87},{gamma:3.82}]},{featureType:'road.local',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'road.highway',elementType:'geometry',stylers:[{visibility:'on'},{saturation:59},{hue:'#00fffb'},{lightness:87},{gamma:3.82}]},{featureType:'transit',stylers:[{visibility:'off'}]},{featureType:'poi.place_of_worship',elementType:'labels',stylers:[{visibility:'off'}]}]";
		$this->styles['Old Map'] 		= "[{featureType:'water',stylers:[{hue:'#00ffe6'},{saturation:-60},{lightness:0}]},{featureType:'landscape.man_made',elementType:'geometry',stylers:[{visibility:'on'},{hue:'#ffc300'},{saturation:-25},{lightness:40}]},{featureType:'landscape.natural',elementType:'geometry',stylers:[{hue:'#b7ff00'},{saturation:-35},{lightness:20}]},{featureType:'administrative',elementType:'labels',stylers:[{saturation:-90},{lightness:30}]},{featureType:'administrative.land_parcel',stylers:[{visibility:'on'}]},{featureType:'administrative.neighborhood',stylers:[{visibility:'off'}]},{featureType:'landscape.man_made',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'poi',elementType:'labels',stylers:[{saturation:-90},{lightness:30}]},{featureType:'poi.park',stylers:[{hue:'#b7ff00'},{saturation:-35},{lightness:3}]},{featureType:'poi.business',stylers:[{visibility:'off'}]},{featureType:'poi.sports_complex',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'poi.government',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'poi.medical',stylers:[{visibility:'on'},{hue:'#ff1e00'},{saturation:-10},{lightness:10}]},{featureType:'poi.school',stylers:[{visibility:'on'},{hue:'#ffc300'},{saturation:-10},{lightness:0}]},{featureType:'road',elementType:'labels',stylers:[{hue:'#000000'},{saturation:-100},{gamma:2},{visibility:'on'}]},{featureType:'road.arterial',elementType:'geometry',stylers:[{visibility:'simplified'},{saturation:-100},{hue:'#00fffb'},{lightness:20}]},{featureType:'road.arterial',elementType:'labels',stylers:[{visibility:'on'},{saturation:-100},{hue:'#00fffb'},{lightness:20}]},{featureType:'road.local',elementType:'geometry',stylers:[{visibility:'simplified'},{saturation:-100},{hue:'#00fffb'},{lightness:-10}]},{featureType:'road.local',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'road.highway',elementType:'geometry',stylers:[{visibility:'simplified'},{saturation:-90},{hue:'#ffd000'},{lightness:5}]},{featureType:'road.highway',elementType:'labels',stylers:[{visibility:'simplified'},{saturation:30},{hue:'#ff1100'},{lightness:0}]},{featureType:'transit',stylers:[{visibility:'off'}]},{featureType:'poi.place_of_worship',elementType:'labels',stylers:[{visibility:'off'}]}]";
		$this->styles['Planisphere']	= "[{featureType:'water',stylers:[{hue:'#00c8ff'},{saturation:20},{lightness:-40}]},{featureType:'landscape.man_made',elementType:'geometry',stylers:[{visibility:'on'},{hue:'#0091ff'},{saturation:20},{lightness:70}]},{featureType:'landscape.natural',elementType:'geometry',stylers:[{visibility:'on'},{hue:'#0091ff'},{saturation:30},{lightness:45}]},{featureType:'administrative.land_parcel',stylers:[{visibility:'on'}]},{featureType:'administrative.neighborhood',stylers:[{visibility:'off'}]},{featureType:'landscape.man_made',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'poi.park',stylers:[{hue:'#51ff00'},{saturation:-30},{lightness:-32}]},{featureType:'poi.business',stylers:[{visibility:'off'}]},{featureType:'poi.sports_complex',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'poi.government',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'poi.medical',stylers:[{visibility:'on'},{hue:'#ff1e00'},{saturation:40},{lightness:10}]},{featureType:'poi.school',stylers:[{visibility:'on'},{hue:'#ffc300'},{saturation:50},{lightness:0}]},{featureType:'road',elementType:'labels',stylers:[{hue:'#000000'},{saturation:-100},{gamma:2},{visibility:'on'}]},{featureType:'road.arterial',elementType:'geometry',stylers:[{visibility:'simplified'},{saturation:-100},{hue:'#00fffb'},{lightness:00}]},{featureType:'road.local',elementType:'geometry',stylers:[{visibility:'simplified'},{saturation:-100},{hue:'#00fffb'},{lightness:-10}]},{featureType:'road.local',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'road.highway',elementType:'geometry',stylers:[{visibility:'simplified'},{saturation:-100},{hue:'#ffd000'},{lightness:-40},{invert_ligthness:true}]},{featureType:'road.highway',elementType:'labels',stylers:[{visibility:'simplified'},{saturation:70},{hue:'#ff1100'},{lightness:0}]},{featureType:'transit',stylers:[{visibility:'off'}]},{featureType:'poi.place_of_worship',elementType:'labels',stylers:[{visibility:'off'}]}]";
		$this->styles['Coffee'] 		= "[{'featureType':'water','elementType':'geometry','stylers':[{'color':'#efdb9e'}]},{'featureType':'landscape.natural.landcover','stylers':[{'color':'#cea76d'}]},{'featureType':'landscape.natural.terrain','stylers':[{'hue':'#ff0000'},{'visibility':'on'},{'color':'#9b7947'}]},{'featureType':'landscape.man_made','stylers':[{'color':'#d4b07b'}]},{'featureType':'administrative','elementType':'geometry','stylers':[{'color':'#be975d'}]},{'featureType':'administrative','elementType':'labels.text.stroke','stylers':[{'visibility':'on'},{'color':'#d5b17b'},{'weight':0}]},{'featureType':'administrative','elementType':'labels.text.fill','stylers':[{'color':'#9b7947'}]},{'featureType':'road','stylers':[{'color':'#ba9762'}]},{'featureType':'water','elementType':'labels.text.fill','stylers':[{'color':'#b5a163'}]},{'featureType':'poi','stylers':[{'color':'#d9b37b'}]},{'featureType':'poi','elementType':'labels.text.fill','stylers':[{'color':'#9b7947'}]},{'featureType':'poi','elementType':'labels.icon','stylers':[{'color':'#9b7947'}]},{'featureType':'road','elementType':'labels.text.fill','stylers':[{'color':'#624a27'}]},{'featureType':'road','elementType':'labels.text.stroke','stylers':[{'visibility':'off'}]},{'featureType':'road','elementType':'labels.icon','stylers':[{'visibility':'off'}]},{'featureType':'landscape.natural','stylers':[{'visibility':'on'},{'color':'#cea76d'}]}]";
		$this->styles['Greyscale'] 		= "[{featureType:'all',stylers:[{saturation:-100},{gamma:0.50}]}]";
		$this->styles['Light Greyscale']= "[{featureType:'water',stylers:[{hue:'#ff0400'},{saturation:-100},{lightness:20}]},{featureType:'landscape.man_made',elementType:'geometry',stylers:[{visibility:'on'},{hue:'#ff0400'},{saturation:-100},{lightness:1}]},{featureType:'landscape.natural',elementType:'geometry',stylers:[{visibility:'on'},{hue:'#ff0400'},{saturation:-100},{lightness:-1}]},{featureType:'administrative',elementType:'labels',stylers:[{hue:'#ff0400'},{saturation:-100},{lightness:30}]},{featureType:'administrative.land_parcel',stylers:[{visibility:'off'}]},{featureType:'administrative.neighborhood',stylers:[{visibility:'off'}]},{featureType:'landscape.man_made',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'poi',elementType:'geometry',stylers:[{hue:'#ff0400'},{saturation:-100},{lightness:70}]},{featureType:'poi',elementType:'labels',stylers:[{saturation:-100},{lightness:20}]},{featureType:'poi.business',stylers:[{visibility:'off'}]},{featureType:'poi.sports_complex',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'poi.government',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'poi.medical',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'road',elementType:'labels',stylers:[{saturation:-100},{hue:'#00fffb'},{lightness:40}]},{featureType:'road.arterial',elementType:'geometry',stylers:[{visibility:'simplified'},{saturation:-100},{hue:'#00fffb'},{lightness:10}]},{featureType:'road.local',elementType:'geometry',stylers:[{visibility:'simpliefied'}]},{featureType:'road.local',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'road.highway',elementType:'geometry',stylers:[{visibility:'simplified'},{saturation:-100},{hue:'#00fffb'},{lightness:40}]},{featureType:'transit',stylers:[{visibility:'off'}]},{featureType:'poi.place_of_worship',elementType:'labels',stylers:[{visibility:'off'}]}]";
		$this->styles['No Roads'] 		= "[{featureType:'road',stylers:[{visibility:'off'}]}]";
		$this->styles['Monochrome'] 	= "[{featureType:'all',stylers:[{visibility:'off'}]},{featureType:'water',stylers:[{visibility:'on'},{lightness:-100}]}]";
	}

	# Get posts
	public function get_maps()
	{
		global $wpdb;
		$this->maps = array();

		// PARAM DEFAULT
		$this->params["search"] 	= NULL;
		$this->params["order_by"] 	= "id";
		$this->params["order_sort"] = "asc";

		// PARAM GET
		foreach($this->params as $key => $value)
		{
			if(isset($_GET[$key]) && $_GET[$key] != "")
			{
				$this->params[$key] = trim($_GET[$key]);
			}
		}

		// SQL
		$sql = '';
		$params = array();

		// ORDER
		if($this->params["order_sort"] != "asc" && $this->params["order_sort"] != "desc") $this->params["order_sort"] = "asc";

		switch($this->params["order_by"])
		{
			case "id":
				$sql .= " ORDER BY id {$this->params["order_sort"]}";
				break;
			case "name":
			default:
				$sql .= " ORDER BY name {$this->params["order_sort"]}";
				break;
		}

		// SQL
		$sql = "SELECT *
				FROM `" . $wpdb->prefix . WiLD_GOOGLEMAP_TABLE . "`
				WHERE 1=1 " . $sql;

		$data = $wpdb->get_results($sql);
		foreach($data as $item)
		{
			$map = new WiLD_Googlemap();
			$map->init_db($item);

			$this->maps []= $map;
		}
		return $this->maps;
	}

	public function get_data($key)
	{
		if(isset($this->params[$key])) return $this->params[$key];
		return null;
	}

	# Select
	public function get_googlemap_select($_exclude = array(), $_current = 0)
	{
		$html = '';
		foreach($this->get_maps() as $googlemap)
		{
			if(in_array($googlemap->id, $_exclude)) continue;
			$html .= '<option value="' . $googlemap->id . '" ' . ($googlemap->id == $_current ? 'selected="selected"' : ''). '>' . $googlemap->name. '</option>';
		}
		return $html;
	}

	# Icons
	public function get_icons()
	{
		$icons = array();
		foreach(scandir(plugin_dir_path( __FILE__ ) . "/icons") as $icon)
		{
			if(preg_match("/^([a-zA-Z0-9-_]+).png$/", $icon)) array_push($icons, $icon);
		}
		return $icons;
	}

	# Styles
	public function get_styles()
	{
		return $this->styles;
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