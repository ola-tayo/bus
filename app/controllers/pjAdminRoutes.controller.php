<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminRoutes extends pjAdmin
{
	public function pjActionDeleteRoute()
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
		if (!pjAuth::factory()->hasAccess())
		{
		    self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
		}
		if (!($this->_get->toInt('id')))
		{
		    self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
		}
		if (!pjRouteModel::factory()->set('id', $this->_get->toInt('id'))->erase()->getAffectedRows())
		{
		    self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => 'Route has not been deleted.'));
		}
		pjMultiLangModel::factory()->where('model', 'pjRoute')->where('foreign_id', $this->_get->toInt('id'))->eraseAll();
		pjRouteDetailModel::factory()->where('route_id', $this->_get->toInt('id'))->eraseAll();
		pjRouteCityModel::factory()->where('route_id', $this->_get->toInt('id'))->eraseAll();
		self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Route has been deleted'));
		exit;
	}
	
	public function pjActionDeleteRouteBulk()
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
	    if (!pjAuth::factory()->hasAccess())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
	    }
	    if (!$this->_post->has('record'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $record = $this->_post->toArray('record');
	    if (empty($record))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    pjMultiLangModel::factory()->where('model', 'pjRoute')->whereIn('foreign_id', $record)->eraseAll();
	    pjRouteModel::factory()->whereIn('id', $record)->eraseAll();
	    pjRouteDetailModel::factory()->whereIn('route_id', $record)->eraseAll();
		pjRouteCityModel::factory()->whereIn('route_id', $record)->eraseAll();
	    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Routes has been deleted.'));
	    exit;
	}
	
	public function pjActionGetRoute()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjRouteModel = pjRouteModel::factory()
				->join('pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->join('pjMultiLang', "t3.model='pjRoute' AND t3.foreign_id=t1.id AND t3.field='from' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
				->join('pjMultiLang', "t4.model='pjRoute' AND t4.foreign_id=t1.id AND t4.field='to' AND t4.locale='".$this->getLocaleId()."'", 'left outer');
			if ($q = $this->_get->toString('q'))
			{
			    $pjRouteModel->where("(t2.content LIKE '%$q%')");
			}
			if ($this->_get->toString('status'))
			{
			    $status = $this->_get->toString('status');
			    if(in_array($status, array('T', 'F')))
			    {
			        $pjRouteModel->where('t1.status', $status);
			    }
			}
			$column = 'title';
			$direction = 'ASC';
			if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
			{
			    $column = $this->_get->toString('column');
			    $direction = strtoupper($this->_get->toString('direction'));
			}
			$total = $pjRouteModel->findCount()->getData();
			$rowCount = $this->_get->toInt('rowCount') ?: 50;
			$pages = ceil($total / $rowCount);
			$page = $this->_get->toInt('page') ?: 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}
			$data = $pjRouteModel
			->select(" t1.id, t1.status, t2.content as title, t3.content as `from`, t4.content as `to`")
			->orderBy("`$column` $direction")
			->limit($rowCount, $offset)
			->findAll()
			->getData();
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
	    $this->setLocalesData();
	    	    
	    $city_arr = pjCityModel::factory()
			->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
			->select('t1.*, t2.content as name')
			->where('status', 'T')
			->orderBy("name ASC")
			->findAll()
			->getData();
		$this->set('city_arr', $city_arr);
		
	    $this->set('has_create', pjAuth::factory('pjAdminRoutes', 'pjActionCreateForm')->hasAccess());
		$this->set('has_update', pjAuth::factory('pjAdminRoutes', 'pjActionUpdateForm')->hasAccess());
		$this->set('has_delete', pjAuth::factory('pjAdminRoutes', 'pjActionDeleteRoute')->hasAccess());
		$this->set('has_delete_bulk', pjAuth::factory('pjAdminRoutes', 'pjActionDeleteRouteBulk')->hasAccess());
	    
		$this->appendJs('jquery-ui.min.js', PJ_THIRD_PARTY_PATH . 'jquery_ui/');
	    $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
	    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
	    $this->appendJs('pjAdminRoutes.js');
	}
	
	public function pjActionCreate()
	{
	    $this->setAjax(true);
	    if (!pjAuth::factory()->hasAccess())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
	    }
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    if (!self::isPost())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
	    }
	    if (!$this->_post->toInt('route_create'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $pjRouteModel = pjRouteModel::factory();
	    $data = array();
	    $data['status'] = $this->_post->check('status') ? 'T' : 'F';	    
	    $id = $pjRouteModel->setAttributes(array_merge($this->_post->raw(), $data))->insert()->getInsertId();
	    if ($id !== false && (int) $id > 0)
	    {
	    	$i18n = $this->_post->toArray('i18n');
	        if ($i18n)
	        {
	            pjMultiLangModel::factory()->saveMultiLang($i18n, $id, 'pjRoute', 'data');
	        }
	        if($this->_post->check('index_arr') && !$this->_post->isEmpty('index_arr'))
			{
				$index_arr = explode("|", $this->_post->toString('index_arr'));
				
				$pjRouteCityModel = pjRouteCityModel::factory();
				$pjMultiLangModel = pjMultiLangModel::factory();
				foreach($index_arr as $k => $index)
				{
					if($this->_post->check('city_id_' . $index) && $this->_post->toInt('city_id_' . $index) > 0)
					{
						$city_id = $this->_post->toInt('city_id_' . $index);
						$data = array();
						$data['route_id'] = $id;
						$data['city_id'] = $city_id;
						$data['order'] = $k + 1;
						$pjRouteCityModel->reset()->setAttributes($data)->insert();
						
						if($k == 0)
						{
							$i18n_arr = $pjMultiLangModel->reset()->getMultiLang($city_id, 'pjCity');
							$i18n_arr = pjUtil::changeLangField($i18n_arr, 'from', 'name');
							$pjMultiLangModel->reset()->saveMultiLang($i18n_arr, $id, 'pjRoute', 'data');
						}
						if($k == count($index_arr) - 1)
						{
							$i18n_arr = $pjMultiLangModel->reset()->getMultiLang($city_id, 'pjCity');
							$i18n_arr = pjUtil::changeLangField($i18n_arr, 'to', 'name');
							$pjMultiLangModel->reset()->saveMultiLang($i18n_arr, $id, 'pjRoute', 'data');
						}
					}
				}
			}
			pjRouteModel::factory()->updateRouteDetail($id);
	        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Route has been added!'));
	    } else {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Route could not be added!'));
	    }
	    exit;
	}
	
	public function pjActionUpdate()
	{
	    $this->setAjax(true);
	    if (!pjAuth::factory()->hasAccess())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
	    }
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    if (!self::isPost())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
	    }
	    if (!$this->_post->toInt('route_update'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    if (!$this->_post->toInt('id'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $pjRouteModel = pjRouteModel::factory();
	    $pjMultiLangModel = pjMultiLangModel::factory();
	    
	    $id = $this->_post->toInt('id');
	    $data = array();
	    $data['status'] = $this->_post->check('status') ? 'T' : 'F';
	    $pjRouteModel->reset()->where('id', $id)->limit(1)->modifyAll(array_merge($this->_post->raw(), $data));
	    $i18n = $this->_post->toArray('i18n');
	    if ($i18n)
	    {
	        $pjMultiLangModel->updateMultiLang($i18n, $id, 'pjRoute', 'data');
	    }
	    
		if(!$this->_post->check('has_bookings'))
		{
			$pjRouteCityModel = pjRouteCityModel::factory();
			$pjRouteCityModel->where('route_id', $id)->eraseAll();
			if($this->_post->check('index_arr') && !$this->_post->isEmpty('index_arr'))
			{
				$index_arr = explode("|", $this->_post->toString('index_arr'));			
				foreach($index_arr as $k => $index)
				{
					if($this->_post->check('city_id_' . $index) && $this->_post->toInt('city_id_' . $index) > 0)
					{
						$city_id = $this->_post->toInt('city_id_' . $index);
						$data = array();
						$data['route_id'] = $id;
						$data['city_id'] = $city_id;
						$data['order'] = $k + 1;
						$pjRouteCityModel->reset()->setAttributes($data)->insert();
						if($k == 0)
						{
							$i18n_arr = $pjMultiLangModel->reset()->getMultiLang($city_id, 'pjCity');
							$i18n_arr = pjUtil::changeLangField($i18n_arr, 'from', 'name');
							$pjMultiLangModel->reset()->updateMultiLang($i18n_arr, $id, 'pjRoute', 'data');
						}
						if($k == (count($index_arr) - 1))
						{
							$i18n_arr = $pjMultiLangModel->reset()->getMultiLang($city_id, 'pjCity');
							$i18n_arr = pjUtil::changeLangField($i18n_arr, 'to', 'name');
							$pjMultiLangModel->reset()->updateMultiLang($i18n_arr, $id, 'pjRoute', 'data');
						}
					}
				}
			}
			$pjRouteModel->updateRouteDetail($id);
			$pjRouteModel->updateBusTime($id);
			$pjRouteModel->updateBusPrice($id);
		}
	    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Route has been updated!'));
	    exit;
	}
	
	public function pjActionSaveRoute()
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
	    $pjRouteModel = pjRouteModel::factory();
	    $arr = $pjRouteModel->find($this->_get->toInt('id'))->getData();
	    if (!$arr)
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Route not found.'));
	    }
	    if (!in_array($this->_post->toString('column'), $pjRouteModel->getI18n()))
	    {
	        $pjRouteModel->reset()->where('id', $this->_get->toInt('id'))->limit(1)->modifyAll(array($this->_post->toString('column') => $this->_post->toString('value')));
	    } else {
	        pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($this->_post->toString('column') => $this->_post->toString('value'))), $this->_get->toInt('id'), 'pjRoute', 'data');
	    }
	    
	    self::jsonResponse(array('status' => 'OK', 'code' => 201, 'text' => 'Route has been updated.'));
	    
	    exit;
	}
	
	public function pjActionCreateForm()
	{
	    $this->setAjax(true);
	    
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    if (!self::isGet())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
	    }
	    $this->setLocalesData();
	    
	    if($this->_get->check('from_id') && $this->_get->toInt('from_id') > 0)
		{
			$direction = 'ASC';
			if($this->_get->check('type') && $this->_get->toString('type') == 'reverse')
			{
				$direction = 'DESC';
			}
			$city_id_arr = pjRouteCityModel::factory()->getCity($this->_get->toInt('from_id'), $direction);
			$this->set('city_id_arr', $city_id_arr);
		}
		
		$city_arr = pjCityModel::factory()
			->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
			->select('t1.*, t2.content as name')
			->where('status', 'T')
			->orderBy("name ASC")
			->findAll()
			->getData();
		$this->set('city_arr', $city_arr);
	}
	
	public function pjActionUpdateForm()
	{
	    $this->setAjax(true);
	    
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    if (!self::isGet())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
	    }
	    if ($this->_get->toInt('id'))
	    {
	        $id = $this->_get->toInt('id');
	        $arr = pjRouteModel::factory()->find($id)->getData();
	        if (count($arr) === 0)
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Route is not found.'));
	        }
	        $arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjRoute');
			$arr['city'] = pjRouteCityModel::factory()->getCity($arr['id'], 'ASC');
	        $this->set('arr', $arr);
	        
	        $city_arr = pjCityModel::factory()
				->join('pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->select('t1.*, t2.content as name')
				->where('status', 'T')
				->orderBy("name ASC")
				->findAll()
				->getData();
			$this->set('city_arr', $city_arr);
			
	        $cnt_bookings = pjBookingModel::factory()
				->where("t1.bus_id IN (SELECT TB.id FROM `".pjBusModel::factory()->getTable()."` AS TB WHERE TB.route_id = ".$id.")")
				->findCount()
				->getData();
			$this->set('cnt_bookings', $cnt_bookings);
	        $this->setLocalesData();
	    }else{
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Missing parameters.'));
	    }
	}
}
?>