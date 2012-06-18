<li data-app-id="<?php echo $data->id; ?>" data-expires-on="<?php echo $data->createTime + 600; ?>">
<?php echo $data->length; ?> hour appointment with <?php echo $data->hairdresser->profile->getFullName(); ?> on <?php echo $data->date; ?> at <?php echo $data->hour; ?>
 <a>Confirm appointment!</a> You still have <span class="countdown"></span>
</li>