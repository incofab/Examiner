<?php

namespace App\Http\Requests;

use App\Enums\Platform;
use App\Models\Exam;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreExamRequest extends FormRequest
{
  protected function prepareForValidation()
  {
    $this->merge([
      'exam_no' => Exam::platformExamNo($this->platform, $this->exam_no)
    ]);
  }
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
   * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
   */
  public function rules(): array
  {
    return [
      'platform' => ['required', new Enum(Platform::class)],
      'exam_no' => ['required', 'string', 'unique:exams,exam_no'],
      'duration' => ['required', 'integer'],
      'subject_details' => ['required', 'array'],
      'subject_details.*.course_session_id' => ['required', 'integer'],
      'subject_details.*.num_of_questions' => ['nullable', 'integer'],
      'subject_details.*.shuffle' => ['nullable', 'boolean'],
      'reference' => ['nullable', 'string']
    ];
  }
}
