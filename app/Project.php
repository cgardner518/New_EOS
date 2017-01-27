<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
    protected $fillable = [
      'name'
    ];
    
    // These will be pulled LASR schedular site at NRL
    public static function projectsForUser($user_id = ''){

      return [
        1 => 'Project One',
        5 => 'Poject Five',
      ];
    }

    public static function allProjects(){
      return [
        1 => 'Project One',
        2 => 'Project Two',
        3 => 'Project Three',
        4 => 'Project Four',
        5 => 'Poject Five',
      ];
    }
}
