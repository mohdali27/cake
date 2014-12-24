
    <?php  
    class ItemsController extends AppController {  
        public $components = array('RequestHandler');
       
        function getItems(){
        	$postData = $this->request->query;
        	$response = array();
   
        	if (!empty($postData) && !empty($postData['restaurant_id']) ) {
        	 $items = $this->Item->query("SELECT * FROM items where restaurant_id = ".$postData['restaurant_id']);
	        	 if(!count($items)){
	        	 $items = "Item Not Present";	
	        	 }
        	}else
        	{
        		$items = "Restaurant ID Not Sent";
        	}
        	  $this->set(array(
                'Items' => $items,
                '_serialize' => array('Items')
            ));

        }

        function setItems(){
        	$postData = $this->request->query;
        	$response = array();
        	if (!empty($postData) && !empty($postData['restaurant_id'])  && !empty($postData['item_name'])  && !empty($postData['price'])) {

        		if($this->Item->save($postData)){ 
                    $response['msg'] = "Item Saved";
                }
                else{
                    $response['msg'] = "Item Not Saved";    
                }
        	}
        	else{
        		$response['msg'] = "Data not sufficient to Save";    
        	}
        	 $this->set(array(
                'Items' => $response,
                '_serialize' => array('Items')
            ));
        }
        function getRestaurants(){
            $this->loadModel('Restaurant');
            $restaurants = $this->Restaurant->find('all');
            $this->set(array(
                'Restaurants' => $restaurants,
                '_serialize' => array('Restaurants')
            ));
              
        }
        function setRestaurants(){
          
             $response = array();
             $postData = $this->request->query;
            

        if (!empty($postData) && !empty($postData['name'])  && !empty($postData['image']) && !empty($postData['owner_email']) && !empty($postData['latitude'])  && !empty($postData['longitude']) ) {
           
           // ********************* image uploading Start

                $img_name = str_replace(" ", "-", strtolower($postData['name']));
                $data = $postData['image'];
                define('UPLOAD_DIR',  WWW_ROOT . 'img/');
 		        list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $data = str_replace(' ', '+', $data);
                $data = base64_decode($data);
                file_put_contents(UPLOAD_DIR.$img_name.".jpeg", $data);
                $postData['image'] = "http://".$_SERVER['HTTP_HOST']."/cake/app/webroot/img/".$img_name.".jpeg";

            // ********************* image uploading End 
             $email = $postData['owner_email'];
             $name = $postData['name'];
             $response = $this->Restaurant->query("SELECT * FROM restaurants WHERE owner_email = '$email' or name = '$name'");
             if(!count($response)){      
                
                if($this->Restaurant->save($postData)){ 
                    $response['msg'] = "Restaurant Saved";
                }
                else{
                    $response['msg'] = "Restaurant Not Saved";    
                }

              }else{
                    $response['msg'] = "Restaurant already Present";    
              }                     
            }
            else{
                $response['msg'] = "Data not sufficient to Save";       
            }
            $this->set(array(
                'response' => $response,
                '_serialize' => array('response')
            )); 
        }
    }    

    ?> 
