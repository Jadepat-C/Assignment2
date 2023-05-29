<?php
// Include employeeDAO file
require_once('./dao/houseDAO.php');

 
// Define variables and initialize with empty values
$owner = $address = $price = $biddingDate = $imgPath ="";
$owner_err = $address_err = $price_err = $biddingDate_err = $img_err = "";

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_owner = trim($_POST["owner"]);
    if(empty($input_owner)){
        $owner_err = "Please enter a owner or agent name.";
    } elseif(!filter_var($input_owner, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $owner_err = "Please enter a valid name.";
    } else{
        $owner = $input_owner;
    }
    
    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }
    
    // Validate price
    $input_price = trim($_POST["price"]);
    if(empty($input_price)){
        $price_err = "Please enter the price.";     
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
        $biddingDate_err = "Date format is invalid. Please use mm-dd-yyyy format";
    } elseif (!checkdate(substr($input_biddingDate, 5, 2), substr($input_biddingDate, 8, 2), substr($input_biddingDate, 0, 4))) {
        $biddingDate_err = "Invalid date";
    } elseif ($input_biddingDate <= $current_date){
        $biddingDate_err = "End of bidding date cannot be in the past";
    } else {
        $biddingDate = $input_biddingDate;
    }

    $target_dir = "imgs/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $imgPath = $target_file;

    if(isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $check = getimagesize($_FILES["file"]["tmp_name"]);

        if($check !== false) {
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            } else {
                $img_err = "Sorry, there was an error uploading your file.";
            }
        } else {
            $img_err =  "File is not an image.";
        }
    } elseif(isset($_FILES["file"]) && $_FILES["file"]["error"] != 0) {
        $img_err =  "No file uploaded.";
    }
    
    // Check input errors before inserting in database
    if(empty($owner_err) && empty($address_err) && empty($price_err) && empty($biddingDate_err) && empty($img_err)){
        $houseDAO = new houseDAO();    
        $house = new House(0, $owner, $address, $price, $biddingDate, $imgPath);
        $addResult = $houseDAO->addHouse($house);        
        header( "refresh:2; url=index.php" ); 
		echo '<br><h6 style="text-align:center">' . $addResult . '</h6>';   
        // Close connection
        $houseDAO->getMysqli()->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="js/script.js"></script>
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add posting record to the database.</p>
					
					<!--the following form action, will send the submitted form data to the page itself ($_SERVER["PHP_SELF"]), instead of jumping to a different page.-->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
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
                            <input type="file" name="file" class="form-control-file d-block <?php echo (!empty($img_err)) ? 'is-invalid' : ''; ?>" accept="image/*">
                            <?php if (!empty($img_err)): ?>
                            <span class="invalid-feedback"><?php echo $img_err;?></span>
                            <?php endif; ?>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
        <?include 'footer.php';?>
    </div>
</body>
</html>