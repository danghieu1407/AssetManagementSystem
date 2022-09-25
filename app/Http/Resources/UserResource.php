<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        // Gender
        $gender = $this->gender;
    
        if ($gender === true) $gender = 'Male';
        elseif ($gender === false) $gender = 'Female';
        else $gender = '';
        // Date Format
        $newDate = date("d/m/Y", strtotime($this->joined_date));
        //Full Name
        $fullName = ucfirst($this->first_name) . ' ' . ucwords($this->last_name);
        return [
            'staff_code' => $this->staff_code,
            'full_name' => $fullName,
            'username' => $this->username,
            'birthday' => $this->date_of_birth,
            'gender' => $gender,
            'joined_date' => $newDate,
            'admin' => $this->admin,
            'location' => $this->location,
            'state' => $this->state,
            'id' => $this->id,
        ];
    }
}
