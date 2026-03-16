<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CmsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
public function rules()
{
    return [
        'type'                 => 'nullable|string',
        'title'                => 'nullable|string|max:255',
        'sub_title'            => 'nullable|string|max:255',
        'btn_text'             => 'nullable|string',
        'btn_link'             => 'nullable|string',
        'video_file'           => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
        'bottom_video'         => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
        'image1'               => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
        'image2'               => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',

        // Section 2
        'sec2_title'           => 'nullable|string',
        'sec2_items'           => 'nullable|array', // Corrected from string to array
        'sec2_items.*.title'   => 'nullable|string',
        'sec2_items.*.desc'    => 'nullable|string',

        // Section 6 (Case Study)
        'case_sec_title'       => 'nullable|string',
        'case_sec_subtitle'    => 'nullable|string',
        'case_image_subtile'   => 'nullable|string',
        'case_description'     => 'nullable|string',
        'industry_description' => 'nullable|string',

        // Industry Items
        'industry'             => 'nullable|array',
        'industry.*.title'     => 'nullable|string',
        'industry.*.image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',

        //AI agent
        'ai_agents_title'         => 'nullable|string|max:255',
        'ai_agents_discription'     => 'nullable|string',
        'ai_deployment'           => 'nullable|array',
        'ai_deployment.*.title'   => 'nullable|string',
        'ai_deployment.*.desc'    => 'nullable|string',
                // FAQ & Bottom
        'faq_title'       => 'nullable|string|max:255',
        'faq_discription' => 'nullable|string',
        'faq'             => 'nullable|array',
        'faq.*.q'         => 'nullable|string',
        'faq.*.a'         => 'nullable|string',
        //bottom
        'bottom_desc'          => 'nullable|string',
    ];
}
}

