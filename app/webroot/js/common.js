//var path_relative= "172.156.1.1:8090";
var path_relative= window.location.hostname;
path_relative = 'http://'+path_relative+'/sets2015/';
var url = window.location.pathname; 
urlCae = window.location.pathname;
var asInitVals = new Array();

$(document).ready(function(){
	$($('.submenu li a[href="' + url + '"]')).parent().parent().parent().parent().parent().addClass('open');
	$($('.submenu li a[href="' + url + '"]')).parent().parent().parent().addClass('active');
	
	$($('.submenu li a[href="' + url + '"]')).parent().parent().parent().addClass('open');
	$($('.submenu li a[href="' + url + '"]')).parent().addClass('active');
	
	$($('.studentViewCtp a[href="' + url + '"]')).addClass('current'); // view student tab menu default selection.
	
	$(".markEntry").keypress(function (e) { 
	       //if the letter is not digit then display error and don't type anything
	       if (e.which != 13 && e.which != 65 && e.which != 97 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
	          //display error message
	         alert("Integer Only Allowed");
	                 return false;
	      }
	});
	//initSecond
	/*$('.markEntry').bind('blur', function(){
	       if($(this).val() > 100){
	         alert("Invalid Input");
	       }
	  });*/
	
	breadcrumb1();
	
	$("ul.tabs a").click(function() {
		$("ul.tabs a").removeClass('current');
		$(".pane div").hide();
		$($(this).attr("href")).show();
		$($(this).addClass('current'));
		 return false;
	});	
	
	$('#example').dataTable({
		 "sScrollY": 500,
		"sScrollX": "100%",
		"bLengthChange": false,
		"lengthChange": false,
		"paging": false,
		 iDisplayLength: -1			
	});

	
	var oTable = $('#example').dataTable( {
		"oLanguage": {
		"destroy": "true",
		"searching": "false",
		"sSearch": "Search all columns:"
		}
	} );
	
	$("tfoot input").keyup( function () {
		/* Filter on the column (the index) of this element */
		oTable.fnFilter( this.value, $("tfoot input").index(this) );
	} );
		
	/*
	 * Support functions to provide a little bit of 'user friendlyness' to the textboxes in 
	 * the footer
	 */
	$("tfoot input").each( function (i) {
		asInitVals[i] = this.value;
	} );
	
	$("tfoot input").focus( function () {
		if ( this.className == "search_init" )
		{
			this.className = "";
			this.value = "";
		}
	} );
	
	$("tfoot input").blur( function (i) {
		if ( this.value == "" )
		{
			this.className = "search_init";
			this.value = asInitVals[$("tfoot input").index(this)];
		}
	} );		
	
	$('#example_filter').hide();
	$('#example_length').hide();
	$('#example_paginate').hide();
	$('.thAction').removeClass();	
	
	$("input[id^='select_date']").datepicker({ 	
	     	changeMonth: true,
		changeYear: true,
		autoclose: true,
		dateFormat: 'dd-mm-yy',
		onSelect: function(dateText, inst){ 
			//$(this).attr('id').val(dateText);
		}
    	});
	 $("[id^='userPrivil']").click(function(){
	 	//var origsrc = $(this).attr('src');
		//var src = '';
		//if (origsrc == 'plus.gif') src = 'minus.gif';
		//if (origsrc == 'minus.gif') src = 'plus.gif';        
		//$(this).attr('src', src);
	
		var activeClass = $(this).attr("id");	 
		$('.'+activeClass).toggle();
        });

	 $("input.chkExamAttendance").click(function(){  
	     var totChecked = "";
	     var totUnChecked = "";
	     $("input.chkExamAttendance").each(function (index){
	           if($(this).is(':checked') == true){
	             totChecked++;
	           }else{
	             totUnChecked++;
	           }
	       });
	     $('#absentTotal').html(totUnChecked);
	     $('#presentTotal').html(totChecked);
	   });
	 
	$("#datepicker_img1 img").click(function(){
         $("#datepicker1").datepicker(
        {
        	changeMonth: true,
            changeYear: true,
               dateFormat: 'yy-mm-dd',
               onSelect: function(dateText, inst){
                     $('#select_date1').val(dateText);
                     $("#datepicker1").datepicker("destroy");
              }
         }
         );
    });
	
	$(".examDate").change(function(){
	    $("#eDCommonCode").html("<img src='"+path_relative+"img/loading.gif' alt='Common Code Loading...'/>");  
	    $.ajax({
	      url: path_relative+'DummyNumbers/eDCommonCode/'+$('#exam_date :selected').text(),
	      type: 'POST',
	      dataType: 'HTML',
	      contentType: 'application/html; charset=utf-8',
	      success: function(data, txtStatus) { 
	        $("#eDCommonCode").html(data);        
	      },
	      error: function(e) {
	        console.log(e);
	       }
	    });
	  });
	
	$(".js-manage-course").click(function(){
		$( "#js-load-forms" ).dialog( "close" );
		$this =$(this);
		$url = $this.attr("href");
		$("#js-manage-student").dialog({
			autoOpen: true,
			width:'80%',
			position: ['center',100],
			
			open:function(event){
					$.ajax({
						url: $url,
						success: function(data) {
								$("#js-manage-student").html(data);	
								$(".js-form").submit(jsFormSubmitRedirect);
								return false;
							}				
					});
				},
			close: function( event, ui ) {
					$("#js-load-forms").html();	
				 }
		 });
		 return false;
	});
	
	$(".js-popup").click(function(){
		$this =$(this);
		$url = $this.attr("href");	
		$("#js-load-forms").dialog({
			autoOpen: true,
			width:'80%',
			position: ['center',100],
			
			open:function(event){
					$.ajax({
						url: $url,
						success: function(data) {
								$("#js-load-forms").html(data);	
								$(".js-form").submit(jsFormSubmitRedirect);
								return false;
							}				
					});
				},
			close: function( event, ui ) {
					$("#js-load-forms").html();	
				 }
		 });
		 return false;
	});
	
	$(".js-data-set").click(function(){	
		var batchId = $('#batch_id').val();
		var programId = $(this).attr('alt');
		
		$("#listCourseMapping").html("<img src='/sets2015/img/loading.gif' alt='Course Mapping Form Loading...'/>");	
		$.ajax({
			url: path_relative+'CourseMappings/findNoOfSemesters/'+batchId+'/'+programId+'/',
			type: 'POST',
			dataType: 'HTML',
			contentType: 'application/html; charset=utf-8',
			success: function(data, txtStatus) {
				$("#listCourseMapping").html(data);				
			},
			error: function(e) {
				console.log(e);
			 }
		});
	});
	
	$(".js-data-set1").change(function(){
		programId = $(this).val();
		//batchId = $("#batch_id").val();
		//alert(path_relative+'CourseMappings/findNoOfSemestersTest/'+programId);
		$.ajax({
			url: path_relative+'CourseMappings/findNoOfSemestersTest/'+programId,
			type: 'POST',
		    dataType: 'HTML',
		    contentType: 'application/html; charset=utf-8',
			success: function(data, txtStatus) {
				$("#semesters").html(data);
			},
		 	error: function(e) {
			    console.log(e);
			 }
		});
	});

	//Enter next tab function - START
    $(".dummy").bind("keypress", function(e) { 
           if (e.keyCode == 13) { 
               var inps = $(".dummy");
               for (var x = 0; x < inps.length; x++) {
                   if (inps[x] == this) {
                       while ((inps[x]).id == (inps[x + 1]).id) {
                       x++;
                       }
                       if ((x + 1) < inps.length) $(inps[x + 1]).focus();
                   }
               }   e.preventDefault();
           }
       });
    //Enter next tab function - END
    
	$(".js-data-set-edit").change(function(){
		programId = $(this).val();
		//alert(programId);
		//batchId = $("#batch_id").val();
		//alert(path_relative+'CourseMappings/editNoOfSemesters/'+programId);
		$.ajax({
			url: path_relative+'CourseMappings/editNoOfSemesters/'+programId,
			type: 'POST',
		    dataType: 'HTML',
		    contentType: 'application/html; charset=utf-8',
			success: function(data, txtStatus) {
				//alert(data);
				$("#semesters").html(data);
			},
		 	error: function(e) {
			    console.log(e);
			 }
		});
	});
	
	$(".js-late-joiner").change(function(){
		batchId = $("#batch_id").val();
		programId = $("#program_id").val();
		monthYearJoined = $("#month_year_joined").val();
		studentId = $("#student_id").val();
		monthYearId = $(this).val();
		if (monthYearId) {
			$("#courseResult").html("<img src='/sets2015/img/loading.gif' alt='Mapping courses and students. Please wait...'/>");
			$.ajax({
				url: path_relative+'Students/getCoursesForASemester/'+batchId+'/'+programId+'/'+monthYearId+'/'+studentId+'/'+monthYearJoined,
				type: 'POST',
			    dataType: 'HTML',
				success: function(data, txtStatus) {
					//alert(data);
					$("#courseResult").html(data);
				}
			});
		}else {
			$("#courseResult").html("");
		}
		
	});
	
	$(".js-batch-transfer").change(function(){
		batchId = $("#batch_id").val();
		programId = $("#program_id").val();
		semesterJoined = $("#semester_joined").val();
		studentId = $("#student_id").val();
		semesterId = $(this).val();
		$("#courseResult").html("<img src='/sets2015/img/loading.gif' alt='Mapping courses and students. Please wait...'/>");
		$.ajax({
			url: path_relative+'Students/getCoursesForASemester/'+batchId+'/'+programId+'/'+semesterId+'/'+studentId+'/'+semesterJoined,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#courseResult").html(data);
				//lateJoinerCourse();
			}
		});
	});
	
	$(".js-csm-list").click(function(){
		batchId=$("#CSMBatchId").val();
		academicId=$("#CSMAcademicId").val();
		programId=$("#StudentProgramId").val();
		semesterId=$(".js-semester").val();
		//alert(path_relative+'CourseStudentMappings/listStudents/'+batchId+'/'+academicId+'/'+programId+'/'+semesterId);
		$("#displayCSMData").html("<img src='/sets2015/img/loading.gif' alt='Mapping courses and students. Please wait...'/>");
		$.ajax({
			url: path_relative+'CourseStudentMappings/listStudents/'+batchId+'/'+academicId+'/'+programId+'/'+semesterId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#displayCSMData").html(data);
			}
		});
	});
	
		$(".js-academic, .js-cae-academic, .student-academic").change(function(){
			clas = $(this).attr('class').split(' ');
			//alert(clas);
			if(clas == 'student-academic') {
				academicId =$(".student-academic").val();
			}else if(clas == 'js-academic') {
				academicId=$(".js-academic").val();
			} else if (clas == 'js-cae-batch') {
				academicId = $(".js-cae-academic").val();
			}
			$.ajax({
				url: path_relative+'programs/findProgramByAcademic/'+academicId,
				type: 'POST',
			    dataType: 'HTML',
				//url: '<?php echo Router::url(array('controller'=>'batches','action'=>'findBatchByBatchMode'));?>',
				success: function(data, txtStatus) {					
					$(".program").html(data);					
					if (clas == 'js-cae-batch') {						
						$("#programs_cae").html(data);
					}else if (clas == 'js-batch') {
						$("#program").html(data);
					}
					if (clas == 'student-academic') { 
						$('.program label').addClass('col-sm-5 control-label no-padding-right');
						$('.program label').html($('.program label').html()+"<span class='ash'>*</span>");
						$('.program select').attr("required","required");						
					}					
					initSecond();
				}				
			});
		});
	
	$(".js-month-year").change(function() {
		batchId=$(".js-batch").val();
		programId=$(".js-program").val();
		academicId=$(".js-academic").val();
		
		//urlCae = window.location.pathname;
		//alert(urlCae);
		strExplode = urlCae.split('/');
		currentModel = strExplode[2];
		currentMethod = strExplode[3];
		//alert(currentModel+' '+currentMethod);
		if (currentMethod == "" || currentMethod == undefined) {
			//Code to retrieve courses
			//alert(path_relative+'ContinuousAssessmentExams/findCoursesByProgram/'+programId+'/'+batchId+'/'+academicId);
			$.ajax({
				url: path_relative+'ContinuousAssessmentExams/findCoursesByProgram/'+programId+'/'+batchId+'/'+academicId,
				type: 'POST',
			    dataType: 'HTML',
				success: function(data, txtStatus) {
					//alert(data);
					//$(".program").html();
					$(".course").html(data);
					//urlCae = window.location.pathname;
					strExplode = urlCae.split('/');
					currentMethod = strExplode[2];
					//alert(currentModel);
					if(currentMethod == "ContinuousAssessmentExams") {
						$("#course_mapping_id").removeClass("js-course");
						$("#course_mapping_id").addClass("js-course-cae");
						//$(".js-month-year").hide();
					}
					initCAE();
				}
				});
		}
	});
		
	function initSecond() {
		//alert('pgm');
		$(".js-program").change(function(){
			clas = $(this).attr('class').split(' ');
			//alert(clas);
			
			//$(".js-programs").html("");
				programId=$(this).val();
				batchId=$(".js-batch").val();
				academicId=$(".js-academic").val();

				strExplode = urlCae.split('/');
				currentModel = strExplode[2];
				currentMethod = strExplode[3];
				//alert(currentModel+" "+currentMethod);
				if((currentModel == "ContinuousAssessmentExams" && currentMethod == "calculateCAEMarks") || (currentModel == "ExamAttendances") || (currentModel == "DummyNumberAllocations" && currentMethod == "add")) {
					//alert('if');
					/*$("#course_mapping_id").removeClass("js-course");
					$("#course_mapping_id").addClass("js-course-cae");
					$(".js-month-year").hide();*/
					$(".monthyear").html("<img src='/sets2015/img/loading.gif' alt='Loading...'/>");
					findMonthYearByBatchAndProgram(batchId, programId);
				}
				//Previously it was current Model and Method Caes
				else if(currentModel == "ContinuousAssessmentExams" && currentMethod == "caeAssignment") {
					$.ajax({
						url: path_relative+'ContinuousAssessmentExams/findSemestersByProgram/'+programId,
						type: 'POST',
					    dataType: 'HTML',
						success: function(data, txtStatus) {
							//alert(data);
							//$(".program").html();
							$(".semester").html(data);
							initSemester();
						}
					});
					template = $("#CaeAction").val();
					//alert(path_relative+'Caes/findCoursesByProgram/'+programId+'/'+batchId+'/'+0+'/'+template);
			          $.ajax({
			            url: path_relative+'Caes/findCoursesByProgram/'+programId+'/'+batchId+'/'+0+'/'+template,
						type: 'POST',
					    dataType: 'HTML',
						success: function(data, txtStatus) {
							$(".course").html(data);
							strExplode = urlCae.split('/');
							currentModel = strExplode[2];
							//if(currentModel == "ContinuousAssessmentExams") {
							if(currentMethod == "caeAssignment") {
								$("#course_mapping_id").removeClass("js-course");
								$("#course_mapping_id").addClass("js-course-cae");
								//$(".js-month-year").hide();
							}
							initCAE();
						}
					});
				}
				else if(currentModel == "ContinuousAssessmentExams" && (currentMethod == undefined || currentMethod == "practical" || currentMethod == "theory" || currentMethod == "project")) {
					/*$("#course_mapping_id").removeClass("js-course");
					$("#course_mapping_id").addClass("js-course-cae");
					$(".js-month-year").hide();*/
					//alert(path_relative+'ContinuousAssessmentExams/findMonthYearByProgram/'+programId+'/'+batchId+'/'+academicId);
					$(".monthyear").html("<img src='/sets2015/img/loading.gif' alt='Loading...'/>");
					findMonthYearByBatchAndProgram(batchId, programId);
				}
				else if(currentModel == "CaePts" && currentMethod == "add") {
					$(".monthyear").html("<img src='/sets2015/img/loading.gif' alt='Loading...'/>");
					findMonthYearByBatchAndProgram(batchId, programId);
					findSemesterByProgram(programId);
					template = $("#CaePtAction").val();
			        findCoursesByTemplate(programId, batchId, template);
				}
				else if(currentModel == "ContinuousAssessmentExams" && currentMethod == "createCaeDownloadTemplate") {
					$(".monthyear").html("<img src='/sets2015/img/loading.gif' alt='Loading...'/>");
					findMonthYearByBatchAndProgram(batchId, programId);
				}
				else if(currentModel == "CaePracticals" && currentMethod == "practical") {
					$(".monthyear").html("<img src='/sets2015/img/loading.gif' alt='Loading...'/>");
					findMonthYearByBatchAndProgram(batchId, programId);
				}
				else if(currentModel == "EsePracticals" && currentMethod == "practical") {
					findMonthYearByBatchAndProgram(batchId, programId);
				}
				else if(currentModel == "CaePracticals" && currentMethod == "practicalDownloadTemplate") {
					findMonthYearByBatchAndProgram(batchId, programId);
				}
				else if(currentModel == "EsePracticals" && currentMethod == "practicalDownloadTemplate") {
					findMonthYearByBatchAndProgram(batchId, programId);
				}
				else if(currentModel == "EsePracticals" && currentMethod == "calculate") {
					findMonthYearByBatchAndProgram(batchId, programId);
				}
				else if(currentModel == "EsePracticals" && currentMethod == "report") {
					findMonthYearByBatchAndProgram(batchId, programId);
				}
				else if(currentModel == "EsePracticals" && currentMethod == "moderate") {
					findMonthYearByBatchAndProgram(batchId, programId);
				}
				else if(currentModel == "CaeProjects" && (currentMethod == "import" || currentMethod == "calculateCae")) {
					findMonthYearByBatchAndProgram(batchId, programId);
				}
				else if(currentModel == "CaePracticals" && currentMethod == "addCaePractical") {
					findSemesterByProgram(programId);
					template = $("#CaePracticalAction").val();
			        findCoursesByTemplate(programId, batchId, template);
				}
				else if(currentModel == "CaeProjects" && currentMethod == "add") {
					findSemesterByProgram(programId);
					template = $("#CaeProjectAction").val();
			        findCoursesByTemplate(programId, batchId, template);
					$("#course_mapping_id").removeClass("js-course");
					$("#course_mapping_id").addClass("js-course-project");
				}
				else if (currentModel == "ExamAttendances" && currentMethod == "attendance") {
					month_year_id = $(".js-month-year").val();
					template = $("#ExamAttendanceCtype").val();
					$.ajax({
						url: path_relative+'ExamAttendances/findExamDates/'+batchId+'/'+programId+'/'+template+'/'+month_year_id,
						type: 'POST',
					    dataType: 'HTML',
						success: function(data, txtStatus) {
							$(".examdates").html(data);
							initExamCourse();
						}
					});
					
				}
				else if (currentModel == "DummyMarks" && currentMethod == "marksEntry") {
					month_year_id = $(".js-month-year").val();
					 
					template = $("#ExamAttendanceCtype").val();
					//alert(template);
					//alert(path_relative+'ExamAttendances/findCourses/'+batchId+'/'+programId+'/'+template+'/'+month_year_id);
					$.ajax({
						url: path_relative+'ExamAttendances/findExamDates/'+batchId+'/'+programId+'/'+template+'/'+month_year_id,
						type: 'POST',
					    dataType: 'HTML',
						success: function(data, txtStatus) {
							$(".examdates").html(data);
							initExamCourse();
						}
					});
					
				}
				else if(currentModel == "CourseStudentMappings" && currentMethod == "mapStudents") {
					$.ajax({
						url: path_relative+'ContinuousAssessmentExams/findSemestersByProgram/'+programId,
						type: 'POST',
					    dataType: 'HTML',
						success: function(data, txtStatus) {
							//alert(data);
							//$(".program").html();
							$(".semester").html(data);
							initSemester();
						}
					});
				} 
				else if(currentModel == "EndSemesterExams" && currentMethod == "pgmwisePublishSearch") {
					$(".monthyear").html("<img src='/sets2015/img/loading.gif' alt='Loading...'/>");
					template = '-';
					//findMonthYearByBatchAndProgram(batchId, programId);
			        findCoursesByTemplate(programId, batchId, template);
					//initCourse();
				}
			});
	}
	
	function findMonthYearByBatchAndProgram(batchId, programId,currentModel) {
		//alert(path_relative+'ContinuousAssessmentExams/listMonthYearByBatchAndProgram/'+batchId+'/'+programId);
		if(batchId && programId) {
			$.ajax({
				url: path_relative+'ContinuousAssessmentExams/listMonthYearByBatchAndProgram/'+batchId+'/'+programId+'/'+currentModel,
				type: 'POST',
			    dataType: 'HTML',
				success: function(data, txtStatus) {
					$(".monthyear").html(data);
					//initCourse();
				}
			});
		}
		else {
			$(".monthyear").html("");
		}
	}
	
	function findSemesterByProgram(programId) {
		//alert(path_relative+'ContinuousAssessmentExams/findSemestersByProgram/'+programId);
		$.ajax({
			url: path_relative+'ContinuousAssessmentExams/findSemestersByProgram/'+programId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$(".semester").html(data);
				initSemester();
			}
		});
	}
	
	function findCoursesByTemplate(programId, batchId, template) {
		temp = 0;
		//alert(template);
		$.ajax({
            url: path_relative+'Caes/findCoursesByProgram/'+programId+'/'+batchId+'/'+temp+'/'+template,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$(".course").html(data);
				strExplode = urlCae.split('/');
				currentModel = strExplode[2];
				if(currentMethod == "addCaePractical") {
					$("#course_mapping_id").removeClass("js-course");
					$("#course_mapping_id").addClass("js-course-cae");
				}
				initCAE();
			}
		});
	}
	
	function initCourse() {
		$(".js-month-year").change(function() {
			batchId=$(".js-batch").val();
			programId=$(".js-program").val();
			academicId=$(".js-academic").val();
			
			//urlCae = window.location.pathname;
			//alert(urlCae);
			strExplode = urlCae.split('/');
			currentModel = strExplode[2];
			currentMethod = strExplode[3];
			//alert(currentModel);
			if (currentMethod == "" || currentMethod == undefined) {
				//Code to retrieve courses
				//alert(path_relative+'ContinuousAssessmentExams/findCoursesByProgram/'+programId+'/'+batchId+'/'+academicId);
				$.ajax({
					url: path_relative+'ContinuousAssessmentExams/findCoursesByProgram/'+programId+'/'+batchId+'/'+academicId,
					type: 'POST',
				    dataType: 'HTML',
					success: function(data, txtStatus) {
						//alert(data);
						//$(".program").html();
						$(".course").html(data);
						//urlCae = window.location.pathname;
						strExplode = urlCae.split('/');
						currentMethod = strExplode[2];
						//alert(currentModel);
						if(currentMethod == "ContinuousAssessmentExams") {
							$("#course_mapping_id").removeClass("js-course");
							$("#course_mapping_id").addClass("js-course-cae");
							//$(".js-month-year").hide();
						}
						initCAE();
					}
					});
			}
			//else if(currentMethod == "calculateCAEMarks"){
				//Code to check assessment and attendance status
				//alert($(".js-month-year").val());
				/*monthYearId = $(".js-month-year").val();
				$.ajax({
					url: path_relative+'ContinuousAssessmentExams/getCaeStatus/'+programId+'/'+batchId+'/'+academicId+'/'+monthYearId,
					type: 'POST',
				    dataType: 'HTML',
					success: function(data, txtStatus) {
						//alert(data);
						//$(".course").html(data);
					}
				});*/
			//}
			else if(currentMethod == "individualCaeMarks"){
				//alert("third");
				//Code to check assessment and attendance status
				monthYearId = $(".js-month-year").val();
				//alert(path_relative+'ContinuousAssessmentExams/individualCaeMarks/'+programId+'/'+batchId+'/'+academicId+'/'+monthYearId);
				$.ajax({
					url: path_relative+'ContinuousAssessmentExams/individualCaeMarks/'+programId+'/'+batchId+'/'+academicId+'/'+monthYearId,
					type: 'POST',
				    dataType: 'HTML',
					success: function(data, txtStatus) {
						//alert(data);
						//$(".course").html(data);
					}
					});
			}
			else if(currentMethod == "approveCae"){
				//Code to check assessment and attendance status
				monthYearId = $(".js-month-year").val();
				//alert(path_relative+'ContinuousAssessmentExams/approveCaeExam/'+programId+'/'+batchId+'/'+academicId+'/'+monthYearId);
				$.ajax({
					url: path_relative+'ContinuousAssessmentExams/approveCaeExam/'+programId+'/'+batchId+'/'+academicId+'/'+monthYearId,
					type: 'POST',
				    dataType: 'HTML',
					success: function(data, txtStatus) {
						//alert(data);
						$(".course").html(data);
					}
				});
			}
			
		})
	}
	
	function initExamCourse() {
		$(".js-exam-date").change(function(){
			timetable_id = $(this).val();
			month_year_id = $(".js-month-year").val();
			//alert(path_relative+'ExamAttendances/findCoursesByMonthYearAndExamDate/'+timetable_id+'/'+month_year_id);
			$.ajax({
				url: path_relative+'ExamAttendances/findCoursesByMonthYearAndExamDate/'+timetable_id+'/'+month_year_id,
				type: 'POST',
			    dataType: 'HTML',
				success: function(data, txtStatus) {
					$(".courses").html(data);
					//initCourse();
				}
			});
		});
	}
