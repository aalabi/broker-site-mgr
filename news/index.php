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
                                <div class="box-header">
                                    <h4 class="box-title">News</h4>	
                                    <h6 class="subtitle">Just click on word which you want to change and enter</h6>
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
                                                <tr>
                                                    <td>1</td>
                                                    <td>The Federal Government spent a total of $1.08bn on External Debt Servicing</td>
                                                    <td>The Federal Government spent a total of $1.08bn (N400.90bn) on external debt servicing between Janua ...</td>
                                                    <td class="col-2">
                                                    <a
                                                        href="javascript:void(0)"
                                                        class="text-white btn btn-warning"
                                                        data-toggle="modal" data-target="#modal-edit-center"
                                                        ><i class="ti-pencil" data-toggle="tooltip" data-original-title="Edit" aria-hidden="true"></i
                                                    ></a>
                                                    <a
                                                            class="text-white btn btn-danger"
                                                            data-toggle="modal" data-target="#modal-delete-center"
                                                            ><i class="ti-trash" data-toggle="tooltip" data-original-title="Delete" aria-hidden="true"></i
                                                        ></a>
                                                    </td>
                                                </tr>
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
                                <form class="form">
                                    <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" placeholder="Untitled">
                                    </div>
                                    <div class="form-group">
                                                    <label>Body</label>
                                                    <textarea rows="5" class="form-control" placeholder="(Optional)"></textarea>
                                                    </div>
                                    <div class="form-group">
                                    <label>Source</label>
                                    <input type="url" class="form-control" placeholder="http://">
                                    </div>
                                    <!-- /.box-body -->
                                </form>
                                </div>
                                <div class="modal-footer modal-footer-uniform">
                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-dismiss="modal"
                                >
                                    Close
                                </button>
                                <button type="button" class="btn btn-primary float-right">
                                    Save changes
                                </button>
                                </div>
                            </div>
                            </div>
                        </div>
                        <!-- /.modal -->

                        <!-- Modal -->
                        <div class="modal center-modal fade" id="modal-edit-center" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit News</strong></h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form class="form">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" value="The Federal Government spent a total of $1.08bn on External Debt Servicing" placeholder="Untitled">
                                    </div>
                                    <div class="form-group">
                                        <label>Body</label>
                                        <textarea rows="5" class="form-control" placeholder="(Optional)"></textarea>
                                    </div>
                                    <!-- /.box-body -->
                                    </form>
                                </div>
                                <div class="modal-footer modal-footer-uniform">
                                    <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-dismiss="modal"
                                    >
                                    Close
                                    </button>
                                    <button type="button" class="btn btn-primary float-right">
                                    Save changes
                                    </button>
                                </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.modal -->

                        <!-- Modal -->
                        <div class="modal center-modal fade" id="modal-delete-center" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Are you sure?</h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form class="form">
                                    <p>You are about to delete ""? This action is not reversible</p>
                                    </form>
                                </div>
                                <div class="modal-footer modal-footer-uniform">
                                    <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-dismiss="modal"
                                    >
                                    Close
                                    </button>
                                    <button type="button" class="btn btn-danger float-right">
                                    Delete
                                    </button>
                                </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.modal -->
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