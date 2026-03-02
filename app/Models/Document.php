<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'document_number',
        'revision',
        'document_date',
        'description',
        'document_category_id',
        'document_code_id',
        'department_id',
        'file_document',
    ];

    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    public function code()
    {
        return $this->belongsTo(DocumentCode::class, 'document_code_id');
    }

    public function department()
{
    return $this->belongsTo(Department::class);
}



}
