<?php
// Include houseDAO file
require_once('./dao/houseDAO.php');
 
// Define variables and initialize with empty values
$owner = $address = $price = $biddingDate = $imgPath = "";
$owner_err = $address_err = $price_err = $biddingDate_err = $img_err = "";
$houseDAO = new houseDAO(); 

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_owner = trim($_POST["owner"]);
    if(empty($input_owner)){
        $owner_err = "Please enter a owner or agent name.";
    } elseif(!filter_var($input_owner, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $owner_err = "Please enter a valid name.";
    } else{
        $owner = $input_owner;
    }
    
    // Validate address address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address."; 
    } else{
        $address = $input_address;
    }
    
    // Validate salary
    $input_price = trim($_POST["price"]);
    if(empty($input_price)){
        $price_err = "Please enter the salary amount.";
    } elseif(!ctype_digit($input_price)){
        $price_err = "Please enter a positive integer value.";
    } else{
        $price = $input_price;
    }

    $input_biddingDate = trim($_POST["bidding-date"]);
    $current_date = date("Y-m-d");
    if (empty($input_biddingDate)) {
        $biddingDate_err = "Please enter the end of bidding date";
    } elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $input_biddingDate)) {
        $biddingDate_err = "Date format is invalid. Please use yyyy-mm-dd format";
    } elseif (!checkdate(substr($input_biddingDate, 5, 2), substr($input_biddingDate, 8, 2), substr($input_biddingDate, 0, 4))) {
        $biddingDate_err = "Invalid date";
    } elseif ($input_biddingDate <= $current_date){
        $biddingDate_err = "End of bidding date cannot be in the past";
    } else {
        $biddingDate = $input_biddingDate;
    }

    // Validate file upload
    $target_dir = "imgs/";
    if (!empty($_FILES["file"]["name"])) {
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $imgPath = $target_file;

        if ($_FILES["file"]["error"] == 0) {
            $check = getimagesize($_FILES["file"]["tmp_name"]);

            if ($check !== false) {
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                    // Success
                } else {
                    $img_err = "Sorry, there was an error uploading your file.";
                }
            } else {
                $img_err = "File is not an image.";
            }
        } 
    } else {
        $id = $_POST["id"];
        $house = $houseDAO->getHouse($id);
        if ($house) {
            $imgPath = $house->getImgPath();
        }
    }
    
    // Check input errors before inserting in database
    if (empty($owner_err) && empty($address_err) && empty($price_err) && empty($biddingDate_err) && empty($img_err)) {
        $house = new House($id, $owner, $address, $price, $biddingDate, $imgPath);
        $result = $houseDAO->updateHouse($house);        
        header( "refresh:2; url=index.php" ); 
		echo '<br><h6 style="text-align:center">' . $result . '</h6>';   
        // Close connection
        $houseDAO->getMysqli()->close();
    }

} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        $house = $houseDAO->getHouse($id);
                
        if($house){
            // Retrieve individual field value
            $owner = $house->getOwner();
            $address = $house->getAddress();
            $price = $house->getPrice();
            $biddingDate = $house->getBiddingDate();
            $imgPath = $house->getImgPath();
        } else{
            // URL doesn't contain valid id. Redirect to error page
            header("location: error.php");
            exit();
        }
    } else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
    // Close connection
    $houseDAO->getMysqli()->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the posting record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="form-group">
                            <label>Owner or Agent Name</label>
                            <input type="text" name="owner" class="form-control <?php echo (!empty($owner_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $owner; ?>">
                            <span class="invalid-feedback"><?php echo $owner_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $price; ?>">
                            <span class="invalid-feedback"><?php echo $price_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>End of Bidding Date</label>
                            <input type="date" name="bidding-date" class="form-control <?php echo (!empty($biddingDate_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $biddingDate; ?>">
                            <span class="invalid-feedback"><?php echo $biddingDate_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Image of the place</label>
                            <input type="file" name="file" class="form-control-file d-block <?php echo (!empty($img_err)) ? 'is-invalid' : ''; ?>" accept="image/*" value="<?php echo $imgPath; ?>">
                            <?php if (!empty($img_err)): ?>
                            <span class="invalid-feedback"><?php echo $img_err;?></span>
                            <?php endif; ?>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>