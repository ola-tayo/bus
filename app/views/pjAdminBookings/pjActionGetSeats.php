<?php
$map = $tpl['bus_type_arr']['seats_map'];
if (is_file($map))
{
	$size = getimagesize($map);
	$selected_seat_arr = $controller->_post->check('selected_seats') && $controller->_post->toString('selected_seats') != '' ? explode('|', $controller->_post->toString('selected_seats')) : array();
	?>
	<div class="alert alert-info"><?php __('lblSelectSeatsHint')?></div>
	<div class="bs-seats-legend">
		<label><span class="bs-available-seats"></span><?php __('lblAvailableSeats');?></label>
		<label><span class="bs-selected-seats"></span><?php __('lblSelectedSeats');?></label>
		<label><span class="bs-booked-seats"></span><?php __('lblBookedSeats');?></label>
	</div>
	<div id="boxMap">
		<div id="mapHolder" style="position: relative; overflow: hidden; width: <?php echo $size[0]; ?>px; height: <?php echo $size[1]; ?>px; margin: 0 auto;">
			<img id="map" src="<?php echo $map; ?>" alt="" style="margin: 0; border: none; position: absolute; top: 0; left: 0; z-index: 500" />
			<?php
			foreach ($tpl['seat_arr'] as $seat)
			{
				?><span rel="hi_<?php echo $seat['id']; ?>" class="rect empty<?php echo in_array($seat['id'], $tpl['booked_seat_arr']) ? ' bs-booked' : (in_array($seat['id'], $selected_seat_arr) ? ' bs-selected bs-available' : ' bs-available');?><?php echo $controller->_post->check('booking_update') ? (in_array($seat['id'], $tpl['seat_pair_arr']) ? ' bs-selected' : null) : null;?>" data-id="<?php echo $seat['id']; ?>" data-name="<?php echo $seat['name']; ?>" style="width: <?php echo $seat['width']; ?>px; height: <?php echo $seat['height']; ?>px; left: <?php echo $seat['left']; ?>px; top: <?php echo $seat['top']; ?>px; line-height: <?php echo $seat['height']; ?>px"><span class="bsInnerRect" data-name="hi_<?php echo $seat['id']; ?>"><?php echo stripslashes($seat['name']); ?></span></span><?php
			}
			?>
		</div>
	</div>
	<?php
}else{
	?>
	<div class="form-group">
		<label><?php __('lblSeats'); ?></label>
		<div>
			<select name="assigned_seats[]" id="assigned_seats" class="form-control select-item required" multiple="multiple" size="5" data-msg-required="<?php __('fd_field_required', false, true);?>">
				<?php
				foreach ($tpl['seat_arr'] as $seat)
				{
					if(!in_array($seat['id'], $tpl['booked_seat_arr']))
					{
						?><option value="<?php echo $seat['id']; ?>"><?php echo stripslashes($seat['name']); ?></option><?php
					}
				}
				?>
			</select>
			<?php if (pjAuth::factory('pjAdminSchedule', 'pjActionSeats')->hasAccess()) { ?>
				<a class="block" target="_blank" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionSeats&amp;bus_id=<?php echo $controller->_post->toInt('bus_id');?>&amp;date=<?php echo $controller->_post->toString('booking_date');?>"><?php __('lblViewSeatsList');?></a>
			<?php } ?>
		</div>
	</div>
	<?php
} 
?>