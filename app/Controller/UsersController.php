<?php  
class UsersController extends AppController {  
   //$uses=array('Card');

  public function index(){
     die("Memzi API Service.");
  }
   public function signup(){
           $response=array() ;
           $cardflag=false;
           if($this->request->is('post')){
                $data=$this->request->data;
                if(!empty($data) && $data['email'] && $data['password']){
                    $checkuser=$this->User->find('first',array('conditions'=>array('User.email'=>$data['email'])));
                    // echo"<pre>";print_r(count($checkuser));die;
                    if($checkuser){
                         $response['error']="Email is already exist.";
                         $response['ErrorCode']="13";
                         return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK)));
                    }else{
                        if($data['user_type']=="Business"){
                               $user['active']=$data['active'];
                               $user['user_name']=$data['user_name'];
                               $user['email']=$data['email'];
                               $user['phone_no']=$data['phone_no'];
                               $user['password']=md5($data['password']);  
                               $user['user_type']=$data['user_type'];
                               $user['device_type']=$data['device_type'];
                               $user['device_id']=$data['device_id'];
                               $user['image']=$this->upload_image($data['image'],$data['user_name']);
                               if($this->User->save($user)){
                                    if(isset($data['cvv']) && isset($data['card_no'])){
                                             $data['user_id']=$this->User->getLastInsertID();
                                             $this->loadModel('Card');
                                             $this->Card->save($data);
                                             $cardflag=true;         
                                    }
                                    if($cardflag){
                                        $data['image']=$user['image'];
                                        $response['user']=$data;
                                    }else{
                                      $response['user']=$user;
                                    }
                                    $response['status']='OK';
                                    return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK)));
                                }else{
                                      $response['error']="Server Error";
                                      $response['ErrorCode']="12";
                                      return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK)));      
                                }
                        }elseif($data['user_type']=="Customer"){
                               $user['active']=$data['active'];
                               $user['user_name']=$data['user_name'];
                               $user['email']=$data['email'];
                               $user['phone_no']=$data['phone_no'];
                               $user['password']=md5($data['password']);  
                               $user['user_type']=$data['user_type'];
                               $user['device_type']=$data['device_type'];
                               $user['device_id']=$data['device_id'];
                               $user['image']=$this->upload_image($user['image'],$user['user_name']);
                               if($this->User->save($user)){
                                    $response['user']=$user; 
                                    $response['status']='OK';
                                    return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK)));
                                }else{
                                      $response['error']="Server Error";
                                      $response['ErrorCode']="12";
                                      return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK)));      
                                }
                        }else{
                              $response['error']="Invalid User Type.";
                              $response['ErrorCode']="14";
                              return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK)));      

                        }
                        
                       
                    }
                }else{
                    $response['error']="Invalid User data.";
                    $response['ErrorCode']="10";
                    return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK)));
                }
           }else{
                    $response['error']="Invalid Request";
                    $response['ErrorCode']="11";
                    return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK)));
           }
    }

    function login(){

        $imgUrl=$this->upload_image($this->request->data['image'],"test");
       // $response=array();
       die($imgUrl);

       if($this->request->is('post')){
           $data=$this->request->data;
           $checkuser=$this->User->find('first',array('conditions'=>array('User.email'=>$data['email'],'User.password'=>md5($data['password']))));

           if($checkuser){
              $response['user']=$checkuser['User'];    
              $response['status']='OK';
              return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK)));
           }else{
                $response['error']="Invalid User";
                $response['ErrorCode']="10";
                return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK)));
           }
       }else{
                $response['error']="Invalid Request";
                $response['ErrorCode']="11";
                return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK)));
       } 
    }

    function notification(){
      $msg = array
          (
            'message'   => 'here is a message. message',
            'title'   => 'This is a title. title',
            'subtitle'  => 'This is a subtitle. subtitle',
            'tickerText'  => 'Ticker text here...Ticker text here...Ticker text here',
            'vibrate' => 1,
            'sound'   => 1,
            'largeIcon' => 'large_icon',
            'smallIcon' => 'small_icon'
          );
     $reg_id=array(REG_ID);    
     $response= $this->send_notification_iphone("B86BC9402A69B031A516BC57F7D3063F","jdjfgjdjfd");
     return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK)));
    }

    function updateUser(){
      $response = array();
      if($this->request->is('post')){
        $data=$this->request->data;
        if(!empty($data) && $data['id'] && $data['user_name'] && $data['phone_no']){
          if($this->User->save($data)){
             $response['msg']="User has been successfully updated.";
             $response['status']="OK";
             return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK)));
          }
        }else{
          $response['error']="Invalid Post Data";
          $response['ErrorCode']="15";
          return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK)));
        }
      }else{
        $response['error']="Invalid Request";
        $response['ErrorCode']="11";
        return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK)));
      }
          
  }

   function deleteUser(){
        $response = array();
        $data=$this->request->data;
        if(!empty($data) && $data['id']){
          if($this->User->delete($data['id'])){
            $response['msg']="User has been deleted successfully";
            $response['status']="OK";
            return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK)));
          }else{
            $response['error']="Invalid User Id.";
            $response['ErrorCode']=16;
            return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK)));
          }
        }else{
          $response['error']="Invalid Post Data";
          $response['ErrorCode']="15";
          return new CakeResponse(array('body' => json_encode($response, JSON_NUMERIC_CHECK))); 
        }
  }
}