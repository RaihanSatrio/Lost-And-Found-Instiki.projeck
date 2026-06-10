<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // Tambahin baris di bawah ini biar kolomnya boleh diisi data
    protected $fillable = ['title', 'category', 'location', 'desc', 'image'];
}