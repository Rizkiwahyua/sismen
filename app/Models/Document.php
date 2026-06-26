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
        'document_number_prefix',
        'document_number_suffix',
        'revision',
        'document_date',
        'description',
        'document_category_id',
        'document_code_id',
        'department_id',
        'file_document',
        'user_id',
        'updater_id',
        'deleted_by',
    ];

    protected static function booted()
    {
        static::creating(function ($document) {
            if (auth()->check()) {
                $document->user_id = auth()->id();
            }
        });

        static::updating(function ($document) {
            if (auth()->check()) {
                $document->updater_id = auth()->id();
            }
        });

        static::saving(function ($document) {
            if ($document->document_number_prefix !== null || $document->document_number_suffix !== null) {
                $codeModel = \App\Models\DocumentCode::find($document->document_code_id);
                $codeStr = $codeModel ? $codeModel->code : '';
                
                $parts = [];
                if (!empty($document->document_number_prefix)) {
                    $parts[] = $document->document_number_prefix;
                }
                if (!empty($codeStr)) {
                    $parts[] = $codeStr;
                }
                if (!empty($document->document_number_suffix)) {
                    $parts[] = $document->document_number_suffix;
                }
                $document->document_number = implode('-', $parts);
            }
        });
    }

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

    public function details()
    {
        return $this->hasMany(DocumentDetail::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updater_id');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
