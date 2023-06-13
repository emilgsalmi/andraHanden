<?php

require_once 'partials/connect.php';

// Sätta en baseUrl
$baseUrl = '/andraHanden';

// Indikera att det är JSON content
header('Content-Type: application/json');

// Etablerar databas kontakt
$connection = connect($host, $db, $username, $password);

// Hanterar GET request för att hämta data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if the request is for the sellers endpoint
    if (strpos($_SERVER['REQUEST_URI'], $baseUrl.'/sellers') !== false) {
        // Get the seller_id from the query parameters
        //
        $queryParams = $_GET;
        $sellerId = isset($queryParams['seller_id']) ? $queryParams['seller_id'] : null;

        try {
            if ($sellerId) {
                // Hämtar data för specifik säljare 
                $stmt = $connection->prepare('SELECT * FROM sellers WHERE seller_id = :seller_id');
                $stmt->bindParam(':seller_id', $sellerId);
            } else {
                // Hämtar säljar data i alfabetisk ordning fråm sellers table
                $stmt = $connection->prepare('SELECT * FROM sellers ORDER BY seller_name ASC');
            }

            $stmt->execute();
            $sellers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($sellers);
        } catch (PDOException $e) {
            http_response_code(500); // Internt Server Fel
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    // Kolla om requesten är för items endpoint
    if (strpos($_SERVER['REQUEST_URI'], $baseUrl.'/items') !== false) {
        try {
            $stmt = $connection->prepare('SELECT * FROM items ORDER BY item_id ASC');
            $stmt->execute();
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($items);
        } catch (PDOException $e) {
            http_response_code(500); // Internt Server Fel
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}   


// Skapa ny användare
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the request is for the sellers endpoint
    if (strpos($_SERVER['REQUEST_URI'], $baseUrl.'/sellers') !== false) {
        try {
            // Create a new seller in the database
            $data = json_decode(file_get_contents('php://input'), true);

            $stmt = $connection->prepare('INSERT INTO sellers (seller_name) VALUES (:seller_name)');
            $stmt->bindParam(':seller_name', $data['seller_name']);    
            $stmt->execute();

            $sellerId = $connection->lastInsertId();

            $response = [
                'message' => 'Seller created successfully',
                'seller_id' => $sellerId
            ];

            echo json_encode($response);
        } catch (PDOException $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    // Skapa nya Items
    if (strpos($_SERVER['REQUEST_URI'], $baseUrl.'/items') !== false) {
        try {
            // Create a new item in the database
            $data = json_decode(file_get_contents('php://input'), true);

            $stmt = $connection->prepare('INSERT INTO items (item_name, seller_id, sold, sale_amount) VALUES (:item_name, :seller_id, :sold, :sale_amount)');
            $stmt->bindParam(':item_name', $data['item_name']);
            $stmt->bindParam(':seller_id', $data['seller_id']);
            $stmt->bindParam(':sold', $data['sold']);
            $stmt->bindParam(':sale_amount', $data['sale_amount']);
            $stmt->execute();

            $itemId = $connection->lastInsertId();

            $response = [
                'message' => 'Item created successfully',
                'item_id' => $itemId
            ];

            echo json_encode($response);
        } catch (PDOException $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

// Handle PUT request to update the sold status and seller of an item
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (strpos($_SERVER['REQUEST_URI'], $baseUrl.'/items') !== false){ 
        $itemID = $_GET['item_id'];
        $soldStatus = $_GET['sold'];
        $sellerID = $_GET['seller_id'];
        

        // Perform necessary validations on itemID, soldStatus, and sellerID inputs
        $stmt = $connection->prepare("UPDATE items SET sold = $soldStatus, seller_id = $sellerID WHERE item_id = $itemID");
        
        $stmt2 = $connection->prepare("UPDATE sellers SET 
                total_sale_amount = (SELECT SUM(i.sale_amount) FROM items i WHERE i.seller_id = sellers.seller_id AND i.sold = 1),
                total_sold_items = (SELECT COUNT(*) FROM items i WHERE i.seller_id = sellers.seller_id AND i.sold = 1)
                WHERE seller_id = $sellerID");
        $stmt->execute();
        $stmt2->execute();

        $response = [
            'message' => 'Item sold!',
            'item_id' => $itemID
        ];
        echo json_encode($response);
    }
}

?>
