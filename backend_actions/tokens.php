<?php
require_once ("functions/tokens.php");
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 17.06.2016
 * Time: 16:06
 */

if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin', true);
?>
<script src="libs/clipboard/clipboard.min.js"></script>
<div class="actionHead">
		<h1>Links</h1>
        </span>
        Suche: <input id="pattern" title="Suche in allen Spalten" autocomplete="off" style="width: 600px;">
        </span>
		<div class="buttonbar">
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" id="dataTable" class="tablesorter">
		<thead>
			<tr>
				<th>Link</th>
                <th>Kopiert</th>
				<th>Benutzt</th>
                <th>Benutzt Von</th>
				<th>Erstellt am</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if (user_check_access('admin', false)){
			if(isset($_GET['typ'])){
				if($_GET['typ'] == 'used'){
					$tokenList = token_getUsed();
				}elseif ($_GET['typ'] == 'unused'){
					$tokenList = token_getUnused();
				}else{
					$tokenList = token_getAll();
				}
			}else {
				$tokenList = token_getAll();
			}
		} else {
			header("location: portal.php");
		}

		$i = 0;
		while ($rs = db_fetch_array($tokenList)) {
		    $nachname = "nicht Verfügbar";
			if($rs['used']){
			    if(isset($rs["Person_id"])) {
                    $user = user_byID($rs["Person_id"]);
                    $nachname = $user["name"];
                }
		    ?>
                <tr id="<?php echo $rs['token']?>" style="background-color:#ff5622">
            <?php } else if($rs['copied']){ ?>
			    <tr id="<?php echo $rs['token']?>" style="background-color:#fff97d">
            <?php } else{?>
                <tr id="<?php echo $rs['token']?>" style="background-color:# <?php echo core_row_backgroundcolor($i)?>">
            <?php }?>
				<td id="token_link<?php echo $i?>">https://www.geschenke.engelbaum.de/registration.php?token=<?php echo htmlspecialchars($rs['token'])?></td>
                <td><?php echo ($rs['copied']) ? "Ja" : "Nein"?></td>
                <td><?php echo ($rs['used']) ? "Ja" : "Nein"?></td>
                <td><?php echo $nachname?></td>
				<td><?php echo htmlspecialchars($rs['createdate'])?></td>
				<td><a id="copy_button" data-clipboard-target="#token_link<?php echo $i?>" title="Als kopiert markieren" href="portal.php?action=newTokens&token=<?php echo htmlspecialchars($rs['token'])?>">Als kopiert markieren</a></td>
			</tr>
			<?php
			$i++;
		}
		?>
		</tbody>

		<tfoot>
		<tr>
			<td colspan="1"></td>
			<td  style="background-color:#D7DDF0">
				<a title="Alle zeigen" href="portal.php?action=tokens&typ=all" class="add" id="showAll">Alle zeigen</a>
			</td>

			<td  style="background-color:#D7DDF0">
				<a title="Neue Links Erstellen" href="#" class="add" id="newLinks1">1 Neuen Link erstellen</a>
			</td>
		</tr>
		<tr>
			<td colspan="1"></td>
			<td  style="background-color:#D7DDF0">
				<a title="Benutzte zeigen" href="portal.php?action=tokens&typ=used" class="add" id="showUsed">Benutzte zeigen</a>
			</td>

			<td  style="background-color:#D7DDF0">
				<a title="Neue Links Erstellen" href="#" class="add" id="newLinks5">5 Neue Links erstellen</a>
			</td>
		</tr>
		<tr>
			<td colspan="1"></td>
			<td  style="background-color:#D7DDF0">
				<a title="Unbenutzte zeigen" href="portal.php?action=tokens&typ=unused" class="add" id="showUnused">Unbenutzte zeigen</a>
			</td>
			<td  style="background-color:#D7DDF0">
				<a title="Neue Links Erstellen" href="#" class="add" id="newLinks10">10 Neue Links erstellen</a>
			</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td  style="background-color:#D7DDF0">
				<a title="Neue Links Erstellen" href="#" class="add" id="newLinks50">50 Neue Links erstellen</a>
			</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td  style="background-color:#D7DDF0">
				<a title="Neue Links Erstellen" href="#" class="add" id="newLinks100">100 Neue Links erstellen</a>
			</td>
		</tr>

		</tfoot>
	</table>
	<script>
        (function(){
            new Clipboard('#copy_button');
        })();

			$("#newLinks1").click(function(){
				var anzahl = 1;
				$.ajax({
					type: 'POST',
					url: 'portal.php?action=newTokens',
					data: {'anzahl': anzahl},
					success: function () {
						window.location.assign("portal.php?action=tokens")
					}
				});
			});

			$("#newLinks5").click(function(){
				var anzahl = 5;
				$.ajax({
					type: 'POST',
					url: 'portal.php?action=newTokens',
					data: {'anzahl': anzahl},
					success: function () {
						window.location.assign("portal.php?action=tokens")
					}
				});
			});
			$("#newLinks10").click(function(){
				var anzahl = 10;
				$.ajax({
					type: 'POST',
					url: 'portal.php?action=newTokens',
					data: {'anzahl': anzahl},
					success: function () {
						window.location.assign("portal.php?action=tokens")
					}
				});
			});
			$("#newLinks50").click(function(){
				var anzahl = 50;
				$.ajax({
					type: 'POST',
					url: 'portal.php?action=newTokens',
					data: {'anzahl': anzahl},
					success: function () {
						window.location.assign("portal.php?action=tokens")
					}
				});
			});

			$("#newLinks100").click(function(){
				var anzahl = 100;
				$.ajax({
					type: 'POST',
					url: 'portal.php?action=newTokens',
					data: {'anzahl': anzahl},
					success: function () {
						window.location.assign("portal.php?action=tokens")
					}
				});
			});
			function markCopied(token) {
				var tok = token;
					$.ajax({
						type: 'POST',
						url: 'portal.php?action=newTokens',
						data: {'token': tok},
						success: function () {
//							window.location.assign("portal.php?action=tokens")
						}
					});
			};
	</script>