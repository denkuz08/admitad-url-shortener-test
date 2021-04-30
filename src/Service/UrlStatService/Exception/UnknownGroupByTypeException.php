<?php

namespace App\Service\UrlStatService\Exception;

class UnknownGroupByTypeException extends \Exception
{
    protected ?string $type = null;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
