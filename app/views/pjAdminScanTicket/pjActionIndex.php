<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-10">
				<h2><?php __('infoScanTicketTitle'); ?></h2>
			</div>
		</div><!-- /.row -->

		<p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoScanTicketDesc'); ?></p>
	</div><!-- /.col-md-12 -->
</div>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-content">
					<div class="m-b-md">
						<div class="b10 pjBsDriverWrap">
                    		<div id="qr-reader"></div>
                    		<div id="qr-input-file-reader" style="display: none;" align="center">
                    			<input type="file" class="form-controller" id="qr-input-file" accept="image/*">
                    		</div>
                    		
                    		<div align="center">
                    			<button class="bsBtnStartScanning btn btn-primary"><?php __('button_start_scanning');?></button>
                    			<?php /*<button class="bsBtnScanAnImageFile pj-button"><?php __('button_scan_image_file');?></button>*/?>
                    			<button class="bsBtnStopScanning btn btn-danger" style="display: none;"><?php __('button_stop_scanning');?></button>
                    		</div>
                    		
                    		<div id="qr-reader-results" style="display: none;"></div>
                    	</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>