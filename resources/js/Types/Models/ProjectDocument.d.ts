import type Model from "@/Types/Models/Model";

type ProjectDocument = Model & {
    project_id: number;
    title: string;
    description: string;
    file_url: string;
    file_name: string;
};

export default ProjectDocument;
