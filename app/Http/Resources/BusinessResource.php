<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;



class BusinessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'attributes' => [
                'name' => $this->name,
                'address' => $this->address,
                'email' => $this->email,
                'currency' => $this->currency,
                'status' => (string)$this->status,
                'image' => env('APP_URL').Storage::url('business/'.$this->image)
            ]/*,
            'relationships' => [
                'id'=> (string)$this->user->id,
                'User name' => $this->user->name,
                'User email' => $this->user->email
            ]*/
        ];
    }
}
