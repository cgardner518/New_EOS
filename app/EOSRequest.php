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
    'needed_by',
    'number_of_parts',
    'status',
    'cost',
    'admin_notes',
    'project_id',
    'user_id',
    'job_num'
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

  public function stl_files()
  {
    return $this->hasMany(StlFile::class, 'eos_id', 'id');
  }



}
