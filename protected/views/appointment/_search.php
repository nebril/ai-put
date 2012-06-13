<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hairdresserId'); ?>
		<?php echo $form->textField($model,'hairdresserId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'clientId'); ?>
		<?php echo $form->textField($model,'clientId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'start'); ?>
		<?php echo $form->textField($model,'start'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hours'); ?>
		<?php echo $form->textField($model,'hours'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'isConfirmed'); ?>
		<?php echo $form->textField($model,'isConfirmed'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->