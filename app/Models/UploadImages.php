<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class UploadImages extends Model
{
    protected $table = 'upload_images';
    protected $primaryKey = 'upload_image_id';

    protected $fillable = [
        'file',
        'ext',
        'lead_id'
    ];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->upload_image_id;
    }
}
