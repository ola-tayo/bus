<?php 
$filter = __('filter', true);
$_yesno_arr = __('_yesno', true, false); 
?>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-10">
				<h2><?php __('infoBusTypesTitle'); ?></h2>
			</div>
		</div><!-- /.row -->

		<p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoBusTypesDesc'); ?></p>
	</div><!-- /.col-md-12 -->
</div>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<?php
	    	$error_code = $controller->_get->toString('err');
	    	if (!empty($error_code))
	    	{
	    	    $titles = __('error_titles', true);
	    	    $bodies = __('error_bodies', true);
	    	    switch (true)
	    	    {
	    	        case in_array($error_code, array('ABT01', 'ABT03')):
	    	            ?>
	    				<div class="alert alert-success">
	    					<i class="fa fa-check m-r-xs"></i>
	    					<strong><?php echo @$titles[$error_code]; ?></strong>
	    					<?php echo @$bodies[$error_code]?>
	    				</div>
	    				<?php
	    				break;
	                case in_array($error_code, array('ABT04', 'ABT05', 'ABT06', 'ABT08', 'ABT09', 'ABT10', 'ABT11', 'ABT12')):
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
			<div class="ibox float-e-margins">
				<div class="ibox-content">
					<div class="row m-b-md">
						<?php if (pjAuth::factory('pjAdminBusTypes', 'pjActionCreate')->hasAccess()) { ?>
							<div class="col-md-4">
								<a href="<?php echo $_SERVER['PHP_SELF'].'?controller=pjAdminBusTypes&action=pjActionCreate'?>" class="btn btn-primary"><i class="fa fa-plus"></i> <?php __('lblAddBusType');?></a>
							</div><!-- /.col-md-6 -->
						<?php } ?>
						<div class="col-md-4 col-sm-8">
							<form action="" method="get" class="form-horizontal frm-filter">
	                            <div class="input-group">
	                                <input type="text" name="q" placeholder="<?php __('plugin_base_btn_search', false, true); ?>" class="form-control">
	                                <div class="input-group-btn">
	                                    <button class="btn btn-primary" type="submit">
	                                        <i class="fa fa-search"></i>
	                                    </button>
	                                </div>
	                            </div>
	                        </form>
						</div><!-- /.col-md-3 -->
						<div class="col-md-4 text-right">
	                        <div class="btn-group" role="group" aria-label="...">
	                            <button type="button" class="btn btn-primary btn-all active"><?php __('lblAll'); ?></button>
	                            <button type="button" class="btn btn-default btn-filter" data-column="status" data-value="T"><i class="fa fa-check m-r-xs"></i><?php echo $filter['active']; ?></button>
	                            <button type="button" class="btn btn-default btn-filter" data-column="status" data-value="F"><i class="fa fa-times m-r-xs"></i><?php echo $filter['inactive']; ?></button>
	                        </div>
	                    </div>
					</div><!-- /.row -->
					<div id="grid"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var pjGrid = pjGrid || {};
pjGrid.hasAccessUpdate = <?php echo pjAuth::factory('pjAdminBusTypes', 'pjActionUpdate')->hasAccess() ? 'true' : 'false';?>;
pjGrid.hasAccessDeleteSingle = <?php echo pjAuth::factory('pjAdminBusTypes', 'pjActionDelete')->hasAccess() ? 'true' : 'false';?>;
pjGrid.hasAccessDeleteMulti = <?php echo pjAuth::factory('pjAdminBusTypes', 'pjActionDeleteBulk')->hasAccess() ? 'true' : 'false';?>;
var myLabel = myLabel || {};
myLabel.name = <?php x__encode('lblName'); ?>;
myLabel.map = <?php x__encode('lblMap');?>;
myLabel.seats = <?php x__encode('lblSeats');?>;
myLabel.status = <?php x__encode('lblStatus'); ?>;
myLabel.active = <?php x__encode('filter_ARRAY_active'); ?>;
myLabel.inactive = <?php x__encode('filter_ARRAY_inactive'); ?>;
myLabel.yes = "<?php echo $_yesno_arr['T']; ?>";
myLabel.no = "<?php echo $_yesno_arr['F']; ?>";
myLabel.delete_selected = <?php x__encode('delete_selected', true); ?>;
myLabel.delete_confirmation = <?php x__encode('delete_confirmation', true); ?>;
</script>