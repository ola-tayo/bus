<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-6">
                <h2><?php __('infoUpdateTicketTitle');?></h2>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 btn-group-languages">
                <?php if ($tpl['is_flag_ready']) : ?>
				<div class="multilang"></div>
				<?php endif; ?>
        	</div>
        </div><!-- /.row -->
		<?php $desc = str_replace("{SIZE}", ini_get('post_max_size'), __('infoUpdateTicketDesc', true, false));?>
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
    	        case in_array($error_code, array('ABS01', 'ABS03', 'ABS09', 'ABS11', 'ABS12')):
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
	            <li role="presentation" class="active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionTicket&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('lblTickets'); ?></a></li>
	            <li role="presentation"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionPrice&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('lblPrices'); ?></a></li>
	        </ul>
	       <div class="tab-content">
	            <div role="tabpanel" class="tab-pane active">
	                <div class="panel-body">
	                	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionTicket&amp;id=<?php echo $tpl['arr']['id']?>" method="post" id="frmUpdateTicket" autocomplete="off">
		            		<input type="hidden" name="bus_update" value="1" />
		                    <input type="hidden" id="id" name="id" value="<?php echo $tpl['arr']['id']?>" />
		                    <input type="hidden" id="index_arr" name="index_arr" value="" />
							<input type="hidden" id="remove_arr" name="remove_arr" value="" />
							<input type="hidden" id="seats_available" name="seats_available" value="<?php echo $tpl['seats_available'];?>" />
							<div class="row form-group">
								<label class="control-label col-lg-3 col-md-4 col-sm-6"><?php __('lblSeatsAvailable'); ?>:</label>							
								<div class="col-lg-9 col-md-8 col-sm-6"><?php echo !empty($tpl['seats_available']) ? $tpl['seats_available'] : 0;?></div>
							</div>	
							
							<div class="row form-group" style="display: none;">
								<?php $_yesno = __('_yesno', true);?>
								<label class="control-label col-lg-3 col-md-4 col-sm-6"><?php __('lblSetSeatsCount'); ?>:</label>							
								<div class="col-lg-9 col-md-8 col-sm-6">
									<div class="clearfix">
						                <div class="switch onoffswitch-data pull-left">
						                    <div class="onoffswitch onoffswitch-seats-count">
						                        <input type="checkbox" class="onoffswitch-checkbox" id="set_seats_count" name="set_seats_count"<?php echo $tpl['arr']['set_seats_count'] == 'T' ? ' checked="checked"' : null;?>>
						                        <label class="onoffswitch-label" for="set_seats_count">
						                            <span class="onoffswitch-inner" data-on="<?php echo $_yesno['T']; ?>" data-off="<?php echo $_yesno['F']; ?>"></span>
						                            <span class="onoffswitch-switch"></span>
						                        </label>
						                    </div>
						                </div>
						            </div><!-- /.clearfix -->
								</div>
							</div>						

							<div class="row">
		                        <div class="col-md-8 col-sm-9">
		                            <div class="bs-ticket-list">
		                                <div class="table-responsive table-responsive-secondary">
		                                    <table class="table table-striped table-hover">
		                                        <thead>
		                                            <tr>
		                                            	<th>&nbsp;</th>
		                                            	<th class="pj-ticket-count <?php echo $tpl['arr']['set_seats_count'] == 'F' ? ' pj-hide-count' : null;?>"><?php __('lblType');?></th>
		                                            	<th class="pj-ticket-count <?php echo $tpl['arr']['set_seats_count'] == 'F' ? ' pj-hide-count' : null;?>"><?php __('lblCount');?></th>
		                                                <th>&nbsp;</th>
		                                            </tr>
		                                        </thead>		
		                                        <tbody id="bs_ticket_list">
		                                        	<?php if(count($tpl['ticket_arr']) > 0) { ?>
		                                        		<?php foreach($tpl['ticket_arr'] as $k => $ticket) { ?>
				                                            <tr class="bs-ticket-row" data-index="<?php echo $ticket['id'];?>">
				                                            	<td><label class="title bs-title-<?php echo $ticket['id'];?>"><?php __('lblTicket'); ?> <?php echo $k + 1;?>:</label></td>
				                                                <td>
				                                                    <?php
				                                                	foreach ($tpl['lp_arr'] as $v)
				                                                	{
				                                                    	?>
				                                                        <div class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
				                                                            <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group' : '';?>" data-index="<?php echo $v['id']; ?>">
				                        										<input type="text" name="i18n[<?php echo $v['id']; ?>][title][<?php echo $ticket['id'];?>]" class="form-control<?php echo (int) $v['is_default'] === 0 ? NULL : ' fdRequired'; ?>" value="<?php echo htmlspecialchars(stripslashes(@$ticket['i18n'][$v['id']]['title'])); ?>" lang="<?php echo $v['id']; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>"/>	
				                        										<?php if ($tpl['is_flag_ready']) : ?>
				                        										<span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
				                        										<?php endif; ?>
				                        									</div>
				                                                        </div>
				                                                        <?php
				                                                    }
				                                                    ?>
				                                                </td>		
				                                                <td class="pj-ticket-count <?php echo $tpl['arr']['set_seats_count'] == 'F' ? ' pj-hide-count' : null;?>">
				                                                	<input type="text" name="seats_count[<?php echo $ticket['id'];?>]" class="form-control ticket-count touchspin3" value="<?php echo $ticket['seats_count']; ?>"/>
				                                                </td>										
				                                                <td>
				                                                	<?php if ($k > 0) { ?>
				                                                		<div class=" text-right">
														                    <a href="javascript:void(0);" class="btn btn-danger btn-outline btn-sm pj-remove-ticket"><i class="fa fa-trash"></i></a>
														                </div>
				                                                	<?php } else { ?>
				                                                    	&nbsp;
				                                                    <?php } ?>
				                                                </td>
				                                            </tr>
				                                    	<?php } ?>
			                                    	<?php } else { 
			                                    		$index = 'bs_' . rand(1, 999999);
			                                    		?>
			                                    		<tr class="bs-ticket-row" data-index="<?php echo $index;?>">
			                                    			<td><label class="title bs-title-<?php echo $index;?>"><?php __('lblTicket'); ?> <?php echo 1;?>:</label></td>
			                                                <td>
			                                                    <?php
			                                                	foreach ($tpl['lp_arr'] as $v)
			                                                	{
			                                                    	?>
			                                                        <div class=" pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
			                                                            <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group' : '';?>" data-index="<?php echo $v['id']; ?>">
			                        										<input type="text" name="i18n[<?php echo $v['id']; ?>][title][<?php echo $index;?>]" class="form-control<?php echo (int) $v['is_default'] === 0 ? NULL : ' fdRequired'; ?>" lang="<?php echo $v['id']; ?>" data-msg-required="<?php __('cbs_field_required', false, true);?>"/>	
			                        										<?php if ($tpl['is_flag_ready']) : ?>
			                        										<span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
			                        										<?php endif; ?>
			                        									</div>
			                                                        </div>
			                                                        <?php
			                                                    }
			                                                    ?>
			                                                </td>		
			                                                <td class="pj-ticket-count <?php echo $tpl['arr']['set_seats_count'] == 'F' ? ' pj-hide-count' : null;?>">
			                                                	<input type="text" name="seats_count[<?php echo $index;?>]" class="form-control ticket-count touchspin3" />
			                                                </td>										
			                                                <td>
			                                                    &nbsp;
			                                                </td>
			                                            </tr>
			                                    	<?php } ?>
		                                        </tbody>
		                                    </table>
		                                </div>
		                            </div>
		                        </div><!-- /.col-sm-7 -->
		
		                        <div class="col-md-4 col-sm-3">
		                            <div class="m-t-lg">
		                                <a href="javascript:void(0);" class="btn btn-primary btn-outline m-t-xs pj-add-ticket"><i class="fa fa-plus"></i> <?php __('btnAdd'); ?></a>
		                            </div>
		                        </div><!-- /.col-sm-5 -->
		                    </div>
		                                   						
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

