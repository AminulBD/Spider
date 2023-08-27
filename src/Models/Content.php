<?php
namespace AminulBD\Spider\Models;

class Content
{
	protected array $fields = [
		'siteName', 'title', 'description', 'image', 'url'
	];
	public ?string $siteName;
	public ?string $title;
	public ?string $description;
	public ?string $image;
	public ?string $url;

	public function __construct(array $attrs = [])
	{
		foreach ($this->fields as $field) {
			$this->$field = $attrs[$field] ?? null;
		}
	}

	public function toArray(): array
	{
		return [
			'title' => $this->title,
			'description' => $this->description,
			'image' => $this->image,
			'url' => $this->url,
		];
	}
}
