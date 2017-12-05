<?php
App::uses('AppController', 'Controller');
class PatchController extends AppController {
	public  $uses = array("Student", "Path", "UsersPath", "UserRolesPath", "Revaluation", "Signature", "DummyNumberAllocation", "CourseStudentMapping",
			"CourseMapping"
	);
	public function index() {
		//9/10/2017
		$this->Student->StudentMark->query("UPDATE student_marks SET revaluation_status = 0 WHERE month_year_id=4 and student_id=4417 and course_mapping_id=32");
		//21/9/2017
		//$this->Student->query("ALTER TABLE `students` CHANGE `tamil_name` `tamil_name` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL");
		//28/8/2017
		//$this->Student->StudentMark->query("UPDATE student_marks SET marks = 0, status='Fail', grade_point=0, grade='RA' WHERE id=110415 and student_id=5798");
		//$this->Student->StudentMark->query("insert into student_marks (student_id, month_year_id, course_mapping_id, marks, status, grade_point, grade, created_by) values (5798, 4, 999, 71, 'Pass', 8, 'B++', 1)");
		//8/8/2017
		//$this->Revaluation->query("UPDATE revaluations SET indicator = 1 WHERE id=3112 and student_id=4417");
		
		//1/8/2017
		//$this->Student->InternalExam->query("delete from internal_exams where month_year_id=3 and student_id=8590 and course_mapping_id=409");
		//$this->Student->EndSemesterExam->query("delete from end_semester_exams where month_year_id=3 and student_id=8590 and course_mapping_id=409");
		//$this->Student->StudentMark->query("delete from student_marks where month_year_id=3 and student_id=8590 and course_mapping_id=409");
		
		//18/7/2017
		//$this->Student->ProfessionalTraining->query("UPDATE professional_trainings SET marks = 'AAA' WHERE id=71 and student_id=3117");
		//$this->Student->StudentMark->query("UPDATE student_marks SET marks = '0', status = 'Fail', grade_point = '0', grade = 'RA' WHERE id=61041 and student_id=3117");
		//$this->Student->ProfessionalTraining->query("UPDATE professional_trainings SET marks = 'AAA' WHERE id=111 and student_id=3159");
		//$this->Student->StudentMark->query("UPDATE student_marks SET marks = '0', status = 'Fail', grade_point = '0', grade = 'RA' WHERE id=61081 and student_id=3159");
		//$this->Student->ProfessionalTraining->query("UPDATE professional_trainings SET marks = 'AAA' WHERE id=183 and student_id=3234");
		//$this->Student->StudentMark->query("UPDATE student_marks SET marks = '0', status = 'Fail', grade_point = '0', grade = 'RA' WHERE id=61153 and student_id=3234");
		
		//11/7/2017
		//$this->CourseMapping->query("update course_mappings set indicator=1 where id in (1285, 1322, 1339, 1428, 1363, 1540, 1549, 1518, 1463, 1406, 1206, 1231, 1582)");
		//$this->CourseStudentMapping->query("update course_student_mappings set indicator=1 where course_mapping_id in (1285, 1322, 1339, 1428, 1363, 1540, 1549, 1518, 1463, 1406, 1206, 1231, 1582)");
		
		//6/7/2017
		//$this->CourseStudentMapping->query("update course_student_mappings set indicator=1 where course_mapping_id=280");
		//$this->Student->StudentMark->query("delete from student_marks where student_id=3141 and course_mapping_id=1589 and month_year_id=4");
		
		//1-7-2017
		/* $this->Student->query("ALTER TABLE `students` CHANGE `addlfield5` `aadhar` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL");
		$this->Student->StudentMark->query("INSERT INTO `student_marks` (`id`, `month_year_id`, `student_id`, `course_mapping_id`, `marks`, `status`, `created_by`, `grade_point`, `grade`) VALUES (NULL, '4', '3736', '1161', '51', 'Fail', '1', '6', 'C')");
		$this->Student->ProfessionalTraining->query("INSERT INTO `professional_trainings` (`id` , `month_year_id` , `student_id` , `cae_pt_id` , `marks` , `created_by`, `created`) VALUES (NULL , '4', '3736', '2', '50', '1', CURRENT_TIMESTAMP)"); */
		
		//$this->CourseStudentMapping->query("update course_student_mappings set indicator=1 where course_mapping_id=280");
		//$this->PublishStatus->query("ALTER TABLE `publish_statuses` ADD `program_id` INT NOT NULL AFTER `batch_id`");
		//$this->DummyNumberAllocation->query("delete from dummy_number_allocations where dummy_number_id=1030");
		//$this->Student->ContinuousAssessmentExam->query("delete from continuous_assessment_exams where student_id=3141 and cae_id=1961");
		//$this->Student->InternalExam->query("delete from internal_exams where student_id=3141 and course_mapping_id=1589 and month_year_id=4");
		
		//$this->Student->StudentMark->query("delete from student_marks where month_year_id=4");
		
		/* $this->Path->query("UPDATE `paths` SET `subcat` = 'Exam1' WHERE `paths`.`id` =172");
		$this->Path->query("UPDATE `paths` SET `subcat` = 'Exam1' WHERE `paths`.`id` =173");
		$this->Path->query("INSERT INTO `paths` (`id`, `cat`, `subcat`, `name`, `path`, `params`) VALUES (NULL, 'Examination', 'Exam1', 'Add', 'ExamAttendances/add', NULL), (NULL, 'Examination', 'Exam1', 'Edit', 'ExamAttendances/edit', NULL)");
		$this->Path->query("INSERT INTO `users_paths` (`id`, `user_id`, `path_id`) VALUES (NULL, '1', '263'), (NULL, '1', '264')");
		$this->Path->query("INSERT INTO `user_roles_paths` (`id`, `user_role_id`, `path_id`) VALUES (NULL, '1', '263'), (NULL, '1', '264')"); */
		
		/* $this->Student->StudentMark->query("delete from student_marks where student_id=8591 and course_mapping_id=302");
		$this->Student->StudentMark->query("delete from student_marks where student_id=8591 and course_mapping_id=303");
		$this->Student->StudentMark->query("delete from student_marks where student_id=8591 and course_mapping_id=304");
		$this->Student->StudentMark->query("delete from student_marks where student_id=8591 and course_mapping_id=305");
		$this->Student->StudentMark->query("delete from student_marks where student_id=8591 and course_mapping_id=306");
		$this->Student->StudentMark->query("delete from student_marks where student_id=8591 and course_mapping_id=307");
		$this->Student->StudentMark->query("delete from student_marks where student_id=8591 and course_mapping_id=308");
		$this->Student->StudentMark->query("delete from student_marks where student_id=8591 and course_mapping_id=309");
		$this->Student->StudentMark->query("UPDATE students SET student_type_id = 3 WHERE id=8701"); */
		//$this->Signature->query("ALTER TABLE `signatures` CHANGE `role` `role` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ");
		/* $this->Path->query("INSERT INTO `sets2015`.`paths` (`id`, `cat`, `subcat`, `name`, `path`, `params`) VALUES (NULL, 'Marks', 'Professional Training', 'Add', 'ProfessionalTrainings/add', NULL)");
		$this->Path->query("INSERT INTO `sets2015`.`paths` (`id`, `cat`, `subcat`, `name`, `path`, `params`) VALUES (NULL, 'Marks', 'Professional Training', 'Edit', 'ProfessionalTrainings/edit', NULL)");
		$this->Path->query("INSERT INTO `sets2015`.`paths` (`id`, `cat`, `subcat`, `name`, `path`, `params`) VALUES (NULL, 'Marks', 'Professional Training', 'View', 'ProfessionalTrainings/view', NULL)");
		$this->UsersPath->query("INSERT INTO `sets2015`.`users_paths` (`id`, `user_id`, `path_id`) VALUES (NULL, '1', '260')");
		$this->UsersPath->query("INSERT INTO `sets2015`.`users_paths` (`id`, `user_id`, `path_id`) VALUES (NULL, '1', '261')");
		$this->UsersPath->query("INSERT INTO `sets2015`.`users_paths` (`id`, `user_id`, `path_id`) VALUES (NULL, '1', '262')");
		$this->UserRolesPath->query("INSERT INTO `sets2015`.`user_roles_paths` (`id`, `user_role_id`, `path_id`) VALUES (NULL, '1', '260')");
		$this->UserRolesPath->query("INSERT INTO `sets2015`.`user_roles_paths` (`id`, `user_role_id`, `path_id`) VALUES (NULL, '1', '261')");
		$this->UserRolesPath->query("INSERT INTO `sets2015`.`user_roles_paths` (`id`, `user_role_id`, `path_id`) VALUES (NULL, '1', '262')"); */
		
		$this->autoRender=false;
	}
	
}