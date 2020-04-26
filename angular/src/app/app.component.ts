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
  done = false; // checks if course has been added
  
  //-----------------Form answer setups-----------------//
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
  // creating course object to bind to and from
  responsedata = new Course("", "", "", true, "", "");
  courseModel = new Course("General Requirement", "Course Mnemonic", "Course Name", true, "", "");
  constructor(private http: HttpClient){ }

  confirmSubmit(course: Course): void{
    // submits and send post information to post file
    console.log(course);
    this.confirm_msg += course.courseID + " " + course.courseName + " was added";
    this.done = true;
    console.log(course);

    this.sendPost(course).subscribe(
      res=>{
        console.log(res);
      }
    )
  }

  onSubmit(form: any): void {
    // doesn't do anything
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
  }

  sendPost(data: any): Observable<any>{ 
    //send post to PHP
    return this.http.post('http://localhost/CS-4640-Project/PHP/ngphp-post.php', data);
  }

}
