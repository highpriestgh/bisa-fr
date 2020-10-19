<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Admin;
use App\Models\ArticleCategory;

class Article extends Model
{
    protected $table = 'articles';

    protected $primaryKey = 'article_id';

    /**
     * Define article author relationship
     */
    public function articleAuthor()
    {
        return $this->hasOne('App\Models\Admin', 'admin_id', 'article_author');
    }

    /**
     * Define article category relationship
     */
    public function articleCategory()
    {
        return $this->hasOne('App\Models\ArticleCategory', 'category_id', 'article_cat_id');
    }
}
