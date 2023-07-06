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
                                    <h4 class="box-title">Company</h4>	
                                    <h6 class="subtitle">Just click on word which you want to change and enter</h6>
                                </div>
                        
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <table id="example1" class="table table-bordered table-separated">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Company</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>7 UP BOTTLING COMP PLC</td>
                                                    <td>
                                                    <a
                                                        href="javascript:void(0)"
                                                        class="text-white btn btn-primary"
                                                        data-toggle="tooltip"
                                                        data-original-title="View"
                                                        ><i class="ti-eye" aria-hidden="true"></i
                                                    ></a>
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
                            <div class="modal-content" style="height: 500px; overflow-y: scroll;">
                                <div class="modal-header">
                                <h5 class="modal-title">Add Company Analysis</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                <form class="form">
                                    <div class="form-group">
                                    <label>Company</label>
                                    <select class="form-control select2" style="width: 100%;">
                                        <option selected="selected">Select Company</option>
                                        <option>7UP BOTTLING COMP PLC</option>
                                    </select>
                                    </div> 
                                    <div class="form-group">
                                    <label>Year</label>
                                    <select class="form-control select2" style="width: 100%;">
                                        <option selected="selected">Select Year</option>
                                        <option>2018</option>
                                        <option>2019</option>
                                        <option>2020</option>
                                        <option>2021</option>
                                        <option>2022</option>
                                        <option>2023</option>
                                    </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Period</label>
                                        <select class="form-control select2" style="width: 100%;">
                                        <option selected="selected">Select Period</option>
                                        <option>1st Quarter</option>
                                        <option>2nd Quarter</option>
                                        <option>3rd Quarter</option>
                                        <option>4th Quarter</option>
                                        <option>Full Year</option>
                                        </select>
                                        </div>  
                                        <div class="form-group">
                                        <label>Dividends</label>
                                        <div>
                                            <input type="number" class="form-control" placeholder="(Optional)">
                                        </div>
                                        </div>
                                        <div class="form-group">
                                        <label>Interim (Optional)</label>
                                        <div>
                                            <input name="group5" type="radio" id="radio_29" class="with-gap radio-col-primary" />
                                            <label for="radio_29">Yes</label>
                                            <input name="group5" type="radio" id="radio_30" class="with-gap radio-col-primary" />
                                            <label for="radio_30">No</label>
                                        </div>
                                        </div>
                                        <div class="form-group">
                                        <label>Bonus</label>
                                        <div>
                                            <input type="text" class="form-control" placeholder="(Optional)">
                                        </div>
                                        </div>
                                        <div class="form-group">
                                        <label>Closure Date</label>
                                        <div>
                                            <input type="date" class="form-control">
                                        </div>
                                        <small>Optional</small>
                                        </div>
                                        <div class="form-group">
                                        <label>AGM Date</label>
                                        <div>
                                            <input type="date" class="form-control">
                                        </div>
                                        <small>Optional</small>
                                        </div>
                                        <div class="form-group">
                                        <label>Payment Date</label>
                                        <div>
                                            <input type="date" class="form-control">
                                        </div>
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
                            <div class="modal-content" style="height: 500px; overflow-y: scroll;">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit <strong>7UP BOTTLING COMP PLC</strong></h5>
                                <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form class="form">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" class="form-control" value="7UP BOTTLING COMP PLC" placeholder="Enter Company Name">
                                </div>
                                <div class="form-group">
                                    <label>Year</label>
                                    <select class="form-control select2" style="width: 100%;">
                                    <option selected="selected">Select Year</option>
                                    <option>2018</option>
                                    <option>2019</option>
                                    <option>2020</option>
                                    <option>2021</option>
                                    <option>2022</option>
                                    <option>2023</option>
                                    </select>
                                    </div>
                                    <div class="form-group">
                                    <label>Period</label>
                                    <select class="form-control select2" style="width: 100%;">
                                        <option selected="selected">Select Period</option>
                                        <option>1st Quarter</option>
                                        <option>2nd Quarter</option>
                                        <option>3rd Quarter</option>
                                        <option>4th Quarter</option>
                                        <option>Full Year</option>
                                    </select>
                                    </div>  
                                    <div class="form-group">
                                        <label>Dividends</label>
                                        <div>
                                        <input type="number" class="form-control" placeholder="(Optional)">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Interim (Optional)</label>
                                        <div>
                                        <input name="group5" type="radio" id="radio_29" class="with-gap radio-col-primary" />
                                        <label for="radio_29">Yes</label>
                                        <input name="group5" type="radio" id="radio_30" class="with-gap radio-col-primary" />
                                        <label for="radio_30">No</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Bonus</label>
                                        <div>
                                        <input type="text" class="form-control" placeholder="(Optional)">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Closure Date</label>
                                        <div>
                                        <input type="date" class="form-control">
                                        </div>
                                        <small>Optional</small>
                                    </div>
                                    <div class="form-group">
                                        <label>AGM Date</label>
                                        <div>
                                        <input type="date" class="form-control">
                                        </div>
                                        <small>Optional</small>
                                    </div>
                                    <div class="form-group">
                                        <label>Payment Date</label>
                                        <div>
                                        <input type="date" class="form-control">
                                        </div>
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