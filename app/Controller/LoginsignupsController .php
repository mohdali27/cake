
    <?php  
    class LoginsignupController extends AppController {  
        public $components = array('RequestHandler');
       
        /*
        function login(){
            $this->loadModel('User');
            $postData = $this->data;
            if (!empty($this->data)) {
                $user=$postData['user_name'];
                $pass=$postData['password'];
               
               $user = $this->User->query("SELECT * FROM users where user_name = '$user' and password ='$pass'");
               if(count($user))
               {
                 $this->Session->write('user',"set");
                 $url = Router::url('/', true).'index';
                 $this->redirect("$url");
               }
               else
               {
                 $this->Session->setFlash('Invalid User or Password');
                $url = Router::url('/', true).'login';
                $this->redirect("$url");

               }

           }
        }
*/
        function updateUser(){
            $response = array();
            $this->loadModel('User');
            $postData = $this->request->query;
            if (!empty($postData) && !empty($postData['id']) && !empty($postData['user_name'])  && !empty($postData['phone_no']) ) {
                $user=$postData['user_name'];
                $phone_no=$postData['phone_no'];
                $update = $this->User->query("UPDATE users SET user_name = '$user', phone_no = '$phone_no' WHERE id = ".$postData['id']);
                 if($update){ // if data gets saved
                        $response['msg'] = "User Updated";
                    }
                    else{
                        $response['msg'] = "User Not Updated";    
                    }

            }else{
                $response['msg'] = "Post Data Invalid";
            }
            $this->set(array(
                'response' => $response,
                '_serialize' => array('response')
            ));  
        }
        function login(){
            $response = array();
            $this->loadModel('User');
            $postData = $this->request->query;
            if (!empty($postData)) {
                    $user=$postData['user_name'];
                    $pass=md5($postData['password']);
                   
                   $response = $this->User->query("SELECT * FROM users where user_name = '$user' and password ='$pass' and active = 1");
                   if(count($response))
                   {
                        $response['msg'] = "User Found"; 
                   }
                   else
                   {
                        $response['msg'] = "Invalid Username Or Passoword";
                   }

           }else{
                $response['msg'] = "Post Data Invalid";
            }
           $this->set(array(
                'response' => $response,
                '_serialize' => array('response')
            ));   
        }
        function deleteUser(){
            $response = array();
            $this->loadModel('User');
            $postData = $this->request->query;
           if (!empty($postData) && !empty($postData['id']) ) { 
            $delete = $this->User->query("UPDATE users SET active = 0 WHERE id = ".$postData['id']);
             if(count($delete))
                   {
                        $response['msg'] = "User Deleted"; 
                   }
                   else
                   {
                        $response['msg'] = "User Not Deleted";
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

        function index(){
            die("Hello");
            $this->loadModel('User');
            $users = $this->User->find('all');
            $this->set(array(
                'users' => $users,
                '_serialize' => array('users')
            ));
                
            }

        function logout(){
            $this->Session->write('user',"unset");
            $url = Router::url('/', true).'login';
            $this->redirect("$url");
        }

        function signup(){ 
            $response = array();
            $this->loadModel('User');
            $postData = $this->request->query;
            if (!empty($postData) && !empty($postData['password']) && !empty($postData['email'])  && !empty($postData['user_name'])  && !empty($postData['active']) ) {
                $email = $postData['email'];
                $pass = md5($postData['password']);
                unset($postData['password']);
                $postData['password'] = $pass;

                $response = $this->User->query("SELECT * FROM users where email = '$email'");
                if(!count($response)){ // if No email already present

                    $date = new DateTime();
                    $date = $date->format('Y-m-d H:i:s');
                    $postData["created_on"]  = $date;
                    if($this->User->save($postData)){ // if data gets saved
                        $response['msg'] = "Data Saved";
                    }
                    else{
                        $response['msg'] = "Data Not Saved";    
                    }
                }
                else{// if email already present
                    $response['msg'] = "User Email Already Present";       
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

        function test()
        {
            $base64img = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAYEBQYFBAYGBQYHBwYIChAKCgkJChQODwwQFxQYGBcUFhYaHSUfGhsjHBYWICwgIyYnKSopGR8tMC0oMCUoKSj/2wBDAQcHBwoIChMKChMoGhYaKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCj/wAARCAAwACkDASIAAhEBAxEB/8QAGwAAAgMBAQEAAAAAAAAAAAAABwgABAYFAgP/xAAuEAABAwIFAgYCAQUAAAAAAAABAgMEBREABgcSIRMxCDJBUWFxFBWBI0OCkcH/xAAZAQACAwEAAAAAAAAAAAAAAAABAgMEBQD/xAAgEQACAwACAQUAAAAAAAAAAAABAgADEQQSExQhMVGh/9oADAMBAAIRAxEAPwBqcCHXLV+PpuiJT4cNM2uTEdVtpa9rbLdyAtduTcggAex5Fhcu4AOpCZVI1kefS3B/FqtKjIeemNoWlxpt8h6KAq/DiSm59Lj7wrMEGmFQWOCZ/I3iQqEmtxY2cqXCjU+Q50hMiBaAyeLkhRO4DcL2IIBvz2LPemE11MQ/C0rFGTW41RYhFghnpISG1Bxze6i1yHVF4AgnypcPN7IbLKEoTsp0WVv6n5EJl3fe99zYN8LXYto7IdELKUONOziYmJiSLPmtaW2ytxQShIuSTYAYX/T+Mxq/qJmfN1RDjuXYI/UUloLUgKHClOcEEG1jYj+7Y+XGn8UNfdoek0xqMpTblVfRTitJ8qF7lL/2lCk/5Yu+HNmmMaT0lmkOoc2qdL9rb0uFwkhfyAU/Y2nsRgEA+xnA5POdtJ6VV8iVakUxgIqTqAuNJfWVKDqFbki58qSRY2HY35OBL4adRpVBqpyPmdDrEd2SpiEt24MWRey46ge11dvZRtzu4avCUeIBbDGpGdGmT0VhUWS3sNj+R0W+RbsbKWT84CqFGCEkk6Y7GJjnZfmqqNBps1dt0mM08be6kg/9x0cNBBPqfLZrE/8ATS4cZ2NCdbftIaDnUc28EA8WAWf5xl8iUhig6gt1aDVHaVTH0FuXTW2R0HlWISSbjaASD2NubEBRwRM2ZMfqlTXPhSmkuLQAWngbXHFwodh24tjMsZTr5nJYVEDaCbGQXUqbSPewO4/Vhf47jDu9XXeWUEj8yaNfgarD8wkZoqCKVQJ05T7bCm2TsW4oAbzwgc+pUQAPUkDCzZuyIvM2ajU5dRT+vfLapDOz+sdgtYOeyvU97n1sMF+foxRp5ZVLrWY33GHA8z1p25tpwchaW9u0WPoABixSMgzRIH7h+OIzZ8sZaiXR8kgbf4ufkd8WuUvILqafrJBS1XUh51tNKgXaUqmqbsYICUqT5OmSrake20C1vYDG2vipChx4EcMRGUMtDslAsPv7xati5QjVoFc6RIHYMxIn/9k=';
            define('UPLOAD_DIR',  WWW_ROOT . 'img/');
            $base64img = str_replace('data:image/jpeg;base64,', '', $base64img);
            $data = base64_decode($base64img);

            $formImage = imagecreatefromstring($data);

            file_put_contents(UPLOAD_DIR."123aa.jpeg", $data);
            //$base64img = str_replace('data:image/jpg;base64,', '', $base64img);

            /*
            echo "<img src='".$base64img."'>";
            $data = base64_decode($base64img);
            $file = UPLOAD_DIR . '123123123.jpeg';
            file_put_contents($file, $data);*/
            //saveImage($base64img1);
            
           /* if(!empty($this->request->data))
            {   echo "<pre> first if" ; print_r($this->params['form']);
                    //Check if image has been uploaded
                    if(true)
                    {
                            $file = $this->params['form']["up_image"]; //put the data into a var for easy use

                            $ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
                            $arr_ext = array('jpg', 'jpeg', 'gif'); //set allowed extensions
                            $test =  move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img/' . $file['name']);
                                  //echo "<pre>"; print_r("here".$test);
                            //only process if the extension is valid
                            if(in_array($ext, $arr_ext))
                            {
                                    //do the actual uploading of the file. First arg is the tmp name, second arg is 
                                    //where we are putting it

                                    //prepare the filename for database entry
                                    //$this->data['User']['image'] = $file['name'];
                            }
                    }

                    //now do the save
                   // if($this->User->save($this->data)) {...} else {...}
            }*/
        }

        /*function signup(){
            $this->loadModel('User');
            $postData = $this->data;
            if (!empty($this->data)) {
                $email = $postData['email'];

                $email_user = $this->User->query("SELECT * FROM users where email = '$email'");
                if(!count($email_user)){ // if No email already present
                    $date = new DateTime();
                    $date = $date->format('Y-m-d H:i:s');
                    $postData["created_on"]  = $date;
                    if($this->User->save($postData)){ // if data gets saved
                        $this->Session->setFlash('Sign up Success');
                        $url = Router::url('/', true).'login';
                        $this->redirect("$url");
                    }

                }
                else{// if email already present
                       $this->Session->setFlash('email already present');
                       
                }
            }

        } */   

    }    

    ?> 
