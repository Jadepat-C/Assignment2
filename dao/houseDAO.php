<?php
require_once('abstractDAO.php');
require_once('./model/house.php');

/**
 * Summary of houseDAO
 */
class houseDAO extends abstractDAO {
        
    /**
     * Summary of __construct
     */
    function __construct() {
        try{
            parent::__construct();
        } catch(mysqli_sql_exception $e){
            throw $e;
        }
    }  
    
    /**
     * Summary of getHouse
     * @param mixed $houseId
     * @return bool|house
     */
    public function getHouse($houseId){
        $query = 'SELECT * FROM houses WHERE id = ?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $houseId);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $temp = $result->fetch_assoc();
            $house = new house($temp['id'],$temp['owner'], $temp['address'], $temp['price'], $temp['bidding_end_date'], $temp['img_path']);
            $result->free();
            return $house;
        }
        $result->free();
        return false;
    }


    /**
     * Summary of getHouses
     * @return array<house>|bool
     */
    public function getHouses(){
        //The query method returns a mysqli_result object
        $result = $this->mysqli->query('SELECT * FROM houses');
        $houses = Array();
        
        if($result->num_rows >= 1){
            while($row = $result->fetch_assoc()){
                //Create a new House object, and add it to the array.
                $house = new house($row['id'], $row['owner'], $row['address'], $row['price'], $row['bidding_end_date'], $row['img_path']);
                $houses[] = $house;
            }
            $result->free();
            return $houses;
        }
        $result->free();
        return false;
    }   
    
    /**
     * Summary of addHouse
     * @param mixed $house
     * @return mixed
     */
    public function addHouse($house){
        
        if(!$this->mysqli->connect_errno){
            //The query uses the question mark (?) as a
            //placeholder for the parameters to be used
            //in the query.
            //The prepare method of the mysqli object returns
            //a mysqli_stmt object. It takes a parameterized 
            //query as a parameter.
			$query = 'INSERT INTO houses (owner, address, price, bidding_end_date, img_path) VALUES (?,?,?,?,?)';
			$stmt = $this->mysqli->prepare($query);
            if($stmt){
                    $owner = $house->getOwner();
			        $address = $house->getAddress();
			        $price = $house->getPrice();
                    $biddingDate = $house->getBiddingDate();
                    $imgPath = $house->getImgPath();
                  
			        $stmt->bind_param('ssiss', 
				        $owner,
				        $address,
				        $price,
                        $biddingDate,
                        $imgPath
			        );    
                    //Execute the statement
                    $stmt->execute();         
                    
                    if($stmt->error){
                        return $stmt->error;
                    } else {
                        return $house->getAddress() . ' added successfully!';
                    } 
			}
             else {
                $error = $this->mysqli->errno . ' ' . $this->mysqli->error;
                echo $error; 
                return $error;
            }
       
        }else {
            return 'Could not connect to Database.';
        }
    }   
    /**
     * Summary of updateHouse
     * @param mixed $house
     * @return mixed
     */
    public function updateHouse($house){
        
        if(!$this->mysqli->connect_errno){
            //The query uses the question mark (?) as a
            //placeholder for the parameters to be used
            //in the query.
            //The prepare method of the mysqli object returns
            //a mysqli_stmt object. It takes a parameterized 
            //query as a parameter.
            $query = "UPDATE houses SET owner=?, address=?, price=?, bidding_end_date=?, img_path=? WHERE id=?";
            $stmt = $this->mysqli->prepare($query);
            if($stmt){
                    $id = $house->getId();
                    $owner = $house->getOwner();
			        $address = $house->getAddress();
			        $price = $house->getPrice();
                    $biddingDate = $house->getBiddingDate();
                    $imgPath = $house->getImgPath();
                  
			        $stmt->bind_param('ssissi', 
				        $owner,
				        $address,
				        $price,
                        $biddingDate,
                        $imgPath,
                        $id
			        );    
                    //Execute the statement
                    $stmt->execute();         
                    
                    if($stmt->error){
                        return $stmt->error;
                    } else {
                        return $house->getAddress() . ' updated successfully!';
                    } 
			}
             else {
                $error = $this->mysqli->errno . ' ' . $this->mysqli->error;
                echo $error; 
                return $error;
            }
       
        }else {
            return 'Could not connect to Database.';
        }
    }

    /**
     * Summary of deleteHouse
     * @param mixed $houseId
     * @return bool
     */
    public function deleteHouse($houseId){
        if(!$this->mysqli->connect_errno){
            $query = 'DELETE FROM houses WHERE id = ?';
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param('i', $houseId);
            $stmt->execute();
            if($stmt->error){
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}
?>