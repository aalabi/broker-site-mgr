<?php
require_once 'controller.php';
echo $headTag;
?>
<body class="hold-transition light-skin sidebar-mini theme-primary sidebar-collapse">
	<div class="wrapper">
        <?= $headerTag ?>
        <?= $aSideMenuTag ?>
        
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<div class="container-full">
				<!-- Content Header (Page header) -->
				<div class="content-header">
					<div class="d-flex align-items-center">
						<div class="mr-auto">
							<h4 class="page-title"><?= $pageTitle ?></h4>
                            <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>                                    
                                    <li class="breadcrumb-item active" aria-current="page"><?= $subPageTitle ?></li>
                                </ol>
                            </nav>
                        </div>
						</div>

					</div>
				</div>

				<!-- Main content -->
				<section class="content">
                    <div class="row">
                        <div class="col-12">
                            <?= $responseTag ?>
                        </div>
                    </div>

					<div class="row">
						<div class="col-12 col-xl-4 mr-16">                            
							<div class="box">
                                <div class="box-header with-border">
                                    <h4 class="box-title">Left Title</h4>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    left bar content coming soon
                                </div>
					        </div>
						</div>
						
                        <div class="col-12 col-xl-8">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h4 class="box-title">Right Title</h4>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    right bar content coming soon
                                </div>
                                <!-- /.box-body -->
                            </div>					
					    </div>
                    </div>	

                    <div class="row">
						<div class="col-12 mr-16">                            
							<div class="box">
                                <div class="box-header with-border">
                                    <h4 class="box-title">Full Length Title</h4>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    full lenght content coming soon
                                </div>
					        </div>
						</div>
                    </div>		
				</section>
				<!-- /.content -->
			</div>
		</div>
		<!-- /.content-wrapper -->

		<?= $footerCredit ?>
	</div>
	<!-- ./wrapper -->

	<?= $chatTags ?>
    <?= $footerScriptTag ?>
</body>
</html>