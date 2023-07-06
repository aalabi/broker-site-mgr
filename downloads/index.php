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
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add">
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
                                    <h4 class="box-title">Downloads</h4>
                                </div>
                                <div class="box-body p-15">
                                    <div class="table-responsive">
                                    <table id="tickets" class="table mt-0 table-hover no-wrap table-borderless" data-page-size="10">
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Name</th>
                                                <th>Type</th>
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
                    </div>

                    <!-- Modal -->
                    <div class="modal center-modal fade" id="modal-add" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Client Service Form</h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="processor.php" method="post" id="add" enctype='multipart/form-data'>
                                        <?= WebPage::getCSRFTokenInputTag() ?>
                                        <input type="hidden" name="action" value="create">
                                        <div class="form-group">
                                            <label>Priority</label>
                                            <input name="priority" type="number" class="form-control" value="1" placeholder="(Optional)">
                                            <small>Number only</small>
                                        </div>  
                                        <div class="form-group">
                                            <label>Name *</label>
                                            <input required name="name" type="text" class="form-control" placeholder="Enter Name">
                                        </div>
                                        <div class="form-group">
                                            <label>Type *</label>
                                            <div>
                                                <input required name="type" type="radio" id="radio_29" class="with-gap radio-col-primary" value="<?= TblDocument::TYPE_VALUES[0] ?>" />
                                                <label for="radio_29">Client Service</label>
                                                <input required name="type" type="radio" id="radio_30" class="with-gap radio-col-primary" value="<?= TblDocument::TYPE_VALUES[1] ?>" />
                                                <label for="radio_30">Public Offer</label>
                                                <input required name="type" type="radio" id="radio_31" class="with-gap radio-col-primary" value="<?= TblDocument::TYPE_VALUES[2] ?>" />
                                                <label for="radio_31">Registrar</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Form *</label>
                                            <div>
                                                <input required name="document" type="file" class="form-control">
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