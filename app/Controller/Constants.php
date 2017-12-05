<?php
App::uses('AppController', 'Controller');
 
class ConstantsController extends AppController {
	//CourseStudentMapping
    public static $coursesNotMapped = "Courses are yet to be mapped for";
    public static $studentsNotAvailableInDatabase = "Students are yet to be enrolled for";
    
    public static $CSMSuccessMessage = "Student Course mapped for"; 
    public static $CSMFailureMessage = "Student Course not mapped for";

    public static $pass = "Pass";
    public static $fail = "Fail";
    
}