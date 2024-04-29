<?php

namespace Modules\Article\Http\Requests\Article;

class MyArticlesRequest extends ArticleIndexRequest
{
    public function authorize(): bool
    {
        return true;
        // todo should be set
        // return is_article_manager();
    }
}
