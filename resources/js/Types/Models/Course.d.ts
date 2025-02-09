import type Model from "@/Types/Models/Model";
import type Location from "@/Types/Models/Location";
import type User from "@/Types/Models/User";

export default interface Course extends Model {
    name: string;
    location_id: number;
    location?: Location;
    start_date: string; // ISO date string
    end_date: string; // ISO date string
    students?: User[]; // Many-to-many relationship through course_students
    teachers?: User[]; // Many-to-many relationship through course_teachers
}
