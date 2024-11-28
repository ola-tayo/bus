<?php 
$booking_statuses = __('booking_statuses', true, false);
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-4 col-sm-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<span class="label label-primary pull-right"><?php __('dash_today');?></span>
					<h5><?php __('dash_new_bookings');?></h5>
				</div>
				<div class="ibox-content ibox-content-stats">
					<div class="row">
						<div class="col-lg-3 col-xs-5">
							<p class="h1 no-margins">
								<a href="#"><?php echo (int) @$tpl['cnt_bookings_today'];?></a>
							</p>
						</div>
						<div class="col-lg-9 col-xs-7 text-right">
							<p class="h1 no-margins">
								<?php echo pjCurrency::formatPrice($tpl['total_amount_today']);?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-4 col-sm-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<span class="label label-primary pull-right"><?php __('dash_this_month');?></span>
					<h5><?php __('dash_total_bookings');?></h5>
				</div>
				<div class="ibox-content ibox-content-stats">
					<div class="row">
						<div class="col-lg-3 col-xs-5">
							<p class="h1 no-margins">
								<a href="#"><?php echo $tpl['cnt_bookings_this_month'];?></a>
							</p>
						</div>
						<div class="col-lg-9 col-xs-7 text-right">
							<p class="h1 no-margins">
								<?php echo pjCurrency::formatPrice($tpl['total_amount_this_month']);?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-2 col-xs-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo ucfirst(__('lblDashboardBuses', true));?></h5>
				</div>
				<div class="ibox-content ibox-content-stats">
					<p class="h1 no-margins">
						<a href="#"><?php echo (int)$tpl['cnt_buses'];?></a>
					</p>
				</div>
			</div>
		</div>

		<div class="col-lg-2 col-xs-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php __('lblDashboardRoutes');?></h5>
				</div>
				<div class="ibox-content ibox-content-stats">
					<p class="h1 no-margins">
						<a href="#"><?php echo (int)$tpl['cnt_routes'];?></a>
					</p>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-5 col-md-5 col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content ibox-heading clearfix">
                    <div class="pull-left">
                        <h3><?php __('lblDashLatestBookings');?></h3>
                    </div><!-- /.pull-left -->
					<?php if (pjAuth::factory('pjAdminBookings', 'pjActionIndex')->hasAccess()) { ?>
	                    <div class="pull-right m-t-md">
	                    	<a class="btn btn-primary btn-sm btn-outline m-n" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex"><?php __('lblDashViewAll');?></a>
	                    </div><!-- /.pull-right -->
                   	<?php } ?>
                </div>
				
                <div class="ibox-content inspinia-timeline">
                	<?php
                	if(!empty($tpl['latest_bookings']))
                	{
                	    foreach($tpl['latest_bookings'] as $v)
                	    {
                	        $client_name_arr = array();
							if(!empty($v['c_fname']))
							{
								$client_name_arr[] = pjSanitize::clean($v['c_fname']);
							}
							if(!empty($v['c_lname']))
							{
								$client_name_arr[] = pjSanitize::clean($v['c_lname']);
							}
							$bus = $v['route_title'] . ', ' . date($tpl['option_arr']['o_time_format'], strtotime($v['departure_time'])) . ' - ' . date($tpl['option_arr']['o_time_format'], strtotime($v['arrival_time']));
							$route = mb_strtolower(__('lblFrom', true), 'UTF-8') . ' ' . $v['from_location'] . ' ' . mb_strtolower(__('lblTo', true), 'UTF-8') . ' ' . $v['to_location'];
                	        ?>
                	        <div class="timeline-item">
                                <div class="row">
                                    <div class="col-xs-3 date">
                                        <i class="fa fa-clock-o"></i>
                                        <?php echo date($tpl['option_arr']['o_date_format'], strtotime($v['created'])) . ' ' . date($tpl['option_arr']['o_time_format'], strtotime($v['created']));?>
                                    </div>
        
                                    <div class="col-xs-7 content">
                                    	<?php if (pjAuth::factory('pjAdminBookings', 'pjActionUpdate')->hasAccess()) { ?>
                                    		<p class="m-b-xs"><strong><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $v['id']?>"><?php echo pjSanitize::html($v['uuid']);?></a></strong></p>
                                    		<p class="m-b-xs"><strong><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $v['id']?>"><?php echo join(" ", $client_name_arr);?></a></strong></p>
                                    	<?php } else { ?>
                                    		<p class="m-b-xs"><strong><a href="javascript:void(0);"><?php echo pjSanitize::html($v['uuid']);?></a></strong></p>
                                    		<p class="m-b-xs"><strong><a href="javascript:void(0);"><?php echo join(" ", $client_name_arr);?></a></strong></p>
                                    	<?php } ?>
                                    	<?php if(!empty($v['c_phone'])) { ?>
                                        	<p class="m-n"><?php echo pjSanitize::html($v['c_phone']);?></p>
                                        <?php } ?>
                                        <p class="m-n"><?php echo $bus;?></p>
                                        <p class="m-n"><span><?php __('lblAt');?></span> <?php echo date($tpl['option_arr']['o_date_format'], strtotime($v['booking_date']));?></p>
                                        <p class="m-n"><span><?php __('lblTickets');?></span> <?php echo $v['tickets'];?></p>
                                    </div>
                                    
                                    <div class="badge bg-<?php echo $v['status'];?> b-r-sm pull-right m-t-md m-r-sm"><?php echo $booking_statuses[$v['status']];?></div>
                                </div>
                            </div>
                	        <?php
                	    }
                	}else{
                	    ?>
                	    <div class="row">
                            <div class="col-xs-12">
                                <p class="m-b-xs"><?php __('lblDashNoBooking');?></p>
                            </div>
                        </div>
                	    <?php
                	}
                	?>
                </div>
            </div>
        </div><!-- /.col-lg-6 -->
        
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="ibox float-e-margins">
                <div class="ibox-content ibox-heading clearfix">
                    <div class="pull-left">
                        <h3><?php __('lblDashNextDeparture');?></h3>
                    </div><!-- /.pull-left -->
					<?php if (pjAuth::factory('pjAdminSchedule', 'pjActionIndex')->hasAccess()) { ?>
	                    <div class="pull-right m-t-md">
	                    	<a class="btn btn-primary btn-sm btn-outline m-n" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionIndex"><?php __('lblDashViewAll');?></a>
	                    </div><!-- /.pull-right -->
                   	<?php } ?>
                </div>
				
                <div class="ibox-content inspinia-timeline">
                	<?php
                	if(!empty($tpl['next_buses_arr']))
                	{
                	    foreach($tpl['next_buses_arr'] as $v)
                	    {
                	    	$bus_time = '';
							if(!empty($v['departure']) && !empty($v['arrive']))
							{
								$bus_time = pjDateTime::formatTime(date('H:i:s', strtotime($v['departure'])), "H:i:s", $tpl['option_arr']['o_time_format']) . ' - ' . pjDateTime::formatTime(date('H:i:s', strtotime($v['arrive'])), "H:i:s", $tpl['option_arr']['o_time_format']);
							}
                	        ?>
                	        <div class="timeline-item">
                                <div class="row">
                                    <div class="col-xs-12">
                                    	<p class="m-b-xs"><strong><?php __('lblBus');?>: <?php echo $v['route'];?>, <?php echo $bus_time;?></strong></p>
                                    	<p class="m-b-xs"><strong><?php __('lblAt');?> <?php echo date($tpl['option_arr']['o_date_format'], strtotime($v['departure_date']));?></strong></p>
                                    	<p class="m-b-xs"><span><?php __('lblTotalBookings');?>:</span> <?php echo $v['total_bookings'];?></p>
                                    	<p class="m-b-xs"><span><?php __('lblTotalTocketsSold');?>:</span> <?php echo intval($v['total_tickets']);?></p>
                                    	<?php if (pjAuth::factory('pjAdminSchedule', 'pjActionBookings')->hasAccess()) { ?>
                                    		<p class="m-b-xs"><strong><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionBookings&amp;bus_id=<?php echo $v['id']?>&amp;date=<?php echo date($tpl['option_arr']['o_date_format'], strtotime($v['departure_date']));?>"><?php __('lblViewPassengersList');?></a></strong></p>
                                    	<?php } ?>
                                    	<?php if (pjAuth::factory('pjAdminSchedule', 'pjActionSeats')->hasAccess()) { ?>
                                    		<p class="m-b-xs"><strong><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionSeats&amp;bus_id=<?php echo $v['id']?>&amp;date=<?php echo date($tpl['option_arr']['o_date_format'], strtotime($v['departure_date']));?>"><?php __('lblViewSeatsList');?></a></strong></p>
                                    	<?php } ?>
                                    </div>
                                </div>
                                <?php if ($k < count($tpl['next_buses_arr']) - 1) { ?>
                               		<div class="hr-line-dashed"></div>
                               	<?php } ?>
                            </div>
                	        <?php
                	    }
                	}else{
                	    ?>
                	    <div class="row">
                            <div class="col-xs-12">
                                <p class="m-b-xs"><?php __('lblDashNoBuses');?></p>
                            </div>
                        </div>
                	    <?php
                	}
                	?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="ibox float-e-margins">
                <div class="ibox-content ibox-heading clearfix">
                    <h3><?php __('lblOverlappingSeats');?></h3>
                </div>
				
                <div class="ibox-content inspinia-timeline">
                	<?php
                	if(!empty($tpl['overlapping_seats']))
                	{
                		$and = ' ' . __('lblAnd', true, false) . ' ';
                	    foreach($tpl['overlapping_seats'] as $k => $v)
                	    {
                	    	$row_arr = array();
							$uuid_arr = $v['uuid'];
							foreach($uuid_arr as $pair)
							{
								list($id, $uuid) = explode(":", $pair);
								if (pjAuth::factory('pjAdminBookings', 'pjActionUpdate')->hasAccess()) {
									$row_arr[] = '<a href="'.$_SERVER['PHP_SELF'].'?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id='.$id.'">' . $pair . '</a>';
								} else {
									$row_arr[] = '<a href="javascript:void(0);">' . $pair . '</a>';
								}
							}
                	        ?>
                	        <div class="timeline-item">
                                <div class="row">
                                    <div class="col-xs-12">
                                    	<p class="m-b-xs"><?php echo join($and, $row_arr);?></p>
                                    </div>
                                </div>
                                <?php if ($k < count($tpl['overlapping_seats']) - 1) { ?>
                               		<div class="hr-line-dashed"></div>
                               	<?php } ?>
                            </div>
                	        <?php
                	    }
                	}else{
                	    ?>
                	    <div class="row">
                            <div class="col-xs-12">
                                <p class="m-b-xs"><?php __('lblNoOverlapping');?></p>
                            </div>
                        </div>
                	    <?php
                	}
                	?>
                </div>
            </div>
        </div>
        
	</div>
	
</div><!-- /.wrapper wrapper-content -->