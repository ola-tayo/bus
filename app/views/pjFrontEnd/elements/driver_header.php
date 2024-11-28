<nav class="navbar navbar-inverse navbar-static-top pjBsHeader" role="navigation">
	<div class="navbar-header btn-group">
		      <button type="button" class="btn navbar-toggle collapsed" data-toggle="collapse" data-target="#pjBsNavCollapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
		    </div>
	<div class="collapse navbar-collapse" id="pjBsNavCollapse">
		<ul class="nav navbar-nav pjBsNav">
			<?php if ($controller->isLoged()) { ?>
				<li<?php echo $controller->_get->toString('action') == 'pjActionScan' ? ' class="active"' : NULL;?>>
					<a href="<?php echo pjUtil::getReferer(); ?>#!/Scan" class="pjBsNavSelector" data-action="scan">
						<i class="fa fa-qrcode"></i>
						<?php __('front_menu_scan_ticket');?>
					</a>
				</li>
				<?php /*<li<?php echo $controller->_get->toString('action') == 'pjActionScanTicket' ? ' class="active"' : NULL;?>>
					<a href="<?php echo pjUtil::getReferer(); ?>#!/ScanTicket" class="pjBsNavSelector" data-action="scan_ticket">
						<i class="fa fa-code"></i>
						<?php __('front_menu_manual_scan');?>
					</a>
				</li>*/?>
				<li<?php echo $controller->_get->toString('action') == 'pjActionDriverProfile' ? ' class="active"' : NULL;?>>
					<a href="<?php echo pjUtil::getReferer(); ?>#!/Profile" class="pjBsNavSelector" data-action="driver_profile">
						<i class="fa fa-user"></i>
						<?php __('front_menu_profile');?>
					</a>
				</li>
				<li>
					<a href="javascript:void(0);" class="pjBsNavSelector" data-action="drive_logout">
						<i class="fa fa-power-off"></i>
						<?php __('front_menu_logout');?>
					</a>
				</li>
			<?php } else { ?>
				<li>
					<a href="<?php echo PJ_INSTALL_URL;?>index.php?controller=pjAdmin&action=pjActionLogin">
						<i class="fa fa-sign-in"></i>
						<?php __('front_menu_login');?>
					</a>
				</li>
			<?php } ?>
		</ul><!-- /.nav navbar-nav navbar-left pjBsNav -->
	</div>
	<?php if (isset($tpl['locale_arr']) && is_array($tpl['locale_arr']) && !empty($tpl['locale_arr']) && count($tpl['locale_arr']) > 1) { ?>
		<div class="nav navbar-nav navbar-right pjBsNav pjBsHeaderOptions">
			<?php
			$locale_id = $controller->pjActionGetLocale();
			$selected_title = null;
			$selected_src = NULL;
			foreach ($tpl['locale_arr'] as $locale)
			{
				if($locale_id == $locale['id'])
				{
					$selected_title = $locale['language_iso'];
					$lang_iso = explode("-", $selected_title);
					if(isset($lang_iso[1]))
					{
						$selected_title = $lang_iso[1];
					}
					if (!empty($locale['flag']) && is_file(PJ_INSTALL_PATH . $locale['flag']))
					{
						$selected_src = PJ_INSTALL_URL . $locale['flag'];
					} elseif (!empty($locale['file']) && is_file(PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $locale['file'])) {
						$selected_src = PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $locale['file'];
					}
					break;
				}
			}
			?>
			
			<div class="pjBsLanguages">
				<div class="btn-group">
					<button type="button" class="btn btn-default dropdown-toggle pull-right" data-toggle="dropdown" aria-expanded="false">
						<img src="<?php echo $selected_src; ?>" alt="">
						<span class="title"><?php echo $selected_title; ?></span>
						<i class="fa fa-caret-down"></i>
					</button>
				
					<ul class="dropdown-menu" role="menu">
						<?php
						foreach ($tpl['locale_arr'] as $k => $locale)
						{
							$selected_src = NULL;
			            	if (!empty($locale['flag']) && is_file(PJ_INSTALL_PATH . $locale['flag']))
			            	{
			            		$selected_src = PJ_INSTALL_URL . $locale['flag'];
			            	} elseif (!empty($locale['file']) && is_file(PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $locale['file'])) {
			            		$selected_src = PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $locale['file'];
			            	}
							?>
							<li <?php echo $controller->getLocaleId() == $locale['id'] ? ' class="active"' : NULL;?>>
		            			<a href="#" class="bsSelectorLocale" data-id="<?php echo $locale['id']; ?>">
		            				<img src="<?php echo $selected_src; ?>" alt="">
									<?php echo pjSanitize::html($locale['name']); ?>
		            			</a>
		            		</li>
							<?php
						} 
						?>
					</ul><!-- /.dropdown-menu -->
				</div><!-- /.btn-group -->
			</div><!-- /.navbar-form pull-left pjBsLanguages -->
		</div><!-- /.navbar-right pjBsHeaderOptions -->
	<?php } ?>
</nav><!-- /.navbar navbar-default pjBsHeader -->