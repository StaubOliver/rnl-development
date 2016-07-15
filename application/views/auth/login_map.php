<div id="login-wrapper" class="container">
	<div id="login">
		<!--<img id="login-branding" src="data:image/gif;base64,R0lGOD lhCwAOAMQfAP////7+/vj4+Hh4eHd3d/v7+/Dw8HV1dfLy8ubm5vX19e3t7fr 6+nl5edra2nZ2dnx8fMHBwYODg/b29np6eujo6JGRkeHh4eTk5LCwsN3d3dfX 13Jycp2dnevr6////yH5BAEAAB8ALAAAAAALAA4AAAVq4NFw1DNAX/o9imAsB tKpxKRd1+YEWUoIiUoiEWEAApIDMLGoRCyWiKThenkwDgeGMiggDLEXQkDoTh CKNLpQDgjeAsY7MHgECgx8YR8oHwNHfwADBACGh4EDA4iGAYAEBAcQIg0Dk gcEIQA7" alt="Branding">-->

		<h4>Please login</h4>

		<div id="login-info-message"><?php echo $message;?></div>

		<?php echo form_open("auth/login_map");?>
			<p>
				<?php //echo lang('login_identity_label', 'identity');?>
				<?php $identity['class'] = 'u-full-width'; ?>
				<?php $identity['placeholder'] = 'username'; ?>
				<?php echo form_input($identity);?>
			</p>

			<p>
				<?php //echo lang('login_password_label', 'password');?>
				<?php $password['class'] = 'u-full-width'; ?>
				<?php $password['placeholder'] = 'password'; ?>
				<?php echo form_input($password);?>
			</p>

			<!--
			<p id="login-remember-me">
				<?php //echo lang('login_remember_label', 'remember');?>
				<?php //echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
			</p>
			-->

			<p>
				<?php echo form_submit('submit', lang('login_submit_btn'), 'class="u-full-width button-primary"');?>
			</p>
		<?php echo form_close();?>

		<p id="login-forgot-password"><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>
		
		<p id="login-create-user">Not a member? <a href="create_user">Join us</a></p>
	</div>
</div>