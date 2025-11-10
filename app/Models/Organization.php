<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
  use HasFactory;

  protected $table = 'organizations';
  protected $primaryKey = 'organization_id';
  public $timestamps = true;

  protected $fillable = [
    'name',
    'adviser_id',
    // any other columns
  ];

  public function adviser()
  {
    return $this->belongsTo(User::class, 'adviser_id', 'user_id');
  }

  public function permits()
  {
    return $this->hasMany(Permit::class, 'organization_id', 'organization_id');
  }
}
