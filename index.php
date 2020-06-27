<?php
require_once 'config.php';
include 'header.php';



try{
    $keyword = $_GET["keyword"];
    if($keyword<>"")
    {
        $query = "SELECT * FROM tbl_contacts WHERE 1 AND (first_name LIKE '$keyword') ORDER BY first_name ";
    }
    else
    {
        $query = "SELECT * FROM tbl_contacts order by first_name"; 
    }
}

catch (Exception $ex) {
    echo $ex->getMessage();
    }

?>
<div class="row">
    <?php if ($ERROR_MSG <> "") { ?>
    <div class="alert alert-dismissable alert-<?php echo $ERROR_TYPE ?>">
        <button data-dismiss="alert" class="close" type="button">×</button>
        <p><?php echo $ERROR_MSG; ?></p>
    </div>
<?php } ?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Contact List</h3>
	</div>
    <!-- ----search---- -->
	<div class="panel-body">
		<div class="col-lg-12" style="padding-left: 0; padding-right: 0;" >
			<form action="index.php" method="get" >
				<div class="col-lg-6 pull-left"style="padding-left: 0;" >
					<span class="pull-left">
						<label class="col-lg-12 control-label" for="keyword" style="padding-right: 0;">
							<input type="text" value="<?php echo $_GET["keyword"]; ?>"placeholder="Search by first name" id="" class="form-control" name="keyword"style="height: 41px;">
						</label>
					</span>
					<button class="btn btn-info">search</button>
				</div>
			</form>
		    <div class="pull-right" >
                <a href="contacts.php">
                    <button class="btn btn-success"><span class="glyphicon glyphicon-user"></span> Add New Contact</button>
                </a>
			</div>
		</div>
		<div class="clearfix"></div>


<div class="table-responsive">
    <table class="table table-striped table-hover table-bordered ">

        <tr>
            <th>Avatar</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Contact No #1 </th>
            <th>Email </th>
            <th>Action </th>
        </tr>
        <?php
        if(!isset($query))
        {
        $query = "SELECT * FROM tbl_contacts order by first_name"; 
        }      
        $records_per_page=10;
        $newquery = $process->paging($query,$records_per_page);
        $process->dataview($newquery);
        ?>
        <tr>
        <td colspan="7" align="center">
            <div class="pagination-wrap">
                <?php $process->paginglink($query,$records_per_page); ?>
            </div>
        </td>
    </tr>
 
            
         

    </table>
    

</div>








    
   

    </div>
    </div>
    </div>


<?php include 'footer.php'; ?>

