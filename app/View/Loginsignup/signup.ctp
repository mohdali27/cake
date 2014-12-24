<h2> Sign UP</h2>
<form action = "<?php echo Router::url('/', true); ?>signup" method="post" >
	User Name: <input type="text" name="user_name" /><br><br>
	Email: <input type="text" name="email" /><br><br>
	Phone No: <input type="text" name="phone_no" /><br><br>
	Password: <input type="text" name="password" /><br><br>
	User Type: <select name="user_type" >
				<option value ="1">Customer</option>
				<option value ="2">Bussness</option>
			 </select>
	<input type= "submit" name ="submit" />
</form>
<h6><a href="<?php echo Router::url('/', true)."login"; ?>" >Login</a></h6>