<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjUtil extends pjToolkit
{
	static public function uuid()
	{
		return chr(rand(65,90)) . chr(rand(65,90)) . time();
	}
	
	static public function getReferer()
	{
		if (isset($_REQUEST['_escaped_fragment_']))
		{
			if (isset($_SERVER['REDIRECT_URL']))
			{
				return $_SERVER['REDIRECT_URL'];
			}
		}
		
		if (isset($_SERVER['HTTP_REFERER']))
		{
			$pos = strpos($_SERVER['HTTP_REFERER'], "#");
			if ($pos !== FALSE)
			{
				return substr($_SERVER['HTTP_REFERER'], 0, $pos);
			}
			return $_SERVER['HTTP_REFERER'];
		}
	}
	
	static public function getClientIp()
	{
		if (isset($_SERVER['HTTP_CLIENT_IP']))
		{
			return $_SERVER['HTTP_CLIENT_IP'];
		} else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if(isset($_SERVER['HTTP_X_FORWARDED'])) {
			return $_SERVER['HTTP_X_FORWARDED'];
		} else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_FORWARDED_FOR'];
		} else if(isset($_SERVER['HTTP_FORWARDED'])) {
			return $_SERVER['HTTP_FORWARDED'];
		} else if(isset($_SERVER['REMOTE_ADDR'])) {
			return $_SERVER['REMOTE_ADDR'];
		}

		return 'UNKNOWN';
	}
	
	static public function textToHtml($content)
	{
		$content = preg_replace('/\r\n|\n/', '<br />', $content);
		return '<html><head><title></title></head><body>'.$content.'</body></html>';
	}
	
	static public function checkFormatDate($date, $inputFormat, $outputFormat = "Y-m-d")
	{
		if (empty($date))
		{
			return FALSE;
		}
		$limiters = array('.', '-', '/');
		foreach ($limiters as $limiter)
		{
			if (strpos($inputFormat, $limiter) !== false)
			{
				$_date = explode($limiter, $date);
				$_iFormat = explode($limiter, $inputFormat);
				$_iFormat = array_flip($_iFormat);
				break;
			}
		}
		if (!isset($_iFormat) || !isset($_date) || count($_date) !== 3)
		{
			return FALSE;
		}else{
			$m = $_date[isset($_iFormat['m']) ? $_iFormat['m'] : $_iFormat['n']];
			$d = $_date[isset($_iFormat['d']) ? $_iFormat['d'] : $_iFormat['j']];
			$y = $_date[$_iFormat['Y']];
			if($m == '' || $d == '' || $y == ''){
				return FALSE;
			}

		}
		return TRUE;
	}
	
	static public function getPostMaxSize()
	{
		$post_max_size = ini_get('post_max_size');
		switch (substr($post_max_size, -1))
		{
			case 'G':
				$post_max_size = (int) $post_max_size * 1024 * 1024 * 1024;
				break;
			case 'M':
				$post_max_size = (int) $post_max_size * 1024 * 1024;
				break;
			case 'K':
				$post_max_size = (int) $post_max_size * 1024;
				break;
		}
		return $post_max_size;
	}
	
	static public function getWeekRange($date, $week_start)
	{
		$week_arr = array(
				0=>'sunday',
				1=>'monday',
				2=>'tuesday',
				3=>'wednesday',
				4=>'thursday',
				5=>'friday',
				6=>'saturday');
			
		$ts = strtotime($date);
		$start = (date('w', $ts) == 0) ? $ts : strtotime('last ' . $week_arr[$week_start], $ts);
		$week_start = ($week_start == 0 ? 6 : $week_start -1);
		return array(date('Y-m-d', $start), date('Y-m-d', strtotime('next ' . $week_arr[$week_start], $start)));
	}
	
	static public function getTimezoneName($timezone)
	{
		$offset = $timezone / 3600;
		$timezone_name = timezone_name_from_abbr(null, $offset * 3600, true);
		if($timezone_name === false)
		{
			$timezone_name = timezone_name_from_abbr(null, $offset * 3600, false);
		}
		if($offset == -12)
		{
			$timezone_name = 'Pacific/Wake';
		}
		return $timezone_name;
	}
	static public function html2txt($document)
	{
		$search = array('@<script[^>]*?>.*?</script>@si',
				'@<[\/\!]*?[^<>]*?>@si',
				'@<style[^>]*?>.*?</style>@siU',
				'@<![\s\S]*?--[ \t\n\r]*>@'
		);
		$text = preg_replace($search, '', $document);
		return $text;
	}
	
	static public function truncateDescription($string, $limit, $break=".", $pad="...")
	{
		if(strlen($string) <= $limit)
			return $string;
		if(false !== ($breakpoint = strpos($string, $break, $limit)))
		{
			if($breakpoint < strlen($string) - 1)
			{
				$string = substr($string, 0, $breakpoint) . $pad;
			}
		}
		return $string;
	}
	
	static public function arrayMergeDistinct ( array &$array1, array &$array2 )
	{
		$merged = $array1;
	
		foreach ( $array2 as $key => &$value )
		{
			if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
			{
				$merged [$key] = pjUtil::arrayMergeDistinct ( $merged [$key], $value );
			}
			else
			{
				$merged [$key] = $value;
			}
		}
	
		return $merged;
	}
	
	static public function sortArrayByArray(Array $array, Array $orderArray) 
	{
		$ordered = array();
		foreach($orderArray as $key)
		{
			if(array_key_exists($key,$array))
			{
				$ordered[$key] = $array[$key];
				unset($array[$key]);
			}
		}
		return $ordered + $array;
	}
	
	public static function getTitles(){
		$arr = array();
		$arr[] = 'mr';
		$arr[] = 'mrs';
		$arr[] = 'ms';
		$arr[] = 'dr';
		$arr[] = 'prof';
		$arr[] = 'rev';
		$arr[] = 'other';
		return $arr;
	}
	
	public static function sqlWeekDays()
	{
		$arr = array();
		$arr[1] = 2;
		$arr[2] = 3;
		$arr[3] = 4;
		$arr[4] = 5;
		$arr[5] = 6;
		$arr[6] = 7;
		$arr[7] = 1;
		return $arr;
	}
	public static function phpWeekDays()
	{
		$arr = array();
		$arr[1] = 7;
		$arr[2] = 1;
		$arr[3] = 2;
		$arr[4] = 3;
		$arr[5] = 4;
		$arr[6] = 5;
		$arr[7] = 6;
		return $arr;
	}
	public static function getWeekDays()
	{
		$arr = array();
		$arr[1] = 'monday';
		$arr[2] = 'tuesday';
		$arr[3] = 'wednesday';
		$arr[4] = 'thursday';
		$arr[5] = 'friday';
		$arr[6] = 'saturday';
		$arr[7] = 'sunday';
		return $arr;
	}
	
	public static function calSeconds($start_time, $end_time)
	{
		$startTimestamp = strtotime($start_time);
		$endTimestamp = strtotime($end_time);
		if($startTimestamp > $endTimestamp)
		{
			$endTimestamp += 86400; 
		}
		return $seconds = $endTimestamp - $startTimestamp;
	}
	
	public static function calDuration($start_time, $end_time)
	{
		$result = array();
		
		list($hours, $minutes, $seconds) = explode(':', $start_time);
		$startTimestamp = mktime($hours, $minutes, $seconds);
		
		list($hours, $minutes, $seconds) = explode(':', $end_time);
		$endTimestamp = mktime($hours, $minutes, $seconds);
		
		$seconds = $endTimestamp - $startTimestamp;
		$minutes = ($seconds / 60) % 60;
		$hours = floor($seconds / (60 * 60));
		
		return compact("seconds", "hours", "minutes");
	}
	
	public static function calDays($start_date, $end_date)
	{
		$startTimeStamp = strtotime($start_date);
		$endTimeStamp = strtotime($end_date);
		
		$timeDiff = abs($endTimeStamp - $startTimeStamp);
		$numberDays = $timeDiff/86400;
		
		$numberDays = intval($numberDays);
		return $numberDays;
	}
	
	public static function checkDateFormat($date)
	{
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date))
	    {
	        return true;
	    }else{
	        return false;
	    }
	}
	
	public static function getMaxUploadedSize()
	{
		$max_upload = (int)(ini_get('upload_max_filesize'));
		$max_post = (int)(ini_get('post_max_size'));
		$memory_limit = (int)(ini_get('memory_limit'));
		$upload_mb = min($max_upload, $max_post, $memory_limit);
		return $upload_mb;
	}
	
	static public function getWeekRangeOfGiveDate($date, $week_start=0)
	{
		$week_arr = array(
			0=>'sunday',
			1=>'monday',
			2=>'tuesday',
			3=>'wednesday',
			4=>'thursday',
			5=>'friday',
			6=>'saturday'
		);
			
		$ts = strtotime($date);
		$start = (date('w', $ts) == $week_start) ? $ts : strtotime('last ' . $week_arr[$week_start], $ts);
		$week_start = ($week_start == 0 ? 6 : $week_start -1);
		$week_sd = date('Y-m-d', $start);
	    $week_ed = date('Y-m-d', strtotime('next ' . $week_arr[$week_start], $start));
	    
		$arr = array();
	    $iso_date = $week_sd;
	    while($iso_date <= $week_ed)
	    {
	        $wday = strtolower(date('l', strtotime($iso_date)));
	        $arr[$wday] = $iso_date;
	        $iso_date = date('Y-m-d', strtotime($iso_date. ' + 1 days'));
	    }
	    return $arr;
	}
	
	public static function changeLangField($i18n_arr, $new_field, $old_field)
	{
		foreach($i18n_arr as $locale => $content)
		{
			$content[$new_field] =  $content[$old_field];
			unset($content[$old_field]);
			$i18n_arr[$locale] = $content;
		}
		return $i18n_arr;
	}
	
	public static function toMomemtJS($format)
	{
		$f = str_replace(
				array('Y', 'm', 'n', 'd', 'j'),
				array('yyyy', 'mm', 'm', 'dd', 'd'),
				$format
		);
	
		return $f;
	}
	
	public static function toBootstrapDate($format)
	{
		return str_replace(
				array('Y', 'm', 'n', 'd', 'j'),
				array('yyyy', 'mm', 'm', 'dd', 'd'),
				$format);
	}
}
?>