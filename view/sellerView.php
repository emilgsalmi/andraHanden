<?php

    class sellersView {
        public function renderJson($data){
            header('Content-Type: application/json');        
            echo json_encode($data);
        }
        public function renderNotFound(){
            header('HTTP/1.0 404 not found');
            echo "item not found";
        }
    }
?>