function initSemester() {
		$(".js-semester").change(function(){
			
			var batchId='0';
			var programId='0';
			var semesterId='0';
			//var courseTypeId='0';
			
			if($(".js-batch").val()){
				batchId = $(".js-batch").val();
			}
			if($(".js-program").val()){
				programId = $(".js-program").val();
			}
			if($(".js-semester").val()){
				semesterId = $(".js-semester").val();
			}
			if($(".js-course-type").val()){
				from = $(".js-course-type").val();
			}

			strExplode = urlCae.split('/');
			currentController = strExplode[2];
			currentMethod = strExplode[3];
			//alert(currentModel+' '+currentMethod);
			//semesterId=$(".js-semester").val();
			//batchId=$(".js-batch").val();
			//programId=$(".js-program").val();
			if (currentController == "CaePracticals" && currentMethod == "addCaePractical") {
				var courseType = $("#CaePracticalAction").val();
			}
			else if (currentController == "CaeProjects" && currentMethod == "add") {
				var courseType = $("#CaeProjectAction").val();
			}
			else if (currentController == "CaePracticals" && currentMethod == "addCaePractical") {
				var courseType = $("#CaeAction").val();
			}
			else if (currentController == "CaePts" && currentMethod == "add") {
				var courseType = $("#CaePtAction").val();
			}
			else if (currentController == "ContinuousAssessmentExams" && currentMethod == "caeAssignment") {
				var courseType = $("#CaeAction").val();
				var batchId = $("#CaeBatchId").val();
				var programId = $("#StudentProgramId").val();
				var semester = $(this).val();
				//alert(batchId+' '+programId+' '+semester);
				findMonthYearForSemester(batchId, programId, semester);
			}

			//alert(path_relative+'Caes/findCoursesByProgram/'+programId+'/'+batchId+'/'+semesterId+'/'+courseType);
			$.ajax({
			url: path_relative+'Caes/findCoursesByProgram/'+programId+'/'+batchId+'/'+semesterId+'/'+courseType,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$(".course").html(data);
				strExplode = urlCae.split('/');
				currentController = strExplode[2];
				if(currentMethod == "caeAssignment") {
					$("#course_mapping_id").removeClass("js-course");
					$("#course_mapping_id").addClass("js-course-cae");

				}
				else if(currentMethod == "addCaePractical") {
					$("#course_mapping_id").removeClass("js-course");
					$("#course_mapping_id").addClass("js-course-cae");
				}
				else if(currentMethod == "add" && currentController== "CaeProjects") {
					$("#course_mapping_id").removeClass("js-course");
					$("#course_mapping_id").addClass("js-course-project");
				}
				initCAE();
			}
			});
		});
	}

	function initCAE() {
		$(".js-course").change(function() {
			cmId = $(".js-course").val();
			//alert(path_relative+'caes/findNoOfCAEs/'+cmId);
				$.ajax({
					url: path_relative+'caes/findNoOfCAEs/'+cmId,
					type: 'POST',
				    dataType: 'HTML',
					success: function(data, txtStatus) {
						//alert(data);
						//$(".program").html();
						$("#noOfCAEs").html(data);
						//initCourse();
					}
				});
		});
		$(".js-course-cae").change(function() {

			cmId = $(this).val();
			batchId=$(".js-batch").val();
			programId=$(".js-program").val();
			//var courseTypeId = $("#CaeCourseTypeId").val();
			//courseType = $("input[name='data[caetype]']:checked").val();
			//alert(courseTypeId);
			//var courseTypeIdSplit = courseTypeId.split('-');
			//alert(courseTypeIdSplit);
			//alert(path_relative+'ContinuousAssessmentExams/retrieveCourseTypeFromCourseWithCmId/'+cmId);
			actionTemplate = getCourseType(cmId);
			//alert(actionTemplate);
			actionTemplate = actionTemplate.split("-");
			//fnTemplate(actionTemplate[0], actionTemplate[1]);
			//alert(courseType);
			//alert(path_relative+'ContinuousAssessmentExams/findNoOfCaes/'+cmId+'/'+batchId+'/'+programId+'/'+courseTypeId);
			//if(jQuery.inArray(courseTypeIdSplit, myarray) !== -1)
			
			strExplode = urlCae.split('/');
			currentController = strExplode[2];
			currentMethod = strExplode[3];
			//alert(currentModel+' '+currentMethod);
			
			//findCaes(cmId, actionTemplate[1], currentController);
			//disableAndEnableButton(cmId, actionTemplate[1]);
			
			//findMonthYearByCmId(cmId, actionTemplate[1]);
			//alert(path_relative+'ContinuousAssessmentExams/getCourseMaxMarksByCmId/'+cmId);
			if($("#CaeAction").val() == "project") {
				$.ajax({
					url: path_relative+'ContinuousAssessmentExams/getCourseMaxMarksByCmId/'+cmId,
					type: 'POST',
				    dataType: 'HTML',
					success: function(data, txtStatus) {
						//alert(data);
						$(".js-project-max-marks").val(data);
					}	
				});
			}
		});
		$(".js-course-project").change(function(){
			cm_id = $("#course_mapping_id").val();
		});
		
	}
	
	function findMonthYearByCmId(cmId, actionTemplate) {
		//alert(path_relative+'ContinuousAssessmentExams/findMonthYearByCmId/'+cmId+'/'+actionTemplate+'/1');
		$.ajax({
			url: path_relative+'ContinuousAssessmentExams/findMonthYearByCmId/'+cmId+'/'+actionTemplate+'/1',
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#monthyears").html(data);
			}				
		});
	}
	
	function findMonthYearForSemester(batchId, programId, semester) {
		//alert(path_relative+'/ContinuousAssessmentExams/findMonthYearBySemester/'+batchId+'/'+programId+'/'+semester);
		$.ajax({
			//url: path_relative+'ContinuousAssessmentExams/findNoOfCaes/'+cmId+'/'+batchId+'/'+programId+'/'+courseTypeId,
			url: path_relative+'/ContinuousAssessmentExams/findMonthYearBySemester/'+batchId+'/'+programId+'/'+semester,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#monthyears").html(data);
				//$("#monthyears").hide();
			}
		});
	}
	
	function findCaes(cmId, actionTemplate, currentController) {
		//alert(path_relative+currentController+'/findNoOfCaes/'+cmId+'/'+actionTemplate);
		$.ajax({
			//url: path_relative+'ContinuousAssessmentExams/findNoOfCaes/'+cmId+'/'+batchId+'/'+programId+'/'+courseTypeId,
			url: path_relative+'/'+currentController+'/findNoOfCaes/'+cmId+'/'+actionTemplate,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#noOfCAEs").html(data);
			}
		});
	}
	
	function disableAndEnableButton(cmId, actionTemplate) {
		//alert(path_relative+'ContinuousAssessmentExams/displayButton/'+cmId+'/'+actionTemplate);
		$.ajax({
			url: path_relative+'ContinuousAssessmentExams/displayButton/'+cmId+'/'+actionTemplate,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				if (data == 1) {
					$("#submit").hide();
				}
				else {
					$("#submit").show();
				}
			}
		});
	}
	
	function getCourseType(cmId) {
		var action = $("#CaeAction").val();
		//alert(action);
		var data;
		var template;
		$.ajax({
			url: path_relative+'ContinuousAssessmentExams/retrieveCourseTypeFromCourseWithCmId/'+cmId+'/'+true,
			type: 'POST',
		    dataType: 'HTML',
		    async:false,
			success: function(data, txtStatus) {
			//return data;
				switch (data) {
					case "1":
						//alert('1');
						template = "theoryTemplate";
						break;
					case "2":
						//alert('2');
						template = "practicalTemplate";
						break;
					case "3":
			            //alert('3');
			            if (action == "practical") {
			              template = "practicalTemplate";
			            }
			            else if (action == "index" || action == "theory") {
			              template = "theoryTemplate";
			            }
			            break;
					case "4":
						//alert('4');
						template = "projectTemplate";
						break;
				}
			}
		});
		//alert(action+"-"+template);
		return action+"-"+template;
	}
	//}
	
	function fnTemplate(action, template) {
		//alert(action+" "+template);
		//alert(path_relative+'ContinuousAssessmentExams/'+template+'/'+template);
		$.ajax({
			url: path_relative+'ContinuousAssessmentExams/'+template+'/'+template,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				//data = stripCaeData(data);
				$("#displayOptions").html(data);
			}
		});
			//alert(data);
			//data = stripCaeData(data);
			//$("#displayOptions").html(data);
	}
	$(".js-studenttype").change(function() {
		if ($(this).val() == 2) {
		  $(".js-student-university").attr("disabled", true); 
		  $(".js-student-semester").attr("disabled", false);
		}else if ($(this).val() == 3) {
	      $(".js-student-university").attr("disabled", false); 
	      $(".js-student-semester").attr("disabled", false);
	      $("#priorBatch").hide();
	    }else if ($(this).val() == 4) {
	      $(".js-student-university").attr("disabled", true); 
	      $(".js-student-semester").attr("disabled", false);
	    }else if ($(this).val() == 5) {
		      $(".js-student-university").attr("disabled", false); 
		      $(".js-student-semester").attr("disabled", false);
		      $("#oldRegNo").show();
		      $("#priorBatch").show();
		}else{
	      $(".js-student-university").attr("disabled", true); 
	      $(".js-student-semester").attr("disabled", false);
	      $("#oldRegNo").hide();
	      $("#priorBatch").hide();
	    }
	  });
	
	$(".js-student-university").change(function() {
	    if ($(this).val() == 1) {
	      $("#oldRegNo").show();
	      $("#priorBatch").hide();
	      $("#StudentPriorBatch").hide();
	    }else {$("#oldRegNo").hide();}
	  });
	$("#displayOptions input:radio, #noOfCAEs input:radio").click(function() {
		$("#choice1, #choice2, #choice3").hide();
		$("#choice"+$(this).val()).show();
	});
	

	$(".js-cae-get").click(function() {
		if($('#StudentBatchId').val() == ''){
			alert("Select Batch");
			return false;
		}
		if($('#StudentAcademicId').val() == ''){
			alert("Select Program");
			return false;
		}
		if($('#StudentProgramId').val() == ''){
			alert("Select Specialisation");
			return false;
		}
		if($('#month_year_id').val() == ''){
			alert("Select Month Year");
			return false;
		}

		$("#displayButtons").html("<img src='../img/loading.gif' alt='Calculating internal marks...'/>");
		programId=$(".js-program").val();
		batchId=$(".js-batch").val();
		academicId=$(".js-academic").val();
		monthYearId=$(".js-month-year").val();
		//alert(path_relative+'ContinuousAssessmentExams/getCaeStatus/'+programId+'/'+batchId+'/'+academicId+'/'+monthYearId);
		$.ajax({
			url: path_relative+'ContinuousAssessmentExams/getCaeStatus/'+programId+'/'+batchId+'/'+academicId+'/'+monthYearId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#displayButtons").html(data);
			}
		});
	});
	
	$(".js-cae-project").click(function() {
		if($('#StudentBatchId').val() == ''){
			alert("Select Batch");
			return false;
		}
		if($('#StudentAcademicId').val() == ''){
			alert("Select Program");
			return false;
		}
		if($('#StudentProgramId').val() == ''){
			alert("Select Specialisation");
			return false;
		}
		if($('#month_year_id').val() == ''){
			alert("Select Month Year");
			return false;
		}

		$("#displayButtons").html("<img src='../img/loading.gif' alt='Calculating project internal marks...'/>");
		programId=$(".js-program").val();
		batchId=$(".js-batch").val();
		academicId=$(".js-academic").val();
		monthYearId=$(".js-month-year").val();
		//alert(path_relative+'ContinuousAssessmentExams/getCaeStatus/'+programId+'/'+batchId+'/'+academicId+'/'+monthYearId);
		$.ajax({
			url: path_relative+'CaeProjects/getProjectData/'+programId+'/'+batchId+'/'+academicId+'/'+monthYearId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#displayButtons").html(data);
			}
		});
	});
	
	$(".js-ese").click(function(){
		if($("#monthYears").val() == "") {
			alert("Choose Month Year");
			return false;
		}
		else {
			monthYear = $("#monthYears").val();
		}
		if($("#ESEBatchId").val() == "") {
			alert("Choose Batch");
			return false;
		}
		else {
			batch = $("#ESEBatchId").val();
		}
		if($("#ESEAcademicId").val() == "") {
			academicId = 0;
		}
		else {
			academicId = $("#ESEAcademicId").val();
		}
		if($("#StudentProgramId").val() == "") {
			programId = 0;
		}
		else {
			programId = $("#StudentProgramId").val();
		}
		//alert(path_relative+'EndSemesterExams/getDummyFinalMarks/'+monthYear+'/'+batch+'/'+academicId+'/'+programId);
		$.ajax({
			url: path_relative+'EndSemesterExams/getDummyFinalMarks/'+monthYear+'/'+batch+'/'+academicId+'/'+programId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#eseResult").html(data);
			}
		});
	});
	
	/*$(".eseValue").each(function() {
	    alert($(this).val());
	});*/
	
	$.each( $(".eseValue input[type=\"text\"]"), function(index, ele){
		   alert( ele.val());
		 });
	
	$(".js-dummy-mismatch").click(function(){
		varAjax=1;
		if ($(".js-month-year").val() == "") {
			alert("Choose month year");
			return false;
		}
		else {
			monthYear = $(".js-month-year").val();
		}
		if ($("#DummyApprovalDummyNumber").val() == "") {
			alert("Enter dummy number");
			return false;
		}
		else {
			startRange = $("#DummyApprovalDummyNumber").val();
		}
		$("#dummyResult").html("<img src='../img/loading.gif' alt='Checking...'/>");
		//alert(path_relative+'DummyMarks/getDummyDiffMarks/'+monthYear+'/'+startRange+'/'+varAjax);
		$.ajax({
			url: path_relative+'DummyMarks/getDummyDiffMarks/'+monthYear+'/'+startRange+'/'+varAjax,
			type: 'POST',
		    dataType: 'HTML',
		 	success : function(data) {
		 		$("#dummyResult").html(data);
		    },
		    error : function(data) {
		    	$("#dummyResult").html("There is no data mismatch and data is already synchronized");
		    	alert($.parseJSON(data.responseText).val);
		    },
		});
	});
	
	$(".js-dummy-individual").click(function(){
		varAjax=1;
		dummyNumber = $("#DummyNumber").val();
		//alert(path_relative+'DummyFinalMarks/getIndividualMark/'+dummyNumber+'/'+varAjax);
		$.ajax({
			url: path_relative+'DummyFinalMarks/getIndividualMark/'+dummyNumber+'/'+varAjax,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#dummyResult").html(data);
			}
		});
	});
	
	$(".js-dummy-display").click(function(){
		monthYear = $("#DummyFinalMarkMonthYearId").val();
		startRange = $("#DummyFinalMarkDummyNumber").val();
		from = $("#DummyFinalMarkFrom").val();
		to = $("#DummyFinalMarkTo").val();
		
		if(monthYear == ''){alert("Select Exam Month Year"); $("#DummyFinalMarkMonthYearId").focus(); return false;}
		if(startRange == ''){ alert("Select Dummy start range"); $("#DummyFinalMarkDummyNumber").focus(); return false;}
		if(from == ''){ alert("Enter start range"); return false;}
		if(to == ''){ alert("Enter end range"); return false;}
		
		//alert(path_relative+'DummyFinalMarks/displayCourseDetails/'+monthYear+'/'+startRange);
		$.ajax({
			url: path_relative+'DummyFinalMarks/displayCourseDetails/'+monthYear+'/'+startRange,
			type: 'POST',
		    dataType: 'HTML',
		    success : function(data) {
		    	$("#dummyResult").html(data);
		    },
		    error : function(request, error) {
		    	//alert(request+" "+error);
		    	alert($.parseJSON(data.responseText).val);
		    },
		});
	});
	
	$(".js-reval-dummy-display").click(function(){
		monthYear = $("#RevaluationExamMonthYearId").val();
		option = $("#RevaluationExamOption").val();
		
		if(monthYear == ''){alert("Select Exam Month Year"); $("#RevaluationExamMonthYearId").focus(); return false;}
		if(option == ''){ alert("Select an optione"); $("#RevaluationExamOption").focus(); return false;}
		
		//alert(path_relative+'RevaluationExams/displayDetails/'+monthYear+'/'+option);
		$.ajax({
			url: path_relative+'RevaluationExams/displayDetails/'+monthYear+'/'+option,
			type: 'POST',
		    dataType: 'HTML',
		    success : function(data) {
		    	$("#revalDummyResult").html(data);
		    },
		    error : function(request, error) {
		    	alert(request+" "+error);
		    	//alert($.parseJSON(data.responseText).val);
		    },
		});
	});
	
	$(".js-reval-moderation").change(function(){
		optValue = this.value;
		if (optValue == "Fail") {
			//$("#failedOption").html("text");
			$.ajax({
				url: path_relative+'RevaluationExams/failedOption/'+optValue,
				type: 'POST',
			    dataType: 'HTML',
				success: function(data, txtStatus) {
					//alert(data);
					$("#failedOption").html(data);
					$("#diffOption").html("");
					triggerSecond();
				},
			});
		}
		else if (optValue == "Pass") {
			$.ajax({
				url: path_relative+'RevaluationExams/diffOption/'+optValue,
				type: 'POST',
			    dataType: 'HTML',
				success: function(data, txtStatus) {
					//alert(data);
					$("#diffOption").html(data);
					$("#failedOption").html("");
					triggerSecond();
				},
			});
			//$("#diffOption").html("");
		}
		
	});
	
	$(".js-reval-adjust").click(function(){
		examMonthYear = $("#RevaluationExamMonthYearId").val();
		examOption = $("#RevaluationExamOption").val();
		adjustToMark = $("#RevaluationExamAdjustTo").val();
		from = $("#RevaluationExamDiffFrom").val();
		to = $("#RevaluationExamDiffTo").val();
		
		if(examMonthYear == ''){alert("Select Exam Month Year");return false;}
		if(examOption == ''){ alert("Select an option"); return false;}
		if(from == ''){ alert("Enter start range"); $("#RevaluationExamDiffFrom").focus(); return false;}
		if(to == ''){ alert("Enter end range"); $("#RevaluationExamDiffTo").focus(); return false;}
		
		if (to < from) {
			alert('Enter value greater than '+from);
			$("#RevaluationExamDiffTo").focus();
			return false;
		}
		
		if(adjustToMark == ''){ alert("Enter moderation value"); $("#RevaluationExamAdjustTo").focus(); return false;}
		
		if (adjustToMark>to) {
			alert('Enter mark between '+from+' and '+to);
			$("#RevaluationExamAdjustTo").focus();
			return false;
		}
		
		/*$.ajax({
			url: path_relative+'RevaluationExams/processData/'+monthYear+'/'+startRange+'/'+from+'/'+to,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#dummyMarksDisplay").html(data);
			}
		});*/
		
	});
	
	$(".js-reval-display").click(function(){
		revaluationType='';
		monthYear = $("#RevaluationExamMonthYearId").val();
		examOption = $("#RevaluationExamOption").val();
		if (examOption == "Fail") {
			failed_option = $("#failed_option").val();	
			urlOption = path_relative+'RevaluationExams/getRevaluationResult/'+monthYear+'/'+examOption+'/'+failed_option+'/'+revaluationType+'/';
		}
		else {
			failed_option = "Pass";
			urlOption = path_relative+'RevaluationExams/getRevaluationResult/'+monthYear+'/'+examOption+'/'+failed_option+'/'+revaluationType+'/';
		}
		$("#revalResult").html("<img src='"+path_relative+"img/loading.gif' />");
		//alert(path_relative+'RevaluationExams/getRevaluationResult/'+monthYear+'/'+examOption+'/'+failed_option+'/'+revaluationType+'/');
		$.ajax({
			url: urlOption,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#revalResult").html(data);
			},
		});
	});
	
	
	$(".js-dummy-moderation").click(function(){
		monthYear = $("#DummyFinalMarkMonthYearId").val();
		startRange = $("#DummyFinalMarkDummyNumber").val();
		maxEseMark = $("#max_ese_mark").val();
		from = $("#from").val();
		to = $("#to").val();
		
		if(monthYear == ''){alert("Select Exam Month Year");return false;}
		if(startRange == ''){ alert("Select Dummy start range"); return false;}
		if(from == ''){ alert("Enter start range"); $("#from").focus(); return false;}
		if (from > maxEseMark) {
			alert('Maximum cannot exceed '+maxEseMark);
			$("#from").focus();
			return false;
		}
		if(to == ''){ alert("Enter end range"); $("#to").focus(); return false;}
		
		if (to > maxEseMark) {
			alert('Maximum cannot exceed '+maxEseMark);
			$("#to").focus();
			return false;
		}
		if (from > to) {
			alert('Enter a value less than '+to);
			$("#from").focus();
			return false;
		}
		
		//alert(path_relative+'DummyFinalMarks/listMarksForRange/'+monthYear+'/'+startRange+'/'+from+'/'+to);
		$.ajax({
			url: path_relative+'DummyFinalMarks/listMarksForRange/'+monthYear+'/'+startRange+'/'+from+'/'+to,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#dummyMarksDisplay").html(data);
			}
		});
	});
	
	/*$(".js-reval-dummy-moderation").click(function(){
		monthYear = $("#RevaluationExamMonthYearId").val();
		startRange = $("#RevaluationExamDummyNumber").val();
		maxEseMark = $("#max_ese_mark").val();
		from = $("#from").val();
		to = $("#to").val();
		
		if(monthYear == ''){alert("Select Exam Month Year");return false;}
		if(startRange == ''){ alert("Select Dummy start range"); return false;}
		if(from == ''){ alert("Enter start range"); $("#from").focus(); return false;}
		if (from > maxEseMark) {
			alert('Maximum cannot exceed '+maxEseMark);
			$("#from").focus();
			return false;
		}
		if(to == ''){ alert("Enter end range"); $("#to").focus(); return false;}
		
		if (to > maxEseMark) {
			alert('Maximum cannot exceed '+maxEseMark);
			$("#to").focus();
			return false;
		}
		if (from > to) {
			alert('Enter a value less than '+to);
			$("#from").focus();
			return false;
		}
		
		//alert(path_relative+'DummyFinalMarks/listMarksForRange/'+monthYear+'/'+startRange+'/'+from+'/'+to);
		$.ajax({
			url: path_relative+'RevaluationExams/listMarksForRange/'+monthYear+'/'+startRange+'/'+from+'/'+to,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#dummyMarksDisplay").html(data);
			}
		});
	});*/
	
	$(".js-dummy-confirm").click(function(){
		monthYear = $("#DummyFinalMarkMonthYearId").val();
		startRange = $("#DummyFinalMarkDummyNumber").val();
		
		if(monthYear == ''){alert("Select Exam Month Year");return false;}
		if(startRange == ''){ alert("Select Dummy start range"); return false;}
		
		//alert(path_relative+'DummyFinalMarks/flagModeration/'+monthYear+'/'+startRange);
		$.ajax({
			url: path_relative+'DummyFinalMarks/flagModeration/'+monthYear+'/'+startRange,
			type: 'POST',
		    dataType: 'HTML',
			success : function(data) {
				alert($.parseJSON(data.responseText).val);
			},
			error : function(data) {
				alert("Error occured");
			},
		});
	});
	
	$(".js-practical-report").click(function() {
		if($('#PracticalBatchId').val() == ''){
			alert("Select Batch");
			return false;
		}
		if($('#PracticalAcademicId').val() == ''){
			alert("Select Program");
			return false;
		}
		if($('#StudentProgramId').val() == ''){
			alert("Select Specialisation");
			return false;
		}
		if($('#month_year_id').val() == ''){
			alert("Select Month Year");
			return false;
		}
		varAjax = 0;
		$("#marksDisplay").html("<img src='../img/loading.gif' alt='Calculating practical marks...'/>");
		programId=$(".js-program").val();
		batchId=$(".js-batch").val();
		academicId=$(".js-academic").val();
		monthYearId=$(".js-month-year").val();
		option=$(".js-practical-category").val();
		//alert(path_relative+'EsePracticals/getMarks/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+option+'/'+varAjax);
		$.ajax({
			url: path_relative+'EsePracticals/getMarks/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+option+'/'+varAjax,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#marksDisplay").html(data);
			}
		});
	});
	
	$(".js-project-report").click(function() {
		if($('#ProjectBatchId').val() == ''){
			alert("Select Batch");
			return false;
		}
		if($('#ProjectAcademicId').val() == ''){
			alert("Select Program");
			return false;
		}
		if($('#StudentProgramId').val() == ''){
			alert("Select Specialisation");
			return false;
		}
		if($('#month_year_id').val() == ''){
			alert("Select Month Year");
			return false;
		}
		varAjax = 0;
		$("#marksDisplay").html("<img src='../img/loading.gif' alt='Calculating Project Marks...'/>");
		programId=$(".js-program").val();
		batchId=$(".js-batch").val();
		academicId=$(".js-academic").val();
		monthYearId=$(".js-month-year").val();
		option=$(".js-project-category").val();
		//alert(path_relative+'EsePracticals/getMarks/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+option+'/'+varAjax);
		$.ajax({
			url: path_relative+'EseProjects/getMarks/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+option+'/'+varAjax,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#marksDisplay").html(data);
			}
		});
	});
	
	$(".js-practical-get").click(function() {
		if($('#PracticalBatchId').val() == ''){
			alert("Select Batch");
			return false;
		}
		if($('#PracticalAcademicId').val() == ''){
			alert("Select Program");
			return false;
		}
		if($('#StudentProgramId').val() == ''){
			alert("Select Specialisation");
			return false;
		}
		if($('#month_year_id').val() == ''){
			alert("Select Month Year");
			return false;
		}

		$("#marksDisplay").html("<img src='../img/loading.gif' alt='Calculating practical marks...'/>");
		programId=$(".js-program").val();
		batchId=$(".js-batch").val();
		academicId=$(".js-academic").val();
		monthYearId=$(".js-month-year").val();
		option="All";
		varAjax = 1;
		//alert(path_relative+'EsePracticals/getStatus/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+option+'/'+varAjax);
		$.ajax({
			url: path_relative+'EsePracticals/getStatus/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+option+'/'+varAjax,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#marksDisplay").html(data);
			}
		});
	});
	
	$(".js-practical-moderation").click(function() {
		if($('#PracticalModBatchId').val() == ''){
			alert("Select Batch");
			return false;
		}
		if($('#PracticalModAcademicId').val() == ''){
			alert("Select Program");
			return false;
		}
		if($('#StudentProgramId').val() == ''){
			alert("Select Specialisation");
			return false;
		}
		//if($('#month_year_id').val() == ''){ //PracticalModMonthYearId
		if($('#PracticalModMonthYearId').val() == ''){ 
			alert("Select Month Year");
			return false;
		}
		if($('#PracticalModCourseMarks').val() == ''){
			alert("Enter Course Marks");
			return false;
		}
		if ($('#PracticalModOption').val()=='') {
			alert("Select an option");
			return false;
		}
		if ($('#PracticalModOption').val()=='ese') {
			if($('#ese_from').val() == ''){
				alert("Enter ESE from");
				return false;
			}
			if($('#ese_to').val() == ''){
				alert("Enter ESE to");
				return false;
			}
			/*if($('#total_from').val() == ''){
				alert("Enter Total from");
				return false;
			}
			if($('#total_to').val() == ''){
				alert("Enter Total to");
				return false;
			}*/
		}
		else if ($('#PracticalModOption').val()=='both') {
			if($('#total_from').val() == ''){
				alert("Enter CAE from");
				return false;
			}
			if($('#total_to').val() == ''){
				alert("Enter CAE to");
				return false;
			}
			if($('#ese_greater_than').val() == ''){
				alert("Enter ESE greater than value");
				return false;
			}
			if($('#ese_lesser_than').val() == ''){
				alert("Enter ESE lesser than value");
				return false;
			}
		}
		else if ($('#PracticalModOption').val()=='total') {
			if($('#ese_from').val() == ''){
				alert("Enter ESE from");
				return false;
			}
			if($('#ese_to').val() == ''){
				alert("Enter ESE to");
				return false;
			}
			if($('#total_from').val() == ''){
				alert("Enter Total from");
				return false;
			}
			if($('#total_to').val() == ''){
				alert("Enter Total to");
				return false;
			}
		}
		$("#marksDisplay").html("<img src='../img/loading.gif' alt='Calculating practical marks...'/>");
		batchId = "-";
		programId = "-";
		academicId = "-";
		
		if($('#PracticalModBatchId').val() != '') batchId=$(".js-batch").val();
		if($('#PracticalModAcademicId').val() != '') academicId=$(".js-academic").val();
		if($('#PracticalModProgramId').val() != '') programId=$(".js-program").val();
		else if($('#StudentProgramId').val() != '') programId=$(".js-program").val();
		if (programId == '') programId='-';
		
		monthYearId=$(".js-month-year").val();
		courseMarks=$(".js-course-marks").val();
		modOption = $('#PracticalModOption').val();
		
		if ($('#PracticalModOption').val()=='ese') {
			from=$(".js-ese-from").val();
			to=$(".js-ese-to").val();
			eseGreaterThan=$(".js-total-from").val();
			eseLesserThan=$(".js-total-to").val();
			eseGreaterThan=0;
			eseLesserThan=0;
		}
		else if ($('#PracticalModOption').val()=='both') {
			from=$(".js-total-from").val();
			to=$(".js-total-to").val();
			eseGreaterThan = $('#ese_greater_than').val();
			eseLesserThan = $('#ese_lesser_than').val();
		}
		else if ($('#PracticalModOption').val()=='total') {
			from=$(".js-ese-from").val();
			to=$(".js-ese-to").val();
			//total from
			eseGreaterThan=$(".js-total-from").val();
			//total to
			eseLesserThan=$(".js-total-to").val();
		}
		varAjax = 1;
		//alert(path_relative+'EsePracticals/getModRecords/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+courseMarks+'/'+modOption+'/'+from+'/'+to+'/'+eseGreaterThan);
		$.ajax({
			url: path_relative+'EsePracticals/getModRecords/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+courseMarks+'/'+modOption+'/'+from+'/'+to+'/'+eseGreaterThan+'/'+eseLesserThan,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#marksDisplay").html(data);
			}
		});
	});
	
	$(".js-tp-practical-moderation").click(function() {
		if($('#PracticalModBatchId').val() == ''){
			alert("Select Batch");
			return false;
		}
		if($('#PracticalModAcademicId').val() == ''){
			alert("Select Program");
			return false;
		}
		if($('#StudentProgramId').val() == ''){
			alert("Select Specialisation");
			return false;
		}
		if($('#month_year_id').val() == ''){
			alert("Select Month Year");
			return false;
		}
		if($('#PracticalModCourseMarks').val() == ''){
			alert("Enter Course Marks");
			return false;
		}
		if ($('#PracticalModOption').val()=='') {
			alert("Select an option");
			return false;
		}
		if ($('#PracticalModOption').val()=='ese') {
			if($('#ese_from').val() == ''){
				alert("Enter ESE from");
				return false;
			}
			if($('#ese_to').val() == ''){
				alert("Enter ESE to");
				return false;
			}
			/*if($('#total_from').val() == ''){
				alert("Enter Total from");
				return false;
			}
			if($('#total_to').val() == ''){
				alert("Enter Total to");
				return false;
			}*/
		}
		else if ($('#PracticalModOption').val()=='both') {
			if($('#total_from').val() == ''){
				alert("Enter CAE from");
				return false;
			}
			if($('#total_to').val() == ''){
				alert("Enter CAE to");
				return false;
			}
			if($('#ese_greater_than').val() == ''){
				alert("Enter ESE greater than value");
				return false;
			}
			if($('#ese_lesser_than').val() == ''){
				alert("Enter ESE lesser than value");
				return false;
			}
		}
		else if ($('#PracticalModOption').val()=='total') {
			if($('#ese_from').val() == ''){
				alert("Enter ESE from");
				return false;
			}
			if($('#ese_to').val() == ''){
				alert("Enter ESE to");
				return false;
			}
			if($('#total_from').val() == ''){
				alert("Enter Total from");
				return false;
			}
			if($('#total_to').val() == ''){
				alert("Enter Total to");
				return false;
			}
		}
		$("#marksDisplay").html("<img src='../img/loading.gif' alt='Calculating practical marks...'/>");
		programId=$(".js-program").val();
		batchId=$(".js-batch").val();
		academicId=$(".js-academic").val();
		monthYearId=$(".js-month-year").val();
		courseMarks=$(".js-course-marks").val();
		modOption = $('#PracticalModOption').val();
		
		if ($('#PracticalModOption').val()=='ese') {
			from=$(".js-ese-from").val();
			to=$(".js-ese-to").val();
			eseGreaterThan=$(".js-total-from").val();
			eseLesserThan=$(".js-total-to").val();
			eseGreaterThan=0;
			eseLesserThan=0;
		}
		else if ($('#PracticalModOption').val()=='both') {
			from=$(".js-total-from").val();
			to=$(".js-total-to").val();
			eseGreaterThan = $('#ese_greater_than').val();
			eseLesserThan = $('#ese_lesser_than').val();
		}
		else if ($('#PracticalModOption').val()=='total') {
			from=$(".js-ese-from").val();
			to=$(".js-ese-to").val();
			//total from
			eseGreaterThan=$(".js-total-from").val();
			//total to
			eseLesserThan=$(".js-total-to").val();
		}
		varAjax = 1;
		//alert(path_relative+'EsePracticals/getModRecords/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+courseMarks+'/'+modOption+'/'+from+'/'+to+'/'+eseGreaterThan);
		$.ajax({
			url: path_relative+'EsePracticals/getModRecords/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+courseMarks+'/'+modOption+'/'+from+'/'+to+'/'+eseGreaterThan+'/'+eseLesserThan,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#marksDisplay").html(data);
			}
		});
	});
	
	$(".js-ese-moderation").click(function() {
		if($('#EseModBatchId').val() == ''){
			alert("Select Batch");
			return false;
		}
		/*if($('#EseModAcademicId').val() == ''){
			alert("Select Program");
			return false;
		}*/
		/*if($('#StudentProgramId').val() == ''){
			alert("Select Specialisation");
			return false;
		}*/
		if($('#month_year_id').val() == ''){
			alert("Select Month Year");
			return false;
		}
		if ($('#EseModOption').val()=='') {
			alert("Select an option");
			return false;
		}
		if ($('#EseModOption').val()=='ese') {
			if($('#ese_from').val() == ''){
				alert("Enter ESE from");
				return false;
			}
			if($('#ese_to').val() == ''){
				alert("Enter ESE to");
				return false;
			}
		}
		else if ($('#EseModOption').val()=='both') {
			if($('#total_from').val() == ''){
				alert("Enter CAE from");
				return false;
			}
			if($('#total_to').val() == ''){
				alert("Enter CAE to");
				return false;
			}
			if($('#ese_greater_than').val() == ''){
				alert("Enter ESE greater than value");
				return false;
			}
			if($('#ese_lesser_than').val() == ''){
				alert("Enter ESE lesser than value");
				return false;
			}
		}
		else if ($('#EseModOption').val()=='total') {
			if($('#ese_from').val() == ''){
				alert("Enter CAE from");
				return false;
			}
			if($('#ese_to').val() == ''){
				alert("Enter CAE to");
				return false;
			}
			if($('#total_from').val() == ''){
				alert("Enter ESE greater than value");
				return false;
			}
			if($('#total_to').val() == ''){
				alert("Enter ESE lesser than value");
				return false;
			}
		}
		batchId=0; programId=0; academicId=0;
		$("#marksDisplay").html("<img src='../img/loading.gif' alt='Calculating practical marks...'/>");
		programId=$(".js-program").val();
		batchId=$(".js-batch").val();
		academicId=$(".js-academic").val();
		monthYearId=$(".js-month-year").val();
		modOption = $('#EseModOption').val();
		courseId = $('#course_id').val();
		
		if(batchId=="" || batchId=="undefined") batchId=0;
		if(programId=="" || programId=="undefined") programId=0;
		if(academicId=="" || academicId=="undefined") academicId=0;
		
		if ($('#EseModOption').val()=='ese') {
			from=$(".js-ese-from").val();
			to=$(".js-ese-to").val();
			eseGreaterThan = 0;
			eseLesserThan = 0;
		}
		else if ($('#EseModOption').val()=='both') {
			from=$(".js-total-from").val();
			to=$(".js-total-to").val();
			eseGreaterThan = $('#ese_greater_than').val();
			eseLesserThan = $('#ese_lesser_than').val();
		}
		else if ($('#EseModOption').val()=='total') {
			from=$(".js-ese-from").val();
			to=$(".js-ese-to").val();
			eseGreaterThan = $('.js-total-from').val();
			eseLesserThan = $('.js-total-to').val();
		}
		varAjax = 1;
		
		if (modOption == "ese") esePath = path_relative+'EndSemesterExams/getModRecordsEse/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+from+'/'+to+'/'+modOption+'/'+courseId;
		if (modOption == "both") esePath = path_relative+'EndSemesterExams/getModRecordsBoth/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+from+'/'+to+'/'+eseGreaterThan+'/'+eseLesserThan+'/'+modOption+'/'+courseId;
		if (modOption == "total") esePath = path_relative+'EndSemesterExams/getModRecordsTotal/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+from+'/'+to+'/'+eseGreaterThan+'/'+eseLesserThan+'/'+modOption+'/'+courseId;
		//alert(esePath);
		$.ajax({
			url: esePath,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#marksDisplay").html(data);
			}
		});
	});
	
	$(".js-mod-option").change(function(){
		option = $(this).val();
		/*if (option == "ESE") {
			varOption = "ese";
		}
		else if (option == "Total") {
			varOption = "ese";
		}*/
		//alert(path_relative+'EsePracticals/displayOption/'+option);
		$.ajax({
			url: path_relative+'EsePracticals/displayOption/'+option,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#modOptions").html(data);
			},
		});
	});
	
	$(".js-ese-mod-option").change(function(){
		option = $(this).val();
		/*if (option == "ESE") {
			varOption = "ese";
		}
		else if (option == "Total") {
			varOption = "ese";
		}*/
		//alert(path_relative+'EndSemesterExams/displayOption/'+option);
		$.ajax({
			url: path_relative+'EndSemesterExams/displayOption/'+option,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#modOptions").html(data);
			},
		});
	});
	
	$(".js-csmDownload").click(function() {
		programId=$(".js-program").val();
		batchId=$(".js-batch").val();
		academicId=$(".js-academic").val();
		//alert(path_relative+'CourseStudentMappings/mapCourseStudent/'+programId+'/'+batchId+'/'+academicId);
		$.ajax({
			url: path_relative+'CourseStudentMappings/mapCourseStudent/'+programId+'/'+batchId+'/'+academicId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#displayCSMData").html(data);
			},
			error: function (xhRequest, ErrorText, thrownError) {
		        //alert("Failed to process promotion correctly, please try again");
		        console.log('xhRequest: ' + xhRequest + "\n");
		        console.log('ErrorText: ' + ErrorText + "\n");
		        console.log('thrownError: ' + thrownError + "\n");
		    }
		});
	});
	
	$(".individualStudent").click(function(){
		regNumber = $(".js-mod-individual").val();
		//alert(path_relative+'ContinuousAssessmentExams/getIndividualStudentData/'+regNumber);
		$.ajax({
			url: path_relative+'ContinuousAssessmentExams/getIndividualStudentData/'+regNumber,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#individualStudent").html(data);
			}
		});
	});
	
	$(".js-report").click(function() {
		if($('#BatchwiseBatchId').val() == '') {
			alert('Choose Batch');
			return false;
		}
		printOption=0;
		monthYearId = $('#BatchwiseMonthYearId').val();
		batchId = $('#BatchwiseBatchId').val();
		option = $('#BatchwiseOption').val();
		
		$("#reportDisplay").html("<img src='"+path_relative+"img/loading.gif' alt='ESE Loading...'/>");
		alert(path_relative+'EndSemesterExams/getReport/'+batchId+'/'+monthYearId+'/'+option+'/'+printOption);
		$.ajax({
			url: path_relative+'EndSemesterExams/getReport/'+batchId+'/'+monthYearId+'/'+option+'/'+printOption,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#reportDisplay").html(data);
			}
		});
	});

	$(".js-report-batchwise").click(function() {
		if($('#BatchwiseMonthYearId').val() == '') {
			alert('Choose Month Year');
			return false;
		}
		if($('#BatchwiseBatchId').val() == '') {
			alert('Choose Batch');
			return false;
		}
		printOption=0;
		programId = '-';
		cmId = '-';
		
		type = $('#BatchwiseType').val();
		monthYearId = $('#BatchwiseMonthYearId').val();
		batchId = $('#BatchwiseBatchId').val();
		
		//alert(type);
		if (type == "WoP") {
			programId="-"; cmId="-";
		} else if (type == "WP") {
			programId = $('#StudentProgramId').val();
			cmId = $('#course_mapping_id').val();
		}
		
		if (programId == '') programId="-";
		if (cmId == '') cmId="-";
		
		$("#reportDisplay").html("<img src='"+path_relative+"img/loading.gif' alt='ESE Loading...'/>");
		//alert(path_relative+'EndSemesterExams/batchwiseReport/'+batchId+'/'+monthYearId+'/'+printOption);
		$.ajax({
			url: path_relative+'EndSemesterExams/batchwiseReport/'+batchId+'/'+monthYearId+'/'+printOption+'/'+programId+'/'+cmId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#reportDisplay").html("");
				var res = data.substring(0, 7);
				if (res == "Success") {
					alert ("Result Published");
				} else {
					alert ("Result Not Published");
				}
			}
		});
	});
	
	$(".js-pgmwise-publish-result").click(function() {
		
		programId = '-';
		cmId = '-';
		
		if($('#BatchwiseMonthYearId').val() == '') {
			alert('Choose Month Year');
			return false;
		}
		if($('#BatchwiseBatchId').val() == '') {
			alert('Choose Batch');
			return false;
		}
		
		if($('#StudentProgramId').val() == '') {
			alert('Choose Program');
			return false;
		}
		
		if($('#course_mapping_id').val() == '') {
			alert('Choose Course');
			return false;
		}
		
		monthYearId = $('#BatchwiseMonthYearId').val();
		batchId = $('#BatchwiseBatchId').val();
		programId = $('#StudentProgramId').val();
		cmId = $('#course_mapping_id').val();
		
		if (programId == '') programId="-";
		if (cmId == '') cmId="-";
		
		$("#reportDisplay").html("<img src='"+path_relative+"img/loading.gif' alt='ESE Loading...'/>");
		alert(path_relative+'EndSemesterExams/pgmwiseReport/'+batchId+'/'+programId+'/'+cmId+'/'+monthYearId);
		$.ajax({
			url: path_relative+'EndSemesterExams/pgmwiseReport/'+batchId+'/'+programId+'/'+cmId+'/'+monthYearId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#reportDisplay").html("");
				var res = data.substring(0, 7);
				if (res == "Success") {
					alert ("Result Published");
				} else {
					alert ("Result Not Published");
				}
			}
		});
	});
	
	$(".js-revaluation-report-batchwise").click(function() { 
		if($('#BatchwiseMonthYearId').val() == '') {
			alert('Choose Month Year');
			return false;
		}
		if($('#BatchwiseBatchId').val() == '') {
			alert('Choose Batch');
			return false;
		}
		printOption=0;
		monthYearId = $('#BatchwiseMonthYearId').val();
		batchId = $('#BatchwiseBatchId').val();
		$("#reportDisplay").html("<img src='"+path_relative+"img/loading.gif' alt='ESE Loading...'/>");
		//alert(path_relative+'RevaluationExams/batchwiseReport/'+batchId+'/'+monthYearId+'/'+printOption);
		$.ajax({
			url: path_relative+'RevaluationExams/batchwiseReport/'+batchId+'/'+monthYearId+'/'+printOption,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#reportDisplay").html(data);
			}
		});
	});
	
	$(".js-reval").click(function() {
		if($('#RevaluationMonthYearId').val() == '') {
			alert('Choose Month Year');
			return false;
		}
		if($('#RevaluationRegNumber').val() == '') {
			alert('Enter Registration Number');
			return false;
		}
		printOption=0;
		monthYearId = $('#RevaluationMonthYearId').val();
		regNumber = $('#RevaluationRegNumber').val();
		$("#revaluationDisplay").html("<img src='"+path_relative+"img/loading.gif' alt='ESE Loading...'/>");
		//alert(path_relative+'Revaluations/revaluationSearch/'+monthYearId+'/'+regNumber);
		$.ajax({
			url: path_relative+'Revaluations/revaluationSearch/'+monthYearId+'/'+regNumber,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#revaluationDisplay").html(data);
			}
		});
	});
	
	$(".js-report-batchwise-print").click(function() {
		if($('#BatchwiseMonthYearId').val() == '') {
			alert('Choose Month Year');
			return false;
		}
		if($('#BatchwiseBatchId').val() == '') {
			alert('Choose Batch');
			return false;
		}
		printOption=1;
		monthYearId = $('#BatchwiseMonthYearId').val();
		batchId = $('#BatchwiseBatchId').val();
		$("#reportDisplay").html("<img src='"+path_relative+"img/loading.gif' alt='Printing...'/>");
		//alert(path_relative+'EndSemesterExams/getReport/'+batchId+'/'+monthYearId+'/'+printOption);
		$.ajax({
			url: path_relative+'EndSemesterExams/getReport/'+batchId+'/'+monthYearId+'/'+printOption,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#reportDisplay").html(data);
			}
		});
	});
	
	$(".js-report-batchwise-print").click(function() {
		if($('#BatchwiseMonthYearId').val() == '') {
			alert('Choose Month Year');
			return false;
		}
		if($('#BatchwiseBatchId').val() == '') {
			alert('Choose Batch');
			return false;
		}
		printOption=1;
		monthYearId = $('#BatchwiseMonthYearId').val();
		batchId = $('#BatchwiseBatchId').val();
		$("#reportDisplay").html("<img src='"+path_relative+"img/loading.gif' alt='Printing...'/>");
		//alert(path_relative+'EndSemesterExams/getReport/'+batchId+'/'+monthYearId+'/'+printOption);
		$.ajax({
			url: path_relative+'RevaluationsExams/getReport/'+batchId+'/'+monthYearId+'/'+printOption,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#reportDisplay").html(data);
			}
		});
	});
	
	$(".js-arrear").click(function() {
		if($('#ArrearMonthYearId').val() == '') {
			alert('Choose Month Year');
			return false;
		}
		varAjax=1;
		monthYearId = $('#ArrearMonthYearId').val();
		$("#reportDisplay").html("<img src='"+path_relative+"img/loading.gif' alt='ESE Loading...'/>");
		//alert(path_relative+'EndSemesterExams/arrearReport/'+monthYearId+'/'+varAjax);
		$.ajax({
			url: path_relative+'EndSemesterExams/arrearReport/'+monthYearId+'/'+varAjax,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#reportDisplay").html(data);
			}
		});
	});
	
	$(".js-failure").click(function() {
		if($('#ArrearMonthYearId').val() == '') {
			alert('Choose Month Year');
			return false;
		}
		if($('#ArrearMonthYearId').val() == $('#ArrearNewMonthYearId').val()) {
			alert('Failure carry over cannot have same Month Year!!!');
			return false;
		}
		if($('#ArrearNewMonthYearId').val() < $('#ArrearMonthYearId').val()) {
			alert('Failure carry over should be after '+$('#ArrearNewMonthYearId option:selected').text());
			return false;
		}
		printOption=0;
		monthYearId = $('#ArrearMonthYearId').val();
		$("#reportDisplay").html("<img src='"+path_relative+"img/loading.gif' alt='ESE Loading...'/>");
		//alert(path_relative+'StudentMarks/failureInternalMarks/'+monthYearId+'/'+printOption);
		$.ajax({
			url: path_relative+'StudentMarks/failureInternalMarks/'+monthYearId+'/'+printOption,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#failureStatus").html("Completed transferring data...");
			},
			error: function(data) {
		    	//$("#dummyResult").html("There is no data mismatch and data is already synchronized");
		    	alert($.parseJSON(data.responseText).val);
		    },
		});
	});
	
	$(".js-faculty-search").click(function() {
		if($('#FacultyAcademicId').val() == '' && $('#FacultyFacultyId').val() == '') {
			alert('Choose an option');
			return false;
		}
		if($('#FacultyAcademicId').val() == '' && ($('#FacultyFacultyId').val() == '') || $('#program_id').val() == '') {
			alert('Choose Program');
			return false;
		}
		if($('#FacultyAcademicId').val() == ''){
			academicId=0
		} else {
			academicId=$(".js-academic").val();
		}
		if($('#program_id').val() == '' || $('#FacultyProgramId').val() == ''){
			programId=0
		} else {
			programId=$(".js-program").val();
		}
		if($('#FacultyFacultyId').val() == ''){
			facultyId=0
		} else {
			facultyId=$(".js-faculty").val();
		}		
		//alert(path_relative+'ContinuousAssessmentExams/searchFacultyAssignment/'+programId+'/'+academicId+'/'+facultyId);
		$.ajax({
			url: path_relative+'ContinuousAssessmentExams/searchFacultyAssignment/'+programId+'/'+academicId+'/'+facultyId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#facultyDisplay").html(data);
			}
		});
	});
	
	$(".js-exam-att, .js-dummy-marks").click(function() {
		strExplode = urlCae.split('/');
		currentController = strExplode[2];
		currentMethod = strExplode[3];
		//alert(currentController+' '+currentMethod);
		examType=$(".js-exam-type").val();
		//alert(examType);
		if($('.js-batch').val() == ''){
			batchId=0
		} else {
			batchId=$(".js-academic").val();
		}
		if($('.js-academic').val() == ''){
			academicId=0
		} else {
			academicId=$(".js-academic").val();
		}
		if($('.js-program').val() == ''){
			programId=0
		} else {
			programId=$(".js-program").val();
		}
		if($('.js-month-year').val() == ''){
			month_year_id=0
		} else {
			month_year_id=$(".js-month-year").val();
		}
		if($('.js-month-year').val() == ''){
			month_year_id=0
		} else {
			month_year_id=$(".js-month-year").val();
		}
		if($('.js-exam-course').val() == ''){
			cm_id=0
		} else {
			cm_id=$(".js-exam-course").val();
		}
			//alert(path_relative+'ExamAttendances/retrieveStudents/'+cm_id+'/'+examType);
		if(currentController=="ExamAttendances" && currentMethod=="attendance") {
			$.ajax({
	 			url: path_relative+'ExamAttendances/retrieveStudents/'+cm_id+'/'+examType,
	 			type: 'POST',
	 		    dataType: 'HTML',
	 			success: function(data, txtStatus) {
	 				$("#students_list").html(data);
	 			}				
	 		});
		}
		else if(currentController=="DummyMarks" && currentMethod=="marksEntry") {
			//alert(path_relative+'DummyMarks/retrieveDummyNumbers/'+cm_id+'/'+examType);
			$.ajax({
	 			url: path_relative+'DummyMarks/retrieveDummyNumbers/'+cm_id+'/'+month_year_id,
	 			type: 'POST',
	 		    dataType: 'HTML',
	 			success: function(data, txtStatus) {
	 				$("#dummy_marks").html(data);
	 			}				
	 		});
		}
		
	});
	
	$(".js-faculty-courses").click(function(){
		//alert('hi');
		var selected = $("#program_id option:selected");
        var message = "";
        selected.each(function () {
            message += $(this).text() + " " + $(this).val() + "\n";
        });
        //alert(message);
	});
    
	 $('#btnCourses').click(function () {
         var selected = $("#program_id option:selected");
         var message = "";
         var program = [];
         
         selected.each(function () {
        	 var programId = {};
             //message += $(this).text() + " " + $(this).val() + "\n";
        	 message += $(this).val();
             //programId.push($(this).val());
        	 //programId[parseInt($(this).val())] =  $(this).text();
        	 programId['name'] = parseInt($(this).val());
        	 programId['value'] = $(this).text();
        	 program.push(programId);
         });
         //alert(program);
         //var programId = programId.serializeArray();
         var jsonString = JSON.stringify(program);
         //alert(jsonString);   
         
         //alert(message);
         //alert(programId);
         //alert(path_relative+'ContinuousAssessmentExams/findCourseMappingByProgram/');
         $.ajax({
 			url: path_relative+'ContinuousAssessmentExams/findCourseMappingByProgram/',
 			type: 'POST',
 			data: {data:jsonString},
 		    dataType: 'HTML',
 			success: function(data, txtStatus) {
 				//alert(data);
 				//$(".program").html();
 				$("#courses").html(data);
 				/*if (clas == 'js-academic-popup') {
 					//$("#program_faculty").html(data);
 				}*/ /*else if (clas == 'js-batch') {
 					$("#program").html(data);
 				}*/
 				
 				//initSecond();
 			}				
 		});
         
     });
	 
	//$(".js-academic-popup, .js-cae-academic").change(function(){
	$(".js-academic-popup").change(function(){
		clas = $(this).attr('class').split(' ');
		//alert(clas);
		if (clas == 'js-academic-popup') {
			academicId=$(".js-academic-popup").val();
			opt = true;
		} /*else if (clas == 'js-cae-batch') {
			academicId = $(".js-cae-academic").val();
		}*/
		//alert(path_relative+'ContinuousAssessmentExams/findProgramByAcademic/'+academicId+'/'+opt);
		$.ajax({
			url: path_relative+'ContinuousAssessmentExams/findProgramByAcademic/'+academicId+'/'+opt,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				//$(".program").html();
				//$(".program").html(data);
				if (clas == 'js-academic-popup') {
					$("#program_faculty").html(data);
					$("#AddProgramId").prop("multiple", "multiple"); 
				} /*else if (clas == 'js-batch') {
					$("#program").html(data);
				}*/
				
				//initSecond();
			}				
		});
	});
	

	$(".js-cae-export").click(function(){
		//alert('hello');
		programId=$(".js-program").val();
		batchId=$(".js-batch").val();
		academicId=$(".js-academic").val();
		monthYearId=$(".js-month-year").val();
		//alert(path_relative+'ContinuousAssessmentExams/exportCae/'+programId+'/'+batchId+'/'+academicId+'/'+monthYearId);
		$.ajax({
			url: path_relative+'ContinuousAssessmentExams/exportInternal/'+programId+'/'+batchId+'/'+academicId+'/'+monthYearId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert('hi');
				//alert(data);
				//$("#displayButtons").html(data);
			}
		});
	});
	
	$(".js-cae-report").click(function() {
		if($('#CaeMonthYearId').val() == '') {
			alert('Choose Month Year');
			return false;
		}
		if($('#CaeBatchId').val() == '') {
			alert('Choose Batch');
			return false;
		}
		if($('#CaeNo').val() == '') {
			alert('Choose Cae Number');
			return false;
		}
		printOption=0;
		monthYearId = $('#CaeMonthYearId').val();
		batchId = $('#CaeBatchId').val();
		caeNo = $('#CaeNo').val();
		
		//alert(path_relative+'ContinuousAssessmentExams/caeReportSearch/'+batchId+'/'+monthYearId+'/'+caeNo+'/'+printOption);
		$("#reportDisplay").html("<img src='"+path_relative+"img/loading.gif' alt='CAE data Loading...'/>");
		$.ajax({
			url: path_relative+'ContinuousAssessmentExams/caeReportSearch/'+batchId+'/'+monthYearId+'/'+caeNo+'/'+printOption,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#reportDisplay").html(data);
			}
		});
	});
	
