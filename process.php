<?php

require 'config.php';


// --------View all--------

class process
{
    private $conn;
    function __construct($DB)
    {
        $this->conn=$DB;
    }
    public function dataview($query)
    {
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if($stmt->rowCount()>0)
        {
            while ($row = $stmt -> fetch(PDO::FETCH_ASSOC))
            {
                ?>
                <tr>
                    <td style="text-align: center;">
                        <?php $pic = ($row["profile_pic"] <> "" ) ? $row["profile_pic"] : "no_avatar.png"?>
                        <a href="profile_pics/<?php echo $pic ?>" target="_blank"><img src="profile_pics/<?php echo $pic ?>" alt="" width="50" height="50" ></a>
                    </td>
                    <td><?php print ($row['first_name']); ?></td>
                    <td><?php print ($row['last_name']); ?></td>
                    <td><?php print ($row['contact_no1']); ?></td>
                    <td><?php print ($row['email_address']); ?></td>
                    <td>

                        <a href="view.php?cid=<?php echo $row["contact_id"]; ?>">
                        <button class="btn btn-sm btn-info"><span class="glyphicon glyphicon-zoom-in"></span> View</button></a> &nbsp;
                        
                        <a href="contacts.php?m=update&cid=<?php echo $row["contact_id"]; ?>"> 
                        <button class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</button></a>&nbsp;
                        
                        <a href="process.php?mode=delete&cid=<?php echo $row["contact_id"]; ?>&keyword=<?php echo $_GET["keyword"]; ?>" onclick="return confirm('Are you sure?')">
                        <button class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove-circle"></span> Delete</button></a>&nbsp;

                    </td>
                </tr>
                <?php
            }
        }
        
    }
    public function paging($query, $records_per_page)
    {
         $starting_position = 0;
         if (isset($_GET["page_no"]))
         {
            $starting_position = ($_GET["page_no"] - 1) * $records_per_page;
         }
         $query2 = $query . " limit $starting_position,$records_per_page";
         return $query2;
    }

    public function paginglink($query, $records_per_page)
    {

        $self = $_SERVER['PHP_SELF'];

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $total_no_of_records = $stmt->rowCount();

        if ($total_no_of_records > 0)
        {
?>
<ul class="pagination"><?php
            $total_no_of_pages = ceil($total_no_of_records / $records_per_page);
            $current_page = 1;
            if (isset($_GET["page_no"]))
            {
               $current_page = $_GET["page_no"];
            }
            if ($current_page != 1)
            {
               $previous = $current_page - 1;
               echo "<li><a href='" . $self . "?page_no=1'>First</a></li>";
               echo "<li><a href='" . $self . "?page_no=" . $previous . "'>Previous</a></li>";
            }
            for ($i = 1;$i <= $total_no_of_pages;$i++)
            {
               if ($i == $current_page)
               {
                  echo "<li><a href='" . $self . "?page_no=" . $i . "' style='color:red;'>" . $i . "</a></li>";
               }
               else
               {
                  echo "<li><a href='" . $self . "?page_no=" . $i . "'>" . $i . "</a></li>";
               }
            }
            if ($current_page != $total_no_of_pages)
            {
               $next = $current_page + 1;
               echo "<li><a href='" . $self . "?page_no=" . $next . "'>Next</a></li>";
               echo "<li><a href='" . $self . "?page_no=" . $total_no_of_pages . "'>Last</a></li>";
            }
      ?>
   </ul>
<?php
        }
    }

    /* paging */
    
    
}


// -------add--------




$mode = $_REQUEST["mode"];

