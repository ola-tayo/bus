<?php
$controller_name = $controller->_get->toString('controller');
$action_name = $controller->_get->toString('action');

// Dashboard
$isScriptDashboard = in_array($controller_name, array('pjAdmin')) && in_array($action_name, array('pjActionIndex'));

// Schedule
$isScriptScheduleController       = in_array($controller_name, array('pjAdminSchedule'));
$isScriptScheduleIndexController = $isScriptScheduleController && in_array($action_name, array('pjActionIndex', 'pjActionBookings', 'pjActionSeats'));
$isScriptScheduleTimetableController = $isScriptScheduleController && in_array($action_name, array('pjActionTimetable'));

// Bookings
$isScriptBookingsController = in_array($controller_name, array('pjAdminBookings'));
$isScriptBookings = $isScriptBookingsController && in_array($action_name, array('pjActionIndex', 'pjActionCreate', 'pjActionUpdate'));

// Scan Ticket
$isScriptScanTicket = in_array($controller_name, array('pjAdminScanTicket')) && in_array($action_name, array('pjActionIndex'));

// Buses
$isScriptBusesController       = in_array($controller_name, array('pjAdminBuses'));

// Cities
$isScriptCitiesController       = in_array($controller_name, array('pjAdminCities'));

// Routes
$isScriptRoutesController     = in_array($controller_name, array('pjAdminRoutes'));

// Bus Types
$isScriptBusTypesController     = in_array($controller_name, array('pjAdminBusTypes'));

// Payments
$isScriptPaymentsController = in_array($controller_name, array('pjPayments'));

// Settings
$isScriptOptionsController = in_array($controller_name, array('pjAdminOptions')) && !in_array($action_name, array('pjActionPreview', 'pjActionInstall'));

$isScriptOptionsBooking         = $isScriptOptionsController && in_array($action_name, array('pjActionBooking'));
$isScriptOptionsBookingForm     = $isScriptOptionsController && in_array($action_name, array('pjActionBookingForm'));
$isScriptOptionsTicket     = $isScriptOptionsController && in_array($action_name, array('pjActionTicket'));
$isScriptOptionsTerm            = $isScriptOptionsController && in_array($action_name, array('pjActionTerm'));
$isScriptOptionsContent            = $isScriptOptionsController && in_array($action_name, array('pjActionContent'));
$isScriptOptionsNotifications   = $isScriptOptionsController && in_array($action_name, array('pjActionNotifications'));


// Permissions - Dashboard
$hasAccessScriptDashboard = pjAuth::factory('pjAdmin', 'pjActionIndex')->hasAccess();

// Permissions - Schedule
$hasAccessScriptSchedule  = pjAuth::factory('pjAdminSchedule')->hasAccess();
$hasAccessScriptScheduleIndex  = pjAuth::factory('pjAdminSchedule', 'pjActionIndex')->hasAccess();
$hasAccessScriptScheduleBookings  = pjAuth::factory('pjAdminSchedule', 'pjActionBookings')->hasAccess();
$hasAccessScriptScheduleSeats  = pjAuth::factory('pjAdminSchedule', 'pjActionSeats')->hasAccess();
$hasAccessScriptScheduleTimetable  = pjAuth::factory('pjAdminSchedule', 'pjActionTimetable')->hasAccess();

// Permissions - Bookings
$hasAccessScriptBookings            = pjAuth::factory('pjAdminBookings')->hasAccess();
$hasAccessScriptBookingsIndex       = pjAuth::factory('pjAdminBookings', 'pjActionIndex')->hasAccess();

// Permissions - Scan Ticket
$hasAccessScriptScanTicket = pjAuth::factory('pjAdminScanTicket', 'pjActionIndex')->hasAccess();

// Permissions - Cities
$hasAccessScriptCities        = pjAuth::factory('pjAdminCities')->hasAccess();
$hasAccessScriptCitiesIndex   = pjAuth::factory('pjAdminCities', 'pjActionIndex')->hasAccess();

// Permissions - Routes
$hasAccessScriptRoutes  = pjAuth::factory('pjAdminRoutes')->hasAccess();
$hasAccessScriptRoutesIndex  = pjAuth::factory('pjAdminRoutes', 'pjActionIndex')->hasAccess();

// Permissions - Bus Types
$hasAccessScriptBusTypes          	  = pjAuth::factory('pjAdminBusTypes', 'pjActionIndex')->hasAccess();

// Permissions - Buses
$hasAccessScriptBuses          	  = pjAuth::factory('pjAdminBuses', 'pjActionIndex')->hasAccess();

// Permissions - Settings
$hasAccessScriptOptions                 = pjAuth::factory('pjAdminOptions')->hasAccess();
$hasAccessScriptOptionsBooking          = pjAuth::factory('pjAdminOptions', 'pjActionBooking')->hasAccess();
$hasAccessScriptOptionsBookingForm      = pjAuth::factory('pjAdminOptions', 'pjActionBookingForm')->hasAccess();
$hasAccessScriptOptionsTicket      = pjAuth::factory('pjAdminOptions', 'pjActionTicket')->hasAccess();
$hasAccessScriptOptionsTerm             = pjAuth::factory('pjAdminOptions', 'pjActionTerm')->hasAccess();
$hasAccessScriptOptionsContent             = pjAuth::factory('pjAdminOptions', 'pjActionContent')->hasAccess();
$hasAccessScriptOptionsNotifications    = pjAuth::factory('pjAdminOptions', 'pjActionNotifications')->hasAccess();

// Permissions - Payments
$hasAccessScriptPayments = pjAuth::factory('pjPayments', 'pjActionIndex')->hasAccess();

