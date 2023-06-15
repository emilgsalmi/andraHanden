<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'partials/connect.php';
require_once 'models/seller.php';
require_once 'controllers/sellersController.php';
require_once 'view/sellerView.php';
require_once 'view/itemView.php';
require_once 'models/item.php';
require_once 'controllers/itemsController.php';


$request = rtrim($_SERVER['REQUEST_URI'], '/');
$baseUrl = '/andraHanden';
$endpoint = str_replace($baseUrl, '', $request);

// Sätta en baseUrl
//!$baseUrl = '/andraHanden';

// Indikera att det är JSON content
// !header('Content-Type: application/json');

// Etablerar databas kontakt
$connection = connect($host, $db, $username, $password);

$sellermodel = new Seller($connection);
$sellerview = new sellersView();
$sellercontroller = new SellerController($sellermodel, $sellerview);
$itemmodel = new Item($connection);
$itemview = new itemsView();
$itemcontroller = new ItemController($itemmodel, $itemview);


if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    if ($endpoint === '/sellers'){
        $sellercontroller->getSellers();
    } elseif (strpos($endpoint, '/sellers/') === 0){
        $sellercontroller->getSellerId();
    }
    if ($endpoint === '/items'){
        $itemcontroller->getItems();
    } elseif (strpos($endpoint, '/items/') === 0){
        $itemcontroller->getItemId();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($endpoint === '/sellers') {
        $sellercontroller->createSeller();
    }
    if ($endpoint === '/items') {
        $itemcontroller->createItem();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if ($endpoint === '/items/') {
        $itemcontroller->updateItem();
    }
}









/* // Hanterar GET request för att hämta data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Kollar om requesten är för säljarnas endpoint
    if (strpos($_SERVER['REQUEST_URI'], $baseUrl.'/sellers') !== false) {
        // Hämtar säljar id från query parametrar
        $queryParams = $_GET;
        $sellerId = isset($queryParams['seller_id']) ? $queryParams['seller_id'] : null;

        $sanitizeSeller = filter_var($sellerId, FILTER_SANITIZE_NUMBER_INT);

        if ($sellerId !== null && !is_numeric($sellerId)) {

            http_response_code(400);
            $response = [
                'error' => 'Invalid seller_id parameter'
            ];
            echo json_encode($response);
            return;
        }

        try {
            if ($sellerId) {
                // Hämtar data för specifik säljare 
                $stmt = $connection->prepare('SELECT * FROM sellers WHERE seller_id = :seller_id');
                $stmt->bindParam(':seller_id', $sellerId, PDO::PARAM_INT);
            } else {
                // Hämtar säljar data i alfabetisk ordning fråm sellers table
                $stmt = $connection->prepare('SELECT * FROM sellers ORDER BY seller_name ASC');
            }

            $stmt->execute();
            $sellers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($sellers);
        } catch (PDOException $e) {
            http_response_code(500); // Internt serverfel
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
            http_response_code(500); // Internt serverfel
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}   


// Skapa ny användare
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kollar om requesten är för säljarnas endpoint
    if (strpos($_SERVER['REQUEST_URI'], $baseUrl.'/sellers') !== false) {
        try {
            // Skapar en ny säljare i databasen
            $data = json_decode(file_get_contents('php://input'), true);

            $sellerName = isset($data['seller_name']) ? trim($data['seller_name']) : '';

            // Utför ytterligare valideringskontroller om det behövs
            if (empty($sellerName)) {
                // Hanterar en ogiltig inmatning
                http_response_code(400); // Bad Request
                $response = [
                    'error' => 'Seller name is required'
                ];
                echo json_encode($response);
                return;
            }
            // Sanerar imatning av säljarens namn   
            $sanitizedSellerName = filter_var($sellerName, FILTER_SANITIZE_STRING);

            // Hämtar data för specifik säljare 
            $stmt = $connection->prepare('INSERT INTO sellers (seller_name) VALUES (:seller_name)');
            $stmt->bindParam(':seller_name', $sanitizedSellerName, PDO::PARAM_STR);    
            $stmt->execute();

            $sellerId = $connection->lastInsertId();

            $response = [
                'message' => 'Seller created successfully',
                'seller_id' => $sellerId
            ];

            echo json_encode($response);
        } catch (PDOException $e) {
            http_response_code(500); // Internt serverfel
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    // Skapa nya Items
    if (strpos($_SERVER['REQUEST_URI'], $baseUrl.'/items') !== false) {
        try {
            // Skapa ett nytt objekt i databasen
            $data = json_decode(file_get_contents('php://input'), true);

            // Validera och rensa indata
            $itemName = isset($data['item_name']) ? trim($data['item_name']) : '';
            $sellerId = isset($data['seller_id']) ? $data['seller_id'] : null;
            $sold = isset($data['sold']) ? (int)$data['sold'] : 0;
            $saleAmount = isset($data['sale_amount']) ? (float)$data['sale_amount'] : 0.0;

            // Utför ytterligare valideringskontroller om det behövs
            if (empty($itemName) || $sellerId === null || !is_numeric($sellerId) || !is_numeric($sold) || !is_numeric($saleAmount)) {
                // Hantera ogiltig inmatning
                http_response_code(400); // dålig request
                $response = [
                    'error' => 'Invalid input parameters'
                ];
                echo json_encode($response);
                return;
            }

            // Sanera ingångsvärdena
            $sanitizedItemName = filter_var($itemName, FILTER_SANITIZE_SPECIAL_CHARS);
            $sanitizedSellerId = filter_var($sellerId, FILTER_SANITIZE_NUMBER_INT);
            $sanitizedSold = filter_var($sold, FILTER_VALIDATE_INT);
            $sanitizedSaleAmount = filter_var($saleAmount, FILTER_VALIDATE_FLOAT);

            $stmt = $connection->prepare('INSERT INTO items (item_name, seller_id, sold, sale_amount) VALUES (:item_name, :seller_id, :sold, :sale_amount)');
            $stmt->bindParam(':item_name', $sanitizedItemName, PDO::PARAM_STR);
            $stmt->bindParam(':seller_id', $sanitizedSellerId, PDO::PARAM_INT);
            $stmt->bindParam(':sold', $sanitizedSold, PDO::PARAM_INT);
            $stmt->bindParam(':sale_amount', $sanitizedSaleAmount, PDO::PARAM_STR);
            $stmt->execute();

            $itemId = $connection->lastInsertId();

            $response = [
                'message' => 'Item created successfully',
                'item_id' => $itemId
            ];

            echo json_encode($response);
        } catch (PDOException $e) {
            http_response_code(500); // Internt serverfel
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

// Uppdatering om ett plagg blir sålt och då uppdateras också säljaren
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (strpos($_SERVER['REQUEST_URI'], $baseUrl.'/items') !== false){ 
    
        $itemID = $_GET['item_id'];
        $soldStatus = $_GET['sold'];
        $sellerID = $_GET['seller_id'];
        
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
 */
?>
