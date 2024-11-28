<div class="form-group">
	<label ><?php __('lblBus'); ?></label>
	<div>
		<select name="bus_id" id="bus_id" class="form-control select-item required" data-msg-required="<?php __('fd_field_required');?>">
			<option value="">-- <?php __('lblChoose'); ?>--</option>
		</select>
	</div>
</div>
--LIMITER--
<div class="form-group">
	<label ><?php __('lblFrom'); ?></label>
	<div>
		<select name="pickup_id" id="pickup_id" class="form-control select-item required" data-msg-required="<?php __('fd_field_required');?>">
			<option value="">-- <?php __('lblChoose'); ?>--</option>
			<?php
			foreach($tpl['from_location_arr'] as $k => $v)
			{
				?><option value="<?php echo $v['id'];?>"<?php echo $controller->_get->check('bus_id') ? ($v['id'] == $controller->_get->toInt('pickup_id') ? ' selected="selected"' : null) : null; ?>><?php echo pjSanitize::clean($v['name']);?></option><?php
			} 
			?>
		</select>
	</div>
	<span id="bsDepartureTime" class="text-info"><?php echo $controller->_get->check('bus_id') ? __('lblDepartureTime', true, false) . ': ' . pjDateTime::formatTime(date('H:i:s', strtotime($tpl['bus']['departure_time'])), "H:i:s", $tpl['option_arr']['o_time_format']) : null;?></span>
</div>
--LIMITER--
<div class="form-group">
	<label ><?php __('lblTo'); ?></label>
	<div>
		<select name="return_id" id="return_id" class="form-control select-item required" data-msg-required="<?php __('fd_field_required');?>">
			<option value="">-- <?php __('lblChoose'); ?>--</option>
			<?php
			foreach($tpl['to_location_arr'] as $k => $v)
			{
				?><option value="<?php echo $v['id'];?>"<?php echo $controller->_get->check('bus_id') ? ($v['id'] == $controller->_get->toInt('return_id') ? ' selected="selected"' : null) : null; ?>><?php echo pjSanitize::clean($v['name']);?></option><?php
			} 
			?>
		</select>
	</div>
	<span id="bsArrivalTime" class="text-info"><?php echo $controller->_get->check('bus_id') ? __('lblArrivalTime', true, false) . ': ' . pjDateTime::formatTime(date('H:i:s', strtotime($tpl['bus']['arrival_time'])), "H:i:s", $tpl['option_arr']['o_time_format']) : null;?></span>
</div>