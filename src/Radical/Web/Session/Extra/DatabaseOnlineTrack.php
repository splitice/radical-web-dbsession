<?php
namespace Radical\Web\Session\Handler;
use Radical\Web\Session;
use Radical\Web\Session\ModuleBase;

class DatabaseOnlineTrack extends ModuleBase {
	function __construct(){
		$IP = Session::IP();
		
		\Radical\DB::Q('INSERT INTO session (session_ip,session_time) VALUES('.\Radical\DB::E($IP).','.time().') ON DUPLICATE KEY UPDATE session_time='.time());
		$this->id = \Radical\DB::insertId();
		
		parent::__construct();
	}
}