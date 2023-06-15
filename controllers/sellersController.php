<?php

require_once "models/seller.php";
require_once "view/sellerView.php";

class sellerController {
    private $model;
    private $view;

    public function __construct($model, $view){
        $this->model = $model;
        $this->view = $view;
    }

    public function getSellers(){
       $sellers = $this->model->getSellers();
       $this->view->renderJson($sellers); 
    }

    public function getSellerId(){
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $parts = explode('/', $path);
        $sellerId = end($parts);

        $seller = $this->model->getSellerId($sellerId);

        if($seller){
            $this->view->renderJson($seller);
        } else {
            $this->view->renderNotFound();
        }
    }
    public function createSeller() {
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate the input data
        // ...

        // Create a new seller
        $seller = $this->model->createSeller($data);

        // Return a response
        if ($seller) {
            $this->view->renderJson($seller);
        } else {
            $this->view->renderError('Failed to create a seller.');
        }
    }
}


