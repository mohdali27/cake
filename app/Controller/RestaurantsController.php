
    <?php  
    class RestaurantsController extends AppController {  
        public $components = array('RequestHandler');
       

        function getRestaurants(){
            $this->loadModel('Restaurant');
            $restaurants = $this->Restaurant->find('all',array('conditions' => array('active' => 1)));
            $this->set(array(
                'Restaurants' => $restaurants,
                '_serialize' => array('Restaurants')
            ));
              
        }
        function deleteRestaurant(){
            $response = array();
            $postData = $this->request->query;
           if (!empty($postData) && !empty($postData['id']) ) { 
            $delete = $this->Restaurant->query("UPDATE restaurants SET active = 0 WHERE id = ".$postData['id']);
             if($delete)
                   {
                        $response['msg'] = "Restaurant Deleted"; 
                   }
                   else
                   {
                        $response['msg'] = "Restaurant Not Deleted";
                   }
           }
           else{
                $response['msg'] = "Post Data Invalid";
            }
            $this->set(array(
                'response' => $response,
                '_serialize' => array('response')
            ));  

        }
        function updateRestaurant(){
          
             $response = array();
             $postData = $this->request->query;
            

        if (!empty($postData) && !empty($postData['id']) && !empty($postData['name'])  && !empty($postData['image']) && !empty($postData['owner_email']) && !empty($postData['latitude'])  && !empty($postData['longitude'])   && !empty($postData['tag_line'])   && !empty($postData['short_description'])   && !empty($postData['description'])) {
           
           // ********************* image uploading Start

                $img_name = str_replace(" ", "-", strtolower($postData['name']));
                $data = $postData['image'];
                define('UPLOAD_DIR',  WWW_ROOT . 'img/');
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $data = str_replace(' ', '+', $data);
                $data = base64_decode($data);
                file_put_contents(UPLOAD_DIR.$img_name.".jpeg", $data);
                $postData['image'] = "http://".$_SERVER['HTTP_HOST']."/mmezi/app/webroot/img/".$img_name.".jpeg";

            // ********************* image uploading End 
                $owner_email = $postData['owner_email'];
                $name = $postData['name'];
                $image = $postData['image'];
                $tag_line = $postData['tag_line'];
                $short_description = $postData['short_description'];
                $description = $postData['description'];
             
             $update = $this->Restaurant->query("UPDATE restaurants SET name = '$name', owner_email = '$owner_email' , image = '$image' , tag_line = '$tag_line' , short_description = '$short_description' , description = '$description'  WHERE id = ".$postData['id']);
                 if($update){ // if data gets saved
                        $response['msg'] = "Restaurant Updated";
                    }
                    else{
                        $response['msg'] = "Restaurant Not Updated";    
                    }
        } else{
                $response['msg'] = "Data not sufficient to Update";       
            }  

                $this->set(array(
                'response' => $response,
                '_serialize' => array('response')
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
                $postData['image'] = "http://".$_SERVER['HTTP_HOST']."/mmezi/app/webroot/img/".$img_name.".jpeg";

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
