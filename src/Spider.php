<?php
namespace AminulBD\Spider;

use AminulBD\Spider\Contracts\Engine;
use AminulBD\Spider\Engines\Bing;
use AminulBD\Spider\Engines\Google;
use AminulBD\Spider\Engines\WordPress;

class Spider
{
	private array $engines = [
		'bing' => Bing::class,
		'google' => Google::class,
		'wordpress' => WordPress::class,
	];
	private Engine $engine;

	public function __construct(string $engine, array $config = [])
	{
		if (isset($this->engines[$engine]) && is_subclass_of($this->engines[$engine], Engine::class)) {
		$this->engine = new $this->engines[$engine]($config);
		} else if (is_subclass_of($engine, Engine::class)) {
		$this->engine = $engine($config);
		} else {
			throw new \Exception("The engine `$engine` is invalid or unsupported.");
		}
	}

	public function find(array $args): Engine
	{
		return $this->engine->find($args);
	}
}
