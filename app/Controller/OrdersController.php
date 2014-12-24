
    <?php  
    class OrdersController extends AppController {  
        public $components = array('RequestHandler');
            var $helpers = array("NotifyHelper");

        function setOrder(){
            $this->loadModel('Restaurant');
            $this->loadModel('User');
            $response = array();

            $postData = $this->request->query;
        if (!empty($postData) && !empty($postData['user_id']) && !empty($postData['restaurant_id'])  && !empty($postData['item_ids'])  && !empty($postData['total_price'])  && !empty($postData['order_confirmation'])  && !empty($postData['payment_id']) ) {
            $checkRestaurant = $this->Restaurant->query("SELECT * FROM restaurants WHERE  active = 1 and id = ".$postData['restaurant_id']);
            $checkUser = $this->User->query("SELECT * FROM users WHERE active = 1 and id = ".$postData['user_id']);
            
            if(count($checkUser)){
                if(count($checkRestaurant)){
                    if($this->Order->save($postData)){ // if data gets saved
                        $response['msg'] = "Order Saved";
                    }
                    else{
                        $response['msg'] = "Order Not Saved";    
                    }
                }else{
                    $response['msg'] = "Restaurant Not Present";      
                }

            }else{
              $response['msg'] = "User Not Present";   
            }
            
        }else{
                $response['msg'] = "Data not sufficient to Save";       
            }
            //$this->loadModel('Notify');
           // $test_helper =  $this->Notify->send_notification_android("B86BC9402A69B031A516BC57F7D3063F","test message");
            $this->set(array(
                'Orders' => $response,
                '_serialize' => array('Orders')
            ));


        }
     

    }    

    ?> 
