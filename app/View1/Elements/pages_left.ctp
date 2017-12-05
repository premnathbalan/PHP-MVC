<!-- #section:basics/sidebar -->

<div id="sidebar" class="sidebar responsive"> 
    <!-- /.sidebar-shortcuts --> 
  <ul class="nav nav-list">    
    <li class=""> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-male"></i> <span class="menu-text"> Students </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">     
       <li class=""><?php echo $this->Html->link("Student Add"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Students",'action' => 'add'),array('data-placement'=>'left','escape' => false)); ?></li>
       <li class=""><?php  echo $this->Html->link("Search/Reg.No."." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Students",'action' => 'regNoSearch'),array('data-placement'=>'left','escape' => false)); ?></li>
       <li class=""><?php  echo $this->Html->link("Search/List"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Students",'action' => 'studentSearch'),array('data-placement'=>'left','escape' => false)); ?></li>
        <!--<li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> View Total Credits </a> <b class="arrow"></b></li>
        <li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> View Attendance Sem Wise </a> <b class="arrow"></b></li>
        <li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Add and View Malpractice Info </a> <b class="arrow"></b></li>
        <li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Add and View NCC </a> <b class="arrow"></b></li>
        <li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Add/View Remarks About Student RTE </a> <b class="arrow"></b></li>
        <li class=""> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-caret-right"></i> Issue TC <b class="arrow fa fa-angle-down"></b></a><b class="arrow"></b> 
			<ul class="submenu">
				<li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Restore Student </a> <b class="arrow"></b> </a></li>
			</ul>
		</li>
		<li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Issue Certificate and Mark Sheets </a> <b class="arrow"></b> </li>
		<li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Issue Provisional Certificate </a> <b class="arrow"></b> </li>
		<li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Issue Course Completion Certificate </a> <b class="arrow"></b> </li>
		<li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Issue Hall Ticket for Selected Exam </a> <b class="arrow"></b> </li>-->
		<li class=""><?php echo $this->Html->link("Photo List"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Students",'action' => 'photo_list'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Student Upload"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Students",'action' => 'studentUpload'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("With Held Student"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Students",'action' => 'studentWithHeld'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Photo Synchronize"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Students",'action' => 'photoSynchronize'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Signature Synchronize"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Students",'action' => 'signatureSynchronize'),array('data-placement'=>'left','escape' => false)); ?></li>
     	<li class=""><?php echo $this->Html->link("Late Joiner"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Students",'action' => 'lateJoiner'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Batch Transfer"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Students",'action' => 'batchTransfer'),array('data-placement'=>'left','escape' => false)); ?></li>
      	<li class=""><?php echo $this->Html->link("Withdrawal"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Students",'action' => 'withdrawal'),array('data-placement'=>'left','escape' => false)); ?></li>
      	<li class=""><?php echo $this->Html->link("Authorized Break of Study"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"StudentAuthorizedBreaks",'action' => 'abs'),array('data-placement'=>'left','escape' => false)); ?></li>
      	<li class=""><?php echo $this->Html->link("Audit Courses"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"StudentAuditCourses",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
      </ul>
    </li>
    <?php //}
    ?>
    
    <?php //$allAccessArray = range(1,211);
	//if(array_intersect($allAccessArray, array_values($check_access_all))){?>
    <li class=""> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-graduation-cap"></i> <span class="menu-text"> PhD Students </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <!--<li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Students</a> <b class="arrow"></b> </li>-->
        <li class=""><?php echo $this->Html->link("Students"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"PhdStudents",'action' => 'index'),array('data-placement'=>'left','escape' => false));?></li>
        <li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Subjects</a> <b class="arrow"></b> </li>
		<li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Marks</a> <b class="arrow"></b> </li>
        <li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Results</a> <b class="arrow"></b> </li>
		<li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Report</a> <b class="arrow"></b> </li>
        <li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Provisional</a> <b class="arrow"></b> </li>
      </ul>
    </li>
    <?php //}
    ?>
    
	 <?php //$allAccessArray = range(1,211);
      //if(array_intersect($allAccessArray, array_values($check_access_all))){?>
      <li class=""> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-file-word-o"></i> <span class="menu-text"> Marks </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        
        <?php //$allAccessArray = range(1,211);
        //if(array_intersect($allAccessArray, array_values($check_access_all))){
        ?>
        <li class=""> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-caret-right"></i>Theory <b class="arrow fa fa-angle-down"></b></a> <b class="arrow"></b> 
			<ul class="submenu">	
        		<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Attendance Sheet & Foil Card", array("controller"=>"Attendances",'action' => 'attendance_foil_cards'),array('data-placement'=>'left','escape' => false)); ?></li>	
				<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Course Attendance", array("controller"=>"Attendances",'action' => 'index','C'),array('data-placement'=>'left','escape' => false)); ?></li>
				<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Gross Attendance", array("controller"=>"Attendances",'action' => 'index','G'),array('data-placement'=>'left','escape' => false)); ?></li>
				<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Assessment Marks", array("controller"=>"ContinuousAssessmentExams",'action' => 'theory'),array('data-placement'=>'left','escape' => false)); ?></li>
				<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Calculate CAE Marks", array("controller"=>"ContinuousAssessmentExams",'action' => 'calculateCAEMarks'),array('data-placement'=>'left','escape' => false)); ?></li>
				<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Moderate CAE Marks", array("controller"=>"ContinuousAssessmentExams",'action' => 'moderateCae'),array('data-placement'=>'left','escape' => false)); ?></li>
				<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Final CAE Marks Download & Print", array("controller"=>"ContinuousAssessmentExams",'action' => 'final_cae'),array('data-placement'=>'left','escape' => false)); ?></li>
				<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Download Template", array("controller"=>"ContinuousAssessmentExams",'action' => 'createCaeDownloadTemplate'),array('data-placement'=>'left','escape' => false));?></li>
				<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Marks Import", array("controller"=>"ContinuousAssessmentExams",'action' => 'readMarksFromExcel'),array('data-placement'=>'left','escape' => false)); ?></li>
				<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Theory CAE Mark Print", array("controller"=>"Attendances",'action' => 'caeMarkPrint/theory'),array('data-placement'=>'left','escape' => false)); ?></li>
				<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."CAE Mark Data", array("controller"=>"Attendances",'action' => 'batchwiseCae/theory/markdata'),array('data-placement'=>'left','escape' => false)); ?></li>
				<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."CAE to Website", array("controller"=>"Attendances",'action' => 'batchwiseCae/theory/website'),array('data-placement'=>'left','escape' => false)); ?></li>
			</ul>			
		</li>
		<?php //}
		?>
	
	<?php //$allAccessArray = range(1,211);
        //if(array_intersect($allAccessArray, array_values($check_access_all))){
        ?>				
      <li class=""> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-caret-right"></i>Practical <b class="arrow fa fa-angle-down"></b></a> <b class="arrow"></b>
	      <ul class="submenu">
	      	<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Attendance Sheet & Foil Card", array("controller"=>"Attendances",'action' => 'practical_attendance_foil_cards'),array('data-placement'=>'left','escape' => false)); ?></li>
	        <li class=""> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-caret-right"></i>C.A.E <b class="arrow fa fa-angle-down"></b></a> <b class="arrow"></b>
	          <ul class="submenu">
	            <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Assessment Marks", array("controller"=>"CaePracticals",'action' => 'practical'),array('data-placement'=>'left','escape' => false)); ?></li>
	            <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Download Template", array("controller"=>"CaePracticals",'action' => 'practicalDownloadTemplate'),array('data-placement'=>'left','escape' => false)); ?></li>
	            <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Bulk Import", array("controller"=>"CaePracticals",'action' => 'practicalImport'),array('data-placement'=>'left','escape' => false)); ?></li>
	          </ul>
	        </li>
	        <li class=""> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-caret-right"></i>E.S.E <b class="arrow fa fa-angle-down"></b></a> <b class="arrow"></b>
	          <ul class="submenu">
	          	<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Assessment Marks", array("controller"=>"EsePracticals",'action' => 'practical'),array('data-placement'=>'left','escape' => false)); ?></li>
	            <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Download Template", array("controller"=>"EsePracticals",'action' => 'practicalDownloadTemplate'),array('data-placement'=>'left','escape' => false)); ?></li>
	            <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Bulk Import", array("controller"=>"EsePracticals",'action' => 'practicalImport'),array('data-placement'=>'left','escape' => false)); ?></li>
	          </ul>
	        </li>
	        <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Calculate Practical", array("controller"=>"EsePracticals",'action' => 'calculate'),array('data-placement'=>'left','escape' => false)); ?></li>
	        <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Practical Report", array("controller"=>"EsePracticals",'action' => 'report'),array('data-placement'=>'left','escape' => false)); ?></li>
	        <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Moderation", array("controller"=>"EsePracticals",'action' => 'moderate'),array('data-placement'=>'left','escape' => false)); ?></li>
	        <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Final Practical Marks", array("controller"=>"EsePracticals",'action' => 'finalPractical'),array('data-placement'=>'left','escape' => false)); ?></li>
	        <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Practical CAE Mark Print", array("controller"=>"Attendances",'action' => 'caeMarkPrint/practical'),array('data-placement'=>'left','escape' => false)); ?></li>	        
	      </ul>
		</li>
		
		<li class=""> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-caret-right"></i>Project <b class="arrow fa fa-angle-down"></b></a> <b class="arrow"></b>
	      <ul class="submenu">
	      	<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Attendance Sheet & Foil Card", array("controller"=>"Attendances",'action' => 'project_attendance_foil_cards'),array('data-placement'=>'left','escape' => false)); ?></li>
	        <li class=""> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-caret-right"></i>C.A.E <b class="arrow fa fa-angle-down"></b></a> <b class="arrow"></b>
	          <ul class="submenu">
	            <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Assessment Marks", array("controller"=>"CaeProjects",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
	            <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Download Template", array("controller"=>"CaeProjects",'action' => 'download'),array('data-placement'=>'left','escape' => false)); ?></li>
	            <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Bulk Import", array("controller"=>"CaeProjects",'action' => 'import'),array('data-placement'=>'left','escape' => false)); ?></li>
	          </ul>
	        </li>
	        <li class=""> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-caret-right"></i>E.S.E <b class="arrow fa fa-angle-down"></b></a> <b class="arrow"></b>
	          <ul class="submenu">
	            <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Assessment Marks", array("controller"=>"EseProjects",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
	            <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Download Template", array("controller"=>"EseProjects",'action' => 'download'),array('data-placement'=>'left','escape' => false)); ?></li>
	            <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Bulk Import", array("controller"=>"EseProjects",'action' => 'import'),array('data-placement'=>'left','escape' => false)); ?></li>
	          </ul>
	        </li>
	        <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Calculate CAE", array("controller"=>"CaeProjects",'action' => 'calculateCae'),array('data-placement'=>'left','escape' => false)); ?></li>
	        <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Calculate Project", array("controller"=>"EseProjects",'action' => 'calculate'),array('data-placement'=>'left','escape' => false)); ?></li>
	        <!--<li class=""> <?php if(in_array(1, array_values($check_access_all))){echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Project Report", array("controller"=>"EseProjects",'action' => 'report'),array('data-placement'=>'left','escape' => false));} ?></li>-->
	        <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Final Project Marks", array("controller"=>"EseProjects",'action' => 'finalProject'),array('data-placement'=>'left','escape' => false)); ?></li>
	        <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Project CAE Mark Print", array("controller"=>"Attendances",'action' => 'caeMarkPrint/project'),array('data-placement'=>'left','escape' => false)); ?></li>
	      </ul>
		</li>
		<?php //}
		?>
     
      	<?php //$allAccessArray = range(1,211);
        //if(array_intersect($allAccessArray, array_values($check_access_all))){
        ?>
        <li class=""> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-caret-right"></i>Professional Training <b class="arrow fa fa-angle-down"></b></a> <b class="arrow"></b> 
			<ul class="submenu">
				<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Attendance Sheet & Foil Card", array("controller"=>"Attendances",'action' => 'pt_attendance_foil_cards'),array('data-placement'=>'left','escape' => false)); ?></li>
				<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Assessment Marks", array("controller"=>"CaePts",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
				<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Download Template", array("controller"=>"ProfessionalTrainings",'action' => 'download'),array('data-placement'=>'left','escape' => false)); ?></li>
				<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Marks Import", array("controller"=>"ProfessionalTrainings",'action' => 'upload'),array('data-placement'=>'left','escape' => false)); ?></li>
			</ul>			
		</li>
		<?php //}
		?>
		
      </ul>
    </li>    
     <?php //}
     ?>     
     
     <?php //$allAccessArray = range(1,211);
        //if(array_intersect($allAccessArray, array_values($check_access_all))){
        ?>
    <li class=""> <a href="#"  class="dropdown-toggle"> <i class="menu-icon fa fa-list-alt"></i> <span class="menu-text"> Arrear</span> <b class="arrow fa fa-angle-down"></b></a> <b class="arrow"></b> 
		<ul class="submenu">
		    <li class=""> <a href="#"  class="dropdown-toggle"> <i class="menu-icon fa fa-eyedropper"></i>Theory</span> <b class="arrow"></b></a> <b class="arrow"></b> 
				<ul class="submenu">
				<!--<li class=""><?php if(in_array(1, array_values($check_access_all))){echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Theory Arrear Report", array("controller"=>"TheoryArrears",'action' => 'index'),array('data-placement'=>'left','escape' => false));} ?>-->
				<!--<li class=""><?php if(in_array(1, array_values($check_access_all))){echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Theory Arrear<br/>Theory Arrear Attendace Sheet<br/>Theory Arrear Cover Page", array("controller"=>"TheoryArrears",'action' => 'index'),array('data-placement'=>'left','escape' => false));} ?>-->
				<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Theory Arrear Mark entry", array("controller"=>"TheoryArrears",'action' => 'theory'),array('data-placement'=>'left','escape' => false)); ?>
				</ul>
			</li>	
			
			<li class=""> <a href="#"  class="dropdown-toggle"> <i class="menu-icon fa fa-flask"></i>Practical</span> <b class="arrow"></b></a> <b class="arrow"></b> 
				<ul class="submenu">
				<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Practical Arrear<br/>Practical Arrear Attendace Sheet<br/>Practical Arrear Cover Page", array("controller"=>"Arrears",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
				<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Practicals Arrear Mark entry", array("controller"=>"Arrears",'action' => 'arrear'),array('data-placement'=>'left','escape' => false)); ?>
				</ul>
			</li>	
			
			<li class=""> <a href="#"  class="dropdown-toggle"> <i class="menu-icon fa fa-list-alt"></i>Project</span> <b class="arrow"></b></a> <b class="arrow"></b> 
				<ul class="submenu">
				<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Project Arrear<br/>Project Arrear Attendace Sheet<br/>Project Arrear Cover Page", array("controller"=>"ProjectArrears",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
				<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Project Arrear Mark entry", array("controller"=>"ProjectArrears",'action' => 'project'),array('data-placement'=>'left','escape' => false)); ?>
				</ul>
			</li>
			
		</ul>
	</li>	
	<?php //}
	?>
	
    <?php //$allAccessArray = range(1,211);
        //if(array_intersect($allAccessArray, array_values($check_access_all))){
        ?>
    <li class=""> <a href="#"  class="dropdown-toggle"> <i class="menu-icon fa fa-list-alt"></i> <span class="menu-text"> Examination </span> <b class="arrow fa fa-angle-down"></b></a> <b class="arrow"></b> 
		<ul class="submenu">
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Exam Label", array("controller"=>"Students",'action' => 'label'),array('data-placement'=>'left','escape' => false)); ?>
		<!--<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Cumulative Arrear Report", array("controller"=>"StudentMarks",'action' => 'cumulativeArrearReport'),array('data-placement'=>'left','escape' => false)); ?>-->
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Time Table", array("controller"=>"Timetables",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Exam Attendance <br/>Foil Card <br/>Cover Page", array("controller"=>"ExamAttendances",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."ESE Exam Absent Record", array("controller"=>"ExamAttendances",'action' => 'absent'),array('data-placement'=>'left','escape' => false)); ?>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Dummy Number View", array("controller"=>"DummyNumbers",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Dummy Number Add", array("controller"=>"DummyNumbers",'action' => 'add'),array('data-placement'=>'left','escape' => false)); ?>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Dummy Number Allocation", array("controller"=>"DummyNumberAllocations",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Dummy Number Foil Card", array("controller"=>"DummyNumberAllocations",'action' => 'FoilCard'),array('data-placement'=>'left','escape' => false)); ?>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Dummy No. Examiner List <br/>& Strength Report", array("controller"=>"DummyNumberAllocations",'action' => 'ExaminerList'),array('data-placement'=>'left','escape' => false)); ?>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Dummy Marks Entry", array("controller"=>"DummyMarks",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Dummy Marks Bulk Upload(Excel)", array("controller"=>"DummyMarks",'action' => 'Upload'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Dummy Marks Approval", array("controller"=>"DummyMarks",'action' => 'approval'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Dummy Marks Moderation", array("controller"=>"DummyFinalMarks",'action' => 'moderation'),array('data-placement'=>'left','escape' => false)); ?></li>
		<!--<li class=""><?php if(in_array(1, array_values($check_access_all))){echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."End Semester Exam", array("controller"=>"EndSemesterExams",'action' => 'index'),array('data-placement'=>'left','escape' => false));} ?></li>-->
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."ESE Moderation", array("controller"=>"EndSemesterExams",'action' => 'moderation'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Course Moderation", array("controller"=>"EndSemesterExams",'action' => 'cmSearch'),array('data-placement'=>'left','escape' => false)); ?></li>
		</ul>
	</li>	
	<?php //}
	?>
	
	<?php //$allAccessArray = range(1,211);
        //if(array_intersect($allAccessArray, array_values($check_access_all))){
        ?>
	<li class=""> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-list"></i> <span class="menu-text"> Results </span> <b class="arrow fa fa-angle-down"></b></a> <b class="arrow"></b>
		<ul class="submenu">		
			<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Publish Results", array("controller"=>"EndSemesterExams",'action' => 'batchwise'),array('data-placement'=>'left','escape' => false)); ?></li>
			<!--<li class=""> <?php if(in_array(1, array_values($check_access_all))){echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."View Published Result", array("controller"=>"EndSemesterExams",'action' => 'getReport'),array('data-placement'=>'left','escape' => false));} ?></li>-->
		    <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Results to Department <br/>(Before Revaluation)", array("controller"=>"Students",'action' => 'beforeRevaluation'),array('data-placement'=>'left','escape' => false)); ?></li>
			<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Mark Data COE", array("controller"=>"Students",'action' => 'publishWebsiteMark'),array('data-placement'=>'left','escape' => false)); ?></li>
			<li class=""> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-list fa-lg"></i> <span class="menu-text"> Results to Website </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
		      <ul class="submenu">
		        <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Students Data", array("controller"=>"Students",'action' => 'publishWebsiteStudents'),array('data-placement'=>'left','escape' => false)); ?></li>
		        <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Mark Data", array("controller"=>"Students",'action' => 'resultsToWebsiteMark'),array('data-placement'=>'left','escape' => false)); ?></li>
		        <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Withheld Type", array("controller"=>"Withhelds",'action' => 'publishWithHeldType'),array('data-placement'=>'left','escape' => false)); ?></li>
		        <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Withheld Student Data", array("controller"=>"StudentWithhelds",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		      </ul>
		    </li>
		    <li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Individual Student Moderation", array("controller"=>"Students",'action' => 'individualUser'),array('data-placement'=>'left','escape' => false)); ?></li>
		</ul>
	</li>    
	<?php //}
	?>
	
	<?php //$allAccessArray = range(1,211);
       // if(array_intersect($allAccessArray, array_values($check_access_all))){
       ?>
	<li class=""> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-calendar"></i> <span class="menu-text"> Revaluation </span> <b class="arrow fa fa-angle-down"></b></a> <b class="arrow"></b>
		<ul class="submenu">
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Single Entry", array("controller"=>"Revaluations",'action' => 'revaluation'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Bulk Upload", array("controller"=>"Revaluations",'action' => 'upload'),array('data-placement'=>'left','escape' => false)); ?></li>		
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Reval Dummy No. Report", array("controller"=>"Revaluations",'action' => 'revaluationsDummyNoReport'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Reval Examiner List", array("controller"=>"Revaluations",'action' => 'revaluationsExaminerList'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Revaluation Strength Report", array("controller"=>"Revaluations",'action' => 'revaluationsStrengthReport'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Revaluation Cover Page Report", array("controller"=>"Revaluations",'action' => 'revaluationsCoverPageReport'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Revaluation Foil Card", array("controller"=>"Revaluations",'action' => 'revaluationsFoilCard'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Revaluation Marks", array("controller"=>"RevaluationDummyMarks",'action' => 'marks'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Revaluation Marks Approval/Difference", array("controller"=>"RevaluationDummyMarks",'action' => 'approval'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Revaluation Marks Moderation", array("controller"=>"RevaluationExams",'action' => 'moderation'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Publish Results <br/>(After Revaluation)", array("controller"=>"RevaluationExams",'action' => 'batchwise'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Results to Department <br/>(After Revaluation)", array("controller"=>"Students",'action' => 'afterRevaluation'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Mark Data COE <br/>(After Revaluation)", array("controller"=>"Students",'action' => 'publishWebsiteMarkAfterRevaluation'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Mark - Result to Website<br/>(After Revaluation)", array("controller"=>"Students",'action' => 'resultsToWebsiteMarkAfterRevaluation'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Arrear Fees Report <br/>(After Revaluation)", array("controller"=>"Students",'action' => 'arrearFeesReport'),array('data-placement'=>'left','escape' => false)); ?></li>
		</ul>
	</li> 
	<?php //}
	?>   
	
	<?php //$allAccessArray = range(1,211);
        //if(array_intersect($allAccessArray, array_values($check_access_all))){
        ?>
	<li class=""> <a href="" class="dropdown-toggle"> <i class="menu-icon fa fa-laptop"></i> <span class="menu-text"> Reports </span> <b class="arrow fa fa-angle-down"></b></a> <b class="arrow"></b>
		<ul class="submenu">
		<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Result Analysis", array("controller"=>"EndSemesterExams",'action' => 'report'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Timetable Report ", array("controller"=>"Timetables",'action' => 'timetableReport'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Top Ranking <br/>(After Revaluation)", array("controller"=>"Students",'action' => 'topRanking'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."CGPA Report", array("controller"=>"Students",'action' => 'cgpa'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Total Report", array("controller"=>"Students",'action' => 'total'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Consolidated Mark View", array("controller"=>"Students",'action' => 'consolidatedMarkView'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Degree Certificate Report", array("controller"=>"Students",'action' => 'dcReport'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Common Code Report", array("controller"=>"Timetables",'action' => 'common_code'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Program Wise Report", array("controller"=>"Timetables",'action' => 'programWise'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Board Strength Report </a> <b class="arrow"></b> </li>
		<li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Dummy No. Report </a> <b class="arrow"></b> </li>
		<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."Revaluation Applied Report", array("controller"=>"Revaluations",'action' => 'report'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Revaluation Consolidated Report</a> <b class="arrow"></b> </li>
		<li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Dummy No. Revaluation Report</a> <b class="arrow"></b> </li>
		<!--<li class=""><?php if(in_array(1, array_values($check_access_all))){echo $this->Html->link("Result Analysis"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"EndSemesterExams",'action' => 'batchwise'),array('data-placement'=>'left','escape' => false));} ?></li>-->
		<li class=""> <?php echo $this->Html->link("<i class='menu-icon fa fa-caret-right'></i>"."CAE Report ", array("controller"=>"ContinuousAssessmentExams",'action' => 'batchwiseCaeExport'),array('data-placement'=>'left','escape' => false)); ?></li>
		</ul>
	</li>
	<?php //}
	?>	
    
    <?php //$allAccessArray = range(1,211);
        //if(array_intersect($allAccessArray, array_values($check_access_all))){
        ?>
    <li class=""> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-certificate"></i> <span class="menu-text"> Certificates </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <li class=""><?php echo $this->Html->link("Semester Grade Sheet"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Students",'action' => 'markSheetSearch',1),array('data-placement'=>'left','escape' => false)); ?></li>
        <li class=""><?php echo $this->Html->link("Transfer Certificate"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Students",'action' => 'tcSearch'),array('data-placement'=>'left','escape' => false)); ?></li>
        <li class=""><?php echo $this->Html->link("Provisional"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Students",'action' => 'pdcSearch',4),array('data-placement'=>'left','escape' => false)); ?></li>
        <!--<li class=""> <a href="#"> <i class="menu-icon fa fa-caret-right"></i> Provisional </a> <b class="arrow"></b> </li>-->
		<li class=""><?php echo $this->Html->link("Consolidated Marksheet"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Students",'action' => 'cMSearch',5),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Degree Certificate"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Students",'action' => 'dcSearch'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Migration Certificate"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Students",'action' => 'migrationSearch'),array('data-placement'=>'left','escape' => false)); ?></li>
      </ul>
    </li>
    <?php //}
    ?>
    
    <?php //$allAccessArray = range(1,211);
	//if(array_intersect($allAccessArray, array_values($check_access_all))){?>
    <li class=""><a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-header"></i> <span class="menu-text"> Masters		
	 <!-- #section:basics/sidebar.layout.badge --> 
      <span class="badge badge-primary"></span> 
      
      <!-- /section:basics/sidebar.layout.badge --> 
      </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow fa fa-angle-down"></b>
		<ul class="submenu">
		<li class=""><?php echo $this->Html->link("Month-Year of Exam"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"MonthYears",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Batch"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Batches",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Programs"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Academics",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Specialisation"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Programs",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Sections"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Sections",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Courses Type"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"CourseTypes",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Courses"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Courses",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Faculty"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Faculties",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Courses Mapping"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"CourseMappings",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Courses Student Mapping"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"CourseStudentMappings",'action' => 'mapStudents'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Non Credit Courses"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"NonCreditCourses",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Audit Courses"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"AuditCourses",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Staff"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Users",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("College"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"CollegeReferences",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
        <li class=""><?php echo $this->Html->link("University"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"UniversityReferences",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Signature"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Signatures",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("With-held Type"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Withhelds",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Department"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Departments",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li> 
		<li class=""><?php echo $this->Html->link("Designation"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Designations",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Users Roles"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"UserRoles",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Type Of Certifications"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"TypeOfCertifications",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Change Password"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Users",'action' => 'change_password'),array('data-placement'=>'left','escape' => false)); ?></li>		
		<li class=""><?php echo $this->Html->link("Disciplines"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Disciplines",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Supervisors"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Supervisors",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		<li class=""><?php echo $this->Html->link("Thesis"." <i class='menu-icon fa fa-caret-right'></i><b class='arrow'></b>", array("controller"=>"Thesis",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></li>
		</ul>		
    </li>
    <?php //}
    ?>
  </ul>
  <!-- /.nav-list --> 
  
  <!-- #section:basics/sidebar.layout.minimize -->
  <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse"> <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i> </div>
  
  <!-- /section:basics/sidebar.layout.minimize --> 
  <script type="text/javascript">
	//try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
 </script> 
</div>
<!-- /section:basics/sidebar -->