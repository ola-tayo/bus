<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFront extends pjAppController
{	
	public $defaultCaptcha = 'BusReservation_Captcha';
	
	public $defaultLocale = 'BusReservation_LocaleId';
	
	public $defaultStore = 'BusReservation_Store';
	
	public $defaultForm = 'BusReservation_Form';
	
	public $defaultStep = 'BusReservation_Step';
	
	public $defaultLangMenu = 'BusReservation_LangMenu';
	
	public function __construct()
	{
		$this->setLayout('pjActionFront');
		self::allowCORS();
	}
	
	public function afterFilter()
	{		
		if (!$this->_get->check('hide') || ($this->_get->check('hide') && $this->_get->toInt('hide') !== 1) &&
            in_array($this->_get->toString('action'), array('pjActionSearch', 'pjActionSeats', 'pjActionCheckout', 'pjActionPreview', 'pjActionDone')))
        {
            $locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file, t2.title')
            ->join('pjBaseLocaleLanguage', 't2.iso=t1.language_iso', 'left')
            ->where('t2.file IS NOT NULL')
            ->orderBy('t1.sort ASC')->findAll()->getData();
            
            $this->set('locale_arr', $locale_arr);
        }
	}
	
	public function beforeFilter()
	{
		return parent::beforeFilter();
	}
	
	public function beforeRender()
	{
		if ($this->_get->check('iframe'))
        {
            $this->setLayout('pjActionIframe');
        }
	}
	
	public function pjActionGetLocale()
	{
		return isset($_SESSION[$this->defaultLocale]) && (int) $_SESSION[$this->defaultLocale] > 0 ? (int) $_SESSION[$this->defaultLocale] : FALSE;
	}
		
		
	public function isXHR()
	{
		return parent::isXHR() || isset($_SERVER['HTTP_ORIGIN']);
	}
	
	protected static function allowCORS()
	{
		$install_url = parse_url(PJ_INSTALL_URL);
        if($install_url['scheme'] == 'https'){
            header('Set-Cookie: '.session_name().'='.session_id().'; SameSite=None; Secure');
        }
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';
        header('P3P: CP="ALL DSP COR CUR ADM TAI OUR IND COM NAV INT"');
        header("Access-Control-Allow-Origin: $origin");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            exit;
        }
	}
	
	protected function _get($key)
	{
		if ($this->_is($key))
		{
			return $_SESSION[$this->defaultStore][$key];
		}
		return false;
	}
	
	protected function _is($key)
	{
		return isset($_SESSION[$this->defaultStore]) && isset($_SESSION[$this->defaultStore][$key]);
	}
	
	protected function _set($key, $value)
	{
		$_SESSION[$this->defaultStore][$key] = $value;
		return $this;
	}
	
	private function pjActionSetLocale($locale)
	{
		if ((int) $locale > 0)
		{
			$_SESSION[$this->defaultLocale] = (int) $locale;
		}
		return $this;
	}
	
	public function pjActionLocale()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if ($this->_get->check('locale_id') && $this->_get->toInt('locale_id') > 0)
			{
				$this->pjActionSetLocale($this->_get->toInt('locale_id'));
				pjAppController::loadSetFields(true);
				pjAppController::setFields($this->getLocaleId());
				
				$day_names = __('day_names', true);
				ksort($day_names, SORT_NUMERIC);
				
				$months = __('months', true);
				ksort($months, SORT_NUMERIC);
				
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Locale have been changed.', 'opts' => array(
					'day_names' => array_values($day_names),
					'month_names' => array_values($months)
				)));
			}
		}
		exit;
	}
}
?>