<table style="display:none;">
    <tbody id="bs_ticket_clone">
        <tr class="bs-ticket-row" data-index="{INDEX}"> 
        	<td><label class="title bs-title-{INDEX}"><?php __('lblTicket'); ?> {ORDER}:</label></td>           
            <td>
                <?php
            	foreach ($tpl['lp_arr'] as $v)
            	{
                	?>
                    <div class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
                        <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group' : '';?>" data-index="<?php echo $v['id']; ?>">
							<input type="text" name="i18n[<?php echo $v['id']; ?>][title][{INDEX}]" class="form-control<?php echo (int) $v['is_default'] === 0 ? NULL : ' fdRequired'; ?>" lang="<?php echo $v['id']; ?>" data-msg-required="<?php __('cbs_field_required', false, true);?>"/>	
							<?php if ($tpl['is_flag_ready']) : ?>
							<span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
							<?php endif; ?>
						</div>
                    </div>
                    <?php
                }
                ?>
            </td>
            <td class="pj-ticket-count <?php echo $tpl['arr']['set_seats_count'] == 'F' ? ' pj-hide-count' : null;?>">
				<input type="text" name="seats_count[{INDEX}]" class="form-control ticket-count touchspin3-{INDEX}" />
			</td>
            <td>
                <div class=" text-right">
                    <a href="javascript:void(0);" class="btn btn-danger btn-outline btn-sm pj-remove-ticket"><i class="fa fa-trash"></i></a>
                </div>
            </td>
        </tr>

    </tbody>
</table>

<?php if ($tpl['is_flag_ready']) : ?>
<script type="text/javascript">
var pjCmsLocale = pjCmsLocale || {};
pjCmsLocale.langs = <?php echo $tpl['locale_str']; ?>;
pjCmsLocale.flagPath = "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/";
</script>
<?php endif; ?>
<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.localeId = "<?php echo $controller->getLocaleId(); ?>";
myLabel.field_required = "<?php __('fd_field_required'); ?>";
myLabel.validate = "<?php __('lblValidate'); ?>";
myLabel.equalTo = "<?php __('lblEqualTo'); ?>";
myLabel.ticket = "<?php __('lblTicket'); ?>";
myLabel.btn_close = <?php x__encode('btnClose'); ?>;
</script>