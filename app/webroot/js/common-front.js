function academicProgram (academicId){
		$.ajax({			
				 $("#programs").html(data);
			}
		});
}
function attendanceSearch(typeVal,printMode,idPage,courseTypeId){ 
	if($('#batch_id').val() == ''){
		alert("Select Batch");
		return false;
	}
	if($('#academic_id').val() == ''){
		alert("Select Program");
		return false;
	}
	if($('#StudentProgramId').val() == ''){
		alert("Select Specialisation");
		return false;
	}
	if($('#monthYears').val() == ''){
		alert("Select Month Year");
		return false;
	}
	var Batch = $('#batch_id').val();
		$("#listAttendance").html("<img src='"+path_relative+"img/loading.gif' alt='Attendance Form Loading...'/>");	
		$.ajax({				
			url: path_relative+'Attendances/search_list/',	
			type: 'POST',	
			data: 'type='+typeVal+'&Academic='+Academic+'&Program='+Program+'&Batch='+Batch+'&MonthYears='+MonthYears+
		    dataType: 'HTML',	
			success: function(data, txtStatus) {	
				 $("#listAttendance").html(data);	
			}					
		});
}
