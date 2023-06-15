<?php
require_once "models/item.php";
require_once "view/itemView.php";

class ItemController {
    private $model;
    private $view;

    public function __construct($model, $view){
        $this->model = $model;
        $this->view = $view;
    }

    public function getItems(){
        $items = $this->model->getItems();
        $this->view->renderJson($items);
    }

    public function getItemId(){
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $parts = explode('/', $path);
        $itemId = end($parts);

        $item = $this->model->getItemId($itemId);

        if($item){
            $this->view->renderJson($item);
        } else {
            $this->view->renderNotFound();
        }
    }

    public function createItem() {
        $data = json_decode(file_get_contents('php://input'), true);
    
        // Validate the input data
        // ...
    
        // Create a new item
        $item = $this->model->createItem($data);
    
        // Return a response
        if ($item) {
            $this->view->renderJson($item);
        } else {
            $this->view->renderError('Failed to create item.');
        }
    }

    public function updateItem($itemId, $data) {
        $item = $this->model->updateItem($itemId, $data);
        return $item;
    }
    
}

?>