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
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-center">
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
                                    <h4 class="box-title">News</h4>	
                                </div>
                        
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <table id="example1" class="table table-bordered table-separated">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Title</th>
                                                    <th>Body</th>
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
                        <div class="modal center-modal fade" id="modal-center" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add News</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="processor.php" method="post" id="add">
                                            <?= WebPage::getCSRFTokenInputTag() ?>
                                            <input type="hidden" name="action" value="create">
                                            <div class="form-group">
                                                <label>Title *</label>
                                                <input required name="title" type="text" class="form-control" placeholder="Untitled">
                                            </div>
                                            <div class="form-group">
                                                <label>Body *</label>
                                                <textarea required name="body" rows="5" class="form-control" placeholder="Blank"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Source *</label>
                                                <input required name="source" type="url" class="form-control" placeholder="https://">
                                            </div>
                                            <!-- /.box-body -->
                                        </form>
                                    </div>
                                    <div class="modal-footer modal-footer-uniform">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                            Close
                                        </button>
                                        <button type="submit"  form="add" class="btn btn-primary float-right">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.modal -->

                        <?= $editModal ?>
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