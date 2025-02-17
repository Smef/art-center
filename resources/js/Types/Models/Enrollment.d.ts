import type Model from "@/Types/Models/Model";
import type User from "@/Types/Models/User";
import Course from "./Course";

export default interface Enrollment extends Model {
    user_id: number;
    course_id: number;
    student?: User;
    course?: Course;
}
