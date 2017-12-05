<?php
App::uses('StudentAuditCoursesController', 'Controller');

/**
 * StudentAuditCoursesController Test Case
 */
class StudentAuditCoursesControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.student_audit_course',
		'app.student',
		'app.batch',
		'app.user',
		'app.department',
		'app.modified_user',
		'app.designation',
		'app.user_role',
		'app.path',
		'app.users_path',
		'app.user_roles_path',
		'app.program',
		'app.academic',
		'app.semester',
		'app.faculty',
		'app.student_type',
		'app.section',
		'app.parent_group',
		'app.transfer_student',
		'app.month_year',
		'app.month',
		'app.timetable',
		'app.course_mapping',
		'app.course',
		'app.course_type',
		'app.course_mode',
		'app.cae_pt',
		'app.lecturer',
		'app.professional_training',
		'app.course_student_mapping',
		'app.attendance',
		'app.cae',
		'app.continuous_assessment_exam',
		'app.cae_practical',
		'app.internal_practical',
		'app.ese_practical',
		'app.practical',
		'app.cae_project',
		'app.project_review',
		'app.ese_project',
		'app.project_viva',
		'app.student_mark',
		'app.internal_exam',
		'app.end_semester_exam',
		'app.dummy_number',
		'app.dummy_number_allocation',
		'app.dummy_range_allocation',
		'app.dummy_mark',
		'app.dummy_final_mark',
		'app.revaluation_dummy_mark',
		'app.revaluation',
		'app.revaluation_exam',
		'app.internal_project',
		'app.exam_attendance',
		'app.student_withheld',
		'app.withheld',
		'app.student_authorized_break',
		'app.student_malpractice',
		'app.student_withdrawal',
		'app.gross_attendance',
		'app.practical_attendance',
		'app.degree_certificate',
		'app.audit_course'
	);

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
		$this->markTestIncomplete('testIndex not implemented.');
	}

/**
 * testView method
 *
 * @return void
 */
	public function testView() {
		$this->markTestIncomplete('testView not implemented.');
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {
		$this->markTestIncomplete('testAdd not implemented.');
	}

/**
 * testEdit method
 *
 * @return void
 */
	public function testEdit() {
		$this->markTestIncomplete('testEdit not implemented.');
	}

/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {
		$this->markTestIncomplete('testDelete not implemented.');
	}

}
