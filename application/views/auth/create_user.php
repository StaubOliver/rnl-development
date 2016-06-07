<div id="register-wrapper" class="container">
	<div id="register">
		<h4>Please register</h4>

		<div id="register-info-message"><?php echo $message;?></div>

		<?php echo form_open("auth/create_user");?>
			
				<script>
					var points = $.parseJSON(sessionStorage.getItem('rnlpoints'));
					
					console.log(points);
					
					if(points) {
						$('#register form').prepend('<input type="hidden" name="queue_total" value="'+points.queue_total+'">');
						$('#register form').prepend('<input type="hidden" name="review_total" value="'+points.review_total+'">');
					}
				</script>
			
				<p>
					<?php $first_name['class'] = 'u-full-width'; ?>
					<?php $first_name['placeholder'] = 'First name'; ?>
					<?php echo form_input($first_name);?>
				</p>

				<p>
					<?php $last_name['class'] = 'u-full-width'; ?>
					<?php $last_name['placeholder'] = 'Last name'; ?>
					<?php echo form_input($last_name);?>
				</p>

				<p>
					<?php $email['class'] = 'u-full-width'; ?>
					<?php $email['placeholder'] = 'Email address'; ?>
					<?php echo form_input($email);?>
				</p>

				<p>
					<?php $password['class'] = 'u-full-width'; ?>
					<?php $password['placeholder'] = 'Password'; ?>
					<?php echo form_input($password);?>
				</p>

				<p>
					<?php $password_confirm['class'] = 'u-full-width'; ?>
					<?php $password_confirm['placeholder'] = 'Confirm password'; ?>
					<?php echo form_input($password_confirm);?>
				</p>

				<!--<div class="g-recaptcha" data-sitekey="6LegQQgTAAAAACvca0jCMdZ36CYT_FfuIdfp19Tp"></div>-->

				<p><?php echo form_submit('submit', lang('create_user_submit_btn'), 'class="u-full-width button-primary"');?></p>

				<p><small>By registering you agree to our <a href="/#terms">terms and conditions</a>.</small></p>
		<?php echo form_close();?>
	</div>
</div>