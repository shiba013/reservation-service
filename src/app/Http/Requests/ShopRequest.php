<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required |max:191',
            'area' => 'required |max:100',
            'genre' => 'required |max:100',
            'overview' => 'required |max:500',
            'start_time' => 'required |before:end_time',
            'end_time' => 'required |after:start_time',
        ];

        if ($this->isMethod('post')) {
            $rules['image'] = 'required |mimes:jpeg,png';
        } else {
            $rules['image'] = 'nullable |mimes:jpeg,png';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => '店名を入力してください',
            'name.max' => '店名は191文字以内で入力してください',
            'image.required' => '画像をアップロードしてください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'area.required' => 'エリアを入力してください',
            'area.max' => 'エリアは100文字以内に入力してください',
            'genre.required' => 'ジャンルを入力してください',
            'genre.max' => 'ジャンルは100文字以内に入力してください',
            'overview.required' => '概要を入力してください',
            'overview.max' => '概要は500文字以内で入力してください',
            'start_time.required' => '営業開始時間を入力してください',
            'start_time.before' => '営業終了時間を超えています',
            'end_time.required' => '営業終了時間を入力してください',
            'end_time.after' => '営業開始時間を超えています',
        ];
    }
}
