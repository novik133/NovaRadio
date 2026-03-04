<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'featured_image',
        'category_id', 'author_id', 'status', 'is_featured',
        'views_count', 'published_at'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'views_count' => 'integer',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
            if (empty($article->excerpt)) {
                $article->excerpt = Str::limit(strip_tags($article->content), 150);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->content));
        return ceil($words / 200); // 200 words per minute
    }
}
admin@srv2:/www/wwwroot/www.novikradio.com$ sudo composer dump-autoload
sudo php artisan db:seed --force
PHP Warning:  Module "mbstring" is already loaded in Unknown on line 0
Generating optimized autoload files
> Illuminate\Foundation\ComposerScripts::postAutoloadDump
> @php artisan package:discover --ansi
PHP Warning:  Module "mbstring" is already loaded in Unknown on line 0

   INFO  Discovering packages.  

  laravel/tinker ...................................................................................... DONE
  nesbot/carbon ....................................................................................... DONE
  nunomaduro/termwind ................................................................................. DONE

Generated optimized autoload files containing 4325 classes
PHP Warning:  Module "mbstring" is already loaded in Unknown on line 0

   INFO  Seeding database.  


In Connection.php line 838:
                                                                                                             
  SQLSTATE[42S02]: Base table or view not found: 1146 Table 'novik_radio.dj_profiles' doesn't exist (Connec  
  tion: mysql, Host: 127.0.0.1, Port: 3306, Database: novik_radio, SQL: select * from `dj_profiles` where (  
  `team_member_id` = 2) limit 1)                                                                             
                                                                                                             

In Connection.php line 420:
                                                                                                     
  SQLSTATE[42S02]: Base table or view not found: 1146 Table 'novik_radio.dj_profiles' doesn't exist  
                                                                                                     

admin@srv2:/www/wwwroot/www.novikradio.com$ 
