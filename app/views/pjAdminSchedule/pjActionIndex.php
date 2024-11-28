<?php 
$months = __('months', true);
ksort($months);
$short_days = __('short_days', true);
$current_date = date($tpl['option_arr']['o_date_format']);
?>
<div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-dateformat="<?php echo pjUtil::toMomemtJS($tpl['option_arr']['o_date_format']); ?>" data-format="<?php echo pjUtil::toMomemtJS($tpl['option_arr']['o_date_format']); ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-10">
				<h2><?php __('infoScheduleTitle'); ?></h2>
			</div>
		</div><!-- /.row -->

		<p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoScheduleDesc'); ?></p>
	</div><!-- /.col-md-12 -->
</div>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">			
			<div class="ibox float-e-margins">
				<div class="ibox-content" id="boxSchedule">
					<form action="" method="get" class="frm-filter">
						<div class="row">
							<div class="col-md-3 col-sm-6">
								<div class="form-group">
									<label><?php __('lblBusesOn')?>:</label>
									<a href="javascript:void(0);" class="btn btn-primary btn-outline btnFilter" rev="<?php echo pjDateTime::formatDate(date('Y-m-d'), 'Y-m-d', $tpl['option_arr']['o_date_format'])?>"><?php __('lblToday');?></a>
									<a href="javascript:void(0);" class="btn btn-primary btn-outline btnFilter" rev="<?php echo pjDateTime::formatDate(date('Y-m-d', time() + (24*60*60)), 'Y-m-d', $tpl['option_arr']['o_date_format']);?>"><?php __('lblTomorrow');?></a>
								</div>
							</div>
							<div class="col-md-3 col-sm-6">
								<div class="form-group">
									<div class="input-group">
										<input type="text" name="schedule_date" id="schedule_date" value="<?php echo $current_date;?>" data-wt="open" readonly="readonly" class="form-control datepicker" readonly="readonly" />																		
										<span class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</span>
									</div><!-- /.input-group date -->
								</div>
							</div>
							<div class="col-md-4 col-sm-6 text-right">
		                        <select name="route_id" id="filter_route_id" class="form-control">
		                        	<option value="">-- <?php __('lblFilterByRoute'); ?> --</option>
		                        	<?php
									foreach($tpl['route_arr'] as $k => $v)
									{
										?><option value="<?php echo $v['id'];?>"><?php echo stripslashes($v['route']);?></option><?php
									} 
									?>
		                        </select>
		                    </div>
		                    <div class="col-md-2 col-sm-6 text-right">
		                    	<div class="form-group">
									<a target="_blank" id="bs_print_schedule" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionPrintSchedule&amp;date=<?php echo $current_date;?>" class="btn btn-primary btnPrint"><i class="fa fa-print"></i> <?php __('lblPrintSchedule');?></a>
									<input type="hidden" id="bs_print_href" id="bs_print_href" value="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&action=pjActionPrintSchedule"/>
								</div>
		                    </div>
						</div>
					</form>
					<div id="grid"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var pjGrid = pjGrid || {};
pjGrid.hasAccessCreateBooking = <?php echo pjAuth::factory('pjAdminBookings', 'pjActionCreate')->hasAccess() ? 'true' : 'false';?>;
pjGrid.hasAccessScheduleBooking = <?php echo pjAuth::factory('pjAdminSchedule', 'pjActionBookings')->hasAccess() ? 'true' : 'false';?>;
pjGrid.hasAccessScheduleSeats = <?php echo pjAuth::factory('pjAdminSchedule', 'pjActionSeats')->hasAccess() ? 'true' : 'false';?>;
pjGrid.hasAccessBookings = <?php echo pjAuth::factory('pjAdminBookings', 'pjActionIndex')->hasAccess() ? 'true' : 'false';?>;
pjGrid.hasAccessUpdateBus = <?php echo pjAuth::factory('pjAdminBuses', 'pjActionTime')->hasAccess() ? 'true' : 'false';?>;
pjGrid.hasAccessNotOperatingBus = <?php echo pjAuth::factory('pjAdminBuses', 'pjActionNotOperating')->hasAccess() ? 'true' : 'false';?>;

var myLabel = myLabel || {};
myLabel.bus = <?php x__encode('lblBus'); ?>;
myLabel.departure = "<?php __('lblDeparture'); ?>";
myLabel.arrival = <?php x__encode('lblArrival'); ?>;
myLabel.tickets = <?php x__encode('lblFTTickets'); ?>;
myLabel.total_tickets = <?php x__encode('lblTotalTickets'); ?>;

myLabel.menu = <?php x__encode('lblMenu'); ?>;
myLabel.add_booking = <?php x__encode('btnAddBooking'); ?>;
myLabel.passengers_list = <?php x__encode('lblPassengersList'); ?>;
myLabel.seats_list = <?php x__encode('lblSeatsList'); ?>;
myLabel.print_passengers_list = <?php x__encode('lblPrintPassengersList'); ?>;
myLabel.print_seats_list = <?php x__encode('lblPrintSeatsList'); ?>;
myLabel.view_trip_booking = <?php x__encode('lblViewTripBookings'); ?>;
myLabel.edit_bus = <?php x__encode('lblEditBus'); ?>;
myLabel.cancel_bus = <?php x__encode('lblCancelBus'); ?>;
</script>