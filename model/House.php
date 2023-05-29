<?php
	class House{		

		private $id;
		private $owner;
		private $address;
		private $price;
		private $biddingDate;
		private $imgPath;
				
		function __construct($id, $owner, $address, $price, $biddingDate, $imgPath){
			$this->setId($id);
			$this->setOwner($owner);
			$this->setAddress($address);
			$this->setPrice($price);
			$this->setBiddingDate($biddingDate);
			$this->setImgPath($imgPath);
		}		
		
		public function getOwner(){
			return $this->owner;
		}
		public function setOwner($owner){
			$this->owner = $owner;
		}
		
		public function getAddress(){
			return $this->address;
		}
		
		public function setAddress($address){
			$this->address = $address;
		}

		public function getPrice(){
			return $this->price;
		}

		public function setPrice($price){
			$this->price = $price;
		}

		public function getBiddingDate(){
			return $this->biddingDate;
		}

		public function setBiddingDate($biddingDate){
			$this->biddingDate = $biddingDate;
		}

		public function getImgPath(){
			return $this->imgPath;
		}

		public function setImgPath($imgPath){
			$this->imgPath = $imgPath;
		}

		public function setId($id){
			$this->id = $id;
		}

		public function getId(){
			return $this->id;
		}

	}
?>