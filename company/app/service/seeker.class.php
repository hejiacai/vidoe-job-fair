<?php
/**
 * 请填写描述
 * @ClassName company_service_seeker
 * @author fangzhou@huibo.com
 * @date
 */
class company_service_seeker extends base_components_baseservice {

	/**
	 * 搜索器转换为URL链接参数
	 * @var array (seeker => html url params)
	 */
	private $_data = array(
		'jobsort'           => "j",
		'calling'           => "c",
		'degree_min'        => 'dmin',
		'degree_max'        => 'dmax',
		'degree_ids'        => 'd',
		'work_year1'        => "ymin",
		'work_year2'        => "ymax",
		'age_lower'         => "amin",
		'age_upper'         => "amax",
		'sex'               => "s",
		'salarymin'         => "smin",
		'salarymax'         => "smax",
		'exp_area_id'       => "ea",
		'cur_area_id'       => "a",
		'exp_station'       => "es",
		'station_type'      => "st",
		'station'           => "sn",
		'company_type'      => "ct",
		'company'           => "cn",
		'keyword'           => "k",
		'containsAnyKey'    => 'sp',
		"marriage"			=> "ma",
		"statureMin"		=> 'sml',
		"statureMax"		=> 'smb',
        "accession_time" => 'ac',
        "major_desc"     => 'md',
        "photo"          => 'pht',
	);

	/**
	 * 搜索器转换为solr参数
	 * @var array (seeker => solr_field/array(function_name, seeker1, seeker2))
	 */
	private $_solr = array(
		"workyear_min"         => "work_year1",
		"workyear_max"         => "work_year2",
		"cur_areas"            => "exp_area_id",
		"exp_areas"            => "cur_area_id",
		"all_stations"         => array("_fixType2", "station", "station_type"),
		"last_work_station"    => array("_fixType1", "station", "station_type"),
		"all_companys"         => array("_fixType2", "company", "company_type"),
		"last_company_name"    => array("_fixType1", "company", "company_type"),
		"jobsorts"             => "jobsort",
		"callings"             => "calling",
		"keyword"              => "keyword",
		"age_lower"            => "age_lower",
		"age_upper"            => "age_upper",
		"exp_station"          => "exp_station",
		"degree_ids"           => array("_fixDegree", "degree_ids", "degree_min", "degree_max"),
		"sex"                  => "sex",
		"contains_any_keyword" => "containsAnyKey",
		'marriage'			   => 'marriage',
		"stature_min"		   => 'statureMin',
		"stature_max"		   => 'statureMax',
		'accession_time'	   => 'accession_time',
		'major_desc'	   => 'major_desc'
	);

	public function __construct() {
		parent::__construct();
	}
	
	public function buildSolr($content) {
		$seeker_items = explode(";;", $content);
		foreach ((array)$seeker_items as $items) {
			$item = explode("::", $items);
			if (count($item) > 1) {
				$arr[$item[0]] = $item[1];
				$params[$this->_data[$item[0]]] = $item[1];
			}
		}

		foreach ($this->_solr as $key => $item) {
			if (is_array($item)) {
				$func = array_shift($item);
				$result = call_user_func_array(array($this, $func), array_map(function($v) use ($arr) {
					if (empty($arr[$v]))
						return "";

					return $arr[$v];
				}, $item));

				if (!empty($result))
					$postvar[$key] = $result;
			} else {
				if (!empty($arr[$item]))
					$postvar[$key] = $arr[$item];
 			}
		}

		return array($params, $postvar);
	}

	public function buildSeeker($content) {
		$content = htmlspecialchars_decode($content);
		if (empty($content))
			return "";

		$_items = explode("&", $content);
		foreach ((array)$_items as $item) {
			list($_key, $_value) = explode("=", $item);
			if ($_key && $_value)
				$items[$_key] = $_value;
		}

		$_arr = array_flip($this->_data);
		$str = "";

		foreach ($_arr as $_key => $_value) {
			if (!empty($items[$_key]))
				$str .= $_value . "::" . $items[$_key] . ";;";
		}

		return $str;
	}

	public function _fixType1($s, $t) {
		if (($t == 1 || empty($t)) && !empty($s))
			return $s;
	}

	public function _fixType2($s, $t) {
		if ($t == 2 && !empty($s))
			return $s;
	}

	public function _fixDegree($ids, $min) {
		if (empty($ids)) {
			/* degree param */
			$degree_common = new base_service_common_degree();
			$degrees = $degree_common->getAll();
			$lower = array_search($min, $degrees);

			if ($lower) {
				$degrees = array_slice($degrees, (empty($lower) ? 0 : $lower));
				$ids  = implode(",", $degrees);
			}
		}
		return $ids;
	}
}