<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $primaryKey = 'profile_id';

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'contact_number',
        'address',
        'office',
        'profile_picture_path',
    ];

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

  protected $table = 'user_profiles';
  protected $primaryKey = 'profile_id';
  protected $fillable = [
    'user_id',
    'first_name',
    'middle_name',
    'last_name',
    'contact_number',
    'address',
  ];

  // Relation to User
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  // Full name accessor
  public function getFullNameAttribute()
  {
    return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
  }
}
