<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminCities extends pjAdmin
{
	public function pjActionDeleteCity()
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
		if (!pjCityModel::factory()->set('id', $this->_get->toInt('id'))->erase()->getAffectedRows())
		{
		    self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => 'City has not been deleted.'));
		}
		pjMultiLangModel::factory()->where('model', 'pjCity')->where('foreign_id', $this->_get->toInt('id'))->eraseAll();
		self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'City has been deleted'));
		exit;
	}
	
	public function pjActionDeleteCityBulk()
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
	    pjMultiLangModel::factory()->where('model', 'pjCity')->whereIn('foreign_id', $record)->eraseAll();
	    pjCityModel::factory()->whereIn('id', $record)->eraseAll();
	    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Cities has been deleted.'));
	    exit;
	}
	
	public function pjActionGetCity()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjCityModel = pjCityModel::factory()->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjCity' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left');
			if ($q = $this->_get->toString('q'))
			{
			    $pjCityModel->where("(t2.content LIKE '%$q%')");
			}
			if ($this->_get->toString('status'))
			{
			    $status = $this->_get->toString('status');
			    if(in_array($status, array('T', 'F')))
			    {
			        $pjCityModel->where('t1.status', $status);
			    }
			}
			$column = 'name';
			$direction = 'ASC';
			if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
			{
			    $column = $this->_get->toString('column');
			    $direction = strtoupper($this->_get->toString('direction'));
			}
			$total = $pjCityModel->findCount()->getData();
			$rowCount = $this->_get->toInt('rowCount') ?: 50;
			$pages = ceil($total / $rowCount);
			$page = $this->_get->toInt('page') ?: 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}
			$data = $pjCityModel
			->select("t1.*, t2.content AS name")
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
	    
	    $this->set('has_create', pjAuth::factory('pjAdminCities', 'pjActionCreateForm')->hasAccess());
		$this->set('has_update', pjAuth::factory('pjAdminCities', 'pjActionUpdateForm')->hasAccess());
		$this->set('has_delete', pjAuth::factory('pjAdminCities', 'pjActionDeleteCity')->hasAccess());
		$this->set('has_delete_bulk', pjAuth::factory('pjAdminCities', 'pjActionDeleteCityBulk')->hasAccess());
	    
	    $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
	    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
	    $this->appendJs('pjAdminCities.js');
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
	    if (!$this->_post->toInt('city_create'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $pjCityModel = pjCityModel::factory();
	    $data = array();
	    $data['status'] = $this->_post->check('status') ? 'T' : 'F';	    
	    $id = $pjCityModel->setAttributes(array_merge($this->_post->raw(), $data))->insert()->getInsertId();
	    if ($id !== false && (int) $id > 0)
	    {
	    	$i18n = $this->_post->toArray('i18n');
	        if ($i18n)
	        {
	            pjMultiLangModel::factory()->saveMultiLang($i18n, $id, 'pjCity', 'data');
	        }
	        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'City has been added!'));
	    } else {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'City could not be added!'));
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
	    if (!$this->_post->toInt('city_update'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    if (!$this->_post->toInt('id'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $pjCityModel = pjCityModel::factory();
	    $data = array();
	    $data['status'] = $this->_post->check('status') ? 'T' : 'F';
	    $pjCityModel->reset()->where('id', $this->_post->toInt('id'))->limit(1)->modifyAll(array_merge($this->_post->raw(), $data));
	    $i18n = $this->_post->toArray('i18n');
	    if ($i18n)
	    {
	        pjMultiLangModel::factory()->updateMultiLang($i18n, $post['id'], 'pjCity', 'data');
	    }
	    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'City has been updated!'));
	    exit;
	}
	
	public function pjActionSaveCity()
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
	    $pjCityModel = pjCityModel::factory();
	    $arr = $pjCityModel->find($this->_get->toInt('id'))->getData();
	    if (!$arr)
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'City not found.'));
	    }
	    if (!in_array($this->_post->toString('column'), $pjCityModel->getI18n()))
	    {
	        $pjCityModel->reset()->where('id', $this->_get->toInt('id'))->limit(1)->modifyAll(array($this->_post->toString('column') => $this->_post->toString('value')));
	    } else {
	        pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($this->_post->toString('column') => $this->_post->toString('value'))), $this->_get->toInt('id'), 'pjCity', 'data');
	    }
	    
	    self::jsonResponse(array('status' => 'OK', 'code' => 201, 'text' => 'City has been updated.'));
	    
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
	        $arr = pjCityModel::factory()->find($id)->getData();
	        if (count($arr) === 0)
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'City is not found.'));
	        }
	        $arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjCity');
	        $this->set('arr', $arr);
	        
	        $this->setLocalesData();
	    }else{
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Missing parameters.'));
	    }
	}
}
?>