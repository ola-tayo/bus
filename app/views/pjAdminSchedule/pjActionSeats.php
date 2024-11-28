<?php 
$number_of_locations = count($tpl['location_arr']); 
?>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-10">
				<h2><?php __('infoSeatsListTitle'); ?></h2>
			</div>
		</div><!-- /.row -->

		<p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoSeatsListDesc'); ?></p>
	</div><!-- /.col-md-12 -->
</div>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<?php if (pjAuth::factory('pjAdminBuses', 'pjActionTime')->hasAccess() ) { ?>
				<h3><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionTime&amp;id=<?php echo $controller->_get->toInt('bus_id');?>"><?php __('lblBus')?>:&nbsp;<?php echo $tpl['bus_arr']['route']?>, <?php echo pjDateTime::formatTime(date('H:i:s', strtotime($tpl['bus_arr']['departure_time'])), 'H:i:s', $tpl['option_arr']['o_time_format'])?> - <?php echo pjDateTime::formatTime(date('H:i:s', strtotime($tpl['bus_arr']['arrival_time'])), 'H:i:s', $tpl['option_arr']['o_time_format'])?></a> <?php __('lblOn');?> <?php echo $controller->_get->toString('date');?></h3>
			<?php } else { ?>
				<h3><a href="javascript:void(0);"><?php __('lblBus')?>:&nbsp;<?php echo $tpl['bus_arr']['route']?>, <?php echo pjDateTime::formatTime(date('H:i:s', strtotime($tpl['bus_arr']['departure_time'])), 'H:i:s', $tpl['option_arr']['o_time_format'])?> - <?php echo pjDateTime::formatTime(date('H:i:s', strtotime($tpl['bus_arr']['arrival_time'])), 'H:i:s', $tpl['option_arr']['o_time_format'])?></a> <?php __('lblOn');?> <?php echo $controller->_get->toString('date');?></h3>
			<?php } ?>			
			<div class="tabs-container tabs-reservations m-b-lg">
				<ul class="nav nav-tabs" role="tablist">
					<?php if (pjAuth::factory('pjAdminSchedule', 'pjActionBookings')->hasAccess() ) { ?>
		            	<li role="presentation"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionBookings&amp;bus_id=<?php echo $controller->_get->toInt('bus_id');?>&amp;date=<?php echo $controller->_get->toString('date');?>"><?php __('lblPassengersList');?></a></li>
		            <?php } ?>
	            	<li role="presentation" class="active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionSeats&amp;bus_id=<?php echo $controller->_get->toInt('bus_id');?>&amp;date=<?php echo $controller->_get->toString('date');?>"><?php __('lblSeatsList'); ?></a></li>
		        </ul>
		       <div class="tab-content">
		            <div role="tabpanel" class="tab-pane active">
		                <div class="panel-body">
							<form action="" method="get" class="frm-filter" id="frmSchedule">
								<div class="row">
									<div class="col-xs-12 text-right">
				                    	<div class="form-group">
											<a target="_blank" id="bs_print_seats" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionPrintSeats&amp;bus_id=<?php echo $controller->_get->toInt('bus_id');?>&amp;date=<?php echo pjDateTime::formatDate($controller->_get->toString('date'), $tpl['option_arr']['o_date_format'], 'Y-m-d');?>" class="btn btn-primary"><i class="fa fa-print"></i> <?php __('lblPrintList');?></a>
										</div>
				                    </div>
								</div>
							</form>
							<div class="pjTblSeatsGrid">
								<table class="tblSeatsGrid" id="tblSeatsGrid" cellpadding="0" cellspacing="0" style="width: 100%;" border="0">
									<thead>
										<tr>
											<th><?php __('lblSeats');?></th>
											<?php
											$total = 0;
											$switch = __('switch', true, false);
											foreach($tpl['location_arr'] as $k => $v)
											{
												$on_str = $off_str = 0;
												$_str = '<br/>';
												if($k < $number_of_locations - 1)
												{
													if(isset($tpl['on_arr'][$v['location_id']]))
													{
														$on_str = array_sum($tpl['on_arr'][$v['location_id']]);
														$total += $on_str;
														$_str .= ' ' . $switch['on']. ': ' . $on_str . ' /';
													}else{
														$_str .= ' ' . $switch['on']. ': 0' . ' /';
													}
												}
												if($k > 0)
												{	
													if(isset($tpl['off_arr'][$v['location_id']]))
													{
														$off_str = array_sum($tpl['off_arr'][$v['location_id']]);
														$total -= $off_str;
														$_str .= ' ' . $switch['off']. ': ' . $off_str . ' /';
													}else{
														$_str .= ' ' . $switch['off']. ': 0' . ' /';
													}
												}
												if($total > 0)
												{
													$_str .= ' ' . __('lblT', true, false) . ': ' . $total;
												}else{
													$_str .= ' ' . __('lblT', true, false) . ': 0';
												}
												$time = '&nbsp;';
												if(!empty($v['departure_time']))
												{
													$time = __('lblDeparture', true, false) . ": ". pjDateTime::formatTime(date('H:i:s', strtotime($v['departure_time'])), "H:i:s", $tpl['option_arr']['o_time_format']);
												}else{
													$time = __('lblArrive', true, false) . ": ". pjDateTime::formatTime(date('H:i:s', strtotime($v['arrival_time'])), "H:i:s", $tpl['option_arr']['o_time_format']);
												}
												
												?><th><b><?php echo pjSanitize::clean($v['location']);?></b><br/><?php echo $time;?><?php echo $_str;?></th><?php
												
												if($k == 0)
												{
													$first_order = $v['order'];
												}
												if($k == count($tpl['location_arr']) - 1)
												{
													$last_order = $v['order'];
												}
											} 
											?>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach($tpl['seat_arr'] as $key => $seat)
										{
											?>
											<tr>
												<td class="text-center"><?php echo pjSanitize::html($seat['name']);?></td>
												<?php
												if(isset($tpl['bs_arr'][$seat['id']]))
												{
													$bs_arr = $tpl['bs_arr'][$seat['id']];
													$first_col = 1;
													$person_titles = __('personal_titles', true, false);
													foreach($bs_arr as $k => $bs)
													{
														$colspan = $bs['return_order'] - $bs['pickup_order'];
														if($bs['return_order'] == $last_order)
														{
															$colspan++;
														}
														if($bs['pickup_order'] > $first_col)
														{
															$interval = $bs['pickup_order'] - $first_col;
															for($i = 1;$i <= $interval; $i++)
															{
																?><td>&nbsp;</td><?php
															}
														}
														
														$client_name_arr = array();
														if(!empty($bs['c_title']))
														{
															$client_name_arr[] = $person_titles[$bs['c_title']];
														}
														if(!empty($bs['c_fname']))
														{
															$client_name_arr[] = pjSanitize::clean($bs['c_fname']);
														}
														if(!empty($bs['c_lname']))
														{
															$client_name_arr[] = pjSanitize::clean($bs['c_lname']);
														}
														$tickets = $bs['tickets'];
														$cnt_tickets = count($tickets);
														
														$_ticket_arr = array();
														if($cnt_tickets > 1)
														{
															foreach($tickets as $t)
															{
																$_ticket_arr[] = $t;
															}
														}else{
															$_ticket_arr[] = $tickets[0];
														}
														$tooltip = __('lblBookingID', true, false) . ': ' . $bs['id'] . '<br/>' . __('lblNumberOfTickets', true, false) . ': ' . join(", ", $_ticket_arr) . '<br/>' . __('lblSeats', true, false) . ': ' . join(", ", $bs['seats']) . '<br/>' . __('lblPhone', true, false) . ': ' . $bs['c_phone'];
														?>
															<td class="bs-booked-seat text-center" colspan="<?php echo $colspan;?>">
																<?php if (pjAuth::factory('pjAdminBookings', 'pjActionUpdate')->hasAccess()) { ?>
																	<a class="timetable-tip" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo $tooltip;?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $bs['id'];?>"><?php echo join(" ", $client_name_arr);?></a>
																<?php } else { ?>
																	<a class="timetable-tip" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo $tooltip;?>" href="javascript:void(0);"><?php echo join(" ", $client_name_arr);?></a>
																<?php } ?>
															</td>
														<?php
														$first_col = $bs['return_order'];
														if($k == count($bs_arr) - 1 && $bs['return_order'] < $last_order)
														{
															$interval = $last_order - $bs['return_order'] + 1;
															for($i = 1;$i <= $interval; $i++)
															{
																?><td>&nbsp;</td><?php
															}
														}
													}
												}else{
													for($i = $first_order;$i <= $last_order; $i++)
													{
														?><td>&nbsp;</td><?php
													}
												}
												?>
											</tr>
											<?php
										} 
										?>
									</tbody>
								</table>
							</div>
							
							<div class="bs-seat-legends">
								<?php __('lblSeatsLegends');?>
							</div>
							
						</div>
	            	</div>
	   			</div>
			</div>
		</div>
	</div>
</div>