/*	$(".js-cae-calculate").click(function(){
		alert("hello");
	});*/
	
	$(".js-mod-batch").change(function(){
		batchId = $(".js-mod-batch").val();
		//alert(path_relative+'ContinuousAssessmentExams/findProgramByBatch/'+batchId);
		$.ajax({
			url: path_relative+'ContinuousAssessmentExams/findProgramByBatch/'+batchId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#program").html(data);
			}
		});
	});
	
	$(".js-cae-approve").click(function(){
		caeId = $("#CaeId").val();
		//CAEPostModel
		postModel = $("#CaePostModel").val();
		//alert(caeId);
		//alert(path_relative+'ContinuousAssessmentExams/approveInternal/'+caeId+'/'+postModel);
		$.ajax({
			url: path_relative+'ContinuousAssessmentExams/approveInternal/'+caeId+'/'+postModel,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				alert("Approved");
				$(".js-cae-approve").attr('disabled', 'disabled');

			}
		});
	});
	
	$(".js-caept-approve").click(function(){
		caeId = $("#ProfessionalTrainingId").val();
		//CAEPostModel
		postModel = $("#ProfessionalTrainingPostModel").val();
		//alert(path_relative+'ProfessionalTrainings/approveInternal/'+caeId+'/'+postModel);
		$.ajax({
			url: path_relative+'ProfessionalTrainings/approveInternal/'+caeId+'/'+postModel,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				alert("Approved");
				$(".js-cae-approve").attr('disabled', 'disabled');

			}
		});
	});
	
	$(".js-reval-dummy-mismatch").click(function(){
		varAjax=1;
		if ($(".js-month-year").val() == "") {
			alert("Choose month year");
			return false;
		}
		else {
			monthYear = $(".js-month-year").val();
		}
		if ($("#RevaluationDummyApprovalDummyNumber").val() == "") {
			alert("Enter dummy number");
			return false;
		}
		else {
			startRange = $("#RevaluationDummyApprovalDummyNumber").val();
		}
		$("#revalDummyResult").html("<img src='../img/loading.gif' alt='Checking...'/>");
		//alert(path_relative+'DummyMarks/getDummyDiffMarks/'+monthYear+'/'+startRange+'/'+varAjax);
		$.ajax({
			url: path_relative+'RevaluationDummyMarks/getDummyDiffMarks/'+monthYear+'/'+startRange+'/'+varAjax,
			type: 'POST',
		    dataType: 'HTML',
		 	success : function(data) {
		 		//alert(data);
		 		$("#revalDummyResult").html(data);
		    },
		    error : function(data) {
		    	//alert(data);
		    	$("#revalDummyResult").html("There is no data mismatch and data is already synchronized");
		    	alert($.parseJSON(data.responseText).val);
		    },
		});
	});
