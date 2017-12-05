<div class="students form">
	<?php echo $this->Form->create('Revaluation', array('type' => 'file')); ?>
	
	<div class="bgFrame1">
	 <div class=" col-sm-12">
		<div class=" col-sm-5">
	   		<?php echo $this->Form->input('month_year_id', array('label' => 'MonthYear', 'options'=>$monthyears, 'empty' => __("----- Select Month Year-----"), 'required'=>'required')); ?>
	   	</div>
	   	
	   	<div class=" col-sm-3">
	   		<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('SEARCH'),array('type'=>'submit', 'name'=>'Submit','value'=>'Submit','class'=>'btn'));?>
	   		<?php echo $this->Form->button('<i class="ace-icon fa fa-file-pdf-o bigger-110"></i>'.__('PDF'),array('type'=>'submit','name'=>'Submit','value'=>'PDF','class'=>'btn js-exam-att-submit'));?>
	   		<?php echo $this->Form->end(); ?>
	   	</div>
	   </div>	
   </div>
   
      <?php  if($results){?>
	<table border='1' class='display tblOddEven' style='margin:5px;'>
		<tr>
			<th>S.No.</th>
			<th>SUBJECT CODE</th>
			<th>COURSE NAME</th>
			<th>DUMMY NO. START</th>
			<th>DUMMY NO. END</th>
			<th>BOARD</th>
			<th>TOTAL</th>
			
		</tr>
		<?php $pageBreak = 0;$serialNo = 1;$packetCnt = 20;$i=1;$CCSeq = 1;$startDummyNumber = "";for($p=0;$p<count($results);$p++){	
			$dummyNo4Digit = substr($results[$p]['EndSemesterExam']['dummy_number'],0,4);
			if(empty($startDummyNumber)){
				$totalCnt = 0;
				for($z=$p;$z<count($results);$z++){ 
					if(@($results[$p]['CourseMapping']['Course']['course_code'] == $results[$z]['CourseMapping']['Course']['course_code']) && ($dummyNo4Digit == substr($results[$z]['EndSemesterExam']['dummy_number'],0,4))){
						$totalCnt++;
					}				
				}
			}		
			
			if(empty($startDummyNumber)){
				$startDummyNumber = $results[$p]['EndSemesterExam']['dummy_number'];
			}
			$endDummyNumber = $results[$p]['EndSemesterExam']['dummy_number'];
			$endDNFlag = 1;		
			
			for($z=$p;$z<count($results);$z++){ 
				if((isset($results[$z+1]['CourseMapping']['Course']['course_code'])) && ($results[$z]['CourseMapping']['Course']['course_code'] == $results[$z+1]['CourseMapping']['Course']['course_code']) && ($endDNFlag == 1) && ($dummyNo4Digit == substr($results[$z+1]['EndSemesterExam']['dummy_number'],0,4))){
					if(($CCSeq < $packetCnt )){ 
						$endDNFlag = 2; 
						$endDummyNumber = "";
					}else if(($CCSeq % $packetCnt)){
						$endDummyNumber = "";$results[$z+1]['EndSemesterExam']['dummy_number'];
					}else{
						$endDNFlag = 2; 
						$endDummyNumber = $results[$z]['EndSemesterExam']['dummy_number'];
					}					
				}else{
					$endDNFlag = 2;
				}
				
			} if($endDummyNumber){
			?>
		<tr>			
			<td><?php echo $serialNo;?></td>
			<td><?php echo $results[$p]['CourseMapping']['Course']['course_code'];?></td>  
			<td><?php echo $results[$p]['CourseMapping']['Course']['course_name'];?></td>
			<td align='center'><?php echo $startDummyNumber;?></td>
			<td align='center'><?php echo $endDummyNumber;?></td>
			<td><?php echo $results[$p]['CourseMapping']['Course']['board'];?></td>
			<td><?php
			if($totalCnt == 0){ 
				echo "1";
			}else if($packetCnt < $totalCnt){  
				echo $packetCnt;
			}else{  
				echo $totalCnt;
			}
			?></td>			
		</tr>
		<?php $pageBreak++;$serialNo++;$totalCnt=0;$startDummyNumber = "";}
		$i++;
		if((isset($results[$p+1]['CourseMapping']['Course']['course_code'])) && ($results[$p]['CourseMapping']['Course']['course_code'] == $results[$p+1]['CourseMapping']['Course']['course_code']) && ($dummyNo4Digit == substr($results[$p+1]['EndSemesterExam']['dummy_number'],0,4))){
			$CCSeq++;
		}else{
			$CCSeq = 1;
		}
		if(isset($results[$p+1]['CourseMapping']['Course']['course_code']) && ($results[$p]['CourseMapping']['Course']['course_code'] != $results[$p+1]['CourseMapping']['Course']['course_code']) || $i == 21){
		$endDNFlag = 1;
		$i=1;}
		if($pageBreak == 4){$pageBreak = 0;
		?>
		<tr>
			<td colspan="8"></td>
		</tr>
		<?php }
		}?>
   </table>
   <?php }?>
</div>

<script>leftMenuSelection('Revaluations/revaluationsCoverPageReport');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REVALUATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> REVALUATION COVER PAGE REPORT <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Revaluations",'action' => 'revaluationsCoverPageReport'),array('data-placement'=>'left','escape' => false)); ?>
</span>