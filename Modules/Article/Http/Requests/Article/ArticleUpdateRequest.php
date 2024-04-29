<?php

namespace Modules\Article\Http\Requests\Article;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Entities\ReserveTemplate;
use Modules\Article\Entities\Article;

class ArticleUpdateRequest extends FormRequest
{
    use CustomFormRequestTrait;
    protected function prepareForValidation(): void
    {
        if ($this->has('is_ball')) {
            $is_ball = $this->get('is_ball');
            $this->merge(['is_ball' => $is_ball ? 1 : 0]);
        }
    }
    public function authorize(): bool
    {
        $article_id = request('id');
        $logged_in_user_id = get_user_id_login();

        return
            is_admin()
            || (is_article_manager() &&
                Article::query()
                    ->where('article_id', $article_id)
                    ->where('user_id', $logged_in_user_id)
                    ->exists());
    }
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->set_validator_update_unique();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }
    public function rules(): array
    {
        $list_status_allowable = trim(implode(',', Article::getStatusArticle()));
        $status_gender_acceptances = implode(',', ReserveTemplate::getStatusGenderAcceptance());

        $rules = [
            'name' => 'nullable|filled',
            'description' => 'nullable',
            'reason_article_disabled' => 'nullable',
            'price' => 'nullable',
            'status' => "nullable|numeric|in:$list_status_allowable",
            'gender_acceptance' => "nullable|numeric|in:$status_gender_acceptances",
            'priority_show' => "nullable|numeric",
            'profit_share_percentage' => 'nullable|min:0|max:100',
            'is_ball' => 'nullable|in:0,1',
            'ball_price' => 'nullable',
            'city_id' => 'nullable|numeric|exists:cities,id',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'short_address' => 'nullable',
            'address' => 'nullable',

            # tags
            'tag_id' => 'nullable|filled|exists:tags,id',
            'tags' => 'nullable|array',
            'tags.*' => 'required|filled|exists:tags,id',

            # categories
            'category_id' => 'nullable|filled|exists:categories,id',
            'categories' => 'nullable|array',
            'categories.*' => 'required|filled|exists:categories,id',

            # keywords
            'keyword_id' => 'nullable|filled|exists:keywords,id',
            'keywords' => 'nullable|array',
            'keywords.*' => 'required|filled|exists:keywords,id',

            # sports
            'sport_id' => 'nullable|filled|exists:sports,id',
            'sports' => 'nullable|array',
            'sports.*' => 'required|filled|exists:sports,id',

            # attributes
            'attribute_id' => 'nullable|filled|exists:articles_attributes,id',
            'attributes' => 'nullable|array',
            'attributes.*' => 'required|filled|exists:articles_attributes,id',

            # images
            'images' => 'nullable|array',
            'images.*' => 'required',
        ];

        if (is_admin() || is_super_admin()) {
            $rules = [
                ...$rules,
                'user_article_manager_id' => 'nullable|exists:users,id',
            ];
        }

        return $rules;
    }
    public function attributes(): array
    {
        return [
            'name' => __('custom.articles.articles.name'),
            'description' => __('custom.articles.articles.description'),
            'price' => __('custom.articles.articles.price'),
            'status' => __('custom.articles.articles.status'),
            'gender_acceptance' => __('custom.articles.articles.gender_acceptance'),
            'user_id' => __('custom.articles.articles.user_id'),
            'profit_share_percentage' => __('custom.articles.articles.profit_share_percentage'),
            'is_ball' => __('custom.articles.articles.is_ball'),
            'ball_price' => __('custom.articles.articles.ball_price'),
            'city_id' => __('custom.articles.articles.city_id'),
            'latitude' => __('custom.articles.articles.latitude'),
            'longitude' => __('custom.articles.articles.longitude'),
            'short_address' => __('custom.articles.articles.short_address'),
            'address' => __('custom.articles.articles.address'),

            # ################
            # tags
            'tag_id' => __('custom.articles.tags.id'),
            'tags' => __('custom.articles.tags'),
            'tags.*' => __('custom.articles.tags'),

            # categories
            'category_id' => __('custom.articles.categories.id'),
            'categories' => __('custom.articles.categories'),
            'categories.*' => __('custom.articles.categories'),

            # keywords
            'keyword_id' => __('custom.articles.keywords.id'),
            'keywords' => __('custom.articles.keywords'),
            'keywords.*' => __('custom.articles.keywords'),

            # sports
            'sport_id' => __('custom.articles.sports.id'),
            'sports' => __('custom.articles.sports'),
            'sports.*' => __('custom.articles.sports'),

            # attributes
            'attribute_id' => __('custom.articles.attributes.id'),
            'attributes' => __('custom.articles.attributes'),
            'attributes.*' => __('custom.articles.attributes'),

            # images
            'images' => __('custom.articles.images'),
            'images.*' => __('custom.articles.images.*'),
        ];
    }
}
