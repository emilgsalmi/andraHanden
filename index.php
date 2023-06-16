<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'partials/connect.php';
require_once 'models/sellerModel.php';
require_once 'controllers/sellersController.php';
require_once 'view/sellerView.php';
require_once 'view/itemView.php';
require_once 'models/itemModel.php';
require_once 'controllers/itemsController.php';


$request = rtrim($_SERVER['REQUEST_URI'], '/');
$baseUrl = '/andraHanden';
$endpoint = str_replace($baseUrl, '', $request);

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
    if (strpos($endpoint, '/items/') === 0) {
        $itemId = substr($endpoint, strlen('/items/'));

        $data = json_decode(file_get_contents('php://input'), true);

        $item = $itemcontroller->updateItem($itemId, $data);

        if ($item) {
            $itemview->renderJson($item);
        } else {
            $itemview->renderError('Failed to update item.');
        }
    }
}

?>
