<?php
// Include employeeDAO file
require_once('./dao/houseDAO.php');
$houseDAO = new houseDAO(); 

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
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
                    <h1 class="mt-5 mb-3">View Record</h1>
                    <div class="row mb-3">
                    <?php
                        // Retrieve image path from the database
                        $img_path = $house->getImgPath();
                        ?>

                        <div class="col-md-4 my-auto">
                            <img src="<?php echo $img_path; ?>" class="img-fluid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><b>Owner or Agent Name</b></label>
                        <p><?php echo $owner; ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Address</b></label>
                        <p><?php echo $address; ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Price</b></label>
                        <p><?php echo number_format($price); ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>End of Bidding Date</b></label>
                        <p><?php echo $biddingDate; ?></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>