<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAppController extends pjBaseAppController
{
	public $models = array();
  
	public function pjActionCheckInstall()
	{
		$this->setLayout('pjActionEmpty');
		
		$result = array('status' => 'OK', 'code' => 200, 'text' => 'Operation succeeded', 'info' => array());
		$folders = array(
						'app/web/upload',
						'app/web/upload/bus_types',
						'app/web/upload/locale'
					);
		foreach ($folders as $dir)
		{
			if (!is_writable($dir))
			{
				$result['status'] = 'ERR';
				$result['code'] = 101;
				$result['text'] = 'Permission requirement';
				$result['info'][] = sprintf('Folder \'<span class="bold">%1$s</span>\' is not writable. You need to set write permissions (chmod 777) to directory located at \'<span class="bold">%1$s</span>\'', $dir);
			}
		}
		
		return $result;
	}
	
	/**
     * Sets some predefined role permissions and grants full permissions to Admin.
     */
    public function pjActionAfterInstall()
    {
        $this->setLayout('pjActionEmpty');

        $result = array('status' => 'OK', 'code' => 200, 'text' => 'Operation succeeded', 'info' => array());

        $pjAuthRolePermissionModel = pjAuthRolePermissionModel::factory();
        $pjAuthUserPermissionModel = pjAuthUserPermissionModel::factory();

        $permissions = pjAuthPermissionModel::factory()->findAll()->getDataPair('key', 'id');

        $roles = array(1 => 'admin', 2 => 'editor', 3 => 'driver');
        foreach ($roles as $role_id => $role)
        {
            if (isset($GLOBALS['CONFIG'], $GLOBALS['CONFIG']["role_permissions_{$role}"])
                && is_array($GLOBALS['CONFIG']["role_permissions_{$role}"])
                && !empty($GLOBALS['CONFIG']["role_permissions_{$role}"]))
            {
                $pjAuthRolePermissionModel->reset()->where('role_id', $role_id)->eraseAll();

                foreach ($GLOBALS['CONFIG']["role_permissions_{$role}"] as $role_permission)
                {
                    if($role_permission == '*')
                    {
                        // Grant full permissions for the role
                        foreach($permissions as $key => $permission_id)
                        {
                            $pjAuthRolePermissionModel->setAttributes(compact('role_id', 'permission_id'))->insert();
                        }
                        break;
                    }
                    else
                    {
                        $hasAsterix = strpos($role_permission, '*') !== false;
                        if($hasAsterix)
                        {
                            $role_permission = str_replace('*', '', $role_permission);
                        }

                        foreach($permissions as $key => $permission_id)
                        {
                            if($role_permission == $key || ($hasAsterix && strpos($key, $role_permission) !== false))
                            {
                                $pjAuthRolePermissionModel->setAttributes(compact('role_id', 'permission_id'))->insert();
                            }
                        }
                    }
                }
            }
        }
        
		// Grant full permissions to Admin
        $user_id = 1; // Admin ID
        $pjAuthUserPermissionModel->reset()->where('user_id', $user_id)->eraseAll();
        foreach($permissions as $key => $permission_id)
        {
            $pjAuthUserPermissionModel->setAttributes(compact('user_id', 'permission_id'))->insert();
        }

        return $result;
    }
    
	public function isEditor()
    {
    	return $this->getRoleId() == 2;
    }
    
    public function getForeignId()
    {
    	return 1;
    }
    
	public function beforeFilter()
    {
    	parent::beforeFilter();

        if(!in_array($this->_get->toString('controller'), array('pjFront')))
        {
            $this->appendJs('pjAdminCore.js');
            $this->appendCss('admin.css');
        }
        return true;
    }

    public static function jsonDecode($str)
	{
		$Services_JSON = new pjServices_JSON();
		return $Services_JSON->decode($str);
	}
	
	public static function jsonEncode($arr)
	{
		$Services_JSON = new pjServices_JSON();
		return $Services_JSON->encode($arr);
	}
	
	public static function jsonResponse($arr)
	{
		header("Content-Type: application/json; charset=utf-8");
		echo pjAppController::jsonEncode($arr);
		exit;
	}

	public function getLocaleId()
	{
		return isset($_SESSION[$this->defaultLocale]) && (int) $_SESSION[$this->defaultLocale] > 0 ? (int) $_SESSION[$this->defaultLocale] : false;
	}
	
	public function setLocaleId($locale_id)
	{
		$_SESSION[$this->defaultLocale] = (int) $locale_id;
	}
	
	public function friendlyURL($str, $divider='-')
	{
		$str = mb_strtolower($str, mb_detect_encoding($str));
		$str = trim($str);
		$str = preg_replace('/[_|\s]+/', $divider, $str);
		$str = preg_replace('/\x{00C5}/u', 'AA', $str);
		$str = preg_replace('/\x{00C6}/u', 'AE', $str);
		$str = preg_replace('/\x{00D8}/u', 'OE', $str);
		$str = preg_replace('/\x{00E5}/u', 'aa', $str);
		$str = preg_replace('/\x{00E6}/u', 'ae', $str);
		$str = preg_replace('/\x{00F8}/u', 'oe', $str);
		$str = preg_replace('/[^a-z\x{0400}-\x{04FF}0-9-]+/u', '', $str);
		$str = preg_replace('/[-]+/', $divider, $str);
		$str = preg_replace('/^-+|-+$/', '', $str);
		return $str;
	}
	
static public function getFromEmail()
	{
		$arr = pjAuthUserModel::factory()
			->findAll()
			->orderBy("t1.id ASC")
			->limit(1)
			->getData();
		return !empty($arr) ? $arr[0]['email'] : null;
	}
	
	static public function getAdminEmail()
	{
		$arr = pjAuthUserModel::factory()
				->where('t1.role_id', '1')
				->where('t1.status', 'T')
				->findAll()
				->getDataPair('id', 'email');
		return $arr;
	}
	
	static public function getAdminPhone()
	{
		$arr = pjAuthUserModel::factory()
				->where('t1.role_id', '1')
				->where('t1.status', 'T')
				->findAll()
				->getDataPair('id', 'phone');
		return $arr;
	}
	
	public function pjActionGenerateInvoice($arr)
	{
		$map = array(
				'confirmed' => 'paid',
				'cancelled' => 'cancelled',
				'pending' => 'not_paid'
		);

		$last_id = 1;
		$invoice_arr = pjInvoiceModel::factory()
			->limit(1)
			->orderBy("id DESC")
			->findAll()
			->getData();
		if(!empty($invoice_arr))
		{
			$last_id = $invoice_arr[0]['id'] + 1;
		}
		
		$response = $this->requestAction(
				array(
					'controller' => 'pjInvoice',
					'action' => 'pjActionCreate',
					'params' => array(
					'key' => md5($this->option_arr['private_key'] . PJ_SALT),
					// -------------------------------------------------
					'uuid' => $last_id,
					'order_id' => $arr['uuid'],
					'foreign_id' => 1,
					'issue_date' => ':CURDATE()',
					'due_date' => ':CURDATE()',
					'created' => ':NOW()',
					// 'modified' => ':NULL',
					'status' => @$map[$arr['status']],
					'payment_method' => $arr['payment_method'],
					'cc_type' => $arr['cc_type'],
					'cc_num' => $arr['cc_num'],
					'cc_exp_month' => $arr['cc_exp_month'],
					'cc_exp_year' => $arr['cc_exp_year'],
					'cc_code' => $arr['cc_code'],
					'subtotal' => $arr['sub_total'],
					// 'discount' => $arr['discount'],
					'tax' => $arr['tax'],
					// 'shipping' => $arr['shipping'],
					'total' => $arr['total'],
					'paid_deposit' => $arr['deposit'],
					'amount_due' => $arr['total'] - $arr['deposit'],
					'currency' => $this->option_arr['o_currency'],
					'notes' => $arr['c_notes'],
					// 'y_logo' => $arr[''],
					// 'y_company' => $arr[''],
					// 'y_name' => $arr[''],
					// 'y_street_address' => $arr[''],
					// 'y_city' => $arr[''],
					// 'y_state' => $arr[''],
					// 'y_zip' => $arr[''],
					// 'y_phone' => $arr[''],
					// 'y_fax' => $arr[''],
					// 'y_email' => $arr[''],
					// 'y_url' => $arr[''],
					'b_billing_address' => $arr['c_address'],
					// 'b_company' => ':NULL',
					'b_name' => $arr['c_name'],
					'b_address' => $arr['c_address'],
					'b_street_address' => $arr['c_address'],
					'b_city' => $arr['c_city'],
					'b_state' => $arr['c_state'],
					'b_zip' => $arr['c_zip'],
					'b_phone' => $arr['c_phone'],
					// 'b_fax' => ':NULL',
					'b_email' => $arr['c_email'],
					// 'b_url' => $arr['url'],
					// 's_shipping_address' => (int) $arr['same_as'] === 1 ? $arr['b_address_1'] : $arr['s_address_1'],
					// 's_company' => ':NULL',
					// 's_name' => (int) $arr['same_as'] === 1 ? $arr['b_name'] : $arr['s_name'],
					// 's_address' => (int) $arr['same_as'] === 1 ? $arr['b_address_1'] : $arr['s_address_1'],
					// 's_street_address' => (int) $arr['same_as'] === 1 ? $arr['b_address_2'] : $arr['s_address_2'],
					// 's_city' => (int) $arr['same_as'] === 1 ? $arr['b_city'] : $arr['s_city'],
					// 's_state' => (int) $arr['same_as'] === 1 ? $arr['b_state'] : $arr['s_state'],
					// 's_zip' => (int) $arr['same_as'] === 1 ? $arr['b_zip'] : $arr['s_zip'],
					// 's_phone' => $arr['phone'],
					// 's_fax' => ':NULL',
					// 's_email' => $arr['email'],
					// 's_url' => $arr['url'],
					// 's_date' => ':NULL',
					// 's_terms' => ':NULL',
					// 's_is_shipped' => ':NULL',
					'items' => array(
							array(
									'name' => $arr['event_title'],
									'description' => $arr['tickets'],
									'qty' => 1,
									'unit_price' => $arr['total'],
									'amount' => $arr['total']
							)
						)
					// -------------------------------------------------
					)
				),
				array('return')
		);
	
		return $response;
	}

	public static function getData($option_arr, $booking_arr, $salt, $locale_id)
	{
		$country = NULL;
		if (isset($booking_arr['c_country']) && !empty($booking_arr['c_country']))
		{
			$country_arr = pjBaseCountryModel::factory()
						->select('t1.id, t2.content AS country_title')
						->join('pjMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$locale_id."'", 'left outer')
						->find($booking_arr['c_country'])->getData();
			if (!empty($country_arr))
			{
				$country = $country_arr['country_title'];
			}
		}
		
		$seats = '';
		$booked_seat_id_arr = pjBookingSeatModel::factory()
			->select("DISTINCT (seat_id)")
			->where('booking_id', $booking_arr['id'])
			->findAll()
			->getDataPair('seat_id', 'seat_id');
		if(!empty($booked_seat_id_arr))
		{
			$selected_seat_arr = pjSeatModel::factory()->whereIn('t1.id', $booked_seat_id_arr)->findAll()->getDataPair('id', 'name');
			$seats = join(", ", $selected_seat_arr);
		}
		
		$row = array();
		if (isset($booking_arr['tickets']))
		{
			$ticket_arr = $booking_arr['tickets'];
			foreach ($ticket_arr as $v)
			{
				if($v['qty'] > 0)
				{
					$price = $v['amount'] / $v['qty'];
					$amount = $v['amount'];
					if(isset($v['price']))
					{
						$price = $v['price'];
						$amount = $price * $v['qty'];
					}
					$row[] = stripslashes($v['title']) . ' '.$v['qty'].' x '.pjCurrency::formatPrice($price) . ' = ' . pjCurrency::formatPrice($amount);
				}
			}
		}
		$tickets = count($row) > 0 ? join("<br/>", $row) : NULL;
		
		$bus = @$booking_arr['route_title'] . ', ' . date($option_arr['o_time_format'], strtotime(@$booking_arr['departure_time'])) . ' - ' . date($option_arr['o_time_format'], strtotime(@$booking_arr['arrival_time']));
		$route = mb_strtolower(__('lblFrom', true), 'UTF-8') . ' ' . @$booking_arr['from_location'] . ' ' . mb_strtolower(__('lblTo', true), 'UTF-8') . ' ' . @$booking_arr['to_location'];
		
		$time = $booking_arr['booking_time'];
		$total = pjCurrency::formatPrice($booking_arr['total']);
		$tax = pjCurrency::formatPrice($booking_arr['tax']);
		
		$booking_date = NULL;
		if (isset($booking_arr['booking_date']) && !empty($booking_arr['booking_date']))
		{
			$tm = strtotime(@$booking_arr['booking_date']);
			$booking_date = date($option_arr['o_date_format'], $tm);
		}
		$personal_titles = __('personal_titles', true, false);
		$payment_methods = pjObject::getPlugin('pjPayments') !== NULL? pjPayments::getPaymentTitles(1, $locale_id): __('payment_methods',true);
		
		$cancelURL = PJ_INSTALL_URL . 'index.php?controller=pjFrontPublic&action=pjActionCancel&id='.@$booking_arr['id'].'&hash='.sha1(@$booking_arr['id'].@$booking_arr['created'].$salt);
		$printURL = PJ_INSTALL_URL . 'index.php?controller=pjFrontPublic&action=pjActionPrintTickets&id='.@$booking_arr['id'].'&hash='.sha1(@$booking_arr['id'].@$booking_arr['created'].$salt);
		$cancelURL = '<a href="'.$cancelURL.'">'.$cancelURL.'</a>';
		$printURL = '<a href="'.$printURL.'">'.$printURL.'</a>';
		$search = array(
			'{Title}', '{FirstName}', '{LastName}', '{Email}', '{Phone}', '{Country}',
			'{City}', '{State}', '{Zip}', '{Address}',
			'{Company}', '{CCType}', '{CCNum}', '{CCExp}','{CCSec}', '{PaymentMethod}',
			'{UniqueID}', '{Date}', '{Bus}', '{Route}', '{Seats}', '{Time}', '{TicketTypesPrice}',
			'{Total}', '{Tax}', '{Notes}',
			'{PrintTickets}',
			'{CancelURL}');
		$replace = array(
			(!empty($booking_arr['c_title']) ? $personal_titles[$booking_arr['c_title']] : null), $booking_arr['c_fname'], $booking_arr['c_lname'], $booking_arr['c_email'], $booking_arr['c_phone'], $country,
			$booking_arr['c_city'], $booking_arr['c_state'], $booking_arr['c_zip'], $booking_arr['c_address'],
			$booking_arr['c_company'], @$booking_arr['cc_type'], @$booking_arr['cc_num'], (@$booking_arr['payment_method'] == 'creditcard' ? @$booking_arr['cc_exp'] : NULL), @$booking_arr['cc_code'], @$payment_methods[$booking_arr['payment_method']],
			@$booking_arr['uuid'], $booking_date, $bus, $route, $seats, $time, $tickets,
			@$total, $tax, @$booking_arr['c_notes'],
			$printURL,
			$cancelURL);

		return compact('search', 'replace');
	}
	
	public function getTemplate($option_arr, $booking_arr, $salt, $locale_id)
	{
		$country = NULL;
		if (isset($booking_arr['c_country']) && !empty($booking_arr['c_country']))
		{
			$country_arr = pjBaseCountryModel::factory()
						->select('t1.id, t2.content AS country_title')
						->join('pjMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$locale_id."'", 'left outer')
						->find($booking_arr['c_country'])->getData();
			if (!empty($country_arr))
			{
				$country = $country_arr['country_title'];
			}
		}
		
		$seats = '';
		if (isset($booking_arr['custom_seat']) && !empty($booking_arr['custom_seat'])) {
		    $seat = pjSeatModel::factory()->reset()
		    ->find($booking_arr['custom_seat']['seat_id'])
		    ->getData();
		    $seats = @$seat['name'];
		    
		    $tickets = pjBookingTicketModel::factory()->reset()
		    ->join('pjMultiLang', "t2.model='pjTicket' AND t2.foreign_id=t1.ticket_id AND t2.field='title' AND t2.locale='".$locale_id."'", 'left outer')
		    ->join('pjTicket', "t3.id=t1.ticket_id", 'left')
		    ->select('t1.*, t2.content as title, (SELECT TP.price FROM `'.pjPriceModel::factory()->getTable().'` AS TP WHERE TP.ticket_id = t1.ticket_id AND TP.bus_id = '.$booking_arr['bus_id'].' AND TP.from_location_id = '.$booking_arr['pickup_id'].' AND TP.to_location_id= '.$booking_arr['return_id']. ' AND is_return = "F" LIMIT 1) as price')
		    ->where('booking_id', $booking_arr['id'])
		    ->where('ticket_id', $booking_arr['custom_seat']['ticket_id'])
		    ->findAll()
		    ->getData();
		    $booking_arr['tickets'] = $tickets;
		    
		    $row = array();
		    if (isset($booking_arr['tickets']))
		    {
		        $ticket_arr = $booking_arr['tickets'];
		        foreach ($ticket_arr as $v)
		        {
		            if($v['qty'] > 0)
		            {
		                $price = $v['amount']/$v['qty'];
		                $row[] = stripslashes($v['title']) . ' 1 x '.pjCurrency::formatPrice($price);
		            }
		        }
		    }
		    $ticket_type = count($row) > 0 ? join("<br/>", $row) : NULL;
		} else {
    		$booked_seat_id_arr = pjBookingSeatModel::factory()
    			->select("DISTINCT (seat_id)")
    			->where('booking_id', $booking_arr['id'])
    			->findAll()
    			->getDataPair('seat_id', 'seat_id');
    		if(!empty($booked_seat_id_arr))
    		{
    			$selected_seat_arr = pjSeatModel::factory()->whereIn('t1.id', $booked_seat_id_arr)->findAll()->getDataPair('id', 'name');
    			$seats = join(", ", $selected_seat_arr);
    		}
    		$row = array();
    		if (isset($booking_arr['tickets']))
    		{
    			$ticket_arr = $booking_arr['tickets'];
    			foreach ($ticket_arr as $v)
    			{
    				if($v['qty'] > 0)
    				{
    					$price = $v['amount']/$v['qty'];
    					$row[] = stripslashes($v['title']) . ' '.$v['qty'].' x '.pjCurrency::formatPrice($price);
    				}
    			}
    		}
    		$ticket_type = count($row) > 0 ? join("<br/>", $row) : NULL;
		}

		$booking_route_arr = explode("<br/>", $booking_arr['booking_route']);
		$bus = $booking_route_arr[0];
		$route = $booking_route_arr[1];
		$time = $booking_arr['booking_time'];
		$total = pjCurrency::formatPrice($booking_arr['total']);
		$tax = pjCurrency::formatPrice($booking_arr['tax']);
		
		$time_arr = explode(" - ", $time);
		
		$booking_date = NULL;
		if (isset($booking_arr['booking_date']) && !empty($booking_arr['booking_date']))
		{
			$tm = strtotime(@$booking_arr['booking_date']);
			$booking_date = date($option_arr['o_date_format'], $tm);
		}
		$personal_titles = __('personal_titles', true, false);
		
		$cancelURL = PJ_INSTALL_URL . 'index.php?controller=pjFrontEnd&action=pjActionCancel&id='.@$booking_arr['id'].'&hash='.sha1(@$booking_arr['id'].@$booking_arr['created'].$salt);
		$search = array(
			'{Title}', '{FirstName}', '{LastName}', '{Email}', '{Phone}', '{Country}',
			'{City}', '{State}', '{Zip}', '{Address}',
			'{Company}', '{CCType}', '{CCNum}', '{CCExp}','{CCSec}', '{PaymentMethod}',
			'{UniqueID}', '{Date}', '{Bus}', '{Route}', '{Seat}', '{Time}',
			'{From_Location}', '{To_Location}', '{Departure_Time}', '{Arrival_Time}',
			'{TicketType}',
			'{Total}', '{Tax}', '{Notes}',
			'{CancelURL}');
		$replace = array(
			(!empty($booking_arr['c_title']) ? $personal_titles[$booking_arr['c_title']] : null), $booking_arr['c_fname'], $booking_arr['c_lname'], $booking_arr['c_email'], $booking_arr['c_phone'], $country,
			$booking_arr['c_city'], $booking_arr['c_state'], $booking_arr['c_zip'], $booking_arr['c_address'],
			$booking_arr['c_company'], @$booking_arr['cc_type'], @$booking_arr['cc_num'], (@$booking_arr['payment_method'] == 'creditcard' ? @$booking_arr['cc_exp'] : NULL), @$booking_arr['cc_code'], @$booking_arr['payment_method'],
			@$booking_arr['uuid'], $booking_date, $bus, $route, $seats, $time,
			@$booking_arr['from_location'], @$booking_arr['to_location'], @$time_arr[0], @$time_arr[1],
			$ticket_type,
			@$total, $tax, @$booking_arr['c_notes'],
			@$cancelURL);

		return compact('search', 'replace');
	}
	
	public function getBusAvailability($bus_id, $store, $option_arr) {
		$pickup_id = $store ['pickup_id'];
		$return_id = $store ['return_id'];
		$booked_seat_arr = array ();
		$bus_type_arr = array ();
		
		$bus_arr = pjBusModel::factory ()->find ( $bus_id )->getData ();		
		if (! empty ( $bus_arr )) {
			$booking_date = pjDateTime::formatDate ($store['date'], $this->option_arr ['o_date_format'] );
			$location_id_arr = pjRouteCityModel::factory ()->getLocationIdPair ( $bus_arr ['route_id'], $pickup_id, $return_id );
			
			$booked_seat_arr = pjBookingSeatModel::factory()->select ( "DISTINCT seat_id" )->where ( "t1.booking_id IN(SELECT TB.id
										FROM `" . pjBookingModel::factory ()->getTable () . "` AS TB
										WHERE (TB.status='confirmed' OR (TB.status='pending' AND UNIX_TIMESTAMP(TB.created) >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL " . $option_arr ['o_min_hour'] . " MINUTE))))
				AND TB.bus_id = $bus_id
				AND TB.booking_date = '$booking_date')
				AND start_location_id IN(" . join ( ",", $location_id_arr ) . ")" )->index ( "FORCE KEY (`booking_id`)" )->findAll ()->getDataPair ( "seat_id", "seat_id" );
			
			$bus_type_arr = pjBusTypeModel::factory ()->find ( $bus_arr ['bus_type_id'] )->getData ();
		}
		
		return compact ( 'booked_seat_arr', 'bus_type_arr' );
	}
	public function getReturnBusAvailability($bus_id, $store, $option_arr) {
		$pickup_id = $store ['return_id'];
		$return_id = $store ['pickup_id'];
		$booked_seat_arr = array ();
		$bus_type_arr = array ();
		
		$bus_arr = pjBusModel::factory ()->find ( $bus_id )->getData ();
		if (! empty ( $bus_arr )) {
			$booking_date = pjDateTime::formatDate ($store['return_date'], $this->option_arr ['o_date_format'] );
			$location_id_arr = pjRouteCityModel::factory ()->getLocationIdPair ( $bus_arr ['route_id'], $pickup_id, $return_id );
			
			$booked_seat_arr = pjBookingSeatModel::factory()->select ( "DISTINCT seat_id" )->where ( "t1.booking_id IN(SELECT TB.id
										FROM `" . pjBookingModel::factory ()->getTable () . "` AS TB
										WHERE (TB.status='confirmed' OR (TB.status='pending' AND UNIX_TIMESTAMP(TB.created) >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL " . $option_arr ['o_min_hour'] . " MINUTE))))
				AND TB.bus_id = $bus_id
				AND TB.booking_date = '$booking_date')
				AND start_location_id IN(" . join ( ",", $location_id_arr ) . ")" )->index ( "FORCE KEY (`booking_id`)" )->findAll ()->getDataPair ( "seat_id", "seat_id" );
			
			$bus_type_arr = pjBusTypeModel::factory ()->find ( $bus_arr ['bus_type_id'] )->getData ();
		}
		
		return compact ( 'booked_seat_arr', 'bus_type_arr' );
	}
	public function isBusReady() {
		$cnt_cities = pjCityModel::factory ()->where ( 'status', 'T' )->findCount ()->getData ();
		$cnt_bus_types = pjBusTypeModel::factory ()->where ( 'status', 'T' )->findCount ()->getData ();
		$cnt_routes = pjRouteModel::factory ()->where ( 'status', 'T' )->findCount ()->getData ();
		$cnt_routes_cities = pjRouteCityModel::factory ()->findCount ()->getData ();
		$cnt_route_details = pjRouteDetailModel::factory ()->findCount ()->getData ();
		$cnt_buses = pjBusModel::factory ()->findCount ()->getData ();
		
		if ($cnt_cities > 0 && $cnt_bus_types > 0 && $cnt_routes > 0 && $cnt_routes_cities > 0 && $cnt_route_details > 0 && $cnt_buses > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function getBusList($pickup_id, $return_id, $bus_id_arr, $booking_period, $booked_data, $date, $is_return) {
		$pjBusLocationModel = pjBusLocationModel::factory ();
		$pjPriceModel = pjPriceModel::factory ();
		$pjBookingSeatModel = pjBookingSeatModel::factory ();
		$pjBookingModel = pjBookingModel::factory ();
		$pjBusTypeModel = pjBusTypeModel::factory ();
		$pjRouteCityModel = pjRouteCityModel::factory ();
		$pjSeatModel = pjSeatModel::factory ();
		$pjCityModel = pjCityModel::factory ();
		
		$pickup_location = $pjCityModel->reset ()->select ( 't1.*, t2.content as name' )->join ( 'pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='" . $this->getLocaleId () . "'", 'left outer' )->find ( $pickup_id )->getData ();
		$return_location = $pjCityModel->reset ()->select ( 't1.*, t2.content as name' )->join ( 'pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='" . $this->getLocaleId () . "'", 'left outer' )->find ( $return_id )->getData ();
		
		$ticket_columns = 0;
		$booking_date = pjDateTime::formatDate ( $date, $this->option_arr ['o_date_format'] );
		$bus_arr = array();
		if ($bus_id_arr) {
			$bus_arr = pjBusModel::factory ()->join ( 'pjMultiLang', "t2.model='pjRoute' AND t2.foreign_id=t1.route_id AND t2.field='title' AND t2.locale='" . $this->getLocaleId () . "'", 'left outer' )->join ( 'pjBusType', "t3.id=t1.bus_type_id", 'left outer' )->select ( " t1.*, t2.content AS route, t3.seats_map" )->where("(t1.route_id IN(SELECT `TR`.id FROM `".pjRouteModel::factory()->getTable()."` AS `TR` WHERE `TR`.status='T') )")->where ( "(t1.id IN(" . join ( ',', $bus_id_arr ) . "))" )->index ( "FORCE KEY (`bus_type_id`)" )->orderBy ( "route asc" )->findAll ()->getData ();
		}
		
		$location_id_arr = array ();
		foreach ( $bus_arr as $k => $bus ) {
			$locations = $pjRouteCityModel->reset ()->join ( 'pjMultiLang', "t2.model='pjCity' AND t2.foreign_id=t1.city_id AND t2.field='name' AND t2.locale='" . $this->getLocaleId () . "'", 'left outer' )->join ( 'pjBusLocation', "(t3.bus_id='" . $bus ['id'] . "' AND t3.location_id=t1.city_id", 'inner' )->select ( "t1.*, t2.content, t3.departure_time, t3.arrival_time" )->where ( 't1.route_id', $bus ['route_id'] )->orderBy ( "`order` ASC" )->findAll ()->getData ();
			
			$bus ['locations'] = $locations;
			
			if (! empty ( $bus ['start_date'] ) && ! empty ( $bus ['end_date'] )) {
				$bus ['from_to'] = pjDateTime::formatDate ( $bus ['start_date'], "Y-m-d", $this->option_arr ['o_date_format'] ) . ' - ' . pjDateTime::formatDate ( $bus ['end_date'], "Y-m-d", $this->option_arr ['o_date_format'] );
			} else {
				$bus ['from_to'] = '';
			}
			if (! empty ( $bus ['departure'] ) && ! empty ( $bus ['arrive'] )) {
				$bus ['depart_arrive'] = pjDateTime::formatTime (date('H:i:s', strtotime($bus ['departure'])), "H:i:s", $this->option_arr ['o_time_format'] ) . ' - ' . pjDateTime::formatTime (date('H:i:s', strtotime($bus ['arrive'])), "H:i:s", $this->option_arr ['o_time_format'] );
			} else {
				$bus ['depart_arrive'] = '';
			}
			$bus_arr [$k] = $bus;
			
			$bus_id = $bus ['id'];
			
			$seat_booked_arr = array ();
			$seat_avail_arr = array ();
			$departure_time = '';
			$arrival_time = '';
			$duration = '';
			
			$pickup_arr = $pjBusLocationModel->reset ()->where ( 'bus_id', $bus_id )->where ( "location_id", $pickup_id )->limit ( 1 )->findAll ()->getData ();
			$return_arr = $pjBusLocationModel->reset ()->where ( 'bus_id', $bus_id )->where ( "location_id", $return_id )->limit ( 1 )->findAll ()->getData ();
			
			if (! empty ( $pickup_arr )) {
				$departure_time = pjDateTime::formatTime (date('H:i:s', strtotime($pickup_arr [0] ['departure_time'])), 'H:i:s', $this->option_arr ['o_time_format'] );
				$booking_period [$bus_id] ['departure_time'] = $booking_date . ' ' . $pickup_arr [0] ['departure_time'];
			}
			if (! empty ( $return_arr )) {
				$arrival_time = pjDateTime::formatTime (date('H:i:s', strtotime($return_arr [0] ['arrival_time'])), 'H:i:s', $this->option_arr ['o_time_format'] );
			}
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
				
				$minutes = ($seconds / 60) % 60;
				$hours = floor ( $seconds / (60 * 60) );
				
				$hour_str = $hours . ' ' . ($hours != 1 ? strtolower ( __ ( 'front_hours', true, false ) ) : strtolower ( __ ( 'front_hour', true, false ) ));
				$minute_str = $minutes > 0 ? '<br/>' . ($minutes . ' ' . ($minutes != 1 ? strtolower ( __ ( 'front_minutes', true, false ) ) : strtolower ( __ ( 'front_minute', true, false ) ))) : '';
				$duration = $hour_str . $minute_str;
				
				if (isset ( $booking_period [$bus_id] ['departure_time'] )) {
					$booking_period [$bus_id] ['arrival_time'] = date ( 'Y-m-d H:i:s', strtotime ( $booking_period [$bus_id] ['departure_time'] ) + $seconds );
				}
			}
			
			$temp_location_id_arr = $pjRouteCityModel->getLocationIdPair ( $bus ['route_id'], $pickup_id, $return_id );
			
			if (! empty ( $booked_data )) {
				if ($is_return == 'F') {
					if ($booked_data ['bus_id'] == $bus_id && empty ( $location_id_arr )) {
						$location_id_arr = $temp_location_id_arr;
					}
				} else {
					if ($booked_data ['return_bus_id'] == $bus_id && empty ( $location_id_arr )) {
						$location_id_arr = $temp_location_id_arr;
					}
				}
			}
			
			if (! empty ( $temp_location_id_arr )) {
				$ticket_price_arr = $pjPriceModel->getTicketPrice ( $bus_id, $pickup_id, $return_id, $booked_data, $this->option_arr, $this->getLocaleId (), $is_return );
				$ticket_arr = $ticket_price_arr ['ticket_arr'];
				
				if ($bus ['set_seats_count'] == 'F') {
					$departure_time = null;
					$arrival_time = null;
					if (isset ( $booking_period [$bus_id] )) {
						if (isset ( $booking_period [$bus_id] ['departure_time'] )) {
							$departure_time = $booking_period [$bus_id] ['departure_time'];
						}
						if (isset ( $booking_period [$bus_id] ['arrival_time'] )) {
							$arrival_time = $booking_period [$bus_id] ['arrival_time'];
						}
					}
					$bus_type_arr = $pjBusTypeModel->reset ()->find ( $bus ['bus_type_id'] )->getData ();
					$seats_available = $bus_type_arr ['seats_count'];
					$seat_booked_arr = $pjBookingSeatModel->reset ()->select ( "DISTINCT t1.seat_id" )->where ( "t1.start_location_id IN(" . join ( ",", $temp_location_id_arr ) . ")
								AND t1.booking_id IN(SELECT TB.id
													FROM `" . $pjBookingModel->getTable () . "` AS TB
													WHERE (TB.status='confirmed'
															OR (TB.status='pending' AND UNIX_TIMESTAMP(TB.created) >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL " . $this->option_arr ['o_min_hour'] . " MINUTE))))
						AND TB.bus_id = $bus_id AND TB.booking_date = '$booking_date')" )->findAll ()->getDataPair ( "seat_id", "seat_id" );
					
					$cnt_booked = count ( $seat_booked_arr );
					$seats_available -= $cnt_booked;
					$bus_arr [$k] ['seats_available'] = $seats_available;
				}
				if (count ( $ticket_arr ) > $ticket_columns) {
					$ticket_columns = count ( $ticket_arr );
				}
				$bus_arr [$k] ['ticket_arr'] = $ticket_arr;
			}
			
			$seats = $pjSeatModel->reset ()->where ( 't1.bus_type_id', $bus ['bus_type_id'] )->findAll ()->getData ();
			foreach ( $seats as $v ) {
				if (! in_array ( $v ['id'], $seat_booked_arr )) {
					$seat_avail_arr [] = $v ['id'] . '#' . $v ['name'];
				}
			}
			
			$bus_arr [$k] ['seat_avail_arr'] = $seat_avail_arr;
			$bus_arr [$k] ['departure_time'] = $departure_time;
			$bus_arr [$k] ['arrival_time'] = $arrival_time;
			$bus_arr [$k] ['duration'] = $duration;
		}
		
		$bus_type_arr = array ();
		$booked_seat_arr = array ();
		$seat_arr = array ();
		$selected_seat_arr = array ();
		
		if (! empty ( $booked_data ) && ! empty ( $location_id_arr )) {
			$bus_id = ($is_return == 'F' ? $booked_data ['bus_id'] : $booked_data ['return_bus_id']);
			
			$arr = pjBusModel::factory ()->find ( $bus_id )->getData ();
			$bus_type_arr = $pjBusTypeModel->reset ()->find ( $arr ['bus_type_id'] )->getData ();
			
			$booked_seat_arr = $pjBookingSeatModel->reset ()->select ( "DISTINCT seat_id" )->where ( "t1.booking_id IN(SELECT TB.id
										FROM `" . pjBookingModel::factory ()->getTable () . "` AS TB
										WHERE (TB.status='confirmed' OR (TB.status='pending' AND UNIX_TIMESTAMP(TB.created) >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL " . $this->option_arr ['o_min_hour'] . " MINUTE))))
				AND TB.bus_id = $bus_id
				AND TB.booking_date = '$booking_date')
				AND start_location_id IN(" . join ( ",", $location_id_arr ) . ")" )->index ( "FORCE KEY (`booking_id`)" )->findAll ()->getDataPair ( "seat_id", "seat_id" );
			
			$selected_seats_str = ($is_return == 'F' ? $booked_data ['selected_seats'] : $booked_data ['return_selected_seats']);
			$seat_arr = $pjSeatModel->reset ()->where ( 'bus_type_id', $arr ['bus_type_id'] )->findAll ()->getData ();
			$selected_seat_arr = $pjSeatModel->reset ()->whereIn ( 't1.id', explode ( "|", $selected_seats_str ) )->findAll ()->getDataPair ( 'id', 'name' );
		}
		
		$from_location = $pickup_location ['name'];
		$to_location = $return_location ['name'];
		
		return compact ( 'booking_period', 'bus_arr', 'bus_type_arr', 'booked_seat_arr', 'seat_arr', 'selected_seat_arr', 'ticket_columns', 'from_location', 'to_location' );
	}	
	
	public function getDirection()
	{
		$dir = 'ltr';
		if($this->getLocaleId() != false)
		{
			$locale_arr = pjLocaleModel::factory()->find($this->getLocaleId())->getData();
			$dir = $locale_arr['dir'];
		}
		return $dir;
	}
	
	public static function getSubjectMessage($notification, $locale_id)
    {
    	$variant = $notification['variant'] == 'confirmation' ? 'confirm' : $notification['variant'];
        $field = $variant . '_tokens_' . $notification['recipient'];
        $pjMultiLangModel = pjMultiLangModel::factory();
        $lang_message = $pjMultiLangModel
        ->reset()
        ->select('t1.*')
        ->where('t1.foreign_id', $notification['id'])
        ->where('t1.model','pjNotification')
        ->where('t1.locale', $locale_id)
        ->where('t1.field', $field)
        ->limit(0, 1)
        ->findAll()
        ->getData();
        $field = $variant . '_subject_' . $notification['recipient'];
        $lang_subject = $pjMultiLangModel
        ->reset()
        ->select('t1.*')
        ->where('t1.foreign_id',  $notification['id'])
        ->where('t1.model','pjNotification')
        ->where('t1.locale', $locale_id)
        ->where('t1.field', $field)
        ->limit(0, 1)
        ->findAll()
        ->getData();
        return compact('lang_message', 'lang_subject');
    }
    
	public static function getSmsMessage($notification, $locale_id)
    {
    	$variant = $notification['variant'] == 'confirmation' ? 'confirm' : $notification['variant'];
        $field = $variant . '_sms_' . $notification['recipient'];
        $pjMultiLangModel = pjMultiLangModel::factory();
        $lang_message = $pjMultiLangModel
        ->reset()
        ->select('t1.*')
        ->where('t1.foreign_id', $notification['id'])
        ->where('t1.model','pjNotification')
        ->where('t1.locale', $locale_id)
        ->where('t1.field', $field)
        ->limit(0, 1)
        ->findAll()
        ->getData();
        return compact('lang_message');
    }
    
    public static function generateQRCode($qr_string, $qr_hash) {
        $dm = new pjDependencyManager(PJ_INSTALL_PATH, PJ_THIRD_PARTY_PATH);
        $dm->load(PJ_CONFIG_PATH . 'dependencies.php')->resolve();
        
        include_once($dm->getPath('phpqrcode') . 'qrlib.php');
        
        $qr_filename = PJ_UPLOAD_PATH . 'qrcodes/qr_'. $qr_hash .'.png';
        QRcode::png($qr_string, $qr_filename, QR_ECLEVEL_L, 3, 2);
        
        return $qr_filename;
    }
}
?>