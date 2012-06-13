<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'appointment-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'hairdresserId'); ?>
		<?php echo $form->textField($model,'hairdresserId'); ?>
		<?php echo $form->error($model,'hairdresserId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'clientId'); ?>
		<?php echo $form->textField($model,'clientId'); ?>
		<?php echo $form->error($model,'clientId'); ?>
	</div>

    <div class="row">
		<?php echo $form->labelEx($model,'start'); ?>
		<?php
		$dateFormat = 'dd.mm.yy';
		$this->widget('CJuiDateTimePicker',array(
	        'model'=>$model,
	        'attribute'=>'start',
	        'mode'=>'datetime',
	        'options'=>array(
	                'ampm' => false,
	                'hourMin' => 10,//max(10, (int)date('G')),
	                'hourMax' => 20,
                    'showMinute' => false,
	                'timeFormat' => 'h:00',
	                'dateFormat' => $dateFormat,
	                'minDate' => 0,
	                /*'onSelect' => 'js:function(dateText, inst){ //TODO
	                    console.log(inst);
	                    longDate = $.datepicker.parseDate("'.$dateFormat.'", dateText.match(/\d\d\.\d\d\.\d\d\d\d/)[0]);
	                    console.log(longDate.getDay());
	                
	                    if(longDate.getDay() == 0) {
                            $("#Appointment_start").datetimepicker({
	                            hourMin : 0,
	                            hourMax : 0,
	                            showHour : false
                            });
	                    }else if(longDate.getDay() == 6) {
	                        $("#Appointment_start").datetimepicker({
	                            hourMin : 10,
	                            hourMax : 16,
	                            showHour : true
                            });
	                    }else {
	                        $("#Appointment_start").datetimepicker({
	                            hourMin : 10,
	                            hourMax : 20,
	                            showHour : true
                            });
	                    }
	                }'*/
	        ),
		    'language' => '',
		));
        ?>
		<?php echo $form->error($model,'start'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hours'); ?>
		<?php echo $form->textField($model,'hours'); ?>
		<?php echo $form->error($model,'hours'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'isConfirmed'); ?>
		<?php echo $form->textField($model,'isConfirmed'); ?>
		<?php echo $form->error($model,'isConfirmed'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->