if ($mode == "add")
{
    $first_name     =   $_POST['first_name'];
    $middle_name    =   $_POST['middle_name'];
    $last_name      =   $_POST['last_name'];
    $email_id       =   $_POST['email_id'];
    $contact_no1    =   $_POST['contact_no1'];
    $contact_no2    =   $_POST['contact_no2'];
    $address        =   $_POST['address'];
    $filename       =   "";
    $error          =   FALSE;
    if (empty($first_name))
	{
		header("location:contacts.php?message=Celebrity First Name is required");
		exit();
	}
	else
	if (empty($last_name))
	{
		header("location:contacts.php?message=Celebrity Last Name is required");
		exit();
	}
	else
	if (empty($email_id))
	{
		header("location:contacts.php?message=Celebrity Email is required");
		exit();
    }
    else
	if (ValidateEmail($email_id) == false)
	{
		header("location:contacts.php?message=Invalid email address");
		exit();
    }
	else
	if (empty($contact_no1))
	{
		header("location:contacts.php?message=Celebrity Contact Number is required");
		exit();
	}
	else
	if (is_numeric($contact_no1)  == false)
	{
		header("location:contacts.php?message=Celebrity Mobile Number must be a number");
		exit();
	}

    if (is_uploaded_file($_FILES["profile_pic"]["tmp_name"])) {
        $filename = time() . '_' . $_FILES["profile_pic"]["name"];
        $filepath = 'profile_pics/' . $filename;
        if (!move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $filepath)) {
        $error = TRUE;
        }
    }

    if (!$error) {
        $sql = "INSERT INTO `tbl_contacts` (`first_name`, `middle_name`, `last_name`,`address`, `contact_no1`, `contact_no2`, `email_address`, `profile_pic`) 
        VALUES ( :fname, :mname, :lname, :address, :contact1, :contact2, :email, :pic)";
        try {
            $stmt = $DB->prepare($sql);

            // bind the values
            $stmt->bindValue(":fname", $first_name);
            $stmt->bindValue(":mname", $middle_name);
            $stmt->bindValue(":lname", $last_name);
            $stmt->bindValue(":address", $address);
            $stmt->bindValue(":contact1", $contact_no1);
            $stmt->bindValue(":contact2", $contact_no2);
            $stmt->bindValue(":email", $email_id);
            $stmt->bindValue(":pic", $filename);

            // execute Query
            $stmt->execute();
            $result = $stmt->rowCount();
            if ($result > 0) {
                $_SESSION["errorType"] = "success";
                $_SESSION["errorMsg"] = "Contact added successfully.";
            } else {
                $_SESSION["errorType"] = "failure";
                $_SESSION["errorMsg"] = "Failed to add contact.";
            }
        } catch (Exception $ex) {


                $_SESSION["errorType"] = "failure";
                $_SESSION["errorMsg"] = $ex->getMessage();
        }
        } else {
            $_SESSION["errorType"] = "failure";
            $_SESSION["errorMsg"] = "failed to upload image.";
        }
        header("location:index.php");
} 
else if ( $mode == "update" ) {

    $first_name     =   $_POST['first_name'];
    $middle_name    =   $_POST['middle_name'];
    $last_name      =   $_POST['last_name'];
    $email_id       =   $_POST['email_id'];
    $contact_no1    =   $_POST['contact_no1'];
    $contact_no2    =   $_POST['contact_no2'];
    $address        =   $_POST['address'];
    $cid            =   $_POST['cid'];
    $filename       =   "";
    $error          =   FALSE;

    if (is_uploaded_file($_FILES["profile_pic"]["tmp_name"])) {
        $filename = time() . '_' . $_FILES["profile_pic"]["name"];
        $filepath = 'profile_pics/' . $filename;
        if (!move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $filepath)) {
            $error = TRUE;
        }
    } else {
        $filename = $_POST['old_pic'];
    }
    
    if (!$error) {


        $sql = "UPDATE `tbl_contacts` SET `first_name` = :fname, `middle_name` = :mname, `last_name` = :lname, `address` = :address, `contact_no1` = :contact1, `contact_no2` = :contact2, `email_address` = :email, `profile_pic` = :pic " . "WHERE contact_id = :cid ";
        
        try {

            $stmt = $DB->prepare($sql);

            // bind the values
            $stmt->bindValue(":fname", $first_name);
            $stmt->bindValue(":mname", $middle_name);
            $stmt->bindValue(":lname", $last_name);
            $stmt->bindValue(":address", $address);
            $stmt->bindValue(":contact1", $contact_no1);
            $stmt->bindValue(":contact2", $contact_no2);
            $stmt->bindValue(":email", $email_id);
            $stmt->bindValue(":pic", $filename);
            $stmt->bindValue(":cid", $cid);

        // execute Query
            $stmt->execute();
            $result = $stmt->rowCount();
            if ($result > 0) {
                $_SESSION["errorType"] = "success";
                $_SESSION["errorMsg"] = "Contact updated successfully.";
            } else {
                $_SESSION["errorType"] = "info";
                $_SESSION["errorMsg"] = "No changes made to contact.";
            }
        } 
        catch (Exception $ex) {
            $_SESSION["errorType"] = "failure";
            $_SESSION["errorMsg"] = $ex->getMessage();
        }

    } else {
        $_SESSION["errorType"] = "failure";
        $_SESSION["errorMsg"] = "Failed to upload image.";
    }
    header("location:index.php");
}
elseif ( $mode == "delete" ) {
    $cid = intval($_GET['cid']);
    $sql = "DELETE FROM `tbl_contacts` WHERE contact_id = :cid";
    try {
        $stmt = $DB->prepare($sql);
        $stmt->bindValue(":cid", $cid);
        $stmt->execute();
        $res = $stmt->rowCount();
    
        if ($res > 0) {
            $_SESSION["errorType"] = "success";
            $_SESSION["errorMsg"] = "Contact deleted successfully.";
        } else {
        $_SESSION["errorType"] = "info";
        $_SESSION["errorMsg"] = "Failed to delete contact.";
        }
    } catch (Exception $ex) {
        $_SESSION["errorType"] = "failure";
        $_SESSION["errorMsg"] = $ex->getMessage();
    }



    header("location:index.php");
    
    
    }
    function ValidateEmail($email)
	{
		if (empty($email))
		{
			return false;
		}
		else
		if (strpos($email, "@")  === false)
		{
			return false;
		}
		else
		if (!(strpos($email, "@.")  === false))
		{
			return false;
		}
		else
		if (!(strpos($email, ".@")  === false))
		{
			return false;
		}
		else
		if (strpos($email, "@")  == 0)
		{
			return false;
		}
		return true;
	}
    ?>