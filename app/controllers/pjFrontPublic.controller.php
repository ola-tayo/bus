<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFrontPublic extends pjFront
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setAjax(true);
		
		$this->setLayout('pjActionEmpty');
	}
	
	public function pjActionSearch()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
		{
			$is_ip_blocked = pjBase::isBlockedIp(pjUtil::getClientIp(), $this->option_arr);
			if($is_ip_blocked == true)
			{
				$this->set('status', 'IP_BLOCKED');
			} else {
				$_SESSION[$this->defaultStep]['1_passed'] = true;
		
				$pjCityModel = pjCityModel::factory();
				$pjRouteDetailModel = pjRouteDetailModel::factory();
					
				$from_location_arr = $pjCityModel
					->reset()
					->select('t1.*, t2.content as name')
					->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->where("t1.id IN(SELECT TRD.from_location_id FROM `".$pjRouteDetailModel->getTable()."` AS TRD)")
					->orderBy("t2.content ASC")
					->findAll()
					->getData();
					
				$to_location_arr = $pjCityModel
					->reset()
					->select('t1.*, t2.content as name')
					->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->where("t1.id IN(SELECT TRD.to_location_id FROM `".$pjRouteDetailModel->getTable()."` AS TRD)")
					->orderBy("t2.content ASC")
					->findAll()
					->getData();
				if($this->_is('pickup_id'))
				{
					$pickup_id = $this->_get('pickup_id');
					$where = "WHERE TRD.from_location_id=" . $pickup_id;
					$return_location_arr = pjCityModel::factory()
						->reset()
						->select('t1.*, t2.content as name')
						->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->where("t1.id IN(SELECT TRD.to_location_id FROM `".$pjRouteDetailModel->getTable()."` AS TRD $where)")
						->orderBy("t2.content ASC")
						->findAll()
						->getData();
		
					$this->set('return_location_arr', $return_location_arr);
				}
				$image = pjOptionModel::factory()
					->where('t1.foreign_id', $this->getForeignId())
					->where('t1.key', 'o_image_path')
					->orderBy('t1.order ASC')
					->findAll()
					->getData();
				$content = pjMultiLangModel::factory()->select('t1.*')
					->where('t1.model','pjOption')
					->where('t1.locale', $this->getLocaleId())
					->where('t1.field', 'o_content')
					->limit(0, 1)
					->index("FORCE KEY (`foreign_id`)")
					->findAll()->getData();
		
				$this->set('from_location_arr', $from_location_arr);
				$this->set('to_location_arr', $to_location_arr);
				$this->set('content_arr', compact('content', 'image'));
				$this->set('status', 'OK');
			}
		}
	}
	
	public function pjActionSeats()
	{
		if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
		{
			$is_ip_blocked = pjBase::isBlockedIp(pjUtil::getClientIp(), $this->option_arr);
			if($is_ip_blocked == true)
			{
				$this->set('status', 'IP_BLOCKED');
			} else {
				$_SESSION[$this->defaultStep]['2_passed'] = true;
		
				if (isset($_SESSION[$this->defaultStore]) && count($_SESSION[$this->defaultStore]) > 0 && $this->isBusReady() == true)
				{
					$booking_period = array();
					if($this->_is('booking_period'))
					{
						$booking_period = $this->_get('booking_period');
					}
					$booked_data = array();
					if($this->_is('booked_data'))
					{
						$booked_data = $this->_get('booked_data');
					}
		
					if($this->_is('bus_id_arr'))
					{
						$bus_id_arr = $this->_get('bus_id_arr');
						$pickup_id = $this->_get('pickup_id');
						$return_id = $this->_get('return_id');
						$date = $this->_get('date');
							
						$bus_list = $this->getBusList($pickup_id, $return_id, $bus_id_arr, $booking_period, $booked_data, $date, 'F');
							
						$booking_period = $bus_list['booking_period'];
							
						$this->_set('booking_period', $booking_period);
							
						$this->set('bus_type_arr', $bus_list['bus_type_arr']);
						$this->set('booked_seat_arr', $bus_list['booked_seat_arr']);
						$this->set('seat_arr', $bus_list['seat_arr']);
						$this->set('selected_seat_arr', $bus_list['selected_seat_arr']);
						$this->set('bus_arr', $bus_list['bus_arr']);
						$this->set('ticket_columns', $bus_list['ticket_columns']);						
					}
					
					$pjCityModel = pjCityModel::factory();
					$pickup_location = $pjCityModel->select ( 't1.*, t2.content as name' )->join ( 'pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='" . $this->getLocaleId () . "'", 'left outer' )->find ( $this->_get('pickup_id') )->getData ();
					$return_location = $pjCityModel->reset ()->select ( 't1.*, t2.content as name' )->join ( 'pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='" . $this->getLocaleId () . "'", 'left outer' )->find ( $this->_get('return_id') )->getData ();
					$this->set('from_location', $pickup_location['name']);
					$this->set('to_location', $return_location['name']);
						
					if($this->_is('return_bus_id_arr'))
					{
						$bus_id_arr = $this->_get('return_bus_id_arr');
						$pickup_id = $this->_get('return_id');
						$return_id = $this->_get('pickup_id');
						$date = $this->_get('return_date');
							
						$bus_list = $this->getBusList($pickup_id, $return_id, $bus_id_arr, $booking_period, $booked_data, $date, 'T');
							
						$booking_period = $bus_list['booking_period'];
		
						$this->_set('booking_period', $booking_period);
							
						$this->set('return_bus_type_arr', $bus_list['bus_type_arr']);
						$this->set('booked_return_seat_arr', $bus_list['booked_seat_arr']);
						$this->set('return_seat_arr', $bus_list['seat_arr']);
						$this->set('return_selected_seat_arr', $bus_list['selected_seat_arr']);
						$this->set('return_bus_arr', $bus_list['bus_arr']);
						$this->set('return_ticket_columns', $bus_list['ticket_columns']);
						$this->set('return_from_location', $bus_list['from_location']);
						$this->set('return_to_location', $bus_list['to_location']);
					}
		
					$this->set('status', 'OK');
				}else{
					$this->set('status', 'ERR');
				}
			}
		}
	}
	
	public function pjActionCheckout()
	{
		if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
		{
			$is_ip_blocked = pjBase::isBlockedIp(pjUtil::getClientIp(), $this->option_arr);
			if($is_ip_blocked == true)
			{
				$this->set('status', 'IP_BLOCKED');
			} else {
				$_SESSION[$this->defaultStep]['3_passed'] = true;
		
				if (isset($_SESSION[$this->defaultStore]) && count($_SESSION[$this->defaultStore]) > 0 && $this->isBusReady() == true)
				{
					$booked_data = $this->_get('booked_data');
					$pickup_id = $this->_get('pickup_id');
					$return_id = $this->_get('return_id');
					$is_return = $this->_get('is_return');
					$bus_id = $booked_data['bus_id'];
					$departure_time = NULL;
					$_departure_time = NULL;
					$arrival_time = NULL;
					$_arrival_time = NULL;
					$duration = NULL;
					$_duration = NULL;
		
					$pjBusLocationModel = pjBusLocationModel::factory();
					$pickup_arr = $pjBusLocationModel->where('bus_id', $bus_id)->where("location_id", $pickup_id)->limit(1)->findAll()->getData();
					$return_arr = $pjBusLocationModel->reset()->where('bus_id', $bus_id)->where("location_id", $return_id)->limit(1)->findAll()->getData();
		
					if(!empty($pickup_arr))
					{
						$departure_time = pjDateTime::formatTime(date('H:i:s', strtotime($pickup_arr[0]['departure_time'])), 'H:i:s', $this->option_arr['o_time_format']);
					}
					if(!empty($return_arr))
					{
						$arrival_time = pjDateTime::formatTime(date('H:i:s', strtotime($return_arr[0]['arrival_time'])), 'H:i:s', $this->option_arr['o_time_format']);
					}
					if(!empty($pickup_arr) && !empty($return_arr))
					{
						$duration_arr = pjUtil::calDuration($pickup_arr[0]['departure_time'], $return_arr[0]['arrival_time']);
							
						$hour_str = $duration_arr['hours'] . ' ' . ($duration_arr['hours'] != 1 ? strtolower(__('front_hours', true, false)) : strtolower(__('front_hour', true, false)));
						$minute_str = $duration_arr['minutes'] > 0 ? ($duration_arr['minutes'] . ' ' . ($duration_arr['minutes'] != 1 ? strtolower(__('front_minutes', true, false)) : strtolower(__('front_minute', true, false))) ) : '';
						$duration = $hour_str . ' ' . $minute_str;
					}
		
					$pjCityModel = pjCityModel::factory();
					$pickup_location = $pjCityModel->reset()->select('t1.*, t2.content as name')->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')->find($pickup_id)->getData();
					$return_location = $pjCityModel->reset()->select('t1.*, t2.content as name')->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')->find($return_id)->getData();
					$from_location = $pickup_location['name'];
					$to_location = $return_location['name'];
		
					$pjBusModel= pjBusModel::factory();
					$bus_arr = $pjBusModel
						->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.route_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->select("t1.*, t2.content as route_title")
						->find($bus_id)
						->getData();
					$bus_arr['departure_time'] = $departure_time;
					$bus_arr['arrival_time'] = $arrival_time;
					$bus_arr['duration'] = $duration;
		
					$pjPriceModel = pjPriceModel::factory();
		
					$ticket_price_arr = $pjPriceModel->getTicketPrice($bus_id, $pickup_id, $return_id, $booked_data, $this->option_arr, $this->getLocaleId(), 'F');
		
					$this->set('from_location', $from_location);
					$this->set('to_location', $to_location);
					$this->set('bus_arr', $bus_arr);
					$this->set('ticket_arr', $ticket_price_arr['ticket_arr']);
					$this->set('price_arr', $ticket_price_arr);
					if ($is_return == "T")
					{
						$return_bus_id = $booked_data['return_bus_id'];
							
						$return_ticket_price_arr = $pjPriceModel->getTicketPrice($return_bus_id, $return_id, $pickup_id, $booked_data, $this->option_arr, $this->getLocaleId(), 'T');
							
						$this->set('return_ticket_arr', $return_ticket_price_arr['ticket_arr']);
						$this->set('return_price_arr', $return_ticket_price_arr);
		
						$_bus_arr = $pjBusModel
							->reset()
							->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.route_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
							->select("t1.*, t2.content as route_title")
							->find($return_bus_id)
							->getData();
		
						$_pickup_arr = $pjBusLocationModel->reset()->where('bus_id', $return_bus_id)->where("location_id", $return_id)->limit(1)->findAll()->getData();
						$_return_arr = $pjBusLocationModel->reset()->where('bus_id', $return_bus_id)->where("location_id", $pickup_id)->limit(1)->findAll()->getData();
		
						if(!empty($_pickup_arr))
						{
							$_departure_time = pjDateTime::formatTime(date('H:i:s', strtotime($_pickup_arr[0]['departure_time'])), 'H:i:s', $this->option_arr['o_time_format']);
						}
						if(!empty($_return_arr))
						{
							$_arrival_time = pjDateTime::formatTime(date('H:i:s', strtotime($_return_arr[0]['arrival_time'])), 'H:i:s', $this->option_arr['o_time_format']);
						}
						if(!empty($_pickup_arr) && !empty($_return_arr))
						{
							$_duration_arr = pjUtil::calDuration($_pickup_arr[0]['departure_time'], $_return_arr[0]['arrival_time']);
		
							$_hour_str = $_duration_arr['hours'] . ' ' . ($_duration_arr['hours'] != 1 ? strtolower(__('front_hours', true, false)) : strtolower(__('front_hour', true, false)));
							$_minute_str = $_duration_arr['minutes'] > 0 ? ($_duration_arr['minutes'] . ' ' . ($_duration_arr['minutes'] != 1 ? strtolower(__('front_minutes', true, false)) : strtolower(__('front_minute', true, false))) ) : '';
							$_duration = $_hour_str . ' ' . $_minute_str;
						}
		
						$_bus_arr['departure_time'] = $_departure_time;
						$_bus_arr['arrival_time'] = $_arrival_time;
						$_bus_arr['duration'] = $_duration;
		
						$_pickup_location = $pjCityModel->reset()->select('t1.*, t2.content as name')->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')->find($return_id)->getData();
						$_return_location = $pjCityModel->reset()->select('t1.*, t2.content as name')->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')->find($pickup_id)->getData();
						$_from_location = $_pickup_location['name'];
						$_to_location = $_return_location['name'];
		
						$this->set('is_return', $is_return);
						$this->set('return_from_location', $_from_location);
						$this->set('return_to_location', $_to_location);
						$this->set('return_bus_arr', $_bus_arr);
					}
		
					$country_arr = pjBaseCountryModel::factory()
						->select('t1.id, t2.content AS country_title')
						->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->orderBy('`country_title` ASC')->findAll()->getData();
		
					$terms_conditions = pjMultiLangModel::factory()->select('t1.*')
						->where('t1.model','pjOption')
						->where('t1.locale', $this->getLocaleId())
						->where('t1.field', 'o_terms')
						->limit(0, 1)
						->findAll()->getData();
		
					$pjSeatModel = pjSeatModel::factory();
			
					$selected_seat_arr = $pjSeatModel->whereIn('t1.id', explode("|", $booked_data['selected_seats']))->findAll()->getDataPair('id', 'name');
					$return_selected_seat_arr = (isset($booked_data['return_selected_seats']) && !empty($booked_data['return_selected_seats'])) ? $pjSeatModel->reset()->whereIn('t1.id', explode("|", $booked_data['return_selected_seats']))->findAll()->getDataPair('id', 'name') : array();
					
					$this->set('selected_seat_arr', $selected_seat_arr);
					$this->set('return_selected_seat_arr', $return_selected_seat_arr);
					$this->set('country_arr', $country_arr);
					$this->set('terms_conditions', $terms_conditions[0]['content']);
		
					$bank_account = pjMultiLangModel::factory()->reset()
					    ->select('t1.content')
					    ->where('t1.model','pjOption')
					    ->where('t1.locale', $this->getLocaleId())
					    ->where('t1.foreign_id', $this->getForeignId())
					    ->where('t1.field', 'o_bank_account')
					    ->limit(1)
					    ->findAll()
					    ->getDataIndex(0);
				    $this->set('bank_account', $bank_account ? $bank_account['content'] : '');
					
					if(pjObject::getPlugin('pjPayments') !== NULL)
					{
					    $this->set('payment_option_arr', pjPaymentOptionModel::factory()->getOptions($this->getForeignId()));
					    $this->set('payment_titles', pjPayments::getPaymentTitles($this->getForeignId(), $this->getLocaleId()));
					}else{
					    $this->set('payment_titles', __('payment_methods', true));
					}
					
					$this->set('status', 'OK');
				}else{
					$this->set('status', 'ERR');
				}
			}
		}
	}
	
	public function pjActionPreview()
	{
		if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
		{
			$is_ip_blocked = pjBase::isBlockedIp(pjUtil::getClientIp(), $this->option_arr);
			if($is_ip_blocked == true)
			{
				$this->set('status', 'IP_BLOCKED');
			} else {
				$_SESSION[$this->defaultStep]['4_passed'] = true;
		
				if (isset($_SESSION[$this->defaultForm]) && count($_SESSION[$this->defaultForm]) > 0 && $this->isBusReady() == true)
				{
					$booked_data = $this->_get('booked_data');
					$pickup_id = $this->_get('pickup_id');
					$return_id = $this->_get('return_id');
					$bus_id = $booked_data['bus_id'];
					$is_return = $this->_get('is_return');
					$departure_time = NULL;
					$arrival_time = NULL;
					$duration = NULL;
					$_departure_time = NULL;
					$_arrival_time = NULL;
					$_duration = NULL;
		
					$pjBusLocationModel = pjBusLocationModel::factory();
					$pickup_arr = $pjBusLocationModel->where('bus_id', $bus_id)->where("location_id", $pickup_id)->limit(1)->findAll()->getData();
					$return_arr = $pjBusLocationModel->reset()->where('bus_id', $bus_id)->where("location_id", $return_id)->limit(1)->findAll()->getData();
		
					if(!empty($pickup_arr))
					{
						$departure_time = pjDateTime::formatTime(date('H:i:s', strtotime($pickup_arr[0]['departure_time'])), 'H:i:s', $this->option_arr['o_time_format']);
					}
					if(!empty($return_arr))
					{
						$arrival_time = pjDateTime::formatTime(date('H:i:s', strtotime($return_arr[0]['arrival_time'])), 'H:i:s', $this->option_arr['o_time_format']);
					}
					if(!empty($pickup_arr) && !empty($return_arr))
					{
						$duration_arr = pjUtil::calDuration($pickup_arr[0]['departure_time'], $return_arr[0]['arrival_time']);
							
						$hour_str = $duration_arr['hours'] . ' ' . ($duration_arr['hours'] != 1 ? strtolower(__('front_hours', true, false)) : strtolower(__('front_hour', true, false)));
						$minute_str = $duration_arr['minutes'] > 0 ? ($duration_arr['minutes'] . ' ' . ($duration_arr['minutes'] != 1 ? strtolower(__('front_minutes', true, false)) : strtolower(__('front_minute', true, false))) ) : '';
						$duration = $hour_str . ' ' . $minute_str;
					}
		
					$pjCityModel = pjCityModel::factory();
					$pickup_location = $pjCityModel->reset()->select('t1.*, t2.content as name')->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')->find($pickup_id)->getData();
					$return_location = $pjCityModel->reset()->select('t1.*, t2.content as name')->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')->find($return_id)->getData();
					$from_location = $pickup_location['name'];
					$to_location = $return_location['name'];
		
					$pjBusModel = pjBusModel::factory();
					$bus_arr = $pjBusModel
						->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.route_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->select("t1.*, t2.content as route_title")
						->find($bus_id)
						->getData();
					$bus_arr['departure_time'] = $departure_time;
					$bus_arr['arrival_time'] = $arrival_time;
					$bus_arr['duration'] = $duration;
		
					$pjPriceModel = pjPriceModel::factory();
					$ticket_price_arr = $pjPriceModel->getTicketPrice($bus_id, $pickup_id, $return_id, $booked_data, $this->option_arr, $this->getLocaleId(), 'F');
		
					$this->set('from_location', $from_location);
					$this->set('to_location', $to_location);
					$this->set('bus_arr', $bus_arr);
					$this->set('ticket_arr', $ticket_price_arr['ticket_arr']);
					$this->set('price_arr', $ticket_price_arr);
		
					if ($is_return == "T")
					{
						$return_bus_id = $booked_data['return_bus_id'];
							
						$return_ticket_price_arr = $pjPriceModel->getTicketPrice($return_bus_id, $return_id, $pickup_id, $booked_data, $this->option_arr, $this->getLocaleId(), 'T');
							
						$this->set('return_ticket_arr', $return_ticket_price_arr['ticket_arr']);
						$this->set('return_price_arr', $return_ticket_price_arr);
		
						$_bus_arr = $pjBusModel
							->reset()
							->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.route_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
							->select("t1.*, t2.content as route_title")
							->find($return_bus_id)
							->getData();
		
						$_pickup_arr = $pjBusLocationModel->reset()->where('bus_id', $return_bus_id)->where("location_id", $return_id)->limit(1)->findAll()->getData();
						$_return_arr = $pjBusLocationModel->reset()->where('bus_id', $return_bus_id)->where("location_id", $pickup_id)->limit(1)->findAll()->getData();
		
						if(!empty($_pickup_arr))
						{
							$_departure_time = pjDateTime::formatTime(date('H:i:s', strtotime($_pickup_arr[0]['departure_time'])), 'H:i:s', $this->option_arr['o_time_format']);
						}
						if(!empty($_return_arr))
						{
							$_arrival_time = pjDateTime::formatTime(date('H:i:s', strtotime($_return_arr[0]['arrival_time'])), 'H:i:s', $this->option_arr['o_time_format']);
						}
						if(!empty($_pickup_arr) && !empty($_return_arr))
						{
							$_duration_arr = pjUtil::calDuration($_pickup_arr[0]['departure_time'], $return_arr[0]['arrival_time']);
		
							$hour_str = $_duration_arr['hours'] . ' ' . ($_duration_arr['hours'] != 1 ? strtolower(__('front_hours', true, false)) : strtolower(__('front_hour', true, false)));
							$minute_str = $_duration_arr['minutes'] > 0 ? ($duration_arr['minutes'] . ' ' . ($_duration_arr['minutes'] != 1 ? strtolower(__('front_minutes', true, false)) : strtolower(__('front_minute', true, false))) ) : '';
							$_duration = $hour_str . ' ' . $minute_str;
						}
		
						$_bus_arr['departure_time'] = $_departure_time;
						$_bus_arr['arrival_time'] = $_arrival_time;
						$_bus_arr['duration'] = $_duration;
		
						$_pickup_location = $pjCityModel->reset()->select('t1.*, t2.content as name')->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')->find($return_id)->getData();
						$_return_location = $pjCityModel->reset()->select('t1.*, t2.content as name')->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')->find($pickup_id)->getData();
						$_from_location = $_pickup_location['name'];
						$_to_location = $_return_location['name'];
		
						$this->set('is_return', $is_return);
						$this->set('return_from_location', $_from_location);
						$this->set('return_to_location', $_to_location);
						$this->set('return_bus_arr', $_bus_arr);
					}
		
					$country_arr = array();
					if(isset($_SESSION[$this->defaultForm]['c_country']) && !empty($_SESSION[$this->defaultForm]['c_country']))
					{
						$country_arr = pjCountryModel::factory()
							->select('t1.id, t2.content AS country_title')
							->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
							->find($_SESSION[$this->defaultForm]['c_country'])->getData();
					}
					$pjSeatModel = pjSeatModel::factory();
						
					$selected_seat_arr = $pjSeatModel->whereIn('t1.id', explode("|", $booked_data['selected_seats']))->findAll()->getDataPair('id', 'name');
					$return_selected_seat_arr = (isset($booked_data['return_selected_seats']) && !empty($booked_data['return_selected_seats'])) ? $pjSeatModel->reset()->whereIn('t1.id', explode("|", $booked_data['return_selected_seats']))->findAll()->getDataPair('id', 'name') : array();
					
					$this->set('selected_seat_arr', $selected_seat_arr);
					$this->set('return_selected_seat_arr', $return_selected_seat_arr);
					$this->set('country_arr', $country_arr);
		
					$bank_account = pjMultiLangModel::factory()
					    ->select('t1.content')
					    ->where('t1.model','pjOption')
					    ->where('t1.locale', $this->getLocaleId())
					    ->where('t1.foreign_id', $this->getForeignId())
					    ->where('t1.field', 'o_bank_account')
					    ->limit(1)
					    ->findAll()
					    ->getDataIndex(0);
				    $this->set('bank_account', $bank_account ? $bank_account['content'] : '');
				    
					if(pjObject::getPlugin('pjPayments') !== NULL)
					{
					    $this->set('payment_option_arr', pjPaymentOptionModel::factory()->getOptions($this->getForeignId()));
					    $this->set('payment_titles', pjPayments::getPaymentTitles($this->getForeignId(), $this->pjActionGetLocale()));
					}else{
					    $this->set('payment_titles', __('payment_methods', true));
					}
					
					$this->set('status', 'OK');
				}else{
					$this->set('status', 'ERR');
				}
			}
		}
	}
	

	public function pjActionGetPaymentForm()
	{
		if ($this->isXHR())
		{
			$is_ip_blocked = pjBase::isBlockedIp(pjUtil::getClientIp(), $this->option_arr);
			if($is_ip_blocked == true)
			{
				$this->set('status', 'IP_BLOCKED');
			} else {
				$arr = pjBookingModel::factory()
				->select('t1.*')
				->find($this->_get->toInt('booking_id'))->getData();
		
				if (!empty($arr['back_id'])) {
					$back_arr = pjBookingModel::factory()
						->select('t1.*')
						->find($arr['back_id'])->getData();
					$arr['deposit'] += $back_arr['deposit'];
				}
				if(pjObject::getPlugin('pjPayments') !== NULL)
			    {
			        $pjPlugin = pjPayments::getPluginName($arr['payment_method']);
			        if(pjObject::getPlugin($pjPlugin) !== NULL)
			        {
			            $this->set('params', $pjPlugin::getFormParams(array('payment_method' => $arr['payment_method']), array(
			                'locale_id'	 => $this->pjActionGetLocale(),
			                'return_url'	=> $this->option_arr['o_thank_you_page'],
			                'id'			=> $arr['id'],
			                'foreign_id'	=> $this->getForeignId(),
			                'uuid'		  => $arr['uuid'],
			                'name'		  => $arr['c_fname'].' '.$arr['c_lname'],
			                'email'		 => $arr['c_email'],
			                'phone'		 => $arr['c_phone'],
			                'amount'		=> $arr['deposit'],
			                'cancel_hash'   => sha1($arr['uuid'].strtotime($arr['created']).PJ_SALT),
			                'currency_code' => $this->option_arr['o_currency'],
			            )));
			        }
			        
			        if ($arr['payment_method'] == 'bank')
			        {
			            $bank_account = pjMultiLangModel::factory()
						    ->select('t1.content')
						    ->where('t1.model','pjOption')
						    ->where('t1.locale', $this->getLocaleId())
						    ->where('t1.foreign_id', $this->getForeignId())
						    ->where('t1.field', 'o_bank_account')
						    ->limit(1)
						    ->findAll()
						    ->getDataIndex(0);
					    $this->set('bank_account', $bank_account ? $bank_account['content'] : '');
			        }
			    }
		
				$this->set('arr', $arr);
				$this->set('get', $this->_get->raw());
			}
		}
	}
	
	public function pjActionCancel()
	{
		$this->setLayout('pjActionCancel');
		$is_ip_blocked = pjBase::isBlockedIp(pjUtil::getClientIp(), $this->option_arr);
		if($is_ip_blocked == true)
		{
			$this->set('status', 'IP_BLOCKED');
		} else {	
			$pjBookingModel = pjBookingModel::factory();
			
			if ($this->_post->check('booking_cancel'))
			{
				$booking_arr = pjBookingModel::factory()
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
					->find($this->_post->toInt('id'))
					->getData();
				if ($booking_arr)
				{
					$pjBookingModel->reset()->set('id', $booking_arr['id'])->modify(array('status' => 'cancelled'));
	
					$booking_arr['tickets'] = pjBookingTicketModel::factory()
						->join('pjMultiLang', "t2.model='pjTicket' AND t2.foreign_id=t1.ticket_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->join('pjTicket', "t3.id=t1.ticket_id", 'left')
						->select('t1.*, t2.content as title')
						->where('booking_id', $booking_arr['id'])
						->findAll()
						->getData();
					
					pjFrontEnd::pjActionConfirmSend($this->option_arr, $booking_arr, PJ_SALT, 'cancel', $this->getLocaleId());
					
					pjUtil::redirect($_SERVER['PHP_SELF'] . '?controller=pjFrontPublic&action=pjActionCancel&err=200');
				}
			}else{
				if ($this->_get->check('hash') && $this->_get->check('id'))
				{
					$arr = $pjBookingModel
						->select('t1.*, t2.departure_time, t2.arrival_time, t3.content as route_title, t4.content as from_location, t5.content as to_location, t6.content as country_title,
							AES_DECRYPT(t1.cc_type, "'.PJ_SALT.'") AS `cc_type`,	
							AES_DECRYPT(t1.cc_num, "'.PJ_SALT.'") AS `cc_num`,
							AES_DECRYPT(t1.cc_exp, "'.PJ_SALT.'") AS `cc_exp`,
							AES_DECRYPT(t1.cc_code, "'.PJ_SALT.'") AS `cc_code`
						')
						->join('pjBus', "t2.id=t1.bus_id", 'left outer')
						->join('pjMultiLang', "t3.model='pjRoute' AND t3.foreign_id=t2.route_id AND t3.field='title' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
						->join('pjMultiLang', "t4.model='pjCity' AND t4.foreign_id=t1.pickup_id AND t4.field='name' AND t4.locale='".$this->getLocaleId()."'", 'left outer')
						->join('pjMultiLang', "t5.model='pjCity' AND t5.foreign_id=t1.return_id AND t5.field='name' AND t5.locale='".$this->getLocaleId()."'", 'left outer')
						->join('pjMultiLang', "t6.model='pjCountry' AND t6.foreign_id=t1.c_country AND t6.field='name' AND t6.locale='".$this->getLocaleId()."'", 'left outer')
						->find($this->_get->toInt('id'))->getData();
											
					if (count($arr) == 0)
					{
						$this->set('status', 2);
					}else{
						if ($arr['status'] == 'cancelled')
						{
							$this->set('status', 4);
						}else{
							$hash = sha1($arr['id'] . $arr['created'] . PJ_SALT);
							if ($this->_get->toString('hash') != $hash)
							{
								$this->set('status', 3);
							}else{
								if($arr['booking_datetime'] > date('Y-m-d H:i:s'))
								{
									$arr['tickets'] = pjBookingTicketModel::factory()
										->join('pjMultiLang', "t2.model='pjTicket' AND t2.foreign_id=t1.ticket_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
										->join('pjTicket', "t3.id=t1.ticket_id", 'left')
										->select('t1.*, t2.content as title')
										->where('booking_id', $arr['id'])
										->findAll()
										->getData();
												 
									$this->set('arr', $arr);
								}else{
									$this->set('status', 5);
								}
							}
						}
					}
				}elseif (!$this->_get->check('err')) {
					$this->set('status', 1);
				}
			}
		}
	}
	
	public function pjActionPrintTickets()
	{
		$is_ip_blocked = pjBase::isBlockedIp(pjUtil::getClientIp(), $this->option_arr);
		if($is_ip_blocked == true)
		{
			$this->setLayout('pjActionEmpty');
			$this->set('status', 'IP_BLOCKED');
		} else {	
			$this->setLayout('pjActionPrint');
		
			$pjBookingModel = pjBookingModel::factory();
		
			$arr = $pjBookingModel
				->select('t1.*, t2.content as from_location, t3.content as to_location')
				->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.pickup_id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->join('pjMultiLang', "t3.model='pjCity' AND t3.foreign_id=t1.return_id AND t3.field='name' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
				->find($this->_get->toInt('id'))
				->getData();
		
			if(!empty($arr))
			{
				if ($arr['is_return'] == 'T')
				{
					$arr['return_arr'] = $pjBookingModel
						->reset()
						->select('t1.*, t2.content as from_location, t3.content as to_location')
						->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.pickup_id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->join('pjMultiLang', "t3.model='pjCity' AND t3.foreign_id=t1.return_id AND t3.field='name' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
						->find($arr['back_id'])->getData();
				}
					
				$hash = sha1($arr['id'].$arr['created'].PJ_SALT);
				if($hash == $this->_get->toString('hash'))
				{
					if($arr['status'] == 'confirmed')
					{
						$arr['tickets'] = pjBookingTicketModel::factory()->join('pjMultiLang', "t2.model='pjTicket' AND t2.foreign_id=t1.ticket_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
							->join('pjTicket', "t3.id=t1.ticket_id", 'left')
							->select('t1.*, t2.content as title, (SELECT TP.price FROM `'.pjPriceModel::factory()->getTable().'` AS TP WHERE TP.ticket_id = t1.ticket_id AND TP.bus_id = '.$arr['bus_id'].' AND TP.from_location_id = '.$arr['pickup_id'].' AND TP.to_location_id= '.$arr['return_id']. ' AND is_return = "F") as price')
							->where('booking_id', $arr['id'])
							->findAll()->getData();
		
						$pjCityModel = pjCityModel::factory();
						$pickup_location = $pjCityModel->reset()->select('t1.*, t2.content as name')->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')->find($arr['pickup_id'])->getData();
						$to_location = $pjCityModel->reset()->select('t1.*, t2.content as name')->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')->find($arr['return_id'])->getData();
						$arr['from_location'] = $pickup_location['name'];
						$arr['to_location'] = $to_location['name'];
		
						$pjMultiLangModel = pjMultiLangModel::factory();
						$lang_template = $pjMultiLangModel
							->reset()
							->select('t1.*')
							->where('t1.model','pjOption')
							->where('t1.locale', $this->getLocaleId())
							->where('t1.field', 'o_ticket_template')
							->limit(0, 1)
							->findAll()->getData();
						$template = '';
						if (count($lang_template) === 1)
						{
							$template = $lang_template[0]['content'];
						}
	
						$seats = pjBookingSeatModel::factory()
						->where('booking_id', $arr['id'])
						->groupBy("t1.seat_id")
						->findAll()
						->getDataPair('id');
						$this->set('seats', $seats);
						$this->set('template', $template);
						$this->set('arr', $arr);
					}
				}else{
					$this->set('status', 'ERR02');
				}
			}else{
				$this->set('status', 'ERR01');
			}
		}
	}
	
	public function pjActionScan()
	{
	    $this->setAjax(true);
	    if ($this->isXHR())
	    {
	        if (!$this->isLoged()) {
	            pjAppController::jsonResponse(array('status' => 'NOT_LOGIN'));
	        }
	        if ($this->_post->check('is_scan_ticket')) {
	            /* $ticket_arr = pjBookingTicketModel::factory()
	             ->where('ticket_id', $this->_post->toString('authenticate_code'))
	             ->findAll()
	             ->getData();
	             
	             if(count($ticket_arr) > 0)
	             {
    	             $hash = sha1($ticket_arr[0]['ticket_id'].PJ_SALT);
    	             pjAppController::jsonResponse(array('status' => 'OK', 'hash' => $hash));
	             }else{
	               pjAppController::jsonResponse(array('status' => 'ERR', 'text' => __('front_authenticate_code_invalid', true)));
	             } */
	        }
	    }
	}
	
	public function pjActionScanTicket()
	{
	    $this->setAjax(true);
	    if ($this->isXHR())
	    {
	        if (!$this->isLoged()) {
	            pjAppController::jsonResponse(array('status' => 'NOT_LOGIN'));
	        }
	        if ($this->_post->check('is_scan_ticket')) {
	            $validate_ticket = __('validate_ticket', true);
	            if ($this->_post->check('authenticate_code') && $this->_post->toString('authenticate_code') != '') {
	                $pjBookingSeatModel = pjBookingSeatModel::factory();
	                
	                $bs_arr = $pjBookingSeatModel->select('t1.*, t2.status, t3.name')
	                ->join('pjBooking', 't2.id=t1.booking_id', 'inner')
	                ->join('pjSeat', 't3.id=t1.seat_id', 'inner')
	                ->where('t1.qr_code', $this->_post->toString('authenticate_code'))
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
	        }
	    }
	}
	
	public function pjActionDriverProfile()
	{
	    $this->setAjax(true);
	    if ($this->isXHR())
	    {
	        if (!$this->isLoged()) {
	            pjAppController::jsonResponse(array('status' => 'NOT_LOGIN'));
	        }
	        $pjAuthUserModel = pjAuthUserModel::factory();
	        if ($this->_post->check('update_profile')) {
	            $pjAuthUserModel->set('id', $this->getUserId())->modify($this->_post->raw());
	            
	            $arr = $pjAuthUserModel->reset()->find($this->getUserId())->getData();
	            unset($arr['password']);
	            $_SESSION[$this->defaultUser] = $arr;
	            pjAppController::jsonResponse(array('status' => 'OK', 'text' => __('front_profile_updated_msg', true)));
	        }
	        $this->set('arr', $pjAuthUserModel->find($this->getUserId())->getData());
	    }
	}
}
?>