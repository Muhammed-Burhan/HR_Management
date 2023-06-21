<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'device_name' => $this->device_name,
            'serial_number'=>$this->serial_number,
            'mac_address'=>$this->mac_address,
            'status'=>(bool) $this->status,
            'branch_id'=>$this->branch_id,
            'registered_date'=>$this->registered_date,
            'sold_date'=>$this->sold_date,
            'cartoon_number'=>$this->cartoon_number,
        ];
    }
}
