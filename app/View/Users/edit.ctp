<!-- File: /app/views/users/edit.ctp -->   
  
<h1>Edit User</h1>  
<?php  
    echo $this->Form->create('User', array('action' => 'edit'));  
    echo $this->Form->input('id', array('type'=>'hidden'));  
    echo $this->Form->input('first_name');  
    echo $this->Form->input('last_name');  
    echo $this->Form->input('email');  
    echo $this->Form->input('mobile');  
    echo $this->Form->end('Update User');  
?> 