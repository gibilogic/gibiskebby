<?php

/**
 * @version       $Id: backend/views/skebby/view.html.php 2013-06-26 15:05:00Z matteo  $
 * @package       GiBi Skebby
 * @author        GiBiLogic
 * @authorUrl     http://www.gibilogic.com
 * @authorEmail   info@gibilogic.com
 * @copyright     Copyright (C) 2013 GiBiLogic. All rights reserved.
 * @license       GNU/GPLv2
 * @description   Skebby backend view
 */

defined('_JEXEC') or die();
jimport('joomla.application.component.view');
jimport('gibiskebby.gibiskebby');

/**
 * SkebbyViewSkebby class.
 */
class SkebbyViewSkebby extends JView
{
	/**
	 * Class constructor.
	 *
	 * @param array $config
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Displays the "index" layout.
	 */
	public function displayIndex($isAccountValid)
	{
		$this->isAccountValid = $isAccountValid;
		$this->methods = array(
			GibiSkebby::SKEBBY_SEND_SMS_METHOD_CLASSIC => 'Classic',
			GibiSkebby::SKEBBY_SEND_SMS_METHOD_CLASSIC_PLUS => 'Classic Plus',
			GibiSkebby::SKEBBY_SEND_SMS_METHOD_BASIC => 'Basic',
			GibiSkebby::SKEBBY_SEND_SMS_METHOD_ZERO => '0cent'
		);

		JToolbarHelper::title(JText::_('COM_SKEBBY'));

		if ($isAccountValid) {
			JToolBarHelper::apply('send', JText::_('COM_SKEBBY_SEND_SMS'));
			JToolBarHelper::divider();
		}

		JToolBarHelper::custom('cron', 'send', '', JText::_('COM_SKEBBY_SCHEDULED_SMS'), false, false);
		JToolBarHelper::preferences('com_skebby', 640, 640);

    $this->setLayout('index');
		$this->display();
	}

	/**
	 * Displays the "send" layout.
	 */
	public function displaySend($data, $result)
	{
		$this->data = $data;
		$this->result = $result;

		JToolbarHelper::title(JText::_('COM_SKEBBY'));
		JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_skebby');

    $this->setLayout('send');
		$this->display();
	}
}
