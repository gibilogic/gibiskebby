<?php

/**
 * @version       $Id: models/virtuemart.php 2013-03-06 00:00:00Z matteo $
 * @package       GiBi Skebby
 * @author        GiBiLogic
 * @authorUrl     http://www.gibilogic.com
 * @authorEmail   info@gibilogic.com
 * @copyright     Copyright (C) 2013 GiBiLogic. All rights reserved.
 * @license       GNU/GPLv2
 * @description   Virtuemart model
 */

defined('_JEXEC') or die('The way is shut!');
jimport('joomla.application.component.model');

/**
 * SkebbyModelVirtuemart class.
 *
 * @see JModel
 */
class SkebbyModelVirtuemart extends JModel
{
	/**
	 * Returns a list of pending orders older than 7 days.
	 * 
	 * @return array
	 */
	public function findPendingOrders()
	{
		$this->_db->setQuery(sprintf(
			"SELECT * FROM #__vm_orders WHERE order_status = 'P' AND cdate < %d AND cdate > %d",
			time() - (7 * 24 * 60 * 60),
			time() - (8 * 24 * 60 * 60)
		));

		$results = $this->_db->loadObjectList('order_id');
		return $results ? $results : array();
	}

	/**
	 * Returns a list of sent orders older than 10 days.
	 *
	 * @return array
	 */
	public function findSentOrders()
	{
		$this->_db->setQuery(sprintf(
			"SELECT * FROM #__vm_orders WHERE order_status = 'S' AND mdate < %d AND mdate > %d",
			time() - (10 * 24 * 60 * 60),
			time() - (11 * 24 * 60 * 60)
		));

		$results = $this->_db->loadObjectList('order_id');
		return $results ? $results : array();
	}

	/**
	 * Returns a list of user's IDs whose birthday is today.
	 * 
	 * @param string $birthdayField
	 * @return array
	 */
	public function findTodayBirthdays($birthdayField = 'vm_datanascita')
	{
		$todayDay = date('j');
		$todayMonth = date('n');
		$this->_db->setQuery("SELECT user_id FROM #__vm_user_info WHERE DAY($birthdayField) = $todayDay AND MONTH($birthdayField) = $todayMonth");

		$results = $this->_db->loadResultArray();
		return $results ? $results : array();
	}

	/**
	 * Returns the user's "Allow SMS" flag.
	 *
	 * @param type $userId
	 * @return boolean
	 */
	public function getAllowSmsByUser($userId, $allowSmsField)
	{
		$this->_db->setQuery("SELECT $allowSmsField FROM #__vm_user_info WHERE user_id = $userId");

		$result = $this->_db->loadResult();
		return ($result !== null && $result);
	}

	/**
	 * Returns the user's phone number, or NULL in case of error.
	 *
	 * @param integer $userId
	 * @param string $phoneField
	 * @return string|null
	 */
	public function getPhoneByUser($userId, $phoneField = 'phone_1')
	{
		$this->_db->setQuery("SELECT $phoneField FROM #__vm_user_info WHERE user_id = $userId");

		return $this->_db->loadResult();
	}

	/**
	 * Returns the user's birthday date, or NULL in case of error.
	 *
	 * @param integer $userId
	 * @param string $birthdayField
	 * @return string|null
	 */
	public function getBirthdayByUser($userId, $birthdayField = 'vm_datanascita')
	{
		$this->_db->setQuery("SELECT $birthdayField FROM #__vm_user_info WHERE user_id = $userId");

		return $this->_db->loadResult();
	}
}
