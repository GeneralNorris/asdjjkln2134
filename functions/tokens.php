<?php
function token_generate_confirmation($userId){
	$userID= (int)$userId;
	$token = sha1(uniqid(microtime(),true));
	$query = db_query(
			"INSERT INTO token(token, createdate, Person_id, emailLink)
			VALUES('".db_secure_chars($token)."',
					NOW(),
					".$userID.",
					1
			)"
			);
    if($query) {
        $url = "https://www.geschenke.engelbaum.de/activation.php?token=$token";
        return $url;
    }else{
        return false;
    }
}

function token_generate_passwordReset($userId){
    $userID= (int)$userId;
    $token = sha1(uniqid(microtime(),true));
    $query = db_query(
        "INSERT INTO token(token, createdate, Person_id, passwordLink)
			VALUES('".db_secure_chars($token)."',
					NOW(),
					".$userID.",
					1
			)"
    );
    if($query) {
        $url = "https://www.geschenke.engelbaum.de/index.php?action=passwordReset&token=$token";
        return $url;
    }else{
        return false;
    }
}

function token_verify_passwordReset($token){
    $tok = db_secure_chars($token);
    $query = db_query("
                SELECT token, createdate, Person_id, passwordLink
                FROM token
                WHERE token='".$tok."' AND passwordLink = 1
                ");
    $result = db_fetch_array($query);
    if($result && $result['passwordLink'] == 1){
        $userId = $result['Person_id'];
        db_query("
                DELETE FROM token
                WHERE token='".$tok."'
                ");
        return $userId;
    }else{
        return false;
    }
}

function token_generate(){
	$token = sha1(uniqid(microtime(),true));
	db_query(
			"INSERT INTO token(token, createdate, used, emailLink, copied)
			VALUES('".db_secure_chars($token)."',
					NOW(),
					0,
					0,
					0
			)"
			);
    return $token;
}

function token_verify_confirmation($token){
    $tok = db_secure_chars($token);
	$query=db_query(
			"SELECT token,createdate, Person_id, emailLink
			 FROM token
			 WHERE token='".$tok."'
			");
	$rs = db_fetch_array($query);
	if($rs && $rs['emailLink'] == 1){
		$userId = $rs['Person_id'];
        db_query("
                UPDATE account
                SET checkedMail = 1
                WHERE Person_id='".$userId."'
                ");
		db_query("
				DELETE FROM token
				WHERE token='".$tok."'
				");
        return true;
	}else{
		return false;
	}
}

function token_verify($token){
	$tok = db_secure_chars($token);

	$query=db_query(
		"SELECT token,createdate, used, emailLink
			 FROM token
			 WHERE token='".$tok ."'"
		);
	$rs = db_fetch_array($query);
	if($rs && $rs['emailLink'] == 0 && $rs['used'] == 0){
		db_query(
			"UPDATE token
			SET used = 1
			WHERE token='".$tok ."'"
		);
		return true;
	}else{
		return false;
	}
}
function token_setFree($token){
	$tok = db_secure_chars($token);

	$query=db_query(
		"SELECT token,createdate, used, emailLink
			 FROM token
			 WHERE token='".$tok ."'"
	);
	$rs = db_fetch_array($query);
	if($rs && $rs['emailLink'] == 0 && $rs['used'] == 1){
		db_query(
			"UPDATE token
			SET used = 0
			WHERE token='".$tok ."'"
		);
		return true;
	}else{
		return false;
	}
}
function token_getAll(){
	$query = db_query(
			"SELECT token, used, createdate, copied, Person_id
			FROM token
			WHERE emailLink = 0
			ORDER BY used DESC"
	);
	return $query;
}

function token_getUnused(){
    $query = db_query(
        "SELECT token, used, createdate, copied, Person_id
			FROM token
			WHERE emailLink = 0 AND used = 0 AND copied = 0
			");
    return $query;
}

function token_getUsed(){
    $query = db_query(
        "SELECT token, used, createdate, copied, Person_id
			FROM token
			WHERE emailLink = 0 AND used = 1 OR copied = 1
			");
    return $query;
}


function token_isUsed($token){
    $tok = db_secure_chars($token);
    $query = db_query(
        "SELECT  used
			FROM token
			WHERE token ='".$tok."'"
    );
    $result = db_fetch_array($query);

    if($result['used'] == 1){
        return true;
    }else{
        return false;
    }
}
function token_delete(){
    $query = db_query(
                "DELETE FROM token
                 WHERE emailLink <> 1
                 OR passwordLink <> 1
                 ");
    if($query){
        return true;
    }else{
        return false;
    }
}

function token_markCopied($token){
    $tok = db_secure_chars($token);
    db_query(
            "UPDATE token
			SET copied = 1
			WHERE token='".$tok ."'"
    );
}
function token_setUser($token, $personId){
    $pid = (int) $personId;
    $tok = db_secure_chars($token);
    db_query("
                UPDATE token
                SET Person_id = '".$pid."'
                WHERE token = '".$tok."'
                ");
}