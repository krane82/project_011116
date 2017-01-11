<main class="page-content">
	<div class="page-inner">
		<div id="main-wrapper">
			<div class="row">
				<div class="col-md-3 center">
					<div class="login-box">
						<p class="logo-name text-lg text-center">Lead Point</p>
						<p class="text-center m-t-md">Please login into your account.</p>
						<form class="m-t-md" action="" method="post">
							<div class="form-group">
								<input type="text" class="form-control" name="login" placeholder="Email" required>
							</div>
							<div class="form-group">
								<input type="password" class="form-control" name="password" placeholder="Password" required>
							</div>
							<input type="submit" value="Login" name="btn" class="btn btn-success btn-block">
						</form>
            <?php extract($data); ?>
            <?php if($login_status=="access_granted") { ?>
              <p style="color:green">Success</p>
            <?php } elseif($login_status=="access_denied") { ?>
              <p style="color:red" class="text-center">Wrong login or password</p>
            <?php } ?>
						<p class="text-center m-t-xs text-sm">2016 &copy; Lead Point.</p>
					</div>
				</div>
			</div><!-- Row -->
		</div><!-- Main Wrapper -->
	</div><!-- Page Inner -->
</main><!-- Page Content -->


