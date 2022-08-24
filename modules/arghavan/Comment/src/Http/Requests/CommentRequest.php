<?php

namespace arghavan\Comment\Http\Requests;

use arghavan\Comment\Rules\ApprovedComment;
use arghavan\Comment\Rules\CommentableRule;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "body" => "required",
            "commentable_id" => "required",
            "comment_id" => ['nullable', new ApprovedComment()],
            "commentable_type" => ['required', new CommentableRule()],
        ];
    }
}
