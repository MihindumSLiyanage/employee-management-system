<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // Defining the fillable fields to allow mass assignment
    // (which fields can be filled automatically when creating or updating an employee.)
    protected $fillable = ['name', 'email', 'phone', 'position', 'salary'];
}