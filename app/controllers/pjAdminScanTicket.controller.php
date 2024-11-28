<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminScanTicket extends pjAppController
{
	public function pjActionIndex()
	{
		$this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
		
	    $this->appendJs('html5-qrcode.min.js');
	    $this->appendJs('pjAdminScanTicket.js');
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
}
?>