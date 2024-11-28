DROP TABLE IF EXISTS `bus_schedule_bookings`;
CREATE TABLE IF NOT EXISTS `bus_schedule_bookings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) DEFAULT NULL,
  `bus_id` int(10) unsigned DEFAULT NULL,
  `pickup_id` int(10) unsigned DEFAULT NULL COMMENT 'Location ID',
  `return_id` int(10) unsigned DEFAULT NULL COMMENT 'Location ID',
  `is_return` enum('T','F') DEFAULT 'F',
  `back_id` int(10) unsigned DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `bus_departure_date` date DEFAULT NULL,
  `booking_time` varchar(255) DEFAULT NULL,
  `booking_route` varchar(255) DEFAULT NULL,
  `booking_datetime` datetime DEFAULT NULL,
  `stop_datetime` datetime DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `sub_total` decimal(9,2) unsigned DEFAULT NULL,
  `tax` decimal(9,2) unsigned DEFAULT NULL,
  `total` decimal(9,2) unsigned DEFAULT NULL,
  `deposit` decimal(9,2) unsigned DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `status` enum('confirmed','cancelled','pending') DEFAULT 'pending',
  `txn_id` varchar(255) DEFAULT NULL,
  `processed_on` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `is_sent` enum('T','F') DEFAULT 'F',
  `ip` varchar(255) DEFAULT NULL,
  `c_title` varchar(255) DEFAULT NULL,
  `c_fname` varchar(255) DEFAULT NULL,
  `c_lname` varchar(255) DEFAULT NULL,
  `c_phone` varchar(255) DEFAULT NULL,
  `c_email` varchar(255) DEFAULT NULL,
  `c_company` varchar(255) DEFAULT NULL,
  `c_notes` text,
  `c_address` varchar(255) DEFAULT NULL,
  `c_city` varchar(255) DEFAULT NULL,
  `c_state` varchar(255) DEFAULT NULL,
  `c_zip` varchar(255) DEFAULT NULL,
  `c_country` int(10) unsigned DEFAULT NULL,
  `cc_type` blob,
  `cc_num` blob,
  `cc_exp` blob,
  `cc_code` blob,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `bus_id` (`bus_id`),
  KEY `pickup_id` (`pickup_id`),
  KEY `return_id` (`return_id`),
  KEY `booking_date` (`booking_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bus_schedule_bookings_payments`;
CREATE TABLE IF NOT EXISTS `bus_schedule_bookings_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` int(10) unsigned DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `amount` decimal(9,2) unsigned DEFAULT NULL,
  `status` enum('paid','notpaid') DEFAULT 'paid',
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bus_schedule_bookings_seats`;
CREATE TABLE IF NOT EXISTS `bus_schedule_bookings_seats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` int(10) DEFAULT NULL,
  `seat_id` int(10) DEFAULT NULL,
  `ticket_id` int(10) DEFAULT NULL,
  `start_location_id` int(10) DEFAULT NULL,
  `end_location_id` int(10) DEFAULT NULL,
  `is_return` enum('T','F') NOT NULL DEFAULT 'F',
  `qr_code` varchar(255) DEFAULT NULL,                                               
  `is_used` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_id_2` (`booking_id`,`seat_id`,`ticket_id`,`start_location_id`),
  KEY `booking_id` (`booking_id`),
  KEY `seat_id` (`seat_id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `start_location_id` (`start_location_id`),
  KEY `end_location_id` (`end_location_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bus_schedule_bookings_tickets`;
CREATE TABLE IF NOT EXISTS `bus_schedule_bookings_tickets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` int(10) DEFAULT NULL,
  `ticket_id` int(10) DEFAULT NULL,
  `qty` int(5) DEFAULT NULL,
  `amount` decimal(9,2) DEFAULT NULL,
  `is_return` enum('T','F') NOT NULL DEFAULT 'F',
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_id` (`booking_id`,`ticket_id`),
  KEY `ticket_id` (`ticket_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bus_schedule_routes`;
CREATE TABLE IF NOT EXISTS `bus_schedule_routes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` enum('T','F') NOT NULL DEFAULT 'T',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bus_schedule_route_details`;
CREATE TABLE IF NOT EXISTS `bus_schedule_route_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `route_id` int(10) unsigned DEFAULT NULL,
  `from_location_id` int(10) DEFAULT NULL,
  `to_location_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `route_id` (`route_id`,`from_location_id`,`to_location_id`),
  KEY `from_location_id` (`from_location_id`),
  KEY `to_location_id` (`to_location_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bus_schedule_bus_types`;
CREATE TABLE IF NOT EXISTS `bus_schedule_bus_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `seats_map` varchar(255) DEFAULT NULL,
  `seats_count` int(4) DEFAULT NULL,
  `use_map` enum('T','F') DEFAULT 'T', 
  `status` enum('T','F') NOT NULL DEFAULT 'T',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bus_schedule_seats`;
CREATE TABLE IF NOT EXISTS `bus_schedule_seats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bus_type_id` int(10) unsigned DEFAULT NULL,
  `width` smallint(5) unsigned DEFAULT NULL,
  `height` smallint(5) unsigned DEFAULT NULL,
  `top` smallint(5) unsigned DEFAULT NULL,
  `left` smallint(5) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bus_type_id` (`bus_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bus_schedule_buses`;
CREATE TABLE IF NOT EXISTS `bus_schedule_buses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `route_id` int(10) unsigned DEFAULT NULL,
  `bus_type_id` int(10) unsigned DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  `recurring` varchar(255) DEFAULT NULL,
  `set_seats_count` enum('T','F') DEFAULT 'F',
  `discount` decimal(9,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `route_id` (`route_id`),
  KEY `bus_type_id` (`bus_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bus_schedule_buses_dates`;
CREATE TABLE IF NOT EXISTS `bus_schedule_buses_dates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bus_id` int(10) unsigned DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bus_schedule_tickets`;
CREATE TABLE IF NOT EXISTS `bus_schedule_tickets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bus_id` int(10) unsigned DEFAULT NULL,
  `seats_count` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bus_id` (`bus_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bus_schedule_prices`;
CREATE TABLE IF NOT EXISTS `bus_schedule_prices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bus_id` int(10) unsigned DEFAULT NULL,
  `ticket_id` int(10) unsigned DEFAULT NULL,
  `from_location_id` int(10) DEFAULT NULL,
  `to_location_id` int(10) DEFAULT NULL,
  `price` decimal(9,2) DEFAULT NULL,
  `is_return` enum('T','F') NOT NULL DEFAULT 'F',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ticket_id` (`ticket_id`,`from_location_id`,`to_location_id`),
  KEY `bus_id` (`bus_id`),
  KEY `from_location_id` (`from_location_id`),
  KEY `to_location_id` (`to_location_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bus_schedule_buses_locations`;
CREATE TABLE IF NOT EXISTS `bus_schedule_buses_locations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bus_id` int(10) unsigned DEFAULT NULL,
  `location_id` int(10) unsigned DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`location_id`),
  KEY `location_id` (`location_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bus_schedule_routes_cities`;
CREATE TABLE IF NOT EXISTS `bus_schedule_routes_cities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `route_id` int(10) unsigned DEFAULT NULL,
  `city_id` int(10) unsigned DEFAULT NULL,
  `order` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `route_id` (`route_id`,`city_id`,`order`),
  KEY `city_id` (`city_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bus_schedule_cities`;
CREATE TABLE IF NOT EXISTS `bus_schedule_cities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` enum('T','F') NOT NULL DEFAULT 'T',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `bus_schedule_options`;
CREATE TABLE IF NOT EXISTS `bus_schedule_options` (
  `foreign_id` int(10) unsigned NOT NULL DEFAULT '0',
  `key` varchar(255) NOT NULL DEFAULT '',
  `tab_id` tinyint(3) unsigned DEFAULT NULL,
  `value` text,
  `label` text,
  `type` enum('string','text','int','float','enum','bool') NOT NULL DEFAULT 'string',
  `order` int(10) unsigned DEFAULT NULL,
  `is_visible` tinyint(1) unsigned DEFAULT '1',
  `style` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`foreign_id`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bus_schedule_password`;
CREATE TABLE IF NOT EXISTS `bus_schedule_password` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `bus_schedule_notifications`;
CREATE TABLE IF NOT EXISTS `bus_schedule_notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `recipient` enum('client','admin') DEFAULT NULL,
  `transport` enum('email','sms') DEFAULT NULL,
  `variant` varchar(30) DEFAULT NULL,
  `is_active` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `recipient` (`recipient`,`transport`,`variant`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `bus_schedule_notifications` (`id`, `recipient`, `transport`, `variant`, `is_active`) VALUES
(1, 'client', 'email', 'confirmation', 1),
(2, 'client', 'email', 'payment', 1),
(3, 'client', 'email', 'cancel', 1),
(4, 'admin', 'email', 'confirmation', 1),
(5, 'admin', 'email', 'payment', 1),
(6, 'admin', 'email', 'cancel', 1),
(7, 'client', 'email', 'pending_time_expired', 1),
(8, 'client', 'sms', 'confirmation', 1),
(9, 'client', 'sms', 'payment', 1),
(10, 'admin', 'sms', 'confirmation', 1),
(11, 'admin', 'sms', 'payment', 1);

INSERT INTO `bus_schedule_plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, 1, 'pjOption', 1, 'o_ticket_template', '<h2 style="text-align: center;">COMPANY NAME and LOGO</h2>\r\n<h2>BUS TICKET</h2>\r\n<h3>Booking ID: <span class="fs10">{UniqueID}</span></h3>\r\n<table style="height: 122px; width: 436px;" border="2" width="436">\r\n<tbody>\r\n<tr>\r\n<td colspan="2">\r\n<h3><strong>Trip Details</strong></h3>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td>Bus:</td>\r\n<td><span class="fs10">{Bus}</span></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Date:</strong></td>\r\n<td><strong><span class="fs10"><span class="fs10">{Date}</span></span></strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Departure from:</strong></td>\r\n<td><strong><span class="fs10">{From_Location}</span> at <span class="fs10">{Departure_Time}</span></strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Arrive to:</strong></td>\r\n<td><strong><span class="fs10">{To_Location}</span> at <span class="fs10">{Arrival_Time}</span></strong></td>\r\n</tr>\r\n<tr>\r\n<td>Ticket Type:</td>\r\n<td><span class="fs10">{TicketType}</span></td>\r\n</tr>\r\n<tr>\r\n<td>Seats:</td>\r\n<td>{Seat}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<table style="height: 122px; width: 436px;" border="2" width="436">\r\n<tbody>\r\n<tr>\r\n<td colspan="2">\r\n<h3><strong>Customer and Booking Details<br /></strong></h3>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td>Customer Name:</td>\r\n<td><strong><span class="fs10"><span class="fs10">{Title}</span> <span class="fs10">{FirstName}</span> <span class="fs10">{LastName}</span></span></strong></td>\r\n</tr>\r\n<tr>\r\n<td>Phone:</td>\r\n<td><span class="fs10"><span class="fs10"><span class="fs10">{Phone}</span></span></span></td>\r\n</tr>\r\n<tr>\r\n<td>Booking Total:</td>\r\n<td><span class="fs10"><span class="fs10">{Total}</span></span></td>\r\n</tr>\r\n<tr>\r\n<td>Online Deposit Payment</td>\r\n<td><span class="fs10">&nbsp;through <span class="fs10">{PaymentMethod}</span><br /></span></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Notes:</p>\r\n<hr />\r\n<p>&nbsp;</p>', 'data'),
(NULL, 1, 'pjOption', 1, 'o_terms', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse eu ipsum consectetur arcu commodo egestas nec eu ante. Aenean nec enim lorem. Proin accumsan luctus luctus. Vivamus pulvinar mollis orci, id convallis eros ultricies vel. Nullam adipiscing, risus non pellentesque aliquam, nibh ligula dictum justo, quis commodo nisi dolor ut nulla. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ante leo, ultricies quis gravida id, vestibulum nec risus. Mauris adipiscing vestibulum nibh non ullamcorper. Suspendisse justo turpis, mattis a cursus ac, vulputate quis metus. Fusce vestibulum faucibus dignissim. Aliquam fermentum mauris felis, a ultrices sem.', 'data'),
(NULL, 1, 'pjOption', 1, 'o_content', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus.<br/><br/>Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus.', 'data'),
(NULL, 1, 'pjOption', 1, 'o_email_notify_subject', 'Seats pending time expired', 'data'),
(NULL, 1, 'pjOption', 1, 'o_email_notify_message', 'We are sorry to let you know that your seats per your ticket booking are no longer reserved for you. The time we keep seats reserved till the booking is paid has expired. If you wish to complete your booking please contact us or start a new one.<br/><br/>Personal details:<br/>Title: {Title}<br/>First Name: {FirstName}<br/>Last Name: {LastName}<br/>E-Mail: {Email}<br/>Phone: {Phone}<br/>Notes: {Notes}<br/>Country: {Country}<br/>City: {City}<br/>State: {State}<br/>Zip: {Zip}<br/>Address: {Address}<br/>Company: {Company}<br/><br/>Booking details:<br/>Booking date: {Date}<br/>Time: {Time}<br/>Bus: {Bus}<br/>Route: {Route}<br/>Seats: {Seats}<br/>Ticket types price: {TicketTypesPrice}<br/>Unique ID: {UniqueID}<br/>Total: {Total}<br/><br/><br/>Thank you!', 'data'),

(NULL, 1, 'pjNotification', 1, 'confirm_subject_client', 'Booking confirmation', 'data'),
(NULL, 1, 'pjNotification', 1, 'confirm_tokens_client', 'You''ve just made a booking.<br/><br/>Personal details:<br/>Title: {Title}<br/>First Name: {FirstName}<br/>Last Name: {LastName}<br/>E-Mail: {Email}<br/>Phone: {Phone}<br/>Notes: {Notes}<br/>Country: {Country}<br/>City: {City}<br/>State: {State}<br/>Zip: {Zip}<br/>Address: {Address}<br/>Company: {Company}<br/><br/>Booking details:<br/>Booking date: {Date}<br/>Time: {Time}<br/>Bus: {Bus}<br/>Route: {Route}<br/>Seats: {Seats}<br/>Ticket types price: {TicketTypesPrice}<br/>Unique ID: {UniqueID}<br/>Total: {Total}<br/><br/>If you want to print your tickets follow next link: {PrintTickets}<br/><br/>If you want to cancel your booking follow next link: {CancelURL}<br/><br/>Thank you, we will contact you ASAP.', 'data'),
(NULL, 2, 'pjNotification', 1, 'payment_subject_client', 'Payment confirmation', 'data'),
(NULL, 2, 'pjNotification', 1, 'payment_tokens_client', 'You''ve just made payment for the booking.<br/><br/>Personal details:<br/>Title: {Title}<br/>First Name: {FirstName}<br/>Last Name: {LastName}<br/>E-Mail: {Email}<br/>Phone: {Phone}<br/>Notes: {Notes}<br/>Country: {Country}<br/>City: {City}<br/>State: {State}<br/>Zip: {Zip}<br/>Address: {Address}<br/>Company: {Company}<br/><br/>Booking details:<br/>Booking date: {Date}<br/>Time: {Time}<br/>Bus: {Bus}<br/>Route: {Route}<br/>Seats: {Seats}<br/>Ticket types price: {TicketTypesPrice}<br/>Unique ID: {UniqueID}<br/>Total: {Total}<br/><br/>If you want to print your tickets follow next link: {PrintTickets}<br/><br/>If you want to cancel your booking follow next link: {CancelURL}<br/><br/>Thank you, we will contact you ASAP.', 'data'),
(NULL, 3, 'pjNotification', 1, 'cancel_subject_client', 'Cancel confirmation', 'data'),
(NULL, 3, 'pjNotification', 1, 'cancel_tokens_client', 'You''ve just cancelled the booking.<br/><br/>Personal details:<br/>Title: {Title}<br/>First Name: {FirstName}<br/>Last Name: {LastName}<br/>E-Mail: {Email}<br/>Phone: {Phone}<br/>Notes: {Notes}<br/>Country: {Country}<br/>City: {City}<br/>State: {State}<br/>Zip: {Zip}<br/>Address: {Address}<br/>Company: {Company}<br/><br/>Booking details:<br/>Booking date: {Date}<br/>Time: {Time}<br/>Bus: {Bus}<br/>Route: {Route}<br/>Seats: {Seats}<br/>Ticket types price: {TicketTypesPrice}<br/>Unique ID: {UniqueID}<br/>Total: {Total}<br/><br/>Thank you, we will contact you ASAP.', 'data'),
(NULL, 4, 'pjNotification', 1, 'confirm_subject_admin', 'New booking received', 'data'),
(NULL, 4, 'pjNotification', 1, 'confirm_tokens_admin', 'You''ve just received a booking.<br/><br/>Personal details:<br/>Title: {Title}<br/>First Name: {FirstName}<br/>Last Name: {LastName}<br/>E-Mail: {Email}<br/>Phone: {Phone}<br/>Notes: {Notes}<br/>Country: {Country}<br/>City: {City}<br/>State: {State}<br/>Zip: {Zip}<br/>Address: {Address}<br/>Company: {Company}<br/><br/>Booking details:<br/>Booking date: {Date}<br/>Time: {Time}<br/>Bus: {Bus}<br/>Route: {Route}<br/>Seats: {Seats}<br/>Ticket types price: {TicketTypesPrice}<br/>Unique ID: {UniqueID}<br/>Total: {Total}<br/><br/>If you want to print your tickets follow next link: {PrintTickets}<br/><br/>If you want to cancel your booking follow next link: {CancelURL}<br/><br/>Thank you, we will contact you ASAP.', 'data'),
(NULL, 5, 'pjNotification', 1, 'payment_subject_admin', 'New payment received', 'data'),
(NULL, 5, 'pjNotification', 1, 'payment_tokens_admin', 'You''ve just received a payment for the booking.<br/><br/>Personal details:<br/>Title: {Title}<br/>First Name: {FirstName}<br/>Last Name: {LastName}<br/>E-Mail: {Email}<br/>Phone: {Phone}<br/>Notes: {Notes}<br/>Country: {Country}<br/>City: {City}<br/>State: {State}<br/>Zip: {Zip}<br/>Address: {Address}<br/>Company: {Company}<br/><br/>Booking details:<br/>Booking date: {Date}<br/>Time: {Time}<br/>Bus: {Bus}<br/>Route: {Route}<br/>Seats: {Seats}<br/>Ticket types price: {TicketTypesPrice}<br/>Unique ID: {UniqueID}<br/>Total: {Total}<br/><br/>If you want to print your tickets follow next link: {PrintTickets}<br/><br/>If you want to cancel your booking follow next link: {CancelURL}<br/><br/>Thank you, we will contact you ASAP.', 'data'),
(NULL, 6, 'pjNotification', 1, 'cancel_subject_admin', 'Booking cancelled', 'data'),
(NULL, 6, 'pjNotification', 1, 'cancel_tokens_admin', 'A booking has just been cancelled.<br/><br/>Personal details:<br/>Title: {Title}<br/>First Name: {FirstName}<br/>Last Name: {LastName}<br/>E-Mail: {Email}<br/>Phone: {Phone}<br/>Notes: {Notes}<br/>Country: {Country}<br/>City: {City}<br/>State: {State}<br/>Zip: {Zip}<br/>Address: {Address}<br/>Company: {Company}<br/><br/>Booking details:<br/>Booking date: {Date}<br/>Time: {Time}<br/>Bus: {Bus}<br/>Route: {Route}<br/>Seats: {Seats}<br/>Ticket types price: {TicketTypesPrice}<br/>Unique ID: {UniqueID}<br/>Total: {Total}<br/><br/>Thank you!', 'data'),
(NULL, 7, 'pjNotification', 1, 'pending_time_expired_subject_client', 'Seats pending time expired', 'data'),
(NULL, 7, 'pjNotification', 1, 'pending_time_expired_tokens_client', 'We are sorry to let you know that your seats per your ticket booking are no longer reserved for you. The time we keep seats reserved till the booking is paid has expired. If you wish to complete your booking please contact us or start a new one.<br/><br/>Personal details:<br/>Title: {Title}<br/>First Name: {FirstName}<br/>Last Name: {LastName}<br/>E-Mail: {Email}<br/>Phone: {Phone}<br/>Notes: {Notes}<br/>Country: {Country}<br/>City: {City}<br/>State: {State}<br/>Zip: {Zip}<br/>Address: {Address}<br/>Company: {Company}<br/><br/>Booking details:<br/>Booking date: {Date}<br/>Time: {Time}<br/>Bus: {Bus}<br/>Route: {Route}<br/>Seats: {Seats}<br/>Ticket types price: {TicketTypesPrice}<br/>Unique ID: {UniqueID}<br/>Total: {Total}<br/><br/><br/>Thank you!', 'data'),
(NULL, 8, 'pjNotification', 1, 'confirm_sms_tokens_client', 'You''ve just made a booking. Booking {UniqueID}', 'data'),
(NULL, 9, 'pjNotification', 1, 'payment_sms_tokens_client', 'You''ve just made a payment. Booking {UniqueID}', 'data'),
(NULL, 10, 'pjNotification', 1, 'confirm_sms_tokens_admin', 'You''ve just received a new booking. Booking {UniqueID}', 'data'),
(NULL, 11, 'pjNotification', 1, 'payment_sms_tokens_admin', 'New payment received. Booking {UniqueID}', 'data'),
(NULL, 1, 'pjPayment', '1', 'cash', 'Cash', 'script');


INSERT INTO `bus_schedule_plugin_base_cron_jobs` (`name`, `controller`, `action`, `interval`, `period`, `is_active`) VALUES
('Send email seats pending time expired', 'pjCron', 'pjActionIndex', 5, 'minute', 1);


INSERT INTO `bus_schedule_options` (`foreign_id`, `key`, `tab_id`, `value`, `label`, `type`, `order`, `is_visible`, `style`) VALUES
('1','o_deposit_payment','2','10.00',NULL,'int','2','1',NULL),
('1','o_tax_payment','2','10.00',NULL,'int','4','1',NULL),
('1','o_booking_status','2','confirmed|pending|cancelled::pending','Confirmed|Pending|Cancelled','enum','5','1',NULL),
('1','o_payment_status','2','confirmed|pending|cancelled::confirmed','Confirmed|Pending|Cancelled','enum','6','1',NULL),
('1','o_min_hour','2','30',NULL,'int','7','1',NULL),
('1','o_thank_you_page','2','https://www.phpjabbers.com',NULL,'string','8','1',NULL),
('1','o_cancel_after_pending_time','2','Yes|No::Yes','Yes|No','enum','8','1',NULL),
('1','o_payment_disable','2','Yes|No::No','Yes|No','enum','9','1',NULL),
('1','o_allow_paypal','2','Yes|No::Yes','Yes|No','enum','10','1',NULL),
('1','o_paypal_address','2','paypal@domain.com',NULL,'string','11','1',NULL),
('1','o_allow_authorize','2','Yes|No::No','Yes|No','enum','12','1',NULL),
('1','o_authorize_transkey','2','',NULL,'string','13','1',NULL),
('1','o_authorize_merchant_id','2','',NULL,'string','14','1',NULL),
('1','o_authorize_timezone','2','-43200|-39600|-36000|-32400|-28800|-25200|-21600|-18000|-14400|-10800|-7200|-3600|0|3600|7200|10800|14400|18000|21600|25200|28800|32400|36000|39600|43200|46800::0','GMT-12:00|GMT-11:00|GMT-10:00|GMT-09:00|GMT-08:00|GMT-07:00|GMT-06:00|GMT-05:00|GMT-04:00|GMT-03:00|GMT-02:00|GMT-01:00|GMT|GMT+01:00|GMT+02:00|GMT+03:00|GMT+04:00|GMT+05:00|GMT+06:00|GMT+07:00|GMT+08:00|GMT+09:00|GMT+10:00|GMT+11:00|GMT+12:00|GMT+13:00','enum','15','1',NULL),
('1','o_authorize_md5_hash','2',NULL,NULL,'string','16','1',NULL),
('1','o_allow_cash','2','Yes|No::Yes','Yes|No','enum','17','1',NULL),
('1','o_allow_creditcard','2','Yes|No::Yes','Yes|No','enum','18','1',NULL),
('1','o_allow_bank','2','Yes|No::No',NULL,'enum','19','1',NULL),
('1','o_bank_account','2',NULL,NULL,'text','20','1',NULL),
('1','o_email_confirmation','3','0|1::1','No|Yes','enum','1','1',NULL),
('1','o_admin_email_confirmation','3','0|1::1','No|Yes','enum','1','1',NULL),
('1','o_sms_confirmation_message','3','',NULL,'text','2','1',NULL),
('1','o_admin_sms_confirmation_message','3','',NULL,'text','2','1',NULL),
('1','o_sms_payment_message','3','',NULL,'text','3','1',NULL),
('1','o_email_confirmation_subject','3','',NULL,'string','3','1',NULL),
('1','o_admin_email_confirmation_subject','3','',NULL,'string','3','1',NULL),
('1','o_admin_sms_payment_message','3','',NULL,'text','4','1',NULL),
('1','o_email_confirmation_message','3','',NULL,'text','4','1',NULL),
('1','o_admin_email_confirmation_message','3','',NULL,'text','4','1',NULL),
('1','o_email_payment','3','0|1::1','No|Yes','enum','5','1',NULL),
('1','o_admin_email_payment','3','0|1::1','No|Yes','enum','5','1',NULL),
('1','o_email_payment_subject','3','',NULL,'string','6','1',NULL),
('1','o_admin_email_payment_subject','3','',NULL,'string','6','1',NULL),
('1','o_email_payment_message','3','',NULL,'text','7','1',NULL),
('1','o_admin_email_payment_message','3','',NULL,'text','7','1',NULL),
('1','o_email_notify','3','0|1::1','No|Yes','enum','8','1',NULL),
('1','o_admin_email_cancel','3','0|1::1','No|Yes','enum','8','1',NULL),
('1','o_email_notify_subject','3','',NULL,'string','12','1',NULL),
('1','o_admin_email_cancel_subject','3','',NULL,'string','12','1',NULL),
('1','o_email_notify_message','3','',NULL,'text','13','1',NULL),
('1','o_admin_email_cancel_message','3','',NULL,'text','13','1',NULL),
('1','o_email_cancel','3','0|1::1','No|Yes','enum','14','1',NULL),
('1','o_email_cancel_subject','3','',NULL,'string','15','1',NULL),
('1','o_email_cancel_message','3','',NULL,'text','16','1',NULL),
('1','o_bf_include_title','4','1|2|3::3','No|Yes|Yes (required)','enum','1','1',NULL),
('1','o_bf_include_fname','4','1|2|3::3','No|Yes|Yes (required)','enum','2','1',NULL),
('1','o_bf_include_lname','4','1|2|3::3','No|Yes|Yes (required)','enum','3','1',NULL),
('1','o_bf_include_phone','4','1|2|3::3','No|Yes|Yes (required)','enum','4','1',NULL),
('1','o_bf_include_email','4','1|2|3::3','No|Yes|Yes (required)','enum','5','1',NULL),
('1','o_bf_include_company','4','1|2|3::1','No|Yes|Yes (required)','enum','6','1',NULL),
('1','o_bf_include_address','4','1|2|3::1','No|Yes|Yes (required)','enum','7','1',NULL),
('1','o_bf_include_notes','4','1|2|3::1','No|Yes|Yes (required)','enum','8','1',NULL),
('1','o_bf_include_city','4','1|2|3::1','No|Yes|Yes (required)','enum','10','1',NULL),
('1','o_bf_include_state','4','1|2|3::1','No|Yes|Yes (required)','enum','11','1',NULL),
('1','o_bf_include_zip','4','1|2|3::1','No|Yes|Yes (required)','enum','12','1',NULL),
('1','o_bf_include_country','4','1|2|3::1','No|Yes|Yes (required)','enum','13','1',NULL),
('1','o_bf_include_captcha','4','1|2|3::3','No|Yes|Yes (required)','enum','14','1',NULL),
('1','o_ticket_template','5','',NULL,'text','1','1',NULL),
('1','o_terms','6','',NULL,'text','1','1',NULL),
('1','o_content','7','',NULL,'text','1','1',NULL),
('1','o_image_name','7','',NULL,'text','1','0',NULL),
('1','o_image_path','7','',NULL,'text','1','0',NULL),

('1','o_multi_lang','99','1|0::1',NULL,'enum',NULL,'0',NULL),
('1','o_fields_index','99','d874fcc5fe73b90d770a544664a3775d',NULL,'string',NULL,'0',NULL),

('1','o_theme','99','theme1|theme2|theme3|theme4|theme5|theme6|theme7|theme8|theme9|theme10|theme11::theme1','Theme 1|Theme 2|Theme 3|Theme 4|Theme 5|Theme 6|Theme 7|Theme 8|Theme 9|Theme 10|Theme 11','enum','5','0',NULL);


INSERT INTO `bus_schedule_plugin_auth_roles` (`id`, `role`, `is_backend`, `is_admin`, `status`) VALUES
(3, 'Driver', 'T', 'T', 'T');


INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'script_name', 'backend', 'Bus Reservation System', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus Reservation System', 'script');


INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'addLocale', 'backend', 'Add language', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add language', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'adminForgot', 'backend', 'Forgot password', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Password reminder', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'adminLogin', 'backend', 'Admin Login', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Admin Login', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'backend', 'backend', 'Backend titles', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Back-end titles', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnAdd', 'backend', 'Button Add', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnBack', 'backend', 'Button Back', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '« Back', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnBackup', 'backend', 'Button Backup', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Backup', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnCancel', 'backend', 'Button Cancel', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cancel', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnContinue', 'backend', 'Button Continue', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Continue', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnDelete', 'backend', 'Button Delete', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Delete', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnLogin', 'backend', 'Login', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Login', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnReset', 'backend', 'Reset', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Reset', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnSave', 'backend', 'Save', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Save', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnSearch', 'backend', 'Search', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Search', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnSend', 'backend', 'Button Send', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Send', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnUpdate', 'backend', 'Update', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Update', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'created', 'backend', 'Created', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'DateTime', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'email', 'backend', 'E-Mail', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'emailForgotBody', 'backend', 'Email / Forgot Body', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Dear {Name},Your password: {Password}', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'emailForgotSubject', 'backend', 'Email / Forgot Subject', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Password reminder', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'frontend', 'backend', 'Front-end titles', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Front-end titles', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'gridActionTitle', 'backend', 'Grid / Action Title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Action confirmation', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'gridBtnCancel', 'backend', 'Grid / Button Cancel', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cancel', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'gridBtnDelete', 'backend', 'Grid / Button Delete', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Delete', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'gridBtnOk', 'backend', 'Grid / Button OK', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'OK', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'gridChooseAction', 'backend', 'Grid / Choose Action', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Choose Action', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'gridConfirmationTitle', 'backend', 'Grid / Confirmation Title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Are you sure you want to delete selected record?', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'gridDeleteConfirmation', 'backend', 'Grid / Delete confirmation', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Delete confirmation', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'gridEmptyResult', 'backend', 'Grid / Empty resultset', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No records found', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'gridGotoPage', 'backend', 'Grid / Go to page', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Go to page:', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'gridItemsPerPage', 'backend', 'Grid / Items per page', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Items per page', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'gridNext', 'backend', 'Grid / Next', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Next »', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'gridNextPage', 'backend', 'Grid / Next page', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Next page', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'gridPrev', 'backend', 'Grid / Prev', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '« Prev', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'gridPrevPage', 'backend', 'Grid / Prev page', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Prev page', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'gridTotalItems', 'backend', 'Grid / Total items', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Total items:', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoListingAddressBody', 'backend', 'Infobox / Listing Address Body', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You can show a map with the location of the listing accommodation on the listing details page. Submit the full address first and then click on ''Get coordinates from Google Maps API'' button. Save your data.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoListingAddressTitle', 'backend', 'Infobox / Listing Address Title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Location and address', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoListingBookingsBody', 'backend', 'Infobox / Listing Bookings Body', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Listing Bookings Body', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoListingBookingsTitle', 'backend', 'Infobox / Listing Bookings Title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Listing Bookings Title', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoListingContactBody', 'backend', 'Infobox / Listing Contact Body', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Listing Contact Body', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoListingContactTitle', 'backend', 'Infobox / Listing Contact Title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Listing Contact Title', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoListingExtendBody', 'backend', 'Infobox / Extend exp.date Body', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Extend exp.date Body', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoListingExtendTitle', 'backend', 'Infobox / Extend exp.date Title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Extend exp.date Title', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoListingPricesBody', 'backend', 'Infobox / Listing Prices Body', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Listing Prices Body', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoListingPricesTitle', 'backend', 'Infobox / Listing Prices Title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Listing Prices Title', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoLocalesArraysBody', 'backend', 'Locale / Languages Array Body', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Languages Array Body', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoLocalesArraysTitle', 'backend', 'Locale / Languages Array Title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Languages Arrays Title', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoLocalesBackendBody', 'backend', 'Infobox / Locales Backend Body', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Languages Backend Body', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoLocalesBackendTitle', 'backend', 'Infobox / Locales Backend Title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Languages Backend Title', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoLocalesBody', 'backend', 'Infobox / Locales Body', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Languages Body', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoLocalesFrontendBody', 'backend', 'Infobox / Locales Frontend Body', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Languages Frontend Body', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoLocalesFrontendTitle', 'backend', 'Infobox / Locales Frontend Title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Languages Frontend Title', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoLocalesTitle', 'backend', 'Infobox / Locales Title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Languages Title', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblAddUser', 'backend', 'Add user', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add user', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBackupDatabase', 'backend', 'Backup / Database', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Backup database', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBackupFiles', 'backend', 'Backup / Files', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Backup files', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblChoose', 'backend', 'Choose', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Choose', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDays', 'backend', 'Days', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'days', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDelete', 'backend', 'Delete', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Delete', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblError', 'backend', 'Error', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Error', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblExport', 'backend', 'Export', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Export', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblForgot', 'backend', 'Forgot password', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Forgot password', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblIp', 'backend', 'IP address', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'IP address', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblIsActive', 'backend', 'Is Active', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Is confirmed', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblName', 'backend', 'Name', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Name', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblNo', 'backend', 'No', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblOption', 'backend', 'Option', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Option', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblOptionList', 'backend', 'Option list', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Option list', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblRole', 'backend', 'Role', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Role', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblStatus', 'backend', 'Status', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Status', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblType', 'backend', 'Type', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Type', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblUpdateUser', 'backend', 'Update user', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Update user', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblUserCreated', 'backend', 'User / Registration Date & Time', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Registration date/time', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblValue', 'backend', 'Value', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Value', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblYes', 'backend', 'Yes', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Yes', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lnkBack', 'backend', 'Link Back', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Back', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'localeArrays', 'backend', 'Locale / Arrays titles', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Arrays titles', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'locales', 'backend', 'Languages', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Languages', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'locale_flag', 'backend', 'Locale / Flag', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Flag', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'locale_is_default', 'backend', 'Locale / Is default', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Is default', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'locale_order', 'backend', 'Locale / Order', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Order', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'locale_title', 'backend', 'Locale / Title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Title', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuBackup', 'backend', 'Menu Backup', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Backup', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuDashboard', 'backend', 'Menu Dashboard', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Dashboard', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuLang', 'backend', 'Menu Multi lang', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Multi Lang', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuLocales', 'backend', 'Menu Languages', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Languages', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuLogout', 'backend', 'Menu Logout', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Logout', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuOptions', 'backend', 'Menu Options', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Settings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuPlugins', 'backend', 'Menu Plugins', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Plugins', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuProfile', 'backend', 'Menu Profile', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Profile', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuUsers', 'backend', 'Menu Users', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Users', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'multilangTooltip', 'backend', 'MultiLang / Tooltip', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select a language by clicking on the corresponding flag and update existing translation.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_currency', 'backend', 'Options / Currency', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Currency', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_date_format', 'backend', 'Options / Date format', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Date format', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_send_email', 'backend', 'opt_o_send_email', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Send email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_smtp_host', 'backend', 'opt_o_smtp_host', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'SMTP Host', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_smtp_pass', 'backend', 'opt_o_smtp_pass', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'SMTP Password', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_smtp_port', 'backend', 'opt_o_smtp_port', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'SMTP Port', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_smtp_user', 'backend', 'opt_o_smtp_user', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'SMTP Username', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_timezone', 'backend', 'Options / Timezone', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Timezone', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_week_start', 'backend', 'Options / First day of the week', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'First day of the week', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pass', 'backend', 'Password', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Password', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'revert_status', 'backend', 'Revert status', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Revert status', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'url', 'backend', 'URL', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'URL', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'user', 'backend', 'Username', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Username', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pj_email_taken', 'backend', 'Users / Email already taken', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'User with this email address exists.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'days_ARRAY_0', 'arrays', 'days_ARRAY_0', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Sunday', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'days_ARRAY_1', 'arrays', 'days_ARRAY_1', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Monday', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'days_ARRAY_2', 'arrays', 'days_ARRAY_2', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Tuesday', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'days_ARRAY_3', 'arrays', 'days_ARRAY_3', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Wednesday', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'days_ARRAY_4', 'arrays', 'days_ARRAY_4', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Thursday', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'days_ARRAY_5', 'arrays', 'days_ARRAY_5', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Friday', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'days_ARRAY_6', 'arrays', 'days_ARRAY_6', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Saturday', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'day_names_ARRAY_0', 'arrays', 'day_names_ARRAY_0', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'S', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'day_names_ARRAY_1', 'arrays', 'day_names_ARRAY_1', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'M', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'day_names_ARRAY_2', 'arrays', 'day_names_ARRAY_2', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'T', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'day_names_ARRAY_3', 'arrays', 'day_names_ARRAY_3', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'W', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'day_names_ARRAY_4', 'arrays', 'day_names_ARRAY_4', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'T', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'day_names_ARRAY_5', 'arrays', 'day_names_ARRAY_5', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'F', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'day_names_ARRAY_6', 'arrays', 'day_names_ARRAY_6', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'S', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AA10', 'arrays', 'error_bodies_ARRAY_AA10', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Given email address is not associated with any account.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AA11', 'arrays', 'error_bodies_ARRAY_AA11', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'For further instructions please check your mailbox.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AA12', 'arrays', 'error_bodies_ARRAY_AA12', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'We are sorry, please try again later.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AA13', 'arrays', 'error_bodies_ARRAY_AA13', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All the changes made to your profile have been saved.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AB02', 'arrays', 'error_bodies_ARRAY_AB02', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All backup files have been saved.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AB03', 'arrays', 'error_bodies_ARRAY_AB03', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No option was selected.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AB04', 'arrays', 'error_bodies_ARRAY_AB04', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Backup not performed.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ALC01', 'arrays', 'error_bodies_ARRAY_ALC01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All the changes made to titles have been saved.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO01', 'arrays', 'error_bodies_ARRAY_AO01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All the changes made to options have been saved.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AU01', 'arrays', 'error_bodies_ARRAY_AU01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All the changes made to this user have been saved.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AU03', 'arrays', 'error_bodies_ARRAY_AU03', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All the changes made to this user have been saved.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AU04', 'arrays', 'error_bodies_ARRAY_AU04', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'We are sorry, but the user has not been added.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AU08', 'arrays', 'error_bodies_ARRAY_AU08', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'User your looking for is missing.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AA10', 'arrays', 'error_titles_ARRAY_AA10', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Account not found!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AA11', 'arrays', 'error_titles_ARRAY_AA11', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Password send!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AA12', 'arrays', 'error_titles_ARRAY_AA12', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Password not send!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AA13', 'arrays', 'error_titles_ARRAY_AA13', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Profile updated!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AB01', 'arrays', 'error_titles_ARRAY_AB01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Backup', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AB02', 'arrays', 'error_titles_ARRAY_AB02', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Backup complete!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AB03', 'arrays', 'error_titles_ARRAY_AB03', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Backup failed!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AB04', 'arrays', 'error_titles_ARRAY_AB04', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Backup failed!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO01', 'arrays', 'error_titles_ARRAY_AO01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Options updated!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AU01', 'arrays', 'error_titles_ARRAY_AU01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'User updated!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AU03', 'arrays', 'error_titles_ARRAY_AU03', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'User added!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AU04', 'arrays', 'error_titles_ARRAY_AU04', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'User failed to add.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AU08', 'arrays', 'error_titles_ARRAY_AU08', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'User not found.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'filter_ARRAY_active', 'arrays', 'filter_ARRAY_active', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Active', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'filter_ARRAY_inactive', 'arrays', 'filter_ARRAY_inactive', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Inactive', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'login_err_ARRAY_1', 'arrays', 'login_err_ARRAY_1', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Wrong username or password', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'login_err_ARRAY_2', 'arrays', 'login_err_ARRAY_2', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Access denied', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'login_err_ARRAY_3', 'arrays', 'login_err_ARRAY_3', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Account is disabled', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'months_ARRAY_1', 'arrays', 'months_ARRAY_1', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'January', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'months_ARRAY_10', 'arrays', 'months_ARRAY_10', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'October', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'months_ARRAY_11', 'arrays', 'months_ARRAY_11', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'November', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'months_ARRAY_12', 'arrays', 'months_ARRAY_12', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'December', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'months_ARRAY_2', 'arrays', 'months_ARRAY_2', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'February', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'months_ARRAY_3', 'arrays', 'months_ARRAY_3', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'March', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'months_ARRAY_4', 'arrays', 'months_ARRAY_4', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'April', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'months_ARRAY_5', 'arrays', 'months_ARRAY_5', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'May', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'months_ARRAY_6', 'arrays', 'months_ARRAY_6', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'June', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'months_ARRAY_7', 'arrays', 'months_ARRAY_7', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'July', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'months_ARRAY_8', 'arrays', 'months_ARRAY_8', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'August', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'months_ARRAY_9', 'arrays', 'months_ARRAY_9', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'September', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'personal_titles_ARRAY_dr', 'arrays', 'personal_titles_ARRAY_dr', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Dr.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'personal_titles_ARRAY_miss', 'arrays', 'personal_titles_ARRAY_miss', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Miss', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'personal_titles_ARRAY_mr', 'arrays', 'personal_titles_ARRAY_mr', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Mr.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'personal_titles_ARRAY_mrs', 'arrays', 'personal_titles_ARRAY_mrs', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Mrs.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'personal_titles_ARRAY_ms', 'arrays', 'personal_titles_ARRAY_ms', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Ms.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'personal_titles_ARRAY_other', 'arrays', 'personal_titles_ARRAY_other', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Other', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'personal_titles_ARRAY_prof', 'arrays', 'personal_titles_ARRAY_prof', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Prof.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'personal_titles_ARRAY_rev', 'arrays', 'personal_titles_ARRAY_rev', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Rev.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_1', 'arrays', 'short_months_ARRAY_1', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Jan', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_10', 'arrays', 'short_months_ARRAY_10', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Oct', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_11', 'arrays', 'short_months_ARRAY_11', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Nov', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_12', 'arrays', 'short_months_ARRAY_12', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Dec', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_2', 'arrays', 'short_months_ARRAY_2', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Feb', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_3', 'arrays', 'short_months_ARRAY_3', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Mar', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_4', 'arrays', 'short_months_ARRAY_4', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Apr', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_5', 'arrays', 'short_months_ARRAY_5', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'May', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_6', 'arrays', 'short_months_ARRAY_6', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Jun', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_7', 'arrays', 'short_months_ARRAY_7', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Jul', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_8', 'arrays', 'short_months_ARRAY_8', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Aug', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_9', 'arrays', 'short_months_ARRAY_9', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Sep', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'status_ARRAY_1', 'arrays', 'status_ARRAY_1', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You are not loged in.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'status_ARRAY_123', 'arrays', 'status_ARRAY_123', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Your hosting account does not allow uploading such a large image.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'status_ARRAY_2', 'arrays', 'status_ARRAY_2', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Access denied. You have not requisite rights to.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'status_ARRAY_3', 'arrays', 'status_ARRAY_3', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Empty resultset.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'status_ARRAY_7', 'arrays', 'status_ARRAY_7', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'The operation is not allowed in demo mode.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'status_ARRAY_996', 'arrays', 'status_ARRAY_996', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No property for the reservation found', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'status_ARRAY_997', 'arrays', 'status_ARRAY_997', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No reservation found', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'status_ARRAY_998', 'arrays', 'status_ARRAY_998', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No permisions to edit the reservation', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'status_ARRAY_999', 'arrays', 'status_ARRAY_999', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No permisions to edit the property', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'status_ARRAY_9997', 'arrays', 'status_ARRAY_9997', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'E-Mail address already exist', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'status_ARRAY_9998', 'arrays', 'status_ARRAY_9998', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Your registration was successfull. Your account needs to be approved.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'status_ARRAY_9999', 'arrays', 'status_ARRAY_9999', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Your registration was successfull.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-10800', 'arrays', 'timezones_ARRAY_-10800', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT-03:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-14400', 'arrays', 'timezones_ARRAY_-14400', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT-04:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-18000', 'arrays', 'timezones_ARRAY_-18000', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT-05:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-21600', 'arrays', 'timezones_ARRAY_-21600', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT-06:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-25200', 'arrays', 'timezones_ARRAY_-25200', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT-07:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-28800', 'arrays', 'timezones_ARRAY_-28800', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT-08:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-32400', 'arrays', 'timezones_ARRAY_-32400', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT-09:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-3600', 'arrays', 'timezones_ARRAY_-3600', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT-01:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-36000', 'arrays', 'timezones_ARRAY_-36000', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT-10:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-39600', 'arrays', 'timezones_ARRAY_-39600', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT-11:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-43200', 'arrays', 'timezones_ARRAY_-43200', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT-12:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-7200', 'arrays', 'timezones_ARRAY_-7200', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT-02:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_0', 'arrays', 'timezones_ARRAY_0', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_10800', 'arrays', 'timezones_ARRAY_10800', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT+03:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_14400', 'arrays', 'timezones_ARRAY_14400', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT+04:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_18000', 'arrays', 'timezones_ARRAY_18000', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT+05:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_21600', 'arrays', 'timezones_ARRAY_21600', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT+06:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_25200', 'arrays', 'timezones_ARRAY_25200', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT+07:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_28800', 'arrays', 'timezones_ARRAY_28800', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT+08:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_32400', 'arrays', 'timezones_ARRAY_32400', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT+09:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_3600', 'arrays', 'timezones_ARRAY_3600', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT+01:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_36000', 'arrays', 'timezones_ARRAY_36000', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT+10:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_39600', 'arrays', 'timezones_ARRAY_39600', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT+11:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_43200', 'arrays', 'timezones_ARRAY_43200', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT+12:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_46800', 'arrays', 'timezones_ARRAY_46800', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT+13:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_7200', 'arrays', 'timezones_ARRAY_7200', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'GMT+02:00', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'u_statarr_ARRAY_F', 'arrays', 'u_statarr_ARRAY_F', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Inactive', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'u_statarr_ARRAY_T', 'arrays', 'u_statarr_ARRAY_T', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Active', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, '_yesno_ARRAY_F', 'arrays', '_yesno_ARRAY_F', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, '_yesno_ARRAY_T', 'arrays', '_yesno_ARRAY_T', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Yes', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'delete_selected', 'backend', 'Label / Delete selected', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Delete selected', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'delete_confirmation', 'backend', 'Label / delete confirmation', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Are you sure that you want to delete selected record(s)?', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblAll', 'backend', 'Label / All', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'email_taken', 'backend', 'Label / email taken', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Email address was already in use.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashLastLogin', 'backend', 'Label / Last login', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Last login', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuRoutes', 'backend', 'Menu / Routes', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Routes', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuInstall', 'backend', 'Label / Install', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Install', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuPreview', 'backend', 'Menu / Preview', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Preview', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblFrom', 'backend', 'Label / From', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'From', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTo', 'backend', 'Label / To', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'To', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblAddRoute', 'backend', 'Label / Add Route', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add Route', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoRoutesTitle', 'backend', 'Infobox / Route list title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Routes list', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoRoutesDesc', 'backend', 'Infobox / Route list description', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Below is a list of all routes. Routes are basic elements of the Bus Schedule system. They describe the start (departure) and the final (arrival) location of a bus trip and all intermediate stops. Here you can search, filter or sort routes. Using the arrow icon at the end of each route row you can create a reverse route or copy the selected route, which might help you in creating your routes list.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoAddRouteTitle', 'backend', 'Infobox / Add new route', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add new route', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoAddRouteDesc', 'backend', 'Infobox / Add new route description', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'The route represents the start (departure) and the final (arrival) location of a bus trip and all intermediate stops. To create a route, give it a title and add all the locations (bus stops) it has, starting with its start / departure location (Location 1) and adding as many location as you need using the \"Add +\" button. The last location you create will be the final (arrival) location. You can delete locations or re-order them using the icons that show at the end of each location row on mouse over.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'bs_field_required', 'backend', 'Label / This field is required', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This field is required.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTitle', 'backend', 'Label / Title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Title', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblLocation', 'backend', 'Label / Location', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Location', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblActive', 'backend', 'Label / Active', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Active', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblInactive', 'backend', 'Label / Inactive', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Inactive', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblLocations', 'backend', 'Label / Locations', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Locations', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDuration', 'backend', 'Label / Duration', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Duration', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblPrices', 'backend', 'Label / Prices', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Ticket Prices', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblUpdateRoute', 'backend', 'Label / Update Route', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Update Route', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUpdateRouteTitle', 'backend', 'Infobox / Update Route', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Update Route', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUpdateRouteDesc', 'backend', 'Infobox / Update Route Desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the form below to update the selected route. You can delete locations or re-order them using the icons that show at the end of each location row on mouse over.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AR01', 'arrays', 'error_titles_ARRAY_AR01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Locations Updated', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AR01', 'arrays', 'error_bodies_ARRAY_AR01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All changes made on route and locations have been saved.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AR03', 'arrays', 'error_titles_ARRAY_AR03', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Route Added', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AR03', 'arrays', 'error_bodies_ARRAY_AR03', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'A new route and it''s locations have been added to the system.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AR04', 'arrays', 'error_titles_ARRAY_AR04', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Route failed to add', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AR04', 'arrays', 'error_bodies_ARRAY_AR04', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'We are sorry that some error occurred and new route could not be added to the system.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AR08', 'arrays', 'error_titles_ARRAY_AR08', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Route not found', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AR08', 'arrays', 'error_bodies_ARRAY_AR08', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'We are sorry that the route you are looking for is missing.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUpdateDurationTitle', 'backend', 'Infobox / Update Duration', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Update Duration', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUpdateDurationDesc', 'backend', 'Infobox / Update Duration Desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Please use the grid below to define durations between locations of the route. The duration will be set in minute.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AR09', 'arrays', 'error_titles_ARRAY_AR09', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Duration Updated', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AR09', 'arrays', 'error_bodies_ARRAY_AR09', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All change you made on durations between location have been saved successfully.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AR10', 'arrays', 'error_titles_ARRAY_AR10', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Price Updated', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AR10', 'arrays', 'error_bodies_ARRAY_AR10', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All change you made on prices between locations have been saved successfully.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUpdatePriceTitle', 'backend', 'Infobox / Update Price', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Update Price', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUpdatePriceDesc', 'backend', 'Infobox / Update Price Desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Please use the grid below to define prices between locations of the route.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_time_format', 'backend', 'Options / Time format', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Time format', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuBuses', 'backend', 'Menu / Buses', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Buses', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblRoute', 'backend', 'Lable / Route', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Route', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblAddBus', 'backend', 'Label / Add Bus', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add Bus', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoBusesTitle', 'backend', 'Infobox / Buses Title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Buses List', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoBusesDesc', 'backend', 'Infobox / Buses Desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Below is a list of all buses you operate. Customers can book tickets for a specific bus trip. We call \"a trip\" a bus traveling on a specific date. Each bus has defined route, bus type, tickets price, departure and arrival times for each route location (bus stop), weekly schedule and a time period while it operates. If you operate several trips of one route per day, you need to set them as separate buses with their own departure and arrival time.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoAddBusTitle', 'backend', 'Infobox / Add New Bus', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add New Bus', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoAddBusDesc', 'backend', 'Infobox / Add New Bus Desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the form below to start creating your bus. You need to define a route that this bus operates on and a bus type. Then you''ll have to define departure and arrival time for each location (bus stop) along the selected route. You also have to choose which days of the week the bus is traveling and the time period this bus will operate. After saving you will be able to define the rest of the bus settings.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblPeriod', 'backend', 'Label / Period', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Period operating', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblRecurring', 'backend', 'Label / Recurring', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Recurring', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'weekdays_ARRAY_tuesday', 'arrays', 'weekdays_ARRAY_tuesday', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Every Tuesday', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'weekdays_ARRAY_wednesday', 'arrays', 'weekdays_ARRAY_wednesday', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Every Wednesday', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'weekdays_ARRAY_monday', 'arrays', 'weekdays_ARRAY_monday', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Every Monday', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'weekdays_ARRAY_thursday', 'arrays', 'weekdays_ARRAY_thursday', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Every Thursday', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'weekdays_ARRAY_friday', 'arrays', 'weekdays_ARRAY_friday', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Every Friday', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'weekdays_ARRAY_saturday', 'arrays', 'weekdays_ARRAY_saturday', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Every Saturday', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'weekdays_ARRAY_sunday', 'arrays', 'weekdays_ARRAY_sunday', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Every Sunday', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDepartureTime', 'backend', 'Lable / Departure Time', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Departure Time', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblArrivalTime', 'backend', 'Lable / Arrival Time', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Arrival Time', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABS03', 'arrays', 'error_titles_ARRAY_ABS03', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus Added', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABS03', 'arrays', 'error_bodies_ARRAY_ABS03', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'New bus has been added into the list.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTimes', 'backend', 'Label / Times', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'General Settings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTickets', 'backend', 'Label / Tickets', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Ticket Types', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUpdateTimeTitle', 'backend', 'Infobox / Update Times', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus General Settings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUpdateTimeDesc', 'backend', 'Infobox / Update Times Desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the form below and the rest of the tabs above to update the general settings of the selected bus. If you''ve just created new bus then you can continue with your bus settings using the tabs above.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblUpdateBus', 'backend', 'Label / Update Bus', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Update Bus', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABS01', 'arrays', 'error_titles_ARRAY_ABS01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus Updated', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABS01', 'arrays', 'error_bodies_ARRAY_ABS01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All changes you made on the times of bus have been saved successfully.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABS04', 'arrays', 'error_titles_ARRAY_ABS04', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus Failed to Add', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABS04', 'arrays', 'error_bodies_ARRAY_ABS04', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'We are sorry that there are some error occurred and the bus could not be added into the system.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABS08', 'arrays', 'error_titles_ARRAY_ABS08', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus not found', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABS08', 'arrays', 'error_bodies_ARRAY_ABS08', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'We are sorry that the bus you are looking for is missing.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblNotOperatingOn', 'backend', 'Label / Not operating on', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Out of service on', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnRemove', 'backend', 'Button / Remove', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Remove', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTicket', 'backend', 'Label / Ticket', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select Ticket Type', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUpdateTicketTitle', 'backend', 'Infobox / Update Tickets', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Ticket Types', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUpdateTicketDesc', 'backend', 'Infobox / Update Tickets Desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the form below to define ticket types available for this bus. Use the \"Add  +\" button to add as many ticket types as you need. Then you will be able to define ticket prices.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABS09', 'arrays', 'error_titles_ARRAY_ABS09', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Tickets Updated', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABS09', 'arrays', 'error_bodies_ARRAY_ABS09', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'The list of tickets you defined for the schedule has been saved successfully.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AS10', 'arrays', 'error_titles_ARRAY_AS10', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Prices Updated', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AS10', 'arrays', 'error_bodies_ARRAY_AS10', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All prices that you defined for the schedule have been saved.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuBusTypes', 'backend', 'Menu / Bus Types', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus Types', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoBusTypesTitle', 'backend', 'Infobox / Bus types title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus Types list', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoBusTypesDesc', 'backend', 'Infobox / Bus types desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Below is a list of all bus types. Each bus you create has to have bus type defined. Trough bus types you can define the number of seats and the seats map of each bus. This will let customers make bookings and reserve their seats and tickets.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblAddBusType', 'backend', 'Lable / Add bus type', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add bus type', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoAddBusTypeTitle', 'backend', 'Infobox / Add bus type title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add new bus type', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoAddBusTypeDesc', 'backend', 'Infobox / Add bus type desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the form below to create new bus type. You can upload and manage a seats map for the bus type. This will let your customers to select specific bus seats on the seats map at Tickets step during the booking process.   To upload a seats map use the browse button below and click on \"Save\" button. Only images in jpeg file format are acceptable. Once you upload and save you will be redirected to a page where you can manage the seats map and make it active for users. If you don''t want to use a seats map then you need to define the number of seats available for this bus type. In this case the system will automatically assign available seats to each booking and ticket.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSeatsMap', 'backend', 'Lable / Seats map', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Seats map', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSeatsCount', 'backend', 'Label / Seats count', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Number of seats', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblUpdateBusType', 'backend', 'Label / Update bus type', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Update bus type', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUpdateBusTypeTitle', 'backend', 'Infobox / Update bus type', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Update bus type', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUpdateBusTypeDesc', 'backend', 'Infobox / Update bus type desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the form below to edit the selected bus type. If you have uploaded a seat map you can either delete it (then you will have to set the \"Number of seats\" value) or manage it. To make the map active for users you need to set the available seats on the map. Just click on the map and a blue rectangle titled \"1\" will show. You can place the rectangle where appropriate via drag&drop. You can also change its size: point the cursor at rectangle''s angle, click and drag to the size you need. The rectangle added represents one seat on the map. Customers will be able to select seats by clicking on these rectangles at Tickets step during the booking process. You can add as many rectangles as your bus type has. The system will automatically count the number of seats this bus type has after saving the changes. To delete a rectangle click on it and Delete button will show below the map.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnDeleteMap', 'backend', 'Button / Delete map', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Delete map', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDeleteMapConfirm', 'backend', 'Label / Delete map confirm', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'If the map is deleted, all of seats you defined for this map will be remove as well. Are you sure that you want to delete the map?', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'buttons_ARRAY_delete', 'arrays', 'buttons_ARRAY_delete', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Delete', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'buttons_ARRAY_cancel', 'arrays', 'buttons_ARRAY_cancel', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cancel', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'buttons_ARRAY_save', 'arrays', 'buttons_ARRAY_save', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Save', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBusType', 'backend', 'Label / Bus type', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus type', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABT01', 'arrays', 'error_titles_ARRAY_ABT01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus type updated', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABT01', 'arrays', 'error_bodies_ARRAY_ABT01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All changes you made on the bus type have been saved successfully.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABT03', 'arrays', 'error_titles_ARRAY_ABT03', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus type added', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABT03', 'arrays', 'error_bodies_ARRAY_ABT03', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'A new bus type has been added to the list. Now you can edit vehicle information.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABT04', 'arrays', 'error_titles_ARRAY_ABT04', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus type failed to add', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABT04', 'arrays', 'error_bodies_ARRAY_ABT04', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'We are sorry that there was some error occurred, so the bus type could not be added successfully.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABT08', 'arrays', 'error_titles_ARRAY_ABT08', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus type not found', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABT08', 'arrays', 'error_bodies_ARRAY_ABT08', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'We are sorry that the bus type you are looking for is missing.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSeats', 'backend', 'Label / Seats', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Seat(s)', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDefineTickets', 'backend', 'Label / Define tickets', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You need to define tickets types first under Ticket Types tab.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblFromTo', 'backend', 'Lable / From - To', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Operates From - To (dates)', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDepartArrive', 'backend', 'Label / Depart - Arrive', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Depart - Arrive', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSeatsAvailable', 'backend', 'Label / Seats available', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Seats available', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSetSeatsCount', 'backend', 'Label / Set seats count', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Set seats count for each ticket type', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCount', 'backend', 'Lable / Count', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Count', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuBookings', 'backend', 'Menu / Bookings', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bookings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'booking_statuses_ARRAY_confirmed', 'arrays', 'booking_statuses_ARRAY_confirmed', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Confirmed', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'booking_statuses_ARRAY_cancelled', 'arrays', 'booking_statuses_ARRAY_cancelled', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cancelled', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'booking_statuses_ARRAY_pending', 'arrays', 'booking_statuses_ARRAY_pending', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Pending', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuBookingList', 'backend', 'Label / Booking list', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bookings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuAddBooking', 'backend', 'Label / Add booking', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add booking', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoBookingListTitle', 'backend', 'Infobox / Booking list', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Manage Bookings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoBookingListDesc', 'backend', 'Infobox / Booking list description', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Below is a list of all ticket bookings made. By default the new bookings stay on top. You will find some brief data about each booking and you can view details and/or edit booking by clicking on the Edit button at the end of each booking row. You can filter booking by their status. You can also search bookings using the search bar or the advanced search (click on the arrow button next to search bar).', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoAddBookingTitle', 'backend', 'Infobox / Add Booking Title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add New Booking', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoAddBookingDesc', 'backend', 'Infobox / Add Booking Desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the form below to add manually new booking. You need to fill in the required data in both tabs, Booking Details and Client Details. The system will calculate the payment data automatically.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingDetails', 'backend', 'Label / Booking Details', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking Details', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblClientDetails', 'backend', 'Label / Client Details', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Client Details', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDate', 'backend', 'Label / Date', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Date', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblUniqueID', 'backend', 'Label / Unique ID', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Unique ID', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTotal', 'backend', 'Label / Total', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Total', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblVoucherCode', 'backend', 'Label / Voucher code', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Voucher code', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblPaymentMethod', 'backend', 'Label / Payment method', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Payment method', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'payment_methods_ARRAY_creditcard', 'arrays', 'payment_methods_ARRAY_creditcard', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Credit card', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'payment_methods_ARRAY_cash', 'arrays', 'payment_methods_ARRAY_cash', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cash', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'payment_methods_ARRAY_bank', 'arrays', 'payment_methods_ARRAY_bank', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bank account', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCCType', 'backend', 'Label / CC type', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'CC type', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'cc_types_ARRAY_AmericanExpress', 'arrays', 'cc_types_ARRAY_AmericanExpress', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'American Express', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'cc_types_ARRAY_Maestro', 'arrays', 'cc_types_ARRAY_Maestro', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Maestro', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'cc_types_ARRAY_MasterCard', 'arrays', 'cc_types_ARRAY_MasterCard', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'MasterCard', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'cc_types_ARRAY_Visa', 'arrays', 'cc_types_ARRAY_Visa', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Visa', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCCNum', 'backend', 'Label / CC number', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'CC number', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCCExp', 'backend', 'Label / CC expiration date', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'CC expiration date', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCCCode', 'backend', 'Label / CC security code ', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'CC security code ', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingTitle', 'backend', 'Label / Title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Title', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingFname', 'backend', 'Label / First name', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'First name', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingLname', 'backend', 'Label / Last name', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Last name', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingPhone', 'backend', 'Lable / Phone', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Phone', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingEmail', 'backend', 'Lable / Email', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingNotes', 'backend', 'Lable / Notes', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Notes', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingCompany', 'backend', 'Lable / Company name', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Company name', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingAddress', 'backend', 'Lable / Address', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Address', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingCity', 'backend', 'Lable / City', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'City', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingState', 'backend', 'Lable / State', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'State', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingZip', 'backend', 'Lable / Zip code', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Zip code', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingCountry', 'backend', 'Lable / Country', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Country', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCopyRoute', 'backend', 'Label / Copy route', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Copy route', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblReverseRoute', 'backend', 'Label / Create reverse route', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Create reverse route', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuBooking', 'backend', 'Menu / Booking', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuGeneral', 'backend', 'Menu / General', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'General', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuBookingForm', 'backend', 'Menu / Booking form', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Checkout Form', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuConfirmation', 'backend', 'Menu / Confirmation', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Confirmation', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_deposit_payment', 'backend', 'Options / Deposit payment', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Deposit payment', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_deposit_payment_text', 'backend', 'Options / Deposit payment desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Percentage of the total booking amount that will be charged during the online booking.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_tax_payment', 'backend', 'Options / Tax payment', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Tax payment', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_booking_status', 'backend', 'Options / Booking status', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking status if not paid', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_booking_status_text', 'backend', 'Options / Booking status desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Set the default booking status should be if payment is not made.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_payment_status', 'backend', 'Options / Payment status', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking status if paid', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_payment_status_text', 'backend', 'Options / Payment status desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Set the default booking status should be if payment is made.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_thank_you_page', 'backend', 'Options / Thank you page', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Thank you page', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_thank_you_page_text', 'backend', 'Options / Thank you page desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'URL for the web page where your clients will be redirected after online payment', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_payment_disable', 'backend', 'Options / Payment disable', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Disable Payments', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_payment_disable_text', 'backend', 'Options / Payment disable', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select ''Yes'' if you want to disable payments and only collect booking details.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_allow_paypal', 'backend', 'Options / Allow paypal', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Allow payments with Paypal ', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_paypal_address', 'backend', 'Options / Paypal address', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'PayPal business email address ', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_allow_authorize', 'backend', 'Options / Allow authorize.net', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Allow payments with Authorize.net', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_authorize_transkey', 'backend', 'Options / Transaction key', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Authorize.net transaction key', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_authorize_merchant_id', 'backend', 'Options / Merchant ID', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Authorize.net merchant ID', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_authorize_timezone', 'backend', 'Options / Authorize timezone', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Authorize.net time zone', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_authorize_md5_hash', 'backend', 'Options / MD5 hash', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Authorize.net MD5 hash', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_allow_cash', 'backend', 'Options / Allow cash', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Allow cash payments', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_allow_creditcard', 'backend', 'Options / Allow credit card', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Collect Credit Card details for offline processing', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_allow_bank', 'backend', 'Options / Allow bank', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Provide Bank account details for wire transfers', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_bank_account', 'backend', 'Options / Bank account', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bank account', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_bf_include_title', 'backend', 'Options / include title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Title', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_bf_include_fname', 'backend', 'Options / First name', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'First name', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_bf_include_lname', 'backend', 'Options / Last name', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Last name', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_bf_include_phone', 'backend', 'Options / Phone', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Phone', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_bf_include_email', 'backend', 'Options / Email', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_bf_include_company', 'backend', 'Options / Company', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Company', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_bf_include_address', 'backend', 'Options / Address', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Address', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_bf_include_notes', 'backend', 'Options / Notes', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Notes', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_bf_include_promo', 'backend', 'Options / Voucher', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Voucher', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_bf_include_city', 'backend', 'Options / City', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'City', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_bf_include_state', 'backend', 'Options / State', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'State', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_bf_include_zip', 'backend', 'Options / Zip', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Zip', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_bf_include_country', 'backend', 'Options / Country', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Country', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_bf_include_captcha', 'backend', 'Options / Captcha', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Captcha', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblOptionClient', 'backend', 'Label / Client', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'To Customers', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblOptionAdministrator', 'backend', 'Label / Administrator', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'To Administrators', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoBookingFormTitle', 'backend', 'Infobox / Booking Form Options', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Checkout Form', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoBookingFormDesc', 'backend', 'Infobox / Booking form descriptoin', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Below you can enable and disable the checkout form fields that customers will have to complete. If you choose \"Yes (required)\" option then this field becomes mandatory and customers will not be able to proceed further without filling it in.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoBookingsTitle', 'backend', 'Infobox / Booking Options', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking Options', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoBookingsDesc', 'backend', 'Infobox / Booking Options desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the form below to set your payment and booking process options. It is important to define the deposit payment setting if you enable online payments, because this setting will define the amount that customers will be charged online. You can choose between several online payment methods and/or enable cash payments. If customers choose Cash payment method then the booking will be accepted without any online payment processing. Note that in this case the new booking status will still be as per \"Status of new bookings that are not paid\" setting. So if status is Pending and you do not confirm the booking, the seats reserved for this booking will be available for booking by other customers after \"Seats Pending Time\" expires.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBus', 'backend', 'Label / Bus', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSelectSeats', 'backend', 'Label / Select seats', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select seats', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'buttons_ARRAY_reselect', 'arrays', 'buttons_ARRAY_reselect', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Re-select', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'buttons_ARRAY_ok', 'arrays', 'buttons_ARRAY_ok', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'OK', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblEqualTo', 'backend', 'Label / Equal to', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Total seats count of all ticket types must be equal to seats available.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblValidate', 'backend', 'Label / Validation', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Validation', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABB01', 'arrays', 'error_titles_ARRAY_ABB01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking updated', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABB01', 'arrays', 'error_bodies_ARRAY_ABB01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All changes you made on the booking have been saved.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABB03', 'arrays', 'error_titles_ARRAY_ABB03', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking added', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABB03', 'arrays', 'error_bodies_ARRAY_ABB03', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'A new booking has been added to the list.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABB04', 'arrays', 'error_titles_ARRAY_ABB04', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking failed to add', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABB04', 'arrays', 'error_bodies_ARRAY_ABB04', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'We are sorry that there is some error occurred and the booking could not be added successfully.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABB08', 'arrays', 'error_titles_ARRAY_ABB08', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking not found', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABB08', 'arrays', 'error_bodies_ARRAY_ABB08', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'We are sorry that the booking you are looking for is missing.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblUpdateBooking', 'backend', 'Label / Update booking', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Update booking', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUpdateBookingTitle', 'backend', 'Infobox / Update booking title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Update booking', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUpdateBookingDesc', 'backend', 'Infobox / Update booking desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the from below to update the selected booking. Note that booking details and client details are separated into two tabs. Using the links in the right column of Booking details tab you can send new booking confirmation email to your clients or print booking tickets. You can use the \"Resend confirmation\" option if you have manually added the booking or have updated it and you wish to notify the customer about this.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuSchedule', 'backend', 'Menu / Schedule', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Schedule', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblNotOperating', 'backend', 'Label / Not operating', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Out of service', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoNotOperatingTitle', 'backend', 'Infobox / Date(s) not operating', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Date(s) out of service', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoNotOperatingDesc', 'backend', 'Infobox / Date(s) not operating desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Below you can define a list of dates when the bus will not operate (travel). This way you can manage better the bus schedule and forbid bookings on dates that are within the bus operating period of time, but for some reason the bus will be out of service. To add more dates on the list use the \"Add +\" button.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABS11', 'arrays', 'error_titles_ARRAY_ABS11', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Date(s) set', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABS11', 'arrays', 'error_bodies_ARRAY_ABS11', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Date(s) not operating that you defined have been saved successfully.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBusTypeTip', 'backend', 'Tip / Bus type', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You need to specify a bus type for this bus. This will define the number of available seats and the bus seats map if one is uploaded for the bus type.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblPeriodTip', 'backend', 'Tip / period', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Define the start date and the end date this bus will operate.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSubTotal', 'backend', 'Label / Sub-total', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Sub-total', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTax', 'backend', 'Label / Tax', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Tax', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblIpAddress', 'backend', 'Label / IP address', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'IP address', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCreatedOn', 'backend', 'Label / Created on', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Created on', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblResendConfirm', 'backend', 'Label / Resend confirmation', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Resend confirmation', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblPrintTickets', 'backend', 'Label / Print tickets', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Print tickets', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblClient', 'backend', 'Label / Client', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Client', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDateTime', 'backend', 'Label / Date / time', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Date / time', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBusRoute', 'backend', 'Label / Bus / Route', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus / Route', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDeleteSeat', 'backend', 'Label / Delete seat', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Delete seat', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoConfirmationTitle', 'backend', 'Infobox / Email confirmations', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Email confirmations', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoConfirmationDesc', 'backend', 'Infobox / Email confirmations description', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Below you can set the automated email and SMS notifications that will be sent to your customers. You can enable or disable notifications, edit their subject and edit the message using the available tokens for each notification. If you wish to use the SMS notification you need to set SMS valid API Key at SMS tab. Customers will receive the email notifications from sender as defined at \"Sender email (for email notifications)\" setting in Settings menu / General tab.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoConfirmation2Title', 'backend', 'Infobox / Email confirmations', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Email confirmations', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoConfirmation2Desc', 'backend', 'Infobox / Email confirmations description', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Below you can set the automated email and SMS notifications that will be sent to Bus Schedule system administrators. You can enable or disable notifications, edit their subject and edit the message using the available tokens for each notification. If you wish to use the SMS notification you need to set SMS valid API Key at SMS tab.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_confirmation_subject', 'backend', 'Options / Booking confirmation subject', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking confirmation subject', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_confirmation_message', 'backend', 'Options / Booking confirmation message', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking confirmation message', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_confirmation_message_text', 'backend', 'Options / Booking confirmation tokens', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '<u>Available Tokens:</u><br/><br/>{Title}<br/>{FirstName}<br/>{LastName}<br/>{Email}<br/>{Phone}<br/>{Notes}<br/>{Country}<br/>{City}<br/>{State}<br/>{Zip}<br/>{Address}<br/>{Company}<br/>{Date}<br/>{Time}<br/>{Bus}<br/>{Route}<br/>{Seats}<br/>{TicketTypesPrice}<br/>{UniqueID}<br/>{Total}<br/>{Tax}<br/>{PaymentMethod}<br/>{CCType}<br/>{CCNum}<br/>{CCExp}<br/>{CCSec}<br/>{PrintTickets}<br/>{CancelURL}<br/>', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_payment_subject', 'backend', 'Options / Payment confirmation subject', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Payment confirmation subject', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_payment_message', 'backend', 'Options / Payment confirmation message', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Payment confirmation message', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_payment_message_text', 'backend', 'Options / Booking confirmation tokens', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '<u>Available Tokens:</u><br/><br/>{Title}<br/>{FirstName}<br/>{LastName}<br/>{Email}<br/>{Phone}<br/>{Notes}<br/>{Country}<br/>{City}<br/>{State}<br/>{Zip}<br/>{Address}<br/>{Company}<br/>{Date}<br/>{Time}<br/>{Bus}<br/>{Route}<br/>{Seats}<br/>{TicketTypesPrice}<br/>{UniqueID}<br/>{Total}<br/>{Tax}<br/>{PaymentMethod}<br/>{CCType}<br/>{CCNum}<br/>{CCExp}<br/>{CCSec}<br/>{PrintTickets}<br/>{CancelURL}<br/>', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_cancel_subject', 'backend', 'Options / Cancel confirmation subject', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cancel confirmation subject', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_cancel_message', 'backend', 'Options / Cancel confirmation message', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cancel confirmation message', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_cancel_message_text', 'backend', 'Options / Cancel confirmation tokens', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '<u>Available Tokens:</u><br/><br/>{Title}<br/>{FirstName}<br/>{LastName}<br/>{Email}<br/>{Phone}<br/>{Notes}<br/>{Country}<br/>{City}<br/>{State}<br/>{Zip}<br/>{Address}<br/>{Company}<br/>{Date}<br/>{Time}<br/>{Bus}<br/>{Route}<br/>{Seats}<br/>{TicketTypesPrice}<br/>{UniqueID}<br/>{Total}<br/>{Tax}<br/>{PaymentMethod}<br/>{CCType}<br/>{CCNum}<br/>{CCExp}<br/>{CCSec}<br/>{PrintTickets}<br/>{CancelURL}<br/>', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblEmailTokens', 'backend', 'Label / Email tokens', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '<u>Available Tokens:</u><br/><br/>{Title}<br/>{FirstName}<br/>{LastName}<br/>{Email}<br/>{Phone}<br/>{Notes}<br/>{Country}<br/>{City}<br/>{State}<br/>{Zip}<br/>{Address}<br/>{Company}<br/>{Date}<br/>{Seat}<br/>{Bus}<br/>{Route}<br/>{Time}<br/>{TicketTypesPrice}<br/>{UniqueID}<br/>{Total}<br/>{Tax}<br/>{PaymentMethod}<br/>{CCType}<br/>{CCNum}<br/>{CCExp}<br/>{CCSec}<br/>{CancelURL}<br/>', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_admin_email_confirmation_subject', 'backend', 'Options / New booking received subject', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'New booking received subject', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_admin_email_confirmation_message', 'backend', 'Options / New booking received message', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'New booking received message', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_admin_email_payment_subject', 'backend', 'Options / New payment received subject', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'New payment received subject', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_admin_email_payment_message', 'backend', 'Options / New payment received message', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'New payment received message', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_admin_email_cancel_subject', 'backend', 'Options / Booking cancelled subject', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking cancelled subject', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_admin_email_cancel_message', 'backend', 'Options / Booking cancelled message', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking cancelled message', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO03', 'arrays', 'error_titles_ARRAY_AO03', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Checkout form fields updated.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO03', 'arrays', 'error_bodies_ARRAY_AO03', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All changes made to the checkout form have been saved successfully.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO02', 'arrays', 'error_titles_ARRAY_AO02', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking options updated', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO02', 'arrays', 'error_bodies_ARRAY_AO02', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All changes made to the booking options have been saved successfully.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO04', 'arrays', 'error_titles_ARRAY_AO04', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Confirmation emails updated', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO04', 'arrays', 'error_bodies_ARRAY_AO04', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All changes made to confirmation emails have been saved successfully.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuTicket', 'backend', 'Menu / Ticket', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Ticket', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_ticket_template', 'backend', 'Options / Ticket template', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Ticket template', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoTicketTemplateTitle', 'backend', 'Infobox / Ticket template', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Ticket template', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoTicketTemplateDesc', 'backend', 'Infobox / Ticket template desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the editor below to create your ticket template. You will find a default template suggested by us, but you can freely edit the content and to look of your ticket. You can add text and images and format the ticket. Use the tokens provided below to load booking information into the ticket. Customers will receive tickets along with the booking confirmation email and will be able to print them. You can print a ticket too from booking details page. ', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO05', 'arrays', 'error_titles_ARRAY_AO05', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Ticket template updated', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO05', 'arrays', 'error_bodies_ARRAY_AO05', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All changes made to the ticket template have been saved.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTemplateTokens', 'backend', 'Label / Template tokens', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '<u>Available Tokens:</u>
<br/>
{Title}<br/>
{FirstName}<br/>
{LastName}<br/>
{Email}<br/>
{Phone}<br/>
{Notes}<br/>
{Country}<br/>
{City}<br/>
{State}<br/>
{Zip}<br/>
{Address}<br/>
{Company}<br/>
{Date}<br/>
{Bus}<br/>
{Route}<br/>
{Seat}<br/>
{Time}<br/>
{From_Location}<br/>
{To_Location}<br/>
{Departure_Time}<br/>
{Arrival_Time}<br/>
{TicketType}<br/>
{UniqueID}<br/>
{Total}<br/>{Tax}<br/>
{PaymentMethod}<br/>
{CCType}<br/>
{CCNum}<br/>
{CCExp}<br/>
{CCSec}<br/>
{QRCode}<br/>
{CancelURL}<br/>', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingResend', 'backend', 'Lable / Resend confirmation', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Resend confirmation', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoResendEmailTitle', 'backend', 'Infobox / Resend confirmation', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Resend booking confirmation email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoResendEmailDesc', 'backend', 'Infobox / Resend confirmation desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the from below to modify the booking confirmation email subject and content. By default the email form is preloaded with the customers email and the content of \"Send payment confirmation email\" as defined in the automated notifications settings at Settings menu / Booking Tab / Notification sub-tab / To Customers. But you can freely edit the message. Use the \"Send\" button at the bottom to send the email.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblReminderTo', 'backend', 'Label / To', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'To', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblReminderSubject', 'backend', 'Label / Subject', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Subject', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblReminderMessage', 'backend', 'Label / Message', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Message', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCopyTicketPrices', 'backend', 'Label / Copy ticket prices', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Copy ticket prices from another bus', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCopyPrices', 'backend', 'Label / Copy prices', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Copy prices', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'buttons_ARRAY_copy', 'arrays', 'buttons_ARRAY_copy', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Copy', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_APC01', 'arrays', 'error_titles_ARRAY_APC01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Prices copied', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_APC01', 'arrays', 'error_bodies_ARRAY_APC01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Ticket prices have been copied successfully form another bus.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoScheduleTitle', 'backend', 'Infobox / Schedule title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Buses schedule', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoScheduleDesc', 'backend', 'Infobox / Schedule description', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Here you can see a list of buses that will departure today or the date selected by you. You can filter the list of buses by routes. You can also sort the list using the arrows into each column header. \"FT Tickets\" stands for the number of full trip tickets sold. \"Total Tickets\" stands for the total number of tickets sold for this bus / trip. You can directly add new booking for a selected bus using the \"Add booking\" button. You can view more details about the bus / trip or start some actions using the arrow next to \"Add booking\" button.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblToday', 'backend', 'Label / Today', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Today', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDeparture', 'backend', 'Label / Departure', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Departure', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblArrival', 'backend', 'Label / Arrival', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Arrival', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookedTickets', 'backend', 'Label / Booked tickets', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booked tickets', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookings', 'backend', 'Label / Bookings', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bookings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoScheduleBookingsTitle', 'backend', 'Infobox / Bookings title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Passengers List', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoScheduleBookingsDesc', 'backend', 'Infobox / Bookings description', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This is a list of all bookings made for the selected bus trip. You will find brief passengers information about each booking and the whole trip. Total passengers equals to total tickets sold. To review a list of passengers that will board on a specific location use the \"Start location\" drop down. To review booking details click on the name of the client. You can also print the list.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblNoBusBetween', 'backend', 'Label / No bus between', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No bus between these two locations', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSelectSeatsHint', 'backend', 'Label / Select seats hint', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Please click on seat(s) you want to book. If you change your mind, let click on Re-select button to make a new selection. Finally, please click on OK button to complete. ', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblAvailableSeats', 'backend', 'Label / Available seats', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Available seats', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSelectedSeats', 'backend', 'Label / Selected seats', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Selected seats', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookedSeats', 'backend', 'Label / Booked seats', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booked seats', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBusesOn', 'backend', 'Label / Buses on', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Buses on', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblPrintSchedule', 'backend', 'Label / Print schedule', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Print schedule', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCurrentDateTime', 'backend', 'Label / Current date/time', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Current date/time', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblPrintList', 'backend', 'Label / Print list', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Print list', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_date', 'frontend', 'Label / Date', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Date', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_from', 'frontend', 'Label / From', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'From', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_to', 'frontend', 'Label / To', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'To', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_choose', 'frontend', 'Label / Choose', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Choose', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_step_1', 'frontend', 'Label / Date & locations', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Date & locations', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_step_2', 'frontend', 'Label / Tickets', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Tickets', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_step_3', 'frontend', 'Label / Checkout', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Checkout', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_step_4', 'frontend', 'Label / Confirm', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Confirm', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_step_5', 'frontend', 'Label / Done!', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Done!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_button_check_availability', 'frontend', 'Button / Check Availability', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Check Availability', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_hours', 'frontend', 'Label / Hours', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Hours', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_hour', 'frontend', 'Label / Hour', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Hour', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_minutes', 'frontend', 'Label / Minutes', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Minutes', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_minute', 'frontend', 'Label / Minute', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Minute', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_journey_from', 'frontend', 'Label / Journey from', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Journey from', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_to', 'frontend', 'Label / to', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'to', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_date_departure', 'frontend', 'Label / Date of departure', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Date of departure', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_link_change_date', 'frontend', 'Label / Change date', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Change date', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_duration', 'frontend', 'Label / Duration', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Duration', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_tickets', 'frontend', 'Label / Tickets', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Tickets', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblFilterByRoute', 'backend', 'Label / Filter by route', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Filter by route', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblPassengersList', 'backend', 'Label / Passengers List', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Passengers List', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblOn', 'backend', 'Label / on', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'on', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSeatsList', 'backend', 'Lable / Seats List', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Seats List', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuNotifications', 'backend', 'Menu / Notifications', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Notifications', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblLegendEmails', 'backend', 'Label / Emails', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Emails', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblLegendSMS', 'backend', 'Label / SMS', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'SMS', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_confirmation', 'backend', 'Options / New booking received email', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'New booking received email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_confirmation_text', 'backend', 'Options / New booking received email', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select ''Yes'' if you want to send an email to clients after they have made new bookings. Otherwise select ''No''.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_payment', 'backend', 'Options / Send payment confirmation email', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Send payment confirmation email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_payment_text', 'backend', 'Options / Send payment confirmation email', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select ''Yes'' if you want to send confirmation email to clients after they have made a payment for their bookings.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_cancel', 'backend', 'Options / Send cancellation email', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Send cancellation email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_cancel_text', 'backend', 'Options / Send cancellation email', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select ''Yes'' if you want to send confirmation email to clients after they have cancelled their bookings.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_sms_confirmation_message', 'backend', 'Options / Booking reminder SMS', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'New booking SMS confirmation', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_sms_confirmation_message_text', 'backend', 'Options / Booking reminder SMS', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '<u>Available Tokens:</u><br/><br/>{Title}<br/>{FirstName}<br/>{LastName}<br/>{Email}<br/>{Date}<br/>{TicketTypesPrice}<br/>{UniqueID}<br/>{Total}<br/>{Tax}<br/>{Phone}', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_admin_email_confirmation_text', 'backend', 'Options / New booking received email', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select ''Yes'' if you want to receive email notifications when new booking has been made. Otherwise select ''No''.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_admin_email_payment_text', 'backend', 'Options / Send payment confirmation email', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select ''Yes'' if you want to receive email notifications when a payment has just been received. Otherwise select ''No''.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_admin_email_cancel_text', 'backend', 'Options / Send cancellation email', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select ''Yes'' if you want to receive email notifications when clients cancel their bookings. Otherwise select ''No''.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_admin_sms_confirmation_message', 'backend', 'Options / New Booking sms', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'New Booking sms', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_admin_sms_payment_message', 'backend', 'Options / Payment confirmation sms', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Payment confirmation sms', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_min_hour', 'backend', 'Options / Min hour', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Seats Pending Time', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_min_hour_text', 'backend', 'Options / Min hour desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'A period of time, while seats assigned to new bookings with Pending status will not be available for other bookings.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblMinutes', 'backend', 'Label / minutes', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'minutes', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingID', 'backend', 'Label / Booking ID', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking ID', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoSeatsListTitle', 'backend', 'Infobox / Seats list', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Seats list', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoSeatsListDesc', 'backend', 'Infobox / Seats list description', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Below is the list of seats of the bus. It will show which seats occupied and which seats available for the routes.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuDailySchedule', 'backend', 'Menu / Daily schedule', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Daily schedule', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuRouteTimetable', 'backend', 'Menu / Route Timetable', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Route Timetable', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoRouteTimetableTitle', 'backend', 'Infobox / Route timetable', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Route timetable', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoRouteTimetableDesc', 'backend', 'Infobox / Route timetable desc', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Here you can see a departure timetable of all buses of a specific route. Use the \"Select route\" drop-down to choose the route you wish to display. It is weekly timetable and you can browse weeks by the \"previous\" and \"next\" links above the timetable. You can also jump to a chosen date / week timetable using the calendar date picker. To view the seats list of a bus trip click on its departure time. On mouse over you will see the number of passengers (total tickets sold) for this trip.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSelectRoute', 'backend', 'Label / Select route', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select route', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblNextWeek', 'backend', 'Label / Next week', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Next week', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuReports', 'backend', 'Menu / Reports', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Reports', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoBusReportTitle', 'backend', 'Infobox / Bus Reports', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus Reports', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoBusReportDesc', 'backend', 'Infobox / Bus Reports description', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the form below to generate a report about the performance and revenues of a selected bus. You can choose between \"up to date\" report or report for chosen by you date to date time period.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoRouteReportTitle', 'backend', 'Infobox / Route Reports', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Route Reports', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoRouteReportDesc', 'backend', 'Infobox / Route Reports description', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the form below to generate a report about the total performance and revenues of all buses from a selected route. You can choose between \"up to date\" report or report for chosen by you date to date time period.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'time_scale_ARRAY_uptodate', 'arrays', 'time_scale_ARRAY_uptodate', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Up to date', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'time_scale_ARRAY_period', 'arrays', 'time_scale_ARRAY_period', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Date to date', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTimeScale', 'backend', 'Label / Time scale', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Time scale', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnGenerate', 'backend', 'Button / Generate', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Generate', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTimePeriod', 'backend', 'Label / Time period', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Time period', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTotalTravels', 'backend', 'Label / Total travels', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Total travels', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTotalBookings', 'backend', 'Label / Total bookings', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Total bookings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTotalTicketsSold', 'backend', 'Label / Total Tickets sold', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Total Tickets sold', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTotalIncome', 'backend', 'Label / Total Income', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Total Income', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblRouteTrips', 'backend', 'Label / Route trips', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Route trips', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTotalFull', 'backend', 'Label / Total full length', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Total Full Trip Tickets', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTotalPartly', 'backend', 'Label / Total partly', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Total Partly Trip Tickets', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTimetable', 'backend', 'Label / Timetable', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Timetable', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTravels', 'backend', 'Label / Travels', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Travels', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblNumberBookings', 'backend', 'Label / Number Bookings', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Number Bookings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblNumberTickets', 'backend', 'Label / Number Tickets', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Number Tickets', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTotalAmount', 'backend', 'Label / Total Amount', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Total Amount', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTicketTypes', 'backend', 'Label / Ticket types', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Ticket types', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblPhone', 'backend', 'Label / Phone', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Phone', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblNotes', 'backend', 'Label / Notes', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Notes', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTotalBuses', 'backend', 'Label / Total Buses', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Total Buses', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBuses', 'backend', 'Label / Buses', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Buses', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblPrevWeek', 'backend', 'Label / Previous week', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Previous week', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblPassengers', 'backend', 'Label / passengers', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'passengers', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblPassenger', 'backend', 'Label / passenger', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'passenger', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTotalTickets', 'backend', 'Label / Total tickets', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Total Tickets', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblNumberOfTickets', 'backend', 'Label / Number of tickets', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Number of tickets', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTotalPassengers', 'backend', 'Label / Total passengers', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Total passengers', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_sender_email', 'backend', 'Options / Sender email', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Sender email (for email notifications)', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnPrint', 'backend', 'Button / Print', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Print', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'switch_ARRAY_on', 'arrays', 'switch_ARRAY_on', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'On', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'switch_ARRAY_off', 'arrays', 'switch_ARRAY_off', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Off', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblT', 'backend', 'Label / T', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'T', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_select', 'frontend', 'Label / Select', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_seats', 'frontend', 'Label / seats', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'seats', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_seat', 'frontend', 'Label / seat', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'seat', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_selected_seats', 'frontend', 'Label / Selected seat(s)', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Selected seat(s)', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_reselect', 'frontend', 'Label / Choose other seat(s)', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Choose other seat(s)', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_button_checkout', 'frontend', 'Button / Checkout', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Checkout', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_button_cancel', 'frontend', 'Button / Cancel', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cancel', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_validation_tickets', 'frontend', 'Label / You need to select at least one ticket.', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You need to select at least one ticket.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_validation_seats', 'frontend', 'Label / You need to select seat(s).', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You need to select seat(s).', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuTerms', 'backend', 'Menu / Terms', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Terms', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoTermsTitle', 'backend', 'Infobox / Terms and Conditions', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Terms and Conditions', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoTermsDesc', 'backend', 'Infobox / Terms and Conditions', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the from below to fill in your Terms and Conditions that are visible at Checkout step during the booking process. Customers must to agree with your Terms and Conditions to be able to make a reservation.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_terms', 'backend', 'Options / Terms and conditions', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Terms and Conditions', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO06', 'arrays', 'error_titles_ARRAY_AO06', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Terms and Conditions updated', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO06', 'arrays', 'error_bodies_ARRAY_AO06', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All changes you made on the Terms and Conditions options have been saved.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_title', 'frontend', 'Label / Title', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Title', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_personal_details', 'frontend', 'Label / Personal Details', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Personal Details', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_booking_details', 'frontend', 'Label / Booking Details', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking Details', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_departure_time', 'frontend', 'Label / Departure time', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Departure time', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_arrival_time', 'frontend', 'Label / Arrival time', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Arrival time', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_link_change_seats', 'frontend', 'Link / Change seats & tickets', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Change seats & tickets', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_fname', 'frontend', 'Label / First name', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'First name', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_lname', 'frontend', 'Label / Last name', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Last name', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_phone', 'frontend', 'Label / Phone', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Phone', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_email', 'frontend', 'Label / Email', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_company', 'frontend', 'Label / Company', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Company', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_notes', 'frontend', 'Label / Notes', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Notes', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_address', 'frontend', 'Label / Address', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Address', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_city', 'frontend', 'Label / City', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'City', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_state', 'frontend', 'Label / State', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'State', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_zip', 'frontend', 'Label / Zip', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Zip', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_country', 'frontend', 'Label / Country', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Country', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_payment_medthod', 'frontend', 'Label / Payment method', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Payment method', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_cc_type', 'frontend', 'Label / CC Type', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'CC Type', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_cc_num', 'frontend', 'Label / CC Number', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'CC Number', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_cc_exp', 'frontend', 'Label / CC Expiration', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'CC Expiration', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_cc_code', 'frontend', 'Label / CC Code', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'CC Code', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_sub_total', 'frontend', 'Label / Sub-total', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Sub-total', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_tax', 'frontend', 'Label / Tax', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Tax', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_total', 'frontend', 'Label / Total', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Total', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_deposit', 'frontend', 'Label / Deposit', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Deposit', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_captcha', 'frontend', 'Label / Captcha', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Captcha', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_terms_conditions', 'frontend', 'Label / Terms and conditions', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Terms and conditions', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_agree', 'frontend', 'Label / Agreement', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'I have read and accepted booking', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_button_back', 'frontend', 'Button / Back', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Back', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_button_preview', 'frontend', 'Button / Preview & Confirm', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Preview & Confirm', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_button_submit', 'frontend', 'Button / Submit', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Submit', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_required_field', 'frontend', 'Label / This field is required.', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This field is required.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_invalid_email', 'frontend', 'Label / Email is not valid.', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Email is not valid.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_incorrect_captcha', 'frontend', 'Label / Captcha is not correct.', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Captcha is not correct.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_button_confirm', 'frontend', 'Button / Confirm', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Confirm', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_from', 'frontend', 'Label / from', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'from', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDeposit', 'backend', 'Label / Deposit', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Deposit', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_messages_ARRAY_0', 'arrays', 'front_messages_ARRAY_0', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking is being processed...', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_messages_ARRAY_1', 'arrays', 'front_messages_ARRAY_1', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Your booking is saved. Redirecting to PayPal...', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_messages_ARRAY_2', 'arrays', 'front_messages_ARRAY_2', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Your booking is saved. Redirecting to Authorize.net...', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_messages_ARRAY_3', 'arrays', 'front_messages_ARRAY_3', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Your booking is saved. [STAG]Start over[ETAG].', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_messages_ARRAY_4', 'arrays', 'front_messages_ARRAY_4', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking failed to save. [STAG]Start over[ETAG].', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_bus_schedule', 'frontend', 'Label / Bus Schedule', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus Schedule', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_messages_ARRAY_5', 'arrays', 'front_messages_ARRAY_5', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There is a problem processing your request. Please, [STAG]start over[ETAG].', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_no_bus_available', 'frontend', 'Label / No bus available', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There is no available bus.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'cancel_err_ARRAY_200', 'arrays', 'cancel_err_ARRAY_200', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking has been cancelled successfully.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'cancel_err_ARRAY_1', 'arrays', 'cancel_err_ARRAY_1', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Missing parameters', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'cancel_err_ARRAY_2', 'arrays', 'cancel_err_ARRAY_2', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking with such ID does not exist.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'cancel_err_ARRAY_3', 'arrays', 'cancel_err_ARRAY_3', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Security hash did not match.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'cancel_err_ARRAY_4', 'arrays', 'cancel_err_ARRAY_4', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking is already cancelled.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_cancel_heading', 'frontend', 'Label / Cancel heading', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Your booking details', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_cancel_time', 'frontend', 'Label / Time', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Time', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_cancel_booking_date', 'frontend', 'Label / Booking date', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking date', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_cancel_bus', 'frontend', 'Label / Bus', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_cancel_route', 'frontend', 'Label / Route', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Route', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_cancel_tickets', 'frontend', 'Label / Tickets', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Tickets', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_booking_created', 'frontend', 'Label / Booking created', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking created', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_txn_id', 'frontend', 'Label / Paypal Transaction ID', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Paypal Transaction ID', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_processed_on', 'frontend', 'Label / Processed on', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Processed on', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_personal_details', 'frontend', 'Label / Personal Details', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Personal Details', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_confirm', 'frontend', 'Label / Confirm', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Confirm', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_cancel_booking_id', 'frontend', 'Label / Booking ID', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking ID', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashTodayBookings', 'backend', 'Label / new bookings today', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'new bookings today', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashTodayBooking', 'backend', 'Label / new booking today', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'new booking today', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashTodayBusesDept', 'backend', 'Label / Buses to departure today', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Buses to departure today', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashTodayBusDept', 'backend', 'Label / Bus to departure today', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus to departure today', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashRoutes', 'backend', 'Label / Routes', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Routes', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashRoute', 'backend', 'Label / Route', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Route', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashBuses', 'backend', 'Label / Buses', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Buses', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashBus', 'backend', 'Label / Bus', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashLatestBookings', 'backend', 'Label / Latest Bookings', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Latest Bookings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashViewAll', 'backend', 'Label / view all', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'view all', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashNextDeparture', 'backend', 'Label / Next Departures', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Next Departures', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashBriefInfo', 'backend', 'Label / Brief Info', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Brief Info', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashNoBooking', 'backend', 'Label / No bookings found', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No bookings found', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblAt', 'backend', 'Label / at', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'at', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTotalTocketsSold', 'backend', 'Label / Total Tickets sold', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Total Tickets sold', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblViewPassengersList', 'backend', 'Label / View passengers list', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'View passengers list', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblViewSeatsList', 'backend', 'Label / View seats list', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'View seats list', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashNoBuses', 'backend', 'Label / No buses found', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No buses found', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashTimetable', 'backend', 'Label / Timetable', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Timetable', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblConfirmedBookings', 'backend', 'Label / Confirmed Bookings', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Confirmed Bookings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTotalRevenue', 'backend', 'Label / Total Revenue', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Total Revenue', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblFrontendLanguages', 'backend', 'Label / Front-end Languages', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Front-end Languages', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblLastBackupAt', 'backend', 'Label / Last backup at', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Last backup at', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblMakeBackup', 'backend', 'Label / Make a backup', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Make a backup', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSeatsLegends', 'backend', 'Label / Seat legends', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'On: number of new passengers that will board on the bus at the bus stop<br/>
Off: number of passengers that will get down and leave at the bus stop<br/>
T: total number of passengers that the bus should departure with them on board.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_validation_invalid_seats', 'frontend', 'Label / Invalid seats selected', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You must select {seats} seat(s).', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblPrintPassengersList', 'backend', 'Label / Print passengers list', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Print passengers list', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblPrintSeatsList', 'backend', 'Label / Print seats list', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Print seats list', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblViewTripBookings', 'backend', 'Label / View trip bookings', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'View trip bookings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblEditBus', 'backend', 'Label / Edit bus', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Edit bus', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCancelBus', 'backend', 'Label / Cancel bus', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cancel bus', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoContentTitle', 'backend', 'Infobox / Content', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Content', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoContentDesc', 'backend', 'Infobox / Content', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the form below to upload an image and fill in text content that will be shown at screen one on the front-end \"Date & Location\" step. If image, text or both are not loaded, then they will just not show. This is not a mandatory form.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuContent', 'backend', 'Menu / Content', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Content', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO07', 'arrays', 'error_titles_ARRAY_AO07', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Content updated', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO07', 'arrays', 'error_bodies_ARRAY_AO07', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All changes made to the content have been saved.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblImage', 'backend', 'Label / Image', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Image', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDeleteImage', 'backend', 'Label / Delete image', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Delete image', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDeleteConfirmation', 'backend', 'Label / Delete image confirmation', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Are you sure that you want to delete this image?', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblContent', 'backend', 'Label / Content', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Content', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblStartLocation', 'backend', 'Label / Start location', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Start location', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblInstallJs1_title', 'backend', 'Label / Install code', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Install code', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblInstallJs1_body', 'backend', 'Label / Install code', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Copy the code below and put it on your web page. It will show the front end booking engine. Please, note that the code should be used on a web page from the same domain name where script is installed.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblInstallConfig', 'backend', 'Label / Language options', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Language options', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblInstallConfigLocale', 'backend', 'Label / Language', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Language', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblInstallConfigHide', 'backend', 'Label / Hide language selector ', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Hide language selector ', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblArrive', 'backend', 'Label / Arrive', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Arrive', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_bus', 'frontend', 'Label / Bus', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_available_seats', 'frontend', 'Label / Seats', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Available seats', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_departure_from', 'frontend', 'Label / Departure', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Departure from', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_arrive_to', 'frontend', 'Label / Arrive', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Arrive to', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_available', 'frontend', 'Label / Available', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Available', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_selected', 'frontend', 'Label / Selected', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Selected', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_booked', 'frontend', 'Label / Booked', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booked', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_prev', 'frontend', 'Label / prev', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'prev', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_next', 'frontend', 'Label / next', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'next', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_journey', 'frontend', 'Label / Journey', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Journey', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_payment', 'frontend', 'Label / Payment', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Payment', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_date', 'frontend', 'Label / Date', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Date', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_at', 'frontend', 'label / at', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'at', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_tickets_total', 'frontend', 'label / Tickets total', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Tickets total', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_booking_not_found', 'frontend', 'label / Booking not found', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Sorry! we could not find your booking in the system.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_hash_not_match', 'frontend', 'label / Hash not match', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Sorry! Hash value does not match.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUsersTitle', 'backend', 'Label / Users', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Users', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUsersDesc', 'backend', 'Label / Users', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Below is a list of all users that have access to the system back end. There are two types of users - Administrators and Editors. Administrators have full access to the system. Editors cannot access Reports, Settings, Users and Install menus.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoAddUserTitle', 'backend', 'Label / Add new user', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add new user', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoAddUserDesc', 'backend', 'Label / Add new user', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the form below to add new user with access to the back end of the system.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoEditUserTitle', 'backend', 'Label / Edit user', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Edit user', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoEditUserDesc', 'backend', 'Label / Edit user', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the form below to update user profile.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoTicketPricesTitle', 'backend', 'Label / Ticket Prices', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Ticket Prices', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoTicketPricesDesc', 'backend', 'Label / Ticket Prices', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Below you can and have to define prices for the full length trip and for all partial trips available if the route has intermediate bus stops. Note that you will need to set prices for each ticket type separately. For speeding up the process you can copy ticket prices from another bus that operates on the same route.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblMinute', 'backend', 'Label / minutes', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'minute(s)', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSearchBy', 'backend', 'Label / Search by', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Search by ID, client name or email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblMap', 'backend', 'Label / Map', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Map', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblFTTickets', 'backend', 'Label / FT Tickets', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'FT Tickets', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'bs_seats_required', 'backend', 'Label / Seats required', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You have to set up at least one seat on the map.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblAssignedSeats', 'backend', 'Label / Assigned seats', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Number of seats selected must be equal to number of tickets.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'print_statuses_ARRAY_100', 'arrays', 'print_statuses_ARRAY_100', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No data found.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'print_statuses_ARRAY_101', 'arrays', 'print_statuses_ARRAY_101', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Date parameter is missing.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'print_statuses_ARRAY_102', 'arrays', 'print_statuses_ARRAY_102', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus parameter is missing.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'print_statuses_ARRAY_103', 'arrays', 'print_statuses_ARRAY_103', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bus with such ID doesn''t not exist.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_messages_ARRAY_6', 'arrays', 'front_messages_ARRAY_6', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'We are sorry, but your booking failed. The available seat(s) for the selected bus have finished while you were placing your order. You can [STAG]start over[ETAG] searching for other buses or dates.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_validation_cc_expired', 'frontend', 'Label / CC expired', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Your credit card was expired.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblOverlappingSeats', 'backend', 'Label / Overlapping Seats', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Overlapping Seats', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblAnd', 'backend', 'Label / and', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'and', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblNoOverlapping', 'backend', 'Label / No overlapping seats found', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No overlapping seats found', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_notify', 'backend', 'Options / Seats pending time expired email', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Seats pending time expired email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_notify_text', 'backend', 'Options / Seats pending time expired email', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select ''Yes'' if you want to send an email to clients if their seats are no longer reserved. Valid for pending bookings only.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_notify_subject', 'backend', 'Options / Seats expired subject', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Seats pending time expired email subject', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_notify_message_text', 'backend', 'Options / Seats expired', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '<u>Available Tokens:</u><br/><br/>{Title}<br/>{FirstName}<br/>{LastName}<br/>{Email}<br/>{Phone}<br/>{Notes}<br/>{Country}<br/>{City}<br/>{State}<br/>{Zip}<br/>{Address}<br/>{Company}<br/>{Date}<br/>{Time}<br/>{Bus}<br/>{Route}<br/>{Seats}<br/>{TicketTypesPrice}<br/>{UniqueID}<br/>{Total}<br/>{Tax}<br/>{PaymentMethod}<br/>{CCType}<br/>{CCNum}<br/>{CCExp}<br/>{CCSec}<br/>{PrintTickets}<br/>{CancelURL}<br/>', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_notify_message', 'backend', 'Options / Seats expired message', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Seats pending time expired email message', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCronScript', 'backend', 'Label / Cron script', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cron script', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCronScriptText', 'backend', 'Label / Cron script text', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'In order to send ''Seats pending time expired email'', you have to set the cron job. On most hosting accounts you should use the text on the right to set up the cron job.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCronJobCompleted', 'backend', 'Label / CRON job completed.', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'CRON job completed.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABT10', 'arrays', 'error_titles_ARRAY_ABT10', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'File size exceeded', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABT10', 'arrays', 'error_bodies_ARRAY_ABT10', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Uploaded image is too big. Please, upload smaller image.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABT09', 'arrays', 'error_titles_ARRAY_ABT09', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'File size exceeded', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABT09', 'arrays', 'error_bodies_ARRAY_ABT09', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'New bus type has been added, but uploaded image is too big. Please, upload smaller image.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCities', 'backend', 'Label / Cities', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cities', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCity', 'backend', 'Label / City', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'City', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSameCity', 'backend', 'Label / Same city name', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'The city name was already used.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ACT01', 'arrays', 'error_titles_ARRAY_ACT01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'City updated', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ACT01', 'arrays', 'error_bodies_ARRAY_ACT01', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'City information has been updated.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ACT03', 'arrays', 'error_titles_ARRAY_ACT03', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'City added', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ACT03', 'arrays', 'error_bodies_ARRAY_ACT03', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'New city has been added into the list.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ACT04', 'arrays', 'error_titles_ARRAY_ACT04', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'City not added', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ACT04', 'arrays', 'error_bodies_ARRAY_ACT04', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'We are sorry that new city could not be added into the list.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ACT08', 'arrays', 'error_titles_ARRAY_ACT08', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'City not found', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ACT08', 'arrays', 'error_bodies_ARRAY_ACT08', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'We are sorry that the city you are looking for is missing.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoAddCityTitle', 'backend', 'Infobox / Add City', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add City', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoAddCityDesc', 'backend', 'Infobox / Add City', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Please enter the city name and click ''Save'' button to add new city.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUpdateCityTitle', 'backend', 'Infobox / Update City', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Update City', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoUpdateCityDesc', 'backend', 'Infobox / Update City', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You can modify the city name and click on ''Save'' button to update the city information.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoCitiesTitle', 'backend', 'Infobox / Cities List', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cities List', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoCitiesDesc', 'backend', 'Infobox / Cities List', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You can find below the list of cities that you can use to assign to a specific route. If you want to add new city, click on the ''Add +'' button.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSameLocation', 'backend', 'Label / Same location', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Same Location', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSameLocationText', 'backend', 'Label / Same location', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'The location was already chosen. Please select another one.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblNoCopyPrice', 'backend', 'Label / No copy price', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There is no other bus for that route.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABT05', 'arrays', 'error_titles_ARRAY_ABT05', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Image size too large', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABT05', 'arrays', 'error_bodies_ARRAY_ABT05', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'New bus type could not be added because image size too large and your server cannot upload it. Maximum allowed size is {SIZE}. Please, upload smaller image.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABT06', 'arrays', 'error_titles_ARRAY_ABT06', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Image size too large', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABT06', 'arrays', 'error_bodies_ARRAY_ABT06', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'The bus type could not be updated because image size too large and your server cannot upload it. Maximum allowed size is {SIZE}. Please, upload smaller image.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCitiesPrompt', 'backend', 'Label / Cities prompt', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You need to add cities first {STAG}here{ETAG}.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblPositiveNumber', 'backend', 'Label / Please enter positive number', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Please enter positive number', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AB09', 'arrays', 'error_titles_ARRAY_AB09', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Email sent!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AB09', 'arrays', 'error_bodies_ARRAY_AB09', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'The confirmation email has been re-sent to the client.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'plugin_backup_size', 'backend', 'Plugin / Size', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Size', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'plugin_backup_sizeXXXXXX', 'backend', 'Plugin / Size', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'SizeXXXX', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'plugin_country_revert_status', 'backend', 'Plugin / Revert status', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Revert status', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDefineSeats', 'backend', 'Label / Define seats', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Once you upload the image you will be able to define seats.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABT11', 'arrays', 'error_titles_ARRAY_ABT11', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No permissions', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABT11', 'arrays', 'error_bodies_ARRAY_ABT11', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Directory app/web/upload/bus_types has no permissions to upload seat maps. Please set permissions to 777.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AS09', 'arrays', 'error_titles_ARRAY_AS09', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Missing parameters', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AS09', 'arrays', 'error_bodies_ARRAY_AS09', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'The list could not be loaded correctly because of missing parameters.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_days_ARRAY_0', 'arrays', 'short_days_ARRAY_0', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Su', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_days_ARRAY_1', 'arrays', 'short_days_ARRAY_1', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Mo', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_days_ARRAY_2', 'arrays', 'short_days_ARRAY_2', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Tu', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_days_ARRAY_3', 'arrays', 'short_days_ARRAY_3', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'We', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_days_ARRAY_4', 'arrays', 'short_days_ARRAY_4', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Th', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_days_ARRAY_5', 'arrays', 'short_days_ARRAY_5', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Fr', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'short_days_ARRAY_6', 'arrays', 'short_days_ARRAY_6', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Sa', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'cancel_err_ARRAY_5', 'arrays', 'cancel_err_ARRAY_5', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You cannot cancel this booking because the bus already started.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingNotConfirmed', 'backend', 'Label / The booking has not been confirmed yet.', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'The booking has been cancelled and cannot be printed.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblEditRouteDisabled', 'backend', 'Label / You have booking for this route and cannot edit it', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You have booking for this route and cannot edit it.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblEditRouteImpossible', 'backend', 'Label / Edit route is impossible', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There are {NUMBER} bookings and changes to this route are not possible.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblNoDatesAdded', 'backend', 'Label / No dates added.', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No dates added.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoPreviewTitle', 'backend', 'Infobox / Preview front end', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Preview front end', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoPreviewDesc', 'backend', 'Infobox / Preview front end', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There are multiple color schemes available for the front end. Click on each of the thumbnails below to preview it. Click on \"Use this theme\" button for the theme you want to use.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblChooseTheme', 'backend', 'Label / Choose theme', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Choose theme', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_1', 'arrays', 'option_themes_ARRAY_1', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 1', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_2', 'arrays', 'option_themes_ARRAY_2', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 2', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_3', 'arrays', 'option_themes_ARRAY_3', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 3', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_4', 'arrays', 'option_themes_ARRAY_4', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 4', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_5', 'arrays', 'option_themes_ARRAY_5', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 5', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_6', 'arrays', 'option_themes_ARRAY_6', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 6', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_7', 'arrays', 'option_themes_ARRAY_7', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 7', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_8', 'arrays', 'option_themes_ARRAY_8', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 8', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_9', 'arrays', 'option_themes_ARRAY_9', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 9', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_10', 'arrays', 'option_themes_ARRAY_10', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 10', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblCurrentlyInUse', 'backend', 'Label / Currently in use', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Currently in use', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnUseThisTheme', 'backend', 'Label / Use this theme', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use this theme', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblReturn', 'backend', 'lblReturn', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Return route', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblReturnTicket', 'backend', 'lblReturnTicket', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Return ticket price', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_return_ticket', 'frontend', 'front_return_ticket', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Return ticket', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_return_date', 'frontend', 'front_return_date', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Return date', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblIsReturn', 'backend', 'lblIsReturn', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Return ticket', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblReturnDate', 'backend', 'lblReturnDate', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Return date', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_roundtrip_price', 'frontend', 'front_roundtrip_price', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Price', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_return_seats', 'frontend', 'front_return_seats', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Return seats', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblPickupBooking', 'backend', 'lblPickupBooking', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Pickup booking', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblReturnBooking', 'backend', 'lblReturnBooking', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Return booking', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblReturnBus', 'backend', 'lblReturnBus', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Return bus', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblReturnSeats', 'backend', 'lblReturnSeats', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Return seat(s)', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_no_return_bus_available', 'backend', 'front_no_return_bus_available', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There is no available bus for your return trip', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_one_way', 'frontend', 'Label / One way', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'One way', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_roundtrip', 'frontend', 'Label / Roundtrip', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Roundtrip', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_departing', 'frontend', 'Label / Departing', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Departing', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_returning', 'frontend', 'Label / Returning', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Returning', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_destinations', 'frontend', 'Label / Destinations', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Destinations', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDiscoutIfReturn', 'backend', 'Label / Discount if return ticket', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Discount if return ticket', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AB10', 'arrays', 'error_titles_ARRAY_AB10', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Email not sent!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AB10', 'arrays', 'error_bodies_ARRAY_AB10', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'The confirmation email has not been sent to the client successfully. Please try again.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblPendingBookingCannotPrint', 'backend', 'Label / Reservation should be confirmed to print the ticket(s).', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Reservation should be confirmed to print the ticket(s).', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuInstallPreview', 'backend', 'Menu / Install & Preview', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Install & Preview', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_sms_payment_message', 'backend', 'Options / SMS confirmation sent after payment', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'SMS confirmation sent after payment', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_sms_payment_message_text', 'backend', 'Options / SMS confirmation sent after payment', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '<u>Available Tokens:</u><br/><br/>{Title}<br/>{FirstName}<br/>{LastName}<br/>{Email}<br/>{Date}<br/>{TicketTypesPrice}<br/>{UniqueID}<br/>{Total}<br/>{Tax}<br/>{Phone}', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_btn_close', 'frontend', 'Label / Close', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Close', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_round_trip_tickets_error', 'frontend', 'Label / Round trip select ticket error', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Number of tickets for round trip cannot be greater than number of tickets for one trip.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_cancel_after_pending_time', 'backend', 'Label / Cancelled After "Seats Pending Time"', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cancelled After \"Seats Pending Time\"', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_nextMonth', 'arrays', 'datepicker_tooltips_ARRAY_nextMonth', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Next Month', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_selectYear', 'arrays', 'datepicker_tooltips_ARRAY_selectYear', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select Year', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_prevYear', 'arrays', 'datepicker_tooltips_ARRAY_prevYear', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Previous Year', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_nextYear', 'arrays', 'datepicker_tooltips_ARRAY_nextYear', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Next Year', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_selectDecade', 'arrays', 'datepicker_tooltips_ARRAY_selectDecade', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select Decade', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_prevDecade', 'arrays', 'datepicker_tooltips_ARRAY_prevDecade', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Previous Decade', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_nextDecade', 'arrays', 'datepicker_tooltips_ARRAY_nextDecade', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Next Decade', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_prevCentury', 'arrays', 'datepicker_tooltips_ARRAY_prevCentury', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Previous Century', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_nextCentury', 'arrays', 'datepicker_tooltips_ARRAY_nextCentury', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Next Century', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_pickHour', 'arrays', 'datepicker_tooltips_ARRAY_pickHour', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Pick Hour', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_incrementHour', 'arrays', 'datepicker_tooltips_ARRAY_incrementHour', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Increment Hour', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_decrementHour', 'arrays', 'datepicker_tooltips_ARRAY_decrementHour', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Decrement Hour', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_pickMinute', 'arrays', 'datepicker_tooltips_ARRAY_pickMinute', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Pick Minute', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_incrementMinute', 'arrays', 'datepicker_tooltips_ARRAY_incrementMinute', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Increment Minute', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_decrementMinute', 'arrays', 'datepicker_tooltips_ARRAY_decrementMinute', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Decrement Minute', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_pickSecond', 'arrays', 'datepicker_tooltips_ARRAY_pickSecond', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Pick Second', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_incrementSecond', 'arrays', 'datepicker_tooltips_ARRAY_incrementSecond', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Increment Second', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_decrementSecond', 'arrays', 'datepicker_tooltips_ARRAY_decrementSecond', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Decrement Second', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_togglePeriod', 'arrays', 'datepicker_tooltips_ARRAY_togglePeriod', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Toggle Period', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_selectTime', 'arrays', 'datepicker_tooltips_ARRAY_selectTime', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select Time', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_today', 'arrays', 'datepicker_tooltips_ARRAY_today', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Go to today', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_clear', 'arrays', 'datepicker_tooltips_ARRAY_clear', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Clear selection', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_close', 'arrays', 'datepicker_tooltips_ARRAY_close', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Close the picker', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_selectMonth', 'arrays', 'datepicker_tooltips_ARRAY_selectMonth', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select Month', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'datepicker_tooltips_ARRAY_prevMonth', 'arrays', 'datepicker_tooltips_ARRAY_prevMonth', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Previous Month', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'enum_arr_ARRAY_yes', 'arrays', 'enum_arr_ARRAY_yes', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Yes', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'enum_arr_ARRAY_no', 'arrays', 'enum_arr_ARRAY_no', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'enum_arr_ARRAY_1', 'arrays', 'enum_arr_ARRAY_1', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Yes', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'enum_arr_ARRAY_2', 'arrays', 'enum_arr_ARRAY_2', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'enum_arr_ARRAY_3', 'arrays', 'enum_arr_ARRAY_3', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Yes (Required)', 'script');





INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoNotificationsTitle', 'backend', 'Info / Email Confirmations Title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Email Confirmations', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoNotificationsDesc', 'backend', 'Info / Email Confirmations Desc', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There are 3 types of email confirmations - one after booking form is submitted , one after payment is made and one when cancelled the booking. Use the available tokens to personalize the email messages.', 'script');


INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_main_title', 'backend', 'Notifications', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Notifications', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_main_subtitle', 'backend', 'Notifications (sub-title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Automated messages are sent both to client and administrator on specific events. Select message type to edit it - enable/disable or just change message text. For SMS notifications you need to enable SMS service. See more <a href=\"https://www.phpjabbers.com/web-sms/\" target=\"_blank\">here</a>.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_recipient', 'backend', 'Notifications / Recipient', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Recipient', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_tokens_note', 'backend', 'Notifications / Tokens (note)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Personalize the message by including any of the available tokens and it will be replaced with corresponding data.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_tokens', 'backend', 'Notifications / Tokens', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Available tokens:', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'recipients_ARRAY_client', 'arrays', 'Recipients / Client', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Client', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'recipients_ARRAY_admin', 'arrays', 'Recipients / Administrator', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Administrator', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'recipient_admin_note', 'backend', 'Recipients / Administrator (note)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Go to <a href=\"index.php?controller=pjBaseUsers&action=pjActionIndex\">Users menu</a> and edit each administrator profile to select if they should receive \"Admin notifications\" or not.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'opt_o_email_body_text', 'backend', 'Options / Email body text', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '<div class=\"col-xs-12\">
<div><small>{Title} - customer title;</small></div>
<div><small>{FirstName} - customer''s first name;</small></div>
<div><small>{LastName} - customer''s last name;</small></div>
<div><small>{Phone} - customer''s phone number;</small></div>
<div><small>{Email} - customer''s e-mail address;</small></div>
<div><small>{Company} - company;</small></div>
<div><small>{Address} - address;</small></div>
<div><small>{City} - city;</small></div>
<div><small>{State} - state;</small></div>
<div><small>{Zip} - zip code;</small></div>
<div><small>{Country} - country;</small></div>
<div><small>{Bus} - bus name;</small></div>
<div><small>{Route} - route name;</small></div>
<div><small>{Seat} - seat;</small></div>
<div><small>{Time} - time;</small></div>
<div><small>{From_Location} - from location;</small></div>
<div><small>{To_Location} - to location;</small></div>
<div><small>{Departure_Time} - departure time;</small></div>
<div><small>{Arrival_Time} - arrival time;</small></div>
<div><small>{TicketType} - ticket type;</small></div>
<div><small>{UniqueID} - booking ID;</small></div>
<div><small>{Total} - total amount;</small></div>
<div><small>{Tax} - tax amount;</small></div>
<div><small>{PaymentMethod} - selected payment method;</small></div>
<div><small>{CCType} - CC type;</small></div>
<div><small>{CCNum} - CC number;</small></div>
<div><small>{CCExp} - CC expire;</small></div>
<div><small>{CCSec} - CC CVC;</small></div>
<div><small>{CancelURL} - link for booking cancellation;</small></div>
 </div>', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_client_email_confirmation', 'arrays', 'Notifications / Client email confirmation', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Send confirmation email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_client_email_payment', 'arrays', 'Notifications / Client email payment', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Send payment confirmation email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_client_email_cancel', 'arrays', 'Notifications / Client email cancel', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Send cancellation email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_admin_email_confirmation', 'arrays', 'Notifications / Admin email confirmation', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Send confirmation email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_admin_email_payment', 'arrays', 'Notifications / Admin email payment', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Send payment confirmation email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_admin_email_cancel', 'arrays', 'Notifications / Admin email cancel', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Send cancellation email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_client_email_pending_time_expired', 'arrays', 'Notifications / Seats pending time expired email', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Send seats pending time expired email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_client_email_confirmation', 'arrays', 'Notifications / Client email confirmation (title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking Confirmation email sent to Client', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_client_email_payment', 'arrays', 'Notifications / Client email payment (title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Payment Confirmation email sent to Client', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_client_email_cancel', 'arrays', 'Notifications / Client email cancel (title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking Cancellation email sent to Client', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_admin_email_confirmation', 'arrays', 'Notifications / Admin email confirmation (title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'New Booking Received email sent to Admin', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_admin_email_payment', 'arrays', 'Notifications / Admin email payment (title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Payment Confirmation email sent to Admin', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_admin_email_cancel', 'arrays', 'Notifications / Admin email cancel (title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cancellation email sent to Admin', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_client_email_pending_time_expired', 'arrays', 'Notifications / Seats pending time expired email (title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Seats pending time expired email sent to Client', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_client_email_confirmation', 'arrays', 'Notifications / Client email confirmation (sub-title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This email is sent to the client when a new reservation is made.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_client_email_payment', 'arrays', 'Notifications / Client email payment (sub-title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This email is sent to the client when a new payment is made.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_client_email_cancel', 'arrays', 'Notifications / Client email cancel (sub-title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This email is sent to client when cancel the reservation.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_admin_email_confirmation', 'arrays', 'Notifications / Admin email confirmation (sub-title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This email is sent to the administrator when a new reservation is made.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_admin_email_payment', 'arrays', 'Notifications / Admin email payment (sub-title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This email is sent to the administrator when a new payment is made.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_admin_email_cancel', 'arrays', 'Notifications / Admin email cancel (sub-title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This email is sent to the administrator when client cancel the booking.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_client_email_pending_time_expired', 'arrays', 'Notifications / Seats pending time expired email (sub-title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This email is sent to the client when seats pending time expired.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_subject', 'backend', 'Subject', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Subject', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_message', 'backend', 'Message', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Message', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_is_active', 'backend', 'Send this message', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Send this message', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_sms_na', 'backend', 'SMS not available', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'SMS notifications are currently not available for your website. See details', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_sms_na_desc', 'backend', 'Label / To use SMS notification, please add you SMS key', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'To use SMS notification, please add you SMS key', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_sms_na_here', 'backend', 'here', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'here', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_send', 'backend', 'Notifications / Send', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Send', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_do_not_send', 'backend', 'Notifications / Do not send', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Do not send', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_status', 'backend', 'Notifications / Status', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Status', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_msg_to_client', 'backend', 'Notifications / Messages sent to Clients', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Messages sent to Clients', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_msg_to_admin', 'backend', 'Notifications / Messages sent to Admin', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Messages sent to Admin', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_msg_to_default', 'backend', 'Notifications / Messages sent to Default', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Messages sent', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_client_sms_confirmation', 'arrays', 'Notifications / Booking confirmation SMS', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking confirmation SMS', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_client_sms_payment', 'arrays', 'Notifications / Payment confirmation SMS', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Payment confirmation SMS', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_admin_sms_confirmation', 'arrays', 'Notifications / Booking confirmation SMS', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking confirmation SMS', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_admin_sms_payment', 'arrays', 'Notifications / Payment confirmation SMS', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Payment confirmation SMS', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_client_sms_confirmation', 'arrays', 'Notifications / Client sms confirmation (title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking Confirmation SMS to Client', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_client_sms_confirmation', 'arrays', 'Notifications / Client sms confirmation (sub-title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This SMS is sent to client when a booking is made.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_client_sms_payment', 'arrays', 'Notifications / Client sms payment (title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Payment SMS to Client', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_client_sms_payment', 'arrays', 'Notifications / Client sms payment (sub-title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This SMS is sent to client when a payment is made for a his booking.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_admin_sms_confirmation', 'arrays', 'Notifications / Admin sms confirmation (title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking Confirmation SMS to Admin', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_admin_sms_confirmation', 'arrays', 'Notifications / Client sms confirmation (sub-title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This SMS is sent to administrator when a booking is made.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_admin_sms_payment', 'arrays', 'Notifications / Admin sms confirmation (title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Payment Confirmation SMS to Admin', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_admin_sms_payment', 'arrays', 'Notifications / Client sms confirmation (sub-title)', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This SMS is sent to administrator when a payment is made for a new booking.', 'script');


INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuSettings', 'backend', 'Label / Settings', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Settings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'settingsTabBookings', 'backend', 'Tab / Bookings', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bookings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'settingsTabPayments', 'backend', 'Tab / Payments', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Payments', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'settingsTabBookingForm', 'backend', 'Tab / Booking Form', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking Form', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'settingsTabTicket', 'backend', 'Tab / Ticket', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Ticket', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'settingsTabTerms', 'backend', 'Tab / Terms & Conditions', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Terms & Conditions', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'settingsTabContent', 'backend', 'Tab / Content', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Content', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'settingsTabNotifications', 'backend', 'Tab / Notifications', 'script', '2022-05-12 08:47:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Notifications', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDeleteContentImageTitle', 'backend', 'Label / Delete image', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete image', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDeleteContentImageConfirm', 'backend', 'Label / Delete image confirmation', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Are you sure that you want to delete this image?', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSelectImage', 'backend', 'Label / Select image', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select image', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblChangeImage', 'backend', 'Label / Change image', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Change image', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuRoutesList', 'backend', 'Menu / Routes List', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Routes List', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menuCitiesList', 'backend', 'Menu / Cities List', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Cities List', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnAddCity', 'backend', 'Button / Add city', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add city', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnAddRoute', 'backend', 'Button / Add route', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add route', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnAddLocation', 'backend', 'Button / Add location', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add location', 'script');




INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdminCities');
SET @level_1_id := (SELECT LAST_INSERT_ID());

  INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminCities_pjActionIndex');
  SET @level_2_id := (SELECT LAST_INSERT_ID());
  
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminCities_pjActionCreateForm');
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminCities_pjActionUpdateForm');
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminCities_pjActionDeleteCity');
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminCities_pjActionDeleteCityBulk');
    

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminCities', 'backend', 'pjAdminCities', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Cities Menu', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminCities_pjActionCreateForm', 'backend', 'pjAdminCities_pjActionCreateForm', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add cities', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminCities_pjActionDeleteCity', 'backend', 'pjAdminCities_pjActionDeleteCity', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete single city', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminCities_pjActionDeleteCityBulk', 'backend', 'pjAdminCities_pjActionDeleteCityBulk', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete multiple cities', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminCities_pjActionIndex', 'backend', 'pjAdminCities_pjActionIndex', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Cities List', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminCities_pjActionUpdateForm', 'backend', 'pjAdminCities_pjActionUpdateForm', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Edit cities', 'script');



INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdminRoutes');
SET @level_1_id := (SELECT LAST_INSERT_ID());

  INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminRoutes_pjActionIndex');
  SET @level_2_id := (SELECT LAST_INSERT_ID());
  
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminRoutes_pjActionCreateForm');
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminRoutes_pjActionUpdateForm');
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminRoutes_pjActionDeleteRoute');
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminRoutes_pjActionDeleteRouteBulk');
    

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminRoutes', 'backend', 'pjAdminRoutes', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Routes Menu', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminRoutes_pjActionCreateForm', 'backend', 'pjAdminRoutes_pjActionCreateForm', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add routes', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminRoutes_pjActionDeleteRoute', 'backend', 'pjAdminRoutes_pjActionDeleteRoute', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete single route', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminRoutes_pjActionDeleteRouteBulk', 'backend', 'pjAdminRoutes_pjActionDeleteRouteBulk', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete multiple routes', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminRoutes_pjActionIndex', 'backend', 'pjAdminRoutes_pjActionIndex', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Routes List', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminRoutes_pjActionUpdateForm', 'backend', 'pjAdminRoutes_pjActionUpdateForm', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Edit routes', 'script');


INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdminOptions');
SET @level_1_id := (SELECT LAST_INSERT_ID());

  INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminOptions_pjActionBooking'); 
  INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjPayments_pjActionIndex');
  INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminOptions_pjActionBookingForm');
  INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminOptions_pjActionTicket');
  INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminOptions_pjActionNotifications'); 
  INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminOptions_pjActionTerm');
  INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminOptions_pjActionContent');
  
INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdminOptions_pjActionPreview');
INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdminOptions_pjActionInstall');


INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminOptions', 'backend', 'pjAdminOptions', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Settings Menu', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminOptions_pjActionBooking', 'backend', 'pjAdminOptions_pjActionBooking', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bookings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjPayments_pjActionIndex', 'backend', 'pjPayments_pjActionIndex', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Payments', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminOptions_pjActionBookingForm', 'backend', 'pjAdminOptions_pjActionBookingForm', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking Form', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminOptions_pjActionTicket', 'backend', 'pjAdminOptions_pjActionTicket', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Ticket setting', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminOptions_pjActionTerm', 'backend', 'pjAdminOptions_pjActionTerm', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Terms', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminOptions_pjActionContent', 'backend', 'pjAdminOptions_pjActionContent', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Content', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminOptions_pjActionNotifications', 'backend', 'pjAdminOptions_pjActionNotifications', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Notifications', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminOptions_pjActionPreview', 'backend', 'pjAdminOptions_pjActionPreview', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Preview Menu', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminOptions_pjActionInstall', 'backend', 'pjAdminOptions_pjActionInstall', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Install Menu', 'script');


INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblUseSeatsMap', 'backend', 'Label / Use seats map', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Use seats map', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'fd_field_required', 'backend', 'Label / This field is required', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This field is required.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblMenu', 'backend', 'Label / Menu', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Menu', 'script');


INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdminBusTypes');
SET @level_1_id := (SELECT LAST_INSERT_ID());

  INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminBusTypes_pjActionIndex');
  SET @level_2_id := (SELECT LAST_INSERT_ID());
  
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBusTypes_pjActionCreate');
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBusTypes_pjActionUpdate');
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBusTypes_pjActionDeleteBusType');
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBusTypes_pjActionDeleteBusTypeBulk');
    

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBusTypes', 'backend', 'pjAdminBusTypes', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Bus Types Menu', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBusTypes_pjActionCreate', 'backend', 'pjAdminBusTypes_pjActionCreate', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add bus types', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBusTypes_pjActionDeleteBusType', 'backend', 'pjAdminBusTypes_pjActionDeleteBusType', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete single bus type', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBusTypes_pjActionDeleteBusTypeBulk', 'backend', 'pjAdminBusTypes_pjActionDeleteBusTypeBulk', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete multiple bus types', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBusTypes_pjActionIndex', 'backend', 'pjAdminBusTypes_pjActionIndex', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Bus Types List', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBusTypes_pjActionUpdate', 'backend', 'pjAdminBusTypes_pjActionUpdate', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Edit bus types', 'script');


INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdminBuses');
SET @level_1_id := (SELECT LAST_INSERT_ID());

  INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminBuses_pjActionIndex');
  SET @level_2_id := (SELECT LAST_INSERT_ID());
  
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBuses_pjActionCreate');
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBuses_pjActionTime');    
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBuses_pjActionNotOperating');
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBuses_pjActionTicket');
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBuses_pjActionPrice');    
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBuses_pjActionDeleteBus');
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBuses_pjActionDeleteBusBulk');
    

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBuses', 'backend', 'pjAdminBuses', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Buses Menu', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBuses_pjActionCreate', 'backend', 'pjAdminBuses_pjActionCreate', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add bus', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBuses_pjActionDeleteBus', 'backend', 'pjAdminBuses_pjActionDeleteBus', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete single bus', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBuses_pjActionDeleteBusBulk', 'backend', 'pjAdminBuses_pjActionDeleteBusBulk', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete multiple buses', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBuses_pjActionIndex', 'backend', 'pjAdminBuses_pjActionIndex', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Buses List', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBuses_pjActionTime', 'backend', 'pjAdminBuses_pjActionTime', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Edit buses', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBuses_pjActionNotOperating', 'backend', 'pjAdminBuses_pjActionNotOperating', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Out of service', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBuses_pjActionTicket', 'backend', 'pjAdminBuses_pjActionTicket', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Ticket Types', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBuses_pjActionPrice', 'backend', 'pjAdminBuses_pjActionPrice', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Ticket Prices', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDateFrom', 'backend', 'Label / Date from', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Date from', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDateTo', 'backend', 'Label / Date to', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Date to', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'prices_invalid_price', 'backend', 'Label / Please enter a valid price.', 'script', '2018-11-19 07:16:00');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Please enter a valid price.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'prices_invalid_number', 'backend', 'Label / Please enter a valid number.', 'script', '2018-11-19 07:16:00');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Please enter a valid number.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnClose', 'backend', 'Button / Close', 'script', '2018-11-19 07:16:00');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Close', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnCopy', 'backend', 'Button / Copy', 'script', '2018-11-19 07:16:00');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Copy', 'script');

INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdminBookings');
SET @level_1_id := (SELECT LAST_INSERT_ID());

  INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminBookings_pjActionIndex');
  SET @level_2_id := (SELECT LAST_INSERT_ID());
  
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBookings_pjActionCreate');
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBookings_pjActionUpdate');   
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBookings_pjActionDeleteBooking');
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBookings_pjActionDeleteBookingBulk');
    INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBookings_pjActionExportBooking');
    

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBookings', 'backend', 'pjAdminBookings', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Bookings Menu', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBookings_pjActionCreate', 'backend', 'pjAdminBookings_pjActionCreate', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add booking', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBookings_pjActionDeleteBooking', 'backend', 'pjAdminBookings_pjActionDeleteBooking', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete single booking', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBookings_pjActionDeleteBookingBulk', 'backend', 'pjAdminBookings_pjActionDeleteBookingBulk', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete multiple bookings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBookings_pjActionIndex', 'backend', 'pjAdminBookings_pjActionIndex', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Bookings List', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBookings_pjActionUpdate', 'backend', 'pjAdminBookings_pjActionUpdate', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Edit bookings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminBookings_pjActionExportBooking', 'backend', 'pjAdminBookings_pjActionExportBooking', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Export bookings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnAddBooking', 'backend', 'Button / Add booking', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add booking', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnAdvancedSearch', 'backend', 'Button / Advanced search', 'script', '2020-10-30 08:30:59');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Advanced search', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblExportSelected', 'backend', 'Label / Export selected', 'script', '2020-10-30 08:30:59');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Export selected', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'script_online_payment_gateway', 'backend', 'Label / Online payments', 'script', '2020-11-09 14:41:28');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Online payments', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'script_offline_payment', 'backend', 'Label / Offline payments', 'script', '2020-11-09 14:41:46');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Offline payments', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDuplicatedUniqueID', 'backend', 'Label / There is another booking with such ID.', 'script', '2021-02-03 08:52:57');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'There is another booking with such ID.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnReselect', 'backend', 'Button / Re-select', 'script', '2021-02-03 08:52:57');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Re-select', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'btnOK', 'backend', 'Button / OK', 'script', '2021-02-03 08:52:57');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'OK', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'fd_select_seats_required', 'backend', 'Label / Please select seats', 'script', '2021-02-03 08:52:57');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Please select seats', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingTotalPrice', 'backend', 'Label / Total Price', 'script', '2020-11-09 14:41:28');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Total Price', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingPaymentMade', 'backend', 'Label / Payment Made', 'script', '2020-11-09 14:41:46');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Payment Made', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingPaymentDue', 'backend', 'Label / Payment Due', 'script', '2020-11-09 14:41:46');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Payment Due', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingConfirmationResend', 'backend', 'Booking / Send confirmation email', 'script', '2021-02-03 08:52:57');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send confirmation email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingPaymentResend', 'backend', 'Booking / Send payment confirmation email', 'script', '2021-02-03 08:52:57');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send payment confirmation email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblBookingCancelledResend', 'backend', 'Booking / Send cancellation email', 'script', '2021-02-03 08:52:57');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send cancellation email', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'email_confirmation', 'backend', 'Label / Email confirmation', 'script', '2020-11-13 01:55:07');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email confirmation', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'email_payment', 'backend', 'Label / Email payment confirmation', 'script', '2020-11-13 01:55:07');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email payment confirmation', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'email_cancellation', 'backend', 'Label / Email cancellation', 'script', '2020-11-13 01:55:19');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email cancellation', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblEmailNotificationNotSet', 'backend', 'Label / Email notification is not set.', 'script', '2020-11-13 03:23:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email notification is not set.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblEmailPaymentNotificationNotSet', 'backend', 'Label / Email payment notification is not set.', 'script', '2020-11-13 03:23:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email payment notification is not set.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblEmailCancellationNotSet', 'backend', 'Label / Email cancellation is not set.', 'script', '2020-11-13 03:48:40');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email cancellation is not set.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblSubject', 'backend', 'Label / Subject', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Subject', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblMessage', 'backend', 'Label / Message', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Message', 'script');


INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdminSchedule');
SET @level_1_id := (SELECT LAST_INSERT_ID());

  INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminSchedule_pjActionIndex');
  	SET @level_2_id := (SELECT LAST_INSERT_ID());
  		INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminSchedule_pjActionBookings');
  		INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminSchedule_pjActionSeats');
  INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminSchedule_pjActionTimetable');
    

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminSchedule', 'backend', 'pjAdminSchedule', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Schedule Menu', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminSchedule_pjActionIndex', 'backend', 'pjAdminSchedule_pjActionIndex', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Buses schedule', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminSchedule_pjActionBookings', 'backend', 'pjAdminSchedule_pjActionBookings', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Passengers List', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminSchedule_pjActionSeats', 'backend', 'pjAdminSchedule_pjActionSeats', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Seats list', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminSchedule_pjActionTimetable', 'backend', 'pjAdminSchedule_pjActionTimetable', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Route timetable', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblTomorrow', 'backend', 'Label / Tomorrow', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Tomorrow', 'script');


INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'dash_status', 'backend', 'Label / Status', 'script', '2020-11-13 01:55:07');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Status', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'dash_today', 'backend', 'Label / today', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'today', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'dash_new_bookings', 'backend', 'Label / New bookings', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'New bookings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'dash_this_month', 'backend', 'Label / this month', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'this month', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'dash_total_bookings', 'backend', 'Label / Total bookings', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Total bookings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'dash_total', 'backend', 'Label / total', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'total', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'dash_view_all_bookings', 'backend', 'Label / View All Bookings', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'View All Bookings', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'dash_booking_made', 'backend', 'Label / booking made', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'booking made', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'dash_bookings_made', 'backend', 'Label / bookings made', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'bookings made', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashboardBuses', 'backend', 'Label / Buses', 'script', '2020-11-13 01:55:07');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Buses', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblDashboardRoutes', 'backend', 'Label / Routes', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Routes', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'script_change_labels', 'backend', 'Label / Change Labels', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Change Labels', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'script_preview_your_website', 'backend', 'Label / Open in new window', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Open in new window', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'script_install_your_website', 'backend', 'Label / Install your website', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Install your website', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblInstallTheme', 'backend', 'lblInstallTheme', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Choose theme', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblInstallCode', 'backend', 'Label / Install Code', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Install Code', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'lblInstallCodeDesc', 'backend', 'Label / Copy the code below and put it on your web page. It will show the front end booking engine. ', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Copy the code below and put it on your web page. It will show the front end booking engine. ', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO40', 'arrays', 'error_titles_ARRAY_AO40', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Preview front end', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO40', 'arrays', 'error_bodies_ARRAY_AO40', 'script', '2015-03-20 11:37:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'To put the booking engine on your website go to <a href=\"index.php?controller=pjAdminOptions&action=pjActionInstall\">Install</a> page.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'system_212', 'frontend', 'Label / Captcha is expired.', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The captcha is not correct. Please try again.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_ip_address_blocked', 'frontend', 'front_ip_address_blocked', 'script', '2020-10-21 08:17:03');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Your IP address has been blocked.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_booking_statuses_ARRAY_1', 'arrays', 'front_booking_statuses_ARRAY_1', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Thank you! Your booking has been made. Please click on the \"Start over\" button to make new booking.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_booking_statuses_ARRAY_11', 'arrays', 'front_booking_statuses_ARRAY_11', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Please wait while redirect to secure payment processor webpage complete...', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_button_start_over', 'backend', 'Button / Start over', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Start over', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_profile_updated_msg', 'frontend', 'Label / Profile updated', 'script', '2016-07-20 02:21:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Your profile has been updated!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_btn_update', 'frontend', 'Button / Update', 'script', '2016-07-20 02:21:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Update', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_menu_scan_ticket', 'frontend', 'Menu / Scan ticket', 'script', '2016-07-20 02:21:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Scan ticket', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_menu_login', 'frontend', 'Menu / Login', 'script', '2016-07-20 02:21:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Login', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_scan_ticket_manually', 'frontend', 'Label / Scan ticket maually', 'script', '2016-07-20 02:21:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Scan ticket maually', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_authenticate_code', 'frontend', 'Label / Authenticate code', 'script', '2016-07-20 02:21:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Authenticate code', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'validate_ARRAY_authenticate_code', 'arrays', 'validate_ARRAY_authenticate_code', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Authenticate code is required.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_btn_validate', 'frontend', 'Button / Validate', 'script', '2016-07-20 02:21:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Validate', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_authenticate_code_invalid', 'frontend', 'Label / Invalid code. Entry not permitted', 'script', '2016-07-20 02:21:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Invalid code. Entry not permitted', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_ticket_details', 'frontend', 'Label / Ticket details', 'script', '2016-07-20 02:21:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Ticket details', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_btn_mark_ticket_used', 'frontend', 'Button / Mark this ticket as used', 'script', '2016-07-20 02:21:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Mark this ticket as used', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_btn_no', 'frontend', 'Button / No', 'script', '2016-07-20 02:21:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'No', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_btn_yes', 'frontend', 'Button / Yes', 'script', '2016-07-20 02:21:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Yes', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_btn_ok', 'frontend', 'Button / OK', 'script', '2016-07-20 02:21:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'OK', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_menu_profile', 'frontend', 'Menu / Profile', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Profile', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_menu_logout', 'frontend', 'Menu / Logout', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Logout', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_update_profile', 'frontend', 'Label / Update profile', 'script', '2016-07-20 02:21:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Update profile', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_password', 'frontend', 'Label / Password', 'script', '2016-07-20 02:21:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Password', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_label_name', 'frontend', 'Label / Name', 'script', '2016-07-20 02:21:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Name', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'validate_ARRAY_cus_email', 'arrays', 'validate_ARRAY_cus_email', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email address is required.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'validate_ARRAY_cus_email_taken', 'arrays', 'validate_ARRAY_cus_email_taken', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'User with this email address exists.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'validate_ARRAY_cus_password', 'arrays', 'validate_ARRAY_cus_password', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Password is required.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'validate_ARRAY_cus_name', 'arrays', 'validate_ARRAY_cus_name', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Name is required.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'validate_ARRAY_cus_phone', 'arrays', 'validate_ARRAY_cus_phone', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Phone is required.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'validate_ARRAY_cus_email_invalid', 'arrays', 'validate_ARRAY_cus_email_invalid', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email is invalid.', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_menu_manual_scan', 'frontend', 'Menu / Manual Scan', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Manual Scan', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_ticket_seat', 'frontend', 'Label / Seat', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Seat', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_ticket_name', 'frontend', 'Label / Name', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Name', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'front_ticket_phone', 'frontend', 'Label / Phone', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Phone', 'script');


INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'validate_ticket_ARRAY_1', 'arrays', 'validate_ticket_ARRAY_1', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Missing parameters!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'validate_ticket_ARRAY_2', 'arrays', 'validate_ticket_ARRAY_2', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Ticket not found!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'validate_ticket_ARRAY_3', 'arrays', 'validate_ticket_ARRAY_3', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The ticket marked as used!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'validate_ticket_ARRAY_4', 'arrays', 'validate_ticket_ARRAY_4', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This ticket already used!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'validate_ticket_ARRAY_5', 'arrays', 'validate_ticket_ARRAY_5', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'You are not logged in!', 'script');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'validate_ticket_ARRAY_6', 'arrays', 'validate_ticket_ARRAY_6', 'script', '2017-08-18 04:02:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Your reservation has not been confirmed!', 'script');



INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'plugin_base_role_arr_ARRAY_3', 'arrays', 'Plugin Base / Driver User', 'plugin', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Driver', 'plugin');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'menScanTicket', 'backend', 'Menu / Scan Ticket', 'plugin', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Scan Ticket', 'plugin');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoScanTicketTitle', 'backend', 'Infobox / Scan Ticket', 'plugin', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Scan Ticket', 'plugin');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'infoScanTicketDesc', 'backend', 'Infobox / ScanTicket description', 'plugin', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Use the form below to scan the codes', 'plugin');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'button_start_scanning', 'backend', 'Button / Start Scanning', 'plugin', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Start Scanning', 'plugin');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'button_stop_scanning', 'backend', 'Button / Stop Scanning', 'plugin', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Stop Scanning', 'plugin');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'button_scan_image_file', 'backend', 'Button / Scan an Image File', 'plugin', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Scan an Image File', 'plugin');


INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdmin_pjActionIndex');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdmin_pjActionIndex', 'backend', 'Label / Dashboard Menu', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Dashboard Menu', 'script');


INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdminScanTicket_pjActionIndex');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdminScanTicket_pjActionIndex', 'backend', 'Label / Scan ticket menu', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Scan ticket', 'script');


INSERT INTO `bus_schedule_plugin_auth_permissions` (`id`, `parent_id`, `key`, `is_shown`) VALUES (NULL, NULL, 'pjAdmin_pjActionDriver', 'F');

INSERT INTO `bus_schedule_plugin_base_fields` VALUES (NULL, 'pjAdmin_pjActionDriver', 'backend', 'Label / Driver access', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `bus_schedule_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Driver access to scan ticket', 'script');
