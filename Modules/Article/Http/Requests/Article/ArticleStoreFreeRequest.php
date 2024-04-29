<?php

namespace Modules\Article\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Entities\ReserveTemplate;

class ArticleStoreFreeRequest extends FormRequest
{
    public function rules(): array
    {
        $status_gender_acceptances = implode(',', ReserveTemplate::getStatusGenderAcceptance());

        return [
            'mobile' => 'required|filled',
            'name' => 'required|filled',
            'description' => 'nullable',
            'price' => 'nullable',
            'gender_acceptance' => "nullable|numeric|in:$status_gender_acceptances",
            'priority_show' => "nullable|numeric",
            'city_id' => 'required|numeric|exists:cities,id',
            'short_address' => 'nullable',
            'address' => 'nullable',
        ];

    }
    public function attributes(): array
    {
        return [
            'name' => __('custom.articles.articles.name'),
            'description' => __('custom.articles.articles.description'),
            'price' => __('custom.articles.articles.price'),
            'gender_acceptance' => __('custom.articles.articles.gender_acceptance'),
            'city_id' => __('custom.articles.articles.city_id'),
            'short_address' => __('custom.articles.articles.short_address'),
            'address' => __('custom.articles.articles.address'),
        ];
    }
}
