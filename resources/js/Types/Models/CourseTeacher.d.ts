import type Model from "@/Types/Models/Model";
import type User from "@/Types/Models/User";
import Course from "./Course";

export default interface CourseTeacher extends Model {
    teacher_id: number;
    course_id: number;
    teacher?: User;
    course?: Course;
}
