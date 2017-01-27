<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class EOSRequest extends Model
{
  protected $table = 'eos_requests';

  protected $fillable = [
    'name',
    'description',
    'dimX',
    'dimY',
    'dimZ',
    'clean',
    'hinges',
    'threads',
    'needed_by',
    'number_of_parts',
    'status',
    'cost',
    'admin_notes',
    'project_id',
    'user_id',
    'stl'
  ];

  public function getVolumeAttribute()
  {
    return ($this->dimX*$this->dimY)*$this->dimZ;
  }

  public function getFilePathAttribute()
  {
    return '/download/'.$this->id.'/'.$this->stl;
  }

  public function users(){
    return $this->hasOne(User::class, 'id', 'user_id');
  }



}
