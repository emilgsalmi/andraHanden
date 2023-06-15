<?php
class Item
    {
        private $conn;
        
        public function __construct($conn)
        {
            $this->conn = $conn;
        }
        public function getItems()
        {
            $stmt = $this->conn->prepare('SELECT * FROM items ORDER BY item_id ASC');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }
        public function getItemId($itemId)
        {
            $stmt = $this->conn->prepare('SELECT * FROM items WHERE item_id = :item_id');
            $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function createItem($data) {
            $stmt = $this->conn->prepare('INSERT INTO items (item_name, seller_id, sold, sale_amount) VALUES (:item_name, :seller_id, :sold, :sale_amount)');
            $stmt->bindParam(':item_name', $data['item_name'], PDO::PARAM_STR);
            $stmt->bindParam(':seller_id', $data['seller_id'], PDO::PARAM_INT);
            $stmt->bindParam(':sold', $data['sold'], PDO::PARAM_INT);
            $stmt->bindParam(':sale_amount', $data['sale_amount'], PDO::PARAM_STR);
            $stmt->execute();
        
            $itemId = $this->conn->lastInsertId();
        
            if ($itemId) {
                return $this->getItemId($itemId);
            } else {
                return null;
            }
        }
        public function updateItem($itemId, $data) {
            $soldStatus = $data['sold'];
            $sellerID = $data['seller_id'];
    
            $stmt = $this->conn->prepare("UPDATE items SET sold = :soldStatus, seller_id = :sellerID WHERE item_id = :itemId");
            $stmt->bindParam(':soldStatus', $soldStatus, PDO::PARAM_INT);
            $stmt->bindParam(':sellerID', $sellerID, PDO::PARAM_INT);
            $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
            $stmt->execute();
    
            // Perform additional update statements or business logic if needed
            // ...
    
            // Check if the update was successful
            $updatedRowCount = $stmt->rowCount();
            if ($updatedRowCount > 0) {
                return $this->getItem($itemId);
            } else {
                return null;
            }
        }
        


    }
?>