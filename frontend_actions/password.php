<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 29.07.2016
 * Time: 15:50
 */
?>
<form action="index.php?action=do_sendPasswordReset" method="post">
						<table cellspacing="3" cellpadding="0" border="0">
							<tr>
								<td style="width:125px">Email-Adresse:</td>
								<td><input type="text" name="email" maxlength="255" style="width:200px" /></td>
							</tr>
                            <tr>
								<td>&nbsp;</td>
								<td><input type="button" value="Abschicken" style="width:200px" onclick="document.forms[0].submit()"/></td>
							</tr>
						</table>
</form>