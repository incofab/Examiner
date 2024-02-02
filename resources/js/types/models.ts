import { ExamAttempt } from './types';

export interface Row {
  id: number;
  created_at: string;
  updated_at: string;
}

export interface User extends Row {
  first_name: string;
  last_name: string;
  other_names: string;
  full_name: string;
  phone: string;
  photo: string;
  photo_url: string;
  email: string;
  is_welfare: boolean;
  gender: string;
}

export interface Course extends Row {
  course_title: string;
  course_code: string;
  category: string;
  description: string;
  sessions: CourseSession[];
}

export interface Question extends Row {
  question_no: number;
  question: string;
  option_a: string;
  option_b: string;
  option_c: string;
  option_d: string;
  option_e: string;
}

export interface CourseSession extends Row {
  course_id: number;
  session: string;
  category: string;
  general_instruction: string;
  course?: Course;
  questions?: Question[];
}

export interface Exam extends Row {
  // event_id: number;
  platform: string;
  reference: string;
  duration: number;
  exam_no: string;
  show_result: boolean;
  time_remaining: number;
  start_time: string;
  pause_time: string;
  end_time: string;
  score: number;
  num_of_questions: number;
  status: string;
  exam_items?: ExamItem[];
  attempts: ExamAttempt;
}

export interface ExamItem extends Row {
  exam_id: number;
  course_session_id: number;
  status: string;
  score: number;
  num_of_questions: number;
  exam?: Exam;
  course_session?: CourseSession;
}
