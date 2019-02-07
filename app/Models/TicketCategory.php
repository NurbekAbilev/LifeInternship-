<?php

namespace App\Models;

use Validator;
use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
   public $table = "ticket_category";
   public $timestamps = false;

   protected $fillable = ['name'];

   private $rules = array(
      'name' => 'required|string'
   );

   public function validate()
   {
      $v = Validator::make(json_decode(json_encode($this), true), $this->rules);

      return $v->passes();
   }

   public function tickets()
   {
      return $this->hasMany('App\Models\Ticket', 'ticket_category', 'id');
   }
}
