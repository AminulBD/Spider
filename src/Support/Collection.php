<?php
namespace AminulBD\Spider\Support;

use AminulBD\Spider\Models\Content;

class Collection
{
	private array $items = [];

	public function __construct(array $items = [])
	{
		$this->collect($items);
	}

	public function collect(array $items): self
	{
		foreach ($items as $item) {
			$this->add(new Content($item));
		}

		return $this;
	}

	public function add(Content $content): self
	{
		$this->items[] = $content;

		return $this;
	}

	public function all(): array
	{
		return $this->items;
	}
}