/*	$(".js-practical-category").change(function(){
		batchId=$("#PracticalReportBatchId").val();
		academicId=$("#PracticalReportAcademicId").val();
		programId=$("#StudentProgramId").val();
		monthyearId=$(".js-month-year").val();
		optionId=$("#PracticalReportOptionId").val();
		//alert(path_relative+'EsePracticals/getMarks/'+batchId+'/'+academicId+'/'+programId+'/'+monthyearId+'/'+optionId);
		$.ajax({
			url: path_relative+'EsePracticals/getMarks/'+batchId+'/'+academicId+'/'+programId+'/'+monthyearId+'/'+optionId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#marksDisplay").html(data);

			}
		});
	});*/
	
	$(".js-cae-practical-approve").click(function(){
		caeId = $("#CaePracticalCaeId").val();
		//alert(path_relative+'CaePracticals/approveCaePractical/'+caeId);
		$.ajax({
			url: path_relative+'CaePracticals/approveCaePractical/'+caeId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				alert("Internal approved");
				$(".js-cae-practical-approve").attr('disabled', 'disabled');

			}
		});
	});
	
	$(".js-ese-practical-approve").click(function(){
		caeId = $("#EsePracticalCaeId").val();
		//alert(path_relative+'CaePracticals/approveCaePractical/'+caeId);
		$.ajax({
			url: path_relative+'EsePracticals/approveEsePractical/'+caeId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				alert("Practical approved");
				$(".js-ese-practical-approve").attr('disabled', 'disabled');

			}
		});
	});
	
	$(".js-mod-cae").click(function(){
		/*var optionValue = [];
		$('input[name=js-option]:checked').map(function() {
			optionValue.push($(this).val());
		});*/
		//optionValue = optionValue;
		//alert(optionValue);
		
		var batchId='0';
		var programId='0';
		var monthYearId='0';
		var from='0';
		var to='0';
		
		if($(".js-mod-batch").val()){
			batchId = $(".js-mod-batch").val();
		}
		if($(".js-program").val()){
			programId = $(".js-program").val();
		}
		if($(".js-mod-monthyear").val()){
			monthYearId = $(".js-mod-monthyear").val();
		}
		if($(".js-mod-from").val()){
			from = $(".js-mod-from").val();
		}
		if($(".js-mod-to").val()){
			to = $(".js-mod-to").val();
		}
		//alert(monthYearId+" "+batchId+" "+programId+" "+from+" "+to);
		urlPath = path_relative+'ContinuousAssessmentExams/moderateCaeSearch/'+monthYearId+'/'+batchId+'/'+programId+'/'+from+'/'+to;
		//alert(urlPath);
		$.ajax({		
			url: urlPath,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#js-moderate-cae").html(data);
			}
		});
		
	});
	
	$(".js-cae-add").click(function(){
		batchId = $(".js-batch").val();
		academicId = $(".js-academic").val();
		programId = $(".js-program").val();
		monthYearId = $(".js-month-year").val();
		$url = path_relative+'ContinuousAssessmentExams/addCae';
		
	});

	$(".js-course-type").change(function(){

		var batchId='0';
		var programId='0';
		var semesterId='0';
		var courseTypeId='0';
		
		if($(".js-mod-batch").val()){
			batchId = $(".js-batch").val();
		}
		if($(".js-mod-program").val()){
			programId = $(".js-program").val();
		}
		if($(".js-semester").val()){
			semesterId = $(".js-semester").val();
		}
		if($(".js-course-type").val()){
			from = $(".js-course-type").val();
		}
		
		
		semesterId=$(".js-semester").val();
		batchId=$(".js-batch").val();
		programId=$(".js-program").val();
		courseTypeId=$(".js-course-type").val();
		//alert(path_relative+'Caes/findCoursesByProgram/'+programId+'/'+batchId+'/'+semesterId+'/'+courseTypeId);
		$.ajax({
		url: path_relative+'Caes/findCoursesByProgram/'+programId+'/'+batchId+'/'+semesterId+'/'+courseTypeId,
		type: 'POST',
	    dataType: 'HTML',
		success: function(data, txtStatus) {
			//alert(data);
			$(".course").html(data);
			strExplode = urlCae.split('/');
			currentModel = strExplode[2];
			if(currentMethod == "caeAssignment") {
				$("#course_mapping_id").removeClass("js-course");
				$("#course_mapping_id").addClass("js-course-cae");
			}
			initCAE();
		}
		});
		
		//alert(path_relative+'Caes/getCourseTypes/'+courseTypeId);
		courseTypeId = $(".js-course-type").val();
		$.ajax({		
			url: path_relative+'Caes/getCourseTypes/'+courseTypeId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#coursetypes").html(data);
			}
		});

	});
	
	$("#StudentCheckbox").click(function(){
		alert(this.value);
	});
	
	$(".js-tw").change(function(){
		//alert(this.name+' '+this.id);
		var str = this.id;
		var res = str.substr(15);
		//alert(res);
		$(".innerRadioButton"+res).attr("disabled",false);
		if($(".StudentCheckbox"+res).attr("disabled",false)) {
			alert($(".innerRadioButton"+res).attr("disabled"));
			//$(".innerRadioButton"+res).attr("disabled",true);
			$(".innerRadioButton78").attr("disabled",true);
			
		}
	});
	
	$(".js-tmp-ese-get").click(function() {
		my_id = $("#ModerationMonthYearId").val();
		reg_no = $("#ModerationRegistrationNumber").val();
		//alert(path_relative+'EsePracticals/getCourses/'+my_id+'/'+reg_no);
		$.ajax({		
			url: path_relative+'EsePracticals/getCourses/'+my_id+'/'+reg_no,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#courses").html(data);
			}
		});
	});
	$(".js-tmp-ese-marks").change(function() {
		my_id = $("#ModerationMonthYearId").val();
		reg_no = $("#ModerationRegistrationNumber").val();
		cm_id = $(this).val();
		student_id = $("#student_id").val();
		//alert(my_id+' '+reg_no+' '+cm_id);
		//alert(path_relative+'EsePracticals/getCourseMarks/'+my_id+'/'+reg_no+'/'+cm_id+'/'+student_id);
		$.ajax({		
			url: path_relative+'EsePracticals/getCourseMarks/'+my_id+'/'+student_id+'/'+cm_id+'/',
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#marks").html(data);
			}
		});
	});
	
	$(".js-individual").click(function(){
		my_id = $("#ModerationMonthYearId").val();
		reg_no = $("#ModerationRegistrationNumber").val();
		$("#marks").html("");
		$("#marks").html("<img src='"+path_relative+"img/loading.gif' alt='Loading...'/>");
		//alert(path_relative+'Students/modifyIndividualUser/'+my_id+'/'+reg_no+'/');
		$.ajax({		
			url: path_relative+'Students/modifyIndividualUser/'+my_id+'/'+reg_no+'/',
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#marks").html(data);
			}
		});
	});
	
	$(".js-ind").click(function(){
		regNo = $("#StudentRegistrationNumber").val();
		//alert(regNo);
		$("#stuName").html("<img src='"+path_relative+"img/loading.gif' alt='Loading...'/>");
		$("#stuSemester").html("<img src='"+path_relative+"img/loading.gif' alt='Loading...'/>");
		$("#course").html("<img src='"+path_relative+"img/loading.gif' alt='Loading...'/>");
		$("#course_details").html("<img src='"+path_relative+"img/loading.gif' alt='Loading...'/>");
		$.ajax({
			url: path_relative+'Students/stuInfo/'+regNo+'/name',
			type: 'POST',
			dataType: 'HTML',
			success : function(data, txtStatus) {
			//	alert(data);
				$("#stuName").html(data);
				//triggerSemester(regNo);
			}
		});
		$.ajax({
			url: path_relative+'Students/stuInfo/'+regNo+'/semester',
			type: 'POST',
			dataType: 'HTML',
			async: true,
			success : function(data, txtStatus) {
				//alert(data);
				$("#stuSemester").html(data);
			}
		});
		$.ajax({
			url: path_relative+'Students/stuId/'+regNo+'/id',
			type: 'POST',
			dataType: 'HTML',
			async: true,
			success : function(data, txtStatus) {
				//alert(data);
				$("#stuSemester").html(data);
			}
		});
	});
	
	$(".js-withdrawal").click(function(){
		my_id = $("#WithdrawalMonthYearId").val();
		reg_no = $("#WithdrawalRegistrationNumber").val();
		//alert(path_relative+'Students/modifyIndividualUser/'+my_id+'/'+reg_no+'/');
		$.ajax({		
			url: path_relative+'Students/withdrawalSearch/'+my_id+'/'+reg_no+'/',
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#courses").html(data);
			}
		});
	});
	
	$(".js-abs").click(function(){
		reg_no = $("#ABSRegistrationNumber").val();
		//alert(path_relative+'AuthorizedBreaks/absSearch/'+reg_no+'/');
		$.ajax({		
			url: path_relative+'StudentAuthorizedBreaks/absSearch/'+reg_no+'/',
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#courses").html(data);
			}
		});
	});
	
	$(".js-arrear-theory").click(function(){
		exam_month_year_id = $("#TheoryMonthYearId").val();
		//alert(path_relative+'TheoryArrears/arrearData/'+exam_month_year_id+'/');
		$("#result").html("<img src='"+path_relative+"img/loading.gif' alt='Loading...'/>");
		$.ajax({		
			url: path_relative+'TheoryArrears/arrearData/'+exam_month_year_id+'/',
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#result").html(data);
			}
		});
	});
	
	$(".js-arrear-pt").click(function(){ 
		exam_month_year_id = $("#PTMonthYearId").val();
		//alert(path_relative+'TheoryArrears/arrearData/'+exam_month_year_id+'/');
		$("#result").html("<img src='"+path_relative+"img/loading.gif' alt='Loading...'/>");
		$.ajax({		
			url: path_relative+'PTArrears/arrearData/'+exam_month_year_id+'/',
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#result").html(data);
			}
		});
	});
	
	$(".js-arrear-practical").click(function(){
		exam_month_year_id = $("#PracticalMonthYearId").val();
		//alert(path_relative+'AuthorizedBreaks/absSearch/'+reg_no+'/');
		$("#result").html("<img src='"+path_relative+"img/loading.gif' alt='Loading...'/>");
		$.ajax({		
			url: path_relative+'Arrears/esePracticalSearch/'+exam_month_year_id+'/',
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#result").html(data);
			}
		});
	});
	
	$(".js-arrear-theory-practical").click(function(){
		exam_month_year_id = $("#PracticalMonthYearId").val();
		//alert(path_relative+'AuthorizedBreaks/absSearch/'+reg_no+'/');
		$("#result").html("<img src='"+path_relative+"img/loading.gif' alt='Loading...'/>");
		$.ajax({		
			url: path_relative+'Arrears/eseTheoryPracticalSearch/'+exam_month_year_id+'/',
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#result").html(data);
			}
		});
	});
	
	$(".js-arrear-practical1").click(function(){
		exam_month_year_id = $("#PracticalMonthYearId").val();
		//alert(path_relative+'AuthorizedBreaks/absSearch/'+reg_no+'/');
		$("#result").html("<img src='"+path_relative+"img/loading.gif' alt='Loading...'/>");
		$.ajax({		
			url: path_relative+'Arrears/practicalSearch/'+exam_month_year_id+'/',
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#result").html(data);
			}
		});
	});
	
	$(".js-pt").click(function(){
		batch_id = $(".js-batch").val();
		program_id = $(".js-program").val();
		month_year_id = $(".js-month-year").val();
		$("#result").html("<img src='"+path_relative+"img/loading.gif' alt='Loading...'/>");
		$.ajax({		
			url: path_relative+'CaePts/indexSearch/'+batch_id+'/'+program_id+'/'+month_year_id+'/',
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#result").html(data);
			}
		});
	});
});

