<?php 
$index = $controller->_get->toString('index');
?>
<div class="container-fluid pjBsDriverWrap">
	<?php include_once PJ_VIEWS_PATH . 'pjFrontEnd/elements/driver_header.php';?>
	<div class="panel panel-default pjBsMain">
		<div class="panel-body pjBsBody">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 col-sm-12">
					<div id="qr-reader" class="form-group"></div>			
					
					<div id="qr-input-file-reader" class="form-group" style="display: none;">
						<input type="file" class="form-control" id="qr-input-file" accept="image/*">
					</div>
					
					<div align="center" class="form-group">
						<button class="bsBtnStartScanning btn btn-primary"><?php __('button_start_scanning');?></button>
						<?php /*<button class="bsBtnScanAnImageFile btn btn-primary"><?php __('button_scan_image_file');?></button>*/?>
						<button class="bsBtnStopScanning btn btn-default" style="display: none;"><?php __('button_stop_scanning');?></button>
					</div>
    				<div id="qr-reader-results"></div>
				</div>
			</div>
		</div>
	</div><!-- panel panel-default pjBsMain -->
</div><!-- /.container-fluid -->