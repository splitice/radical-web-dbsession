<?php
namespace Radical\Web\Session\Authentication\Source;

use Radical\Database\DynamicTypes\Password;

class MultipleDatabase extends NullSource {
	const FIELD_USERNAME = '*username';
	const FIELD_PASSWORD = '*password';
	private $tables = array();

	function __construct(){
		$this->tables = func_get_args();
		parent::__construct();
	}
	protected function getFields($username){
		return array(static::FIELD_USERNAME=>$username);
	}
	protected function getUserObject($table, $username){
		$class = $table->getClass();
		$data = $this->getFields($username);

		return $class::fromFields($data);
	}

	function login($username, $password, ...$args){
		foreach($this->tables as $table){
			$res = $this->getUserObject($table, $username);
			if($res){
				$pass = $res->getSQLField(static::FIELD_PASSWORD);
				if($pass){
					if($pass instanceof Password){
						if($pass->Compare($password)){
							if($res){
								\Radical\Web\Session::$data['user'] = $res;
								return true;
							}
						}
					}else{
						throw new \Exception(static::FIELD_PASSWORD.' must be a DynamicType of Password');
					}
				}
			}
		}
		return false;
	}
}