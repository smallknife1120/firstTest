<?php
namespace system {
	include __DIR__.DIRECTORY_SEPARATOR.'config.php';
	use Exception;

	final class Application {
		private $site_name = null;
		private static $gateways = array();

		function __construct($site_name) {
			define('SITE_NAME', $site_name);
			spl_autoload_register('system\\Application::_autoload');
		}

		function run() {
			print_r(self::$gateways);
			echo __DIR__.DIRECTORY_SEPARATOR.'config.php';
		}

		function addHook($hook) {
			
		}

		/**
		@param string $namespace
		@param bool $path 
		@param int $path
		@param double $path 
		
		*/
		function addGateway($namespace, $path) {
			$path_last_separator = substr($path, strlen($path) - 1);
			$namespace_last_separator = substr($namespace, strlen($namespace) - 1);
			$namespace = strtolower($namespace);

			if ($path_last_separator != '\\' && $path_last_separator != '/') {
				$path .= DIRECTORY_SEPARATOR;
			}
			if ($namespace_last_separator != '\\') {
				$namespace .= '\\';
			}
			if (file_exists($path)) {
				self::$gateways[$namespace] = $path;
				krsort(self::$gateways);
			}
			else {
				throw(new Exception('folder is not exists : '.$path));
			}
		}

		private static function _autoload($class) {
			$class = strtolower($class);

			if (0 === strpos($class, 'system\\')) {
				$file_path = __DIR__.strtr(substr($class, strlen('system\\')), array('\\'=>DIRECTORY_SEPARATOR)).EXT;
			}
			else {
				foreach (self::$gateways as $namespace=>$path) {
					if (0 === strpos($class, $namespace)) {
						$file_path = $path.strtr(substr($class, strlen($namespace)), array('\\'=>DIRECTORY_SEPARATOR)).EXT;
						break;
					}
				}
			}
			echo $file_path;
			exit();
		}
		
	}
}