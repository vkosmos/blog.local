<?php

namespace App;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use Sluggable;

    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;

    protected $fillable = ['title', 'content', 'date', 'description'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /*
     *
     */

    public static function add($fields)
    {
        $post = new static;
        $post->fill($fields);
        $post->user_id = 1;
        $post->save();
        return $post;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function remove()
    {
        $this->removeImage();
        $this->delete();
    }

    public function removeImage()
    {
        if (null != $this->image){
            Storage::delete('upload/' . $this->image);
        }
    }

    public function uploadImage($image)
    {
        if (null == $image){
            return false;
        }

        $this->removeImage();
        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }

    public function getImage()
    {
        if (null == $this->image){
            return '/img/no-image.jpg';
        }

        return '/uploads/' .  $this->image;
    }

    public function setCategory($id)
    {
        if (null == $id)
        {
            return false;
        }
        $this->category_id = $id;
        $this->save();
    }

    public function setTags($ids)
    {
        if (null == $ids)
        {
            return false;
        }
        $this->tags()->sync($ids);
        $this->save();
    }

    public function setDraft()
    {
        $this->status = Post::IS_DRAFT;
        $this->save();
    }

    public function setPublic()
    {
        $this->status = Post::IS_PUBLIC;
        $this->save();
    }

    public function toggleStatus($value)
    {
        if (null == $value){
            return $this->setDraft();
        }
        else{
            return $this->setPublic();
        }
    }

    public function setFeatured()
    {
        $this->is_featured = 1;
        $this->save();
    }

    public function setStandart()
    {
        $this->is_featured = 0;
        $this->save();
    }

    public function toggleFeatured($value)
    {
        if (null == $value){
            return $this->setStandart();
        }
        else{
            return $this->setFeatured();
        }
    }


    /*
     *
     */

    public function category()
    {
        //dd($this->belongsTo(Category::class));
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'post_tags',
            'post_id',
            'tag_id'
        );
    }

    public function comments()
    {
        return $this->hasMany(
            Comment::class
        );
    }

    /*
     *
     */
    public function setDateAttribute($value)
    {
        $value = '16/3/19';
        $date = Carbon::createFromFormat('d/m/y', $value)->format('Y-m-d');
        $this->attributes['date'] = $date;
    }

    public function getDateAttribute($value)
    {
        $value = '2019-03-16';
        $date = Carbon::createFromFormat('Y-m-d', $value)->format('d/m/y');
        return $date;
    }


    public function getCategoryTitle()
    {
        return (null != $this->category_id) ? $this->category->title : 'Нет категории';
    }

    public function getTagsTitles()
    {
        return (!$this->tags->isEmpty()) ? implode(', ', $this->tags->pluck('title')->all()) : 'Нет тэгов';

    }

    public function getCategoryId()
    {
        return null != $this->category ? $this->category->id : null;
    }

    public function getDate()
    {
        return Carbon::createFromFormat('d/m/y', $this->date)->format('F d, Y');
    }

    public function hasPrevious()
    {
        return self::where('id', '<', $this->id)->max('id');
    }

    public function getPrevious()
    {
        $postId = $this->hasPrevious();
        return self::find($postId);
    }

    public function hasNext()
    {
        return self::where('id', '>', $this->id)->min('id');
    }

    public function getNext()
    {
        $postId = $this->hasNext();
        return self::find($postId);
    }

    public function related()
    {
        return self::all()->except($this->id);
    }

    public function hasCategory()
    {
        return null != $this->category ? true : false;
    }

    public function getComments()
    {
        return $this->comments()->where('status', 1)->get();
    }

}
