    <!-- File: /app/Views/Users/add.ctp -->      
    <h1>Add New User</h1>  
      
    <?php  
    echo $this->Form->create();  
    echo $this->Form->input('user_name');  
    echo $this->Form->input('email');  
    echo $this->Form->input('phone_no');  
    echo $this->Form->input('password');  
    echo $this->Form->input('user_type');  
    echo $this->Form->end('Save New User');  
    ?>  