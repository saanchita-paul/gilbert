<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['setting_key', 'setting_value'];

    // Settings key
    public const AUTO_ASSIGN_TO_CHATBOT = 'auto_assign_to_chatbot'; // Automatic assign leads to chatbot
}
