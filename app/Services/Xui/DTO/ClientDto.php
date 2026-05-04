<?php

namespace App\Services\Xui\DTO;

use JSON\json;

final class ClientDto
{
    public function __construct(

        public string $id,
        public string $email,
    )
    {}

    public function toArray():array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
        ];
    }

    public function toJson():string
    {
        return json_encode($this->toArray(),JSON_UNESCAPED_UNICODE);
    }
}
