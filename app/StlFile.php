<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StlFile extends Model
{
    //
    protected $table = 'stl_files';

    protected $fillable = [
      'file_name',
      'file_size',
      'eos_id',
      'uploaded_by',
      'dimX',
      'dimY',
      'dimZ',
      'clean',
      'hinges',
      'threads',
    ];
}
