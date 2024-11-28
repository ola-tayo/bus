<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminBookings extends pjAdmin
{
	public $defaultPeriod = 'booking_period';
	
	public $defaultReturnPeriod = 'booking_return_period';
	
	public function pjActionCheckUniqueId()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
		    if (!$this->_get->check('uuid') || $this->_get->isEmpty('uuid'))
			{
				echo 'false';
				exit;
			}
			$pjBookingModel = pjBookingModel::factory()->where('t1.uuid', $this->_get->toString('uuid'));
			if ($this->_get->check('id')&& $this->_get->toInt('id') > 0)
			{
			    $pjBookingModel->where('t1.id !=', $this->_get->toInt('id'));
			}
			echo $pjBookingModel->findCount()->getData() == 0 ? 'true' : 'false';
		}
		exit;
	}
	
	public function pjActionCreate()
	{
		$this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }

		if(self::isPost() && $this->_post->check('booking_create'))
		{
			$pjBookingModel = pjBookingModel::factory();
				
			$bus_id = $this->_post->toInt('bus_id');
			$pickup_id = $this->_post->toInt('pickup_id');
			$return_id = $this->_post->toInt('return_id');
			
			$data = array();
			$data['ip'] = pjUtil::getClientIp();
			$data['booking_date'] = pjDateTime::formatDate($this->_post->toString('booking_date'), $this->option_arr['o_date_format']);
			if ($this->_post->check('is_return')) 
			{
				$data['return_date'] = pjDateTime::formatDate($this->_post->toString('return_date'), $this->option_arr['o_date_format']);
			}
			$data['booking_datetime'] = $data['booking_date'];
			if(isset($_SESSION[$this->defaultPeriod]))
			{
				$data['booking_datetime'] = $_SESSION[$this->defaultPeriod]['departure_time'];
				$data['stop_datetime'] = $_SESSION[$this->defaultPeriod]['arrival_time'];
			}
			$data['bus_departure_date'] = $data['booking_date'];
			$bus_data = pjBusModel::factory()->find($bus_id)->getData();
			$depart_date_time_iso = $data['booking_date'] . ' ' . $bus_data['departure_time'];
			if($depart_date_time_iso > $data ['booking_datetime'])
			{
				$data['bus_departure_date'] = date('Y-m-d', strtotime($depart_date_time_iso) - 86400);
			}
			
			$bt_arr = array();
			$pjBusLocationModel = pjBusLocationModel::factory();
			$_arr = $pjBusLocationModel->where('bus_id', $bus_id)->where("location_id", $pickup_id)->findAll()->getData();
			if(count($_arr) > 0)
			{
				$bt_arr[] = pjDateTime::formatTime(date('H:i:s', strtotime($_arr[0]['departure_time'])), "H:i:s", $this->option_arr['o_time_format']);
				if(!isset($_SESSION[$this->defaultPeriod]))
				{
					$data['booking_datetime'] .= ' ' . $_arr[0]['departure_time'];
				}
			}
			
			$_arr = $pjBusLocationModel->reset()->where('bus_id', $bus_id)->where("location_id", $return_id)->findAll()->getData();
			if(count($_arr) > 0)
			{
				$bt_arr[] = pjDateTime::formatTime(date('H:i:s', strtotime($_arr[0]['arrival_time'])), "H:i:s", $this->option_arr['o_time_format']);
			}
			$data['booking_time'] = join(" - ", $bt_arr);

			$data['sub_total'] = $this->_post->toFloat('pickup_sub_total');
			$data['tax'] = $this->_post->toFloat('pickup_tax');
			$data['total'] = $this->_post->toFloat('pickup_total');
			$data['deposit'] = $this->_post->toFloat('pickup_deposit');
			$id = pjBookingModel::factory(array_merge($this->_post->raw(), $data))->insert()->getInsertId();			
			if ($id !== false && (int) $id > 0)
			{
			    $client_name_arr = array();
			    if ($this->_post->check('c_fname') && $this->_post->toString('c_fname') != '') {
			        $client_name_arr[] = pjSanitize::clean($this->_post->toString('c_fname'));
			    }
		        if ($this->_post->check('c_lname') && $this->_post->toString('c_lname') != '') {
		            $client_name_arr[] = pjSanitize::clean($this->_post->toString('c_lname'));
			    }
			    $client_name = implode(' ', $client_name_arr);
			    $client_phone = $this->_post->check('c_phone') ? pjSanitize::clean($this->_post->toString('c_phone')) : '';
			    
				$bus_arr = pjBusModel::factory()
					->select('t1.*, t2.seats_map')
					->join('pjBusType', "t2.id=t1.bus_type_id", 'left')
					->find($bus_id)->getData();
				
				$ticket_arr = pjPriceModel::factory()
					 ->select("t1.*")
					 ->where('t1.bus_id', $bus_id)
					 ->where('t1.from_location_id', $pickup_id)
					 ->where('t1.to_location_id', $return_id)
					 ->where('is_return = "F"')
					 ->findAll()->getData();

				$location_pair = array();
				$location_arr = pjRouteCityModel::factory()->getLocations($bus_arr['route_id'], $pickup_id, $return_id);
					
				for($i = 0; $i < count($location_arr); $i++ )
				{
					$j = $i + 1;
					if($j < count($location_arr))
					{
						$location_pair[] = $location_arr[$i]['city_id'] . '-' . $location_arr[$j]['city_id'];
					}
				}
				
				$pjBookingTicketModel = pjBookingTicketModel::factory();
				foreach($ticket_arr as $v)
				{
					if ($this->_post->check('ticket_cnt_' . $v['ticket_id']) && $this->_post->toInt('ticket_cnt_' . $v['ticket_id']) > 0)
					{
						$ticket_data = array();
						$ticket_data['booking_id'] = $id;
						$ticket_data['ticket_id'] = $v['ticket_id'];
						$ticket_data['qty'] = $this->_post->toInt('ticket_cnt_' . $v['ticket_id']);
						$ticket_data['amount'] = $ticket_data['qty'] * $v['price'];
						$ticket_data['is_return'] = 'F';
						$pjBookingTicketModel->reset()->setAttributes($ticket_data)->insert();
					}
				}
				$pjBookingSeatModel = pjBookingSeatModel::factory();
				if(!empty($bus_arr['seats_map']))
				{
					$seat_id_arr = explode("|", $this->_post->toString('selected_seats'));
				}else{
					$seat_id_arr = $_REQUEST['assigned_seats'];
				}
				$tmp_qr_code_arr = array();
				foreach($location_pair as $pair)
				{
					$_arr = explode("-", $pair);
					$k = 0;
					foreach($ticket_arr as $v)
					{
						$qty = $this->_post->toInt('ticket_cnt_' . $v['ticket_id']);
						for($i = 1; $i <= $qty; $i ++)
						{
						    if (isset($tmp_qr_code_arr[$seat_id_arr[$k]])) {
						        $qr_code = $tmp_qr_code_arr[$seat_id_arr[$k]];
						    } else {
						        $qr_code = pjUtil::uuid();
						        $tmp_qr_code_arr[$seat_id_arr[$k]] = $qr_code;
						    }
						    
							$seat_data = array();
							$seat_data['booking_id'] = $id;
							$seat_data['seat_id'] = $seat_id_arr[$k];
							$seat_data['ticket_id'] = $v['ticket_id'];					
							$seat_data['start_location_id'] = $_arr[0];
							$seat_data['end_location_id'] = $_arr[1];
							$seat_data['is_return'] = 'F';					
							$seat_data['qr_code'] = $qr_code;
							$booking_seat_id = $pjBookingSeatModel->reset()->setAttributes($seat_data)->insert()->getInsertId();
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
							$k++;
						}
					}
				}
				
				if ($this->_post->check('is_return') && $this->_post->check('return_bus_id')) 
				{
					$data['bus_id'] = $this->_post->toInt('return_bus_id');
					$data['booking_date'] = pjDateTime::formatDate($this->_post->toString('return_date'), $this->option_arr['o_date_format']);
					$data['booking_datetime'] = $data['booking_date'];
					if(isset($_SESSION[$this->defaultPeriod]))
					{
						$data['booking_datetime'] = $_SESSION[$this->defaultReturnPeriod]['departure_time'];
						$data['stop_datetime'] = $_SESSION[$this->defaultReturnPeriod]['arrival_time'];
					}
					$data['uuid'] = pjUtil::uuid();
					$data['pickup_id'] = $this->_post->toInt('return_id');
					$data['return_id'] = $this->_post->toInt('pickup_id');
					$data['is_return'] = 'F';
					unset($data['return_date']);
					$data['booking_route'] = $this->_post->toString('booking_return_route');				
					$data['bus_departure_date'] = $data['booking_date'];
					$bus_data = pjBusModel::factory()->find($this->_post->toInt('return_bus_id'))->getData();
					$depart_date_time_iso = $data['booking_date'] . ' ' . $bus_data['departure_time'];
					if($depart_date_time_iso > $data['booking_datetime'])
					{
						$data['bus_departure_date'] = date('Y-m-d', strtotime($depart_date_time_iso) - 86400);
					}
					
					$bt_arr = array();
					$pjBusLocationModel = pjBusLocationModel::factory();
					$_arr = $pjBusLocationModel->where('bus_id', $this->_post->toInt('return_bus_id'))->where("location_id", $return_id)->findAll()->getData();
					if(count($_arr) > 0)
					{
						$bt_arr[] = pjDateTime::formatTime(date('H:i:s', strtotime($_arr[0]['departure_time'])), "H:i:s", $this->option_arr['o_time_format']);
						if(!isset($_SESSION[$this->defaultPeriod]))
						{
							$data['booking_datetime'] .= ' ' . $_arr[0]['departure_time'];
						}
					}
				
					$_arr = $pjBusLocationModel->reset()->where('bus_id', $this->_post->toInt('return_bus_id'))->where("location_id", $pickup_id)->findAll()->getData();
					if(count($_arr) > 0)
					{
						$bt_arr[] = pjDateTime::formatTime(date('H:i:s', strtotime($_arr[0]['arrival_time'])), "H:i:s", $this->option_arr['o_time_format']);
					}
					$data['booking_time'] = join(" - ", $bt_arr);				
					$data['sub_total'] = $this->_post->toFloat('return_sub_total');
					$data['tax'] = $this->_post->toFloat('return_tax');
					$data['total'] = $this->_post->toFloat('return_total');
					$data['deposit'] = $this->_post->toFloat('return_deposit');					
					$return_booking_id = pjBookingModel::factory(array_merge($this->_post->raw(), $data))->insert()->getInsertId();
					if ($return_booking_id !== false && (int) $return_booking_id > 0)
					{	
						$pjBookingModel
							->reset()
							->set('id', $id)
							->modify(array('back_id' => $return_booking_id));
					
						$pjBookingModel
							->reset()
							->set('id', $return_booking_id)
							->modify(array('back_id' => $id));
					
						$return_bus_arr = pjBusModel::factory()
							->select('t1.*, t2.seats_map')
							->join('pjBusType', "t2.id=t1.bus_type_id", 'left')
							->find($this->_post->toInt('return_bus_id'))->getData();
						$return_ticket_arr = pjPriceModel::factory()
							->reset()
							->select("t1.*, t2.discount")
							->join('pjBus', 't1.bus_id = t2.id', 'left')
							->where('t1.bus_id', $this->_post->toInt('return_bus_id'))
							->where('t1.from_location_id', $return_id)
							->where('t1.to_location_id', $pickup_id)
							->where('is_return = "F"')
							->findAll()->getData();
						
						foreach($return_ticket_arr as $v)
						{
							if ($this->_post->check('return_ticket_cnt_' . $v['ticket_id']) && $this->_post->toInt('return_ticket_cnt_' . $v['ticket_id']) > 0) 
							{
								$price = $v['price'] - ($v['price'] * $v['discount'] / 100);
								$ticket_data = array();
								$ticket_data['booking_id'] = $return_booking_id;
								$ticket_data['ticket_id'] = $v['ticket_id'];
								$ticket_data['qty'] = $this->_post->toInt('return_ticket_cnt_' . $v['ticket_id']);
								$ticket_data['amount'] = $ticket_data['qty'] * $price;
								$ticket_data['is_return'] = 'T';
								$pjBookingTicketModel->reset()->setAttributes($ticket_data)->insert();
							}
						}
						if(!empty($return_bus_arr['seats_map']))
						{
							$seat_id_arr = explode("|", $this->_post->toString('return_selected_seats'));
						}else{
							$seat_id_arr = $this->_post->toString('assigned_return_seats');
						}
						$tmp_qr_code_arr = array();
						foreach($location_pair as $pair)
						{
							$_arr = explode("-", $pair);
							$k = 0;
							foreach($return_ticket_arr as $v)
							{
								$qty = $this->_post->toInt('return_ticket_cnt_' . $v['ticket_id']);
								for($i = 1; $i <= $qty; $i ++)
								{
								    if (isset($tmp_qr_code_arr[$seat_id_arr[$k]])) {
								        $qr_code = $tmp_qr_code_arr[$seat_id_arr[$k]];
								    } else {
								        $qr_code = pjUtil::uuid();
								        $tmp_qr_code_arr[$seat_id_arr[$k]] = $qr_code;
								    }
								    
									$seat_data = array();
									$seat_data['booking_id'] = $return_booking_id;
									$seat_data['seat_id'] = $seat_id_arr[$k];
									$seat_data['ticket_id'] = $v['ticket_id'];			
									$seat_data['start_location_id'] = $_arr[0];
									$seat_data['end_location_id'] = $_arr[1];
									$seat_data['is_return'] = 'T';			
									$seat_data['qr_code'] = $qr_code;
									$return_booking_seat_id = $pjBookingSeatModel->reset()->setAttributes($seat_data)->insert()->getInsertId();
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
									$k++;
								}
							}
						}
					}
				}
				if(isset($_SESSION[$this->defaultPeriod]))
				{
				    unset($_SESSION[$this->defaultPeriod]);
				}
				if(isset($_SESSION[$this->defaultReturnPeriod]))
				{
				    unset($_SESSION[$this->defaultReturnPeriod]);
				}
				$err = 'ABB03';
			}else{
				$err = 'ABB04';
			}
			
			pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminBookings&action=pjActionIndex&err=$err");
		}
		
		$this->set('country_arr', pjBaseCountryModel::factory()
			->select('t1.*, t2.content AS name')
			->join('pjMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
			->where('t1.status', 'T')
			->orderBy('`name` ASC')->findAll()->getData()
		);
		
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

		$this->set('from_location_arr', $from_location_arr);
		$this->set('to_location_arr', $to_location_arr);

		if($this->_get->check('bus_id') && $this->_get->toInt('bus_id') > 0)
		{
			$bus_id = $this->_get->toInt('bus_id');
			$pickup_id = $this->_get->toInt('pickup_id');
			$return_id = $this->_get->toInt('return_id');
								
			$date = pjDateTime::formatDate($this->_get->toString('date'), $this->option_arr['o_date_format']);
			$day_of_week = strtolower(date('l', strtotime($date)));
			
			$pjBusModel = pjBusModel::factory();

			$data = $pjBusModel
				->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.route_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->join('pjBusType', "t3.id=t1.bus_type_id", 'left outer')
				->select(" t1.*, t2.content AS route, t3.seats_map")
				->where("(t1.start_date <= '$date' AND '$date' <= t1.end_date) AND (t1.recurring LIKE '%$day_of_week%') AND t1.id NOT IN (SELECT TSD.bus_id FROM `".pjBusDateModel::factory()->getTable()."` AS TSD WHERE TSD.`date` = '$date')")
				->where("(t1.route_id IN(SELECT TRD.route_id FROM `".pjRouteDetailModel::factory()->getTable()."` AS TRD WHERE (TRD.from_location_id = ".$pickup_id." AND TRD.to_location_id = ".$return_id.")))")
				->orderBy("route asc")
				->findAll()
				->getData();
							 	
			foreach($data as $k => $v)
			{
				if(!empty($v['start_date']) && !empty($v['end_date']))
				{
					$v['from_to'] = pjDateTime::formatDate($v['start_date'], "Y-m-d", $this->option_arr['o_date_format']) . ' - ' . pjUtil::formatDate($v['end_date'], "Y-m-d", $this->option_arr['o_date_format']);
				}else{
					$v['from_to'] = '';
				}
				if(!empty($v['departure_time']) && !empty($v['arrival_time']))
				{
					$v['depart_arrive'] = pjDateTime::formatTime(date('H:i:s', strtotime($v['departure_time'])), "H:i:s", $this->option_arr['o_time_format']) . ' - ' . pjUtil::formatTime($v['arrival_time'], "H:i:s", $this->option_arr['o_time_format']);
				}else{
					$v['depart_arrive'] = '';
				}
				$data[$k] = $v;
			}
			$this->set('bus_arr', $data);
			
			$bus = $pjBusModel->reset()->find($bus_id)->getData();
			$this->set('bus', $bus);
			
			$route_id = $bus['route_id'];
			
			$location_id_arr = pjRouteCityModel::factory()->getLocationIdPair($route_id, $pickup_id, $return_id);
			
			$ticket_arr = pjPriceModel::factory()
				->reset()
				->join('pjTicket', 't1.ticket_id = t2.id', 'left')
				->join('pjMultiLang', "t3.model='pjTicket' AND t3.foreign_id=t1.ticket_id AND t3.field='title' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
				->join('pjBus', 't1.bus_id = t4.id', 'left')
				->select("t1.*, t2.seats_count, t3.content as ticket, t4.discount")
				->where('t1.bus_id', $bus_id)
				->where('t1.from_location_id', $pickup_id)
				->where('t1.to_location_id', $return_id)
				->where('is_return = "F"')
				->index("FORCE KEY (`ticket_id`)")
				->orderBy("ticket ASC")
				->findAll()
				->getData();
			
			$bus_type_arr = pjBusTypeModel::factory()->find($bus['bus_type_id'])->getData();
			if($location_id_arr && $bus['set_seats_count'] == 'F')
			{
				$seats_available = $bus_type_arr['seats_count'];
				$cnt_arr = pjBookingSeatModel::factory()
					->select("COUNT(DISTINCT t1.seat_id) as cnt_booked")
					->where("t1.start_location_id IN(".join(",", $location_id_arr).") 
					         AND t1.booking_id IN(SELECT TB.id 
					                              FROM `".pjBookingModel::factory()->getTable()."` AS TB 
					                              WHERE (TB.status='confirmed' 
					                                     OR (TB.status='pending' AND UNIX_TIMESTAMP(TB.created) >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL ".$this->option_arr['o_min_hour']." MINUTE)))) 
					                                     AND TB.bus_id = $bus_id AND TB.booking_date = '$date')")
					->findAll()
					->getData();
				
				$cnt_booked = 0;
				if(count($cnt_arr) > 0)
				{
					$cnt_booked = $cnt_arr[0]['cnt_booked'];
				}
				$seats_available -= $cnt_booked;
				$this->set('seats_available', $seats_available);
			}
			
			$this->set('ticket_arr', $ticket_arr);
			$this->set('bus_type_arr', $bus_type_arr);
		}

		$this->appendCss('css/select2.min.css', PJ_THIRD_PARTY_PATH . 'select2/');
		$this->appendJs('js/select2.full.min.js', PJ_THIRD_PARTY_PATH . 'select2/');
		$this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
		$this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
		$this->appendJs('pjAdminBookings.js');
	}
	
	public function pjActionDeleteBooking()
	{
		$this->setAjax(true);
	
		if (!$this->isXHR())
		{
			self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
		}
		
		if (!self::isGet() && !$this->_get->check('id') && $this->_get->toInt('id') < 0)
		{
			self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
		}
		
		if (pjBookingModel::factory()->set('id', $this->_get->toInt('id'))->erase()->getAffectedRows() == 1)
		{
		    $seat_arr = pjBookingSeatModel::factory()->where('booking_id', $this->_get->toInt('id'))->findAll()->getData();
			pjBookingTicketModel::factory()->where('booking_id', $this->_get->toInt('id'))->eraseAll();
			pjBookingSeatModel::factory()->reset()->where('booking_id', $this->_get->toInt('id'))->eraseAll();
			pjBookingPaymentModel::factory()->where('booking_id', $this->_get->toInt('id'))->eraseAll();
			foreach ($seat_arr as $val) {
			    if (file_exists(PJ_INSTALL_PATH . 'qr_'.$val['qr_code'].'.png'))
			    {
			        @unlink(PJ_INSTALL_PATH . 'qr_'.$val['qr_code'].'.png');
			    }
			}
			$response = array('status' => 'OK');
		} else {
			$response = array('status' => 'ERR');
		}
		
		self::jsonResponse($response);
	}
	
	public function pjActionDeleteBookingBulk()
	{
		$this->setAjax(true);
	
		if (!$this->isXHR())
		{
			self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
		}
		
		if (!self::isPost())
		{
			self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
		}

		if (!$this->_post->has('record') || !($record = $this->_post->toArray('record')))
		{
			self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Missing, empty or invalid data.'));
		}
		
		if (pjBookingModel::factory()->whereIn('id', $record)->eraseAll()->getAffectedRows() > 0)
		{
		    $seat_arr = pjBookingSeatModel::factory()->whereIn('booking_id', $record)->findAll()->getData();
			pjBookingTicketModel::factory()->whereIn('booking_id', $record)->eraseAll();
			pjBookingSeatModel::factory()->reset()->whereIn('booking_id', $record)->eraseAll();
			pjBookingPaymentModel::factory()->whereIn('booking_id', $record)->eraseAll();
			foreach ($seat_arr as $val) {
			    if (file_exists(PJ_INSTALL_PATH . 'qr_'.$val['qr_code'].'.png'))
			    {
			        @unlink(PJ_INSTALL_PATH . 'qr_'.$val['qr_code'].'.png');
			    }
			}
			self::jsonResponse(array('status' => 'OK'));
		}
		
		self::jsonResponse(array('status' => 'ERR'));
	}
	
	public function pjActionExportBooking()
	{
		if ($record = $this->_post->toArray('record'))
		{
			$arr = pjBookingModel::factory()
				->select('t1.*,
					AES_DECRYPT(t1.cc_type, "'.PJ_SALT.'") AS `cc_type`,	
					AES_DECRYPT(t1.cc_num, "'.PJ_SALT.'") AS `cc_num`,
					AES_DECRYPT(t1.cc_exp, "'.PJ_SALT.'") AS `cc_exp`,
					AES_DECRYPT(t1.cc_code, "'.PJ_SALT.'") AS `cc_code`
				')
				->whereIn('id', $record)->findAll()->getData();
			$csv = new pjCSV();
			$csv
				->setHeader(true)
				->setName("Bookings-".time().".csv")
				->process($arr)
				->download();
		}
		exit;
	}
	
	public function pjActionGetBooking()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjBookingModel = pjBookingModel::factory()
				->join('pjBus', "t2.id=t1.bus_id", 'left outer')
				->join('pjMultiLang', "t3.model='pjRoute' AND t3.foreign_id=t2.route_id AND t3.field='title' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
				->join('pjMultiLang', "t4.model='pjCity' AND t4.foreign_id=t1.pickup_id AND t4.field='name' AND t4.locale='".$this->getLocaleId()."'", 'left outer')
				->join('pjMultiLang', "t5.model='pjCity' AND t5.foreign_id=t1.return_id AND t5.field='name' AND t5.locale='".$this->getLocaleId()."'", 'left outer')
				;
			if ($q = $this->_get->toString('q'))
			{
				$q = str_replace(array('%', '_'), array('\%', '\_'), trim($q));
				$pjBookingModel->where("(t1.id = '$q' OR t1.uuid = '$q' OR t1.c_fname LIKE '%$q%' OR t1.c_lname LIKE '%$q%' OR t1.c_email LIKE '%$q%')");
			}
			
			if ($this->_get->check('bus_id') && $this->_get->toInt('bus_id') > 0)
			{
				$bus_id = $this->_get->toInt('bus_id');
				$pjBookingModel->where("(t1.bus_id='".$bus_id."')");
			}
			if ($this->_get->check('route_id') && $this->_get->toInt('route_id') > 0)
			{
				$route_id = $this->_get->toInt('route_id');
				$pjBookingModel->where("(t1.bus_id IN (SELECT TB.id FROM `".pjBusModel::factory()->getTable()."` AS TB WHERE TB.route_id=$route_id))");
			}
			
			if ($this->_get->check('status') && $this->_get->toString('status') != '' && in_array($this->_get->toString('status'), array('confirmed','cancelled','pending')))
			{
				$pjBookingModel->where('t1.status', $this->_get->toString('status'));
			}
			if ($this->_get->check('date_from') && $this->_get->toString('date_from') != '' && $this->_get->check('date_to') && $this->_get->toString('date_to') != '')
			{
				$df = pjDateTime::formatDate($this->_get->toString('date_from'), $this->option_arr['o_date_format']);
				$dt = pjDateTime::formatDate($this->_get->toString('date_to'), $this->option_arr['o_date_format']);
				$pjBookingModel->where("(t1.booking_date BETWEEN '$df' AND '$dt')");
				
			} else {
				if ($this->_get->check('date_from') && $this->_get->toString('date_from') != '')
				{
					$df = pjDateTime::formatDate($this->_get->toString('date_from'), $this->option_arr['o_date_format']);
					$pjBookingModel->where("(t1.booking_date >= '$df')");
				} elseif ($this->_get->check('date_to') && $this->_get->toString('date_to') != '') {
					$dt = pjDateTime::formatDate($this->_get->toString('date_to'), $this->option_arr['o_date_format']);
					$pjBookingModel->where("(t1.booking_date <= '$dt')");
				}
			}
			
			$column = 'created';
			$direction = 'DESC';
			if ($this->_get->check('direction') && $this->_get->check('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
			{
				$column = $this->_get->toString('column');
				$direction = strtoupper($this->_get->toString('direction'));
			}

			$total = $pjBookingModel->findCount()->getData();
			$rowCount = $this->_get->check('rowCount') && $this->_get->toInt('rowCount') > 0 ? $this->_get->toInt('rowCount') : 10;
			$pages = ceil($total / $rowCount);
			$page = $this->_get->check('page') && $this->_get->toInt('page') > 0 ? $this->_get->toInt('page') : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjBookingModel
				->select('t1.*, t2.departure_time, t2.arrival_time, t3.content as route_title, t4.content as from_location, t5.content as to_location,
					AES_DECRYPT(t1.cc_type, "'.PJ_SALT.'") AS `cc_type`,	
					AES_DECRYPT(t1.cc_num, "'.PJ_SALT.'") AS `cc_num`,
					AES_DECRYPT(t1.cc_exp, "'.PJ_SALT.'") AS `cc_exp`,
					AES_DECRYPT(t1.cc_code, "'.PJ_SALT.'") AS `cc_code`
				')
				->orderBy("$column $direction")
				->limit($rowCount, $offset)
				->findAll()
				->getData();
			foreach($data as $k => $v)
			{
				$route_details = '';
				$client_arr = array();
				if(!empty($v['c_fname']))
				{
					$client_arr[] = $v['c_fname'];
				}
				if(!empty($v['c_lname']))
				{
					$client_arr[] = $v['c_lname'];
				}
				$v['client'] = join(" ", $client_arr) . "<br/>" . $v['c_email'];
				$v['date_time'] = date($this->option_arr['o_date_format'] . ', ' . $this->option_arr['o_time_format'], strtotime($v['booking_datetime'])) . '<br/>' . date($this->option_arr['o_date_format'] . ', ' . $this->option_arr['o_time_format'], strtotime($v['stop_datetime']));
				if(date($this->option_arr['o_date_format'], strtotime($v['booking_datetime'])) == date($this->option_arr['o_date_format'], strtotime($v['stop_datetime'])))
				{
					$v['date_time'] = date($this->option_arr['o_date_format'], strtotime($v['booking_datetime'])) . '<br/>' . date($this->option_arr['o_time_format'], strtotime($v['booking_datetime'])) . ' - ' . date($this->option_arr['o_time_format'], strtotime($v['stop_datetime']));
				}
				
				$route_details .= $v['route_title'];
				$route_details .= ', ' . date($this->option_arr['o_time_format'], strtotime($v['departure_time'])) . ' - ' . date($this->option_arr['o_time_format'], strtotime($v['arrival_time']));
				$route_details .= '<br/>'  . mb_strtolower(__('lblFrom', true), 'UTF-8') . ' ' . $v['from_location'] . ' ' . mb_strtolower(__('lblTo', true), 'UTF-8') . ' ' . $v['to_location'];
				
				$v['route_details'] = $route_details;
				$data[$k] = $v;
			}			
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
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
		
		$_arr = pjBusModel::factory()
			->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.route_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
			->select("t1.*, t2.content AS route")
			->orderBy("route ASC")
			->findAll()
			->getData();
			
		foreach($_arr as $k => $v)
		{
			if(!empty($v['departure_time']) && !empty($v['arrival_time']))
			{
				$v['depart_arrive'] = pjDateTime::formatTime(date('H:i:s', strtotime($v['departure_time'])), "H:i:s", $this->option_arr['o_time_format']) . ' - ' . pjDateTime::formatTime(date('H:i:s', strtotime($v['arrival_time'])), "H:i:s", $this->option_arr['o_time_format']);
			}else{
				$v['depart_arrive'] = '';
			}
			$_arr[$k] = $v;
		}
		
		$route_arr = pjRouteModel::factory()
			->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
			->select("t1.*, t2.content AS route")
			->orderBy("route ASC")
			->findAll()
			->getData();
		
		$this->set('route_arr', $route_arr);
		$this->set('bus_arr', $_arr);
		
		$this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
	    $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
	    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
	    $this->appendJs('pjAdminBookings.js');
	}
	
	public function pjActionSaveBooking()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjBookingModel = pjBookingModel::factory();
			if (!in_array($this->_post->toString('column'), $pjBookingModel->getI18n()))
			{
				$pjBookingModel->set('id', $this->_get->toInt('id'))->modify(array($this->_post->toString('column') => $this->_post->toString('value')));
			} else {
			    pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($this->_post->toString('column') => $this->_post->toString('value'))), $this->_get->toInt('id'), 'pjBooking');
			}
		}
		exit;
	}
		
	public function pjActionUpdate()
	{
		$this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
		
		$pjBookingModel = pjBookingModel::factory();

		if ($this->_get->check('id') && $this->_get->toInt('id') > 0)
		{
			$pjBookingModel->where('t1.id', $this->_get->toInt('id'));
		} elseif ($this->_get->check('uuid') && $this->_get->isEmpty('uuid')) {
			$pjBookingModel->where('t1.uuid', $this->_get->toString('uuid'));
		} else {
			$pjBookingModel->where('t1.id', '0');
		}
		
		$arr = $pjBookingModel
			->select("t1.*, t2.content AS country")
			->join('pjMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.c_country AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
			->limit(1)
			->findAll()->getData();
		
		if (empty($arr) || count($arr) == 0)
		{
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionIndex&err=ABB08");
		}
		$arr = $arr[0];		
		if (self::isPost() && $this->_post->check('booking_update'))
		{
			$bus_id = $this->_post->toInt('bus_id');
			$pickup_id = $this->_post->toInt('pickup_id');
			$return_id = $this->_post->toInt('return_id');
			$booking_date = pjDateTime::formatDate($this->_post->toString('booking_date'), $this->option_arr['o_date_format']);
			$data = array();
			$data['ip'] = pjUtil::getClientIp();
			$data['booking_date'] = $booking_date;
			$data['booking_datetime'] = $data['booking_date'];
			$data['stop_datetime'] = $data['booking_date'];
			$data['bus_departure_date'] = $data['booking_date'];
			$bt_arr = array();
			
			$index = $this->defaultPeriod;
				
			if (isset($_SESSION[$index]))
			{
				$data['booking_datetime'] = $_SESSION[$index]['departure_time'];
				$data['stop_datetime'] = $_SESSION[$index]['arrival_time'];
			}
			
			$pjBusLocationModel = pjBusLocationModel::factory();
			$_arr = $pjBusLocationModel
				->where('bus_id', $bus_id)
				->where("location_id", $pickup_id)
				->limit(1)
				->findAll()
				->getDataIndex(0);
			if ($_arr)
			{
				$bt_arr[] = pjDateTime::formatTime(date('H:i:s', strtotime($_arr['departure_time'])), "H:i:s", $this->option_arr['o_time_format']);
				if (!isset($_SESSION[$index]))
				{
					$data['booking_datetime'] .= ' ' . $_arr['departure_time'];
				}
			}
			
			$_arr = $pjBusLocationModel
				->reset()
				->where('bus_id', $bus_id)
				->where("location_id", $return_id)
				->limit(1)
				->findAll()
				->getDataIndex(0);
			if ($_arr)
			{
				$bt_arr[] = pjDateTime::formatTime(date('H:i:s', strtotime($_arr['arrival_time'])), "H:i:s", $this->option_arr['o_time_format']);
				if (!isset($_SESSION[$index]))
				{
					$data['stop_datetime'] .=  ' ' . $_arr['arrival_time'];
				}
			}
			$data['booking_time'] = join(" - ", $bt_arr);
			$pjBookingModel->reset()->set('id', $this->_post->toInt('id'))->modify(array_merge($this->_post->raw(), $data));
			
			$bus_arr = pjBusModel::factory()
				->select('t1.*, t2.seats_map')
				->join('pjBusType', "t2.id=t1.bus_type_id", 'left')
				->find($bus_id)->getData();
			$ticket_arr = pjPriceModel::factory()
				->select("t1.*")
				->where('t1.bus_id', $bus_id)
				->where('t1.from_location_id', $pickup_id)
				->where('t1.to_location_id', $return_id)
				->where('t1.is_return = "F"')
				->findAll()
				->getData();
							 
			$location_pair = array();
			$location_arr = pjRouteCityModel::factory()->getLocations($bus_arr['route_id'], $pickup_id, $return_id);
			
			for($i = 0; $i < count($location_arr); $i++ )
			{
				$j = $i + 1;
				if($j < count($location_arr))
				{
					$location_pair[] = $location_arr[$i]['city_id'] . '-' . $location_arr[$j]['city_id'];
				}
			}
			
			$pjBookingTicketModel = pjBookingTicketModel::factory();
			$pjBookingTicketModel->where('booking_id', $this->_post->toInt('id'))->where('is_return = "F"')->eraseAll();
			
			foreach($ticket_arr as $v)
			{
				if ($this->_post->check('ticket_cnt_' . $v['ticket_id']) && $this->_post->toInt('ticket_cnt_' . $v['ticket_id']) > 0)
				{
					$data = array();
					$data['booking_id'] = $this->_post->toInt('id');
					$data['ticket_id'] = $v['ticket_id'];
					$data['qty'] = $this->_post->toInt('ticket_cnt_' . $v['ticket_id']);
					$data['amount'] = $data['qty'] * $v['price'];
					$data['is_return'] = 'F';
					$pjBookingTicketModel->reset()->setAttributes($data)->insert();
				}
			}
			if (($arr['is_return'] == 'F') && !empty($arr['back_id'])) {
				$pjBookingModel->reset()->set('id', $arr['back_id'])->modify(array('return_date' => $booking_date));
				$pjBookingTicketModel->reset()->where('booking_id', $arr['back_id'])->where('is_return = "T"')->eraseAll();
					
				$return_ticket_arr = pjPriceModel::factory()
					->select("t1.*, t2.discount")
					->join('pjBus', 't1.bus_id = t2.id', 'left')
					->where('t1.bus_id', $bus_id)
					->where('t1.from_location_id', $pickup_id)
					->where('t1.to_location_id', $return_id)
					->where('t1.is_return = "T"')
					->findAll()
					->getData();
					
				foreach($return_ticket_arr as $v)
				{
					if ($this->_post->check('ticket_cnt_' . $v['ticket_id']) && $this->_post->toInt('ticket_cnt_' . $v['ticket_id']) > 0)
					{
						$price = $v['price'] - ($v['price'] * $v['discount'] / 100);
						$data = array();
						$data['booking_id'] = $arr['back_id'];
						$data['ticket_id'] = $v['ticket_id'];
						$data['qty'] = $this->_post->toInt('ticket_cnt_' . $v['ticket_id']);
						$data['amount'] = $data['qty'] * $price;
						$data['is_return'] = 'T';
						$pjBookingTicketModel->reset()->setAttributes($data)->insert();
					}
				}
			}				 
			$pjBookingSeatModel = pjBookingSeatModel::factory();
			
			$bs_arr = array();
			$_bs_arr = $pjBookingSeatModel->where('booking_id', $_POST['id'])->groupBy("t1.seat_id")->findAll()->getData();
			foreach ($_bs_arr as $bs) {
			    $bs_arr[$bs['seat_id']] = array(
			        'code' => $bs['qr_code'],
			        'is_used' => $bs['is_used']
			    );
			}
			
			$pjBookingSeatModel->where('booking_id', $this->_post->toInt('id'))->eraseAll();
			if(!empty($bus_arr['seats_map']))
			{
				$seat_id_arr = explode("|", $this->_post->toString('selected_seats'));
			}else{
				$seat_id_arr = $_REQUEST['assigned_seats'];
			}
			$is_return = $arr['is_return'] == 'F' && $arr['back_id'] ? 'T' : 'F';
			
			$tmp_qr_code_arr = array();
			$client_name_arr = array();
			if ($this->_post->check('c_fname') && $this->_post->toString('c_fname') != '') {
			    $client_name_arr[] = pjSanitize::clean($this->_post->toString('c_fname'));
			}
			if ($this->_post->check('c_lname') && $this->_post->toString('c_lname') != '') {
			    $client_name_arr[] = pjSanitize::clean($this->_post->toString('c_lname'));
			}
			$client_name = implode(' ', $client_name_arr);
			$client_phone = $this->_post->check('c_phone') ? pjSanitize::clean($this->_post->toString('c_phone')) : '';
			
			foreach($location_pair as $pair)
			{
				$_arr = explode("-", $pair);
				$k = 0;
				foreach($ticket_arr as $v)
				{
					$qty = $this->_post->toInt('ticket_cnt_' . $v['ticket_id']);
					for($i = 1; $i <= $qty; $i ++)
					{
					    if (isset($bs_arr[$seat_id_arr[$k]])) {
					        $qr_code = $bs_arr[$seat_id_arr[$k]]['code'];
					        $is_used = $bs_arr[$seat_id_arr[$k]]['is_used'];
					    } else {
					        $is_used = 0;
					        if (isset($tmp_qr_code_arr[$seat_id_arr[$k]])) {
					            $qr_code = $tmp_qr_code_arr[$seat_id_arr[$k]];
					        } else {
					            $qr_code = pjUtil::uuid();
					            $tmp_qr_code_arr[$seat_id_arr[$k]] = $qr_code;
					        }
					    }
					    
						$data = array();
						$data['booking_id'] = $this->_post->toInt('id');
						$data['seat_id'] = $seat_id_arr[$k];
						$data['ticket_id'] = $v['ticket_id'];						
						$data['start_location_id'] = $_arr[0];
						$data['end_location_id'] = $_arr[1];
						$data['is_return'] = $is_return;						
						$data['qr_code'] = $qr_code;
						$data['is_used'] = $is_used;
						$booking_seat_id = $pjBookingSeatModel->reset()->setAttributes($data)->insert()->getInsertId();
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
						$k++;
					}
				}
			}
			
			if(isset($_SESSION[$this->defaultPeriod]))
			{
			    unset($_SESSION[$this->defaultPeriod]);
			}
			if(isset($_SESSION[$this->defaultReturnPeriod]))
			{
			    unset($_SESSION[$this->defaultReturnPeriod]);
			}
			
			$err = 'ABB01';
			pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminBookings&action=pjActionIndex&err=$err");
		} else {
			unset($_SESSION[$this->defaultPeriod]);
			unset($_SESSION[$this->defaultReturnPeriod]);
			
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
				
			$this->set('from_location_arr', $from_location_arr);
			$this->set('to_location_arr', $to_location_arr);
			
			$day_of_week = strtolower(date('l', strtotime($arr['booking_date'])));
			$bus_id = $arr['bus_id'];
			$pickup_id = $arr['pickup_id'];
			$return_id = $arr['return_id'];
			$booking_date = $arr['booking_date'];
			
			$data = array();
			$bus_id_arr = pjBusModel::factory()->getBusIds($booking_date, $pickup_id, $return_id, $arr['id']);
			if(!empty($bus_id_arr))
			{
			    $data = pjBusModel::factory()
			    ->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.route_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
			    ->join('pjBusType', "t3.id=t1.bus_type_id", 'left outer')
			    ->select(" t1.*, t2.content AS route, t3.seats_map")
			    ->whereIn('t1.id', $bus_id_arr)
			    ->orderBy("route asc")
			    ->findAll()
			    ->getData();
			    foreach($data as $k => $v)
			    {
			        if(!empty($v['departure_time']) && !empty($v['arrival_time']))
			        {
			            $v['depart_arrive'] = pjDateTime::formatTime(date('H:i:s', strtotime($v['departure_time'])), "H:i:s", $this->option_arr['o_time_format']) . ' - ' . pjDateTime::formatTime(date('H:i:s', strtotime($v['arrival_time'])), "H:i:s", $this->option_arr['o_time_format']);
			        }else{
			            $v['depart_arrive'] = '';
			        }
			        $data[$k] = $v;
			    }
			}
			$this->set('bus_arr', $data);
			
			$bus_arr = pjBusModel::factory()->find($arr['bus_id'])->getData();
			$this->set('bus', $bus_arr);
							
			$location_id_arr = pjRouteCityModel::factory()->getLocationIdPair($bus_arr['route_id'], $pickup_id, $return_id);
			if (count($location_id_arr) == 0) 
			{
				$location_id_arr[] = 0;
			}
			
			$pjPriceModel = pjPriceModel::factory();
			$pjBookingSeatModel = pjBookingSeatModel::factory();
			
			$ticket_arr = $pjPriceModel
				->reset()
				->join('pjTicket', 't1.ticket_id = t2.id', 'left')
				->join('pjMultiLang', "t3.model='pjTicket' AND t3.foreign_id=t1.ticket_id AND t3.field='title' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
				->join('pjBus', 't1.bus_id = t4.id', 'left')
				->select("t1.*, t2.seats_count, t3.content as ticket, t4.discount")
				->where('t1.bus_id', $bus_id)
				->where('t1.from_location_id', $pickup_id)
				->where('t1.to_location_id', $return_id)
				->where('is_return = "F"')
				->orderBy("ticket ASC")
				->findAll()->getData();
			
			$bus_type_arr = pjBusTypeModel::factory()->find($bus_arr['bus_type_id'])->getData();
			if($bus_arr['set_seats_count'] == 'F')
			{
				$seats_available = $bus_type_arr['seats_count'];
				$cnt_arr = $pjBookingSeatModel
						->reset()
						->select("COUNT(DISTINCT t1.seat_id) as cnt_booked")
						->where("t1.start_location_id IN(".join(",", $location_id_arr).") 
						         AND t1.booking_id <> ".$arr['id']." 
						         AND t1.booking_id IN(SELECT TB.id 
						                              FROM `".pjBookingModel::factory()->getTable()."` AS TB 
						                              WHERE (TB.status='confirmed' OR (TB.status='pending' AND UNIX_TIMESTAMP(TB.created) >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL ".$this->option_arr['o_min_hour']." MINUTE)))) 
						                                    AND TB.bus_id = $bus_id 
						                                    AND ( ('".$arr['booking_datetime']."' BETWEEN TB.booking_datetime AND TB.stop_datetime) OR ('".$arr['stop_datetime']."' BETWEEN TB.booking_datetime AND TB.stop_datetime) OR (TB.booking_datetime BETWEEN '".$arr['booking_datetime']."' AND '".$arr['stop_datetime']."')OR (TB.stop_datetime BETWEEN '".$arr['booking_datetime']."' AND '".$arr['stop_datetime']."') ) )")
						->findAll()
						->getData();
						
				$cnt_booked = 0;
				if(count($cnt_arr) > 0)
				{
					$cnt_booked = $cnt_arr[0]['cnt_booked'];
				}
				$seats_available -= $cnt_booked;
				$this->set('seats_available', $seats_available);
			}
			
			$this->set('ticket_arr', $ticket_arr);
			$this->set('bus_type_arr', $bus_type_arr);

			$this->set('seat_arr', pjSeatModel::factory()->where('bus_type_id', $bus_arr['bus_type_id'])->findAll()->getData());

			$ticket_pair_arr = $pjBookingSeatModel->reset()->select("t1.ticket_id, COUNT(DISTINCT seat_id) as qty")->where('booking_id', $arr['id'])->groupBy("t1.ticket_id")->findAll()->getDataPair("ticket_id", 'qty');
			$this->set('ticket_pair_arr', $ticket_pair_arr);
			
			$seat_pair_arr = $pjBookingSeatModel->reset()->where('booking_id', $arr['id'])->findAll()->getDataPair("seat_id", 'seat_id');
			$this->set('seat_pair_arr', $seat_pair_arr);
			
			$selected_seats = array();
			if(!empty($seat_pair_arr))
			{
				$selected_seats = pjSeatModel::factory()->whereIn('id', $seat_pair_arr)->findAll()->getDataPair("id", 'name');
			}
			$booked_seat_arr = $pjBookingSeatModel
				->reset()
				->select("DISTINCT seat_id")
				->where("t1.booking_id IN(SELECT TB.id 
				                          FROM `".pjBookingModel::factory()->getTable()."` AS TB 
				                          WHERE (TB.status='confirmed' OR (TB.status='pending' AND UNIX_TIMESTAMP(TB.created) >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL ".$this->option_arr['o_min_hour']." MINUTE)))) 
				                                 AND TB.id <> '".$arr['id']."' 
				                                 AND TB.bus_id = $bus_id 
				                                 AND TB.booking_date = '$booking_date') 
				                                 AND start_location_id IN(".join(",", $location_id_arr).")")
				->findAll()
				->getDataPair("seat_id", "seat_id");
			
			$this->set('selected_seats', $selected_seats);
			$this->set('arr', $arr);
			$this->set('booked_seat_arr', $booked_seat_arr);
				
			$this->set('country_arr', pjBaseCountryModel::factory()
				->select('t1.*, t2.content AS name')
				->join('pjMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('t1.status', 'T')
				->orderBy('`name` ASC')->findAll()->getData()
			);
			
			$this->appendJs('tinymce.min.js', PJ_THIRD_PARTY_PATH . 'tinymce/');
			$this->appendCss('css/select2.min.css', PJ_THIRD_PARTY_PATH . 'select2/');
			$this->appendJs('js/select2.full.min.js', PJ_THIRD_PARTY_PATH . 'select2/');
			$this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
			$this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
			$this->appendJs('pjAdminBookings.js');	
		}
	}
	
	public function pjActionChangeDate()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
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

			$this->set('from_location_arr', $from_location_arr);
			$this->set('to_location_arr', $to_location_arr);
	    }
	}
	
	public function pjActionGetReturnBuses()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$bus_arr = array();
				
			if($this->_post->toInt('pickup_id') != $this->_post->toInt('return_id'))
			{
				$pickup_id = $this->_post->toInt('return_id');
				$return_id = $this->_post->toInt('pickup_id');
				$date = pjDateTime::formatDate($this->_post->toString('return_date'), $this->option_arr['o_date_format']);
				$bus_id_arr = pjBusModel::factory()->getBusIds($date, $pickup_id, $return_id);
				if(!empty($bus_id_arr))
				{
				    $bus_arr = pjBusModel::factory()
				    ->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.route_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				    ->join('pjBusType', "t3.id=t1.bus_type_id", 'left outer')
				    ->select(" t1.*, t2.content AS route, t3.seats_map")
				    ->whereIn('t1.id', $bus_id_arr)
				    ->orderBy("route asc")
				    ->findAll()
				    ->getData();
				    foreach($bus_arr as $k => $v)
				    {
				        if(!empty($v['departure_time']) && !empty($v['arrival_time']))
				        {
				            $v['depart_arrive'] = pjDateTime::formatTime(date('H:i:s', strtotime($v['departure_time'])), "H:i:s", $this->option_arr['o_time_format']) . ' - ' . pjDateTime::formatTime(date('H:i:s', strtotime($v['arrival_time'])), "H:i:s", $this->option_arr['o_time_format']);
				        }else{
				            $v['depart_arrive'] = '';
				        }
				        $bus_arr[$k] = $v;
				    }
				}
			}
			$this->set('bus_arr', $bus_arr);
		}
	}
	
	public function pjActionGetBuses()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$bus_arr = array();
			
			if($this->_post->toInt('pickup_id') != $this->_post->toInt('return_id'))
			{
				$pickup_id = $this->_post->toInt('pickup_id');
				$return_id = $this->_post->toInt('return_id');
				$date = pjDateTime::formatDate($this->_post->toString('booking_date'), $this->option_arr['o_date_format']);
				$bus_id_arr = pjBusModel::factory()->getBusIds($date, $pickup_id, $return_id);
				if(!empty($bus_id_arr))
				{
				    $bus_arr = pjBusModel::factory()
				    ->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.route_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				    ->join('pjBusType', "t3.id=t1.bus_type_id", 'left outer')
				    ->select(" t1.*, t2.content AS route, t3.seats_map")
				    ->whereIn('t1.id', $bus_id_arr)
				    ->orderBy("route asc")
				    ->findAll()
				    ->getData();
				    foreach($bus_arr as $k => $v)
				    {
				        if(!empty($v['departure_time']) && !empty($v['arrival_time']))
				        {
				            $v['depart_arrive'] = pjDateTime::formatTime(date('H:i:s', strtotime($v['departure_time'])), "H:i:s", $this->option_arr['o_time_format']) . ' - ' . pjDateTime::formatTime(date('H:i:s', strtotime($v['arrival_time'])), "H:i:s", $this->option_arr['o_time_format']);
				        }else{
				            $v['depart_arrive'] = '';
				        }
				        $bus_arr[$k] = $v;
				    }
				}
			}
			$this->set('bus_arr', $bus_arr);
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
			$this->set('location_arr', $location_arr);
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
			$this->set('location_arr', $location_arr);
		}
	}
	
	public function pjActionGetTickets()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$booking_date = pjDateTime::formatDate($this->_post->toString('booking_date'), $this->option_arr['o_date_format']);
			$bus_id = $this->_post->toInt('bus_id');
			$pickup_id = $this->_post->toInt('pickup_id');
			$return_id = $this->_post->toInt('return_id');
			$departure_time = '';
			$arrival_time = '';
			$departure_dt = '';
			$arrival_dt = '';
			$pjBusLocationModel = pjBusLocationModel::factory();
			$pickup_arr = $pjBusLocationModel->where('bus_id', $bus_id)->where("location_id", $pickup_id)->limit(1)->findAll()->getData();
			$return_arr = $pjBusLocationModel->reset()->where('bus_id', $bus_id)->where("location_id", $return_id)->limit(1)->findAll()->getData();

			if(!empty($pickup_arr))
			{
				$departure_time = __('lblDepartureTime', true, false) . ': ' . pjDateTime::formatTime(date('H:i:s', strtotime($pickup_arr[0]['departure_time'])), 'H:i:s', $this->option_arr['o_time_format']);
				$departure_dt = $booking_date . ' ' . $pickup_arr[0]['departure_time'];
			}
			if(!empty($return_arr))
			{
				$arrival_time = __('lblArrivalTime', true, false) . ': ' . pjDateTime::formatTime(date('H:i:s', strtotime($return_arr[0]['arrival_time'])), 'H:i:s', $this->option_arr['o_time_format']);
				$arrival_dt = $booking_date . ' ' . $return_arr[0]['arrival_time'];
			}

			$arr = pjBusModel::factory()->find($bus_id)->getData();
			$locations = pjRouteCityModel::factory()
				->join('pjBusLocation', "(t2.bus_id='" . $bus_id . "' AND t2.location_id=t1.city_id", 'inner' )
				->select("t1.*, t2.departure_time, t2.arrival_time" )
				->where('t1.route_id', $arr['route_id'] )
				->orderBy("`order` ASC" )->findAll ()->getData ();
			
			if (! empty ( $pickup_arr ) && ! empty ( $return_arr )) {
				$seconds = 0;
				$start_count = false;
				foreach ( $locations as $key => $lo ) {
					$next_location = $locations [$key + 1];					
					if ($lo ['city_id'] == $pickup_id) {
						$start_count = true;
					}
					if (isset ( $next_location ) && $start_count == true) {
						$seconds += pjUtil::calSeconds ( $lo ['departure_time'], $next_location ['arrival_time'] );
						if ($key + 1 < count ( $locations ) && $key > 0 && $lo ['city_id'] != $pickup_id) {
							$seconds += pjUtil::calSeconds ( $lo ['arrival_time'], $lo ['departure_time'] );
						}
					}
					if ($next_location ['city_id'] == $return_id) {
						break;
					}
				}				
				$arrival_dt = date('Y-m-d H:i:s', strtotime ($departure_dt) + $seconds );
			}
				
			$location_id_arr = pjRouteCityModel::factory()->getLocationIdPair($arr['route_id'], $pickup_id, $return_id);
			if(!empty($location_id_arr))
			{
				$ticket_arr = pjPriceModel::factory()
					->reset()
					->join('pjTicket', 't1.ticket_id = t2.id', 'left')
					->join('pjMultiLang', "t3.model='pjTicket' AND t3.foreign_id=t1.ticket_id AND t3.field='title' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
					->join('pjBus', 't1.bus_id = t4.id', 'left')
					->select("t1.*, t2.seats_count, t3.content as ticket, t4.discount")
					->where('t1.bus_id', $bus_id)
					->where('t1.from_location_id', $pickup_id)
					->where('t1.to_location_id', $return_id)
					->where('is_return = "F"')
					->index("FORCE KEY (`ticket_id`)")
					->orderBy("ticket ASC")
					->findAll()
					->getData();
				
				if($arr['set_seats_count'] == 'F')
				{
					$and_where = '';
					if ($this->_post->check('id') && $this->_post->toInt('id') > 0) {
						$and_where .= " AND TB.id <> ".$this->_post->toInt('id');
					}
					$bus_type_arr = pjBusTypeModel::factory()->find($arr['bus_type_id'])->getData();
					$seats_available = $bus_type_arr['seats_count'];
					$cnt_arr = pjBookingSeatModel::factory()
						->select("COUNT(DISTINCT t1.seat_id) as cnt_booked")
						->where("t1.start_location_id IN(".join(",", $location_id_arr).") AND t1.booking_id IN(SELECT TB.id FROM `".pjBookingModel::factory()->getTable()."` AS TB WHERE (TB.status='confirmed' OR (TB.status='pending' AND UNIX_TIMESTAMP(TB.created) >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL ".$this->option_arr['o_min_hour']." MINUTE)))) AND TB.bus_id = $bus_id $and_where AND TB.booking_date='$booking_date')")
						->findAll()
						->getData();
					
					$cnt_booked = 0;
					if(count($cnt_arr) > 0)
					{
						$cnt_booked = $cnt_arr[0]['cnt_booked'];
					}
					$seats_available -= $cnt_booked;
					$this->set('seats_available', $seats_available);
				}
							 
				$this->set('ticket_arr', $ticket_arr);
				$this->set('arr', $arr);
				$this->set('departure_time', $departure_time);
				$this->set('arrival_time', $arrival_time);
				$booking_arr = array();
				if ($this->_post->check('id') && $this->_post->toInt('id') > 0) {
					$booking_arr = pjBookingModel::factory()->find($this->_post->toInt('id'))->getData();
				}
				$this->set('booking_arr', $booking_arr);
			}
		}
	}
	
	public function pjActionGetReturnTickets()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if($this->_post->check('return_bus_id') && $this->_post->toInt('return_bus_id') > 0)
			{
				$booking_date = pjDateTime::formatDate($this->_post->toString('return_date'), $this->option_arr['o_date_format']);
				$bus_id = $this->_post->toInt('return_bus_id');
				$pickup_id = $this->_post->toInt('return_id');
				$return_id = $this->_post->toInt('pickup_id');
				$departure_time = '';
				$arrival_time = '';
				$departure_dt = '';
				$arrival_dt = '';
				$pjBusLocationModel = pjBusLocationModel::factory();
				$pickup_arr = $pjBusLocationModel->where('bus_id', $bus_id)->where("location_id", $pickup_id)->limit(1)->findAll()->getData();
				$return_arr = $pjBusLocationModel->reset()->where('bus_id', $bus_id)->where("location_id", $return_id)->limit(1)->findAll()->getData();
		
				if(!empty($pickup_arr))
				{
					$departure_time = __('lblDepartureTime', true, false) . ': ' . pjDateTime::formatTime(date('H:i:s', strtotime($pickup_arr[0]['departure_time'])), 'H:i:s', $this->option_arr['o_time_format']);
					$departure_dt = $booking_date . ' ' . $pickup_arr[0]['departure_time'];
				}
				if(!empty($return_arr))
				{
					$arrival_time = __('lblArrivalTime', true, false) . ': ' . pjDateTime::formatTime(date('H:i:s', strtotime($return_arr[0]['arrival_time'])), 'H:i:s', $this->option_arr['o_time_format']);
					$arrival_dt = $booking_date . ' ' . $return_arr[0]['arrival_time'];
				}
				
				$arr = pjBusModel::factory()->find($bus_id)->getData();
				$locations = pjRouteCityModel::factory()
					->join('pjBusLocation', "(t2.bus_id='" . $bus_id . "' AND t2.location_id=t1.city_id", 'inner' )
					->select("t1.*, t2.departure_time, t2.arrival_time" )
					->where('t1.route_id', $arr['route_id'] )
					->orderBy("`order` ASC" )->findAll ()->getData ();
				if (! empty ( $pickup_arr ) && ! empty ( $return_arr )) {
					$seconds = 0;
					$start_count = false;
					foreach ( $locations as $key => $lo ) {
						$next_location = $locations [$key + 1];					
						if ($lo ['city_id'] == $pickup_id) {
							$start_count = true;
						}
						if (isset ( $next_location ) && $start_count == true) {
							$seconds += pjUtil::calSeconds ( $lo ['departure_time'], $next_location ['arrival_time'] );
							if ($key + 1 < count ( $locations ) && $key > 0 && $lo ['city_id'] != $pickup_id) {
								$seconds += pjUtil::calSeconds ( $lo ['arrival_time'], $lo ['departure_time'] );
							}
						}
						if ($next_location ['city_id'] == $return_id) {
							break;
						}
					}				
					$arrival_dt = date('Y-m-d H:i:s', strtotime ($departure_dt) + $seconds );
				}
				
				$location_id_arr = pjRouteCityModel::factory()->getLocationIdPair($arr['route_id'], $pickup_id, $return_id);
					
				if(!empty($location_id_arr))
				{
					$pjPriceModel = pjPriceModel::factory();
		
					$ticket_arr = $pjPriceModel
						->reset()
						->join('pjTicket', 't1.ticket_id = t2.id', 'left')
						->join('pjMultiLang', "t3.model='pjTicket' AND t3.foreign_id=t1.ticket_id AND t3.field='title' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
						->join('pjBus', 't1.bus_id = t4.id', 'left')
						->select("t1.*, t2.seats_count, t3.content as ticket, t4.discount")
						->where('t1.bus_id', $bus_id)
						->where('t1.from_location_id', $pickup_id)
						->where('t1.to_location_id', $return_id)
						->where('is_return = "F"')
						->index("FORCE KEY (`ticket_id`)")
						->orderBy("ticket ASC")
						->findAll()
						->getData();
					
					if($arr['set_seats_count'] == 'F')
					{
						$and_where = '';
						if ($this->_post->check('id') && $this->_post->toInt('id') > 0) {
							$and_where .= " AND TB.id <> ".$this->_post->toInt('id');
						}
						$bus_type_arr = pjBusTypeModel::factory()->find($arr['bus_type_id'])->getData();
						$seats_available = $bus_type_arr['seats_count'];
						$cnt_arr = pjBookingSeatModel::factory()
							->select("COUNT(DISTINCT t1.seat_id) as cnt_booked")
							->where("t1.start_location_id IN(".join(",", $location_id_arr).") AND t1.booking_id IN(SELECT TB.id FROM `".pjBookingModel::factory()->getTable()."` AS TB WHERE (TB.status='confirmed' OR (TB.status='pending' AND UNIX_TIMESTAMP(TB.created) >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL ".$this->option_arr['o_min_hour']." MINUTE)))) AND TB.bus_id = $bus_id $and_where AND TB.booking_date='$booking_date')")
							->findAll()
							->getData();
							
						$cnt_booked = 0;
						if(count($cnt_arr) > 0)
						{
							$cnt_booked = $cnt_arr[0]['cnt_booked'];
						}
						$seats_available -= $cnt_booked;
						$this->set('seats_available', $seats_available);
					}
					
					$this->set('ticket_arr', $ticket_arr);
					$this->set('arr', $arr);
					$this->set('departure_time', $departure_time);
					$this->set('arrival_time', $arrival_time);
				}
			}
		}
	}
	
	public function pjActionGetSeats()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$bus_id = $this->_post->toInt('bus_id');
			$pickup_id = $this->_post->toInt('pickup_id');
			$return_id = $this->_post->toInt('return_id');

			$booking_date = pjDateTime::formatDate($this->_post->toString('booking_date'), $this->option_arr['o_date_format']);
			
			$arr = pjBusModel::factory()->find($bus_id)->getData();			
			
			$pjRouteCityModel = pjRouteCityModel::factory();
			$location_id_arr = $pjRouteCityModel->getLocationIdPair($arr['route_id'], $pickup_id, $return_id);
			
			$bus_type_arr = pjBusTypeModel::factory()->find($arr['bus_type_id'])->getData();
			
			$pickup_arr = pjBusLocationModel::factory()->where('bus_id', $bus_id)->where("location_id", $pickup_id)->limit(1)->findAll()->getData();
			$locations = $pjRouteCityModel
				->reset()
				->join('pjBusLocation', "(t2.bus_id='".$bus_id."' AND t2.location_id=t1.city_id", 'inner')
				->select("t1.*, t2.departure_time, t2.arrival_time")
				->where('t1.route_id', $arr['route_id'])
				->orderBy("`order` ASC")
				->findAll()
				->getData();
			
			$seconds = 0;
			$start_count = false;
			foreach($locations as $key => $lo)
			{
				$next_location = $locations[$key + 1];
			
				if($lo['city_id'] == $pickup_id)
				{
					$start_count = true;
				}
				if(isset($next_location) && $start_count == true)
				{
					$seconds += pjUtil::calSeconds($lo['departure_time'], $next_location['arrival_time']);
					if($key + 1 < count($locations) && $key > 0 && $lo['city_id'] != $pickup_id)
					{
						$seconds += pjUtil::calSeconds($lo['arrival_time'], $lo['departure_time']);
					}
				}
				if($next_location['city_id'] == $return_id)
				{
					break;
				}
			}
			$departure_time = null;
			$arrival_time = null;
			if(!empty($pickup_arr))
			{
				$departure_time = $booking_date . ' ' . $pickup_arr[0]['departure_time'];
				$arrival_time = date('Y-m-d H:i:s', strtotime($departure_time) + $seconds);
				$_SESSION[$this->defaultPeriod]['departure_time'] = $departure_time;
				$_SESSION[$this->defaultPeriod]['arrival_time'] = $arrival_time;
			}
			if($this->_post->check('booking_create'))
			{
				$booked_seat_arr = pjBookingSeatModel::factory()->select ( "DISTINCT seat_id" )
					->where ( "t1.booking_id IN(SELECT TB.id
										FROM `" . pjBookingModel::factory ()->getTable () . "` AS TB
										WHERE (TB.status='confirmed' OR (TB.status='pending' AND UNIX_TIMESTAMP(TB.created) >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL " . $this->option_arr ['o_min_hour'] . " MINUTE))))
						AND TB.bus_id = $bus_id
						AND TB.booking_date = '$booking_date')
						AND start_location_id IN(" . join ( ",", $location_id_arr ) . ")" )->index ( "FORCE KEY (`booking_id`)" )->findAll ()->getDataPair ( "seat_id", "seat_id" );
			}else{
				$booking_id = $this->_post->toInt('id');
				$seat_pair_arr = array();
				$booking_arr = pjBookingModel::factory()->find($booking_id)->getData();
				$pjBookingSeatModel = pjBookingSeatModel::factory();
				$booked_seat_arr = pjBookingSeatModel::factory()
					->select("DISTINCT seat_id")
					->where("t1.booking_id IN(SELECT TB.id 
					                          FROM `".pjBookingModel::factory()->getTable()."` AS TB 
					                          WHERE (TB.status='confirmed' OR (TB.status='pending' AND UNIX_TIMESTAMP(TB.created) >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL ".$this->option_arr['o_min_hour']." MINUTE)))) 
					                                AND TB.id <> $booking_id
					                                AND TB.booking_date = '$booking_date' 
					                                AND TB.bus_id = $bus_id) 
					                                AND start_location_id IN(".join(",", $location_id_arr).")")
					->findAll()
					->getDataPair("seat_id", "seat_id");
				if($booking_arr['pickup_id'] == $pickup_id && $booking_arr['return_id'] == $return_id)
				{
					$seat_pair_arr = $pjBookingSeatModel->reset()->where('booking_id', $booking_id)->findAll()->getDataPair("seat_id", 'seat_id');
				}
				
				$this->set('seat_pair_arr', $seat_pair_arr);
			}
			
			$this->set('bus_type_arr', $bus_type_arr);
			$this->set('booked_seat_arr', $booked_seat_arr);
			$this->set('seat_arr', pjSeatModel::factory()->where('bus_type_id', $arr['bus_type_id'])->findAll()->getData());
		}
	}

	public function pjActionGetReturnSeats()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if ($this->_post->check('return_bus_id') && $this->_post->toInt('return_bus_id') > 0) 
			{
				$bus_id = $this->_post->toInt('return_bus_id');
				$pickup_id = $this->_post->toInt('return_id');
				$return_id = $this->_post->toInt('pickup_id');

				$booking_date = pjDateTime::formatDate($this->_post->toString('return_date'), $this->option_arr['o_date_format']);
				
				$arr = pjBusModel::factory()->find($bus_id)->getData();			
				
				$pjRouteCityModel = pjRouteCityModel::factory();
				$location_id_arr = $pjRouteCityModel->getLocationIdPair($arr['route_id'], $pickup_id, $return_id);
								
				$bus_type_arr = pjBusTypeModel::factory()->find($arr['bus_type_id'])->getData();
				
				$pickup_arr = pjBusLocationModel::factory()->where('bus_id', $bus_id)->where("location_id", $pickup_id)->limit(1)->findAll()->getData();
				$locations = $pjRouteCityModel
					->reset()
					->join('pjBusLocation', "(t2.bus_id='".$bus_id."' AND t2.location_id=t1.city_id", 'inner')
					->select("t1.*, t2.departure_time, t2.arrival_time")
					->where('t1.route_id', $arr['route_id'])
					->orderBy("`order` ASC")
					->findAll()
					->getData();
				
				$seconds = 0;
				$start_count = false;
				foreach($locations as $key => $lo)
				{
					$next_location = $locations[$key + 1];
						
					if($lo['city_id'] == $pickup_id)
					{
						$start_count = true;
					}
					if(isset($next_location) && $start_count == true)
					{
						$seconds += pjUtil::calSeconds($lo['departure_time'], $next_location['arrival_time']);
						if($key + 1 < count($locations) && $key > 0 && $lo['city_id'] != $pickup_id)
						{
							$seconds += pjUtil::calSeconds($lo['arrival_time'], $lo['departure_time']);
						}
					}
					if($next_location['city_id'] == $return_id)
					{
						break;
					}
				}
				$departure_time = null;
				$arrival_time = null;
				if(!empty($pickup_arr))
				{
					$departure_time = $booking_date . ' ' . $pickup_arr[0]['departure_time'];
					$arrival_time = date('Y-m-d H:i:s', strtotime($departure_time) + $seconds);
					$_SESSION[$this->defaultReturnPeriod]['departure_time'] = $departure_time;
					$_SESSION[$this->defaultReturnPeriod]['arrival_time'] = $arrival_time;
				}
				if($this->_post->check('booking_create'))
				{
					$booked_seat_arr = pjBookingSeatModel::factory()->select ( "DISTINCT seat_id" )
						->where ( "t1.booking_id IN(SELECT TB.id
										FROM `" . pjBookingModel::factory ()->getTable () . "` AS TB
										WHERE (TB.status='confirmed' OR (TB.status='pending' AND UNIX_TIMESTAMP(TB.created) >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL " . $this->option_arr ['o_min_hour'] . " MINUTE))))
							AND TB.bus_id = $bus_id
							AND TB.booking_date = '$booking_date')
							AND start_location_id IN(" . join ( ",", $location_id_arr ) . ")" )->index ( "FORCE KEY (`booking_id`)" )->findAll ()->getDataPair ( "seat_id", "seat_id" );
				}else{
					$booking_id = $this->_post->toInt('id');
					$seat_pair_arr = array();
					$booking_arr = pjBookingModel::factory()->find($booking_id)->getData();
					$pjBookingSeatModel = pjBookingSeatModel::factory();
					$booked_seat_arr = pjBookingSeatModel::factory()
						->select("DISTINCT seat_id")
						->where("t1.booking_id IN(SELECT TB.id 
						                          FROM `".pjBookingModel::factory()->getTable()."` AS TB 
						                          WHERE (TB.status='confirmed' OR (TB.status='pending' AND UNIX_TIMESTAMP(TB.created) >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL ".$this->option_arr['o_min_hour']." MINUTE)))) 
						                                AND TB.id <> $booking_id 
						                                AND TB.booking_date = '$booking_date'
						                                AND TB.bus_id = $bus_id) 
						                                AND start_location_id IN(".join(",", $location_id_arr).")")
						->findAll()
						->getDataPair("seat_id", "seat_id");
					if($booking_arr['pickup_id'] == $pickup_id && $booking_arr['return_id'] == $return_id)
					{
						$seat_pair_arr = $pjBookingSeatModel->reset()->where('booking_id', $booking_id)->findAll()->getDataPair("seat_id", 'seat_id');
					}
					
					$this->set('seat_pair_arr', $seat_pair_arr);
				}
				
				$this->set('bus_type_arr', $bus_type_arr);
				$this->set('booked_seat_arr', $booked_seat_arr);
				$this->set('seat_arr', pjSeatModel::factory()->where('bus_type_id', $arr['bus_type_id'])->findAll()->getData());
			}
		}
	}
	
	public function pjActionPrintTickets()
	{
		$this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
		
		$this->setLayout('pjActionPrint');
			
		$pjBookingModel = pjBookingModel::factory();
						
		$arr = $pjBookingModel->find($this->_get->toInt('id'))->getData();
		if (empty($arr))
		{
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionIndex&err=ABB08");
		}
		$hash = sha1($arr['id'].$arr['created'].PJ_SALT);
		if($hash != $this->_get->toString('hash'))
		{
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionIndex&err=ABB08");
		}
		if($arr['status'] == 'confirmed')
		{
			$price_tbl = pjPriceModel::factory()->getTable();
			
			$pjBookingTicketModel = pjBookingTicketModel::factory();
			$tickets = $pjBookingTicketModel
				->join('pjMultiLang', "t2.model='pjTicket' AND t2.foreign_id=t1.ticket_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->join('pjTicket', "t3.id=t1.ticket_id", 'left')
				->select('t1.*, t2.content as title, (SELECT TP.price FROM `'.$price_tbl.'` AS TP WHERE TP.ticket_id = t1.ticket_id AND TP.bus_id = '.$arr['bus_id'].' AND TP.from_location_id = '.$arr['pickup_id'].' AND TP.to_location_id= '.$arr['return_id']. ' AND is_return = "F" LIMIT 1) as price')
				->where('booking_id', $arr['id'])
				->findAll()->getData();

			$arr['tickets'] = $tickets;
			
			$pjCityModel = pjCityModel::factory();
			$pickup_location = $pjCityModel->reset()->select('t1.*, t2.content as name')->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')->find($arr['pickup_id'])->getData();
			$to_location = $pjCityModel->reset()->select('t1.*, t2.content as name')->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')->find($arr['return_id'])->getData();
			$arr['from_location'] = $pickup_location['name'];
			$arr['to_location'] = $to_location['name'];	
				
			$pjMultiLangModel = pjMultiLangModel::factory();
			$lang_template = $pjMultiLangModel
				->reset()->select('t1.*')
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
		}elseif ($arr['status'] == 'pending'){
			$this->set('pending_booking', true);
		}
	}
	
	public function pjActionEmailConfirmation()
	{
	    $this->checkLogin();
	    
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        if (self::isPost())
	        {
	            if($this->_post->toInt('send_email') && $this->_post->toString('to') && $this->_post->toString('subject') && $this->_post->toString('message') && $this->_post->toInt('id'))
	            {
	                $Email = self::getMailer($this->option_arr);
	                $message = pjUtil::textToHtml($this->_post->toString('message'));
	                $r = $Email
	                ->setTo($this->_post->toString('to'))
	                ->setSubject($this->_post->toString('subject'))
	                ->send($message);
	                if (isset($r) && $r)
	                {
	                    pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
	                }
	                pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
	            }
	        }
	        if (self::isGet())
	        {
	            if($booking_id = $this->_get->toInt('booking_id'))
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
						->find($booking_id)
						->getData();
											
					$booking_arr['tickets'] = pjBookingTicketModel::factory()
						->join('pjMultiLang', "t2.model='pjTicket' AND t2.foreign_id=t1.ticket_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->join('pjTicket', "t3.id=t1.ticket_id", 'left')
						->select('t1.*, t2.content as title')
						->where('booking_id', $booking_id)
						->findAll()
						->getData();
	                	                
	                $notification = pjNotificationModel::factory()->where('recipient', 'client')->where('transport', 'email')->where('variant', 'confirmation')->findAll()->getDataIndex(0);
	                if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
	                {
	                	$tokens = pjAppController::getData($this->option_arr, $booking_arr, PJ_SALT, $this->getLocaleId());
			
						$resp = pjAppController::getSubjectMessage($notification, $this->getLocaleId());	                    
	                    $lang_message = $resp['lang_message'];
	                    $lang_subject = $resp['lang_subject'];
	                    
	                    $subject_client = str_replace($tokens['search'], $tokens['replace'], @$lang_subject[0]['content']);	                    
	                    $message_client = str_replace($tokens['search'], $tokens['replace'], @$lang_message[0]['content']);
	                    
	                    $this->set('arr', array(
	                        'id' => $booking_id,
	                        'to' => $booking_arr['c_email'],
	                        'message' => $message_client,
	                        'subject' => $subject_client
	                    ));
	                }
	            }
	        }
	    }
	}
	
	public function pjActionEmailPayment()
	{
	    $this->checkLogin();
	    
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        if (self::isPost())
	        {
	            if($this->_post->toInt('send_email') && $this->_post->toString('to') && $this->_post->toString('subject') && $this->_post->toString('message') && $this->_post->toInt('id'))
	            {
	                $Email = self::getMailer($this->option_arr);
	                $message = pjUtil::textToHtml($this->_post->toString('message'));
	                $r = $Email
	                ->setTo($this->_post->toString('to'))
	                ->setSubject($this->_post->toString('subject'))
	                ->send($message);
	                if (isset($r) && $r)
	                {
	                    pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
	                }
	                pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
	            }
	        }
	        if (self::isGet())
	        {
	            if($booking_id = $this->_get->toInt('booking_id'))
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
						->find($booking_id)
						->getData();
											
					$booking_arr['tickets'] = pjBookingTicketModel::factory()
						->join('pjMultiLang', "t2.model='pjTicket' AND t2.foreign_id=t1.ticket_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->join('pjTicket', "t3.id=t1.ticket_id", 'left')
						->select('t1.*, t2.content as title')
						->where('booking_id', $booking_id)
						->findAll()
						->getData();
	                
	                $notification = pjNotificationModel::factory()->where('recipient', 'client')->where('transport', 'email')->where('variant', 'payment')->findAll()->getDataIndex(0);
	                if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
	                {
	                	$tokens = pjAppController::getData($this->option_arr, $booking_arr, PJ_SALT, $this->getLocaleId());
			
						$resp = pjAppController::getSubjectMessage($notification, $this->getLocaleId());	                    
	                    $lang_message = $resp['lang_message'];
	                    $lang_subject = $resp['lang_subject'];
	                    
	                    $subject_client = str_replace($tokens['search'], $tokens['replace'], @$lang_subject[0]['content']);	                    
	                    $message_client = str_replace($tokens['search'], $tokens['replace'], @$lang_message[0]['content']);
	                    
	                    $this->set('arr', array(
	                        'id' => $booking_id,
	                        'to' => $booking_arr['c_email'],
	                        'message' => $message_client,
	                        'subject' => $subject_client
	                    ));
	                }
	            }
	        }
	    }
	}
	
	public function pjActionEmailCancellation()
	{
	$this->checkLogin();
	    
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        if (self::isPost())
	        {
	            if($this->_post->toInt('send_email') && $this->_post->toString('to') && $this->_post->toString('subject') && $this->_post->toString('message') && $this->_post->toInt('id'))
	            {
	                $Email = self::getMailer($this->option_arr);
	                $message = pjUtil::textToHtml($this->_post->toString('message'));
	                $r = $Email
	                ->setTo($this->_post->toString('to'))
	                ->setSubject($this->_post->toString('subject'))
	                ->send($message);
	                if (isset($r) && $r)
	                {
	                    pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
	                }
	                pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
	            }
	        }
	        if (self::isGet())
	        {
	            if($booking_id = $this->_get->toInt('booking_id'))
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
						->find($booking_id)
						->getData();
											
					$booking_arr['tickets'] = pjBookingTicketModel::factory()
						->join('pjMultiLang', "t2.model='pjTicket' AND t2.foreign_id=t1.ticket_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->join('pjTicket', "t3.id=t1.ticket_id", 'left')
						->select('t1.*, t2.content as title')
						->where('booking_id', $booking_id)
						->findAll()
						->getData();
	                
	                $notification = pjNotificationModel::factory()->where('recipient', 'client')->where('transport', 'email')->where('variant', 'cancel')->findAll()->getDataIndex(0);
	                if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
	                {
	                	$tokens = pjAppController::getData($this->option_arr, $booking_arr, PJ_SALT, $this->getLocaleId());
			
						$resp = pjAppController::getSubjectMessage($notification, $this->getLocaleId());	                    
	                    $lang_message = $resp['lang_message'];
	                    $lang_subject = $resp['lang_subject'];
	                    
	                    $subject_client = str_replace($tokens['search'], $tokens['replace'], @$lang_subject[0]['content']);	                    
	                    $message_client = str_replace($tokens['search'], $tokens['replace'], @$lang_message[0]['content']);
	                    
	                    $this->set('arr', array(
	                        'id' => $booking_id,
	                        'to' => $booking_arr['c_email'],
	                        'message' => $message_client,
	                        'subject' => $subject_client
	                    ));
	                }
	            }
	        }
	    }
	}
}
?>