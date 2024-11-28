<?php
if (isset($tpl['status']) && $tpl['status'] == 'IP_BLOCKED') {
	?>
	<h4 class="text-danger text-center"><?php __('front_ip_address_blocked');?></h4>
	<?php 
} else {
	$front_message = __('front_booking_statuses', true);
	if (isset($tpl['arr']['payment_method']))
	{
		if($tpl['arr']['deposit'] > 0)
		{
			if(isset($tpl['params']['plugin']) && !empty($tpl['params']['plugin']))
			{
				$payment_messages = __('payment_plugin_messages');
				?>
				<div>
					<p class="text-success text-center bsSystemMessage">
						<?php echo isset($payment_messages[$tpl['arr']['payment_method']]) ? $payment_messages[$tpl['arr']['payment_method']]: $front_message[8]; ?><br/>
						<?php
						if (pjObject::getPlugin($tpl['params']['plugin']) !== NULL)
						{
							$controller->requestAction(array('controller' => $tpl['params']['plugin'], 'action' => 'pjActionForm', 'params' => $tpl['params']));
						}
						?>
					</p>
				</div>
				<?php
			}else{
				?>
				<div>
					<p class="text-success text-center bsSystemMessage">
						<?php
						switch ($tpl['arr']['payment_method'])
						{
							case 'bank':
								echo $front_message[1] . '<br/>' .  nl2br(pjSanitize::html($tpl['bank_account']));
								break;
							case 'creditcard':
							case 'cash':
							default:
								echo $front_message[1];
								break;
						}
						?>
					</p>
				</div>
				<?php
			}
		}else{
			?><p class="text-success text-center bsSystemMessage"><?php echo $front_message[1]; ?></p><?php
		}
	}else{
		?><p class="text-success text-center bsSystemMessage"><?php echo $front_message[1]; ?></p><?php
	}
	?>
	<?php
	if($tpl['get']['payment_method'] == 'bank' || $tpl['get']['payment_method'] == 'creditcard' || $tpl['get']['payment_method'] == 'cash' || $tpl['option_arr']['o_payment_disable'] == 'Yes') 
	{
		?>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12 text-center">
				<br />
				<button class="btn btn-primary bsStartOver"><?php __('front_button_start_over')?></button>
			</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
		</div>
		<?php
	} 
	?>
<?php } ?>