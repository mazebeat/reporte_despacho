<?php namespace App\Util;

class Soaper extends SoapService
{

	/**
	 * Services name
	 *
	 * @var string
	 * example: $name = 'weather';
	 */
	protected $name;

	/**
	 * Webservice URL
	 *
	 * @var string
	 * example: $wsdl = 'http://www.webservicex.net/globalweather.asmx?WSDL';
	 */
	protected $wsdl;

	/**
	 * @var boolean
	 */
	protected $trace = true;

	/**
	 * Function name
	 *
	 * @var [type]
	 *      example: $function = 'GetWeather';
	 */
	private $function_name;

	/**
	 * [$function_result description]
	 *
	 * @var [type]
	 */
	private $function_result;

	/**
	 * Result of webservice function
	 *
	 * @var [type]
	 */
	private $result;

	/**
	 * Params for wsdl call
	 *
	 * @var
	 */
	private $params;


	/**
	 * Static instance of this class
	 *
	 * @var [type]
	 */
	private $instance;

	/**
	 * [function_name description]
	 *
	 * @param  [type] $function_name [description]
	 *
	 * @return [type]                [description]
	 */
	public function function_name($function_name)
	{
		$this->function_name = (string)$function_name;

		return $this;
	}

	/**
	 * [getFunction_name description]
	 *
	 * @return [description]
	 */
	public function getFunction_name()
	{
		return $this->function_name;
	}

	/**
	 * [function_result description]
	 *
	 * @param  [type] $function_result [description]
	 *
	 * @return [type]                  [description]
	 */
	public function function_result($function_result)
	{
		$this->function_result = (string)$function_result;

		return $this;
	}

	/**
	 * [getFunction_result description]
	 *
	 * @return [type] [description]
	 */
	public function getFunction_result()
	{
		return $this->function_result;
	}

	/**
	 * [result description]
	 *
	 * @param  [type] $result [description]
	 *
	 * @return [type]         [description]
	 */
	public function result($result)
	{
		$this->result = $result;

		return $this;
	}

	/**
	 * [getRestult description]
	 *
	 * @return [type] [description]
	 */
	public function getResult()
	{
		return $this->result;
	}

	/**
	 * @return mixed
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * @param mixed $params
	 */
	public function params(array $params)
	{
		$this->params = array($params);
	}

	/**
	 * [instance description]
	 *
	 * @param  [type] $instance [description]
	 *
	 * @return [type]           [description]
	 */
	public function instance($instance)
	{
		$this->instance = $instance;

		return $this;
	}

	/**
	 * [getInstance description]
	 *
	 * @return [type] [description]
	 */
	public function getInstance()
	{
		return $this->instance;
	}

	/**
	 * [__construct description]
	 */
	public function __construct($name, $wsdl)
	{
		parent::__construct($this->wsdl($wsdl));
		$this->name($name);
	}

	/**
	 * Get all the available functions
	 *
	 * @return mixed
	 */
	public function functions()
	{
		return $this->getFunctions();
	}

	/**
	 * [result description]
	 *
	 * @return [type] [description]
	 */
	public function run($function_name, $params = null)
	{
		$this->function_name($function_name);
		$this->function_result($this->getFunction_name() . 'Result');
		if (isset($params)) {
			$this->result($this->call($this->getFunction_name(), array($params)));
		}
		else {
			$this->result($this->call($this->getFunction_name(), $this->getParams()));
		}

		return $this;
	}

	/**
	 * [get description]
	 *
	 * @return [type] [description]
	 */
	public function get()
	{
		$this->result($this->getResult()->{$this->getFunction_result()});

		return $this;
	}

	/**
	 * [toArray description]
	 *
	 * @return [type] [description]
	 */
	public function toArray()
	{
		$xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $this->getResult());
		$xml = new SimpleXMLElement(utf8_encode($xml));
		$this->result(json_decode(json_encode((array)$xml), true));
		unset($this->instance);

		return $this->getResult();
	}

	/**
	 * [toJson description]
	 *
	 * @return [type] [description]
	 */
	public function toJson()
	{
		return json_encode($this->toArray());
	}
}
