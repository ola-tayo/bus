<?php
if(isset($tpl['template']))
{ 
    foreach ($tpl['seats'] as $seat)
    {
        $tpl['arr']['custom_seat'] = $seat;
        $data = $controller->getTemplate($tpl['option_arr'], $tpl['arr'], PJ_SALT, $controller->getLocaleId());
        $template_arr = str_replace($data['search'], $data['replace'], $tpl['template']);
        $temp = $template_arr;
        $temp = str_replace('{QRCode}', '<img src="' . PJ_INSTALL_URL . 'app/web/upload/qrcodes/qr_' . $seat['qr_code'] . '.png" />', $temp);
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
}else{
	?>
	<table class="table" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td><?php !isset($tpl['pending_booking']) ? __('lblBookingNotConfirmed') : __('lblPendingBookingCannotPrint');?></td>
			</tr>
		</tbody>
	</table>
	<?php
} 
?>