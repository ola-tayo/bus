<?php
ob_start();
if(isset($tpl['ticket_arr']))
{ 
	?>
	<label><?php __('lblTickets'); ?>:</label>
	<?php
	$seats_avail = $tpl['seats_available'];
	foreach($tpl['ticket_arr'] as $v)
	{
		if($tpl['arr']['set_seats_count'] == 'T')
		{
			$seats_avail = $v['seats_count'] - $v['cnt_booked'];
		}
		if($v['price'] != '')
		{
			if($tpl['booking_arr'] && (int) $tpl['booking_arr']['back_id'] > 0 && $tpl['booking_arr']['is_return'] == 'F')
			{
				$price = $v['price'] - ($v['price'] * $v['discount'] / 100);
			}else{
				$price = $v['price'];
			}
			?>
			<div class="form-group">
				<label><?php echo pjSanitize::html($v['ticket']); ?></label>
				<div class="input-group">
					<select name="ticket_cnt_<?php echo $v['ticket_id'];?>" class="form-control bs-ticket" data-price="<?php echo $price;?>">
						<?php
						for($i = 0; $i <= $seats_avail; $i++)
						{
							?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php
						}
						?>
					</select>
					<span class="input-group-addon">&nbsp;x&nbsp;<?php echo pjCurrency::formatPrice($price);?></span>
				</div>
			</div>
			<?php
		}
	} 
	?>
	<input type="hidden" id="bs_number_of_seats" name="bs_number_of_seats" value="<?php echo $seats_avail; ?>"/>
	<?php
}
$ticket = ob_get_contents();
ob_end_clean();
$departure_time = $tpl['departure_time'];
$arrival_time = $tpl['arrival_time'];
pjAppController::jsonResponse(compact('ticket', 'departure_time', 'arrival_time'));
?>