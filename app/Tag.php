<?php

namespace App;

use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes, MultiTenantModelTrait;

    public $table = 'tags';

    protected $fillable = ['name','address','latitude','longitude','description','img','active','created_by_id',];

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
