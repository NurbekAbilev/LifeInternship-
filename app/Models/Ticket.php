<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public $table = "ticket";

    private $rules = array(
        'full_name' => 'required',
        'email' => 'required|email',
        'phone_num' => 'required',
        'ticket_category' => 'required',
        'description' => 'required'
    );

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'ticket_id', 'id')->orderBy('created_at');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\TicketCategory', 'ticket_category', 'id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\TicketStatus', 'ticket_status', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo('App\User', 'admin_id');
    }

    public function statusColor()
    {
        switch ($this->status()->first()->id) {
            case (1):
                return "text-primary";
            case (2):
                return "text-info";
            case (3):
                return "text-danger";
            case (4):
                return "text-success";
            case (5):
                return "text-muted";
        }
    }

    public function getRouteKeyName()
    {
        return 'hash';
    }

    public function validate()
    {
        $validator = Validator::make(json_decode(json_encode($this), true), $this->rules);

        return $validator->passes();
    }
}
