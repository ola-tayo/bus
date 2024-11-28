<?php 
$titles = __('error_titles', true);
$bodies = __('error_bodies', true);
$booking_statuses = __('booking_statuses', true);
?>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-10">
				<h2><?php __('infoUpdateBookingTitle');?></h2>
			</div>
		</div><!-- /.row -->

		<p class="m-b-none"><i class="fa fa-info-circle"></i><?php __('infoUpdateBookingDesc');?></p>
	</div><!-- /.col-md-12 -->
</div>

<div class="wrapper wrapper-content animated fadeInRight">
	<?php
	$error_code = $controller->_get->toString('err');
	if (!empty($error_code))
	{
		switch (true)
		{
			case in_array($error_code, array('ABB01', 'ABB03')):
				?>
				<div class="alert alert-success">
					<i class="fa fa-check m-r-xs"></i>
					<strong><?php echo @$titles[$error_code]; ?></strong>
					<?php echo @$bodies[$error_code];?>
				</div>
				<?php 
				break;
			case in_array($error_code, array('ABB02', 'ABB04', 'ABB08')):	
				?>
				<div class="alert alert-danger">
					<i class="fa fa-exclamation-triangle m-r-xs"></i>
					<strong><?php echo @$titles[$error_code]; ?></strong>
					<?php echo @$bodies[$error_code];?>
				</div>
				<?php
				break;
		}
	} 
	$time_arr = explode(" - ", $tpl['arr']['booking_time']); 
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $tpl['arr']['id']; ?>" id="frmUpdateBooking" method="post" novalidate="novalidate">
		<input type="hidden" name="booking_update" value="1" />
		<input type="hidden" id="booking_route" name="booking_route" value="<?php echo stripslashes($tpl['arr']['booking_route']);?>" />
		<input type="hidden" id="reload_map" name="reload_map" value="1" />
		<input type="hidden" id="return_reload_map" name="reload_map" value="1" />
		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id'];?>" />
		<input type="checkbox" name="is_return" id="is_return" value="T" <?php echo $tpl['arr']['is_return'] == 'T'?'checked="checked"':NULL; ?> style="display: none;"/>
		<div class="row">
			<div class="col-lg-9">
				<div class="tabs-container">
					<ul class="nav nav-tabs">
						<li class="active"><a class="tab-booking-details" href="#booking-details" rev="1" aria-controls="booking-details" role="tab" data-toggle="tab" aria-expanded="true"><?php __('lblBookingDetails');?></a></li>
						<li class=""><a class="tab-client-details" href="#client-details" rev="2" aria-controls="client-details" role="tab" data-toggle="tab" aria-expanded="false"><?php __('lblClientDetails');?></a></li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="booking-details">
							<div class="panel-body">
								
								<div class="bs-loader-outer">
									<div class="bs-loader"></div>
									<div class="row">
										<div class="col-sm-6 col-xs-12">
											<div class="form-group">
												<label ><?php __('lblDate'); ?></label>
												<div class="input-group date datepicker">
													<input class="form-control required" id="booking_date" name="booking_date" value="<?php echo pjDateTime::formatDate($tpl['arr']['booking_date'], 'Y-m-d', $tpl['option_arr']['o_date_format'])?>" type="text" readonly="readonly" data-msg-required="<?php __('fd_field_required');?>">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												</div>
											</div>
											<div id="fromBox">
												<div class="form-group">
													<label ><?php __('lblFrom'); ?></label>
													<div>
														<div>
															<select name="pickup_id" id="pickup_id" class="form-control select-item required" data-msg-required="<?php __('fd_field_required');?>">
																<option value="">-- <?php __('lblChoose'); ?>--</option>
																<?php
																foreach($tpl['from_location_arr'] as $k => $v)
																{
																	?><option value="<?php echo $v['id'];?>"<?php echo $v['id'] == $tpl['arr']['pickup_id'] ? ' selected="selected"' : null; ?>><?php echo pjSanitize::clean($v['name']);?></option><?php
																} 
																?>
															</select>
														</div>
														<span id="bsDepartureTime" class="text-info"><?php echo !empty($time_arr) ? __('lblDepartureTime', true, false) . ': ' . $time_arr[0] : null;?></span>
													</div>
												</div>
											</div>
											<div id="toBox">
												<div class="form-group">
													<label ><?php __('lblTo'); ?></label>
													<div>
														<div>
															<select name="return_id" id="return_id" class="form-control select-item required" data-msg-required="<?php __('fd_field_required');?>">
																<option value="">-- <?php __('lblChoose'); ?>--</option>
																<?php
																foreach($tpl['to_location_arr'] as $k => $v)
																{
																	?><option value="<?php echo $v['id'];?>"<?php echo $v['id'] == $tpl['arr']['return_id'] ? ' selected="selected"' : null; ?>><?php echo pjSanitize::clean($v['name']);?></option><?php
																} 
																?>
															</select>
														</div>
														<span id="bsArrivalTime" class="text-info"><?php echo !empty($time_arr) ? __('lblArrivalTime', true, false) . ': ' . $time_arr[1] : null;?></span>
													</div>
												</div>
											</div>
											<div id="busBox">
												<div class="form-group">
													<label ><?php __('lblBus'); ?></label>
													<div>
														<select name="bus_id" id="bus_id" class="form-control select-item required" data-msg-required="<?php __('fd_field_required');?>">
															<option value="">-- <?php __('lblChoose'); ?>--</option>
															<?php
															foreach ($tpl['bus_arr'] as $k => $v)
															{
																?><option value="<?php echo $v['id']; ?>"<?php echo $v['id'] == $tpl['arr']['bus_id'] ? ' selected="selected"' : null; ?> data-set="<?php echo !empty($v['seats_map']) ? 'T' : 'F';?>"><?php echo $v['route']; ?>, <?php echo $v['depart_arrive']; ?></option><?php
															}
															?>
														</select>
													</div>
												</div>
											</div>
											<div id="ticketBox">
												<?php
												if(isset($tpl['ticket_arr']))
												{ 
													?>
													<label><?php __('lblTickets'); ?>:</label>
													<?php
													$seats_avail = $tpl['seats_available'];
													$total_titkets = 0;
													foreach($tpl['ticket_arr'] as $v)
													{
														if($v['price'] != '')
														{
															if((int) $tpl['arr']['back_id'] > 0 && $tpl['arr']['is_return'] == 'F')
															{
																$price = $v['price'] - ($v['price'] * $v['discount'] / 100);
															}else{
																$price = $v['price'];
															}
															?>
															<div class="form-group">
																<label><?php echo pjSanitize::html($v['ticket']); ?></label>
																<div class="input-group">
																	<select name="ticket_cnt_<?php echo $v['ticket_id'];?>" class="form-control bs-ticket" data-price="<?php echo $price;?>" data-msg-required="<?php __('fd_field_required');?>">
																		<?php
																		for($i = 0; $i <= $seats_avail; $i++)
																		{
																			if(isset($tpl['ticket_pair_arr'][$v['ticket_id']]) && ($tpl['ticket_pair_arr'][$v['ticket_id']] == $i) )
																			{
																				?><option value="<?php echo $i; ?>" selected="selected"><?php echo $i; ?></option><?php
																				$total_titkets += $i;
																			}else{
																				?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php
																			}
																		}
																		$seats_avail -= $total_titkets;
																		?>
																	</select>
																	<span class="input-group-addon">&nbsp;x&nbsp;<?php echo pjCurrency::formatPrice($price);?></span>
																</div>
															</div>
															<?php
														}
													} 
													?>
													<input type="hidden" id="bs_number_of_seats" name="bs_number_of_seats" value="<?php echo $tpl['seats_available']; ?>" data-msg-required="<?php __('fd_field_required');?>"/>
													<?php
												} 
												?>
											</div>
											
											<div class="form-group" id="seatsBox" style="display: <?php echo !empty($tpl['bus_type_arr']['seats_map']) ? 'block' : 'none';?>;">
												<label><?php __('lblSeats'); ?>:</label>
												<label>
													<span id="bs_selected_seat_label"><?php echo join(", ", $tpl['selected_seats'])?></span>
													<a class="bs-select-seats" href="#"><?php __('lblSelectSeats');?></a>
												</label>
												<div>
													<input type="hidden" id="selected_seats" name="selected_seats" value="<?php echo join("|", $tpl['seat_pair_arr'])?>"<?php echo !empty($tpl['bus_type_arr']['seats_map']) ? (!empty($tpl['seat_pair_arr']) ? ' class=""' : null): null;?> data-msg-required="<?php __('fd_field_required');?>" />
												</div>
											</div>									
											<div id="selectSeatsBox" style="display: none;">
												<div class="form-group">
													<label><?php __('lblSeats'); ?></label>
													<div>
														<select name="assigned_seats[]" id="assigned_seats" class="form-control select-item <?php echo empty($tpl['bus_type_arr']['seats_map']) ? ' ' : null;?>" multiple="multiple" size="5" data-msg-required="<?php __('fd_field_required', false, true);?>">
															<?php
															foreach ($tpl['seat_arr'] as $seat)
															{
																if(!in_array($seat['id'], $tpl['booked_seat_arr']))
																{
																	?><option value="<?php echo $seat['id']; ?>"<?php echo in_array($seat['id'], $tpl['seat_pair_arr']) ? ' selected="selected"' : null;?>><?php echo stripslashes($seat['name']); ?></option><?php
																}
															}
															?>
														</select>
														<?php if (pjAuth::factory('pjAdminSchedule', 'pjActionSeats')->hasAccess()) { ?>
															<a class="block" target="_blank" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionSeats&amp;bus_id=<?php echo $tpl['arr']['bus_id']?>&amp;date=<?php echo pjDateTime::formatDate($tpl['arr']['booking_date'], 'Y-m-d', $tpl['option_arr']['o_date_format'])?>"><?php __('lblViewSeatsList');?></a>
														<?php } ?>
													</div>
												</div>
											</div>	
											<?php if ($tpl['arr']['is_return'] == 'T' || !empty($tpl['arr']['back_id'])) { ?>
												<div class="form-group">
										            <label class="control-label"><?php __('lblIsReturn'); ?>: </label>
										        	<?php if ($tpl['arr']['is_return'] == 'T') { ?>
														<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&id=<?php echo $tpl['arr']['back_id']?>"><?php __('lblReturnBooking')?></a>
													<?php } elseif (!empty($tpl['arr']['back_id'])) { ?>
														<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&id=<?php echo $tpl['arr']['back_id']?>"><?php __('lblPickupBooking')?></a>
													<?php } ?>
										        </div>
									        <?php } ?>
										</div>
										<div class="col-sm-6 col-xs-12">
											<div class="form-group">
												<label><?php __('lblBookingID'); ?></label>
												<div>
													<input class="form-control required" name="uuid" id="uuid" value="<?php echo pjSanitize::html($tpl['arr']['uuid']); ?>" maxlength="255" data-msg-required="<?php __('fd_field_required', false, true);?>" type="text" aria-required="true">
												</div>
											</div>
											
											<div class="form-group">
												<label><?php __('lblStatus'); ?></label>
												<div>
													<select name="status" id="status" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>" aria-required="true">
														<option value="">-- <?php __('lblChoose'); ?> --</option>
														<?php
														foreach (__('booking_statuses', true, false) as $k => $v)
														{
															?><option value="<?php echo $k; ?>"<?php echo $tpl['arr']['status'] == $k ? 'selected="selected"' : '';?>><?php echo $v; ?></option><?php
														}
														?>
													</select>
												</div>
											</div>
											
											<div class="form-group">
												<label><?php __('lblSubTotal'); ?></label>
												<div class="input-group">
													<input type="text" class="form-control number" name="sub_total" id="sub_total" value="<?php echo pjSanitize::clean($tpl['arr']['sub_total']); ?>" data-msg-required="<?php __('fd_field_required');?>" data-msg-number="<?php __('prices_invalid_price');?>">
													<span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']);?></span>
												</div>
											</div>
											
											<div class="form-group">
												<label><?php __('lblTax'); ?></label>
												<div class="input-group">
													<input type="text" class="form-control number" name="tax" id="tax" value="<?php echo pjSanitize::clean($tpl['arr']['tax']); ?>" data-tax="<?php echo $tpl['option_arr']['o_tax_payment'];?>" data-msg-required="<?php __('fd_field_required');?>" data-msg-number="<?php __('prices_invalid_price');?>">
													<span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']);?></span>
												</div>
											</div>
											
											<div class="form-group">
												<label><?php __('lblTotal'); ?></label>
												<div class="input-group">
													<input type="text" class="form-control number" name="total" id="total" value="<?php echo pjSanitize::clean($tpl['arr']['total']); ?>" data-msg-required="<?php __('fd_field_required');?>" data-msg-number="<?php __('prices_invalid_price');?>">
													<span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']);?></span>
												</div>
											</div>
											
											<div class="form-group">
												<label><?php __('lblDeposit'); ?></label>
												<div class="input-group">
													<input type="text" class="form-control number" name="deposit" id="deposit" value="<?php echo pjSanitize::clean($tpl['arr']['deposit']); ?>" data-deposit="<?php echo $tpl['option_arr']['o_deposit_payment'];?>" data-msg-required="<?php __('fd_field_required');?>" data-msg-number="<?php __('prices_invalid_price');?>">
													<span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']);?></span>
												</div>
											</div>
											
											<div class="form-group">
												<label><?php __('lblPaymentMethod'); ?></label>
												<?php
												$online_arr = array();
												$offline_arr = array();
												foreach (__('payment_methods', true, false) as $k => $v)
												{
													if($k == 'creditcard') continue;
													if(in_array($k, array('cash', 'bank')))
													{
														$offline_arr[$k] = $v;
													}else{
														$online_arr[$k] = $v;
													}
												}
												?>
												<div>
													<select name="payment_method" id="payment_method" class="form-control" data-msg-required="<?php __('fd_field_required', false, true);?>">
														<option value="">-- <?php __('lblChoose'); ?>--</option>
														<optgroup label="<?php __('script_online_payment_gateway', false, true); ?>">
														<?php
														foreach($online_arr as $k => $v)
														{
															?><option value="<?php echo $k;?>"<?php echo $k == $tpl['arr']['payment_method'] ? ' selected="selected"' : NULL; ?>><?php echo $v;?></option><?php
														}
														?>
														</optgroup>
														<optgroup label="<?php __('script_offline_payment', false, true); ?>">
														<?php
														foreach($offline_arr as $k => $v)
														{
															?><option value="<?php echo $k;?>"<?php echo $k == $tpl['arr']['payment_method'] ? ' selected="selected"' : NULL; ?>><?php echo $v;?></option><?php
														}
														?>
														</optgroup>
													</select>
												</div>
											</div>
										</div>
									</div>
									
								</div>
								
								<div class="hr-line-dashed"> </div>

								<div class="clearfix">
									<button class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in">
										<span class="ladda-label"><?php __('btnSave'); ?></span>
										<?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>   
									</button>
								
									<button class="btn btn-white btn-lg pull-right" type="button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminBookings&action=pjActionIndex';"><?php __('btnCancel'); ?></button>
								</div>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="client-details">
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-4 col-md-6 col-xs-12">
										<div class="form-group">
											<label><?php __('lblBookingTitle'); ?></label>
											<div>
												<select name="c_title" id="c_title" class="form-control <?php echo $tpl['option_arr']['o_bf_include_title'] == 3 ? ' required' : NULL; ?>" data-msg-required="<?php __('fd_field_required');?>">
													<option value="">-- <?php __('lblChoose'); ?>--</option>
													<?php
													$title_arr = pjUtil::getTitles();
													$name_titles = __('personal_titles', true, false);
													foreach ($title_arr as $v)
													{
														?><option value="<?php echo $v; ?>"<?php echo $v == $tpl['arr']['c_title'] ? ' selected="selected"' : NULL; ?>><?php echo $name_titles[$v]; ?></option><?php
													}
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-6 col-xs-12">
										<div class="form-group">
											<label><?php __('lblBookingFname'); ?></label>
											<div>
												<input type="text" name="c_fname" id="c_fname" value="<?php echo pjSanitize::html($tpl['arr']['c_fname']);?>" class="form-control <?php echo (int) $tpl['option_arr']['o_bf_include_fname'] === 3 ? ' required' : NULL; ?>" data-msg-required="<?php __('fd_field_required');?>">
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-6 col-xs-12">
										<div class="form-group">
											<label><?php __('lblBookingLname'); ?></label>
											<div>
												<input type="text" name="c_lname" id="c_lname" value="<?php echo pjSanitize::html($tpl['arr']['c_lname']);?>" class="form-control <?php echo (int) $tpl['option_arr']['o_bf_include_lname'] === 3 ? ' required' : NULL; ?>" data-msg-required="<?php __('fd_field_required');?>">
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-6 col-xs-12">
										<div class="form-group">
											<label><?php __('lblBookingEmail'); ?></label>
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-at"></i></span>
												<input type="text" name="c_email" id="c_email" value="<?php echo pjSanitize::html($tpl['arr']['c_email']);?>" class="form-control email<?php echo $tpl['option_arr']['o_bf_include_email'] == 3 ? '  required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>" data-msg-email="<?php __('plugin_base_email_invalid', false, true);?>"/>
											</div>
										</div>
									</div>
								
									<div class="col-lg-4 col-md-6 col-xs-12">
										<div class="form-group">
											<label><?php __('lblBookingPhone'); ?></label>
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-phone"></i></span>
												<input type="text" name="c_phone" id="c_phone" value="<?php echo pjSanitize::html($tpl['arr']['c_phone']);?>" class="form-control<?php echo $tpl['option_arr']['o_bf_include_phone'] == 3 ? '  required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>" />
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-6 col-xs-12">
										<div class="form-group">
											<label><?php __('lblBookingCompany'); ?></label>
											<div>
												<input type="text" name="c_company" id="c_company" value="<?php echo pjSanitize::html($tpl['arr']['c_company']);?>" class="form-control <?php echo (int) $tpl['option_arr']['o_bf_include_company'] === 3 ? '  required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>">
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-6 col-xs-12">
										<div class="form-group">
											<label><?php __('lblBookingAddress'); ?></label>
											<div>
												<input type="text" name="c_address" id="c_address" value="<?php echo pjSanitize::html($tpl['arr']['c_address']);?>" class="form-control <?php echo (int) $tpl['option_arr']['o_bf_include_address'] === 3 ? '  required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>">
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-6 col-xs-12">
										<div class="form-group">
											<label><?php __('lblBookingCity'); ?></label>
											<div>
												<input type="text" name="c_city" id="c_city" value="<?php echo pjSanitize::html($tpl['arr']['c_city']);?>" class="form-control <?php echo (int) $tpl['option_arr']['o_bf_include_city'] === 3 ? '  required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>">
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-6 col-xs-12">
										<div class="form-group">
											<label><?php __('lblBookingState'); ?></label>
											<div>
												<input type="text" name="c_state" id="c_state" value="<?php echo pjSanitize::html($tpl['arr']['c_state']);?>" class="form-control <?php echo (int) $tpl['option_arr']['o_bf_include_state'] === 3 ? '  required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-4 col-md-6 col-xs-12">
										<div class="form-group ">
											<label><?php __('lblBookingZip'); ?></label>
											<div>
												<input type="text" name="c_zip" id="c_zip" value="<?php echo pjSanitize::html($tpl['arr']['c_zip']);?>" class="form-control <?php echo (int) $tpl['option_arr']['o_bf_include_zip'] === 3 ? '  required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>">
											</div>
										</div>
										<div class="form-group ">
											<label><?php __('lblBookingCountry'); ?></label>
											<div>
												<select name="c_country" id="c_country" class="form-control select-item <?php echo (int) $tpl['option_arr']['o_bf_include_country'] === 3 ? '  required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>">
													<option value="">-- <?php __('lblChoose'); ?> --</option>
													<?php
													foreach ($tpl['country_arr'] as $country)
													{
														?><option value="<?php echo $country['id']; ?>"<?php echo $tpl['arr']['c_country'] == $country['id'] ? 'selected="selected"' : '';?>><?php echo stripslashes($country['name']); ?></option><?php
													}
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="col-lg-8 col-md-6 col-xs-12 ">
										<div class="form-group">
											<label><?php __('lblBookingNotes'); ?></label>
											<div>
												<textarea name="c_notes" id="c_notes" class="form-control<?php echo $tpl['option_arr']['o_bf_include_notes'] == 3 ? '  required' : NULL; ?>" rows="4" cols="30" data-msg-required="<?php __('fd_field_required', false, true);?>"><?php echo htmlspecialchars(stripslashes($tpl['arr']['c_notes'])); ?></textarea>
											</div>
										</div>
									</div>
									
								</div>
								<div class="hr-line-dashed"></div>
								<div class="clearfix">
									<button class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in">
										<span class="ladda-label"><?php __('btnSave'); ?></span>
										<?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>   
									</button>
								
									<button class="btn btn-white btn-lg pull-right" type="button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminBookings&action=pjActionIndex';"><?php __('btnCancel'); ?></button>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="m-b-lg">
					<div class="edit-reservation-actions">
						<a href="#" class="btn btn-primary btn-outline btn-block confirmation-email" data-id="<?php echo $tpl['arr']['id'];?>" title="<?php __('lblBookingConfirmationResend'); ?>"><i class="fa fa-envelope"></i> <?php __('lblBookingConfirmationResend'); ?></a>
						<a href="#" class="btn btn-primary btn-outline btn-block payment-email" data-id="<?php echo $tpl['arr']['id'];?>" title="<?php __('lblBookingPaymentResend'); ?>"><i class="fa fa-envelope"></i> <?php __('lblBookingPaymentResend'); ?></a>
						<a href="#" class="btn btn-primary btn-outline btn-block cancellation-email" data-id="<?php echo $tpl['arr']['id'];?>" title="<?php __('lblBookingCancelledResend'); ?>"><i class="fa fa-times"></i> <?php __('lblBookingCancelledResend'); ?></a>
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionPrintTickets&amp;id=<?php echo $tpl['arr']['id']; ?>&hash=<?php echo sha1($tpl['arr']['id'].$tpl['arr']['created'].PJ_SALT)?>" class="btn btn-primary btn-outline btn-block" target="_blank" title="<?php __('lblPrintTickets'); ?>"><i class="fa fa-print"></i> <?php __('lblPrintTickets'); ?></a>
					</div>
					<div id="pjBsSummaryWrapper" class="panel no-borders">
						<div id="panel-status" class="panel-heading bg-<?php echo $tpl['arr']['status'];?>">
							<p class="lead m-n">
								<i class="fa fa-exclamation-triangle"></i> <?php __('lblStatus'); ?>: <span class="pull-right status-text"><?php echo @$booking_statuses[$tpl['arr']['status']];?></span>
							</p>
						</div>
						<div class="panel-body">
							<p class="lead m-b-xs">
								<i class="fa color-pending fa-key"></i> <?php __('lblBookingID')?>:<span class="pull-right"><?php echo pjSanitize::html($tpl['arr']['uuid']);?></span>
							</p>
							<p class="lead m-b-xs">
								<i class="fa color-pending fa-calendar"></i> <?php __('lblCreatedOn'); ?>: <span class="pull-right"><?php echo date($tpl['option_arr']['o_date_format'], strtotime($tpl['arr']['created'])); ?>, <?php echo date($tpl['option_arr']['o_time_format'], strtotime($tpl['arr']['created'])); ?></span>
							</p>
							<p class="lead m-b-xs">
								<i class="fa color-pending fa-globe"></i> <?php __('lblIpAddress'); ?>:<span class="pull-right"><?php echo pjSanitize::html($tpl['arr']['ip']);?></span>
							</p>
						</div>
					</div>
					<div class="edit-reservation-widgets" style="margin: 0;">
						<div class="m-b-md">
							<a href="javascript:void(0);" class="widget widget-bg widget-client-info">
							<?php if (!empty($tpl['arr']['c_name'])) { ?>
								<p class="lead m-b-xs">
									<i class="fa fa-user"></i> <?php echo pjSanitize::html($tpl['arr']['c_name']);?>
								</p>
							<?php } ?>
							<?php if (!empty($tpl['arr']['c_email'])) { ?>
								<p class="lead m-b-xs">
									<i class="fa fa-envelope-o"></i> <?php echo pjSanitize::html($tpl['arr']['c_email']);?>
								</p>
							<?php } ?>
							<?php if (!empty($tpl['arr']['c_phone'])) { ?>
								<p class="lead m-n">
									<i class="fa fa-phone"></i> <?php echo pjSanitize::html($tpl['arr']['c_phone']);?>
								</p>
							<?php } ?>
							</a>
						</div>
						<div class="m-b-md">
							<?php
							$total = $tpl['arr']['total'] > 0 ? $tpl['arr']['total'] : 0;
							$payment_made = $tpl['arr']['status'] == 'confirmed' ? $tpl['arr']['deposit'] : 0;
							$payment_due = $total - $payment_made;
							$payment_due = $payment_due < 0 ? 0 : $payment_due;
							?>
							<a href="javascript:void(0);" class="widget widget-bg">
								<p class="lead m-b-xs">
									 <?php __('lblBookingTotalPrice');?>: <strong class="pull-right cr-total-quote"><?php echo pjCurrency::formatPrice($total, " ", NULL, $tpl['option_arr']['o_currency']);?></strong>
								</p>
								<p class="lead m-b-xs">
									 <?php __('lblBookingPaymentMade');?>: <strong class="pull-right pj_collected"><?php echo pjCurrency::formatPrice($payment_made, " ", NULL, $tpl['option_arr']['o_currency']);?></strong>
								</p>
								<p class="lead m-n">
									 <?php __('lblBookingPaymentDue');?>: <strong id="pj_due_payment" class="pull-right"><?php echo pjCurrency::formatPrice($payment_due, " ", NULL, $tpl['option_arr']['o_currency']);?></strong>
								</p>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="modal fade" id="selectSeatModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
		      <div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        	<h4 class="modal-title"><?php __('lblSelectSeats'); ?></h4>
		      </div>
		      <div id="selectSeatContentWrapper" class="modal-body"></div>
		      <div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal"><?php __('btnCancel');?></button>
		        	<button type="button" id="btnReselectSeat"class="btn btn-secondary"><?php __('btnReselect');?></button>
		        	<button type="button" id="btnSelectSeatConfirm" class="btn btn-primary"><?php __('btnOK');?></button>
		      </div>
	    </div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="confirmEmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
		      <div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        	<h4 class="modal-title"><?php __('email_confirmation'); ?></h4>
		      </div>
		      <div id="confirmEmailContentWrapper" class="modal-body"></div>
		      <div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal"><?php __('btnCancel');?></button>
		        	<button id="btnSendEmailConfirm" type="button" class="btn btn-primary"><?php __('btnSend');?></button>
		      </div>
	    </div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="paymentEmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
		      <div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        	<h4 class="modal-title"><?php __('email_payment'); ?></h4>
		      </div>
		      <div id="paymentEmailContentWrapper" class="modal-body"></div>
		      <div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal"><?php __('btnCancel');?></button>
		        	<button id="btnSendEmailPayment" type="button" class="btn btn-primary"><?php __('btnSend');?></button>
		      </div>
	    </div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="cancellationEmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
		      <div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        	<h4 class="modal-title"><?php __('email_cancellation'); ?></h4>
		      </div>
		      <div id="cancellationEmailContentWrapper" class="modal-body"></div>
		      <div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal"><?php __('btnCancel');?></button>
		        	<button id="btnSendEmailCancellation" type="button" class="btn btn-primary"><?php __('btnSend');?></button>
		      </div>
	    </div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.duplicatedUniqueID = "<?php __('lblDuplicatedUniqueID'); ?>";
myLabel.from = "<?php echo strtolower(__('lblFrom', true, false));?>";
myLabel.to = "<?php echo strtolower(__('lblTo', true, false));?>";
myLabel.assigned_seats = "<?php echo __('lblAssignedSeats');?>";
myLabel.loader = '<img src="<?php echo PJ_IMG_PATH;?>backend/pj-preloader.gif" />';
myLabel.choose = <?php x__encode('lblChoose');?>;
</script>