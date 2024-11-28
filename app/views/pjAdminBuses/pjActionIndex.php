<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-10">
				<h2><?php __('infoBusesTitle'); ?></h2>
			</div>
		</div><!-- /.row -->

		<p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoBusesDesc'); ?></p>
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
	    	        case in_array($error_code, array('ABS01', 'ABS03')):
	    	            ?>
	    				<div class="alert alert-success">
	    					<i class="fa fa-check m-r-xs"></i>
	    					<strong><?php echo @$titles[$error_code]; ?></strong>
	    					<?php echo @$bodies[$error_code]?>
	    				</div>
	    				<?php
	    				break;
	                case in_array($error_code, array('ABS04', 'ABS05', 'ABS06', 'ABS08', 'ABS09', 'ABS10', 'ABS11', 'ABS12')):
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
						<?php if (pjAuth::factory('pjAdminBuses', 'pjActionCreate')->hasAccess()) { ?>
							<div class="col-md-3">
								<a href="<?php echo $_SERVER['PHP_SELF'].'?controller=pjAdminBuses&action=pjActionCreate'?>" class="btn btn-primary"><i class="fa fa-plus"></i> <?php __('lblAddBus');?></a>
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
						<div class="col-md-5 text-right">
	                        <select name="route_id" id="filter_route_id" class="form-control">
	                        	<option value="">-- <?php __('lblFilterByRoute'); ?> --</option>
	                        	<?php
								foreach($tpl['route_arr'] as $k => $v)
								{
									?><option value="<?php echo $v['id'];?>"><?php echo pjSanitize::clean($v['route']);?></option><?php
								} 
								?>
	                        </select>
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
pjGrid.hasAccessUpdate = <?php echo pjAuth::factory('pjAdminBuses', 'pjActionUpdate')->hasAccess() ? 'true' : 'false';?>;
pjGrid.hasAccessDeleteSingle = <?php echo pjAuth::factory('pjAdminBuses', 'pjActionDelete')->hasAccess() ? 'true' : 'false';?>;
pjGrid.hasAccessDeleteMulti = <?php echo pjAuth::factory('pjAdminBuses', 'pjActionDeleteBulk')->hasAccess() ? 'true' : 'false';?>;

var myLabel = myLabel || {};
myLabel.route = <?php x__encode('lblRoute'); ?>;
myLabel.from_to = <?php x__encode('lblFromTo');?>;
myLabel.depart_arrive = <?php x__encode('lblDepartArrive');?>;
myLabel.delete_selected = <?php x__encode('delete_selected', true); ?>;
myLabel.delete_confirmation = <?php x__encode('delete_confirmation', true); ?>;
</script>