?>

<?php if ($hasAccessScriptDashboard): ?>
    <li<?php echo $isScriptDashboard ? ' class="active"' : NULL; ?>>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionIndex"><i class="fa fa-th-large"></i> <span class="nav-label"><?php __('plugin_base_menu_dashboard');?></span></a>
    </li>
<?php endif; ?>

<?php if ($hasAccessScriptScheduleIndex || $hasAccessScriptScheduleTimetable): ?>
   <li<?php echo $isScriptScheduleController ? ' class="active"' : NULL; ?>>
        <a href="#"><i class="fa fa-calendar"></i> <span class="nav-label"><?php __('menuSchedule');?></span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            
            <?php if ($hasAccessScriptScheduleIndex): ?>
                <li<?php echo $isScriptScheduleIndexController ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionIndex"><?php __('menuDailySchedule');?></a></li>
            <?php endif; ?>

            <?php if ($hasAccessScriptScheduleTimetable): ?>
                <li<?php echo $isScriptScheduleTimetableController ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionTimetable"><?php __('menuRouteTimetable');?></a></li>
            <?php endif; ?>
        </ul>
    </li>
<?php endif; ?>

<?php if ($hasAccessScriptBookingsIndex): ?>
    <li<?php echo $isScriptBookings ? ' class="active"' : NULL; ?>>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex"><i class="fa fa-list"></i> <span class="nav-label"><?php __('menuBookings');?></span></a>
    </li>
<?php endif; ?>

<?php if ($hasAccessScriptScanTicket): ?>
    <li<?php echo $isScriptScanTicket ? ' class="active"' : NULL; ?>>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminScanTicket&amp;action=pjActionIndex"><i class="fa fa-qrcode"></i> <span class="nav-label"><?php __('menScanTicket');?></span></a>
    </li>
<?php endif; ?>

<?php if ($hasAccessScriptBuses): ?>
    <li<?php echo $isScriptBusesController ? ' class="active"' : NULL; ?>>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionIndex"><i class="fa fa-bus"></i> <span class="nav-label"><?php __('menuBuses');?></span></a>
    </li>
<?php endif; ?>

<?php if ($hasAccessScriptCities || $hasAccessScriptCitiesIndex || $hasAccessScriptRoutes || $hasAccessScriptRoutesIndex): ?>
    <li<?php echo $isScriptCitiesController || $isScriptRoutesController ? ' class="active"' : NULL; ?>>
        <a href="#"><i class="fa fa-road"></i> <span class="nav-label"><?php __('menuRoutes');?></span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            
            <?php if ($hasAccessScriptCities): ?>
                <li<?php echo $isScriptCitiesController ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCities&amp;action=pjActionIndex"><?php __('menuCitiesList');?></a></li>
            <?php endif; ?>

            <?php if ($hasAccessScriptRoutes): ?>
                <li<?php echo $isScriptRoutesController ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminRoutes&amp;action=pjActionIndex"><?php __('menuRoutesList');?></a></li>
            <?php endif; ?>
        </ul>
    </li>
<?php endif; ?>

<?php if ($hasAccessScriptBusTypes): ?>
    <li<?php echo $isScriptBusTypesController ? ' class="active"' : NULL; ?>>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBusTypes&amp;action=pjActionIndex"><i class="fa fa-truck"></i> <span class="nav-label"><?php __('menuBusTypes');?></span></a>
    </li>
<?php endif; ?>

<?php if ($hasAccessScriptOptionsBooking || $hasAccessScriptPayments || $hasAccessScriptOptionsBookingForm || $hasAccessScriptOptionsTicket || $hasAccessScriptOptionsContent || $hasAccessScriptOptionsNotifications || $hasAccessScriptOptionsTerm): ?>
    <li<?php echo $isScriptOptionsController || $isScriptPaymentsController ? ' class="active"' : NULL; ?>>
        <a href="#"><i class="fa fa-cog"></i> <span class="nav-label"><?php __('menuSettings');?></span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <?php if ($hasAccessScriptOptionsBooking): ?>
                <li<?php echo $isScriptOptionsBooking ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionBooking"><?php __('settingsTabBookings');?></a></li>
            <?php endif; ?>

            <?php if ($hasAccessScriptPayments): ?>
                <li<?php echo $isScriptPaymentsController ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjPayments&amp;action=pjActionIndex"><?php __('settingsTabPayments');?></a></li>
            <?php endif; ?>

            <?php if ($hasAccessScriptOptionsBookingForm): ?>
                <li<?php echo $isScriptOptionsBookingForm ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionBookingForm"><?php __('settingsTabBookingForm');?></a></li>
            <?php endif; ?>
            
            <?php if ($hasAccessScriptOptionsTicket): ?>
                <li<?php echo $isScriptOptionsTicket ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionTicket"><?php __('settingsTabTicket');?></a></li>
            <?php endif; ?>

			<?php if ($hasAccessScriptOptionsTerm): ?>
                <li<?php echo $isScriptOptionsTerm ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionTerm"><?php __('settingsTabTerms');?></a></li>
            <?php endif; ?>
            
			<?php if ($hasAccessScriptOptionsContent): ?>
                <li<?php echo $isScriptOptionsContent ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionContent"><?php __('settingsTabContent');?></a></li>
            <?php endif; ?>
            
            <?php if ($hasAccessScriptOptionsNotifications): ?>
                <li<?php echo $isScriptOptionsNotifications ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionNotifications"><?php __('settingsTabNotifications');?></a></li>
            <?php endif; ?>

        </ul>
    </li>
<?php endif; ?>