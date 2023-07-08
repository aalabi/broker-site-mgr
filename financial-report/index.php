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
                        data-target="#modal-center-add">
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
                                <div class="box-header">
                                    <h4 class="box-title">Financial</h4>	
                                </div>
        
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <table id="example1" class="table table-bordered table-separated">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Company</th>
                                                    <th>Period</th>
                                                    <th>Actions</th>
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
                        <div class="modal center-modal fade" id="modal-center-add" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content" style="height: 500px; overflow-y: scroll;">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Company Analysis</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="processor.php" method="post" id="add" enctype="multipart/form-data">
                                            <?= WebPage::getCSRFTokenInputTag() ?>
                                            <input type="hidden" name="action" value="create">
                                            <div class="form-group">
                                                <label>Company *</label>
                                                <select name="company" required class="form-control select2" style="width: 100%;">                                                    
                                                    <?= $companyOption ?>
                                                </select>
                                            </div> 
                                            <div class="form-group">
                                                <label>Year  *</label>
                                                <select name="year" required class="form-control select2" style="width: 100%;">
                                                    <?= $yearOption ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Period *</label>
                                                <select name="period" required class="form-control select2" style="width: 100%;">
                                                    <?= $periodOption ?>                                                
                                                </select>
                                            </div>                                                                                               
                                            <div class="form-group">
                                                <label>Report *</label>
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
                                    <button form="add" type="submit" class="btn btn-primary float-right">
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