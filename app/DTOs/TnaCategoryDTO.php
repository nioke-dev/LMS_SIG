<?php

namespace App\DTOs;

use App\Models\TnaCategory;

class TnaCategoryDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $desc
    ) {}

    /**
     * Factory method to create DTO from TnaCategory model
     */
    public static function fromModel(TnaCategory $category): self
    {
        return new self(
            id: $category->id,
            name: $category->name,
            desc: $category->description ?? ''
        );
    }

    /**
     * Convert to array for JSON
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'desc' => $this->desc,
        ];
    }
}
