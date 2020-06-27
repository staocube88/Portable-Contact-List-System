
<?php require_once 'config.php'; include 'header.php';

try {
$sql = "SELECT * FROM tbl_contacts WHERE 1 AND contact_id = :cid";
$stmt = $DB->prepare($sql);
$stmt->bindValue(":cid", intval($_GET["cid"]));
$stmt->execute();
$results = $stmt->fetchAll();
} 
catch (Exception $ex) {
echo $ex->getMessage();
}

?>

<div class="row">
    <ul class="breadcrumb">
        <li><a href="index.php">Home</a></li>
        <li class="active"><?php echo ($_GET["m"] == "update") ? " Edit" : " Add"; ?> Contacts</li>
    </ul>
</div>

<div class="row">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo ($_GET["m"] == "update") ? "Edit" : "Add New"; ?> Contact</h3>
        </div>
        <div class="panel-body">

        <form class="form-horizontal" name="contact_form" id="contact_form" enctype="multipart/form-data" method="post" action="process.php">

        <input type="hidden" name="mode" value="<?php echo ($_GET["m"] =="update") ? "update" : "add"; ?>" >
        <input type="hidden" name="old_pic" value="<?php echo $results[0]["profile_pic"] ?>" >
        <input type="hidden" name="cid" value="<?php echo intval($results[0]["contact_id"]); ?>" >


            <fieldset>
                <div class="form-group">
                    <label class="col-lg-4 control-label" for="first_name"><span class="required text-warning">*</span>First Name:</label>
                    <div class="col-lg-5">
                        <input type="text" value="<?php echo $results[0]["first_name"] ?>" placeholder="First Name" id="first_name" class="form-control" name="first_name">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label" for="middle_name">Middle Name:</label>
                    <div class="col-lg-5">
                        <input type="text" value="<?php echo $results[0]["middle_name"] ?>" placeholder="Middle Name" id="middle_name" class="form-control" name="middle_name">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label" for="last_name"><span class="required text-warning">*</span>Last Name:</label>
                    <div class="col-lg-5">
                        <input type="text" value="<?php echo $results[0]["last_name"] ?>" placeholder="Last Name" id="last_name" class="form-control" name="last_name">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label" for="email_id"><span
                    class="required text-warning">*</span>Email ID:</label>
                    <div class="col-lg-5">
                        <input type="text" value="<?php echo $results[0]["email_address"] ?>"   placeholder="Email ID" id="email_id" class="form-control" name="email_id">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label" for="contact_no1"><span class="required text-warning">*</span>Contact No #1:</label>
                    <div class="col-lg-5">
                        <input type="text" value="<?php echo $results[0]["contact_no1"] ?>" placeholder="Contact Number" id="contact_no1" class="form-control" name="contact_no1">
                        <span class="help-block">Maximum of 10 digits only and only numbers.</span>
                    </div>  
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label" for="contact_no2">Contact No #2:</label>
                    <div class="col-lg-5">
                        <input type="text" value="<?php echo $results[0]["contact_no2"] ?>" placeholder="Contact Number" id="contact_no2" class="form-control" name="contact_no2">
                        <span class="help-block">Maximum of 10 digits only and only numbers.</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label" for="profile_pic">Profile picture:</label>
                    <div class="col-lg-5">
                        <input type="file" id="profile_pic" class="form-control file" name="profile_pic">
                        <span id="profile_pic_err" class="error"></span>
                        <span class="help-block">Must be jpg, jpeg, png, gif, bmp image only.</span>
                    </div>
                </div>

                <?php if ($_GET["m"] == "update") { ?>
                    <div class="form-group">
                            <div class="col-lg-1 col-lg-offset-4">
                                <?php $pic = ($results[0]["profile_pic"] <> "" ) ? $results[0]["profile_pic"] : "no_avatar.png" ?>
                                <a href="profile_pics/<?php echo $pic ?>" target="_blank"><img src="profile_pics/<?php echo $pic ?>" alt="" width="100" height="100" class="thumbnail" ></a>
                            </div>
                    </div>
            <?php } ?>

                <div class="form-group">
                    <label class="col-lg-4 control-label" for="address">Address:</label>
                    <div class="col-lg-5">
                        <textarea id="address" name="address" rows="3" class="form-control"><?php
                        echo $results[0]["address"] ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-5 col-lg-offset-4">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                    
                </div>
                <div class="form-group">
                    <div class="col-lg-5 col-lg-offset-4">
                    <p id="message" style="color:red;"></p>
                    <?php
                    if (isset($_GET['message']))
                    {
                        $message = $_GET['message'];
                        print "<p style='color:red;font-size:20px'>$message</p>";
                    }
                    
                    ?>
                    </div>
                    
                </div>
            </fieldset>
            
        </form>
        
        </div>
    </div>
</div>





<?php include 'footer.php'; ?>
