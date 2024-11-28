<?php
$months = __('months', true);
ksort($months);
$short_days = __('short_days', true);
?>
<div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-format="<?php echo $tpl['date_format']; ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <h2><?php __('infoNotOperatingTitle');?></h2>
            </div>
        </div><!-- /.row -->
		<?php $desc = str_replace("{SIZE}", ini_get('post_max_size'), __('infoNotOperatingDesc', true, false));?>
        <p class="m-b-none"><i class="fa fa-info-circle"></i><?php echo $desc;?></p>
    </div><!-- /.col-md-12 -->
</div>
<div class="row wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-12">
    	<?php
    	$error_code = $controller->_get->toString('err');
    	if (!empty($error_code))
    	{
    	    $titles = __('error_titles', true);
    	    $bodies = __('error_bodies', true);
    	    switch (true)
    	    {
    	        case in_array($error_code, array('ABS01', 'ABS03', 'ABS11', 'ABS12')):
    	            ?>
    				<div class="alert alert-success">
    					<i class="fa fa-check m-r-xs"></i>
    					<strong><?php echo @$titles[$error_code]; ?></strong>
    					<?php echo @$bodies[$error_code]?>
    				</div>
    				<?php
    				break;
                case in_array($error_code, array('ABS04', 'ABS06', 'ABS08', 'ABS09', 'ABS10')):
    				?>
    				<div class="alert alert-danger">
    					<i class="fa fa-exclamation-triangle m-r-xs"></i>
    					<strong><?php echo @$titles[$error_code]; ?></strong>
    					<?php echo @$bodies[$error_code]?>
    				</div>
    				<?php
    				break;
    		}
    	}
    	?>
    	<h3><?php __('lblRoute'); ?>: <?php echo pjSanitize::html(@$tpl['route_arr']['title']);?></h3>
    	<div class="tabs-container tabs-reservations m-b-lg">
			<ul class="nav nav-tabs" role="tablist">
	            <li role="presentation"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionTime&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('lblTimes');?></a></li>
	            <li role="presentation" class="active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionNotOperating&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('lblNotOperating'); ?></a></li>
	            <li role="presentation"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionTicket&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('lblTickets'); ?></a></li>
	            <li role="presentation" class=""><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionPrice&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('lblPrices'); ?></a></li>
	        </ul>
	       <div class="tab-content">
	            <div role="tabpanel" class="tab-pane active">
	                <div class="panel-body">
	                	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionNotOperating&amp;id=<?php echo $tpl['arr']['id']?>" method="post" id="frmNotOperating" autocomplete="off">
		            		<input type="hidden" name="bus_update" value="1" />
		                    <input type="hidden" id="id" name="id" value="<?php echo $tpl['arr']['id']?>" />
		                    <div class="form-group">
		                    	<label class="control-label"><?php __('lblNotOperatingOn');?></label>
		                    </div>
							<div id="bs_date_container" class="row form-group">
								<?php
								foreach($tpl['date_arr'] as $v)
								{
									?>
									<div class="com-lg-3 col-md-4 col-sm-6 pj-date-item">
										<div class="form-group clearfix">
											<div class="input-group"> 
												<input type="text" name="date[]" value="<?php echo date($tpl['option_arr']['o_date_format'], strtotime($v['date']));?>" class="form-control datepick" readonly="readonly" data-msg-required="<?php __('fd_field_required');?>" /> 
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>
											<a class="pj-button-remove-date btn btn-danger btn-outline btn-sm" href="javascript: void(0)"><i class="fa fa-trash" aria-hidden="true"></i></a>
										</div>
									</div>
									<?php
								}
								if($controller->_get->check('date') && !$controller->_get->isEmpty('date'))
								{
									?>
									<div class="com-lg-3 col-md-4 col-sm-6 pj-date-item">
										<div class="form-group clearfix">
											<div class="input-group"> 
												<input type="text" name="date[]" value="<?php echo $controller->_get->toString('date');?>" class="form-control datepick" readonly="readonly" data-msg-required="<?php __('fd_field_required');?>" /> 
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>
											<a class="pj-button-remove-date btn btn-danger btn-outline btn-sm" href="javascript: void(0)"><i class="fa fa-trash" aria-hidden="true"></i></a>
										</div>
									</div>
									<?php
								} 
								?>
							</div>
							<div class="alert alert-info pjBrsNoDates" style="display: none;"><?php __('lblNoDatesAdded');?></div>		     
							<div class="text-center"><a href="javascript:void(0);" class="btn btn-primary pj-button-add-date"><i class="fa fa-plus"></i> <?php __('btnAdd');?></a></div>               						
		                    <div class="hr-line-dashed"></div>
		
		                    <div class="clearfix">
		                        <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
		                            <span class="ladda-label"><?php __('btnSave'); ?></span>
		                            <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
		                        </button>
		                        <a class="btn btn-white btn-lg pull-right" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminBuses&action=pjActionIndex"><?php __('btnCancel'); ?></a>
		                    </div><!-- /.clearfix -->
		                </form>
	                </div>
	            </div>
	   		</div>
	   	</div>
    </div><!-- /.col-lg-12 -->
</div>

<div id="bs_date_clone" style="display:none;">
	<div class="com-lg-3 col-md-4 col-sm-6 pj-date-item">
		<div class="form-group clearfix">
			<div class="input-group"> 
				<input type="text" name="date[]" class="form-control datepick" readonly="readonly" data-msg-required="<?php __('fd_field_required');?>" /> 
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
			<a class="pj-button-remove-date btn btn-danger btn-outline btn-sm" href="javascript: void(0)"><i class="fa fa-trash" aria-hidden="true"></i></a>
		</div>
	</div>
</div>

<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.field_required = "<?php __('fd_field_required'); ?>";
</script>