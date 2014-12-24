<!-- File: /app/views/categories/add.ctp -->    
     
<h1>Add Category</h1>
<?php
echo $this->Form->create('Category');
echo $form->input('name');
echo $form->end('Save Post');
?>