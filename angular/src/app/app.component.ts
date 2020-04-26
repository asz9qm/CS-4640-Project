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

  confirmSubmit(course: Course): void{
    console.log(course);
    this.confirm_msg += course.courseID + course.courseName + " was added";
    this.done = true;
    console.log(course);

     // Convert the form data to json format
    //  let params = JSON.stringify(course);
    //  console.log(params);

     // To send a GET request, use the concept of URL rewriting to pass data to the backend
     // this.http.get<Order>('http://localhost/cs4640/inclass11/ngphp-get.php?str='+params)
     // To send a POST request, pass data as an object
    this.sendPost(course).subscribe(
      res=>{
        console.log(res);
      }
    )
     //  return this.http.post<Course>('http://localhost/CS-4640-Project/PHP/ngphp-post.php', course);
    //  .subscribe((data2) => {
    //       // Receive a response successfully, do something here
    //       // console.log('Response from backend ', data);
    //       this.responsedata = data2;     // assign response to responsedata property to bind to screen later
    //  }, (error) => {
    //       // An error occurs, handle an error in some way
    //       console.log('Error ', error);
    //  })
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

  sendPost(data: any): Observable<any>{ //send post to PHP
  //   console.log("HEY");
  //   console.log(data);
  //   console.log("HEY");
  //   console.log('You submitted: ', data);
  //   this.data_submitted = data;

  //   // Convert the form data to json format
  //   let params = JSON.stringify(data);
  //   console.log(params);

    return this.http.post('http://localhost/CS-4640-Project/PHP/ngphp-post.php', data);
  //   console.log(data);
  }

  // subPost(data:any){ //send request to PHP script
  //   this.sendPost(data).subscribe(
  //       res => {
  //         console.log(res);
  //       }
  //   );
  // }

}
