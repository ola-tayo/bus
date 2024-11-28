<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminBusTypes extends pjAdmin
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
			pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminBusTypes&action=pjActionIndex&err=ABT05");
		}
		if (self::isPost() && $this->_post->check('bus_type_create'))
		{
			$pjBusTypeModel = pjBusTypeModel::factory();
			$pjSeatModel = pjSeatModel::factory();
			$data = array();
			$data['use_map'] = $this->_post->check('use_map') ? 'T' : 'F';
			$id = $pjBusTypeModel->setAttributes(array_merge($this->_post->raw(), $data))->insert()->getInsertId();
			if ($id !== false && (int) $id > 0)
			{
				$i18n_arr = $this->_post->toI18n('i18n');
				if ($i18n_arr)
				{
					pjMultiLangModel::factory()->saveMultiLang($i18n_arr, $id, 'pjBusType', 'data');
				}
				if($this->_post->check('use_map'))
				{
					if (isset($_FILES['seats_map']))
					{
						if($_FILES['seats_map']['error'] == 0)
						{
							if(getimagesize($_FILES['seats_map']["tmp_name"]) != false)
							{
								if (is_writable('app/web/upload/bus_types'))
								{
									$Image = new pjImage();
									if ($Image->getErrorCode() !== 200)
									{
										$Image->setAllowedTypes(array('image/png', 'image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg'));
										if ($Image->load($_FILES['seats_map']))
										{
											$resp = $Image->isConvertPossible();
											if ($resp['status'] === true)
											{
												$hash = md5(uniqid(rand(), true));
												$image_path = PJ_UPLOAD_PATH . 'bus_types/' . $id . '_' . $hash . '.' . $Image->getExtension();
												
												$Image->loadImage();
												$Image->saveImage($image_path);
												$data = array();
												$data['seats_map'] = $image_path;
																														
												$pjBusTypeModel->reset()->where('id', $id)->limit(1)->modifyAll($data);
											}
										}
									}
								}else{
									pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBusTypes&action=pjActionUpdate&id=$id&err=ABT11");
								}
							}else{
								pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBusTypes&action=pjActionUpdate&id=$id&err=ABT12");
							}
						}else if($_FILES['seats_map']['error'] != 4){
							pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBusTypes&action=pjActionUpdate&id=$id&err=ABT09");
						}
					}
				}else{
					$seats_count = $this->_post->toInt('seats_count');
					for($i = 1; $i <= $seats_count; $i++)
					{
						$sdata = array();
						$sdata['bus_type_id'] = $id;
						$sdata['name'] = $i;
						$pjSeatModel->reset()->setAttributes($sdata)->insert();
					}
				}				
				$err = 'ABT03';				
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBusTypes&action=pjActionUpdate&id=$id&err=$err");
			} else {
				$err = 'ABT04';
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBusTypes&action=pjActionIndex&err=$err");
			}			
		} else {
			$this->setLocalesData();	

			$this->appendCss('jasny-bootstrap.min.css', PJ_THIRD_PARTY_PATH . 'jasny/');
	        $this->appendJs('jasny-bootstrap.min.js',  PJ_THIRD_PARTY_PATH . 'jasny/');
			$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminBusTypes.js');
		}
	}
	
	public function pjActionDeleteBusType()
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
	    $pjBusTypeModel = pjBusTypeModel::factory();
	    $arr = $pjBusTypeModel->find($id)->getData();
	    if (!$arr)
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Bus type not found.'));
	    }
	    if ($pjBusTypeModel->reset()->set('id', $id)->erase()->getAffectedRows() == 1)
	    {
	        pjMultiLangModel::factory()->where('model', 'pjBusType')->where('foreign_id', $id)->eraseAll();
	        pjSeatModel::factory()->where('bus_type_id', $id)->eraseAll();
	        if(!empty($arr['seats_map']) && is_file(PJ_INSTALL_PATH . $arr['map_path']))
			{
				@unlink(PJ_INSTALL_PATH . $arr['seats_map']);
			}
			self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Bus type has been deleted'));
	    }else{
	        self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => 'Bus type has not been deleted.'));
	    }
		exit;
	}
	
	public function pjActionDeleteBusTypeBulk()
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
		$arr = pjBusTypeModel::factory()->whereIn('id', $record)->findAll()->getData();
		pjBusTypeModel::factory()->reset()->whereIn('id', $record)->eraseAll();
		pjMultiLangModel::factory()->where('model', 'pjBusType')->whereIn('foreign_id', $record)->eraseAll();
		pjSeatModel::factory()->whereIn('bus_type_id', $record)->eraseAll();
		
		foreach ($arr as $val) {
			if(!empty($val['seats_map']) && is_file(PJ_INSTALL_PATH . $val['seats_map']))
			{
				@unlink(PJ_INSTALL_PATH . $val['seats_map']);
			}
		}
		
		self::jsonResponse(array('status' => 'OK'));
	}
	
	public function pjActionGetBusType()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjBusTypeModel = pjBusTypeModel::factory()
							->join('pjMultiLang', "t2.model='pjBusType' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer');
			if ($this->_get->toString('status'))
			{
			    $status = $this->_get->toString('status');
			    if(in_array($status, array('T', 'F')))
			    {
			        $pjBusTypeModel->where('t1.status', $status);
			    }
			}
			if ($q = $this->_get->toString('q'))
			{
			    $pjBusTypeModel->where("(t2.content LIKE '%$q%')");
			}
			
			$column = 'name';
			$direction = 'ASC';
			if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
			{
			    $column = $this->_get->toString('column');
			    $direction = strtoupper($this->_get->toString('direction'));
			}

			$total = $pjBusTypeModel->findCount()->getData();
			$rowCount = $this->_get->toInt('rowCount') ?: 10;
			$pages = ceil($total / $rowCount);
			$page = $this->_get->toInt('page') ?: 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjBusTypeModel
				->select("t1.id, t1.seats_map, t1.seats_count, t1.status, t2.content as name")
				->orderBy("$column $direction")
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
	    
	    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
		$this->appendJs('pjAdminBusTypes.js');
	}
	
	public function pjActionSaveBusType()
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
		$pjBusTypeModel = pjBusTypeModel::factory();
		$arr = $pjBusTypeModel->find($this->_get->toInt('id'))->getData();
		if (!$arr)
		{
		    self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Bus type not found.'));
		}
		if (!in_array($this->_post->toString('column'), $pjBusTypeModel->getI18n()))
		{
		    $pjBusTypeModel->reset()->where('id', $this->_get->toInt('id'))->limit(1)->modifyAll(array($this->_post->toString('column') => $this->_post->toString('value')));
		} else {
		    pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($this->_post->toString('column') => $this->_post->toString('value'))), $this->_get->toInt('id'), 'pjBusType', 'data');
		}
		self::jsonResponse(array('status' => 'OK', 'code' => 201, 'text' => 'Bus type has been updated.'));
	}
	
	public function pjActionUpdate()
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
			pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminBusTypes&action=pjActionIndex&err=ABT06");
		}	
		if (self::isPost() && $this->_post->check('bus_type_update'))
		{
			$pjBusTypeModel = pjBusTypeModel::factory();
			$pjSeatModel = pjSeatModel::factory();
			
			$id = $this->_post->toInt('id');
			$arr = $pjBusTypeModel->find($id)->getData();
			if (empty($arr))
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBusTypes&action=pjActionIndex&err=ABT08");
			}			
			$data = array();
			$data['use_map'] = $this->_post->check('use_map') ? 'T' : 'F';
			$post = $this->_post->raw();			
			if($this->_post->check('use_map'))
			{
				if (isset($_FILES['seats_map']))
				{
					if($_FILES['seats_map']['error'] == 0)
					{
						if(getimagesize($_FILES['seats_map']["tmp_name"]) != false)
						{
							if (is_writable('app/web/upload/bus_types'))
							{
								$Image = new pjImage();
								if ($Image->getErrorCode() !== 200)
								{
									$Image->setAllowedTypes(array('image/png', 'image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg'));
									if ($Image->load($_FILES['seats_map']))
									{
										$resp = $Image->isConvertPossible();
										if ($resp['status'] === true)
										{
											$hash = md5(uniqid(rand(), true));
											$image_path = PJ_UPLOAD_PATH . 'bus_types/' . $id . '_' . $hash . '.' . $Image->getExtension();
											
											$Image->loadImage();
											$Image->saveImage($image_path);
											$data['seats_map'] = $image_path;
										}
									}
								}
											
								$pjSeatModel->where('bus_type_id', $id)->eraseAll();
								if(isset($post['seats_count']))
								{
									unset($post['seats_count']);
								}
							}else{
								pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBusTypes&action=pjActionUpdate&id=".$id."&err=ABT11");
							}
						}else{
							pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBusTypes&action=pjActionUpdate&id=".$id."&err=ABT12");
						}
					}else if($_FILES['seats_map']['error'] != 4){
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBusTypes&action=pjActionUpdate&id=".$id."&err=ABT10");
					}
				}
			}else{
				@unlink($arr['seats_map']);
			    $data['seats_map'] = ':NULL';
			}
			
			if (isset($post['seats']))
			{
				$seat1_arr = array_values($pjSeatModel->where('bus_type_id', $post['id'])->findAll()->getDataPair('id', 'id'));
				$seat2_arr = array();
				$sdata = array();
				foreach ($post['seats'] as $seat)
				{
					list($sid, $sdata['width'], $sdata['height'], $sdata['left'], $sdata['top'], $sdata['name']) = explode("|", $seat);
					$seat2_arr[] = $sid;
					$sdata['bus_type_id'] = $post['id'];
					$pjSeatModel->reset()->where('id', $sid)->limit(1)->modifyAll($sdata);
				}
				$diff = array_diff($seat1_arr, $seat2_arr);
				if (count($diff) > 0)
				{
					$pjSeatModel->reset()->whereIn('id', $diff)->eraseAll();
				}
			}
			if (isset($post['seats_new']))
			{
				$sdata = array();
				foreach ($post['seats_new'] as $seat)
				{
					list(, $sdata['width'], $sdata['height'], $sdata['left'], $sdata['top'], $sdata['name']) = explode("|", $seat);
					$sdata['bus_type_id'] = $post['id'];
					$pjSeatModel->reset()->setAttributes($sdata)->insert();
				}
			}
			
			if(!isset($post['seats']) && !isset($post['seats_new']) && !isset($post['use_map']))
			{
				$cnt_seats = $pjSeatModel->reset()->where('bus_type_id', $post['id'])->findCount()->getData();
				if($post['seats_count'] > $cnt_seats)
				{
					for($i = $cnt_seats + 1; $i <= $post['seats_count']; $i++)
					{
						$sdata = array();
						$sdata['bus_type_id'] = $post['id'];
						$sdata['name'] = $i;
						$pjSeatModel->reset()->setAttributes($sdata)->insert();
					}
				}else if($post['seats_count'] < $cnt_seats){
					$pjSeatModel->where('bus_type_id', $post['id'])->where("(name > ".$post['seats_count']." AND name <= $cnt_seats)")->eraseAll();
				}
			}
			
			$cnt_seats = $pjSeatModel->reset()->where('bus_type_id', $post['id'])->findCount()->getData();
			if($cnt_seats > 0)
			{
				$data['seats_count'] = $cnt_seats;
			}
			
			$pjBusTypeModel->reset()->where('id', $post['id'])->limit(1)->modifyAll(array_merge($post, $data));
			
			if (isset($post['i18n']))
			{
				pjMultiLangModel::factory()->updateMultiLang($post['i18n'], $post['id'], 'pjBusType', 'data');
			}						
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBusTypes&action=pjActionUpdate&id=".$post['id']."&err=ABT01");			
		}
		
		if (self::isGet() && $this->_get->toInt('id'))
    	{
    		$this->setLocalesData();	
    		
			$pjMultiLangModel = pjMultiLangModel::factory();			
			$arr = pjBusTypeModel::factory()->find($this->_get->toInt('id'))->getData();			
			if (count($arr) === 0)
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBusTypes&action=pjActionIndex&err=ABT08");
			}
			$arr['i18n'] = $pjMultiLangModel->getMultiLang($arr['id'], 'pjBusType');
			$this->set('arr', $arr);
			$this->set('seat_arr', pjSeatModel::factory()->where('bus_type_id', $arr['id'])->orderBy("t1.id ASC")->findAll()->getData());
			
			$this->appendJs('jquery-ui.min.js',  PJ_THIRD_PARTY_PATH . 'jquery_ui/');
			$this->appendCss('jquery-ui.min.css', PJ_THIRD_PARTY_PATH . 'jquery_ui/');			
			$this->appendCss('jasny-bootstrap.min.css', PJ_THIRD_PARTY_PATH . 'jasny/');
	        $this->appendJs('jasny-bootstrap.min.js',  PJ_THIRD_PARTY_PATH . 'jasny/');
			$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminBusTypes.js');
    	}
	}
	
	public function pjActionDeleteMap()
	{
		$this->setAjax(true);
	
		$id = $this->_post->toInt('id');
		$pjBusTypeModel = pjBusTypeModel::factory();
		$arr = $pjBusTypeModel->find($id)->getData(); 
		
		if(!empty($arr))
		{
			$map_path = $arr['seats_map'];
			if (file_exists(PJ_INSTALL_PATH . $map_path)) {
				@unlink(PJ_INSTALL_PATH . $map_path);
			}
			$data = array();
			$data['seats_map'] = ':NULL';
			$pjBusTypeModel->reset()->where(array('id' => $id))->limit(1)->modifyAll($data);
			pjSeatModel::factory()->where('bus_type_id', $id)->eraseAll();
			
			$this->set('code', 200);
		}else{
			$this->set('code', 100);
		}
	}
}
?>