<div id="forgot-wrapper" class="container">
	<div id="forgot">
		<h4>Forgot your password?</h4>

		<div id="forgot-info-message"><?php echo $message;?></div>

		<?php echo form_open("auth/forgot_password");?>

		<p>
			<?php $email['class'] = 'u-full-width'; ?>
			<?php $email['placeholder'] = 'Email address'; ?>
			<?php echo form_input($email);?>
		</p>

      	<p><?php echo form_submit('submit', lang('forgot_password_submit_btn'), 'class="u-full-width button-primary"');?></p>

		<?php echo form_close();?>
	</div>
</div>
