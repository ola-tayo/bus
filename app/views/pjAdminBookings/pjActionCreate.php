<?php 
$titles = __('error_titles', true);
$bodies = __('error_bodies', true);
$months = __('months', true);
ksort($months);
$short_days = __('short_days', true);
?>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-10">
				<h2><?php __('infoAddBookingTitle');?></h2>
			</div>
		</div><!-- /.row -->

		<p class="m-b-none"><i class="fa fa-info-circle"></i><?php __('infoAddBookingDesc');?></p>
	</div><!-- /.col-md-12 -->
</div>
<div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-format="<?php echo pjUtil::toBootstrapDate($tpl['option_arr']['o_date_format']); ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>
<div class="wrapper wrapper-content animated fadeInRight">
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate" method="post" id="frmCreateBooking" novalidate="novalidate">
		<input type="hidden" name="booking_create" value="1" />
		<input type="hidden" id="booking_route" name="booking_route"/>
		<input type="hidden" id="booking_return_route" name="booking_return_route"/>
		<input type="hidden" id="reload_map" name="reload_map" value="1" />
		<input type="hidden" id="return_reload_map" name="return_reload_map" value="1" />
		<input type="hidden" id="pickup_sub_total" name="pickup_sub_total"/>
		<input type="hidden" id="return_sub_total" name="return_sub_total"/>
		<input type="hidden" id="pickup_tax" name="pickup_tax"/>
		<input type="hidden" id="return_tax" name="return_tax"/>
		<input type="hidden" id="pickup_total" name="pickup_total"/>
		<input type="hidden" id="return_total" name="return_total"/>
		<input type="hidden" id="pickup_deposit" name="pickup_deposit"/>
		<input type="hidden" id="return_deposit" name="return_deposit"/>
		<div class="tabs-container">
			<ul class="nav nav-tabs">
				<li class="active"><a class="tab-reservation-details" href="#reservation-details" rev="1" aria-controls="reservation-details" role="tab" data-toggle="tab" aria-expanded="true"><?php __('lblBookingDetails');?></a></li>
				<li class=""><a class="tab-client-details" href="#client-details" rev="2" aria-controls="client-details" role="tab" data-toggle="tab" aria-expanded="false"><?php __('lblClientDetails');?></a></li>
			</ul>
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="reservation-details">
					<div class="panel-body">
						<div class="bs-loader-outer">
							<div class="bs-loader"></div>
							<div class="row">
								<div class="col-sm-6 col-xs-12">
									<div class="form-group">
										<label ><?php __('lblDate'); ?></label>
										<div class="input-group date datepicker">
											<input class="form-control required" id="booking_date" name="booking_date" value="<?php echo $controller->_get->check('date_from') ? pjDateTime::formatDate($controller->_get->toString('date_from'), 'Y-m-d', $tpl['option_arr']['o_date_format']) : date($tpl['option_arr']['o_date_format']); ?>" type="text" readonly="readonly" data-msg-required="<?php __('fd_field_required');?>">
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
															?><option value="<?php echo $v['id'];?>"<?php echo $controller->_get->check('bus_id') ? ($v['id'] == $controller->_get->toInt('pickup_id') ? ' selected="selected"' : null) : null; ?>><?php echo pjSanitize::clean($v['name']);?></option><?php
														} 
														?>
													</select>
												</div>
												<span id="bsDepartureTime" class="text-info"><?php echo $controller->_get->check('bus_id') ? __('lblDepartureTime', true, false) . ': ' . pjDateTime::formatTime(date('H:i:s', strtotime($tpl['bus']['departure_time'])), "H:i:s", $tpl['option_arr']['o_time_format']) : null;?></span>
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
															?><option value="<?php echo $v['id'];?>"<?php echo $controller->_get->check('bus_id') ? ($v['id'] == $controller->_get->toInt('return_id') ? ' selected="selected"' : null) : null; ?>><?php echo pjSanitize::clean($v['name']);?></option><?php
														} 
														?>
													</select>
												</div>
												<span id="bsArrivalTime" class="text-info"><?php echo $controller->_get->check('bus_id') ? __('lblArrivalTime', true, false) . ': ' . pjDateTime::formatTime(date('H:i:s', strtotime($tpl['bus']['arrival_time'])), "H:i:s", $tpl['option_arr']['o_time_format']) : null;?></span>
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
													if($controller->_get->check('bus_id'))
													{
														foreach ($tpl['bus_arr'] as $k => $v)
														{
															?><option value="<?php echo $v['id']; ?>"<?php echo $v['id'] == $controller->_get->toInt('bus_id') ? ' selected="selected"' : null; ?> data-set="<?php echo !empty($v['seats_map']) ? 'T' : 'F';?>"><?php echo $v['route']; ?>, <?php echo $v['depart_arrive']; ?></option><?php
														}
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
											foreach($tpl['ticket_arr'] as $v)
											{
												?>
												<div class="form-group">
													<label><?php echo pjSanitize::html($v['ticket']); ?></label>
													<div class="input-group">
														<select name="ticket_cnt_<?php echo $v['ticket_id'];?>" class="form-control bs-ticket" data-price="<?php echo $v['price'];?>" data-msg-required="<?php __('fd_field_required');?>">
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
											?>
											<input type="hidden" id="bs_number_of_seats" name="bs_number_of_seats" value="<?php echo $seats_avail; ?>" data-msg-required="<?php __('fd_field_required');?>"/>
											<?php
										} 
										?>
									</div>
									
									<div class="form-group" id="seatsBox" style="display: none;">
										<label><?php __('lblSeats'); ?>:</label>
										<label>
											<span id="bs_selected_seat_label"></span>
											<a class="bs-select-seats" href="#"><?php __('lblSelectSeats');?></a>
										</label>
										<div>
											<input type="hidden" id="selected_seats" name="selected_seats" value="" class="required" data-msg-required="<?php __('fd_select_seats_required', false, true);?>"/>
										</div>
									</div>									
									<div id="selectSeatsBox" style="display: none;"></div>	
									
									<div class="form-group">
							            <label class="control-label"><?php __('lblIsReturn'); ?></label>
							        
							            <div class="clearfix">
							                <div class="switch onoffswitch-data pull-left">
							                    <div class="onoffswitch onoffswitch-return-ticket">
							                        <input type="checkbox" class="onoffswitch-checkbox" id="is_return" name="is_return" value="T">
							                        <label class="onoffswitch-label" for="is_return">
							                            <span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T'); ?>" data-off="<?php __('_yesno_ARRAY_F'); ?>"></span>
							                            <span class="onoffswitch-switch"></span>
							                        </label>
							                    </div>
							                </div>
							            </div><!-- /.clearfix -->
							        </div>
							        
									<div class="form-group returnBox" style="display: none;">
										<label ><?php __('lblReturnDate'); ?></label>
										<div class="input-group date datepicker">
											<input class="form-control" id="return_date" name="return_date" type="text" readonly="readonly" data-msg-required="<?php __('fd_field_required');?>">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>
									</div>
									
									<div id="returnBox" class="returnBox"></div>
									<div id="returnTicketBox" class="returnBox" style="display: none;">
										
									</div>
									<div class="form-group" id="seatsReturnBox" style="display: none;">
										<label><?php __('lblReturnSeats'); ?>:</label>
										<label>
											<span id="bs_return_selected_seat_label"></span>
											<a class="bs-select-return-seats" href="#"><?php __('lblSelectSeats');?></a>
										</label>
										<div>
											<input type="hidden" id="return_selected_seats" name="return_selected_seats" value="" data-msg-required="<?php __('fd_select_seats_required', false, true);?>"/>
										</div>
									</div>
									<div id="selectReturnSeatsBox" style="display: none;"></div>	
									
								</div>
								<div class="col-sm-6 col-xs-12">
									<div class="form-group">
										<label><?php __('lblBookingID'); ?></label>
										<div>
											<input class="form-control required" name="uuid" id="uuid" value="<?php echo pjUtil::uuid(); ?>" maxlength="255" data-msg-required="<?php __('fd_field_required', false, true);?>" type="text" aria-required="true">
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
													?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
												}
												?>
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label><?php __('lblSubTotal'); ?></label>
										<div class="input-group">
											<input type="text" class="form-control number" name="sub_total" id="sub_total" data-msg-required="<?php __('fd_field_required');?>" data-msg-number="<?php __('prices_invalid_price');?>">
											<span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']);?></span>
										</div>
									</div>
									
									<div class="form-group">
										<label><?php __('lblTax'); ?></label>
										<div class="input-group">
											<input type="text" class="form-control number" name="tax" id="tax" data-tax="<?php echo $tpl['option_arr']['o_tax_payment'];?>" data-msg-required="<?php __('fd_field_required');?>" data-msg-number="<?php __('prices_invalid_price');?>">
											<span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']);?></span>
										</div>
									</div>
									
									<div class="form-group">
										<label><?php __('lblTotal'); ?></label>
										<div class="input-group">
											<input type="text" class="form-control number" name="total" id="total" data-msg-required="<?php __('fd_field_required');?>" data-msg-number="<?php __('prices_invalid_price');?>">
											<span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']);?></span>
										</div>
									</div>
									
									<div class="form-group">
										<label><?php __('lblDeposit'); ?></label>
										<div class="input-group">
											<input type="text" class="form-control number" name="deposit" id="deposit" data-deposit="<?php echo $tpl['option_arr']['o_deposit_payment'];?>" data-msg-required="<?php __('fd_field_required');?>" data-msg-number="<?php __('prices_invalid_price');?>">
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
													?><option value="<?php echo $k;?>"><?php echo $v;?></option><?php
												}
												?>
												</optgroup>
												<optgroup label="<?php __('script_offline_payment', false, true); ?>">
												<?php
												foreach($offline_arr as $k => $v)
												{
													?><option value="<?php echo $k;?>"><?php echo $v;?></option><?php
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
							<div class="col-lg-3 col-md-6 col-sm-6">
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
												?><option value="<?php echo $v; ?>"><?php echo $name_titles[$v]; ?></option><?php
											}
											?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-6 col-sm-6">
								<div class="form-group">
									<label><?php __('lblBookingFname'); ?></label>
									<div>
										<input type="text" name="c_fname" id="c_fname" class="form-control <?php echo (int) $tpl['option_arr']['o_bf_include_fname'] === 3 ? ' required' : NULL; ?>" data-msg-required="<?php __('fd_field_required');?>">
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-6 col-sm-6">
								<div class="form-group">
									<label><?php __('lblBookingLname'); ?></label>
									<div>
										<input type="text" name="c_lname" id="c_lname" class="form-control <?php echo (int) $tpl['option_arr']['o_bf_include_lname'] === 3 ? ' required' : NULL; ?>" data-msg-required="<?php __('fd_field_required');?>">
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-6 col-sm-6">
								<div class="form-group">
									<label><?php __('lblBookingEmail'); ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-at"></i></span>
										<input type="text" name="c_email" id="c_email" class="form-control email<?php echo $tpl['option_arr']['o_bf_include_email'] == 3 ? '  required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>" data-msg-email="<?php __('plugin_base_email_invalid', false, true);?>"/>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-3 col-md-6 col-sm-6">
								<div class="form-group">
									<label><?php __('lblBookingPhone'); ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-phone"></i></span>
										<input type="text" name="c_phone" id="c_phone" class="form-control<?php echo $tpl['option_arr']['o_bf_include_phone'] == 3 ? '  required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>" />
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-6 col-sm-6">
								<div class="form-group">
									<label><?php __('lblBookingCompany'); ?></label>
									<div>
										<input type="text" name="c_company" id="c_company" class="form-control <?php echo (int) $tpl['option_arr']['o_bf_include_company'] === 3 ? '  required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>">
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-6 col-sm-6">
								<div class="form-group">
									<label><?php __('lblBookingAddress'); ?></label>
									<div>
										<input type="text" name="c_address" id="c_address" class="form-control <?php echo (int) $tpl['option_arr']['o_bf_include_address'] === 3 ? '  required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>">
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-6 col-sm-6">
								<div class="form-group">
									<label><?php __('lblBookingCity'); ?></label>
									<div>
										<input type="text" name="c_city" id="c_city" class="form-control <?php echo (int) $tpl['option_arr']['o_bf_include_city'] === 3 ? '  required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-3 col-md-6 col-sm-6">
								<div class="form-group">
									<label><?php __('lblBookingState'); ?></label>
									<div>
										<input type="text" name="c_state" id="c_state" class="form-control <?php echo (int) $tpl['option_arr']['o_bf_include_state'] === 3 ? '  required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>">
									</div>
								</div>
								<div class="form-group ">
									<label><?php __('lblBookingZip'); ?></label>
									<div>
										<input type="text" name="c_zip" id="c_zip" class="form-control <?php echo (int) $tpl['option_arr']['o_bf_include_zip'] === 3 ? '  required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>">
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
												?><option value="<?php echo $country['id']; ?>"><?php echo stripslashes($country['name']); ?></option><?php
											}
											?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-lg-9 col-md-6 col-sm-6 ">
								<div class="form-group">
									<label><?php __('lblBookingNotes'); ?></label>
									<div>
										<textarea name="c_notes" id="c_notes" class="form-control<?php echo $tpl['option_arr']['o_bf_include_notes'] == 3 ? '  required' : NULL; ?>" rows="7" cols="30" data-msg-required="<?php __('fd_field_required', false, true);?>"></textarea>
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
	</form>
	
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
	
	<div class="modal fade" id="returnSelectSeatModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  	<div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
			      <div class="modal-header">
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        	<h4 class="modal-title"><?php __('lblSelectSeats'); ?></h4>
			      </div>
			      <div id="returnSelectSeatContentWrapper" class="modal-body"></div>
			      <div class="modal-footer">
			        	<button type="button" class="btn btn-default" data-dismiss="modal"><?php __('btnCancel');?></button>
			        	<button type="button" id="btnReturnReselectSeat"class="btn btn-secondary"><?php __('btnReselect');?></button>
			        	<button type="button" id="btnReturnSelectSeatConfirm" class="btn btn-primary"><?php __('btnOK');?></button>
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
</div>