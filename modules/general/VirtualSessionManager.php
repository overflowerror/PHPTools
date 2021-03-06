<?php
	
	define("MAX_VSESSIONS", 15);
	
	class vSession {
		private $name;
		public function __construct($name) {
			$this->name = $name;
		}
		public function init() {
			global $_SESSION;
			if (!isset($_SESSION['vSession']))
				$_SESSION['vSession'] = array();
			if (count($_SESSION['vSession']) > MAX_VSESSIONS)
				$this->deleteOldest();
			if (!isset($_SESSION['vSession'][$this->name])) {
				$_SESSION['vSession'][$this->name] = array();
				$_SESSION['vSession'][$this->name]['created'] = time();
				$_SESSION['vSession'][$this->name]['modified'] = time();
			} else {
				throw new Exception("session exists");
			}
		}
		public function exists() {
			global $_SESSION;
			return isset($_SESSION['vSession']) && isset($_SESSION['vSession'][$this->name]);
		}
		public function destroy() {
			global $_SESSION;
			unset($_SESSION['vSession'][$this->name]);
		}
		public function destroyAll() {
			global $_SESSION;
			unset($_SESSION['vSession']);
		}
		public function setAttribute($name, $value) {
			global $_SESSION;
			if (count($_SESSION['vSession']) > MAX_VSESSIONS)
				$this->deleteOldest();
			$_SESSION['vSession'][$this->name]['modified'] = time();
			$_SESSION['vSession'][$this->name][$name] = $value;
		}
		public function getAttribute($name) {
			global $_SESSION;
			return $_SESSION['vSession'][$this->name][$name];
		}
		public function attributeIsset($name) {
			global $_SESSION;
			return isset($_SESSION['vSession'][$this->name][$name]);
		}
		private function deleteOldest() {
			global $_SESSION;
			$oldestKey = false;
			$last = time() + 1;
			foreach ($_SESSION['vSession'] as $key => $value) {
				if ($value['modified'] < $last) {
					$last = $value['modified'];
					$oldestKey = $key;
				}
			}
			if (!$oldestKey)
				return;
			unset($_SESSION['vSession'][$oldestKey]);
		}
	}
?>
