<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_TingkatResiko extends Model
{
    use HasFactory;

    protected $table = 'm_tingkat_resiko';
    protected $guarded = ['id'];
    public $timestamps = false;
}
