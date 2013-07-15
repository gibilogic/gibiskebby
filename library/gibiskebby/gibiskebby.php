<?php

/**
 * @version         $Id: gibiskebby.php 2013-06-25 00:00:00Z matteo $
 * @package         GiBi Skebby
 * @author          GiBiLogic
 * @authorUrl       http://www.gibilogic.com
 * @authorEmail     info@gibilogic.com
 * @copyright       (c) 2013 GiBiLogic Snc
 * @license         GNU/GPL v2 or later
 * @description     GiBi Skebby main class
 */

defined('_JEXEC') or die();

/**
 * GibiSkebby class.
 */
class GibiSkebby
{
	/**
	 * "Classic" method for sending SMS.
	 *
	 * @var string $SKEBBY_SEND_SMS_METHOD_CLASSIC
	 * @static
	 */
	const SKEBBY_SEND_SMS_METHOD_CLASSIC = 'send_sms_classic';

	/**
	 * "Classic Plus" method for sending SMS.
	 *
	 * @var string $SKEBBY_SEND_SMS_METHOD_CLASSIC_PLUS
	 * @static
	 */
	const SKEBBY_SEND_SMS_METHOD_CLASSIC_PLUS = 'send_sms_classic_report';

	/**
	 * "Basic" method for sending SMS.
	 *
	 * @var string $SKEBBY_SEND_SMS_METHOD_BASIC
	 * @static
	 */
	const SKEBBY_SEND_SMS_METHOD_BASIC = 'send_sms_basic';

	/**
	 * "0cent" method for sending SMS.
	 *
	 * @var string $SKEBBY_SEND_SMS_METHOD_ZERO
	 * @static
	 */
	const SKEBBY_SEND_SMS_METHOD_ZERO = 'send_sms';

	/**
	 * Url for HTTP REST APIs.
	 * 
	 * @var string $restUrl
	 */
	private $httpRestUrl = 'http://gateway.skebby.it/api/send/smseasy/advanced/rest.php';

	/**
	 * Url for HTTPS REST APIs.
	 *
	 * @var string $restUrl
	 */
	private $httpsRestUrl = 'https://gateway.skebby.it/api/send/smseasy/advanced/rest.php';

	/**
	 * Skebby account's username.
	 *
	 * @var string $username
	 */
	private $username;

	/**
	 * Skebby account's password.
	 *
	 * @var string $password
	 */
	private $password;

	/**
	 * SMS sender number.
	 *
	 * @var string $senderNumber
	 */
	private $senderNumber = null;

	/**
	 * SMS sender name.
	 *
	 * @var string $senderName
	 */
	private $senderName = null;

	/**
	 * Default international telephone number prefix.
	 *
	 * @var string $defaultPhonePrefix
	 */
	private $defaultPhonePrefix = '39';

	/**
	 * Test mode for sending SMS.
	 * 
	 * @var boolean $isTestMode
	 */
	private $isTestMode = false;

	/**
	 * HTTPS API call for sending SMS.
	 *
	 * @var boolean $useHttps
	 */
	private $useHttps = false;

	/**
	 * TRUE to use the 'UCS2' encoding scheme.
	 * 
	 * @var boolean $useExtendedAlphabet
	 */
	private $useExtendedAlphabet = false;

	/**
	 * TRUE to send long SMS.
	 *
	 * @var boolean $allowLongSms
	 */
	private $allowLongSms = false;

	/**
	 * Debug mode flag.
	 * 
	 * @var boolean $debugMode
	 */
	private $debugMode = false;

	/**
	 * Constructor.
	 *
	 * @param string $username
	 * @param string $password
	 */
	public function __construct($username, $password, $senderNumber = null, $senderName = null)
	{
		$this->username = $username;
		$this->password = $password;
		$this->senderNumber = $this->sanitizeNumber($senderNumber);
		$this->senderName = $this->sanitizeName($senderName);
	}

