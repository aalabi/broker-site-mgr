<?php
require_once 'controller.php';
echo $headTag;
?>
<body class="hold-transition theme-primary bg-img" style="background-image: url(<?= Functions::getImageUrl(true) ?>home-bg.jpg)">	
	<div class="container h-p100">
		<div class="row align-items-center justify-content-md-center h-p100">	
			
			<div class="col-12">
				<div class="row justify-content-center no-gutters">
					<div class="col-lg-5 col-md-5 col-12">
						<div class="bg-white rounded30 shadow-lg">
							<div class="content-top-agile p-20 pb-0">
								<img src="<?= Functions::getImageUrl(true) ?>logo.png" alt="" width="100">
								<p class="mb-0">Sign in to continue to <?= $settings->sitename ?> Site Manager</p>		
                                <?= $responseTag ?>					
							</div>
                            
							<div class="p-40">
								<form action="processor.php" method="post">
                                    <?= WebPage::getCSRFTokenInputTag() ?>
									<div class="form-group">
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<span class="input-group-text bg-transparent"><i class="ti-email"></i></span>
											</div>
											<input type="email" required class="form-control pl-15 bg-transparent" name="email" placeholder="email">
										</div>
									</div>
									<div class="form-group">
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<span class="input-group-text  bg-transparent"><i class="ti-lock"></i></span>
											</div>
											<input type="password" required class="form-control pl-15 bg-transparent" name="password" placeholder="password">
										</div>
									</div>
									  <div class="row">
										<!-- /.col -->
										<div class="col-12">
										 <div class="fog-pwd text-center">
											<a href="<?= $settings->machine->url.$settings->machine->backend ?>/password-reset" class="hover-warning"><i class="ion ion-locked"></i> Forgot Password?</a><br>
										  </div>
										</div>
										<!-- /.col -->
										<div class="col-12 text-center">
										  <button type="submit" class="btn mt-10 al-bg-success">SIGN IN</button>
										</div>
										<!-- /.col -->
									  </div>
								</form>		
							</div>						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <?= $footerScriptTag ?>
</body>
</html>
