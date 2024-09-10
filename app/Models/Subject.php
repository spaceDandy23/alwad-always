<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['schedule', 'name' ];

    public function students(){
        return $this->belongsToMany(Student::class, 'student_subject')->withPivot('present','id');
    }
    public function teachers(){
        return $this->belongsToMany(User::class, 'subject_teacher');
    
    }

    
}
