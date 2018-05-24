<?php
$userID = isset($_GET['editUserID'])? (int) $_GET['editUserID'] : $_SESSION['userID'];

if (!user_check_editpermission($userID)) {
	$userID = $_SESSION['userID'];
}
$i=1;
$kinder_list = user_getKinderAdmin($userID);
while ($kind = db_fetch_array($kinder_list)){
	$i++;
}
if (!(@$_GET['ajax'] === "true")) { ?>

	<form method="post" action="portal.php?action=do_kinderAdd">
<?php } else { ?>
	<form method="post" action="do_backend_action.php?action=do_kinderAdd">
<?php } ?>
<input type="hidden" name="userID" value= <?php echo $userID?>>
<?php 
if(!user_getChildMax($userID)){
	
	for ($i; $i < 7; $i++) {
		echo '<tr><td colspan="2"><h3>Kind '.$i.'</h3></td></tr>';
		kind_printAddElements($i);
	}
}else{
	$m = $i +20;
	for ($i; $i < $m; $i++) {
		echo '<tr><td colspan="2"><h3>Kind '.$i.'</h3></td></tr>';
		kind_printAddElements($i);
	}
}?>
</form>