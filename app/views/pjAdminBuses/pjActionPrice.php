<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <h2><?php __('infoTicketPricesTitle');?></h2>
            </div>
        </div><!-- /.row -->
		<?php $desc = str_replace("{SIZE}", ini_get('post_max_size'), __('infoTicketPricesDesc', true, false));?>
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
    	        case in_array($error_code, array('ABS01', 'ABS03', 'ABS09', 'AS10', 'ABS11', 'ABS12', 'APC01')):
    	            ?>
    				<div class="alert alert-success">
    					<i class="fa fa-check m-r-xs"></i>
    					<strong><?php echo @$titles[$error_code]; ?></strong>
    					<?php echo @$bodies[$error_code]?>
    				</div>
    				<?php
    				break;
                case in_array($error_code, array('ABS04', 'ABS06', 'ABS08', 'ABS10')):
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
	            <li role="presentation"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionNotOperating&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('lblNotOperating'); ?></a></li>
	            <li role="presentation"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionTicket&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('lblTickets'); ?></a></li>
	            <li role="presentation" class="active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionPrice&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('lblPrices'); ?></a></li>
	        </ul>
	       <div class="tab-content">
	            <div role="tabpanel" class="tab-pane active">
	                <div class="panel-body">
	                	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionPrice&amp;id=<?php echo $tpl['arr']['id']?>" method="post" id="frmUpdatePrice" autocomplete="off">
		            		<input type="hidden" name="bus_update" value="1" />
		                    <input type="hidden" id="id" name="id" value="<?php echo $tpl['arr']['id']?>" />
		                    	
							<div class="row form-group">
								<div class="col-sm-6 col-xs-12">
		                    		<div class="form-group">
		                                <label class="control-label"><?php __('lblTicket', false, true); ?></label>
		                                <div>
		                                	<?php if(count($tpl['ticket_arr']) > 0) { ?>
			                                    <select name="ticket_id" id="ticket_id" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>">
													<?php
													foreach ($tpl['ticket_arr'] as $v)
													{
														if(isset($tpl['ticket_id']) && $tpl['ticket_id'] == $v['id'])
														{
															?><option value="<?php echo $v['id']; ?>" selected="selected"><?php echo stripslashes($v['title']); ?></option><?php
														}else{
															?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['title']); ?></option><?php
														}
													}
													?>
												</select>
											<?php } else { ?>
												<div class="text text-danger"><?php __('lblDefineTickets'); ?></div>
											<?php } ?>
		                                </div>
		                                <?php if(count($tpl['ticket_arr']) > 0) { ?>
		                                	<a href="javascript:void(0);" class="pj-copy-ticket"><?php __('lblCopyTicketPrices');?></a>
		                                <?php } ?>
		                            </div><!-- /.form-group -->
                    			</div>
                    			
                    			<div class="col-sm-6 col-xs-12">
		                    		<div class="form-group">
		                                <label class="control-label"><?php __('lblDiscoutIfReturn', false, true); ?></label>
		                                <div>
		                                	<div class="input-group">
												<input type="text" name="discount" id="discount" class="form-control number pj-positive-number" value="<?php echo (float) $tpl['arr']['discount'] > 0 ? $tpl['arr']['discount'] : NULL;?>" data-msg-number="<?php __('prices_invalid_number', false, true);?>" >	
												<span class="input-group-addon">%</span>
											</div>
		                                </div>
		                            </div><!-- /.form-group -->
                    			</div>
							</div>
							
							<div class="bs-loader-outer">
								<div class="bs-loader"></div>
								<div id="bs_price_grid" >
									<?php
									if(isset($tpl['location_arr']))
									{
										include_once PJ_VIEWS_PATH . 'pjAdminBuses/pjActionGetPriceGrid.php';
									} 
									?>
								</div>
							</div>
							
							               						
		                    <div class="hr-line-dashed"></div>
		
		                    <div class="clearfix">
		                    	<?php if(count($tpl['ticket_arr']) > 0) { ?>
			                        <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
			                            <span class="ladda-label"><?php __('btnSave'); ?></span>
			                            <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
			                        </button>
		                        <?php } ?>
		                        <a class="btn btn-white btn-lg pull-right" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminBuses&action=pjActionIndex"><?php __('btnCancel'); ?></a>
		                    </div><!-- /.clearfix -->
		                </form>
	                </div>
	            </div>
	   		</div>
	   	</div>
    </div><!-- /.col-lg-12 -->
</div>

<!-- Modal -->
<div class="modal fade" id="modalCopyPrice" tabindex="-1" role="dialog" aria-labelledby="modalCopyPriceLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<form id="frmCopyPrice" method="post">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="modalCopyPriceLabel"><?php __('lblCopyPrices');?></h4>
	      </div>
	      <div class="modal-body">
	        <div class="form-group">
	            <label class="control-label"><?php __('lblBus');?>:</label>
	
	            <select name="source_bus_id" id="source_bus_id" class="form-control form-control-lg">
	                <option value="">-- <?php __('lblChoose'); ?>--</option>
					<?php
					foreach ($tpl['bus_arr'] as $k => $v)
					{
						?><option value="<?php echo $v['id']; ?>"><?php echo $v['route']; ?>, <?php echo pjDateTime::formatTime(date('H:i:s', strtotime($v['departure_time'])), "H:i:s", $tpl['option_arr']['o_time_format']) . ' - ' . pjDateTime::formatTime(date('H:i:s', strtotime($v['arrival_time'])), "H:i:s", $tpl['option_arr']['o_time_format']); ?></option><?php
					}
					?>
	            </select>
	        </div>
	        <div id="ticketTypeBox"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"><?php __('btnCancel');?></button>
	        <button type="button" class="ladda-button btn btn-primary btn-phpjabbers-loader btnCopyPrice" data-style="zoom-in" style="margin-right: 15px;">
				<span class="ladda-label"><?php __('btnCopy'); ?></span>
				<?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
			</button>
	      </div>
	    </form>
    </div>
  </div>
</div>

<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.field_required = "<?php __('fd_field_required'); ?>";
myLabel.buses = <?php echo count($tpl['bus_arr']);?>;
myLabel.alert_no_copy_price_text = <?php x__encode('lblNoCopyPrice'); ?>;
myLabel.btn_close = <?php x__encode('btnClose'); ?>;
</script>