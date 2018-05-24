<?php
function vorgang_add() {
	$resultId = false;
	$dbresult = db_query ( "INSERT INTO vorgang () VALUES ()", false );
	
	if ($dbresult !== false) {
		$resultId = db_autoinc_id ();
	}
	return $resultId;
}
