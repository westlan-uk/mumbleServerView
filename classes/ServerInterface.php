<?php

require_once 'settings.php';

class ServerInterface
{
	private $conn;
	private $meta;
	private $version;
	private $contextVars;
	private static $instance;

	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct()
	{
		global $dbInterface_address;
		global $dbInterface_icesecrets;

		// Check that the PHP Ice extension is loaded.
		if (!extension_loaded('ice')) {
			throw new Exception("No ICE extension loaded.");
		} else {
			$this->contextVars = $dbInterface_icesecrets;

			if (!function_exists('Ice_intVersion') || Ice_intVersion() < 30400) {
				// ice 3.3
				global $ICE;
				Ice_loadProfile();
				$this->conn = $ICE->stringToProxy($dbInterface_address);
				$this->meta = $this->conn->ice_checkedCast("::Murmur::Meta");
				// use IceSecret if set
				if (!empty($this->contextVars)) {
					$this->meta = $this->meta->ice_context($this->contextVars);
				}
				$this->meta = $this->meta->ice_timeout(10000);
			} else {
				// ice 3.4
				$initData = new Ice_InitializationData;
				$initData->properties = Ice_createProperties();
				$initData->properties->setProperty('Ice.ImplicitContext', 'Shared');
				$ICE = Ice_initialize($initData);
				/*
				 * getImplicitContext() is not implemented for icePHP yet…
				 * $ICE->getImplicitContext();
				 * foreach ($this->contextVars as $key=>$value) {
				 * 	 $ICE->getImplicitContext()->put($key, $value);
				 * }
				 */
				try {
					$this->meta = Murmur_MetaPrxHelper::checkedCast($ICE->stringToProxy($dbInterface_address));
				} catch (Ice_ConnectionRefusedException $exc) {
					throw new Exception("ICE connection refused.");
				}
			}

			$this->connect();
		}
	}

	private function connect()
	{
		try {
			$this->version = $this->getVersion();
		} catch (Ice_UnknownUserException $exc) {
			switch ($exc->unknown) {
				case 'Murmur::InvalidSecretException':
					throw new Exception('The Ice end requires a password, but you did not specify one or not the correct one.');
				default:
					throw new Exception('Unknown exception was thrown. Please report to the developer. Class: ' . get_class($exc) . isset($exc->unknown)?' ->unknown: '.$exc->unknown:'' . ' Stacktrage: <pre>' . $exc->getTraceAsString() . '</pre>');
			}
		} catch (Ice_LocalException $exc) {
			throw new Exception('Unknown exception was thrown. Please report to the developer. Class: ' . get_class($exc) . ' Stacktrage: <pre>' . $exc->getTraceAsString() . '</pre>');
		}
	}

	public function getVersion()
	{
		if ($this->version == null) {
			$this->meta->getVersion($major, $minor, $patch, $text);
			$this->version = $major . '.' . $minor . '.' . $patch . ' ' . $text;
		}

		return $this->version;
	}

	public function getServer($srvid)
	{
		$server = $this->meta->getServer(intval($srvid));

		if ($server != null && !empty($this->contextVars)) {
			$server = $server->ice_context($this->contextVars);
		}

		return $server;
	}
}
