<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    //
    use SoftDeletes;

    protected $table = 'products';
    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'video_link', 'product_link', 'image_link', 'channel_id', 'old_price', 'new_price', 'description', 'start_time', 'end_time', 'available_time', 'start_date'];

    public function channel(){
    	return $this->belongsTo('App\Channels', 'channel_id');
    }

}
