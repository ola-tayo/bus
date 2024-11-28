<?php
$STORE = @$_SESSION[$controller->defaultStore];
$FORM = @$_SESSION[$controller->defaultForm];
$booked_data = $STORE['booked_data'];
$index = $controller->_get->toString('index');
?>
<div class="panel panel-default pjBsMain">
	<?php
	include PJ_VIEWS_PATH . 'pjFrontEnd/elements/header.php';
	?>
	<div class="panel-body pjBsBody">
		<?php
		if($tpl['status'] == 'OK')
		{
			?>
			<div class="pjBsForm pjBsFormCheckout">
				<form id="bsCheckoutForm_<?php echo $index;?>" action="" method="post" class="bsCheckoutForm" data-toggle="validator" role="form">
					<input type="hidden" name="step_checkout" value="1" />
					
					<?php
					include PJ_VIEWS_PATH . 'pjFrontEnd/elements/booking_details.php';
					?>
					
					<div class="pjBsFormBody">
						<p class="pjBsFormTitle"><?php __('front_personal_details');?></p><!-- /.pjBsFormTitle -->

						<div class="form-horizontal">
							<?php
							if (in_array($tpl['option_arr']['o_bf_include_title'], array(2, 3)))
							{
								?>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_title'); ?> <?php if($tpl['option_arr']['o_bf_include_title'] == 3): ?><span class="pjBsAsterisk">*</span><?php endif;?>: </label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
	
									<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
										<select name="c_title" class="form-control pjBsFieldInline<?php echo ($tpl['option_arr']['o_bf_include_title'] == 3) ? ' required' : NULL; ?>"  data-msg-required="<?php __('front_required_field', false, true);?>">
											<option value="">----</option>
											<?php
											$title_arr = pjUtil::getTitles();
											$name_titles = __('personal_titles', true, false);
											foreach ($title_arr as $v)
											{
												?><option value="<?php echo $v; ?>"<?php echo isset($FORM['c_title']) && $FORM['c_title'] == $v ? ' selected="selected"' : NULL; ?>><?php echo $name_titles[$v]; ?></option><?php
											}
											?>
										</select>
	
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
									</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
								</div><!-- /.form-group -->
								<?php
							}
							if (in_array($tpl['option_arr']['o_bf_include_fname'], array(2, 3)))
							{
								?>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_fname'); ?> <?php if($tpl['option_arr']['o_bf_include_fname'] == 3): ?><span class="pjBsAsterisk">*</span><?php endif;?>: </label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
	
									<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
										<input type="text" name="c_fname" class="form-control<?php echo ($tpl['option_arr']['o_bf_include_fname'] == 3) ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_fname']) ? pjSanitize::clean($FORM['c_fname']) : null;?>" data-msg-required="<?php __('front_required_field', false, true);?>"/>
	
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
									</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
								</div><!-- /.form-group -->
								<?php
							} 
							if (in_array($tpl['option_arr']['o_bf_include_lname'], array(2, 3)))
							{
								?>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_lname'); ?> <?php if($tpl['option_arr']['o_bf_include_lname'] == 3): ?><span class="pjBsAsterisk">*</span><?php endif;?>: </label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
	
									<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
										<input type="text" name="c_lname" class="form-control<?php echo ($tpl['option_arr']['o_bf_include_lname'] == 3) ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_lname']) ? pjSanitize::clean($FORM['c_lname']) : null;?>" data-msg-required="<?php __('front_required_field', false, true);?>"/>
	
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
									</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
								</div><!-- /.form-group -->
								<?php
							}
							if (in_array($tpl['option_arr']['o_bf_include_phone'], array(2, 3)))
							{
								?>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_phone'); ?> <?php if($tpl['option_arr']['o_bf_include_phone'] == 3): ?><span class="pjBsAsterisk">*</span><?php endif;?>: </label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
	
									<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
										<input type="text" name="c_phone" class="form-control<?php echo ($tpl['option_arr']['o_bf_include_phone'] == 3) ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_phone']) ? pjSanitize::clean($FORM['c_phone']) : null;?>" data-msg-required="<?php __('front_required_field', false, true);?>"/>
	
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
									</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
								</div><!-- /.form-group -->
								<?php
							}
							if (in_array($tpl['option_arr']['o_bf_include_email'], array(2, 3)))
							{
								?>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_email'); ?> <?php if($tpl['option_arr']['o_bf_include_email'] == 3): ?><span class="pjBsAsterisk">*</span><?php endif;?>: </label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
	
									<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
										<input type="text" name="c_email" class="form-control email<?php echo ($tpl['option_arr']['o_bf_include_email'] == 3) ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_email']) ? pjSanitize::clean($FORM['c_email']) : null;?>" data-msg-required="<?php __('front_required_field', false, true);?>"/>
	
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
									</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
								</div><!-- /.form-group -->
								<?php
							}
							if (in_array($tpl['option_arr']['o_bf_include_company'], array(2, 3)))
							{
								?>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_company'); ?> <?php if($tpl['option_arr']['o_bf_include_company'] == 3): ?><span class="pjBsAsterisk">*</span><?php endif;?>: </label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
	
									<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
										<input type="text" name="c_company" class="form-control<?php echo ($tpl['option_arr']['o_bf_include_company'] == 3) ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_company']) ? pjSanitize::clean($FORM['c_company']) : null;?>" data-msg-required="<?php __('front_required_field', false, true);?>"/>
	
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
									</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
								</div><!-- /.form-group -->
								<?php
							}
							if (in_array($tpl['option_arr']['o_bf_include_notes'], array(2, 3)))
							{
								?>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_notes'); ?> <?php if($tpl['option_arr']['o_bf_include_notes'] == 3): ?><span class="pjBsAsterisk">*</span><?php endif;?>: </label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
	
									<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
										<textarea name="c_notes" style="height: 100px;" class="form-control<?php echo ($tpl['option_arr']['o_bf_include_notes'] == 3) ? ' required' : NULL; ?>" data-msg-required="<?php __('front_required_field', false, true);?>"><?php echo isset($FORM['c_notes']) ? pjSanitize::clean($FORM['c_notes']) : null;?></textarea>
	
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
									</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
								</div><!-- /.form-group -->
								<?php
							}
							if (in_array($tpl['option_arr']['o_bf_include_address'], array(2, 3)))
							{
								?>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_address'); ?> <?php if($tpl['option_arr']['o_bf_include_address'] == 3): ?><span class="pjBsAsterisk">*</span><?php endif;?>: </label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
	
									<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
										<input type="text" name="c_address" class="form-control<?php echo ($tpl['option_arr']['o_bf_include_address'] == 3) ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_address']) ? pjSanitize::clean($FORM['c_address']) : null;?>" data-msg-required="<?php __('front_required_field', false, true);?>"/>
	
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
									</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
								</div><!-- /.form-group -->
								<?php
							}
							if (in_array($tpl['option_arr']['o_bf_include_city'], array(2, 3)))
							{
								?>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_city'); ?> <?php if($tpl['option_arr']['o_bf_include_city'] == 3): ?><span class="pjBsAsterisk">*</span><?php endif;?>: </label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
	
									<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
										<input type="text" name="c_city" class="form-control<?php echo ($tpl['option_arr']['o_bf_include_city'] == 3) ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_city']) ? pjSanitize::clean($FORM['c_city']) : null;?>" data-msg-required="<?php __('front_required_field', false, true);?>"/>
	
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
									</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
								</div><!-- /.form-group -->
								<?php
							}
							if (in_array($tpl['option_arr']['o_bf_include_state'], array(2, 3)))
							{
								?>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_state'); ?> <?php if($tpl['option_arr']['o_bf_include_state'] == 3): ?><span class="pjBsAsterisk">*</span><?php endif;?>: </label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
	
									<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
										<input type="text" name="c_state" class="form-control<?php echo ($tpl['option_arr']['o_bf_include_state'] == 3) ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_state']) ? pjSanitize::clean($FORM['c_state']) : null;?>" data-msg-required="<?php __('front_required_field', false, true);?>"/>
	
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
									</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
								</div><!-- /.form-group -->
								<?php
							}
							if (in_array($tpl['option_arr']['o_bf_include_zip'], array(2, 3)))
							{
								?>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_zip'); ?> <?php if($tpl['option_arr']['o_bf_include_zip'] == 3): ?><span class="pjBsAsterisk">*</span><?php endif;?>: </label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
	
									<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
										<input type="text" name="c_zip" class="form-control<?php echo ($tpl['option_arr']['o_bf_include_zip'] == 3) ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_zip']) ? pjSanitize::clean($FORM['c_zip']) : null;?>" data-msg-required="<?php __('front_required_field', false, true);?>"/>
	
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
									</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
								</div><!-- /.form-group -->
								<?php
							}
							if (in_array($tpl['option_arr']['o_bf_include_country'], array(2, 3)))
							{
								?>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_country'); ?> <?php if($tpl['option_arr']['o_bf_include_country'] == 3): ?><span class="pjBsAsterisk">*</span><?php endif;?>: </label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
	
									<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
										<select name="c_country" class="form-control<?php echo ($tpl['option_arr']['o_bf_include_country'] == 3) ? ' required' : NULL; ?>" data-msg-required="<?php __('front_required_field', false, true);?>">
											<option value="">----</option>
											<?php
											foreach ($tpl['country_arr'] as $v)
											{
												?><option value="<?php echo $v['id']; ?>"<?php echo isset($FORM['c_country']) && $FORM['c_country'] == $v['id'] ? ' selected="selected"' : NULL; ?>><?php echo $v['country_title']; ?></option><?php
											}
											?>
										</select>
	
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
									</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
								</div><!-- /.form-group -->
								<?php
							}
							if($tpl['option_arr']['o_payment_disable'] == 'No')
							{ 
								$plugins_payment_methods = pjObject::getPlugin('pjPayments') !== NULL? pjPayments::getPaymentMethods(): array();
								$haveOnline = $haveOffline = false;
								foreach ($tpl['payment_titles'] as $k => $v)
								{
									if($k == 'creditcard') continue;
									if (array_key_exists($k, $plugins_payment_methods))
									{
										if(!isset($tpl['payment_option_arr'][$k]['is_active']) || (isset($tpl['payment_option_arr']) && $tpl['payment_option_arr'][$k]['is_active'] == 0)  || $k == 'cash' || $k == 'bank')
										{
											continue;
										}
									}else if( (isset($tpl['option_arr']['o_allow_'.$k]) && $tpl['option_arr']['o_allow_'.$k] == '0') || $k == 'cash' || $k == 'bank'){
										continue;
									}
									$haveOnline = true;
									break;
								}
								foreach ($tpl['payment_titles'] as $k => $v)
								{
									if($k == 'creditcard') continue;
									if(($k == 'cash' || $k == 'bank') && isset($tpl['payment_option_arr'][$k]['is_active']) && $tpl['payment_option_arr'][$k]['is_active'] == 1)
									{
										$haveOffline = true;
										break;
									}
								}
								?>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_payment_medthod'); ?> <span class="pjBsAsterisk">*</span>: </label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
	
									<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
										<select id="bsPaymentMethod_<?php echo $index;?>" name="payment_method" class="form-control required" data-msg-required="<?php __('front_required_field', false, true);?>">
											<option value="">----</option>
											<?php 
											if ($haveOnline && $haveOffline)
											{
												?><optgroup label="<?php __('script_online_payment_gateway', false, true); ?>"><?php 
											}
											?>
											 <?php
											 foreach ($tpl['payment_titles'] as $k => $v)
											 {
												 if($k == 'creditcard') continue;
												 if (array_key_exists($k, $plugins_payment_methods))
												 {
													 if(!isset($tpl['payment_option_arr'][$k]['is_active']) || (isset($tpl['payment_option_arr']) && $tpl['payment_option_arr'][$k]['is_active'] == 0)  || $k == 'cash' || $k == 'bank')
													 {
														 continue;
													 }
												 }else if( (isset($tpl['option_arr']['o_allow_'.$k]) && $tpl['option_arr']['o_allow_'.$k] == '0') || $k == 'cash' || $k == 'bank'){
													 continue;
												 }
												 ?><option value="<?php echo $k; ?>"<?php echo isset($FORM['payment_method']) && $FORM['payment_method'] == $k ? ' selected="selected"' : NULL;?>><?php echo $v; ?></option><?php
											 }
											 ?>
											 <?php
											 if ($haveOnline && $haveOffline)
											 {
												?>
												</optgroup>
												<optgroup label="<?php __('script_offline_payment', false, true); ?>">
												<?php 
											 }
											 ?>
											 <?php
											 foreach ($tpl['payment_titles'] as $k => $v)
											 {
												 if($k == 'creditcard') continue;
												 if(($k == 'cash' || $k == 'bank') && isset($tpl['payment_option_arr'][$k]['is_active']) && $tpl['payment_option_arr'][$k]['is_active'] ==1)
												 {
													 ?><option value="<?php echo $k; ?>"<?php echo isset($FORM['payment_method']) && $FORM['payment_method'] == $k ? ' selected="selected"' : NULL;?>><?php echo $v; ?></option><?php													 
												 }
											  }
											?>
											<?php
											if ($haveOnline && $haveOffline)
											{
												?></optgroup><?php 
											}
											?>
										</select>
	
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
									</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
								</div><!-- /.form-group -->
								
								<div id="bsBankData_<?php echo $index;?>" style="display: <?php echo isset($FORM['payment_method']) && $FORM['payment_method'] == 'bank' ? 'block' : 'none'; ?>">
									<div class="form-group">
										<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">&nbsp;</label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
		
										<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
											<?php echo stripslashes(nl2br($tpl['bank_account'])); ?>
										</div>
									</div>
								</div>
								<?php
							}
							if (in_array($tpl['option_arr']['o_bf_include_captcha'], array(2, 3)))
							{ 
								?>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_captcha'); ?> <?php if($tpl['option_arr']['o_bf_include_captcha'] == 3): ?><span class="pjBsAsterisk">*</span><?php endif;?>: </label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
	
									<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
										<?php
										if($tpl['option_arr']['o_captcha_type_front'] == 'system')
										{
		    								?>
											<div class="pjBsCaptcha">
												<input type="text" id="pjBrCaptchaInput" name="captcha" class="form-control<?php echo ($tpl['option_arr']['o_bf_include_captcha'] == 3) ? ' required' : NULL; ?>" maxlength="6" autocomplete="off" data-msg-required="<?php __('front_required_field', false, true);?>"/>
												<img id="pjBrCaptchaImage" src="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjFrontEnd&action=pjActionCaptcha&rand=<?php echo rand(1000, 999999); ?>&session_id=<?php echo $controller->_get->check('session_id') ? pjObject::escapeString($controller->_get->toString('session_id')) : NULL;?>" alt="Captcha" style="border: solid 1px #E0E3E8;"/>
											</div><!-- /.pjBsCaptcha -->
		
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
										<?php 
										} else {
											?>
										    <div id="g-recaptcha_<?php echo $controller->_get->toString('index');?>" class="g-recaptcha" data-sitekey="<?php echo $tpl['option_arr']['o_captcha_site_key_front'] ?>"></div>
										    <input type="hidden" id="recaptcha" name="recaptcha" class="recaptcha<?php echo ($tpl['option_arr']['o_bf_include_captcha'] == 3) ? ' required' : NULL; ?>" autocomplete="off" data-msg-remote="<?php __('front_incorrect_captcha');?>"/>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
											<?php 
										}
										?>
									</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
								</div><!-- /.form-group -->
								<?php
							} 
							?>
						</div><!-- /.form-horizontal -->
					</div><!-- /.pjBsFormBody -->
					
					<footer class="pjBsFormFoot">
						<div class="form-group">
							<p class="pjBsFormTitle"><?php __('front_label_terms_conditions');?></p><!-- /.pjBsFormTitle -->

							<div class="checkbox">
								<label>
									<?php
									if(!empty($tpl['terms_conditions']))
									{ 
										?>
										<input id="bsAgree_<?php echo $index?>" name="agreement" type="checkbox" checked="checked" />&nbsp;<?php __('front_label_agree');?>&nbsp;<a href="#" data-pj-toggle="modal" data-pj-target="#pjBsModalTerms"><?php __('front_label_terms_conditions');?></a>
										<?php
									}else{
										?>
										<input id="bsAgree_<?php echo $index?>" name="agreement" type="checkbox" checked="checked" />&nbsp;<?php __('front_label_agree');?>&nbsp;<?php __('front_label_terms_conditions');?>
										<?php
									} 
									?>
								</label>
							</div><!-- /.checkbox -->

							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
						</div><!-- /.form-group -->
					
						<div class="clearfix pjBsFormMessages" style="display: none;">
							<div id="bsBookingMsg_<?php echo $index?>" class="text-success pjBrBookingMsg"></div>
						</div><!-- /.clearfix pjBsFormActions -->
					
						<div class="clearfix pjBsFormActions">
							<a href="#" id="bsBtnBack3_<?php echo $index;?>" class="btn btn-default pull-left"><?php __('front_button_back'); ?></a>
							<button type="button" id="bsBtnPreview_<?php echo $index;?>" class="btn btn-primary pull-right"><?php __('front_button_preview'); ?></button>
						</div><!-- /.clearfix pjBsFormActions -->
					</footer><!-- /.pjBsFormFoot -->
				</form>
				
				<div class="modal fade pjBsModal pjBsModalTerms" id="pjBsModalTerms" tabindex="-1" role="dialog" aria-labelledby="pjBsModalTermsLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<header class="modal-header">
								<button type="button" class="close" data-pj-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
	
								<p class="modal-title"><?php __('front_label_terms_conditions');?></p><!-- /.modal-title -->
							</header><!-- /.modal-header -->
	
							<div class="modal-body">
								<?php echo nl2br(pjSanitize::clean($tpl['terms_conditions']));?>
							</div><!-- /.modal-body -->
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /#pjBsModalTerms.modal fade pjBsModal pjBsModalTerms -->
			</div><!-- /.pjBsForm pjBsFormCheckout -->
			<?php 
		} else {
			?>
			<div>
				<?php
				$front_messages = __('front_messages', true, false);
				$system_msg = str_replace("[STAG]", "<a href='#' class='bsStartOver'>", $front_messages[5]);
				$system_msg = str_replace("[ETAG]", "</a>", $system_msg); 
				echo $system_msg; 
				?>
			</div>
			<?php
		}
		?>
	</div><!-- /.panel-body pjBsBody -->
</div>