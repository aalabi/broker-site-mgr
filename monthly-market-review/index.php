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
				<div class="content-header d-flex justify-content-between align-items-center">
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
                    <button
                        type="button"
                        class="btn btn-primary"
                        data-toggle="modal"
                        data-target="#modal-center">
                        <i class="ti-plus" aria-hidden="true"></i>
                        Add
                    </button>
				</div>

				<!-- Main content -->
				<section class="content">
                    <div class="row">
                        <div class="col-12">
                            <?= $responseTag ?>
                        </div>
                    </div>

                    <div class="row">
						<div class="col-12 mr-16">                            
							<div class="box">
                                <div class="box-header with-border">
                                    <h4 class="box-title">Monthly Market Review</h4>
                                </div>
                                <div class="box-body p-15">
                                    <div class="table-responsive">
                                        <table id="tickets" class="table mt-0 table-hover no-wrap table-borderless" data-page-size="10">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Period</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?= $tr ?>                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                                                
                        <!-- Modal -->
                        <div class="modal center-modal fade" id="modal-center" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Monthly Market Review</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="processor.php" method="post" id="add" enctype="multipart/form-data">
                                            <?= WebPage::getCSRFTokenInputTag() ?>
                                            <input type="hidden" name="action" value="create">
                                            <div class="form-group">
                                                <label>Month *</label>
                                                <select required name="month" class="form-control select2" style="width: 100%;">
                                                    <?= $monthOption ?>                                                    
                                                </select>
                                            </div> 
                                            <div class="form-group">
                                                <label>Year</label>
                                                <select required name="year" class="form-control select2" style="width: 100%;">
                                                    <?= $yearOption ?>
                                                </select>
                                            </div>  
                                            <div class="form-group">
                                                <label>Report</label>
                                                <div>
                                                <input required name="report" type="file" class="form-control">
                                                </div>
                                                <small>PDF max. 5MB</small>
                                            </div>
                                            <!-- /.box-body -->
                                        </form>
                                    </div>
                                    <div class="modal-footer modal-footer-uniform">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" form="add" class="btn btn-primary float-right">
                                        Save changes
                                    </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.modal -->

                        <?= $deleteModal ?>
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