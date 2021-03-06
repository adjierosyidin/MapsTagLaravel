<?php

namespace App;

use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;

class Tag extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia;

    public $table = 'tags';

    protected $fillable = ['name','address','latitude','longitude','description','active', 'tag_color','created_by_id',];

    public $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'img', 'thumbnail'
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('img')
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(325)
                    ->height(210);
            });
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function color()
    {
        return $this->belongsTo(User::class, 'tag_color');
    }

    public function getImgAttribute()
    {
        $files = $this->getMedia('img');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
        });

        return $files;
    }

    public function getThumbnailAttribute()
    {
        return $this->getFirstMediaUrl('img', 'thumb');
    }

    public function scopeSearchResults($query)
    {
        return $query->where('active', 1)
            ->when(request()->filled('date'), function($query) {
                $query->where(function($query) {
                    $search = request()->input('date');
                    $query->where('created_at', 'LIKE', "%$search%");
                });
            })
            ->when(request()->filled('search'), function($query) {
                $query->where(function($query) {
                    $search = request()->input('search');
                    $query->where('name', 'LIKE', "%$search%")
                        ->orWhere('description', 'LIKE', "%$search%")
                        ->orWhere('address', 'LIKE', "%$search%");
                });     
            });
    }
}
