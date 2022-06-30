<?php namespace Motokraft\Uri;

/**
 * @copyright   2022 motokraft. MIT License
 * @link https://github.com/motokraft/uri
 */

use \Motokraft\Object\BaseObject;
use \Motokraft\Object\Traits\ObjectTrait;

class Uri
{
	use ObjectTrait;

	const SCHEME = 'scheme';
	const USER = 'user';
	const PASS = 'pass';
	const HOST = 'host';
	const PORT = 'port';
	const PATH = 'path';
	const QUERY = 'query';
	const FRAGMENT = 'fragment';
	const ALL = 'all';

	private static $instances = [];

	private $scheme;
	private $user;
	private $pass;
	private $host;
	private $port;
	private $path;
	private $fragment;

	function __construct(string $uri = null)
	{
		if(!empty($uri))
		{
			$this->parseUri($uri);
		}
	}

	static function getInstance(string $uri = 'SERVER')
	{
		if (!isset(self::$instances[$uri]))
		{
			if($uri === 'SERVER')
			{
				$_server = new BaseObject($_SERVER);

				$shema = $_server->get('REQUEST_SCHEME');
				$uri = strtolower($shema) . '://';

				$uri .= $_server->get('HTTP_HOST');
				$uri .= $_server->get('REQUEST_URI');
			}

			self::$instances[$uri] = new static($uri);
		}

		return self::$instances[$uri];
	}

	static function base() : null|string
	{
		$paths = [self::SCHEME, self::HOST];

		$uri = self::getInstance('SERVER');
		return $uri->toString($paths);
	}

	function setScheme(string $value) : static
	{
		$this->set('scheme', $value);
		return $this;
	}

	function setUser(string $value) : static
	{
		$this->set('user', $value);
		return $this;
	}

	function setPass(string $value) : static
	{
		$this->set('pass', $value);
		return $this;
	}

	function setHost(string $value) : static
	{
		$this->set('host', $value);
		return $this;
	}

	function setPort(int $value) : static
	{
		$this->set('port', $value);
		return $this;
	}

	function setPath(string $value) : static
	{
		$this->set('path', $value);
		return $this;
	}

	function setFragment(string $value) : static
	{
		$this->set('fragment', $value);
		return $this;
	}

	function getScheme($default = null) : null|string
	{
		return $this->get('scheme', $default);
	}

	function getUser($default = null) : null|string
	{
		return $this->get('user', $default);
	}

	function getPass($default = null) : null|string
	{
		return $this->get('pass', $default);
	}

	function getHost($default = null) : null|string
	{
		return $this->get('host', $default);
	}

	function getPort($default = 0) : int
	{
		return (int) $this->get('port', $default);
	}

	function getPath($default = null) : null|string
	{
		return $this->get('path', $default);
	}

	function getFragment($default = null) : null|string
	{
		return $this->get('fragment', $default);
	}

	function parseUri(string $uri) : bool
	{
		if(($data = parse_url($uri)) === false)
		{
			throw new \Exception(sprintf(
				'Unable to parse the address "%s"', $uri
			), 500);
		}

		$result = new BaseObject($data);

		if($scheme = $result->get('scheme'))
		{
			$this->setScheme($scheme);
		}

		if($host = $result->get('host'))
		{
			$this->setHost($host);
		}

		if($port = $result->get('port'))
		{
			$this->setPort((int) $port);
		}

		if($user = $result->get('user'))
		{
			$this->setUser($user);
		}

		if($pass = $result->get('pass'))
		{
			$this->setPass($pass);
		}

		if($path = $result->get('path'))
		{
			$this->setPath($path);
		}

		if($query = $result->get('query'))
		{
			parse_str($query, $data);
			$this->loadArray($data);
		}

		if($fragment = $result->get('fragment'))
		{
			$this->setFragment($fragment);
		}

		return true;
	}

	function toString($paths = [self::ALL]) : null|string
	{
		$result = null;

		if($this->_hasConstant(self::SCHEME, $paths))
		{
			$result .= $this->_toStringScheme();
		}

		if($this->_hasConstant(self::USER, $paths))
		{
			$result .= $this->_toStringUser();
		}

		if($this->_hasConstant(self::PASS, $paths))
		{
			$result .= $this->_toStringPass();
		}

		if($this->_hasConstant(self::HOST, $paths))
		{
			$result .= $this->_toStringHost();
		}

		if($this->_hasConstant(self::PORT, $paths))
		{
			$result .= $this->_toStringPort();
		}

		if($this->_hasConstant(self::PATH, $paths))
		{
			$result .= $this->_toStringPath();
		}

		if($this->_hasConstant(self::QUERY, $paths))
		{
			$result .= $this->_toStringQuery();
		}

		if($this->_hasConstant(self::FRAGMENT, $paths))
		{
			$result .= $this->_toStringFragment();
		}

		return $result;
	}

	function __toString() : string
	{
		return (string) $this->toString();
	}

	private function _hasConstant(string $name, array $paths) : bool
	{
		if(in_array(self::ALL, $paths, true))
		{
			return true;
		}

		return in_array($name, $paths, true);
	}

	private function _toStringScheme() : null|string
	{
		if($value = $this->getScheme())
		{
			return $value . '://';
		}

		return null;
	}

	private function _toStringUser() : null|string
	{
		if($value = $this->getUser())
		{
			return $value . ':';
		}

		return null;
	}

	private function _toStringPass() : null|string
	{
		if($value = $this->getPass())
		{
			return $value . '@';
		}

		return null;
	}

	private function _toStringHost() : null|string
	{
		return $this->getHost();
	}

	private function _toStringPort() : null|string
	{
		if($value = $this->getPort())
		{
			return ':' . $value;
		}

		return null;
	}

	private function _toStringPath() : null|string
	{
		return $this->getPath();
	}

	private function _toStringQuery() : null|string
	{
		if($data = (array) $this->getArray())
		{
			return '?' . http_build_query($data);
		}

		return null;
	}

	private function _toStringFragment() : null|string
	{
		if($value = $this->getFragment())
		{
			return '#' . $value;
		}

		return null;
	}
}