<?php

/**
 * @version       $Id: backend/controllers/skebby.php 2013-06-26 00:00:00Z matteo $
 * @package       GiBi RTBCourses
 * @author        GiBiLogic
 * @authorUrl     http://www.gibilogic.com
 * @authorEmail   info@gibilogic.com
 * @copyright     Copyright (C) 2013 GiBiLogic. All rights reserved.
 * @license       GNU/GPLv2
 * @description   Skebby backend controller
 */

defined('_JEXEC') or die();
jimport('joomla.application.component.controller');
jimport('joomla.application.component.help');
jimport('gibiskebby.gibiskebby');

/**
 * SkebbyControllerSkebby class.
 */
class SkebbyControllerSkebby extends JController
{
	/**
	 * Controller's view.
	 *
	 * @var JView
	 */
	private $view;

	/**
	 * Class constructor.
	 *
	 * @param array $config
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->params =& JComponentHelper::getParams('com_skebby');
		$this->view = $this->getView('skebby', 'html', 'SkebbyView');
		$this->model =& $this->getModel('Virtuemart', 'SkebbyModel');
	}

	/**
	 * Index task.
	 */
	public function index()
	{
		$isAccountValid = $this->params->get('params.skebby_username') && $this->params->get('params.skebby_password');
		$this->view->displayIndex($isAccountValid);
	}

	/**
	 * Send task.
	 */
	public function send()
	{
		$data = JRequest::getVar('jform', array(), 'POST');

		$this->view->displaySend($data, $this->sendSms($data));
	}

	/**
	 * Cron task.
	 */
	public function cron()
	{
		$sentCount = $this->checkOldPendingOrders() + $this->checkOldSentOrders() + $this->checkBirthdays();

		$this->setRedirect('index.php?option=com_skebby', str_replace('#COUNT#', $sentCount, JText::_('COM_SKEBBY_SCHEDULED_SMS_DONE')));
	}

	/**
	 * Checks for old pending orders, sending an SMS notification to the users.
	 * 
	 * @return integer
	 */
	private function checkOldPendingOrders()
	{
		$sentCount = 0;

		$orders = $this->model->findPendingOrders();
		foreach ($orders as $order) {
			if (!$this->model->getAllowSmsByUser($order->user_id, $this->params->get('params.vm_allowsms_field'))) {
				continue;
			}

			$phone = $this->model->getPhoneByUser($order->user_id, $this->params->get('params.vm_phone_field'));
			if (!$phone) {
				continue;
			}

			if ($this->sendSms(array(
				'numbers' => $phone,
				'message' => str_replace('#ORDER#', $order->order_number, JText::_('COM_SKEBBY_SMS_ORDER_OLD_PENDING'))
			))) {
				$sentCount++;
			}

		}

		return $sentCount;
	}

	/**
	 * Checks for old sent orders, sending an SMS notification to the users.
	 *
	 * @return integer
	 */
	private function checkOldSentOrders()
	{
		$sentCount = 0;

		$orders = $this->model->findSentOrders();
		foreach ($orders as $order) {
			if (!$this->model->getAllowSmsByUser($order->user_id, $this->params->get('params.vm_allowsms_field'))) {
				continue;
			}

			$phone = $this->model->getPhoneByUser($order->user_id, $this->params->get('params.vm_phone_field'));
			if (!$phone) {
				continue;
			}

			if ($this->sendSms(array(
				'numbers' => $phone,
				'message' => str_replace('#ORDER#', $order->order_number, JText::_('COM_SKEBBY_SMS_ORDER_OLD_SENT'))
			))) {
				$sentCount++;
			}
		}

		return $sentCount;
	}

	/**
	 * Checks for today birthdays, sending an SMS notification to the users.
	 *
	 * @return integer
	 */
	private function checkBirthdays()
	{
		$sentCount = 0;

		$usersIds = $this->model->findTodayBirthdays($this->params->get('params.vm_birthday_field', 'vm_datanascita'));
		foreach ($usersIds as $userId) {
			if (!$this->model->getAllowSmsByUser($userId, $this->params->get('params.vm_allowsms_field'))) {
				continue;
			}

			$phone = $this->model->getPhoneByUser($userId, $this->params->get('params.vm_phone_field'));
			if (!$phone) {
				continue;
			}

			if ($this->sendSms(array(
				'numbers' => $phone,
				'message' => JText::_('COM_SKEBBY_SMS_BIRTHDAY')
			))) {
				$sentCount++;
			}
		}

		return $sentCount;
	}

	/**
	 * Sends an SMS by using the GibiSkebby library.
	 * 
	 * @param array $data
	 * @return mixed
	 */
	private function sendSms($data)
	{
		if (!isset($data['numbers'], $data['message'])) {
			return false;
		}

		if (!$data['numbers'] || !$data['message']) {
			return false;
		}

		$skebby = new GibiSkebby(
			$this->params->get('params.skebby_username'),
			$this->params->get('params.skebby_password'),
			$this->params->get('params.skebby_number', null),
			$this->params->get('params.skebby_name', null)
		);

		if (isset($data['long'])) {
			$skebby->setAllowLongSms($data['long'] == 1);
		}

		if (isset($data['extended'])) {
			$skebby->setUseExtendedAlphabet($data['extended'] == 1);
		}

		$isTestMode = (isset($data['istest']) && $data['istest'] == 1) || ($this->params->get('params.skebby_debug', 0) == 1);
		$skebby->setTestMode($isTestMode);

		return $skebby->sendSms(
			explode(',', preg_replace('[^0-9\,]', '', $data['numbers'])),
			$data['message'],
			$data['method']
		);
	}
}
