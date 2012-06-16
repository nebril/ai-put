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
		<?php echo $form->labelEx($model,'isConfirmed'); ?>
		<?php echo $form->textField($model,'isConfirmed'); ?>
		<?php echo $form->error($model,'isConfirmed'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hour'); ?>
		<?php echo $form->textField($model,'hour'); ?>
		<?php echo $form->error($model,'hour'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'length'); ?>
		<?php echo $form->textField($model,'length'); ?>
		<?php echo $form->error($model,'length'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'createTime'); ?>
		<?php echo $form->textField($model,'createTime',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'createTime'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->