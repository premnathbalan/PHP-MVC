<?php 
//echo $caeCount;
if ($caeCount > 0 && isset($caeCount)) {
for ($i=1; $i<=$caeCount; $i++) {
	echo "CAE ". $i."</br>";
}
}
//echo $this->Html->link('<i class="ace-icon fa fa-plus-square"></i>'. 'Add CAE', array("controller"=>"Caes",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false, 'title'=>'Add'));
?>