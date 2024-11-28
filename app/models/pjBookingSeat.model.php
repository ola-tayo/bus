<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjBookingSeatModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'bookings_seats';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'booking_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'seat_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'ticket_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'start_location_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'end_location_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'is_return', 'type' => 'enum', 'default' => 'F'),
	    array('name' => 'qr_code', 'type' => 'varchar', 'default' => ':NULL'),
	    array('name' => 'is_used', 'type' => 'tinyint', 'default' => '0')
	);
	
	public static function factory($attr=array())
	{
		return new pjBookingSeatModel($attr);
	}
}
?>