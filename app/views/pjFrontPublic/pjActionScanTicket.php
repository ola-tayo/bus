<?php 
$validate = str_replace(array('"', "'"), array('\"', "\'"), __('validate', true, true));
$index = $controller->_get->toString('index');
?>
<div class="container-fluid pjBsDriverWrap">
	<?php include_once PJ_VIEWS_PATH . 'pjFrontEnd/elements/driver_header.php';?>
	<div class="panel panel-default pjBsMain">
		<div class="panel-body pjBsBody">
		<div class="row">
			<div class="col-lg-6 col-md-8 col-lg-offset-3 col-md-offset-2 col-sm-12">
				<form id="pjBsScanTicketForm_<?php echo $index;?>" action="#" method="post" autocomplete="off">
					<input type="hidden" name="is_scan_ticket" value="1" />					
						<h2 class="text-center"><?php __('front_scan_ticket_manually');?></h2>
						<div class="form-horizontal">
							<div class="form-group required">
								<label class="col-sm-5 col-xs-12 control-label"><?php __('front_authenticate_code'); ?></label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
								<div class="col-sm-7 col-xs-12">
									<input type="text" name="authenticate_code" class="form-control required" data-msg-required="<?php echo $validate['authenticate_code'];?>" />
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
								</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
							</div><!-- /.form-group -->
							<div class="form-group">
								<label class="col-sm-5 col-xs-12 control-label">&nbsp;</label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
								<div class="col-sm-7 col-xs-12">
									<button type="submit" class="btn btn-primary"><?php __('front_btn_validate');?></button>
								</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
							</div><!-- /.form-group -->
							<div class="form-group">
								<label class="col-sm-5 col-xs-12 control-label">&nbsp;</label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
								<div class="col-sm-7 col-xs-12">
									<div id="pjBsScanTicketMsg_<?php echo $index;?>"></div>
								</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
							</div><!-- /.form-group -->
							
						</div>
					</form>
				</div>
			</div>
		</div>
	</div><!-- panel panel-default pjBsMain -->
</div><!-- /.container-fluid -->