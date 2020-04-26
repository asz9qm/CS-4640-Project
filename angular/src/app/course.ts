export class Course {
    constructor(
        public category: string,
        public courseID: string,
        public courseName: string,
        public taken: boolean,
        public semester: string,
        public grade: string
    ){}
}