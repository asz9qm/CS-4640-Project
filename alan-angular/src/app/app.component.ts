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
  user = "";
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

  getUser(): Observable<any>{
    return this.http.get('http://localhost/CS-4640-Project/PHP/getUser.php');
  }

  confirmSubmit(course: Course): void{
    // submits and send post information to post file
    this.confirm_msg += course.courseID + " " + course.courseName + " was added";
    this.done = true;

    console.log("Hello");
    console.log(sessionStorage.getItem("user"));
    console.log(sessionStorage);
    console.log("Hello");

    // this.getUser().subscribe(
    //   (data)=>{
    //     this.user = data[0].user;
    //     console.log(this.user);
    //   });
    this.sendPost(course).subscribe(
      res=>{
        console.log(res);
      }
    )
  }

  sendPost(data: any): Observable<any>{ 
    //send post to PHP
    return this.http.post('http://localhost/CS-4640-Project/PHP/ngphp-post.php', data);
  }

}
