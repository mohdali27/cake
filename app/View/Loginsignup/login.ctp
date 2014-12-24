<h1> Login  </h1>
<form action = "<?php echo Router::url('/', true); ?>login" method="post" >
	User Name: <input type="text" name="user_name" /><br><br>
	Password: <input type="text" name="password" /><br><br>
	<input type= "submit" name ="submit" />
</form>
<h6><a href="<?php echo Router::url('/', true)."signup"; ?>" >SignUp</a></h6>