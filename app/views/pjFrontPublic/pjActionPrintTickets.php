<?php
if (isset($tpl['status']) && $tpl['status'] == 'IP_BLOCKED') {
	?>
	<h4 class="text-danger text-center"><?php __('front_ip_address_blocked');?></h4>
	<?php 
} else {
	if(isset($tpl['template']))
	{ 
	    foreach ($tpl['seats'] as $seat)
	    {
	        $tpl['arr']['custom_seat'] = $seat;
	        $data = $controller->getTemplate($tpl['option_arr'], $tpl['arr'], PJ_SALT, $controller->getLocaleId());
	        $template_arr = str_replace($data['search'], $data['replace'], $tpl['template']);
	        $temp = $template_arr;
	        $temp = str_replace('{QRCode}', '<img src="' . PJ_INSTALL_URL . 'app' . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . 'qrcodes' . DIRECTORY_SEPARATOR . 'qr_' . $seat['qr_code'] . '.png" />', $temp);
	        ?>
    		<table class="table" cellpadding="0" cellspacing="0">
    			<tbody>
    				<tr>
    					<td><?php echo $temp;?></td>
    				</tr>
    			</tbody>
    		</table>
		<?php
	   }
	} else{
		if(@$tpl['status'] == 'ERRO1')
		{
			__('front_booking_not_found');
		}else if(@$tpl['status'] == 'ERRO2'){
			__('front_hash_not_match');
		}else{
			__('lblPendingBookingCannotPrint');
		}
	}
}
?>