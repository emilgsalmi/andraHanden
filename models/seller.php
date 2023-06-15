<?php

    class Seller
    {
        private $conn;
        
        public function __construct($conn)
        {
            $this->conn = $conn;
        }
        public function getSellers()
        {
            $stmt = $this->conn->prepare('SELECT * FROM sellers ORDER BY seller_name ASC');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getSellerId($sellerId)
        {
            $stmt = $this->conn->prepare('SELECT * FROM sellers WHERE seller_id = :seller_id');
            $stmt->bindParam(':seller_id', $sellerId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function createSeller($data) {
            $stmt = $this->conn->prepare('INSERT INTO sellers (seller_name) VALUES (:seller_name)');
            $stmt->bindParam(':seller_name', $data['seller_name'], PDO::PARAM_STR);
            $stmt->execute();

            $sellerId = $this->conn->lastInsertId();
        
            if ($sellerId) {
                return $this->getSellerId($sellerId);
            } else {
                return null;
            }
        }


        
    }

?>
    