//JQuery document ready ends here
function computeTotal(ese) {
	if ($("#internal_practical_new_mark").val() > $("#max_cae_mark").val()) {
		alert('Mark cannot be greater than '+$("#max_cae_mark").val());
		oldcae = $("#internal_practical_marks").val();
		$("#internal_practical_new_mark").val(oldcae);
		return false;
	}
	if ($("#external_practical_new_mark").val() > $("#max_ese_mark").val()) {
		alert('Mark cannot be greater than '+$("#max_ese_mark").val());
		oldese = $("#external_practical_marks").val();
		$("#external_practical_new_mark").val(oldese);
		return false;
	}
	
	if (($("#internal_practical_new_mark").val()=='a') || ($("#internal_practical_new_mark").val()=='A')) internal=0;
	else internal=$("#internal_practical_new_mark").val();
	
	if (ese=='a' || ese=='A') external=0;
	else external=ese;
	
	cae = parseInt(internal);
	ese = parseInt(external);
	total = cae+ese;
	//alert(cae+' '+ese+' '+total);
	$("#student_new_mark").val(total);
}

function computeIndividual(cm_id, ass_option, new_mark, max_value, k) {
	//alert(new_mark+' '+max_value);
	
	if ((ass_option == 'cae') && (new_mark > max_value)) {
		alert("Marks cannot exceed "+max_value);
		$("#cont_ass_marks_new_"+cm_id+'_'+k).val($("#cont_ass_marks_"+cm_id+'_'+k).val());
		return false;
	}
	if ((ass_option == 'ese') && (new_mark > max_value)) {
		alert("Marks cannot exceed "+max_value);
		$("#ese_new_marks_"+cm_id).val($("#ese_marks_"+cm_id).val());
		return false;
	}
	student_id = $("#student_id").val();
	month_year_id = $("#ModerationMonthYearId").val();
	var inputdata = $('#cm_id_'+cm_id+' :input').serializeArray();
	//alert(inputdata);
	inputdata.push({name: 'cm_id', value: cm_id});
	//inputdata.push({name: 'course_type_id', value: course_type_id});
	inputdata.push({name: 'student_id', value: student_id});
	inputdata.push({name: 'month_year_id', value: month_year_id});
	inputdata.push({name: 'ass_option', value: ass_option});
	//console.info(response);
	$.each(inputdata, function(i, field){
		//$("#resultdata").append(field.name + ":" + field.value + "\n");
    });
    //alert(inputdata);
	$.ajax({
		data: inputdata,
		url: "editCaeMarks/",
		type : "POST",
		dataType: "json",   // Expected response type
	    cache: false,
	    success: function(response, status) {
	        //alert(response.internal);
	        $("#internal_exam_marks_"+response.cm_id).val(response.internal);
	        $("#ese_new_marks_"+response.cm_id).val(response.ese);
	        $("#student_marks_"+response.cm_id).val(response.total);
	        $("#result_"+response.cm_id).html(response.result);
	    	//alert ("Success");
	    },
	    error: function(response, status) {
	        //alert('Error! response=' + response + " status=" + status);
	    }
	});

}

