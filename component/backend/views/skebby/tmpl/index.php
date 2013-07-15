<?php

/**
 * @version       $Id: backend/views/skebby/tmpl/index.php 2013-06-26 10:32:00Z matteo $
 * @package       GiBi Skebby
 * @author        GiBiLogic
 * @authorUrl     http://www.gibilogic.com
 * @authorEmail   info@gibilogic.com
 * @copyright     Copyright (C) 2013 GiBiLogic. All rights reserved.
 * @license       GNU/GPLv2
 * @description   "Index" layout for the "skebby" view
 */

defined('_JEXEC') or die();
JHTML::_('behavior.tooltip');

?>
<h2><?php echo JText::_('COM_SKEBBY_TITLE_SEND_SMS') ?></h2>
<?php if (!$this->isAccountValid): ?>
<p><?php echo JText::_('COM_SKEBBY_ERROR_ACCOUNT') ?></p>
<?php else: ?>
<form action="index.php?option=com_skebby&amp;task=send" method="post" name="adminForm" id="adminForm" class="form-validate">
	<table class="adminform">
			<tbody>
				<tr>
					<td width="100">
						<label for="jform[numbers]" class="hasTip" title="<?php echo JText::_('COM_SKEBBY_FORM_FIELD_NUMBER') ?>::<?php echo JText::_('COM_SKEBBY_FORM_FIELD_NUMBER_HELP') ?>">
							<?php echo JText::_('COM_SKEBBY_FORM_FIELD_NUMBER') ?><span class="star">&nbsp;*</span>
						</label>
					</td>
					<td>
						<textarea name="jform[numbers]" id="jform_numbers" class="inputbox required" cols="30"></textarea>
					</td>
				</tr>
				<tr>
					<td width="100"><label for="jform[message]"><?php echo JText::_('COM_SKEBBY_FORM_FIELD_MESSAGE') ?><span class="star">&nbsp;*</span></label></td>
					<td><textarea name="jform[message]" id="jform_message" class="inputbox required" cols="30"></textarea></td>
				</tr>
				<tr>
					<td width="100"><label for="jform[method]"><?php echo JText::_('COM_SKEBBY_FORM_FIELD_METHOD') ?><span class="star">&nbsp;*</span></label></td>
					<td>
						<select name="jform[method]" id="jform_method">
							<?php foreach ($this->methods as $key => $value): ?>
							<option value="<?php echo $key ?>"><?php echo $value ?></option>
							<?php	endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td width="100"><label for="jform[long]"><?php echo JText::_('COM_SKEBBY_FORM_FIELD_LONGSMS') ?></label></td>
					<td>
						<select name="jform[long]" id="jform_long">
							<option value="0"><?php echo JText::_('JNO') ?></option>
							<option value="1"><?php echo JText::_('JYES') ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="100"><label for="jform[extended]"><?php echo JText::_('COM_SKEBBY_FORM_FIELD_EXTENDEDALPHABET') ?></label></td>
					<td>
						<select name="jform[extended]" id="jform_extended">
							<option value="0"><?php echo JText::_('JNO') ?></option>
							<option value="1"><?php echo JText::_('JYES') ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="100"><label for="jform[istest]"><?php echo JText::_('COM_SKEBBY_FORM_FIELD_ISTEST') ?></label></td>
					<td>
						<select name="jform[istest]" id="jform_istest">
							<option value="0"><?php echo JText::_('JNO') ?></option>
							<option value="1"><?php echo JText::_('JYES') ?></option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="task" value="send" />
	</fieldset>
</form>
<?php endif; ?>
