<?php

namespace Modules\Article\Http\Requests\Comment;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Entities\Comment;

class CommentUpdateRequest extends FormRequest
{
    use CustomFormRequestTrait;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->set_validator_censorship();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function rules(): array
    {
        $statuses_comment = implode(',', Comment::getStatusComment());

        return [
            'text' => 'required|filled|censorship',
            'article_id' => 'nullable|exists:articles,id',
            'parent' => 'nullable|exists:comments,id',
            'status' => "nullable|numeric|in:$statuses_comment",
        ];
    }

}
