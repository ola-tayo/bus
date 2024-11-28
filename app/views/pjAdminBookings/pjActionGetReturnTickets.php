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
		if($v['price'] != '')
		{
			if($tpl['arr']['set_seats_count'] == 'T')
			{
				$seats_avail = $v['seats_count'] - $v['cnt_booked'];
			}
			$price = $v['price'] - ($v['price'] * $v['discount'] / 100);
			?>
			<div class="form-group">
				<label><?php echo pjSanitize::html($v['ticket']); ?></label>
				<div class="input-group">
					<select name="return_ticket_cnt_<?php echo $v['ticket_id'];?>" class="form-control bs-return-ticket" data-price="<?php echo $price;?>">
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
	<input type="hidden" id="bs_return_number_of_seats" name="bs_return_number_of_seats" value="<?php echo $seats_avail; ?>"/>
	<?php
}
$ticket = ob_get_contents();
ob_end_clean();
pjAppController::jsonResponse(compact('ticket'));
?>