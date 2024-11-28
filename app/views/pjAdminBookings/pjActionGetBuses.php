<div class="form-group">
	<label ><?php __('lblBus'); ?></label>
	<div>
		<select name="bus_id" id="bus_id" class="form-control select-item required" data-msg-required="<?php __('fd_field_required');?>">
			<option value="">-- <?php echo count($tpl['bus_arr']) > 0 ? __('lblChoose', true, false) : __('lblNoBusBetween', true, false); ?>--</option>
			<?php
			foreach ($tpl['bus_arr'] as $k => $v)
			{
				?><option value="<?php echo $v['id']; ?>"<?php echo isset($tpl['arr']) ? ($v['id'] == $tpl['arr']['bus_id'] ? ' selected="selected"' : null) : null; ?> data-set="<?php echo !empty($v['seats_map']) ? 'T' : 'F';?>"><?php echo $v['route']; ?>, <?php echo $v['depart_arrive']; ?></option><?php
			}
			?>
		</select>
	</div>
</div>