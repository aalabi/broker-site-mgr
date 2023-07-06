<?php
require_once 'controller.php';
echo $headTag;
?>
<body class="hold-transition theme-primary bg-img" style="background-image: url(<?= Functions::getImageUrl() ?>home-bg.jpg)">	
	<div class="container h-p100">
		<div class="row align-items-center justify-content-md-center h-p100">				
			<div class="col-12">
				<div class="row justify-content-center no-gutters">
					<div class="col-lg-5 col-md-5 col-12">
						<div class="bg-white rounded30 shadow-lg">
							<div class="content-top-agile p-20 pb-0">
								<img src="<?= Functions::getImageUrl() ?>aquila-eye-green-removebg.png" alt="" width="200">								
                                <h3 class="mb-0 text-normal"><?= $pageTitle ?></h3>									
                                <?= $responseTag ?>					
							</div>                            
							<div class="p-40">
                                <form action="processor.php" method="post">
                                    <?= WebPage::getCSRFTokenInputTag() ?>                                    
									<div class="form-group">
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<span class="input-group-text bg-transparent"><i class="ti-key"></i></span>
											</div>
											<input <?= $readOnlyToken ?> type="text" name='token' class="form-control pl-15 bg-transparent" value='<?= $resetToken ?>' placeholder="Enter token">
										</div>
									</div>
                                    <?= $emailTag ?>
									<div class="form-group">
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<span class="input-group-text bg-transparent"><i class="ti-lock"></i></span>
											</div>
											<input <?= $readOnlyPassword ?> type="password" name="password" class="form-control pl-15 bg-transparent" placeholder="Enter password">
										</div>
									</div>
									<div class="form-group">
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<span class="input-group-text bg-transparent"><i class="ti-lock"></i></span>
											</div>
											<input <?= $readOnlyPassword ?> type="password" name="confirmPassword" class="form-control pl-15 bg-transparent" placeholder="Confirm password">
										</div>
									</div>
									  <div class="row">
										<div class="col-12 text-center">
							                <button type="submit" class="btn al-bg-success margin-top-10">Proceed</button>
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
