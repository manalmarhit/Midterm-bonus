//  hashing the password to make it more secure and storing the hashed version in the DB
<?php
echo password_hash("123456", PASSWORD_DEFAULT);
?>
