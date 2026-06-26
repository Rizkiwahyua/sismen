<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentCode extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'description', 'is_active'];

    protected static function booted()
    {
        static::updated(function ($code) {
            if ($code->isDirty('code')) {
                foreach ($code->documents as $doc) {
                    $doc->save();
                }
            }
        });
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'document_code_id');
    }
}