function computePractical(cm_id, ass_option, new_mark, max_value) {
	//alert(cm_id+' '+' '+ass_option+' '+new_mark+' '+max_value);
	
	if ((ass_option == 'cae') && (new_mark > max_value)) {
		alert("Marks cannot exceed "+max_value);
		$("#pr_cae_"+cm_id+'_'+k).val($("#pr_cae_old_"+cm_id).val());
		return false;
	}
	if ((ass_option == 'ese') && (new_mark > max_value)) {
		alert("Marks cannot exceed "+max_value);
		$("#pr_ese_"+cm_id).val($("#pr_ese_old"+cm_id).val());
		return false;
	}
	var inputdata = $('#cm_id_'+cm_id+' :input').serializeArray();
	
	student_id = $("#student_id").val();
	month_year_id = $("#ModerationMonthYearId").val();
	inputdata.push({name: 'cm_id', value: cm_id});
	inputdata.push({name: 'student_id', value: student_id});
	inputdata.push({name: 'month_year_id', value: month_year_id});
	inputdata.push({name: 'ass_option', value: ass_option});
	
	//console.info(response);
	$.each(inputdata, function(i, field){
        //$("#resultdata").append(field.name + ":" + field.value + "\n");
    });
    
	$.ajax({
		data: inputdata,
		url: "editPractical/",
		type : "POST",
		dataType: "json",   // Expected response type
	    cache: false,
	    success: function(response, status) {
	        //alert(response.internal);
	    	$("#cae_"+response.cm_id).val(response.internal);
	        $("#ese_"+response.cm_id).val(response.ese);
	        $("#student_mark_"+response.cm_id).val(response.total);
	        $("#result_"+response.cm_id).html(response.result);
	    	//alert ("Success");
	    },
	    error: function(response, status) {
	        //alert('Error! response=' + response + " status=" + status);
	    }
	});

}

function computeBatchTransfer(cm_id, ass_option, new_mark, max_value) {
	//alert(cm_id+' '+' '+ass_option+' '+new_mark+' '+max_value);
	if ($("#StudentMarkMonthYearId"+cm_id).val()=='') {
		alert("Select Month Year");
		$("#StudentMarkCaeMarks"+cm_id).val()='';
		$("#StudentMarkEseMarks"+cm_id).val()='';
		return false;
	}
	if ((ass_option == 'cae') && (parseInt(new_mark) > parseInt(max_value))) { 
		//alert(ass_option+" "+new_mark+" "+max_value);
		alert("Marks cannot exceed "+max_value);
		$("#StudentMarkCaeMarks"+cm_id).val();
		return false;
	}
	if ((ass_option == 'ese') && (parseInt(new_mark) > parseInt(max_value))) {
		alert("Marks cannot exceed "+max_value);
		$("#StudentMarkEseMarks"+cm_id).val();
		return false;
	}
	//ese = $("#StudentMarkEseMarks"+cm_id).val();
	//cae = $("#StudentMarkCaeMarks"+cm_id).val();
	//total = $("#StudentMarkMarks_"+cm_id).val();
	var inputdata = $('#cm_id_'+cm_id+' :input').serializeArray();
	//alert(inputdata);
	student_id = $("#StudentMarkStudentId").val();
	month_year_id = $("#StudentMarkMonthYearId"+cm_id).val();
	btfou = $("#StudentMarkBtfou").val();
	uid = $("#StudentMarkUid").val();
	total = $("#StudentMarkMarks_"+cm_id).val();
	status = $("#StudentMarkStatus"+cm_id).val();
	inputdata.push({name: 'cm_id', value: cm_id});
	inputdata.push({name: 'btfou', value: btfou});
	inputdata.push({name: 'uid', value: uid});
	inputdata.push({name: 'student_id', value: student_id});
	inputdata.push({name: 'ass_option', value: ass_option});
	inputdata.push({name: 'total', value: total});
	inputdata.push({name: 'status', value: status});
	//console.info(response);
	$.each(inputdata, function(i, field){
		//$("#resultdata").append(field.name + ":" + field.value + "\n");
    });
	
	$.ajax({
		data: inputdata,
		url: "../batchTransferEditCaeMarks/",
		type : "POST",
		dataType: "json",   // Expected response type
	    cache: false,
	    
	    success: function(response, status) {
	        //alert(response.total);
	    	$("#StudentMarkCaeMarks"+response.cm_id).val(response.cae);
	        $("#StudentMarkEseMarks"+response.cm_id).val(response.ese);
	        $("#StudentMarkMarks_"+response.cm_id).html(response.total);
	        $("#StudentMarkStatus"+response.cm_id).html(response.result);
	    	//alert ("Success");
	    },
	    error: function(response, status) {
	        //alert('Error! response=' + response + " status=" + status);
	    }
	});

}

function checkPublishedData(semester_id) {
	student_id = $("#student_id").val();
	data='';
	//alert(semester_id+' '+student_id);
	//alert(path_relative+'Students/checkPublishedData/'+student_id+'/'+semester_id+'/');
	//alert($("#sem"+semester_id).html());
	$.ajax({
		url: path_relative+'StudentAuthorizedBreaks/chkPublishedData/'+student_id+'/'+semester_id+'/',
		type: 'POST',
		dataType: 'HTML',
		contentType: 'application/html; charset=utf-8',
		success: function(data, txtStatus) {
			//alert(data);
			if (data != '1') { 
				$("#sem"+semester_id).html(data);
				$("#checkbox"+semester_id).prop('checked', false);
			}
			else if (data == '1') {
				//$("#sem"+semester_id).html(data);
			}
			else if (data == '2') {
				$("#sem"+semester_id).html("");
			}
			//$("input."+getId).prop('checked', true);	
		},
		error: function(e) {
			console.log(e);
		}
	});
}
function eseSearch(){ 
	var examMonth = "-";var batchId = "-";var Academic = "-";var programId = "-";var exam_type = "-";
	if($('#monthyears').val()){
		var examMonth = $('#monthyears').val();
	}if($('#batch_id').val()){
		var batchId = $('#batch_id').val();
	}if($('#academic_id').val()){
		var Academic = $('#academic_id').val();
	}if($('#StudentProgramId').val()){	
		var programId = $('#StudentProgramId').val();
	}if($('#exam_type').val()){
		var exam_type = $('#exam_type').val();
	}	
	if(examMonth == ''){alert("Select Exam Month Year");return false;}
	if(batchId == ''){alert("Select Batch");return false;}
	if(Academic == ''){alert("Select Program");return false;}
	if(programId == ''){alert("Select Specialisation");return false;}
	if(exam_type == ''){alert("Select Exam Type");return false;}
	
	$("#indexEseSearch").html("<img src='"+path_relative+"img/loading.gif' alt='ESE Loading...'/>");
	//alert(path_relative+'EndSemesterExams/getDummyFinalMarks/'+examMonth+'/'+batchId+'/'+Academic+'/'+programId+'/'+exam_type+'/');
	$.ajax({
		url: path_relative+'EndSemesterExams/getDummyFinalMarks/'+examMonth+'/'+batchId+'/'+Academic+'/'+programId+'/'+exam_type+'/',
		type: 'POST',
		dataType: 'HTML',
		contentType: 'application/html; charset=utf-8',
		success: function(data, txtStatus) {
			$("#indexEseSearch").html(data);				
		},
		error: function(e) {
			console.log(e);
		}
	});
}

function validateMarks() {
	$('.getStudents').each(function(index, obj){
	    marks = data[CAE][marks][index];
	    //alert(index+" "+marks);
	    var regex = new RegExp("^[aA0-9]+$");
	    //var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
	    if (regex.test(marks)) {
	        return true;
	    }
	    e.preventDefault();
	    return false;
	});	
}

function valEditFaculty() {
	if($('#EditFacultyId').val() == '') {
		alert('Choose Faculty');
		$('#EditFacultyId').focus();
		return false;
	}
}

function getMarks(cmId) {
	//alert(cmId+" "+path_relative+'ContinuousAssessmentExams/getMarks/'+cmId);
	$.ajax({		
		url: path_relative+'ContinuousAssessmentExams/getMarks/'+cmId,
		type: 'POST',
	    dataType: 'HTML',
		success: function(data, txtStatus) {
			//alert(data);
			$("#marks").html(data);
		}
	});
}

function toggleOption(str) {
	//alert(str);
	if(str == "Batch") {
		$(".js-mod-program").attr("disabled", false); 
		$(".js-mod-batch").attr("disabled", false);
		
		$(".js-mod-monthyear").attr("disabled", true);
		$(".js-mod-monthyear").val("");
	}
	else if(str == "MonthYear") {
		$(".js-mod-monthyear").attr("disabled", false);
		
		$(".js-mod-program").attr("disabled", true); 
		$(".js-mod-batch").attr("disabled", true);
		$(".js-mod-program").val("");
		$(".js-mod-batch").val("");
	}
}

function test() {
	$(".js-batch").change(function() {
		batchId=$(this).val();
		//alert(batchId);
		$.ajax({		
			url: path_relative+'monthyears/findMonthYearsByBatch/'+batchId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#monthyear").html(data);
			}
		});
	});
}
function jsFormSubmitRedirect(){ 
	$this = $(this);
		var formURL = $this.attr("action");
		 var formData = {};
			var a = $this.serializeArray();			
			$.each(a, function() {			
				if(this.name !== "_method"){
					if (formData[this.name] !== undefined) {
						if (!formData[this.name].push) {
							formData[this.name] = [formData[this.name]];
						}
						formData[this.name].push(this.value || '');
					} else {
						formData[this.name] = this.value || '';
					}
				}
			});		
		$.ajax({
			url : formURL,
			type: "POST",
			data: formData, // data object
			success:function(data) 
			{		
				//$("."+$res_div).html(data);
				if(data !='success'){
					$("#js-load-forms").html(data);
					$(".js-form").submit(jsFormSubmitRedirect);	
					return false;
				}else{
					var $redirectUrl = $this.attr('success_url');
					window.location = path_relative+"../.."+$redirectUrl;
				}	
			}
		});
     return false;
}

function addInternal() {
	if($("#CaeBatchId").val()=='') {
		alert('Choose Batch')
		$('#CaeBatchId').focus();
		return false
	}
	if($("#CaeAcademicId").val()=='') {
		alert('Choose Program')
		$('#CaeAcademicId').focus();
		return false
	}
	if($("#program_id").val()=='') {
		alert('Choose Specialisation')
		$('#program_id').focus();
		return false
	}
	/*if($("#CaeCourseTypeId").val()=='') {
		alert('Choose Course Type')
		$('#CaeCourseTypeId').focus();
		return false
	}*/
	if($("#course_mapping_id").val()=='') {
		alert('Choose Course')
		$('#course_mapping_id').focus();
		return false
	}
	/*if($("input[name='data[caetype]']:checked").length<=0) {
		alert("Choose Course Type")
		return false
	}*/
	//alert($(".js-batch").val()+" "+$(".js-academic").val()+" "+$(".js-program").val()+" "+$(".js-course").val()+" "+$(".js-semester").val()+" "+$(".js-semester").val()+" "+$(".js-marks").val()+"/"+$("#CaeAction.val()"));
	batchId = $(".js-batch").val();
	academicId = $(".js-academic").val();
	programId = $(".js-program").val();
	cmId = $(".js-course-cae").val();
	semesterId = $(".js-semester").val();
	monthYearId = $(".js-month-year").val();
	//alert($(".cae_type").val());
	courseTypeId = $("#CaeCourseTypeId").val();
	choice = $("#displayOptions input[type='radio']:checked").val();
	template = $("#CaeAction").val();
	



	//alert(path_relative+'ContinuousAssessmentExams/addInternals/'+batchId+'/'+academicId+'/'+programId+'/'+cmId+'/'+semesterId+'/'+monthYearId+'/'+template+'/'+courseTypeId);
	return false;
	$.ajax({		
		url: path_relative+'ContinuousAssessmentExams/addInternals/'+batchId+'/'+academicId+'/'+programId+'/'+cmId+'/'+semesterId+'/'+monthYearId+'/'+template+'/'+courseTypeId,
		type: 'POST',
	    dataType: 'HTML',
		success: function(data, txtStatus) {
			//alert(data);
			$(".js-mark").attr('disabled', true);
			//alert(data);
			/*var res = data.split("-");
			if (res[0] == 2 || res[0] == 3) {
				$('.caeAddBtn').attr('disabled','disabled');
			}
			var data = data.replace(res[0]+'-'+res[1]+'-',"");*/
			$("#noOfCAEs").append(data);

		}				

	});
}

function moderateCae(){
	//alert($(".js-mod-sign").val()+" "+$(".js-mod-mark").val());
	$('#modOperator').val($(".js-mod-sign").val());
	$('#modValue').val($(".js-mod-mark").val());
	$('#ContinuousAssessmentExamModerateCaeForm').submit();
}

function stripCaeData(data) {
	var res = data.split("-");
	//alert(res[1]);
	
	if (res[0] == 2 && res[1] >= 1) {
		alert("Maximum Reached");
		$('.caeAddBtn').attr('disabled','disabled');
	}
	else if (res[0] == 3 && res[1] >= 1) {
		alert("Maximum Reached");
		$('.caeAddBtn').attr('disabled','disabled');
	}
	else {
		$('.caeAddBtn').removeAttr('disabled');
	}
	var data = data.replace(res[0]+'-'+res[1]+'-',"");
	return data;
}

function checkMarks() {
	/*var choiceValue = $("input[id^='caetype']:checked").val();
	var marks = 0;
	$("#choice"+choiceValue+" .js-marks").each(function(index) {
		marks=Number($(this).val())+Number(marks);
	});
	if(marks != $(".js-project-max-marks").val()) {
		alert('Marks not equal to 100');
		return false;
	}*/


}

