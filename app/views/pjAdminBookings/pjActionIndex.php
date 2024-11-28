<?php
$titles = __('error_titles', true);
$bodies = __('error_bodies', true);
$months = __('months', true);
ksort($months);
$short_days = __('short_days', true);
$statuses = __('booking_statuses', true, false);
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-10">
                <h2><?php __('infoBookingListTitle')?></h2>
            </div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoBookingListDesc')?></p>
    </div><!-- /.col-md-12 -->
</div>
<div class="row wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
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
		?>
		<div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-format="<?php echo pjUtil::toBootstrapDate($tpl['option_arr']['o_date_format']); ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<form method="get" class="frm-filter">
					<div class="row m-b-md">
						<div class="col-sm-3">
							<?php
							if(pjAuth::factory('pjAdminBookings', 'pjActionCreate')->hasAccess())
							{
								?>
								<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate" class="btn btn-primary"><i class="fa fa-plus"></i> <?php __('btnAddBooking'); ?></a>
								<?php
							}
							?>
						</div><!-- /.col-md-6 -->
			
						<div class="col-md-3 col-sm-5">
							<div class="input-group">
								<input type="text" name="q" placeholder="<?php __('plugin_base_btn_search', false, true); ?>" class="form-control">
			
								<div class="input-group-btn">
									<button class="btn btn-primary" type="submit">
										<i class="fa fa-search"></i>
									</button>
								</div>
							</div>
						</div><!-- /.col-md-3 -->
			
						<div class="col-lg-2 col-md-3 col-sm-4">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="btn btn-primary btn-outline btn-advance-search"><?php __('btnAdvancedSearch'); ?></a>
						</div><!-- /.col-md-2 -->
			
						<div class="col-lg-2 col-lg-offset-2 col-md-12 text-right">
							<select id="filter_status" name="status" class="form-control">
								<option value="">-- <?php __('lblAll');?> --</option>
								<option value="confirmed"><?php echo $statuses['confirmed'];?></option>
								<option value="pending"><?php echo $statuses['pending'];?></option>
								<option value="cancelled"><?php echo $statuses['cancelled'];?></option>
							</select>
						</div><!-- /.col-md-6 -->
					</div><!-- /.row -->
				</form>
				<div id="collapseOne" class="collapse" style="height: 0;" aria-expanded="false">
					<div class="m-b-lg">
						<ul class="agile-list no-padding">
							<li class="success-element b-r-sm">
							<div class="panel-body">
								<form method="get" class="frm-filter-advanced">
									
									<div class="row">
										<div class="col-lg-3 col-md-6">
											<div class="form-group">
												<label class="control-label"><?php __('lblFrom'); ?></label>
												<div class="input-group date datepicker">
													<input class="form-control" type="text" name="date_from" id="date_from" autocomplete="off">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-6">
											<div class="form-group">
												<label class="control-label"><?php __('lblTo'); ?></label>
												<div class="input-group date datepicker">
													<input class="form-control" type="text" name="date_to" id="date_to" autocomplete="off">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-6">
											<div class="form-group">
												<label class="control-label"><?php __('lblRoute'); ?></label>
												<select name="route_id" id="route_id" class="form-control">
													<option value="">-- <?php __('lblChoose'); ?>--</option>
													<?php
													foreach($tpl['route_arr'] as $k => $v)
													{
														?><option value="<?php echo $v['id'];?>"><?php echo pjSanitize::clean($v['route']);?></option><?php
													} 
													?>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-6">
											<div class="form-group">
												<label class="control-label"><?php __('lblBus'); ?></label>
												<select name="bus_id" id="filter_bus_id" class="form-control">
													<option value="">-- <?php __('lblChoose'); ?>--</option>
													<?php
													foreach ($tpl['bus_arr'] as $k => $v)
													{
														?><option value="<?php echo $v['id']; ?>"><?php echo $v['route']; ?>, <?php echo $v['depart_arrive']; ?></option><?php
													}
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="m-t-sm">
										<button class="btn btn-primary" type="submit"><?php __('btnSearch');?></button>
										<button class="btn btn-primary btn-outline" type="reset"><?php __('btnCancel');?></button>
									</div>
								</form>
							</div>
							<!-- /.panel-body -->
							</li>
							<!-- /.panel panel-primary -->
						</ul>
					</div>
					<!-- /.m-b-lg -->
				</div>
				
				<div id="grid" class="pj-grid"></div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
var pjGrid = pjGrid || {};
pjGrid.hasUpdate = <?php echo pjAuth::factory('pjAdminBookings', 'pjActionUpdate')->hasAccess() ? 'true' : 'false'; ?>;
pjGrid.hasDeleteSingle = <?php echo pjAuth::factory('pjAdminBookings', 'pjActionDeleteReservation')->hasAccess() ? 'true' : 'false'; ?>;
pjGrid.hasDeleteMulti = <?php echo pjAuth::factory('pjAdminBookings', 'pjActionDeleteReservationBulk')->hasAccess() ? 'true' : 'false'; ?>;
pjGrid.hasExport = <?php echo pjAuth::factory('pjAdminBookings', 'pjActionExportBooking')->hasAccess() ? 'true' : 'false'; ?>;
pjGrid.queryString = "";
<?php
if ($controller->_get->check('bus_id') && $controller->_get->toInt('bus_id') > 0)
{
    ?>pjGrid.queryString += "&bus_id=<?php echo $controller->_get->toInt('bus_id'); ?>";<?php
}
if ($controller->_get->check('route_id') && $controller->_get->toInt('route_id') > 0)
{
    ?>pjGrid.queryString += "&route_id=<?php echo $controller->_get->toInt('route_id'); ?>";<?php
}
?>
var myLabel = myLabel || {};
myLabel.client = <?php x__encode('lblClient'); ?>;
myLabel.date_time = "<?php __('lblDateTime'); ?>";
myLabel.uuid = <?php x__encode('lblUniqueID'); ?>;
myLabel.bus_route = <?php x__encode('lblBusRoute'); ?>;
myLabel.email = <?php x__encode('email'); ?>;
myLabel.status = "<?php __('lblStatus'); ?>";
myLabel.pending = "<?php echo $statuses['pending']; ?>";
myLabel.confirmed = "<?php echo $statuses['confirmed']; ?>";
myLabel.cancelled = "<?php echo $statuses['cancelled']; ?>";
myLabel.exportSelected = <?php x__encode('lblExportSelected'); ?>;
myLabel.delete_selected = <?php x__encode('plugin_base_delete_selected'); ?>;
myLabel.delete_confirmation = <?php x__encode('plugin_base_delete_confirmation'); ?>;
</script>