	/**
	 * Sends an SMS to the specified number(s).
	 *
	 * @param mixed $numbers
	 * @param string $text
	 * @param string $method
	 * @return array
	 */
	public function sendSms($numbers, $text, $method = self::SKEBBY_SEND_SMS_METHOD_CLASSIC)
	{
		$data = array(
			'method'     => ($this->isTestMode ? 'test_' : '') . $this->sanitizeMethod($method),
			'username'   => $this->username,
			'password'   => $this->password,
			'recipients' => $this->numbersToJson($numbers),
			'text'       => $this->sanitizeText($text),
			'charset'    => 'UTF-8'
		);

		if ($this->senderNumber) {
			$data['sender_number'] = $this->senderNumber;
		}

		if ($this->senderName) {
			$data['sender_string'] = $this->senderName;
		}

		if (!$this->isTestMode && $this->useExtendedAlphabet) {
			$data['encoding_scheme'] = 'UCS2';
		}

		if ($this->isTestMode) {
			JFactory::getApplication()->enqueueMessage(JText::_('COM_SKEBBY_SMS_CONTENT') . ' ' . $data['text']);
		}

		return $this->executeApiCall($this->useHttps ? $this->httpsRestUrl : $this->httpRestUrl, $data);
	}

	/**
	 * Sets the default international telephone number prefix.
	 *
	 * @param string $defaultPhonePrefix
	 * @return GiBiSkebby
	 */
	public function setDefaultPhonePrefix($defaultPhonePrefix)
	{
		$this->defaultPhonePrefix = $defaultPhonePrefix;

		return $this;
	}

	/**
	 * Sets the test mode status.
	 * 
	 * @param boolean $isTestMode
	 * @return GiBiSkebby
	 */
	public function setTestMode($isTestMode)
	{
		$this->isTestMode = $isTestMode;
		if ($this->debugMode) {
			echo "  Setting test mode: $isTestMode\n";
		}

		return $this;
	}

	/**
	 * Enables or disables the HTTPS API call.
	 *
	 * @param boolean $useHttps
	 * @return GiBiSkebby
	 */
	public function setUseHttps($useHttps)
	{
		$this->useHttps = $useHttps;
		if ($this->debugMode) {
			echo "  Setting use HTTPS: $useHttps\n";
		}

		return $this;
	}

	/**
	 * Enables or disables the extended alphabet.
	 *
	 * @param boolean $useExtendedAlphabet
	 * @return GiBiSkebby
	 */
	public function setUseExtendedAlphabet($useExtendedAlphabet)
	{
		$this->useExtendedAlphabet = $useExtendedAlphabet;
		if ($this->debugMode) {
			echo "  Setting extended alphabet: $useExtendedAlphabet\n";
		}

		return $this;
	}

	/**
	 * Enables or disables the sending of long SMS.
	 *
	 * @param boolean $allowLongSms
	 * @return GiBiSkebby
	 */
	public function setAllowLongSms($allowLongSms)
	{
		$this->allowLongSms = $allowLongSms;
		if ($this->debugMode) {
			echo "  Setting allow long SMS: $allowLongSms\n";
		}

		return $this;
	}

	/**
	 * Enables or disables the debug mode.
	 *
	 * @param boolean $debugMode
	 * @return GiBiSkebby
	 */
	public function setDebugMode($debugMode)
	{
		$this->debugMode = $debugMode;

		return $this;
	}

	/**
	 * Returns a JSON rapresentation of the numbers array.
	 * 
	 * @param mixed $numbers
	 * @return string
	 */
	private function numbersToJson($numbers)
	{
		if ($this->debugMode) {
			echo "  NumbersToJson for numbers " . (is_array($numbers) ? implode(', ', $numbers) : $numbers) . "\n";
		}

		$json = '';
		if (is_array($numbers)) {
			$numbers = array_filter(array_map(array($this, 'sanitizeNumber'), $numbers));
			$json .= '["' . implode('","', $numbers) . '"]';
		}
		else {
			$numbers = $this->sanitizeNumber($numbers);
			if ($numbers) {
				$json .= '["' . $numbers . '"]';
			}
		}

		if ($this->debugMode) {
			echo "    Resulting JSON; $json\n";
		}

		return $json;
	}