function getEseDisplay(attrId) {
	clas = $('#'+attrId).attr('class');
	clas = clas.split(' ');
	strExplode = urlCae.split('/');
	currentModel = strExplode[2];
	currentMethod = strExplode[3];
	//alert(currentModel+" "+currentMethod);
	var batchId='0';
	var academicId='0';
	var programId='0';
	var monthYearId='0';
	if($(".js-batch").val()){
		batchId = $(".js-batch").val();
	}
	if($(".js-academic").val()){
		academicId = $(".js-academic").val();
	}
	if($(".js-program").val()){
		programId = $(".js-program").val();
	}
	if($(".js-month-year").val()){
		monthYearId = $(".js-month-year").val();
	}
	if(currentModel == "EsePracticals" && currentMethod == "esePracticals") {
		//alert('now '+path_relative+'ContinuousAssessmentExams/caeDisplay/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId);
		$.ajax({
			url: path_relative+'EsePracticals/eseDisplay/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
			    $(".caeDisplay").html(data);
			}
		});
	}
}

function getCaeDisplay(attrId) { 
	//$(".js-cae-display").click(function(){
	//alert($('#'+attrId).attr('class'));
		clas = $('#'+attrId).attr('class');
		clas = clas.split(' ');
	
		//alert(clas[1]);
		
		strExplode = urlCae.split('/');
		currentModel = strExplode[2];
		currentMethod = strExplode[3];
		//alert(currentModel+" "+currentMethod);
		var batchId='0';
		var academicId='0';
		var programId='0';
		var monthYearId='0';
		if($(".js-batch").val()){
			batchId = $(".js-batch").val();
		}
		if($(".js-academic").val()){
			academicId = $(".js-academic").val();
		}
		if($(".js-program").val()){
			programId = $(".js-program").val();
		}
		if($(".js-month-year").val()){
			monthYearId = $(".js-month-year").val();
		}
		//alert(currentModel+" "+currentMethod);
		// shud relook into this line
		//if(currentModel == "ContinuousAssessmentExams" && currentMethod == "findMonthYearByProgram") {
		if(currentModel == "ContinuousAssessmentExams" && (currentMethod == "" || currentMethod == undefined || currentMethod == "theory" || currentMethod == "practical" || currentMethod == "project")) {
			$.ajax({
				url: path_relative+'ContinuousAssessmentExams/caeDisplay/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+currentMethod,
				type: 'POST',
			    dataType: 'HTML',
				//url: '<?php echo Router::url(array('controller'=>'batches','action'=>'findBatchByBatchMode'));?>',
				success: function(data, txtStatus) {
					//alert(data);
				    if(clas[1] == "js-cae-list") {
				    	$(".caeList").html(data);
				    } else if(clas[1] == "js-cae-display") {
				    	$(".caeDisplay").html(data);
				    }
				}
			});
		}
		else if(currentModel == "CaePracticals" && currentMethod == "practical") {
			//alert(path_relative+'CaePracticals/caePracticalDisplay/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+currentMethod);
			$.ajax({
				url: path_relative+'CaePracticals/caePracticalDisplay/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+currentMethod,
				type: 'POST',
			    dataType: 'HTML',
				success: function(data, txtStatus) {
			    	$(".caeDisplay").html(data);
				}
			});
		}
		else if(currentModel == "EsePracticals" && currentMethod == "practical") {
			//alert(path_relative+'EsePracticals/esePracticalDisplay/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+currentMethod);
			$.ajax({
				url: path_relative+'EsePracticals/esePracticalDisplay/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+currentMethod,
				type: 'POST',
			    dataType: 'HTML',
				success: function(data, txtStatus) {
			    	$(".eseDisplay").html(data);
				}
			});
		}
		else if(currentModel == "CaeProjects" && (currentMethod == "index" || currentMethod == undefined)) {
			//alert(path_relative+'CaeProjects/caeProjectDisplay/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+currentMethod);
			$.ajax({
				url: path_relative+'CaeProjects/caeProjectDisplay/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+currentMethod,
				type: 'POST',
			    dataType: 'HTML',
				success: function(data, txtStatus) {
			    	$(".caeDisplay").html(data);
				}
			});
		}
		else if(currentModel == "EseProjects" && (currentMethod == "index" || currentMethod == undefined)) {
			//alert(path_relative+'EseProjects/eseProjectDisplay/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+currentMethod);
			$.ajax({
				url: path_relative+'EseProjects/eseProjectDisplay/'+batchId+'/'+academicId+'/'+programId+'/'+monthYearId+'/'+currentMethod,
				type: 'POST',
			    dataType: 'HTML',
				success: function(data, txtStatus) {
			    	$(".eseDisplay").html(data);
				}
			});
		}
	//});
}

function breadcrumb1(){ 
	$('#breadcrumb1').html($('.breadcrumb1').html());
	$('.breadcrumb1').html("");	
}
function leftMenuSelection(url){
	url = "/sets2015/"+url;
	$($('.submenu li a[href="' + url + '"]')).parent().parent().parent().parent().parent().addClass('open');
	$($('.submenu li a[href="' + url + '"]')).parent().parent().parent().addClass('active');
	
	$($('.submenu li a[href="' + url + '"]')).parent().parent().parent().addClass('open');
	$($('.submenu li a[href="' + url + '"]')).parent().addClass('active');
	
	$($('.studentViewCtp a[href="' + url + '"]')).addClass('current'); // view student tab menu default selection.
}
function PrintDiv() {    
    var divToPrint = document.getElementById('divToPrint');
    var popupWin = window.open('', '_blank', 'width=300,height=300');
    popupWin.document.open();
    popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
    popupWin.document.close();
}
/*function validateCaePractical() {
	if($("#modelMarks").val() == "") {
		alert("Enter CAE marks!");
		$("#modelMarks").focus();
		return false;
	}
	if($("#eseMarks").val() == "") {
		alert("Enter ESE marks!");
		$("#eseMarks").focus();
		return false;
	}
	total = parseInt($("#modelMarks").val())+parseInt($("#eseMarks").val());
	if(total != parseInt($("#maxMarks").val())) {
		alert("Total not matching the maximum mark");
		return false;
	}
	return true;
}*/
function coeSearch(){ 
	var examMonth = "-";var batchId = "-";var Academic = "-";var programId = "-";var exam_type = "-";
	if($('#monthyears').val()){var examMonth = $('#monthyears').val();}
	if($('#batch_id').val()){var batchId = $('#batch_id').val();}
	if($('#academic_id').val()){var Academic = $('#academic_id').val();}
	if(jQuery.type($('#programId').val()) === "undefined"){ 
		if(jQuery.type($('#StudentProgramId').val()) === "undefined"){ 
			if($('#program_id').val()){var programId = $('#program_id').val();}
		} else{
			var programId = $('#StudentProgramId').val();
		}
	}else{
		if($('#programId').val()){var programId = $('#programId').val();}
	}
	//if($('#exam_type').val()){var exam_type = $('#exam_type').val();}

	if(examMonth == '-'){alert("Select Exam Month Year");return false;}
	//alert(path_relative+'Students/coeSearch/'+examMonth+'/'+batchId+'/'+Academic+'/'+programId+'/');
	$("#coeSearch").html("<img src='"+path_relative+"img/loading.gif' alt='Loading...'/>");	
	$.ajax({
		url: path_relative+'Students/coeSearch/'+examMonth+'/'+batchId+'/'+Academic+'/'+programId+'/',
		type: 'POST',
		dataType: 'HTML',
		contentType: 'application/html; charset=utf-8',
		success: function(data, txtStatus) {
			$("#coeSearch").html(data);				
		},
		error: function(e) {
			console.log(e);
		 }
	});
}

function eseValidate(eseValue, i) {
	maxValue = $('.maxEseValue'+i).val();
	if(eseValue > maxValue) {
		alert("Value cannot exceed "+maxValue);
		$('.eseValue'+i).val($('.eseOldValue'+i).val());
		$('.eseValue'+i).focus();
		return false;
	}
}

function RevalDummyMarkAllot(){
	if($('#monthyears').val() == ''){
		alert("Select Exam Month Year");
		return false;
	}
	
	if($('#monthyears').val()){
		$("#tblDummyMarkAllot").html("<img src='"+path_relative+"img/loading.gif' alt='Revaluation Dummy Mark List Record Loading...'/>");	
		$.ajax({
			url: path_relative+'RevaluationDummyMarks/searchMarks/'+$('#monthyears').val(),
			type: 'POST',
			dataType: 'HTML',
			contentType: 'application/html; charset=utf-8',
			success: function(data, txtStatus) { 
				$("#tblDummyMarkAllot").html(data);				
			},
			error: function(e) {
				console.log(e);
			 }
		});
	}
}
function manipulateCumulativeMark(seqNo,curMarks){
	  if(seqNo){
	    var packetNo = Math.floor(seqNo/2);
	    var Mks = "";
	    for(i=((packetNo*20)+1);i<=(packetNo*20)+20;i++){        
	      if(jQuery.type($("#DN"+(seqNo-1)).val()) === "undefined" || (seqNo % 20) == 1){ 
	        $("#packetMark"+seqNo).html(curMarks);
	        return false;
	      }else if((jQuery.type($("#DNM"+(seqNo+1)).val()) === "undefined") || ($("#DNM"+(seqNo+1)).val()) ){
	        Mks = Number(curMarks) + Number($("#packetMark"+(seqNo-1)).html());
	        $("#packetMark"+seqNo).html(Mks);
	        return false;
	      }else{
	        Mks = Number(curMarks) + Number($("#packetMark"+(seqNo-1)).html());
	        $("#packetMark"+seqNo).html(Mks);        
	      } 
	    }
	  }
}
function store(seqNo,Mks,level, revId) {
	  manipulateCumulativeMark(seqNo,Mks);
	  var Mks = Mks.trim();
	  if((seqNo) && (Mks) && (level) && (revId)){
	    $("#msg"+seqNo).removeClass("grey");
	    $("#msg"+seqNo).html("");
	    if(parseInt(Mks) > parseInt($('#max_ese_qp_mark').val())){
	      $("#msg"+seqNo).addClass("grey");
	      $("#msg"+seqNo).html("Mark cannot exceed "+$('#max_ese_qp_mark').val());  
	      return false;
	    }
	    var filter = /^[0-9-+]+$/;
	    if (!filter.test(seqNo)) {
	      $("#msg"+seqNo).addClass("grey");
	      $("#msg"+seqNo).html("Enter Valid Mark");  
	      return false;
	    }   
	    var DNId = "-";
	    if($("#DNId"+seqNo).val() !=''){
	      var DNId = $("#DNId"+seqNo).val();
	    }
	    var autoGenId = "-";
	    if($("#autoGenId"+seqNo).val() !=''){
	      var autoGenId = $("#autoGenId"+seqNo).val();
	    }
	    var rId = "-";
	    if($("#revId"+seqNo).val() !=''){
	      var rId = $("#revId"+seqNo).val();
	    }
	    $.ajax({
	      url: path_relative+'RevaluationDummyMarks/dnToM/'+level+'/'+DNId+'/'+$("#DN"+seqNo).val()+'/'+Mks+'/'+autoGenId+'/'+revId+'/',
	      type: 'POST',
	      dataType: 'HTML',
	      contentType: 'application/html; charset=utf-8',
	      success: function(data, txtStatus) { 
	        if(data){
	          $("#msg"+seqNo).html(data);
	          $("#msg"+seqNo).addClass("grey");
	        }else{
	        
	        }
	      },
	      error: function(e) {
	        console.log(e);
	       }
	    });
	  }
}

function displayRevaluationMark(id, studentId, cmId, examMonthYearId, courseCode) {
	//alert(path_relative+'RevaluationExams/edit/'+id+'/'+studentId+'/'+cmId+'/'+examMonthYearId+'/'+courseCode+'/');
	$.ajax({
		url: path_relative+'RevaluationExams/edit/'+id+'/'+studentId+'/'+cmId+'/'+examMonthYearId+'/'+courseCode+'/',
		type: 'POST',
		success: function(data, txtStatus) {
			//alert(data);
			$("#revalResult").html(data);
			//$('#revalResult').target = "_new"; 
			$("#diffOption").html("");
		},
		error: function(xhr, status, error) {
		  var err = eval("(" + xhr.responseText + ")");
		  //alert(err.Message);
		}
	});
	
	/*$.ajax({
		url: path_relative+'RevaluationExams/edit/'+id+'/'+studentId+'/'+cmId+'/'+examMonthYearId+'/',
        type:"POST",
        data: { id : id , studentId : studentId , cmId : cmId },
        success:function(data){
        	$("#revalResult").html(data);
        },error:function(){ 
            alert("error!!!!");
        }
    });*/ //end of ajax
}
function updateRevaluationMark(id = null,studentId = null,CMId = null, examMonthYearId = null) {
	if($('#new_external_mark').val()){
		$.ajax({
			url: path_relative+'RevaluationExams/update/'+id+'/'+studentId+'/'+CMId+'/'+examMonthYearId+'/'+$('#new_external_mark').val(),
			type: 'POST',
			success: function(data, txtStatus) { alert(data);
			},
			error: function(xhr, status, error) {
			  var err = eval("(" + xhr.responseText + ")");
			  //alert(err.Message);
			}
		});
		 $("#revalResult").html("<img src='"+path_relative+"img/loading.gif' />");	
		 $('.js-reval-moderation').trigger('change');
		 $('.js-reval-display').trigger('click');			
	}
}
function triggerSecond() {
	//alert('test');
	$(".js-reval-fail-moderation").change(function(){
		failOption = this.value;
		//alert(failOption);
		if (failOption == "Pass") {
			$.ajax({
				url: path_relative+'RevaluationExams/revalPassModeration/',
				type: 'POST',
			    dataType: 'HTML',
				success: function(data, txtStatus) {
					//alert(data);
					$("#diffOption").html(data);
				},
			});
		}
		else if (failOption == "Fail") {
			$.ajax({
				url: path_relative+'RevaluationExams/revalFailModeration/',
				type: 'POST',
			    dataType: 'HTML',
				success: function(data, txtStatus) {
					//alert(data);
					$("#diffOption").html(data);
				},
			});
		}
	});
}

function confirmRDN(){
	if($('#maxRow').val()){
		for(i=1;i<=($('#maxRow').val());i++){
			if($("#DNM"+i).html() != ""){
				alert("Please Check Mark for Dummy Number "+$("#DN"+i).val());
				return false;
			}
			if($("#DNM"+i).val() == ""){
				alert("Please Check Mark Entry for Dummy Number "+$("#DN"+i).val());
				return false;
			}
		}
		alert("Thank You For Your Confirmation.");
		window.location = path_relative+'RevaluationDummyMarks/marks';
	}	
}

function displayResult(revaluationType) {
	var examMonthYearId = $('#RevaluationExamMonthYearId').val();
	var examOption = $('#RevaluationExamOption').val();
	var failedOption = $('#failed_option').val();
	if(examMonthYearId == ''){alert("Select Exam Month Year");$('#RevaluationExamMonthYearId').focus();return false;}
	if(examOption == ''){alert("Select applied for option");$('#RevaluationExamOption').focus();return false;}
	if(failedOption == ''){alert("Select an option");$('#failed_option').focus();return false;}
	
	//alert(path_relative+'RevaluationExams/displayResult/'+examMonthYearId+'/'+examOption+'/'+failedOption+'/'+diffFrom+'/'+diffTo+'/'+revaluationType+'/');
	$.ajax({
		url: path_relative+'RevaluationExams/displayResult/'+examMonthYearId+'/'+examOption+'/'+failedOption+'/'+diffFrom+'/'+diffTo+'/'+revaluationType+'/',
		type: 'POST',
		dataType: 'HTML',
		contentType: 'application/html; charset=utf-8',
		success: function(data, txtStatus) {
			$("#revalResult").html(data);	
			$("#errormsg").html("");
		},
		error: function(e) {
			console.log(e);
		}
	});
	
}

function beforeRevaluationModeration(revaluationType) {
	var eseFrom = $('#ese_diff_greater_than_br').val();
	var eseTo = $('#ese_diff_lesser_than_br').val();
	var totalFrom = $('#total_diff_greater_than_br').val();
	var totalTo = $('#total_diff_lesser_than_br').val();
	
	var examMonthYearId = $('#RevaluationExamMonthYearId').val();
	var examOption = $('#RevaluationExamOption').val();
	var failedOption = $('#failed_option').val();
	if(examMonthYearId == ''){alert("Select Exam Month Year");$('#RevaluationExamMonthYearId').focus();return false;}
	if(examOption == ''){alert("Select applied for option");$('#RevaluationExamOption').focus();return false;}
	if(failedOption == ''){alert("Select an option");$('#failed_option').focus();return false;}
	if(eseFrom == ''){alert("Enter ESE difference from");$('#ese_diff_greater_than_br').focus();return false;}
	if(eseTo == ''){alert("Enter ESE difference to");$('#ese_diff_lesser_than_br').focus();return false;}
	if(totalFrom == ''){alert("Enter Total difference from");$('#total_diff_greater_than_br').focus();return false;}
	if(totalTo == ''){alert("Enter Total difference to");$('#total_diff_lesser_than_br').focus();return false;}
	//revaluationType=this.value;
	
	$("#revalResult").html("<img src='"+path_relative+"img/loading.gif' alt='ESE Loading...'/>");
	//alert(path_relative+'RevaluationExams/beforeRevaluation/'+examMonthYearId+'/'+examOption+'/'+failedOption+'/'+diffFrom+'/'+diffTo+'/'+revaluationType+'/');
	$.ajax({
		url: path_relative+'RevaluationExams/beforeRevaluation/'+examMonthYearId+'/'+examOption+'/'+failedOption+'/'+diffFrom+'/'+diffTo+'/'+revaluationType+'/',
		type: 'POST',
		dataType: 'HTML',
		contentType: 'application/html; charset=utf-8',
		success: function(data, txtStatus) {
			$("#revalResult").html(data);	
			$("#errormsg").html("");
		},
		error: function(e) {
			console.log(e);
		}
	});
}

function afterRevaluationModeration(revaluationType) {
	var eseFrom = $('#ese_diff_greater_than_ar').val();
	var eseTo = $('#ese_diff_lesser_than_ar').val();
	var totalFrom = $('#total_diff_greater_than_ar').val();
	var totalTo = $('#total_diff_lesser_than_ar').val();
	
	var examMonthYearId = $('#RevaluationExamMonthYearId').val();
	var examOption = $('#RevaluationExamOption').val();
	var failedOption = $('#failed_option').val();
	if(examMonthYearId == ''){alert("Select Exam Month Year");$('#RevaluationExamMonthYearId').focus();return false;}
	if(examOption == ''){alert("Select applied for option");$('#RevaluationExamOption').focus();return false;}
	if(failedOption == ''){alert("Select an option");$('#failed_option').focus();return false;}
	if(eseFrom == ''){alert("Enter ESE difference from");$('#ese_diff_greater_than_ar').focus();return false;}
	if(eseTo == ''){alert("Enter ESE difference to");$('#ese_diff_lesser_than_ar').focus();return false;}
	if(totalFrom == ''){alert("Enter Total difference from");$('#total_diff_greater_than_ar').focus();return false;}
	if(totalTo == ''){alert("Enter Total difference to");$('#total_diff_lesser_than_ar').focus();return false;}
	//revaluationType=this.value;
	
	$("#revalResult").html("<img src='"+path_relative+"img/loading.gif' alt='ESE Loading...'/>");
	//alert(path_relative+'RevaluationExams/afterRevaluation/'+examMonthYearId+'/'+examOption+'/'+failedOption+'/'+diffFrom+'/'+diffTo+'/'+revaluationType+'/');
	$.ajax({
		url: path_relative+'RevaluationExams/afterRevaluation/'+examMonthYearId+'/'+examOption+'/'+failedOption+'/'+diffFrom+'/'+diffTo+'/'+revaluationType+'/',
		type: 'POST',
		dataType: 'HTML',
		contentType: 'application/html; charset=utf-8',
		success: function(data, txtStatus) {
			$("#revalResult").html(data);	
			$("#errormsg").html("");
		},
		error: function(e) {
			console.log(e);
		}
	});
}

