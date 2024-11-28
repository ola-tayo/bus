<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjCron extends pjAppController
{
	public function __construct()
	{
		$this->setLayout('pjActionEmpty');
	}
	
	public function pjActionIndex()
	{
		$option_arr = $this->option_arr;
		$pjBookingModel = pjBookingModel::factory();
		$arr = $pjBookingModel
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
			->where('status', 'pending')
			->where('is_sent', 'F')
			->where("(UNIX_TIMESTAMP(t1.created) < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL ".$option_arr['o_min_hour']." MINUTE)))")
			->findAll()
			->getData();
		$pjBookingTicketModel = pjBookingTicketModel::factory();
		$pjPriceModel = pjPriceModel::factory();
		$pjMultiLangModel = pjMultiLangModel::factory();
		
		$pjEmail = self::getMailer($this->option_arr);
		$notification = pjNotificationModel::factory()->where('recipient', 'client')->where('transport', 'email')->where('variant', 'pending_time_expired')->findAll()->getDataIndex(0);
		foreach($arr as $k => $v)
		{
		    if($option_arr['o_cancel_after_pending_time'] == 'Yes')
		    {
		        $pjBookingModel->reset()->where('id', $v['id'])->limit(1)->modifyAll(array('status' => 'cancelled'));
		    }
			if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
			{
				$resp = pjAppController::getSubjectMessage($notification, $this->getLocaleId());
				$lang_message = $resp['lang_message'];
				$lang_subject = $resp['lang_subject'];
				if (count($lang_message) === 1 && count($lang_subject) === 1 && $lang_subject[0]['content'] != '' && $lang_message[0]['content'] != '')
				{
					$v['tickets'] = $pjBookingTicketModel
						->reset()
						->join('pjMultiLang', "t2.model='pjTicket' AND t2.foreign_id=t1.ticket_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->join('pjTicket', "t3.id=t1.ticket_id", 'left')
						->select('t1.*, t2.content as title, (SELECT TP.price FROM `'.$pjPriceModel->getTable().'` AS TP WHERE TP.ticket_id = t1.ticket_id AND TP.bus_id = '.$v['bus_id'].' AND TP.from_location_id = '.$v['pickup_id'].' AND TP.to_location_id= '.$v['return_id']. ') as price')
						->where('booking_id', $v['id'])
						->findAll()
						->getData();
					
					$tokens = pjAppController::getData($option_arr, $v, PJ_SALT, $this->getLocaleId());
					$subject = str_replace($tokens['search'], $tokens['replace'], $lang_subject[0]['content']);
					$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);			
					$r = $pjEmail
						->setTo($v['c_email'])
						->setSubject($subject)
						->send($message);
					if ($r) {
						$pjBookingModel->reset()->where('id', $v['id'])->limit(1)->modifyAll(array('is_sent' => 'T'));
					}
				}
			}
		}
		return __('lblCronJobCompleted', true);
	}
}
?>