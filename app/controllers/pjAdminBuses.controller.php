<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminBuses extends pjAdmin
{
    public function pjActionCreate()
	{
		$this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
		
		$post_max_size = pjUtil::getPostMaxSize();
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['CONTENT_LENGTH']) && (int) $_SERVER['CONTENT_LENGTH'] > $post_max_size)
		{
			pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminBuses&action=pjActionIndex&err=ABS05");
		}
		if (self::isPost() && $this->_post->check('bus_create'))
		{
			$data = array();
			if(!$this->_post->isEmpty('start_date'))
			{
				$data['start_date'] = pjDateTime::formatDate($this->_post->toString('start_date'), $this->option_arr['o_date_format']);
			}
			if(!$this->_post->isEmpty('end_date'))
			{
				$data['end_date'] = pjDateTime::formatDate($this->_post->toString('end_date'), $this->option_arr['o_date_format']);
			}	
			$recurring = $this->_post->toArray('recurring');
			$data['recurring'] = $recurring ? join("|", $recurring) : ':NULL';
			
			$pjBusModel = pjBusModel::factory();
			$id = $pjBusModel->setAttributes(array_merge($this->_post->raw(), $data))->insert()->getInsertId();
			if ($id !== false && (int) $id > 0)
			{
				$location_arr = pjRouteCityModel::factory()
					->where('route_id', $this->_post->toInt('route_id'))
					->orderBy("t1.order ASC")
					->findAll()
					->getData();
								
				$pjBusLocationModel = pjBusLocationModel::factory();
				$number_of_locations = count($location_arr);
				$b_data = array();
				$today = date('Y-m-d');
				foreach($location_arr as $k => $v)
				{
					$data = array();
					$data['bus_id'] = $id;
					$data['location_id'] = $v['city_id'];
					if($k == 0)
					{
						$data['arrival_time'] = ":NULL";
						$b_data['departure_time'] = date('H:i:s', strtotime($today . ' ' . $this->_post->toString('departure_time_' . $v['city_id'])));
					}else{
						$data['arrival_time'] = date('H:i:s', strtotime($today . ' ' . $this->_post->toString('arrival_time_' . $v['city_id'])));
					}
					if($k == ($number_of_locations - 1))
					{
						$data['departure_time'] = ":NULL";
						$b_data['arrival_time'] = date('H:i:s', strtotime($today . ' ' . $this->_post->toString('arrival_time_' . $v['city_id'])));
					}else{
						$data['departure_time'] = date('H:i:s', strtotime($today . ' ' . $this->_post->toString('departure_time_' . $v['city_id'])));
					}
					$pjBusLocationModel->reset()->setAttributes($data)->insert();
				}
				$pjBusModel->reset()->where('id', $id)->limit(1)->modifyAll($b_data);
				
				$err = 'ABS03';
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBuses&action=pjActionTime&id=$id&err=$err");
			}else{
				$err = 'ABS04';
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBuses&action=pjActionIndex&err=$err");
			}
		} else {
			$route_arr = pjRouteModel::factory()
				->select(" t1.*, t2.content as title")
				->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('t1.status', 'T')
				->orderBy("t2.content ASC")
				->findAll()
				->getData();
			
			$this->set('route_arr', $route_arr);
			
			$bus_type_arr = pjBusTypeModel::factory()
						->select(" t1.*, t2.content as name")
						->join('pjMultiLang', "t2.model='pjBusType' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->where('t1.status', 'T')
						->orderBy("t2.content ASC")->findAll()->getData();
						
			$this->set('bus_type_arr', $bus_type_arr);
			$this->set('date_format', pjUtil::toBootstrapDate($this->option_arr['o_date_format']));	

			$this->appendCss('clockpicker.css', PJ_THIRD_PARTY_PATH . 'clockpicker/');
	        $this->appendJs('clockpicker.js', PJ_THIRD_PARTY_PATH . 'clockpicker/');
			$this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
			$this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
			$this->appendJs('pjAdminBuses.js');
		}
	}
	
	public function pjActionDeleteBus()
	{
		$this->setAjax(true);
	    
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    if (!pjAuth::factory()->hasAccess())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
	    }
	    if (!($this->_get->toInt('id')))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $id = $this->_get->toInt('id');
	    $pjBusModel = pjBusModel::factory();
	    $arr = $pjBusModel->find($id)->getData();
	    if (!$arr)
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Bus not found.'));
	    }
	    if ($pjBusModel->reset()->set('id', $id)->erase()->getAffectedRows() == 1)
	    {
	        $pjTicketModel = pjTicketModel::factory();
			$ticket_id_arr = $pjTicketModel->where('t1.bus_id', $id)->findAll()->getDataPair('id', 'id');
			if(!empty($ticket_id_arr))
			{
				pjMultiLangModel::factory()->where('model', 'pjTicket')->whereIn('foreign_id', $ticket_id_arr)->eraseAll();
			}
			$pjTicketModel->reset()->where('bus_id', $id)->eraseAll();
			pjBusLocationModel::factory()->where('bus_id', $id)->eraseAll();
			pjPriceModel::factory()->where('bus_id', $id)->eraseAll();
			pjBusDateModel::factory()->where('bus_id', $id)->eraseAll();
			pjBusModel::factory()->where('id', $id)->eraseAll();
			
			self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Bus has been deleted'));
	    }else{
	        self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => 'Bus has not been deleted.'));
	    }
		exit;
	}
	
	public function pjActionDeleteBusBulk()
	{
		$this->setAjax(true);
	
		if (!pjAuth::factory()->hasAccess())
		{
		    self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Access denied.'));
		}
		
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
			self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid data.'));
		}
		
		$pjTicketModel = pjTicketModel::factory();
				
		$ticket_id_arr = $pjTicketModel->whereIn('t1.bus_id', $record)->findAll()->getDataPair('id', 'id');
		if(!empty($ticket_id_arr))
		{
			pjMultiLangModel::factory()->where('model', 'pjTicket')->whereIn('foreign_id', $ticket_id_arr)->eraseAll();
		}
		$pjTicketModel->reset()->whereIn('bus_id', $record)->eraseAll();
		pjBusLocationModel::factory()->whereIn('bus_id', $record)->eraseAll();
		pjPriceModel::factory()->whereIn('bus_id', $record)->eraseAll();
		pjBusDateModel::factory()->whereIn('bus_id', $record)->eraseAll();
		pjBusModel::factory()->whereIn('id', $record)->eraseAll();
		
		self::jsonResponse(array('status' => 'OK'));
	}
	
	public function pjActionGetBus()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjBusModel = pjBusModel::factory()
				->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.route_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer');
			if ($q = $this->_get->toString('q'))
			{
			    $pjBusModel->where("(t2.content LIKE '%$q%')");
			}
			if ($this->_get->check('route_id') && $this->_get->toInt('route_id') > 0)
			{
				$pjBusModel->where("route_id", $this->_get->toInt('route_id'));
			}
			
			$column = 'route';
			$direction = 'ASC';
			if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
			{
			    $column = $this->_get->toString('column');
				if($column == 'from_to')
				{
					$column = 'start_date';
				}
				if($column == 'depart_arrive')
				{
					$column = 'departure_time';
				}
			    $direction = strtoupper($this->_get->toString('direction'));
			}

			$total = $pjBusModel->findCount()->getData();
			$rowCount = $this->_get->toInt('rowCount') ?: 10;
			$pages = ceil($total / $rowCount);
			$page = $this->_get->toInt('page') ?: 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjBusModel->select(" t1.*, t2.content AS route")
								 ->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
			foreach($data as $k => $v)
			{
				if(!empty($v['start_date']) && !empty($v['end_date']))
				{
					$v['from_to'] = date($this->option_arr['o_date_format'], strtotime($v['start_date'])) . ' - ' . date($this->option_arr['o_date_format'], strtotime($v['end_date']));
				}else{
					$v['from_to'] = '';
				}
				if(!empty($v['departure_time']) && !empty($v['arrival_time']))
				{
					$v['depart_arrive'] = date($this->option_arr['o_time_format'], strtotime($v['departure_time'])) . ' - ' . date($this->option_arr['o_time_format'], strtotime($v['arrival_time']));
				}else{
					$v['depart_arrive'] = '';
				}
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
	    
	    $route_arr = pjRouteModel::factory()
			->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
			->select(" t1.*, t2.content AS route")
			->orderBy("route ASC")
			->findAll()
			->getData();
		
		$this->set('route_arr', $route_arr);
	    
	    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
		$this->appendJs('pjAdminBuses.js');
	}
	
	public function pjActionSaveBus()
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
		
		if (!pjAuth::factory($this->_get->toString('controller'), 'pjActionUpdate')->hasAccess())
		{
		    self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
		}
		$pjBusModel = pjBusModel::factory();
		$arr = $pjBusModel->find($this->_get->toInt('id'))->getData();
		if (!$arr)
		{
		    self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Bus not found.'));
		}
		if (!in_array($this->_post->toString('column'), $pjBusModel->getI18n()))
		{
		    $pjBusModel->reset()->where('id', $this->_get->toInt('id'))->limit(1)->modifyAll(array($this->_post->toString('column') => $this->_post->toString('value')));
		} else {
		    pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($this->_post->toString('column') => $this->_post->toString('value'))), $this->_get->toInt('id'), 'pjBus', 'data');
		}
		self::jsonResponse(array('status' => 'OK', 'code' => 201, 'text' => 'Bus has been updated.'));
	}
	
	public function pjActionTime()
	{
		$this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
		
		$post_max_size = pjUtil::getPostMaxSize();
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['CONTENT_LENGTH']) && (int) $_SERVER['CONTENT_LENGTH'] > $post_max_size)
		{
			pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminBuses&action=pjActionIndex&err=ABS06");
		}	
		if (self::isPost() && $this->_post->check('bus_update'))
		{
			$pjBusModel = pjBusModel::factory();
				
			$arr = $pjBusModel->find($this->_post->toInt('id'))->getData();
			
			$b_data = array();
			if(!$this->_post->isEmpty('start_date'))
			{
				$b_data['start_date'] = pjDateTime::formatDate($this->_post->toString('start_date'), $this->option_arr['o_date_format']);
			}
			if(!$this->_post->isEmpty('end_date'))
			{
				$b_data['end_date'] = pjDateTime::formatDate($this->_post->toString('end_date'), $this->option_arr['o_date_format']);
			}	
			$recurring = $this->_post->toArray('recurring');
			$b_data['recurring'] = $recurring ? join("|", $recurring) : ':NULL';
			
			$location_arr = pjRouteCityModel::factory()->select('t1.*, t2.content as name')
							->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.city_id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
							->where('route_id', $arr['route_id'])->orderBy("t1.order ASC")->findAll()->getData();
							
			$pjBusLocationModel = pjBusLocationModel::factory();
			$number_of_locations = count($location_arr);
			
			$today = date('Y-m-d');
			foreach($location_arr as $k => $v)
			{
				$data = array();
				if($k == 0)
				{
					$data['arrival_time'] = ":NULL";
					$b_data['departure_time'] = date('H:i:s', strtotime($today . ' ' . $this->_post->toString('departure_time_' . $v['city_id'])));
				}else{
					$data['arrival_time'] = date('H:i:s', strtotime($today . ' ' . $this->_post->toString('arrival_time_' . $v['city_id'])));
				}
				if($k == ($number_of_locations - 1))
				{
					$data['departure_time'] = ":NULL";
					$b_data['arrival_time'] = date('H:i:s', strtotime($today . ' ' . $this->_post->toString('arrival_time_' . $v['city_id'])));
				}else{
					$data['departure_time'] = date('H:i:s', strtotime($today . ' ' . $this->_post->toString('departure_time_' . $v['city_id'])));
				}
				if($pjBusLocationModel->reset()->where('bus_id', $arr['id'])->where('location_id', $v['city_id'])->findCount()->getData() > 0)
				{
					$pjBusLocationModel->reset()->where('bus_id', $arr['id'])->where('location_id', $v['city_id'])->limit(1)->modifyAll($data);
					
				}else{
					$data['bus_id'] = $arr['id'];
					$data['location_id'] = $v['city_id'];
					$pjBusLocationModel->reset()->setAttributes($data)->insert();
				}
			}
			$pjBusModel->reset()->where('id', $arr['id'])->limit(1)->modifyAll(array_merge($this->_post->raw(), $b_data));
			
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBuses&action=pjActionTime&id=" . $arr['id'] . "&err=ABS01");
		}
		
		if (self::isGet() && $this->_get->toInt('id'))
    	{
    		$arr = pjBusModel::factory()->find($this->_get->toInt('id'))->getData();			
			if (count($arr) === 0)
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBuses&action=pjActionIndex&err=ABS08");
			}
			$this->set('arr', $arr);
			
			$route_arr = pjRouteModel::factory()
				->select(" t1.*, t2.content as title")
				->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('t1.status', 'T')
				->find($arr['route_id'])
				->getData();				
			$this->set('route_arr', $route_arr);
			
			$bus_type_arr = pjBusTypeModel::factory()
						->select(" t1.*, t2.content as name")
						->join('pjMultiLang', "t2.model='pjBusType' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->where('t1.status', 'T')
						->orderBy("t2.content ASC")->findAll()->getData();
						
			$this->set('bus_type_arr', $bus_type_arr);
			
			$location_arr = pjRouteCityModel::factory()
				->select('t1.*, t2.content as name')
				->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.city_id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('route_id', $arr['route_id'])
				->orderBy("t1.order ASC")
				->findAll()
				->getData();
			$this->set('location_arr', $location_arr);
			
			$sl_arr = array(); 
			$_sl_arr = pjBusLocationModel::factory()
				->where('bus_id', $this->_get->toInt('id'))
				->findAll()
				->getData();
			foreach($_sl_arr as $k => $v)
			{
				$sl_arr[$v['location_id']] = $v;
			}
			$this->set('sl_arr', $sl_arr);
			$this->set('date_format', pjUtil::toBootstrapDate($this->option_arr['o_date_format']));	
			
			$this->appendCss('clockpicker.css', PJ_THIRD_PARTY_PATH . 'clockpicker/');
	        $this->appendJs('clockpicker.js', PJ_THIRD_PARTY_PATH . 'clockpicker/');
			$this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
			$this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
			$this->appendJs('pjAdminBuses.js');
    	}
	}

	public function pjActionGetLocations()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if ($this->_get->check('route_id') && $this->_get->toInt('route_id') > 0)
			{
				$location_arr = pjRouteCityModel::factory()
					->select('t1.*, t2.content as name')
					->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.city_id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->where('route_id', $this->_get->toInt('route_id'))
					->orderBy("t1.order ASC")
					->findAll()->getData();
				$this->set('location_arr', $location_arr);
				
				if($this->_get->check('bus_id') && $this->_get->toInt('bus_id') > 0)
				{
					$sl_arr = array(); 
					$_sl_arr = pjBusLocationModel::factory()
						->where('bus_id', $this->_get->toInt('bus_id'))
						->findAll()
						->getData();
					foreach($_sl_arr as $k => $v)
					{
						$sl_arr[$v['location_id']] = $v;
					}
					$this->set('sl_arr', $sl_arr);
				}
			}
		}
	}
	
	public function pjActionNotOperating()
	{
		$this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
		
		if (self::isPost() && $this->_post->check('bus_update'))
		{
			$pjBusDateModel = pjBusDateModel::factory();
			$pjBusDateModel->where('bus_id', $this->_post->toInt('id'))->eraseAll();
			if($date_arr = $this->_post->toArray('date'))
			{
				foreach($date_arr as $date)
				{
					if(!empty($date))
					{
						$data = array();
						$data['bus_id'] = $this->_post->toInt('id');
						$data['date'] = pjDateTime::formatDate($date, $this->option_arr['o_date_format']);
						$pjBusDateModel->reset()->setAttributes($data)->insert();
					}
				}
			}			
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBuses&action=pjActionNotOperating&id=" . $this->_post->toInt('id') . "&err=ABS11");
		}
		
		if (self::isGet() && $this->_get->toInt('id'))
    	{
    		$arr = pjBusModel::factory()->find($this->_get->toInt('id'))->getData();			
			if (count($arr) === 0)
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBuses&action=pjActionIndex&err=ABS08");
			}
			$this->set('arr', $arr);
			
			$this->set('date_arr', pjBusDateModel::factory()->where('bus_id', $arr['id'])->orderBy("`date` ASC")->findAll()->getData());
				
			$route_arr = pjRouteModel::factory()
				->select(" t1.*, t2.content as title")
				->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('t1.status', 'T')
				->find($arr['route_id'])
				->getData();				
			$this->set('route_arr', $route_arr);
			$this->set('date_format', pjUtil::toBootstrapDate($this->option_arr['o_date_format']));	
			
			$this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
			$this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
			$this->appendJs('pjAdminBuses.js');
    	}
	}
	
	public function pjActionTicket()
	{
		$this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
		
		if (self::isPost() && $this->_post->check('bus_update'))
		{
			$pjBusModel = pjBusModel::factory();
			$arr = pjBusModel::factory()->find($this->_post->toInt('id'))->getData();
			if (empty($arr))
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBuses&action=pjActionIndex&err=AR08");
			}
			
			$s_arr = array();
			if($this->_post->check('set_seats_count'))
			{
				$s_arr['set_seats_count'] = 'T';
			}else{
				$s_arr['set_seats_count'] = 'F';
			}
			$pjBusModel->reset()->where('id', $this->_post->toInt('id'))->limit(1)->modifyAll($s_arr);
			
			$pjMultiLangModel = pjMultiLangModel::factory();
			$pjTicketModel = pjTicketModel::factory();
			if ($i18n_arr = $this->_post->toArray('i18n'))
			{
				if($this->_post->check('index_arr') && $this->_post->toString('index_arr') != '')
				{
					$index_arr = explode("|", $this->_post->toString('index_arr'));		
					$seats_count_arr = 	$this->_post->toArray('seats_count');
					foreach($index_arr as $k => $v)
					{
						if(strpos($v, 'bs') !== false)
						{
							$t_data = array();
							$t_data['bus_id'] = $this->_post->toInt('id');
							if($this->_post->check('set_seats_count'))
							{
								$t_data['seats_count'] = $seats_count_arr[$v];
							}
							$ticket_id = $pjTicketModel->reset()->setAttributes($t_data)->insert()->getInsertId();
							if ($ticket_id !== false && (int) $ticket_id > 0)
							{
								foreach ($i18n_arr as $locale => $locale_arr)
								{
									foreach ($locale_arr as $field => $content)
									{
										if(is_array($content))
										{
											$insert_id = $pjMultiLangModel->reset()->setAttributes(array(
												'foreign_id' => $ticket_id,
												'model' => 'pjTicket',
												'locale' => $locale,
												'field' => $field,
												'content' => $content[$v],
												'source' => 'data'
											))->insert()->getInsertId();
										}
									}
								}
							}
						}else{
							$t_data = array();
							if($this->_post->check('set_seats_count'))
							{
								$t_data['seats_count'] = $seats_count_arr[$v];
							}else{
								$t_data['seats_count'] = ':NULL';
							}
							$pjTicketModel->reset()->where('id', $v)->limit(1)->modifyAll($t_data);
							foreach ($i18n_arr as $locale => $locale_arr)
							{
								foreach ($locale_arr as $field => $content)
								{
									if(is_array($content))
									{
										$sql = sprintf("INSERT INTO `%1\$s` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
											VALUES (NULL, :foreign_id, :model, :locale, :field, :update_content, :source)
											ON DUPLICATE KEY UPDATE `content` = :update_content, `source` = :source;",
											$pjMultiLangModel->getTable()
										);
										$foreign_id = $v;
										$model = 'pjTicket';
										$source = 'data';
										$update_content = $content[$v];
										$pjMultiLangModel->prepare($sql)->exec(compact('foreign_id', 'model', 'locale', 'field', 'update_content', 'source'));
									}
								}
							}
						}
					}
				}
			}
			if($this->_post->check('remove_arr') && $this->_post->toString('remove_arr') != '')
			{
				$remove_arr = explode("|", $this->_post->toString('remove_arr'));				
				$pjMultiLangModel->reset()->where('model', 'pjTicket')->whereIn('foreign_id', $remove_arr)->eraseAll();
				pjPriceModel::factory()->whereIn('ticket_id', $remove_arr)->eraseAll();
				$pjTicketModel->reset()->whereIn('id', $remove_arr)->eraseAll();
			}
			
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBuses&action=pjActionTicket&id=" . $this->_post->toInt('id') . "&err=ABS09");
		}
		
		if (self::isGet() && $this->_get->toInt('id'))
    	{
    		$arr = pjBusModel::factory()->find($this->_get->toInt('id'))->getData();			
			if (count($arr) === 0)
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBuses&action=pjActionIndex&err=ABS08");
			}
			$this->set('arr', $arr);
			
			$seats_available = 0;
			if(!empty($arr['bus_type_id']))
			{
				$bus_type_arr = pjBusTypeModel::factory()->find($arr['bus_type_id'])->getData();
				if(!empty($bus_type_arr))
				{
					$seats_available = $bus_type_arr['seats_count'];
				}
			}
			
			$ticket_arr = pjTicketModel::factory()->where('bus_id', $arr['id'])->findAll()->getData();
			foreach($ticket_arr as $k => $v)
			{
				$ticket_arr[$k]['i18n'] = pjMultiLangModel::factory()->getMultiLang($v['id'], 'pjTicket');
			}

			$route_arr = pjRouteModel::factory()
				->select(" t1.*, t2.content as title")
				->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('t1.status', 'T')
				->find($arr['route_id'])
				->getData();
			$this->set('ticket_arr', $ticket_arr);
			$this->set('seats_available', $seats_available);
			$this->set('route_arr', $route_arr);
				
			$this->setLocalesData();
			
			$this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
			$this->appendJs('pjAdminBuses.js');
    	}
	}
	
	public function pjActionPrice()
	{
		$this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
		
		if (self::isPost() && $this->_post->check('bus_update'))
		{
			$pjBusModel = pjBusModel::factory();
			$id = $this->_post->toInt('id');
			$arr = $pjBusModel->find($id)->getData();
			
			if (count($arr) === 0)
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBuses&action=pjActionIndex&err=ABS08");
			}
			
			$discount = 0;
			if($this->_post->toFloat('discount') > 0)
			{
				$discount = $this->_post->toFloat('discount');
			}
			$pjBusModel->reset()->where('id', $id)->limit(1)->modifyAll(array('discount' => $discount));
			
			$pjPriceModel = pjPriceModel::factory();
			$location_arr = pjRouteCityModel::factory()
				->select('t1.*')
				->where('route_id', $arr['route_id'])
				->orderBy("t1.order ASC")
				->findAll()
				->getData();
			
			$ticket_id = $this->_post->toInt('ticket_id');
			$number_of_locations = count($location_arr);
			foreach($location_arr as $k => $row)
			{
				if($k <= ($number_of_locations - 2))
				{
					$j = 1;
					foreach($location_arr as $col)
					{
						if($j > 1)
						{
							$cnt = $pjPriceModel
								->reset()
								->where('ticket_id', $ticket_id)
								->where('from_location_id', $row['city_id'])
								->where('to_location_id', $col['city_id'])
								->where('is_return = "F"')
								->findCount()
								->getData();
								
							$price = $this->_post->toFloat('price_' . $row['city_id'] . '_' . $col['city_id']);
							if($price != '')
							{
								if (!is_numeric($price)) 
								{
									$price = ':NULL';
								}else{
									if($price < 0)
									{
										$price = ':NULL';
									}
								}
							}else{
								$price = ':NULL';
							}
							if($cnt == 0)
							{
								$data = array();
								$data['bus_id'] = $id;
								$data['ticket_id'] = $ticket_id;
								$data['from_location_id'] = $row['city_id'];
								$data['to_location_id'] = $col['city_id'];
								$data['price'] = $price;
								$data['is_return'] = 'F';
								$pjPriceModel->reset()->setAttributes($data)->insert();
							}else{
								$pjPriceModel->reset()
									->where('bus_id', $id)
									->where('ticket_id', $ticket_id)
									->where('from_location_id', $row['city_id'])
									->where('to_location_id', $col['city_id'])
									->where('is_return = "F"')
									->limit(1)
									->modifyAll(array('price' => $price));
							}
						}
						$j++;
					}
				}
			}
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBuses&action=pjActionPrice&id=" . $id . "&ticket_id=$ticket_id&err=AS10");
		}
		
		if (self::isGet() && $this->_get->toInt('id'))
    	{
    		$arr = pjBusModel::factory()->find($this->_get->toInt('id'))->getData();			
			if (count($arr) === 0)
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBuses&action=pjActionIndex&err=ABS08");
			}
			$this->set('arr', $arr);
			
			$ticket_arr = pjTicketModel::factory()
				->select(" t1.*, t2.content as title")
				->join('pjMultiLang', "t2.model='pjTicket' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('t1.bus_id', $arr['id'])
				->orderBy("t2.content ASC")
				->findAll()
				->getData();
						
			$this->set('ticket_arr', $ticket_arr);

			$pjRouteCityModel = pjRouteCityModel::factory();

			$location_arr = $pjRouteCityModel->select('t1.*, t2.content as name')
				->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.city_id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('route_id', $arr['route_id'])
				->orderBy("t1.order ASC")
				->findAll()->getData();
			
			if(count($ticket_arr) > 0)
			{
				if(!$this->_get->check('ticket_id'))
				{
					$ticket_id = $ticket_arr[0]['id'];
				}else{
					$ticket_id = $this->_get->toInt('ticket_id');
				}
				$location_id_arr = $pjRouteCityModel
					->reset()
					->where('t1.route_id', $arr['route_id'])
					->findAll()
					->getDataPair('city_id', 'city_id');
					
				$price_arr = array();
				if(!empty($location_id_arr))
				{
					$_price_arr = pjPriceModel::factory()
						->where('ticket_id', $ticket_id)
						->whereIn('from_location_id', $location_id_arr)
						->where('is_return = "F"')
						->findAll()
						->getData();
					if(!empty($_price_arr))
					{
						foreach($_price_arr as $v)
						{
							$price_arr[$v['from_location_id'] . '_' . $v['to_location_id']] = $v['price'];
							$price_arr[$v['from_location_id'] . '_' . $v['to_location_id']] = $v['price'];
						}
					}
				}
				$this->set('price_arr', $price_arr);
				
				$return_price_arr = array();
				if(!empty($location_id_arr))
				{
					$_price_arr = pjPriceModel::factory()
						->where('ticket_id', $ticket_id)
						->where('is_return = "T"')
						->whereIn('from_location_id', $location_id_arr)
						->findAll()->getData();
					if(!empty($_price_arr))
					{
						foreach($_price_arr as $v)
						{
							$return_price_arr[$v['from_location_id'] . '_' . $v['to_location_id']] = $v['price'];
							$return_price_arr[$v['from_location_id'] . '_' . $v['to_location_id']] = $v['price'];
						}
					}
				}
				
				$this->set('return_price_arr', $return_price_arr);
				
				$this->set('ticket_id', $ticket_id);
			}
			
			$this->set('arr', $arr);
			$this->set('location_arr', $location_arr);
						
			$bus_arr = pjBusModel::factory()
				->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.route_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->select(" t1.*, t2.content AS route")
				->where('t1.route_id', $arr['route_id'])
				->where('t1.id <>', $arr['id'])
				->orderBy("route asc")
				->findAll()
				->getData();
			$this->set('bus_arr', $bus_arr);
			
			$route_arr = pjRouteModel::factory()
				->select(" t1.*, t2.content as title")
				->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('t1.status', 'T')
				->find($arr['route_id'])
				->getData();
			$this->set('route_arr', $route_arr);
			
			$this->appendJs('tableHeadFixer.js');
			$this->appendJs('pjAdminBuses.js');
    	}
	}
	
	public function pjActionGetPriceGrid()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$arr = pjBusModel::factory()->find($this->_get->toInt('bus_id'))->getData();
			
			$pjRouteCityModel = pjRouteCityModel::factory();

			$location_arr = $pjRouteCityModel
				->select('t1.*, t2.content as name')
				->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.city_id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('route_id', $arr['route_id'])
				->orderBy("t1.order ASC")
				->findAll()
				->getData();
			
			
			$ticket_id = $this->_get->toInt('ticket_id');
			$location_id_arr = $pjRouteCityModel
				->reset()
				->where('t1.route_id', $arr['route_id'])
				->findAll()
				->getDataPair('city_id', 'city_id');
			$price_arr = array();
			if(!empty($location_id_arr))
			{
				$_price_arr = pjPriceModel::factory()
					->where('ticket_id', $ticket_id)
					->whereIn('from_location_id', $location_id_arr)
					->findAll()
					->getData();
				if(!empty($_price_arr))
				{
					foreach($_price_arr as $v)
					{
						$price_arr[$v['from_location_id'] . '_' . $v['to_location_id']] = $v['price'];
					}
				}
			}
			$this->set('price_arr', $price_arr);
			$this->set('ticket_id', $ticket_id);

			$this->set('arr', $arr);
			$this->set('location_arr', $location_arr);
		}
	}
	
	public function pjActionGetTickets()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$ticket_arr = array();
			if ($this->_get->check('bus_id') && $this->_get->toInt('bus_id') > 0)
			{
				$ticket_arr = pjTicketModel::factory()->select('t1.*, t2.content as title')
								->join('pjMultiLang', "t2.model='pjTicket' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
								->where('bus_id', $this->_get->toInt('bus_id'))->orderBy("t2.content ASC")->findAll()->getData();
			}
			$this->set('ticket_arr', $ticket_arr);
		}
	}
	
	public function pjActionCopyPrices()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array();
			
			$source_bus_id = $this->_post->toInt('source_bus_id');
			$source_ticket_id = $this->_post->toInt('source_ticket_id');
			$dst_bus_id = $this->_get->toInt('bus_id');
			$dst_ticket_id = $this->_get->toInt('ticket_id');
			
			$pjPriceModel = pjPriceModel::factory();
			$price_arr = $pjPriceModel->where('bus_id', $source_bus_id)->where('ticket_id', $source_ticket_id)->findAll()->getData();
			foreach($price_arr as $v)
			{
				$cnt = $pjPriceModel->reset()->where('bus_id', $dst_bus_id)->where('ticket_id', $dst_ticket_id)->where('from_location_id', $v['from_location_id'])->where('to_location_id', $v['to_location_id'])->findCount()->getData();
				$price = $v['price'];
				if($cnt == 0)
				{
					$data = array();
					$data['bus_id'] = $dst_bus_id;
					$data['ticket_id'] = $dst_ticket_id;
					$data['from_location_id'] = $v['from_location_id'];
					$data['to_location_id'] = $v['to_location_id'];
					$data['price'] = $price;
					$pjPriceModel->reset()->setAttributes($data)->insert();
				}else{
					$pjPriceModel->reset()
						->where('bus_id', $dst_bus_id)
						->where('ticket_id', $dst_ticket_id)
						->where('from_location_id', $v['from_location_id'])
						->where('to_location_id', $v['to_location_id'])
						->limit(1)
						->modifyAll(array('price' => $price));
				}
			}
			$response['code'] = 200;
			pjAppController::jsonResponse($response);
		}
	}
}
?>