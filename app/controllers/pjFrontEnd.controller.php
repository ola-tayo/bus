<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFrontEnd extends pjFront
{
	public function __construct()
	{
		parent::__construct();
		$this->setAjax(true);
		$this->setLayout('pjActionEmpty');
	}

	public function pjActionLoad()
	{
		$this->setAjax(false);
	    $this->setLayout('pjActionFront');
	    
	    ob_start();
	    header("Content-Type: text/javascript; charset=utf-8");
	    if($this->_get->toInt('hide') > 0)
	    {
	        $this->session->setData($this->defaultLangMenu, 'hide');
	    }else{
	        $this->session->setData($this->defaultLangMenu, 'show');
		}
		if (!$this->session->getData($this->defaultLocale))
	    {
	        $this->session->setData($this->defaultLocale, $this->_get->toInt('locale'));
	    }
	}
	
	public function pjActionLoadCss()
	{
		$dm = new pjDependencyManager(PJ_INSTALL_PATH, PJ_THIRD_PARTY_PATH);
		$dm->load(PJ_CONFIG_PATH . 'dependencies.php')->resolve();
		
		$theme = $this->option_arr['o_theme'];
		if($this->_get->check('theme') && in_array($this->_get->toString('theme'), array('theme1','theme2','theme3','theme4','theme5','theme6','theme7','theme8','theme9','theme10')))
		{
			$theme = $this->_get->toString('theme');
		}
		$theme = str_replace("theme", "", $theme);
		$theme = 'theme' . $theme;
		$fonts = $theme; 
		$arr = array(
				array('file' => "$fonts.css", 'path' => PJ_CSS_PATH . "fonts/"),
				array('file' => 'font-awesome.min.css', 'path' => $dm->getPath('font_awesome')),
				array('file' => 'perfect-scrollbar.min.css', 'path' => $dm->getPath('pj_perfect_scrollbar')),
				array('file' => 'select2.min.css', 'path' => $dm->getPath('pj_select2')),
				array('file' => 'bootstrap-datetimepicker.min.css', 'path' => $dm->getPath('pj_bootstrap_datetimepicker')),
				array('file' => "style.css", 'path' => PJ_CSS_PATH),
				array('file' => "$theme.css", 'path' => PJ_CSS_PATH . "themes/"),
				array('file' => 'transitions.css', 'path' => PJ_CSS_PATH)
		);
		
		header("Content-Type: text/css; charset=utf-8");
		foreach ($arr as $item)
		{
			$string = FALSE;
			if ($stream = fopen($item['path'] . $item['file'], 'rb'))
			{
				$string = stream_get_contents($stream);
				fclose($stream);
			}
			
			if ($string !== FALSE)
			{
				echo str_replace(
					array('../fonts/fontawesome', 'pjWrapper'),
					array(
						PJ_INSTALL_URL . $dm->getPath('font_awesome') . 'fonts/fontawesome',
						"pjWrapperBusReservation_" . $theme),
					$string
				) . "\n";
			}
		}
		exit;
	}
	
	public function pjActionCaptcha()
	{
	    $this->setAjax(true);
	    
	    header("Cache-Control: max-age=3600, private");
	    $rand = $this->_get->toInt('rand') ?: rand(1, 9999);
	    $patterns = 'app/web/img/button.png';
	    if(!empty($this->option_arr['o_captcha_background_front']) && $this->option_arr['o_captcha_background_front'] != 'plain')
	    {
	        $patterns = PJ_INSTALL_PATH . $this->getConstant('pjBase', 'PLUGIN_IMG_PATH') . 'captcha_patterns/' . $this->option_arr['o_captcha_background_front'];
	    }
	    $Captcha = new pjCaptcha(PJ_INSTALL_PATH . $this->getConstant('pjBase', 'PLUGIN_WEB_PATH') . 'obj/arialbd.ttf', $this->defaultCaptcha, (int) $this->option_arr['o_captcha_length_front']);
	    $Captcha->setImage($patterns)->setMode($this->option_arr['o_captcha_mode_front'])->init($rand);
	    exit;
	}
	
	public function pjActionCheckCaptcha()
	{
	    $this->setAjax(true);
	    if (!$this->_get->check('captcha') || !$this->_get->toString('captcha') || strtoupper($this->_get->toString('captcha')) != $_SESSION[$this->defaultCaptcha]){
	        echo 'false';
	    }else{
	        echo 'true';
	    }
	    exit;
	}
	
	public function pjActionCheckReCaptcha()
	{
	    $this->setAjax(true);
	    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$this->option_arr['o_captcha_secret_key_front'].'&response='.$this->_get->toString('recaptcha'));
	    $responseData = json_decode($verifyResponse);
	    echo $responseData->success ? 'true': 'false';
	    exit;
	}

	public function pjActionCheck()
	{
		if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
		{
			$resp = array();
			$return_bus_id_arr = array();

			if($this->_get->check('pickup_id') && $this->_get->check('return_id') && $this->_get->toInt('pickup_id') != $this->_get->toInt('return_id'))
			{
				$resp['code'] = 200;
	
				$pjBusModel = pjBusModel::factory();
	
				$pickup_id = $this->_get->toInt('pickup_id');
				$return_id = $this->_get->toInt('return_id');
	
				$date = pjDateTime::formatDate($this->_get->toString('date'), $this->option_arr['o_date_format']);
				if($this->_get->check('final_check'))
				{
					$date = pjDateTime::formatDate($this->_get('date'), $this->option_arr['o_date_format']);
				} else {
					$this->_set('date', $this->_get->toString('date'));
				}

				$bus_id_arr = $pjBusModel->getBusIds($date, $pickup_id, $return_id);
				if(empty($bus_id_arr))
				{
					$resp['code'] = 100;
					if(!$this->_get->check('final_check'))
					{
						if($this->_is('bus_id_arr'))
						{
							unset($_SESSION[$this->defaultStore]['bus_id_arr']);
						}
					}
					pjAppController::jsonResponse($resp);
				}
	
				if ($this->_get->check('is_return') && $this->_get->toString('is_return') == 'T')
				{
					$pickup_id = $this->_get->toInt('return_id');
					$return_id = $this->_get->toInt('pickup_id');
						
					$date = pjDateTime::formatDate($this->_get->toString('return_date'), $this->option_arr['o_date_format']);
					$return_bus_id_arr = $pjBusModel->getBusIds($date, $pickup_id, $return_id);
					if(!$this->_get->check('final_check')) {
						$this->_set('return_date', $this->_get->toString('return_date'));	
					}
					if(empty($return_bus_id_arr))
					{
						$resp['code'] = 101;
						if(!$this->_get->check('final_check'))
						{
							if($this->_is('return_bus_id_arr'))
							{
								unset($_SESSION[$this->defaultStore]['return_bus_id_arr']);
							}
						}
						pjAppController::jsonResponse($resp);
					}
				}else{
					if(!$this->_get->check('final_check'))
					{
						if($this->_is('return_bus_id_arr'))
						{
							unset($_SESSION[$this->defaultStore]['return_bus_id_arr']);
						}
						if($this->_is('return_date'))
						{
							unset($_SESSION[$this->defaultStore]['return_date']);
						}
					}
				}
	
				if(!$this->_get->check('final_check'))
				{
					$this->_set('pickup_id', $this->_get->toInt('pickup_id'));
					$this->_set('return_id', $this->_get->toInt('return_id'));
					$this->_set('bus_id_arr', $bus_id_arr);
					$this->_set('is_return', $this->_get->toString('is_return'));
	
					if ($this->_get->check('is_return') && $this->_get->toString('is_return') == 'T')
					{
						$this->_set('return_bus_id_arr', $return_bus_id_arr);
					}
					if($this->_is('booked_data'))
					{
						unset($_SESSION[$this->defaultStore]['booked_data']);
					}
					if($this->_is('bus_id'))
					{
						unset($_SESSION[$this->defaultStore]['bus_id']);
					}
					$resp['code'] = 200;
					pjAppController::jsonResponse($resp);
				}else{
					$STORE = @$_SESSION[$this->defaultStore];
					$avail_arr = $this->getBusAvailability($STORE['booked_data']['bus_id'], $STORE, $this->option_arr);
					$booked_seat_arr = $avail_arr['booked_seat_arr'];
					$seat_id_arr = explode("|", $STORE['booked_data']['selected_seats']);
					$intersect = array_intersect($booked_seat_arr, $seat_id_arr);
					if(!empty($intersect))
					{
						$resp['code'] = 100;
					}else{
						$resp['code'] = 200;
					}
					pjAppController::jsonResponse($resp);
				}
			}
			pjAppController::jsonResponse($resp);
		}
	}
	
	public function pjActionSaveTickets()
	{
		$this->setAjax(true);
		$resp = array();
		$resp['code'] = 200;
		$this->_set('booked_data', $this->_post->raw());
		pjAppController::jsonResponse($resp);
	}
	
	public function pjActionSaveForm()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (!isset($_SESSION[$this->defaultForm]) || count($_SESSION[$this->defaultForm]) === 0)
			{
				$_SESSION[$this->defaultForm] = array();
			}
			if($this->_post->check('step_checkout')){
				if ((int) $this->option_arr['o_bf_include_captcha'] === 3)
				{
					if($this->option_arr['o_captcha_type_front'] == 'system')
				    {
				    	if (!$this->_post->check('captcha') || !pjCaptcha::validate($this->_post->toString('captcha'), $_SESSION[$this->defaultCaptcha])) {
				     		pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 110, 'text' => __('system_212', true)));  	
				       	}
				    }else{
				    	if (!$this->_post->check('recaptcha') || !pjValidation::pjActionNotEmpty($this->_post->toString('recaptcha'))) {
				     		pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 110, 'text' => __('system_212', true)));  	
				       	}
				    }
				}
				$_SESSION[$this->defaultForm] = $this->_post->raw();
			}
	        
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 211, 'text' => __('system_211', true)));
		}
	}
		
	public function pjActionSaveBooking() {
		$this->setAjax ( true );
		
		if ($this->isXHR ()) {
			
			$STORE = @$_SESSION [$this->defaultStore];
			$FORM = @$_SESSION [$this->defaultForm];
			$booked_data = @$STORE ['booked_data'];
			
			$pjBookingModel = pjBookingModel::factory ();
			
			$bus_id = $booked_data ['bus_id'];
			$return_bus_id = isset ( $booked_data ['return_bus_id'] ) ? $booked_data ['return_bus_id'] : 0;
			$pickup_id = $this->_get ( 'pickup_id' );
			$return_id = $this->_get ( 'return_id' );
			$is_return = $this->_get ( 'is_return' );
			
			$depart_arrive = '';
			$depart_time = null;
			
			$bus_arr = pjBusModel::factory ()->join ( 'pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.route_id AND t2.field='title' AND t2.locale='" . $this->getLocaleId () . "'", 'left outer' )->join ( 'pjBusType', "t3.id=t1.bus_type_id", 'left' )->select ( 't1.*, t3.seats_map, t2.content as route' )->find ( $bus_id )->getData ();
			if (! empty ( $bus_arr ['departure_time'] ) && ! empty ( $bus_arr ['arrival_time'] )) {
				$depart_arrive = pjDateTime::formatTime (date('H:i:s', strtotime($bus_arr ['departure_time'])), "H:i:s", $this->option_arr ['o_time_format'] ) . ' - ' . pjDateTime::formatTime (date('H:i:s', strtotime($bus_arr ['arrival_time'])), "H:i:s", $this->option_arr ['o_time_format'] );
				$depart_time = $bus_arr ['departure_time'];
			}
			
			$pjCityModel = pjCityModel::factory ();
			$pickup_location = $pjCityModel->reset ()->select ( 't1.*, t2.content as name' )->join ( 'pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='" . $this->getLocaleId () . "'", 'left outer' )->find ( $pickup_id )->getData ();
			$return_location = $pjCityModel->reset ()->select ( 't1.*, t2.content as name' )->join ( 'pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='" . $this->getLocaleId () . "'", 'left outer' )->find ( $return_id )->getData ();
			$from_location = $pickup_location ['name'];
			$to_location = $return_location ['name'];
			
			$data = array ();
			$data ['bus_id'] = $bus_id;
			$data ['uuid'] = time ();
			$data ['ip'] = pjUtil::getClientIp ();
			$data['booking_date'] = pjDateTime::formatDate($this->_get ( 'date' ), $this->option_arr['o_date_format']);
			if ($is_return == 'T') {
				$data['return_date'] = pjDateTime::formatDate($this->_get ( 'return_date' ), $this->option_arr['o_date_format']);
			}
			$data ['booking_datetime'] = $data ['booking_date'];
			if (isset ( $STORE ['booking_period'] [$bus_id] )) {
				$data ['booking_datetime'] = $STORE ['booking_period'] [$bus_id] ['departure_time'];
				$data ['stop_datetime'] = $STORE ['booking_period'] [$bus_id] ['arrival_time'];
			}
			$data ['status'] = $this->option_arr ['o_booking_status'];
			
			$data['bus_departure_date'] = $data ['booking_date'];
			$depart_date_time_iso = pjDateTime::formatDate ( $this->_get ( 'date' ), $this->option_arr ['o_date_format'] ) . ' ' . pjDateTime::formatTime (date('H:i:s', strtotime($depart_time)), "H:i:s", $this->option_arr ['o_time_format'] );
			if($depart_date_time_iso > $data ['booking_datetime'])
			{
				$data['bus_departure_date'] = date('Y-m-d', strtotime($depart_date_time_iso) - 86400);
			}
			$payment = 'none';
			if (isset ( $FORM ['payment_method'] )) {
				if ($FORM ['payment_method'] && $FORM ['payment_method'] == 'creditcard') {
					$data ['cc_exp'] = $FORM ['cc_exp_year'] . '-' . $FORM ['cc_exp_month'];
				}
				
				if ($FORM ['payment_method']) {
					$payment = $FORM ['payment_method'];
				}
			}
			
			$bt_arr = array ();
			$pjBusLocationModel = pjBusLocationModel::factory ();
			$_arr = $pjBusLocationModel->where ( 'bus_id', $bus_id )->where ( "location_id", $pickup_id )->limit ( 1 )->findAll ()->getData ();
			if (count ( $_arr ) > 0) {
				$bt_arr [] = pjDateTime::formatTime (date('H:i:s', strtotime($_arr [0] ['departure_time'])), "H:i:s", $this->option_arr ['o_time_format'] );
				$data ['booking_datetime'] .= ' ' . $_arr [0] ['departure_time'];
			}
			
			$_arr = $pjBusLocationModel->reset ()->where ( 'bus_id', $bus_id )->where ( "location_id", $return_id )->limit ( 1 )->findAll ()->getData ();
			if (count ( $_arr ) > 0) {
				$bt_arr [] = pjDateTime::formatTime (date('H:i:s', strtotime($_arr [0] ['arrival_time'])), "H:i:s", $this->option_arr ['o_time_format'] );
			}
			$data ['booking_time'] = join ( " - ", $bt_arr );
			$data ['pickup_id'] = $pickup_id;
			$data ['return_id'] = $return_id;
			$data ['is_return'] = $is_return;
			$data ['booking_route'] = $bus_arr ['route'] . ', ' . $depart_arrive . '<br/>';
			
			$data ['booking_route'] .= __ ( 'front_from', true, false ) . ' ' . $from_location . ' ' . __ ( 'front_to', true, false ) . ' ' . $to_location;
			
			$pjPriceModel = pjPriceModel::factory ();
			$ticket_price_arr = $pjPriceModel->getTicketPrice($bus_id, $pickup_id, $return_id, $booked_data, $this->option_arr, $this->getLocaleId(), 'F');
			
			$data ['sub_total'] = $ticket_price_arr['sub_total'];
			$data ['tax'] = $ticket_price_arr['tax'];
			$data ['total'] = $ticket_price_arr['total'];
			$data ['deposit'] = $ticket_price_arr['deposit'];
			
			$id = $pjBookingModel->setAttributes ( array_merge ( $FORM, $data ) )->insert ()->getInsertId ();
			
			if ($id !== false && ( int ) $id > 0) {
			    $client_name_arr = array();
			    if (isset($FORM['c_fname']) && !empty($FORM['c_fname'])) {
			        $client_name_arr[] = pjSanitize::clean($FORM['c_fname']);
			    }
			    if (isset($FORM['c_lname']) && !empty($FORM['c_lname'])) {
			        $client_name_arr[] = pjSanitize::clean($FORM['c_lname']);
			    }
			    $client_name = implode(' ', $client_name_arr);
			    $client_phone = isset($FORM['c_phone']) && !empty($FORM['c_phone']) ? pjSanitize::clean($FORM['c_phone']) : '';
			    
				$back_insert_id = 0;
				if ($is_return == 'T') {
					$child_bus_arr = pjBusModel::factory ()->join ( 'pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.route_id AND t2.field='title' AND t2.locale='" . $this->getLocaleId () . "'", 'left outer' )->join ( 'pjBusType', "t3.id=t1.bus_type_id", 'left' )->select ( 't1.*, t3.seats_map, t2.content as route' )->find ( $return_bus_id )->getData ();
					
					if (! empty ( $child_bus_arr ['departure_time'] ) && ! empty ( $child_bus_arr ['arrival_time'] )) {
						$depart_arrive = pjDateTime::formatTime (date('H:i:s', strtotime($child_bus_arr ['departure_time'])), "H:i:s", $this->option_arr ['o_time_format'] ) . ' - ' . pjDateTime::formatTime (date('H:i:s', strtotime($child_bus_arr ['arrival_time'])), "H:i:s", $this->option_arr ['o_time_format'] );
					}
					$bt_arr = array ();
					$pjBusLocationModel = pjBusLocationModel::factory ();
					$_arr = $pjBusLocationModel->where ( 'bus_id', $child_bus_arr ['id'] )->where ( "location_id", $return_id )->limit ( 1 )->findAll ()->getData ();
					if (count ( $_arr ) > 0) {
						$bt_arr [] = pjDateTime::formatTime (date('H:i:s', strtotime($_arr [0] ['departure_time'])), "H:i:s", $this->option_arr ['o_time_format'] );
						$data ['booking_datetime'] .= ' ' . $_arr [0] ['departure_time'];
					}
					
					$_arr = $pjBusLocationModel->reset ()->where ( 'bus_id', $child_bus_arr ['id'] )->where ( "location_id", $pickup_id )->limit ( 1 )->findAll ()->getData ();
					if (count ( $_arr ) > 0) {
						$bt_arr [] = pjDateTime::formatTime (date('H:i:s', strtotime($_arr [0] ['arrival_time'])), "H:i:s", $this->option_arr ['o_time_format'] );
					}
					$data ['booking_time'] = join ( " - ", $bt_arr );
					
					$data ['booking_route'] = $child_bus_arr ['route'] . ', ' . $depart_arrive . '<br/>';
					$data ['booking_route'] .= __ ( 'front_from', true, false ) . ' ' . $to_location . ' ' . __ ( 'front_to', true, false ) . ' ' . $from_location;
					$data ['booking_date'] = pjDateTime::formatDate ( $this->_get ( 'return_date' ), $this->option_arr ['o_date_format'], 'Y-m-d');
					if (isset ( $STORE ['booking_period'] [$return_bus_id] )) {
						$data ['booking_datetime'] = $STORE ['booking_period'] [$return_bus_id] ['departure_time'];
						$data ['stop_datetime'] = $STORE ['booking_period'] [$return_bus_id] ['arrival_time'];
					}
					unset ( $data ['return_date'] );
					unset ( $data ['is_return'] );
					
					$data ['bus_id'] = $return_bus_id;
					$data ['uuid'] = time () + 1;
					$data ['pickup_id'] = $return_id;
					$data ['return_id'] = $pickup_id;
										
					$return_ticket_price_arr = $pjPriceModel->getTicketPrice($return_bus_id, $return_id, $pickup_id, $booked_data, $this->option_arr, $this->getLocaleId(), 'T');
					
					$data ['sub_total'] = isset($return_ticket_price_arr['sub_total']) ? $return_ticket_price_arr['sub_total'] : 0;
					$data ['tax'] = isset($return_ticket_price_arr['tax']) ? $return_ticket_price_arr['tax'] : 0;
					$data ['total'] = isset($return_ticket_price_arr['total']) ? $return_ticket_price_arr['total'] : 0;
					$data ['deposit'] = isset($return_ticket_price_arr['deposit']) ? $return_ticket_price_arr['deposit'] : 0;
					
					$back_insert_id = $pjBookingModel->reset ()->setAttributes ( array_merge ( $FORM, $data ) )->insert ()->getInsertId ();
					if ($back_insert_id !== false && ( int ) $back_insert_id > 0) {
						$pjBookingModel->reset ()->set ( 'id', $id )->modify ( array (
								'back_id' => $back_insert_id 
						) );
						
						$pjBookingModel->reset ()->set ( 'id', $back_insert_id )->modify ( array (
								'back_id' => $id 
						) );
					}
				}
				
				$ticket_arr = pjPriceModel::factory ()->select ( "t1.*" )->where ( 't1.bus_id', $bus_id )->where ( 't1.from_location_id', $pickup_id )->where ( 't1.to_location_id', $return_id )->where ( 'is_return = "F"' )->findAll ()->getData ();
				
				$location_arr = pjRouteCityModel::factory ()->getLocations ( $bus_arr ['route_id'], $pickup_id, $return_id );
				$location_pair = array ();
				for($i = 0; $i < count ( $location_arr ); $i ++) {
					$j = $i + 1;
					if ($j < count ( $location_arr )) {
						$location_pair [] = $location_arr [$i] ['city_id'] . '-' . $location_arr [$j] ['city_id'];
					}
				}
				$pjBookingTicketModel = pjBookingTicketModel::factory ();
				foreach ( $ticket_arr as $k => $v ) {
					if (isset ( $booked_data ['ticket_cnt_' . $v ['ticket_id']] ) && $booked_data ['ticket_cnt_' . $v ['ticket_id']] > 0) {
						$data = array ();
						$data ['booking_id'] = $id;
						$data ['ticket_id'] = $v ['ticket_id'];
						$data ['qty'] = $booked_data ['ticket_cnt_' . $v ['ticket_id']];
						$data ['amount'] = $data ['qty'] * $v ['price'];
						$data ['is_return'] = 'F';
						$pjBookingTicketModel->reset ()->setAttributes ( $data )->insert ();
					}
				}
				
				$pjBookingSeatModel = pjBookingSeatModel::factory ();
				
				$seat_id_arr = explode ( "|", $booked_data ['selected_seats'] );
				$tmp_qr_code_arr = array();
				foreach ( $location_pair as $pair ) {
					$_arr = explode ( "-", $pair );
					$k = 0;
					foreach ( $ticket_arr as $j => $v ) {
						if (isset ( $booked_data ['ticket_cnt_' . $v ['ticket_id']] ) && $booked_data ['ticket_cnt_' . $v ['ticket_id']] > 0) {
							$qty = $booked_data ['ticket_cnt_' . $v ['ticket_id']];
							if ($qty > 0) {
								for($i = 1; $i <= $qty; $i ++) {
								    if (isset($tmp_qr_code_arr[$seat_id_arr[$k]])) {
								        $qr_code = $tmp_qr_code_arr[$seat_id_arr[$k]];
								    } else {
								        $qr_code = pjUtil::uuid();
								        $tmp_qr_code_arr[$seat_id_arr[$k]] = $qr_code;
								    }
								    
									$data = array ();
									$data ['booking_id'] = $id;
									$data ['seat_id'] = $seat_id_arr [$k];
									$data ['ticket_id'] = $v ['ticket_id'];									
									$data ['start_location_id'] = $_arr [0];
									$data ['end_location_id'] = $_arr [1];
									$data ['is_return'] = 'F';
									$data ['qr_code'] = $qr_code;
									$booking_seat_id = $pjBookingSeatModel->reset ()->setAttributes ( $data )->insert ()->getInsertId();
									if ($booking_seat_id !== false && (int)$booking_seat_id > 0) {
									    $qr_code_arr = array();
									    $qr_code_arr[] = $qr_code;
									    if (!empty($client_name)) {
									        $qr_code_arr[] = $client_name;
									    }
									    if (!empty($client_phone)) {
									        $qr_code_arr[] = $client_phone;
									    }
									    $qr_code_text = implode(' | ', $qr_code_arr);
									    $this->generateQRCode($qr_code_text, $qr_code);
									}
									$k ++;
								}
							}
						}
					}
				}
				
				if ($is_return == 'T') {
					$ticket_arr = pjPriceModel::factory ()->select ( "t1.*, t2.discount" )->join ( 'pjBus', 't1.bus_id = t2.id', 'left' )->where ( 't1.bus_id', $return_bus_id )->where ( 't1.from_location_id', $return_id )->where ( 't1.to_location_id', $pickup_id )->where ( 'is_return = "F"' )->findAll ()->getData ();
					
					$location_arr = pjRouteCityModel::factory ()->getLocations ( $bus_arr ['route_id'], $pickup_id, $return_id );
					$location_pair = array ();
					for($i = 0; $i < count ( $location_arr ); $i ++) {
						$j = $i + 1;
						if ($j < count ( $location_arr )) {
							$location_pair [] = $location_arr [$i] ['city_id'] . '-' . $location_arr [$j] ['city_id'];
						}
					}
					$pjBookingTicketModel = pjBookingTicketModel::factory ();
					foreach ( $ticket_arr as $k => $v ) {
						if (isset ( $booked_data ['return_ticket_cnt_' . $v ['ticket_id']] ) && $booked_data ['return_ticket_cnt_' . $v ['ticket_id']] > 0) {
							$price = $v ['price'] - ($v ['price'] * $v ['discount'] / 100);
							$data = array ();
							$data ['booking_id'] = $back_insert_id;
							$data ['ticket_id'] = $v ['ticket_id'];
							$data ['qty'] = $booked_data ['return_ticket_cnt_' . $v ['ticket_id']];
							$data ['amount'] = $data ['qty'] * $price;
							$data ['is_return'] = 'T';
							$pjBookingTicketModel->reset ()->setAttributes ( $data )->insert ();
						}
					}
					
					$seat_id_arr = explode ( "|", $booked_data ['return_selected_seats'] );
					$tmp_qr_code_arr = array();
					foreach ( $location_pair as $pair ) {
						$_arr = explode ( "-", $pair );
						$kk = 0;
						foreach ( $ticket_arr as $j => $v ) {
							if (isset ( $booked_data ['return_ticket_cnt_' . $v ['ticket_id']] ) && $booked_data ['return_ticket_cnt_' . $v ['ticket_id']] > 0) {
								$qty = $booked_data ['return_ticket_cnt_' . $v ['ticket_id']];
								if ($qty > 0) {
									for($i = 1; $i <= $qty; $i ++) {
									    if (isset($tmp_qr_code_arr[$seat_id_arr[$kk]])) {
									        $qr_code = $tmp_qr_code_arr[$seat_id_arr[$kk]];
									    } else {
									        $qr_code = pjUtil::uuid();
									        $tmp_qr_code_arr[$seat_id_arr[$kk]] = $qr_code;
									    }
									    
										$data = array ();
										$data ['booking_id'] = $back_insert_id;
										$data ['seat_id'] = $seat_id_arr [$kk];
										$data ['ticket_id'] = $v ['ticket_id'];										
										$data ['start_location_id'] = $_arr [1];
										$data ['end_location_id'] = $_arr [0];
										$data ['is_return'] = 'T';
										$data ['qr_code'] = $qr_code;
										$return_booking_seat_id = $pjBookingSeatModel->reset ()->setAttributes ( $data )->insert ()->getInsertId();
										if ($return_booking_seat_id !== false && (int)$return_booking_seat_id > 0) {
										    $qr_code_arr = array();
										    $qr_code_arr[] = $qr_code;
										    if (!empty($client_name)) {
										        $qr_code_arr[] = $client_name;
										    }
										    if (!empty($client_phone)) {
										        $qr_code_arr[] = $client_phone;
										    }
										    $qr_code_text = implode(' | ', $qr_code_arr);
										    $this->generateQRCode($qr_code_text, $qr_code);
										}
										$kk ++;
									}
								}
							}
						}
					}
				}
				
				$arr = $pjBookingModel->reset ()->select ( 't1.*, t2.departure_time, t2.arrival_time, t3.content as route_title, t4.content as from_location, t5.content as to_location,
					AES_DECRYPT(t1.cc_type, "'.PJ_SALT.'") AS `cc_type`,	
					AES_DECRYPT(t1.cc_num, "'.PJ_SALT.'") AS `cc_num`,
					AES_DECRYPT(t1.cc_exp, "'.PJ_SALT.'") AS `cc_exp`,
					AES_DECRYPT(t1.cc_code, "'.PJ_SALT.'") AS `cc_code`
				' )->join ( 'pjBus', "t2.id=t1.bus_id", 'left outer' )->join ( 'pjMultiLang', "t3.model='pjRoute' AND t3.foreign_id=t2.route_id AND t3.field='title' AND t3.locale='" . $this->getLocaleId () . "'", 'left outer' )->join ( 'pjMultiLang', "t4.model='pjCity' AND t4.foreign_id=t1.pickup_id AND t4.field='name' AND t4.locale='" . $this->getLocaleId () . "'", 'left outer' )->join ( 'pjMultiLang', "t5.model='pjCity' AND t5.foreign_id=t1.return_id AND t5.field='name' AND t5.locale='" . $this->getLocaleId () . "'", 'left outer' )->find ( $id )->getData ();
				
				$tickets = pjBookingTicketModel::factory ()->join ( 'pjMultiLang', "t2.model='pjTicket' AND t2.foreign_id=t1.ticket_id AND t2.field='title' AND t2.locale='" . $this->getLocaleId () . "'", 'left outer' )->join ( 'pjTicket', "t3.id=t1.ticket_id", 'left' )->select ( 't1.*, t2.content as title' )->where ( 'booking_id', $arr ['id'] )->findAll ()->getData ();
				
				$arr ['tickets'] = $tickets;
				
				$payment_data = array ();
				$payment_data ['booking_id'] = $arr ['id'];
				$payment_data ['payment_method'] = $payment;
				$payment_data ['payment_type'] = 'online';
				$payment_data ['amount'] = $arr ['deposit'];
				$payment_data ['status'] = 'notpaid';
				pjBookingPaymentModel::factory ()->setAttributes ( $payment_data )->insert ();
				
				pjFrontEnd::pjActionConfirmSend ( $this->option_arr, $arr, PJ_SALT, 'confirm', $this->getLocaleId());
				
				if ($is_return == 'T') {
					$return_arr = $pjBookingModel->reset ()->select ( 't1.*, t2.departure_time, t2.arrival_time, t3.content as route_title, t4.content as from_location, t5.content as to_location,
						AES_DECRYPT(t1.cc_type, "'.PJ_SALT.'") AS `cc_type`,	
						AES_DECRYPT(t1.cc_num, "'.PJ_SALT.'") AS `cc_num`,
						AES_DECRYPT(t1.cc_exp, "'.PJ_SALT.'") AS `cc_exp`,
						AES_DECRYPT(t1.cc_code, "'.PJ_SALT.'") AS `cc_code`
					' )->join ( 'pjBus', "t2.id=t1.bus_id", 'left outer' )->join ( 'pjMultiLang', "t3.model='pjRoute' AND t3.foreign_id=t2.route_id AND t3.field='title' AND t3.locale='" . $this->getLocaleId () . "'", 'left outer' )->join ( 'pjMultiLang', "t4.model='pjCity' AND t4.foreign_id=t1.pickup_id AND t4.field='name' AND t4.locale='" . $this->getLocaleId () . "'", 'left outer' )->join ( 'pjMultiLang', "t5.model='pjCity' AND t5.foreign_id=t1.return_id AND t5.field='name' AND t5.locale='" . $this->getLocaleId () . "'", 'left outer' )->find ( $arr ['back_id'] )->getData ();
					
					$return_tickets = pjBookingTicketModel::factory ()->join ( 'pjMultiLang', "t2.model='pjTicket' AND t2.foreign_id=t1.ticket_id AND t2.field='title' AND t2.locale='" . $this->getLocaleId () . "'", 'left outer' )->join ( 'pjTicket', "t3.id=t1.ticket_id", 'left' )->select ( 't1.*, t2.content as title' )->where ( 'booking_id', $arr ['back_id'] )->findAll ()->getData ();
					
					$return_arr ['tickets'] = $return_tickets;
					
					pjFrontEnd::pjActionConfirmSend ( $this->option_arr, $return_arr, PJ_SALT, 'confirm', $this->getLocaleId());
				}
				
				unset ( $_SESSION [$this->defaultStore] );
				unset ( $_SESSION [$this->defaultForm] );
				unset ( $_SESSION [$this->defaultStep] );
				unset ( $_SESSION [$this->defaultCaptcha] );
				
				$json = array (
						'code' => 200,
						'text' => '',
						'booking_id' => $id,
						'payment' => $payment 
				);
			} else {
				$json = array (
						'code' => 100,
						'text' => '' 
				);
			}
			pjAppController::jsonResponse ( $json );
		}
	}
		
	public function pjActionGetLocations()
	{
		$this->setAjax(true);
	
		$pjCityModel = pjCityModel::factory();
		$pjRouteDetailModel = pjRouteDetailModel::factory();
	
		if($this->_get->check('pickup_id'))
		{
			$where = '';
			if($this->_get->toInt('pickup_id') > 0)
			{
				$where = "WHERE TRD.from_location_id=" . $this->_get->toInt('pickup_id');
			}
			$location_arr = $pjCityModel
				->reset()
				->select('t1.*, t2.content as name')
				->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where("t1.id IN(SELECT TRD.to_location_id FROM `".$pjRouteDetailModel->getTable()."` AS TRD $where)")
				->orderBy("t2.content ASC")
				->findAll()
				->getData();
		}
		if($this->_get->check('return_id'))
		{
			$where = '';
			if($this->_get->toInt('return_id') > 0)
			{
				$where = "WHERE TRD.to_location_id=" . $this->_get->toInt('return_id');
			}
			$location_arr = $pjCityModel
				->reset()
				->select('t1.*, t2.content as name')
				->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where("t1.id IN(SELECT TRD.from_location_id FROM `".$pjRouteDetailModel->getTable()."` AS TRD $where)")
				->orderBy("t2.content ASC")
				->findAll()
				->getData();
		}
	
		$this->set('location_arr', $location_arr);
	}
	
	public function pjActionGetRoundtripPrice()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_SESSION[$this->defaultStore]) && count($_SESSION[$this->defaultStore]) > 0 && $this->isBusReady() == true)
			{
				$pickup_id = $this->_get('pickup_id');
				$return_id = $this->_get('return_id');
				$is_return = $this->_get('is_return');
				$bus_id = $this->_get->check('bus_id') && $this->_get->toInt('bus_id') > 0 ? $this->_get->toInt('bus_id') : 0;
				$return_bus_id = $this->_get->check('return_bus_id') && $this->_get->toInt('return_bus_id') > 0 ? $this->_get->toInt('return_bus_id') : 0;
	
				$pjPriceModel = pjPriceModel::factory();
				if($bus_id > 0)
				{
					$ticket_price_arr = $pjPriceModel->getTicketPrice($bus_id, $pickup_id, $return_id, $this->_post->raw(), $this->option_arr, $this->getLocaleId(), 'F');
					$this->set('price_arr', $ticket_price_arr);
				}
				if($return_bus_id > 0 && $is_return == "T")
				{
					$return_ticket_price_arr = $pjPriceModel->getTicketPrice($return_bus_id, $return_id, $pickup_id, $this->_post->raw(), $this->option_arr, $this->getLocaleId(), 'T');
					$this->set('return_price_arr', $return_ticket_price_arr);
				}
				$this->set('status', 'OK');
			}else{
				$this->set('status', 'ERR');
			}
		}
	}

	public function pjActionGetSeats()
	{
		$this->setAjax(true);
	
		$bus_id = $this->_get->toInt('bus_id');
		$STORE = @$_SESSION[$this->defaultStore];
			
		$avail_arr = $this->getBusAvailability($bus_id, $STORE, $this->option_arr);
		
		$this->set('bus_arr', pjBusModel::factory()->find($bus_id)->getData());
		$this->set('bus_type_arr', $avail_arr['bus_type_arr']);
		$this->set('booked_seat_arr', $avail_arr['booked_seat_arr']);
		if(!empty($avail_arr['bus_type_arr']))
		{
			$this->set('seat_arr', pjSeatModel::factory()->where('bus_type_id', $avail_arr['bus_type_arr']['id'])->findAll()->getData());
		}else{
			$this->set('seat_arr', array());
		}
	}
	
	public function pjActionGetReturnSeats()
	{
		$this->setAjax(true);
	
		$bus_id = $this->_get->toInt('bus_id');
		$STORE = @$_SESSION[$this->defaultStore];
		$avail_arr = $this->getReturnBusAvailability($bus_id, $STORE, $this->option_arr);
		$this->set('bus_arr', pjBusModel::factory()->find($bus_id)->getData());
			
		$this->set('return_bus_type_arr', $avail_arr['bus_type_arr']);
		$this->set('booked_return_seat_arr', $avail_arr['booked_seat_arr']);
		if(!empty($avail_arr['bus_type_arr']))
		{
			$this->set('return_seat_arr', pjSeatModel::factory()->where('bus_type_id', $avail_arr['bus_type_arr']['id'])->findAll()->getData());
		}else{
			$this->set('return_seat_arr', array());
		}
	}

	public static function pjActionConfirmSend($option_arr, $data, $salt, $opt, $locale)
	{
		$Email = self::getMailer($option_arr);
	    
	    $pjMultiLangModel = pjMultiLangModel::factory();
	    
	    $admin_email = pjAppController::getAdminEmail();
	    $admin_phone = pjAppController::getAdminPhone();
	    $locale_id = isset($data['locale_id']) && (int) $data['locale_id'] > 0 ? (int) $data['locale_id'] : $locale;
	    
	    $pjNotificationModel = pjNotificationModel::factory();
	    
		$tokens = pjAppController::getData($option_arr, $data, PJ_SALT, $locale_id);
		
		$opt = $opt == 'confirm' ? 'confirmation' : $opt;
		
		/*Confirmation sent to clients*/
		$notification = $pjNotificationModel->reset()->where('recipient', 'client')->where('transport', 'email')->where('variant', $opt)->findAll()->getDataIndex(0);
		if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
		{
			$resp = pjAppController::getSubjectMessage($notification, $locale_id);
			$lang_message = $resp['lang_message'];
			$lang_subject = $resp['lang_subject'];
			if (count($lang_message) === 1 && count($lang_subject) === 1 && $lang_subject[0]['content'] != '' && $lang_message[0]['content'] != '')
			{
				$subject = str_replace($tokens['search'], $tokens['replace'], $lang_subject[0]['content']);
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);				
				$Email
					->setTo($data['c_email'])
					->setSubject($subject)
					->send($message);
			}
		}
		/*Confirmation sent to admin*/
		$notification = $pjNotificationModel->reset()->where('recipient', 'admin')->where('transport', 'email')->where('variant', $opt)->findAll()->getDataIndex(0);
		if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
		{
			$resp = pjAppController::getSubjectMessage($notification, $locale_id);
			$lang_message = $resp['lang_message'];
			$lang_subject = $resp['lang_subject'];
			if (count($lang_message) === 1 && count($lang_subject) === 1 && $lang_subject[0]['content'] != '' && $lang_message[0]['content'] != '')
			{
				$subject = str_replace($tokens['search'], $tokens['replace'], $lang_subject[0]['content']);
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
				foreach($admin_email as $email)
				{
					$Email
						->setTo($email)
						->setSubject($subject)
						->send($message);
				}
			}
		}
		/*SMS sent to client*/
		if(!empty($data['c_phone']))
		{
			$notification = $pjNotificationModel->reset()->where('recipient', 'client')->where('transport', 'sms')->where('variant', $opt)->findAll()->getDataIndex(0);
			if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
			{
				$resp = pjAppController::getSmsMessage($notification, $locale_id);
				$lang_message = $resp['lang_message'];
				if (count($lang_message) === 1 && $lang_message[0]['content'] != '')
				{
					$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
					$params = array(
						'text' => $message,
						'type' => 'unicode',
						'key' => md5($option_arr['private_key'] . PJ_SALT)
					);
					$params['number'] = $data['c_phone'];
					pjBaseSms::init($params)->pjActionSend();
				}
			}
		}
		/*SMS sent to admin*/
		if(!empty($admin_phone))
		{
			$notification = $pjNotificationModel->reset()->where('recipient', 'admin')->where('transport', 'sms')->where('variant', $opt)->findAll()->getDataIndex(0);
			if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
			{
				$resp = pjAppController::getSmsMessage($notification, $locale_id);
				$lang_message = $resp['lang_message'];
				if (count($lang_message) === 1 && $lang_message[0]['content'] != '')
				{
					$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
					$params = array(
						'text' => $message,
						'type' => 'unicode',
						'key' => md5($option_arr['private_key'] . PJ_SALT)
					);
					foreach ($admin_phone as $phone) {
                    	if (!empty($phone)) {
		                    $params['number'] = $phone;
		                    pjBaseSms::init($params)->pjActionSend();
                    	}
                    }					
				}
			}
		}
	}
	
	public function pjActionConfirm()
	{
	    $this->setAjax(true);
	 
	    if (pjObject::getPlugin('pjPayments') === NULL)
	    {
	        $this->log('pjPayments plugin not installed');
	        exit;
	    }
	    
	    $pjPayments = new pjPayments();
	    $post = $this->_post->raw();
	    $get = $this->_get->raw();
	    $request = array();
	    if(isset($get['payment_method']))
	    {
	        $request = $get;
	    }
	    if(isset($post['payment_method']))
	    {
	        $request = $post;
	    }
	    if($pjPlugin = $pjPayments->getPaymentPlugin($request))
	    {
	        if($uuid = $this->requestAction(array('controller' => $pjPlugin, 'action' => 'pjActionGetCustom', 'params' => $request), array('return')))
	        {
	        	$pjBookingModel = pjBookingModel::factory();
				$booking_arr = $pjBookingModel
					->select('t1.*, t2.departure_time, t2.arrival_time, t3.content as route_title, t4.content as from_location, t5.content as to_location,
						AES_DECRYPT(t1.cc_type, "'.PJ_SALT.'") AS `cc_type`,	
						AES_DECRYPT(t1.cc_num, "'.PJ_SALT.'") AS `cc_num`,
						AES_DECRYPT(t1.cc_exp, "'.PJ_SALT.'") AS `cc_exp`,
						AES_DECRYPT(t1.cc_code, "'.PJ_SALT.'") AS `cc_code`
					')
					->join('pjBus', "t2.id=t1.bus_id", 'left outer')
					->join('pjMultiLang', "t3.model='pjRoute' AND t3.foreign_id=t2.route_id AND t3.field='title' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
					->join('pjMultiLang', "t4.model='pjCity' AND t4.foreign_id=t1.pickup_id AND t4.field='name' AND t4.locale='".$this->getLocaleId()."'", 'left outer')
					->join('pjMultiLang', "t5.model='pjCity' AND t5.foreign_id=t1.return_id AND t5.field='name' AND t5.locale='".$this->getLocaleId()."'", 'left outer')
					->where('t1.uuid', $uuid)
					->limit(1)
					->findAll()
					->getDataIndex(0);				
				if (!empty($booking_arr))
	            {
	            	$booking_arr['tickets'] = pjBookingTicketModel::factory()
						->join('pjMultiLang', "t2.model='pjTicket' AND t2.foreign_id=t1.ticket_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->join('pjTicket', "t3.id=t1.ticket_id", 'left')
						->select('t1.*, t2.content as title')
						->where('booking_id', $booking_arr['id'])
						->findAll()
						->getData();
					$deposit = $booking_arr['deposit'];
		            if (!empty($booking_arr['back_id'])) {
						$back_arr = $pjBookingModel->reset()->select('t1.*')->find($booking_arr['back_id'])->getData();
						$deposit += $back_arr['deposit'];
					}
						
	                $params = array(
	                    'request'		=> $request,
	                    'payment_method' => $request['payment_method'],
	                    'foreign_id'	 => $this->getForeignId(),
	                    'amount'		 => $deposit,
	                    'txn_id'		 => @$booking_arr['txn_id'],
	                    'order_id'	   => $booking_arr['id'],
	                    'cancel_hash'	=> sha1($booking_arr['uuid'].strtotime($booking_arr['created']).PJ_SALT),
	                    'key'			=> md5($this->option_arr['private_key'] . PJ_SALT)
	                );
	                $response = $this->requestAction(array('controller' => $pjPlugin, 'action' => 'pjActionConfirm', 'params' => $params), array('return'));
	                if($response['status'] == 'OK')
	                {
	                    $this->log("Payments | {$pjPlugin} plugin<br>Booking was confirmed. UUID: {$uuid}");
		                $pjBookingModel->reset()->set('id', $booking_arr['id'])->modify(array(
							'status' => $this->option_arr['o_payment_status'],
							'txn_id' => @$response['txn_id'],
							'processed_on' => ':NOW()'
						));
						if (!empty($booking_arr['back_id'])) 
						{
							$pjBookingModel->reset()->set('id', $booking_arr['back_id'])->modify(array(
									'status' => $this->option_arr['o_payment_status'],
									'txn_id' => @$response['txn_id'],
									'processed_on' => ':NOW()'
							));
						}
						pjBookingPaymentModel::factory()
							->where('booking_id', $booking_arr['id'])
							->where('payment_type', 'online')
							->modifyAll(array('status' => 'paid'));
							
						pjFrontEnd::pjActionConfirmSend($this->option_arr, $booking_arr, PJ_SALT, 'payment', $this->getLocaleId());
						if ($booking_arr['is_return'] == 'T') {
						    $return_arr = $pjBookingModel
						     ->reset ()
						     ->select ( 't1.*, t2.departure_time, t2.arrival_time, t3.content as route_title, t4.content as from_location, t5.content as to_location,
						     	AES_DECRYPT(t1.cc_type, "'.PJ_SALT.'") AS `cc_type`,	
								AES_DECRYPT(t1.cc_num, "'.PJ_SALT.'") AS `cc_num`,
								AES_DECRYPT(t1.cc_exp, "'.PJ_SALT.'") AS `cc_exp`,
								AES_DECRYPT(t1.cc_code, "'.PJ_SALT.'") AS `cc_code`
						     ' )
						     ->join ( 'pjBus', "t2.id=t1.bus_id", 'left outer' )
						     ->join ( 'pjMultiLang', "t3.model='pjRoute' AND t3.foreign_id=t2.route_id AND t3.field='title' AND t3.locale='" . $this->getLocaleId () . "'", 'left outer' )
						     ->join ( 'pjMultiLang', "t4.model='pjCity' AND t4.foreign_id=t1.pickup_id AND t4.field='name' AND t4.locale='" . $this->getLocaleId () . "'", 'left outer' )
						     ->join ( 'pjMultiLang', "t5.model='pjCity' AND t5.foreign_id=t1.return_id AND t5.field='name' AND t5.locale='" . $this->getLocaleId () . "'", 'left outer' )
						     ->find ( $booking_arr ['back_id'] )->getData ();
						    
						    $return_tickets = pjBookingTicketModel::factory ()
						     ->join ( 'pjMultiLang', "t2.model='pjTicket' AND t2.foreign_id=t1.ticket_id AND t2.field='title' AND t2.locale='" . $this->getLocaleId () . "'", 'left outer' )
						     ->join ( 'pjTicket', "t3.id=t1.ticket_id", 'left' )
						     ->select ( 't1.*, t2.content as title' )
						     ->where ( 'booking_id', $booking_arr ['back_id'] )
						     ->findAll ()->getData ();
						    
						    $return_arr ['tickets'] = $return_tickets;
						    
						    pjFrontEnd::pjActionConfirmSend ( $this->option_arr, $return_arr, PJ_SALT, 'payment', $this->getLocaleId());
						}
	                    	                    
	                    echo $this->option_arr['o_thank_you_page'];
	                    exit;
	                }elseif($response['status'] == 'CANCEL'){
	                    $this->log("Payments | {$pjPlugin} plugin<br>Payment was cancelled. UUID: {$uuid}");
	                    
	                	$pjBookingModel->reset()->set('id', $booking_arr['id'])->modify(array(
							'status' => 'cancelled',
							'processed_on' => ':NOW()'
						));
						if (!empty($booking_arr['back_id'])) 
						{
							$pjBookingModel->reset()->set('id', $booking_arr['back_id'])->modify(array(
									'status' => 'cancelled',
									'processed_on' => ':NOW()'
							));
						}
							
						pjFrontEnd::pjActionConfirmSend($this->option_arr, $booking_arr, PJ_SALT, 'cancel', $this->getLocaleId());
						if ($booking_arr['is_return'] == 'T') {
						    $return_arr = $pjBookingModel
						     ->reset ()
						     ->select ( 't1.*, t2.departure_time, t2.arrival_time, t3.content as route_title, t4.content as from_location, t5.content as to_location,
						     	AES_DECRYPT(t1.cc_type, "'.PJ_SALT.'") AS `cc_type`,	
								AES_DECRYPT(t1.cc_num, "'.PJ_SALT.'") AS `cc_num`,
								AES_DECRYPT(t1.cc_exp, "'.PJ_SALT.'") AS `cc_exp`,
								AES_DECRYPT(t1.cc_code, "'.PJ_SALT.'") AS `cc_code`
						     ' )
						     ->join ( 'pjBus', "t2.id=t1.bus_id", 'left outer' )
						     ->join ( 'pjMultiLang', "t3.model='pjRoute' AND t3.foreign_id=t2.route_id AND t3.field='title' AND t3.locale='" . $this->getLocaleId () . "'", 'left outer' )
						     ->join ( 'pjMultiLang', "t4.model='pjCity' AND t4.foreign_id=t1.pickup_id AND t4.field='name' AND t4.locale='" . $this->getLocaleId () . "'", 'left outer' )
						     ->join ( 'pjMultiLang', "t5.model='pjCity' AND t5.foreign_id=t1.return_id AND t5.field='name' AND t5.locale='" . $this->getLocaleId () . "'", 'left outer' )
						     ->find ( $booking_arr ['back_id'] )->getData ();
						    
						    $return_tickets = pjBookingTicketModel::factory ()
						     ->join ( 'pjMultiLang', "t2.model='pjTicket' AND t2.foreign_id=t1.ticket_id AND t2.field='title' AND t2.locale='" . $this->getLocaleId () . "'", 'left outer' )
						     ->join ( 'pjTicket', "t3.id=t1.ticket_id", 'left' )
						     ->select ( 't1.*, t2.content as title' )
						     ->where ( 'booking_id', $booking_arr ['back_id'] )
						     ->findAll ()->getData ();
						    
						    $return_arr ['tickets'] = $return_tickets;
						    
						    pjFrontEnd::pjActionConfirmSend ( $this->option_arr, $return_arr, PJ_SALT, 'cancel', $this->getLocaleId());
						}
	                    
	                    echo $this->option_arr['o_thank_you_page'];
	                    exit;
	                }else{
	                    $this->log("Payments | {$pjPlugin} plugin<br>Booking confirmation was failed. UUID: {$uuid}");
	                }
	                
	                if(isset($response['redirect']) && $response['redirect'] == true)
	                {
	                    echo $this->option_arr['o_thank_you_page'];
	                    exit;
	                }
	            }else{
	                $this->log("Payments | {$pjPlugin} plugin<br>Booking with UUID {$uuid} not found.");
	            }
	            echo $this->option_arr['o_thank_you_page'];
	            exit;
	        }
	    }
	    echo $this->option_arr['o_thank_you_page'];
	    exit;
	}
	
	public function pjActionDriverLogout() {
	    $this->setAjax(true);
	    if ($this->isLoged())
	    {
	        unset($_SESSION[$this->defaultUser]);
	    }
	    exit;
	}
	
	public function pjActionCheckTicket() {
	    $this->setAjax(true);
	    $validate_ticket = __('validate_ticket', true);
	    if ($this->isLoged())
	    {
	        if ($this->_post->check('code') && $this->_post->toString('code') != '') {
	            $pjBookingSeatModel = pjBookingSeatModel::factory();
	            
	            $code_arr = explode(' | ', $this->_post->toString('code'));
	            $bs_arr = $pjBookingSeatModel->select('t1.*, t2.status, t3.name')
	            ->join('pjBooking', 't2.id=t1.booking_id', 'inner')
	            ->join('pjSeat', 't3.id=t1.seat_id', 'inner')
	            ->where('t1.qr_code', $code_arr[0])
	            ->limit(1)
	            ->findAll()->getDataIndex(0);
	            if ($bs_arr) {
	                if ($bs_arr['status'] == 'confirmed') {
	                    if ($bs_arr['is_used'] == 1) {
	                        pjAppController::jsonResponse(array('status' => 'ERR', 'text' => $validate_ticket[4]));
	                    } else {
	                        $booking_arr = pjBookingModel::factory()->find($bs_arr['booking_id'])->getData();
	                        $client_name_arr = array();
	                        if (!empty($booking_arr['c_fname'])) {
	                            $client_name_arr[] = pjSanitize::clean($booking_arr['c_fname']);
	                        }
	                        if (!empty($booking_arr['c_lname'])) {
	                            $client_name_arr[] = pjSanitize::clean($booking_arr['c_lname']);
	                        }
	                        $client_name = implode(' ', $client_name_arr);
	                        
	                        $data = array();
	                        $data[] = __('front_ticket_seat', true).': '.pjSanitize::clean($bs_arr['name']);
	                        if (!empty($client_name)) {
	                            $data[] = __('front_ticket_name', true).': '.$client_name;
	                        }
	                        if (!empty($booking_arr['c_phone'])) {
	                            $data[] = __('front_ticket_phone', true).': '.pjSanitize::clean($booking_arr['c_phone']);
	                        }
	                        $pjBookingSeatModel->reset()->where('qr_code', $bs_arr['qr_code'])->modifyAll(array('is_used' => 1));
	                        pjAppController::jsonResponse(array('status' => 'OK', 'text' => $validate_ticket[3].'<br/>'.implode('<br/>', $data)));
	                    }
	                } else {
	                    pjAppController::jsonResponse(array('status' => 'ERR', 'text' => $validate_ticket[6]));
	                }
	            } else {
	                pjAppController::jsonResponse(array('status' => 'ERR', 'text' => $validate_ticket[2]));
	            }
	        } else {
	            pjAppController::jsonResponse(array('status' => 'ERR', 'text' => $validate_ticket[1]));
	        }
	    } else {
	        pjAppController::jsonResponse(array('status' => 'ERR', 'text' => $validate_ticket[5]));
	    }
	    exit;
	}
	
	public function pjActionCheckDriverEmail()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        if (!$this->_get->check('email') || $this->_get->toString('email') == '')
	        {
	            echo 'false';
	            exit;
	        }
	        $pjAuthUserModel = pjAuthUserModel::factory()
	        ->where('t1.email', $this->_get->toString('email'))
	        ->where('t1.role_id', 3)
	        ->where('t1.id !=', $this->getUserId());
	        echo $pjAuthUserModel->findCount()->getData() == 0 ? 'true' : 'false';
	    }
	    exit;
	}
}
?>