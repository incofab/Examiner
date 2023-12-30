<?php
namespace App\Actions;

use App\Models\CourseSession;
use App\Models\Exam;
use App\Models\ExamItem;
use Cache;
use Http;

/**
 * Retrieves the subject details and attach them to respective exam items
 */
class RetrieveSubjectDetails
{
  function __construct(private Exam $exam)
  {
  }

  /** @return CourseSession[] */
  public function run()
  {
    $subjectDetails = $this->getDetails();
    $courseSessions = array_map(function ($data) {
      $courseSession = new CourseSession($data);
      $examItem = $this->findExamItem($courseSession->id);
      if ($examItem) {
        $examItem->course_session = $courseSession;
      }
      $questionCount = count($courseSession->questions);
      $examItem->safeUpdate(['num_of_questions' => $questionCount]);
      // ExamItem::query()
      //   ->find($examItem->id)
      //   ->update(['num_of_questions' => $questionCount]);
      return $courseSession;
    }, $subjectDetails);

    return $courseSessions;
  }

  private function findExamItem($courseSessionId): ExamItem|null
  {
    $matchedExamItems = $this->exam->examItems->filter(
      fn($item) => $item->course_session_id == $courseSessionId
    );
    return $matchedExamItems->first();
    // $filter = array_filter(
    //   $this->exam->examItems,
    //   fn($item) => $item->course_session_id == $courseSessionId
    // );
    // $examItem = reset($filter);
    // return $examItem;
  }

  private function getDetails(): array
  {
    $cacheKey = self::getCacheKey($this->exam->exam_no);
    $subjectDetails = Cache::get($cacheKey, function () use ($cacheKey) {
      $url = config('services.content.retrieve-course-sessions');
      $res = Http::post($url, ['subjects' => $this->exam->examItems]);

      abort_unless($res->ok(), 401, 'Error retrieving records');
      $data = $res->json('course_sessions');
      Cache::put($cacheKey, $data, now()->addHours(24));
      return $data;
    });
    return (array) $subjectDetails;
  }

  private static function getCacheKey(string $examNo)
  {
    return "subject_details_{$examNo}";
  }

  static function removeCache(Exam $exam)
  {
    Cache::delete(self::getCacheKey($exam->exam_no));
  }
}
