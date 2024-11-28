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
				<h2><?php __('infoRouteTimetableTitle'); ?></h2>
			</div>
		</div><!-- /.row -->

		<p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoRouteTimetableDesc'); ?></p>
	</div><!-- /.col-md-12 -->
</div>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">			
			<div class="ibox float-e-margins">
				<div class="ibox-content">
					<form action="" method="get" class="frm-filter">
						<div class="row">
							<label class="col-lg-2 col-md-4 col-sm-3 text-right"><?php __('lblSelectRoute')?>:</label>
							<div class="col-lg-4 col-md-4 col-sm-3">		
								<div class="form-group">						
			                        <select name="route_id" id="route_id" class="form-control">
			                        	<?php
										foreach($tpl['route_arr'] as $k => $v)
										{
											?><option value="<?php echo $v['id'];?>"><?php echo stripslashes($v['route']);?></option><?php
										} 
										?>
			                        </select>
		                       	</div>
		                    </div>
							<div class="col-lg-3 col-md-4 col-sm-3">
								<div class="form-group">
									<div class="input-group">
										<input type="text" name="selected_date" id="selected_date" value="<?php echo $current_date;?>" data-wt="open" readonly="readonly" class="form-control datepicker" readonly="readonly" />																		
										<span class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</span>
									</div><!-- /.input-group date -->
								</div>
							</div>
						</div>
					</form>
					<div class="bs-loader-outer">
						<div class="bs-loader"></div>
						<div id="boxTimetable"><?php include PJ_VIEWS_PATH . 'pjAdminSchedule/elements/getTimetable.php'; ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>