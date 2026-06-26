<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentDetail extends Model
{
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    protected $fillable = [
        'document_id',
        'sub_title',
        'department_ids',
        'description'
    ];

    protected $casts = [
        'department_ids' => 'array',
    ];
}
