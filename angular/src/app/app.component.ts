import { Component } from '@angular/core';

import { HttpClient, HttpErrorResponse, HttpParams } from '@angular/common/http';
import {Observable} from 'rxjs';
import { Course } from './course';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css', "../../../CSS/requirements.css", "../../../CSS/main.css"]
})

export class AppComponent {
  title = 'UVA Semester Scheduler';
  data_submitted = "";
  confirm_msg = "";
  done = false;

  categoriesArray = [
    "General Requirement", "Computing Elective", "Integration Elective", "College Requirement"
  ]
  semestersArray = [
    "Fall 2017", "Spring 2018", "Fall 2018", "Spring 2019", "Fall 2019", "Spring 2020", 
    "Fall 2020", "Spring 2021", "Other"
  ]
  gradesArray = [
    "A+", "A", "A-", "B+", "B", "B-", "C+", "C", "C-", "D+", "D", "D-", "F", "CR", "NC"
  ]
  responsedata = new Course("", "", "", true, "", "");
  courseModel = new Course("General Requirement", "Course Mnemonic", "Course Name", true, "", "");
  constructor(private http: HttpClient){ }

  confirmSubmit(data:any): void{
    console.log(data);
    this.confirm_msg += data.courseID + data.courseName + " was added";
    this.done = true;
    console.log(data);
    this.sendPost(data).subscribe(
      res => {
        console.log(res);
      }
    );
    // let headers = new Headers({ 'Content-Type': 'application/json' });
    // let params = JSON.stringify(data);
    // this.http.post<Course>('http://localhost/CS-4640-Project/PHP/addCourse.php', params, Course);
    // console.log(data);
  }

  onSubmit(form: any): void {
    console.log('You submitted: ', form);
    this.data_submitted = form;

    // Convert the form data to json format
    let params = JSON.stringify(form);

    this.http.post<Course>('http://localhost/CS-4640-Project/PHP/ngphp-post.php', params)
    .subscribe((data) => {
      this.responsedata = data;
    }, (error) => {
      console.log('Error ', error);
    })

    //check if the course is already in, if not don't submit

  }

  sendPost(data: Course): Observable<Course[]>{ //send post to PHP
    console.log("HEY");
    console.log(data);
    console.log("HEY");
    console.log('You submitted: ', data);
    this.data_submitted = data;

    // Convert the form data to json format
    let params = JSON.stringify(data);

    return this.http.post('http://localhost/CS-4640-Project/PHP/addCourse.php', { data: data })
    .pipe(map((res) => {
      this.cars.push(res['data']);
      return this.cars;
    }),
    catchError(this.handleError));
}
    console.log(data);
  }

  // subPost(data:any){ //send request to PHP script
  //   this.sendPost(data).subscribe(
  //       res => {
  //         console.log(res);
  //       }
  //   );
  // }

}
