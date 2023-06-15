<?php
class itemsView {
        public function renderJson($data){
            header('Content-Type: application/json');        
            echo json_encode($data);
        }
        public function renderNotFound(){
            header('HTTP/1.0 404 not found');
            echo "item not found";
        }
        public function renderError($errorMessage) {
            header('HTTP/1.0 500 Internal Server Error');
            echo $errorMessage;
        }
    }
?>