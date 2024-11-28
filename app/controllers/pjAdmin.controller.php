<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdmin extends pjAppController
{
	public $defaultUser = 'admin_user';
	
	public $requireLogin = true;
	
	public function __construct($requireLogin=null)
	{
		$this->setLayout('pjActionAdmin');
		
		if (!is_null($requireLogin) && is_bool($requireLogin))
		{
			$this->requireLogin = $requireLogin;
		}
		
		if ($this->requireLogin)
		{
			if (!$this->isLoged() && !in_array(@$_REQUEST['action'], array('pjActionLogin', 'pjActionForgot', 'pjActionPreview', 'pjActionExportFeed')))
			{
				if (!$this->isXHR())
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjBase&action=pjActionLogin");
				} else {
					header('HTTP/1.1 401 Unauthorized');
					exit;
				}
			} elseif ($this->isLoged() && isset($_SESSION[$this->defaultUser]['role_id']) && (int)$_SESSION[$this->defaultUser]['role_id'] == 3) {
			    if (!pjAuth::factory('pjAdmin', 'pjActionDriver')->hasAccess())
			    {
			        $this->sendForbidden();
			        return;
			    } else {
                    pjUtil::redirect(PJ_INSTALL_URL. "driver.php");
			    }
			}
		}
		
		$ref_inherits_arr = array();
		if ($this->isXHR() && isset($_SERVER['HTTP_REFERER'])) {
			$http_refer_arr = parse_url($_SERVER['HTTP_REFERER']);
			parse_str($http_refer_arr['query'], $arr);
			if (isset($arr['controller']) && isset($arr['action'])) {
				parse_str($_SERVER['QUERY_STRING'], $query_string_arr);
				$key = $query_string_arr['controller'].'_'.$query_string_arr['action'];
				$cnt = pjAuthPermissionModel::factory()->where('`key`', $key)->findCount()->getData();
				if ($cnt <= 0) {
					$ref_inherits_arr[$query_string_arr['controller'].'::'.$query_string_arr['action']] = $arr['controller'].'::'.$arr['action'];
				}
			}
		}
		$inherits_arr = array(
			'pjAdminOptions::pjActionUpdateTheme' => 'pjAdminOptions::pjActionPreview',
			'pjBasePermissions::pjActionResetPermission' => 'pjBasePermissions::pjActionUserPermission',
			'pjAdminOptions::pjActionDeleteContentImage' => 'pjAdminOptions::pjActionContent',
		
			'pjAdminCities::pjActionCreate' => 'pjAdminCities::pjActionCreateForm',
			'pjAdminCities::pjActionGetCity' => 'pjAdminCities::pjActionIndex',
			'pjAdminCities::pjActionUpdate' => 'pjAdminCities::pjActionUpdateForm',
			'pjAdminCities::pjActionSaveCity' => 'pjAdminCities::pjActionUpdateForm',
		
			'pjAdminRoutes::pjActionCreate' => 'pjAdminRoutes::pjActionCreateForm',
			'pjAdminRoutes::pjActionGetRoute' => 'pjAdminRoutes::pjActionIndex',
			'pjAdminRoutes::pjActionUpdate' => 'pjAdminRoutes::pjActionUpdateForm',
			'pjAdminRoutes::pjActionSaveRoute' => 'pjAdminRoutes::pjActionUpdateForm',
		
			'pjAdminBusTypes::pjActionGetBusType' => 'pjAdminBusTypes::pjActionIndex',
			'pjAdminBusTypes::pjActionDeleteMap' => 'pjAdminBusTypes::pjActionUpdate',
		
			'pjAdminBuses::pjActionGetBus' => 'pjAdminBuses::pjActionIndex',
			'pjAdminBuses::pjActionGetLocations' => 'pjAdminBuses::pjActionCreate',
			'pjAdminBuses::pjActionGetPriceGrid' => 'pjAdminBuses::pjActionPrice',
			'pjAdminBuses::pjActionGetTickets' => 'pjAdminBuses::pjActionPrice',
			'pjAdminBuses::pjActionCopyPrices' => 'pjAdminBuses::pjActionPrice',
		
			'pjAdminSchedule::pjActionGetSchedule' => 'pjAdminSchedule::pjActionIndex',
			'pjAdminSchedule::pjActionPrintSchedule' => 'pjAdminSchedule::pjActionIndex',
			'pjAdminSchedule::pjActionGetBookings' => 'pjAdminSchedule::pjActionBookings',
			'pjAdminSchedule::pjActionPrintBookings' => 'pjAdminSchedule::pjActionBookings',
			'pjAdminSchedule::pjActionPrintSeats' => 'pjAdminSchedule::pjActionSeats',
			'pjAdminSchedule::pjActionGetTimetable' => 'pjAdminSchedule::pjActionTimetable',			
		);
		if ($_REQUEST['controller'] == 'pjAdminOptions' && isset($_REQUEST['next_action'])) {
			$inherits_arr['pjAdminOptions::pjActionUpdate'] = 'pjAdminOptions::'.$_REQUEST['next_action'];
		}
		$inherits_arr = array_merge($inherits_arr, $ref_inherits_arr);
		pjRegistry::getInstance()->set('inherits', $inherits_arr);
	}
	
	public function beforeFilter()
	{
		parent::beforeFilter();

		if (!pjAuth::factory()->hasAccess() && @$_REQUEST['action'] != 'pjActionExportFeed')
		{
			$this->sendForbidden();
			return false;
		}
		
		return true;
	}
	
	public function afterFilter()
	{
		parent::afterFilter();
		$this->appendJs('index.php?controller=pjBase&action=pjActionMessages', PJ_INSTALL_URL, true);
	}
	
	public function beforeRender()
	{
		
	}
	
	public function pjActionVerifyAPIKey()
    {
        $this->setAjax(true);

        if ($this->isXHR())
        {
            if (!self::isPost())
            {
                self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => __('plugin_base_api_key_text_ARRAY_100', true)));
            }

            $option_key = $this->_post->toString('key');
            if (!array_key_exists($option_key, $this->option_arr))
            {
                self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => __('plugin_base_api_key_text_ARRAY_101', true)));
            }

            $option_value = $this->_post->toString('value');
            if(empty($option_value))
            {
                self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => __('plugin_base_api_key_text_ARRAY_102', true)));
            }

            $html = '';
            $isValid = false;
            switch ($option_key)
            {
                case 'o_google_maps_api_key':
                    $address = preg_replace('/\s+/', '+', $this->option_arr['o_timezone']);
                    $api_key_str = $option_value;
                    $gfile = "https://maps.googleapis.com/maps/api/geocode/json?key=".$api_key_str."&address=".$address;
                    $Http = new pjHttp();
                    $response = $Http->request($gfile)->getResponse();
                    $geoObj = pjAppController::jsonDecode($response);
                    $geoArr = (array) $geoObj;
                    if ($geoArr['status'] == 'OK')
                    {
                        $isValid = true;
                    }
                    break;
                default:
                    // API key for an unknown service. We can't verify it so we assume it's correct.
                    $isValid = true;
            }

            if ($isValid)
            {
                self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => __('plugin_base_api_key_text_ARRAY_200', true), 'html' => $html));
            }
            else
            {
                self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => __('plugin_base_api_key_text_ARRAY_103', true), 'html' => $html));
            }
        }
        exit;
    }
		
	public function pjActionIndex()
	{
		$this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
		
		$pjBookingModel = pjBookingModel::factory();
		$pjBusModel = pjBusModel::factory();
		$pjRouteModel = pjRouteModel::factory();
			
		$first_date_of_month = date('Y-m-01');
	    $last_date_of_month = date('Y-m-t');
	    $cnt_bookings_today = $total_amount_today = $cnt_bookings_this_month = $total_amount_this_month = 0;
	    
	    $current_date = date('Y-m-d');
		$weekday = strtolower(date('l'));
		
		$cnt_today_departure = 0;
		$next_buses_arr = array();
		$date = $current_date;
	    
	    $pjBookingModel
			->where('DATE(t1.created) BETWEEN "'.$first_date_of_month.'" AND "'.$last_date_of_month.'"')
			->whereIn('t1.status', array('confirmed','pending'));
		$bookings_today = $pjBookingModel->orderBy('t1.created DESC')->findAll()->getData();	
		foreach ($bookings_today as $val) {
			$total = $val['total'];
			if (date('Y-m-d', strtotime($val['created'])) == date('Y-m-d')) {
				$cnt_bookings_today += 1;
				$total_amount_today += $total;
			}
			$cnt_bookings_this_month += 1;
			$total_amount_this_month += $total;
		}	
		
		$cnt_buses = $pjBusModel->findCount()->getData();
		$this->set('cnt_bookings_today', $cnt_bookings_today)
			->set('total_amount_today', $total_amount_today)
			->set('cnt_bookings_this_month', $cnt_bookings_this_month)
			->set('total_amount_this_month', $total_amount_this_month)
			->set('total_bookings', $pjBookingModel->reset()->findCount()->getData())
			->set('cnt_buses', $cnt_buses)
			->set('cnt_routes', $pjRouteModel->where('t1.status', 'T')->findCount()->getData());
			
		$next_3_months = strtotime('+3 month', strtotime($date));			
		if($cnt_buses > 0)
		{
			while(count($next_buses_arr) < 5 && strtotime($date) < $next_3_months)
			{
				$bus_arr = $pjBusModel
					->reset()
					->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.route_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->select("t1.*, t2.content AS route,
								(SELECT COUNT(TB.id) 
									FROM `".$pjBookingModel->getTable()."` AS TB 
									WHERE TB.bus_id=t1.id AND TB.booking_date='$date') AS total_bookings,
								(
									SELECT SUM(TBT.qty) 
									FROM `".pjBookingTicketModel::factory()->getTable()."` AS TBT 
									WHERE 
										TBT.booking_id IN (
											SELECT TB1.id 
											FROM `".$pjBookingModel->getTable()."` AS TB1
											WHERE TB1.bus_id=t1.id AND TB1.booking_date='$date' AND (TB1.status='confirmed' OR (TB1.status='pending' AND UNIX_TIMESTAMP(TB1.created) >= ".(time() - $this->option_arr['o_min_hour'] * 60)."))
										)
								) AS total_tickets")
					->where("(t1.start_date <= '$date' AND t1.end_date >= '$date')")
					->orderBy("departure_time ASC")
					->findAll()
					->getData();
				foreach($bus_arr as $v)
				{
					if(empty($v['recurring']))
					{
						if($date == $current_date)
						{
							$cnt_today_departure++;
						}
						if(count($next_buses_arr) < 5 && strtotime(date('Y-m-d H:i:s')) <= strtotime($date . ' ' . $v['departure_time']))
						{
							$v['departure_date'] = $date;
							$next_buses_arr[] = $v;
						}
					}else{
						if(in_array($weekday, explode("|", $v['recurring'])))
						{
							if($date == $current_date)
							{
								$cnt_today_departure++;
							}
							if(count($next_buses_arr) < 5 && strtotime(date('Y-m-d H:i:s')) <= strtotime($date . ' ' . $v['departure_time']))
							{
								$v['departure_date'] = $date;
								$next_buses_arr[] = $v;
							}
						}
					}
				}
				$date = date('Y-m-d', strtotime($date . ' + 1 day'));
			}
		}
		$this->set('cnt_today_departure', $cnt_today_departure);
		$latest_bookings = $pjBookingModel
			->reset()
			->select("t1.*, (SELECT SUM(TBT.qty) 
							FROM `".pjBookingTicketModel::factory()->getTable()."` AS TBT 
							WHERE TBT.booking_id=t1.id)
					 	AS tickets, t2.departure_time, t2.arrival_time, t3.content as route_title, t4.content as from_location, t5.content as to_location")
		 	->join('pjBus', "t2.id=t1.bus_id", 'left outer')
		 	->join('pjMultiLang', "t3.model='pjRoute' AND t3.foreign_id=t2.route_id AND t3.field='title' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
		 	->join('pjMultiLang', "t4.model='pjCity' AND t4.foreign_id=t1.pickup_id AND t4.field='name' AND t4.locale='".$this->getLocaleId()."'", 'left outer')
		 	->join('pjMultiLang', "t5.model='pjCity' AND t5.foreign_id=t1.return_id AND t5.field='name' AND t5.locale='".$this->getLocaleId()."'", 'left outer')
			->limit(5)
			->orderBy('t1.created DESC')
			->findAll()->getData();
		
		$this->set('latest_bookings', $latest_bookings);
		$this->set('next_buses_arr', $next_buses_arr);
		
		$this->appendCss('dashboard.css');
	}
	
	public function pjActionDriver()
	{
	    $this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    pjUtil::redirect(PJ_INSTALL_URL. "driver.php");
	}
}
?>