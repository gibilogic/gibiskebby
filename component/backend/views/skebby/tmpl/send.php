<?php

/**
 * @version       $Id: backend/views/skebby/tmpl/send.php 2013-06-26 10:32:00Z matteo $
 * @package       GiBi Skebby
 * @author        GiBiLogic
 * @authorUrl     http://www.gibilogic.com
 * @authorEmail   info@gibilogic.com
 * @copyright     Copyright (C) 2013 GiBiLogic. All rights reserved.
 * @license       GNU/GPLv2
 * @description   "Send" layout for the "skebby" view
 */

defined('_JEXEC') or die();

?>
<h2><?php echo JText::_('COM_SKEBBY_SEND_REPORT') ?></h2>
<ul>
	<li>
		<strong><?php echo JText::_('COM_SKEBBY_FORM_FIELD_NUMBER') ?></strong>:&nbsp;
		<?php echo isset($this->data['numbers']) ? $this->data['numbers'] : '-' ?>
	</li>
	<li>
		<strong><?php echo JText::_('COM_SKEBBY_FORM_FIELD_MESSAGE') ?></strong>:&nbsp;
		<?php echo isset($this->data['message']) ? $this->data['message'] : '-' ?>
	</li>
	<li>
		<strong><?php echo JText::_('COM_SKEBBY_FORM_FIELD_METHOD') ?></strong>:&nbsp;
		<?php echo isset($this->data['method']) ? $this->data['method'] : '-' ?>
	</li>
	<li>
		<strong><?php echo JText::_('COM_SKEBBY_FORM_FIELD_EXTENDEDALPHABET') ?></strong>:&nbsp;
		<?php echo isset($this->data['extended']) ? ($this->data['extended'] == 1 ? JText::_('JYES') : JText::_('JNO')) : '-' ?>
	</li>
	<li>
		<strong><?php echo JText::_('COM_SKEBBY_FORM_FIELD_LONGSMS') ?></strong>:&nbsp;
		<?php echo isset($this->data['long']) ? ($this->data['long'] == 1 ? JText::_('JYES') : JText::_('JNO')) : '-' ?>
	</li>
	<li>
		<strong><?php echo JText::_('COM_SKEBBY_FORM_FIELD_ISTEST') ?></strong>:&nbsp;
		<?php echo isset($this->data['istest']) ? ($this->data['istest'] == 1 ? JText::_('JYES') : JText::_('JNO')) : '-' ?>
	</li>
</ul>

<h3><?php echo JText::_('COM_SKEBBY_RESULTS') ?></h3>
<?php if (false === $this->result): ?>
<p><?php echo JText::_('COM_SKEBBY_ERROR_DATA') ?></p>
<?php else: ?>
<ul>
	<li>
		<strong><?php echo JText::_('COM_SKEBBY_RESULTS_STATUS') ?></strong>:&nbsp;
		<?php echo $this->result->status ? ucfirst($this->result->status) : '-' ?>
	</li>
	<li>
		<strong><?php echo JText::_('COM_SKEBBY_RESULTS_CODE') ?></strong>:&nbsp;
		<?php echo $this->result->response->code ? $this->result->response->code : '-' ?>
	</li>
	<li>
		<strong><?php echo JText::_('COM_SKEBBY_RESULTS_MESSAGE') ?></strong>:&nbsp;
		<?php echo $this->result->response->message ? $this->result->response->message : '-' ?>
	</li>
</ul>
<?php endif; ?>
