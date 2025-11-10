<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventApprovalFlow extends Model
{
  use HasFactory;

  protected $table = 'event_approval_flow';
  protected $primaryKey = 'approval_id';
  public $timestamps = true;

  protected $fillable = [
    'permit_id',
    'approver_role',
    'approver_id',
    'status',
    'comments',
    'signature_path',
    'approved_at',
  ];

  protected $dates = ['approved_at', 'created_at', 'updated_at'];

  // Relationships

  public function permit()
  {
    return $this->belongsTo(Permit::class, 'permit_id', 'permit_id');
  }

  public function approver()
  {
    return $this->belongsTo(User::class, 'approver_id', 'user_id');
  }
}