function displayBeforeRevaluationResult(revaluationType) {
	//alert('test');
	var ese_from = $('#ese_diff_greater_than_br').val();
	var ese_to = $('#ese_diff_lesser_than_br').val();
	var total_from = $('#total_diff_greater_than_br').val();
	var total_to = $('#total_diff_lesser_than_br').val();
	
	var exam_month_year_id = $('#RevaluationExamMonthYearId').val();
	var exam_option = $('#RevaluationExamOption').val();
	var failed_option = $('#failed_option').val();
	
	if(exam_month_year_id == ''){alert("Select Exam Month Year");$('#RevaluationExamMonthYearId').focus();return false;}
	if(exam_option == ''){alert("Select applied for option");$('#RevaluationExamOption').focus();return false;}
	if(failed_option == ''){alert("Select an option");$('#failed_option').focus();return false;}
	
	if(ese_from == ''){alert("Enter ESE difference from");$('#ese_diff_greater_than_br').focus();return false;}
	if(ese_to == ''){alert("Enter ESE difference to");$('#ese_diff_lesser_than_br').focus();return false;}
	if(total_from == ''){alert("Enter Total difference from");$('#total_diff_greater_than_br').focus();return false;}
	if(total_to == ''){alert("Enter Total difference to");$('#total_diff_lesser_than_br').focus();return false;}
	
	urlOption = path_relative+'RevaluationExams/getRevaluationResult/'+exam_month_year_id+'/'+exam_option+'/'+failed_option+'/'+revaluationType+'/'+ese_from+'/'+ese_to+'/'+total_from+'/'+total_to+'/';
	//alert(path_relative+'RevaluationExams/getRevaluationResult/'+exam_month_year_id+'/'+exam_option+'/'+failed_option+'/'+revaluationType+'/'+ese_from+'/'+ese_to+'/'+total_from+'/'+total_to+'/');
	$.ajax({
		url: urlOption,
		type: 'POST',
	    dataType: 'HTML',
		success: function(data, txtStatus) {
			$("#revalResult").html(data);
		},
	});
}

function displayAfterRevaluationResult(revaluationType) {
	//alert('testing');
	var ese_from = $('#ese_diff_greater_than_ar').val();
	var ese_to = $('#ese_diff_lesser_than_ar').val();
	var total_from = $('#total_diff_greater_than_ar').val();
	var total_to = $('#total_diff_lesser_than_ar').val();
	
	var exam_month_year_id = $('#RevaluationExamMonthYearId').val();
	var exam_option = $('#RevaluationExamOption').val();
	var failed_option = $('#failed_option').val();
	
	if(exam_month_year_id == ''){alert("Select Exam Month Year");$('#RevaluationExamMonthYearId').focus();return false;}
	if(exam_option == ''){alert("Select applied for option");$('#RevaluationExamOption').focus();return false;}
	if(failed_option == ''){alert("Select an option");$('#failed_option').focus();return false;}
	
	if(ese_from == ''){alert("Enter ESE difference from");$('#ese_diff_greater_than_ar').focus();return false;}
	if(ese_to == ''){alert("Enter ESE difference to");$('#ese_diff_lesser_than_ar').focus();return false;}
	if(total_from == ''){alert("Enter Total difference from");$('#total_diff_greater_than_ar').focus();return false;}
	if(total_to == ''){alert("Enter Total difference to");$('#total_diff_lesser_than_ar').focus();return false;}
	
	urlOption = path_relative+'RevaluationExams/getRevaluationResult/'+exam_month_year_id+'/'+exam_option+'/'+failed_option+'/'+revaluationType+'/'+ese_from+'/'+ese_to+'/'+total_from+'/'+total_to+'/'+revaluationType;
	alert(path_relative+'RevaluationExams/getRevaluationResult/'+exam_month_year_id+'/'+exam_option+'/'+failed_option+'/'+revaluationType+'/'+ese_from+'/'+ese_to+'/'+total_from+'/'+total_to+'/');
	$("#revalResult").html("");
	$("#revalResult").html("<img src='/sets2015/img/loading.gif' alt='Loading. Please wait...'/>");
	$.ajax({
		url: urlOption,
		type: 'POST',
	    dataType: 'HTML',
		success: function(data, txtStatus) {
			$("#revalResult").html(data);
		},
	});
}

function testing(new_cm_id, old_cm_id, jsn_old_courses) {
	//alert(old_cm_id+' '+new_cm_id+' '+jsn_old_courses);
	tagValue = "<input type='hidden' name='data[MapStudents][equivalent]["+old_cm_id+"]' id='crse"+old_cm_id+"' value='"+new_cm_id+"'>";
	//alert(tagValue);
	//alert(jsn_old_courses);
	$.each(jsn_old_courses, function(cm_id, course_code) {
		//alert(cm_id+' '+course_code+' '+old_cm_id+' '+new_cm_id);
		//alert($("#crse"+cm_id).val());
		
		if ($("#crse"+cm_id).val() == new_cm_id) {
			//alert('found');
			alert(cm_id);
			delTagValue = "<input type='hidden' name='data[MapStudents][equivalent]["+cm_id+"]' id='crse"+cm_id+"' value=''>";
			// alert(delTagValue);
			$("#hdnEquivalent"+cm_id).html(delTagValue);
		}
    });
	$("#hdnEquivalent"+old_cm_id).html(tagValue);
}

function test2(cm_id, jsn_old_courses) {
	//alert(cm_id+' '+jsn_old_courses);
	return false;
	tagValue = "<input type='hidden' name='data[MapStudents][equivalent]["+old_cm_id+"]' id='crse"+old_cm_id+"' value='"+new_cm_id+"'>";
	//alert(tagValue);
	//alert(jsn_old_courses);
	$.each(jsn_old_courses, function(cm_id, course_code) {
		//alert(cm_id+' '+course_code+' '+old_cm_id+' '+new_cm_id);
		//alert($("#crse"+cm_id).val());
		
		if ($("#crse"+cm_id).val() == new_cm_id) {
			//alert('found');
			//alert(cm_id);
			delTagValue = "<input type='hidden' name='data[MapStudents][equivalent]["+cm_id+"]' id='crse"+cm_id+"' value=''>";
			// alert(delTagValue);
			$("#hdnEquivalent"+cm_id).html(delTagValue);
		}
    });
	$("#hdnEquivalent"+old_cm_id).html(tagValue);
}

function deleteTransferRecord(transfer_id) {
	//alert(transfer_id);
}

function lateJoinerCourse() {
	$(".js-lj-course").change(function(){
		cmId=$(this).val();
		//alert($("#student_id").val());
		
		batchId = $("#batch_id").val();
		programId = $("#program_id").val();
		semesterJoined = $("#semester_joined").val();
		studentId = $("#student_id").val();
		regNum = $("#reg_num").val();
		semesterId = $(this).val();
		
		/*$("#monthYearResult").html("<img src='/sets2015/img/loading.gif' alt='Mapping courses and students. Please wait...'/>");
		$.ajax({
			url: path_relative+'Students/getMonthYears/'+batchId+'/'+programId+'/'+semesterJoined+'/'+cmId+'/'+regNum,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#monthYearResult").html(data);
			}
		});*/
		/*$("#semesterResult").html("<img src='/sets2015/img/loading.gif' alt='Mapping courses and students. Please wait...'/>");
		$.ajax({
			url: path_relative+'Students/getSemesters/'+batchId+'/'+programId+'/'+semesterJoined+'/'+semesterId+'/'+cmId,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				//alert(data);
				$("#semesterResult").html(data);
			}
		});*/
		
	});
}

function chkBoxMapStudents(getId){
	if($('#'+getId).is(':checked') == true){
	    $("input."+getId).prop('checked', true);	     
	}else{
		$("input."+getId).prop('checked', false);    
	}
}

function userPrivileges(strVar,seqId){
	if($('#'+strVar+seqId).is(':checked') == true){
	    $("."+strVar+seqId).prop('checked', true);	     
	}else{
		$("."+strVar+seqId).prop('checked', false);    
	}
}

function PracticalAttendanceSearch(){
		$("#listExamAttendances").html("");
		$exam_month_year_id = $("#EAMonthyears").val();
		$exam_type = $("#EAExamType").val();
		$("#listExamAttendances").html("<img src='"+path_relative+"img/loading.gif' />");
		$.ajax({
			url: path_relative+'Arrears/search/'+$exam_month_year_id+'/'+$exam_type,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				$("#listExamAttendances").html(data);
			}
		});
}

function ArrearPracCAEEntry($seq,$markEntry,$student_id,$exam_month_year_id){
	//alert($('#CaeOldMark'+$seq).val());
	//alert($('#cae_practical_id'+$seq).val());
	if ($('#CaeOldMark'+$seq).val() == '') {
		cae_old_mark=0;
	}
	else {
		cae_old_mark=$('#CaeOldMark'+$seq).val();
	}
	if(parseInt($('#maxCAEMark').html())< parseInt($markEntry)){		
		$("#spanArrearPracCAEEntry"+$seq).addClass("grey");
		$("#spanArrearPracCAEEntry"+$seq).html("Invalid Mark Entry");		
	}else{
		$("#spanArrearPracCAEEntry"+$seq).removeClass("grey");
		$("#spanArrearPracCAEEntry"+$seq).html("");
		$.ajax({
			url: path_relative+'Arrears/saveCAEPracticalMarks/'+cae_old_mark+'/'+$('#cae_practical_id').val()+'/'+$markEntry+'/'+$student_id+'/'+$exam_month_year_id,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
			}
		});
	}
}

function ArrearTheoryCAEEntry($seq,$markEntry,$student_id,$exam_month_year_id){
	//alert($('#CaeOldMark'+$seq).val());
	//alert($('#cae_practical_id'+$seq).val());
	//alert(path_relative+'TheoryArrears/saveTheoryCAEMarks/'+$('#CaeOldMark'+$seq).val()+'/'+$('#cm_id').val()+'/'+$markEntry+'/'+$student_id+'/'+$exam_month_year_id);
	if(parseInt($('#maxCAEMark').html())< parseInt($markEntry)){		
		$("#spanArrearPracESEEntry"+$seq).addClass("grey");
		$("#spanArrearPracESEEntry"+$seq).html("Invalid Mark Entry");		
	}else{
		$("#spanArrearPracESEEntry"+$seq).removeClass("grey");
		$("#spanArrearPracESEEntry"+$seq).html("");
		$.ajax({
			url: path_relative+'TheoryArrears/saveTheoryCAEMarks/'+$('#CaeOldMark'+$seq).val()+'/'+$('#cm_id').val()+'/'+$markEntry+'/'+$student_id+'/'+$exam_month_year_id,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
			}
		});
	}
}

function ArrearPTCAEEntry($seq,$markEntry,$student_id,$exam_month_year_id,$cae_pt_id){ alert($seq); 
	//alert($('#CaeOldMark'+$seq).val());
	//alert($('#cae_practical_id'+$seq).val());
	alert(path_relative+'PTArrears/savePTCAEMarks/'+$('#CaeOldMark'+$seq).val()+'/'+$('#cm_id').val()+'/'+$markEntry+'/'+$student_id+'/'+$exam_month_year_id+'/'+$cae_pt_id);
	var caeOldMark = '-';
	if ($('#CaeOldMark'+$seq).val() != '')  caeOldMark = $('#CaeOldMark'+$seq).val();
	
	if(parseInt($('#maxCAEMark').html())< parseInt($markEntry)){		
		$("#spanArrearPracESEEntry"+$seq).addClass("grey");
		$("#spanArrearPracESEEntry"+$seq).html("Invalid Mark Entry");		
	}else{
		$("#spanArrearPracESEEntry"+$seq).removeClass("grey");
		$("#spanArrearPracESEEntry"+$seq).html("");
		$.ajax({
			url: path_relative+'PTArrears/savePTCAEMarks/'+caeOldMark+'/'+$('#cm_id').val()+'/'+$markEntry+'/'+$student_id+'/'+$exam_month_year_id+'/'+$cae_pt_id,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
			}
		});
	}
}

function ArrearPracESEEntry($seq,$markEntry,$student_id,$exam_month_year_id){
	//alert($('#CaeOldMark'+$seq).val());
	//alert($('#cae_practical_id'+$seq).val());
	if(parseInt($('#maxESEMark').html())< parseInt($markEntry)){		
		$("#spanArrearPracESEEntry"+$seq).addClass("grey");
		$("#spanArrearPracESEEntry"+$seq).html("Invalid Mark Entry");		
	}else{
		$("#spanArrearPracESEEntry"+$seq).removeClass("grey");
		$("#spanArrearPracESEEntry"+$seq).html("");
		$.ajax({
			url: path_relative+'Arrears/saveESEPracticalMarks/'+$('#ese_practical_id').val()+'/'+$markEntry+'/'+$student_id+'/'+$exam_month_year_id,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
			}
		});
	}
}

function PrjCAEntry($seq,$markEntry,$student_id,$exam_month_year_id){
	//alert($('#CaeOldMark'+$seq).val());
	//alert($('#cae_practical_id'+$seq).val());
	cae_project_id = $("#CaeProjectId").val();
	if(parseInt($markEntry) > parseInt($('#CaeProjectMaxMarks').val())){ 
		$("#spanProjectCAEEntry"+$seq).addClass("grey");
		$("#spanProjectCAEEntry"+$seq).html("Invalid Mark Entry");		
	}else{ 
		$("#spanProjectCAEEntry"+$seq).removeClass("grey");
		$("#spanProjectCAEEntry"+$seq).html("");
		$.ajax({
			url: path_relative+'CaeProjects/saveCAEProjectMarks/'+cae_project_id+'/'+$markEntry+'/'+$student_id+'/'+$exam_month_year_id,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				if (data == true) {
					$("#spanProjectCAEEntry"+$seq).html("Mark Saved");
				}
				else {
					$("#spanProjectCAEEntry"+$seq).html("Mark Not Saved");
				}
			}
		});
	}
}

function PrjESEntry($seq,$markEntry,$student_id,$exam_month_year_id){
	//alert($('#CaeOldMark'+$seq).val());
	//alert($('#cae_practical_id'+$seq).val());
	ese_project_id = $("#EseProjectId").val();
	if(parseInt($markEntry) > parseInt($('#EseProjectMaxMarks').val())){ 
		$("#spanProjectESEEntry"+$seq).addClass("grey");
		$("#spanProjectESEEntry"+$seq).html("Invalid Mark Entry");		
	}else{ 
		$("#spanProjectESEEntry"+$seq).removeClass("grey");
		$("#spanProjectESEEntry"+$seq).html("");
		$.ajax({
			url: path_relative+'EseProjects/saveESEProjectMarks/'+ese_project_id+'/'+$markEntry+'/'+$student_id+'/'+$exam_month_year_id,
			type: 'POST',
		    dataType: 'HTML',
			success: function(data, txtStatus) {
				if (data == true) {
					$("#spanProjectESEEntry"+$seq).html("Mark Saved");
				}
				else {
					$("#spanProjectESEEntry"+$seq).html("Mark Not Saved");
				}
			}
		});
	}
}

function checkMarkEnryEmpty(){
	var confirmStatus = 1;
	$("input.dummy").each(function (index){
		var currId = $(this).attr('id');
		//alert(currId); 
		if($('#'+currId).val() == ''){
			confirmStatus = 2;
			alert("Enter Valid Mark");
			$('#'+currId).focus();
			return false;
		}
	});
	if(confirmStatus == 1){
		alert("Thank You For Your Confirmation");
		window.location = path_relative+'Arrears/arrear';
	}
}

function validateAbs() {
	semester_id = $("#semester_id").val();
	//alert(semester_id);
	for(i=1; i<=semester_id; i++) {
		if ($("#checkbox"+i).attr('checked') && $("#month_year_id"+i).val()==="") {
			alert('Select MonthYear!!!');
			return false;
		}
	}
	
}
function audit_course(student_id) {
	month_year_id = $("#StudentAuditCourseMonthYearId").val();
	//alert(path_relative+'StudentAuditCourses/getAuditCourseDetails/'+month_year_id+'/'+student_id+'/');
	$.ajax({
		url: path_relative+'StudentAuditCourses/getAuditCourseDetails/'+month_year_id+'/'+student_id+'/'+true,
		type: 'POST',
	    dataType: 'HTML',
		success: function(data, txtStatus) {
			$("#result").html(data);
		}
	});	
}
function getAuditCourses(month_year_id, student_id) {
	$.ajax({
		url: path_relative+'StudentAuditCourses/get_audit_courses/'+month_year_id+'/'+student_id+'/',
		type: 'POST',
	    dataType: 'HTML',
		success: function(data, txtStatus) {
			$("#courses").html(data);
		}
	});	
}
function delAuditCourse(audit_course_id, student_id) {
	//alert(audit_course_id);
	$.ajax({
		url: path_relative+'StudentAuditCourses/delete/'+audit_course_id+'/'+student_id+'/',
		type: 'POST',
	    dataType: 'HTML',
		success: function(data, txtStatus) {
			//$("#courses").html(data);
		}
	});	
}
function confirmTransferMarks(reg_num){ 
	var confirmStatus = 1;
	$(".month_year_id").each(function (index){
		var currId = $(this).attr('id'); 
		var cmId = currId.match(/\d+/);
		
		if($("#StudentMarkMonthYearId"+cmId).val() != '' && ($("#StudentMarkCaeMarks"+cmId).val()=="" || $("#StudentMarkEseMarks"+cmId).val()=="")) {
			confirmStatus = 2;
			alert("Enter Valid Mark");
			if ($("#StudentMarkCaeMarks"+cmId).val()=='') {
				$("#StudentMarkCaeMarks"+cmId).focus(); 
				return false; 
			}
			if ($("#StudentMarkEseMarks"+cmId).val()=='') {
				$("#StudentMarkEseMarks"+cmId).focus(); 
				return false; 
			}
		}
	});
	if(confirmStatus == 1){
		alert("Thank You For Your Confirmation");
		window.location = path_relative+'Students/transferCourses/'+reg_num;
	}
}
