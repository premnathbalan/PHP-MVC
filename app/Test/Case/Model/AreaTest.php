<?php
App::uses('Area', 'Model');

/**
 * Area Test Case
 */
class AreaTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.area',
		'app.phd_student',
		'app.supervisor',
		'app.user',
		'app.department',
		'app.modified_user',
		'app.designation',
		'app.user_role',
		'app.path',
		'app.users_path',
		'app.user_roles_path',
		'app.month_year',
		'app.month',
		'app.timetable',
		'app.course_mapping',
		'app.batch',
		'app.student',
		'app.program',
		'app.academic',
		'app.semester',
		'app.faculty',
		'app.student_type',
		'app.section',
		'app.parent_group',
		'app.university_reference',
		'app.transfer_student',
		'app.revaluation_exam',
		'app.dummy_number',
		'app.dummy_number_allocation',
		'app.dummy_range_allocation',
		'app.dummy_mark',
		'app.dummy_final_mark',
		'app.end_semester_exam',
		'app.revaluation',
		'app.revaluation_dummy_mark',
		'app.course_student_mapping',
		'app.exam_attendance',
		'app.attendance',
		'app.internal_exam',
		'app.internal_practical',
		'app.cae_practical',
		'app.lecturer',
		'app.practical',
		'app.ese_practical',
		'app.internal_project',
		'app.student_authorized_break',
		'app.project_viva',
		'app.ese_project',
		'app.project_review',
		'app.cae_project',
		'app.student_malpractice',
		'app.student_mark',
		'app.student_withdrawal',
		'app.gross_attendance',
		'app.student_withheld',
		'app.withheld',
		'app.continuous_assessment_exam',
		'app.cae',
		'app.practical_attendance',
		'app.professional_training',
		'app.cae_pt',
		'app.degree_certificate',
		'app.student_audit_course',
		'app.audit_course',
		'app.course',
		'app.course_type',
		'app.course_mode'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Area = ClassRegistry::init('Area');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Area);

		parent::tearDown();
	}

}