	/**
	 * Returns a cleaned phone number, or NULL in case of error or invalid number.
	 * This method removes any starting '+' and/or '00'.
	 *
	 * @param string $number
	 * @return string|null
	 */
	private function sanitizeNumber($number)
	{
		if ($this->debugMode) {
			echo "  Sanitizing number: '$number'\n";
		}

		if (!$number) {
			return null;
		}

		if (0 === strpos($number, '+')) {
			$number = substr($number, 1);
		}
		else if (0 === strpos($number, '00')) {
			$number = substr($number, 2);
		}
		else {
			$number = $this->defaultPhonePrefix . $number;
		}

		$number = preg_replace('[^0-9]', '', $number);
		if ($this->debugMode) {
			echo "    Sanitized number: '$number'\n";
		}

		return $number;
	}

	/**
	 * Returns a sanitized method for sending SMS.
	 * 
	 * @param string $method
	 * @return string
	 */
	private function sanitizeMethod($method)
	{
		if ($this->debugMode) {
			echo "  Sanitizing method: '$method'\n";
		}

		$validMethods = array(
			self::SKEBBY_SEND_SMS_METHOD_BASIC,
			self::SKEBBY_SEND_SMS_METHOD_CLASSIC,
			self::SKEBBY_SEND_SMS_METHOD_CLASSIC_PLUS,
			self::SKEBBY_SEND_SMS_METHOD_ZERO
		);

		if (!in_array($method, $validMethods)) {
			$method = self::SKEBBY_SEND_SMS_METHOD_CLASSIC;
		}

		if ($this->debugMode) {
			echo "    Sanitized method: '$method'\n";
		}

		return $method;
	}

	/**
	 * Returns a sanitized sender name.
	 * 
	 * @param string $name
	 * @return string|null
	 */
	private function sanitizeName($name)
	{
		if ($this->debugMode) {
			echo "  Sanitizing name: '$name'\n";
		}

		if (!$name) {
			return null;
		}

		$name = preg_replace('[^a-zA-Z0-9 \.]', '', $name);
		if (strlen($name) > 11) {
			$name = substr($name, 0, 11);
		}

		if ($this->debugMode) {
			echo "    Sanitized name: '$name'\n";
		}

		return $name;
	}

	/**
	 * Returns a sanitized SMS text.
	 *
	 * @param string $text
	 * @return string
	 */
	private function sanitizeText($text)
	{
		if ($this->debugMode) {
			echo "  Sanitizing text: '$text'\n";
		}

		$maxLength = !$this->isTestMode && $this->useExtendedAlphabet ? 70 : 160;
		if ($this->allowLongSms) {
			$maxLength = !$this->isTestMode && $this->useExtendedAlphabet ? 335 : 765;
		}
		$text = substr($text, 0, $maxLength);

		if (!$this->useExtendedAlphabet) {
			$total = 0;
			foreach (str_split('[\]^{|}~€') as $char) {
				$total += substr_count($text, $char);
			}

			if ($total > 0) {
				$text = substr($text, 0, -$total);
			}
		}

		if ($this->debugMode) {
			echo "    Sanitized text: '$text'\n";
		}

		return $text;
	}

	/**
	 * Executes a REST api call.
	 *
	 * @param string $url
	 * @param array $data
	 * @return mixed
	 */
	private function executeApiCall($url, $data)
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => 60,
			CURLOPT_URL            => $url,
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => $data
		));

		$result = new SimpleXMLElement(curl_exec($curl));
		curl_close($curl);

		reset($result);
		return end($result);
	}
}
