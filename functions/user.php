<?php
require_once("functions/passhash.php");

/**
 * Prüft ob korrekte Anmeldung erfolgt ist und noch aktuell ist.
 * Ist dies nicht der Fall, wird die Session beendet.
 */
function user_check_login($doRedirect = true, $page = 0, $backend = false, $teilhabe = false)
{
    $login = false;
    if (isset($_SESSION['login'])) {
        $query = db_query("SELECT A.Person_id, A.password, A.faultylogins, P.email as login
                                FROM account A JOIN person P ON A.Person_id = P.id 
                                WHERE P.email='" . db_secure_chars($_SESSION['login']) . "' LIMIT 1"
                );
        $rs = db_fetch_array($query);
        $faultyLogins = $rs['faultylogins'];
        if ($rs['login'] == $_SESSION['login']
            && $rs['faultylogins'] < CONF_MAXFAULTYLOGINS
            //!!!! Hier wird das Kennwort geprüft
            && passhash::check_password($rs['password'], $_SESSION['password'])
            //!!!!
        ) {
            $_SESSION['userID'] = $rs['Person_id'];
            $_SESSION['roles'] = user_roles($rs['Person_id']);
            //Kontrolle abgeschaltet (oben), weil Router bei MD mit zwei IP-Adressen nach drausen geht.
            //Dafür hier Logging eingeschaltet
            if ($_SESSION['ip'] != core_securechars($_SERVER['REMOTE_ADDR'])) {
                log_add('securityviolation', 'IP-Adresse hat sich innerhalb einer SESSION geändert: alte:' . $_SESSION['ip'] . ' neue:' . $_SERVER['REMOTE_ADDR']);
                $_SESSION['ip'] = core_securechars($_SERVER['REMOTE_ADDR']);
            }
            if (isset($_SESSION['do_log_login'])) {
                unset($_SESSION['do_log_login']);
                user_log_loginSuccess($_SESSION['userID'], core_securechars($_SERVER['REMOTE_ADDR']));
            }
            $login = true;
        }
    }
//	//Zugangsbeschränkung bei Wartung
//	if("78.94.240.90" != $_SERVER['REMOTE_ADDR'] && "127.0.0.1" != $_SERVER['REMOTE_ADDR']){
//		$login = false;
//	}
    if (!$login) {
        if (isset($_SESSION['login'])) {
            //Ein fehlerhafter Loginversuch
            user_log_loginFailure($_SESSION['login'], core_securechars($_SERVER['REMOTE_ADDR']));
            if ($faultyLogins >= CONF_MAXFAULTYLOGINS) {
                $faulty = true;
            } else {
                $wrong = true;
            }
        } else {
            //Eine Seite wurde aufgerufen, ohne angemeldet zu sein
            //kein Problem
        }
        if ($doRedirect) {
            user_logout();
            if($backend){
                header("location: geschenke_login.php");
            } if($teilhabe){
                if ($faulty) {
                    header("location: teilhabe.php?action=login&faulty=true");
                    exit;
                } elseif ($wrong) {
                    header("location: teilhabe.php?action=login&wrong=true");
                    exit;
                }
            } else{
                if ($page == 0) {
                    header("location: index.php");
                    exit;
                } elseif ($page == 1) {
                    if ($faulty) {
                        header("location: index.php?action=login&faulty=true");
                        exit;
                    } elseif ($wrong) {
                        header("location: index.php?action=login&wrong=true");
                        exit;
                    }
                }
            }
        }
    }

    return $login;
}

function user_check_loginsyntax($login)
{
    return $login === core_securechars($login);
}

/**
 * Erfolgreiche Anmeldung eines Benutzers in der Datenbank eintragen
 * @param int $userID
 */
function user_log_loginSuccess($userID, $ip)
{
    $_SESSION['lastlogindate'] = time();
    $userID = (int)$userID;
    db_query("UPDATE account SET lastlogindate=NOW(), faultylogins = 0 WHERE Person_id=" . $userID . " LIMIT 1");
    log_add('login', $ip);
}

function user_log_loginFailure($login, $ip)
{
    db_query("UPDATE account SET faultylogins = faultylogins+1 WHERE login='" . db_secure_chars($login) . "' LIMIT 1");
    log_add('loginfailed', $ip . "," . $login);
}

/**
 * Gbit true zurück, wenn der aktuelle Benutzer
 * das Benutzerkonto mit der ID "$userID" bearbeiten darf,
 * false sonst.
 * @param int $userID
 * @return boolean
 */
function user_check_editpermission($userID)
{
    $userID = (int)$userID;
    $result = false;

    if ($userID == $_SESSION['userID']) { //Eigenes Benutzerkonto dürfen alle Benutzer bearbeiten
        $result = true;
    } elseif (user_check_access('admin', false)) { //Admins dürfen alle Benutzerkonten bearbeiten
        $result = true;
    } elseif (user_check_access('contactor_manager', false)) { //contactor_manager dürfen nur reine contactor bearbeiten
        if (user_checkExclusiveRole($userID, "contactor")) {
            $result = true;
        }
    }
    return $result;
}

function user_update_password($newPw1, $newPw2, $userID)
{
    $userID = (int)$userID;
    $result = false;

    if (user_check_password($newPw1, $newPw2)) {
        db_query(
            "
				UPDATE
					account
				SET
					password='" . passhash::hash(md5($newPw1)) . "',
					modifydate=NOW()
				WHERE
					Person_id=" . (int)$userID . "
			"
        );
        $result = true;
    }
    return $result;
}

function user_update_roles($userID, $roles)
{
    //Nur gültige Rollen zulassen
    $roles = array_intersect($roles, user_getUserRoles());
    @$rolesString = implode(",", $roles);
    $userID = (int)$userID;
    db_query(
        "	UPDATE
				account
			SET
				roles='" . db_secure_chars($rolesString) . "',
				modifydate=NOW()
			WHERE
				Person_id=" . $userID
    );
}

function user_update_faultylogins($userID, $faultylogins)
{
    $userID = (int)$userID;
    $faultylogins = (int)$faultylogins;
    db_query(
        "
			UPDATE
				account
			SET
				faultylogins=" . $faultylogins . "
			WHERE
				Person_id=" . $userID . "
		"
    );
}

function user_resetLoginFailure($userId)
{
    db_query("UPDATE account SET faultylogins = 0 WHERE Person_id='" . db_secure_chars($userId) . "' LIMIT 1");
}


function user_update_contact($userID, $name, $lastname, $email, $strasse, $ort, $plz, $tel)
{
    $userID = (int)$userID;
    db_query(
        "	UPDATE
				person 
			SET
				vorname='" . db_secure_chars($name) . "',
				name='" . db_secure_chars($lastname) . "',
				email='" . db_secure_chars($email) . "',
				strasse ='" . db_secure_chars($strasse) . "',
				ort = '" . db_secure_chars($ort) . "',
				plz  ='" . db_secure_chars($plz) . "',
				tel='" . db_secure_chars($tel) . "'
			WHERE
				id=" . $userID

    );
    db_query(
        "UPDATE 
				account
			SET
				modifydate=NOW()				
			WHERE
				Person_id=" . $userID
    );
}

function user_update_maxChildren($userId, $maxChildren)
{
    $userID = (int)$userId;
    db_query(
        "UPDATE
				account
			SET
				maxChildren='" . (int)$maxChildren . "'
			WHERE
				Person_id =" . $userID
    );
}

//function user_update_kundennr($userId, $kundennr)
//{
//    $userID = (int)$userId;
//    db_query(
//        "UPDATE
//				account
//			SET
//				kundennr='" . (int)$kundennr . "'
//			WHERE
//				Person_id =" . $userID
//    );
//}

function user_activate($userId, $activated)
{
    $userID = (int)$userId;
    db_query(
        "UPDATE
				account
			SET
				activated='" . (int)$activated . "'
			WHERE
				Person_id =" . $userID
    );
}

function user_isActivated($userId)
{
    $userID = (int)$userId;
    $query = db_query(
        "SELECT
				activated
			FROM
				account
			WHERE 
				Person_id=" . $userID
    );
    $rs = db_fetch_array($query);
    if ($rs['activated'] == 1) {
        return true;
    } else {
        return false;
    }
}

function user_isGeschenkeshop($userId){
    $userID = (int)$userId;
    $query = db_query(
        "SELECT
				geschenkeshop
			FROM
				account
			WHERE 
				Person_id=" . $userID
    );
    $rs = db_fetch_array($query);
    if ($rs['geschenkeshop'] == 1) {
        return true;
    } else {
        return false;
    }
}

function user_isKinderurlaub($userId){
    $userID = (int)$userId;
    $query = db_query(
        "SELECT
				kinderurlaub
			FROM
				account
			WHERE 
				Person_id=" . $userID
    );
    $rs = db_fetch_array($query);
    if ($rs['kinderurlaub'] == 1) {
        return true;
    } else {
        return false;
    }
}

//return: TRUE wenn user unendlich viele Kinder haben kann
function user_getChildMax($userId)
{
    $userID = (int)$userId;
    $query = db_query(
        "SELECT
				maxChildren
			FROM
				account
			WHERE
				Person_id=" . $userID
    );
    $rs = db_fetch_array($query);
    if ($rs['maxChildren'] == 1) {
        return true;
    } else {
        return false;
    }
}

function user_mailIsUsed($mail)
{
    $mailadress = db_secure_chars($mail);
    $query = db_query("
					SELECT id
					FROM person
					WHERE email='" . $mailadress . "'
					");
    $result = db_fetch_array($query);
    if ($result['id'] == 0) {
        return false;
    } else {
        return true;
    }
}

function user_update_mailChecked($userId, $mailChecked)
{
    $userID = (int)$userId;
    db_query(
        "UPDATE
				account
			SET
				checkedMail='" . (int)$mailChecked . "'
			WHERE
				Person_id =" . $userID
    );
}

function user_isMailConfirmed($userId)
{
    $userID = (int)$userId;
    $query = db_query("
					SELECT checkedMail
					FROM account
					WHERE Person_id=" . $userID
    );
    $result = db_fetch_array($query);
    if ($result['checkedMail'] == 1) {
        return true;
    } else {
        return false;
    }
}


function user_check_password($newPw1, $newPw2)
{
    return ($newPw1 == $newPw2 && strlen($newPw1) >= 5);
}

function user_pwCheck($userId, $pw)
{
    $passw = md5($pw);
    $userID = (int)$userId;
    $query = db_query("
			SELECT password 
			FROM account
			WHERE Person_id=" . $userID
    );
    $rs = db_fetch_array($query);
    if (passhash::check_password($rs['password'], $passw)) {
        return true;

    } else {
        return false;

    }
}

/**
 * Gibt true zurück, wenn der aktuelle Benutzer EINE der Rollen in $roles hat,
 * false sonst. Ist $logout == true, wird der Benutzer abgemeldet, wenn er nicht eine der Rollen hat.
 *
 * @param $roles string kommaseparierte Liste der Rollen
 * @param $logout boolean
 * @return boolean
 */
function user_check_access($roles = '', $logout = false)
{
    $result = false;
    $needles = preg_split('/,/', $roles, -1, PREG_SPLIT_NO_EMPTY);

    //
    if (isset($_SESSION['roles'])) {
        foreach ($needles as $needle) {
            if (in_array($needle, $_SESSION['roles'])) {
                //User hat eine der Rollen. Glückwunsch!
                $result = true;
                break;
            }
        }
    }

    if (!$result && $logout) {
        log_add('securityviolation', 'Benutzer wurde abgemeldet weil er die erforderliche Rolle nicht besitzt: ' . $roles);
        user_logout();
        header("location: index.php");
        echo "Keine Berechtigung!";
        print_r($_SESSION["roles"]);
        exit();
    }

    return $result;
}

/**
 * Gibt true zurück, wenn der Benutzer mit $userID
 * ausschließlich die Rolle $role hat,
 * false sonst.
 * @param int $userID
 * @param string $role
 * @return boolean
 */
function user_checkExclusiveRole($userID, $role)
{
    $userID = (int)$userID;
    $user_roles = user_roles($userID);

    if (isset($user_roles[0]) && $user_roles[0] === $role && count($user_roles) == 1) {
        return true;
    } else {
        return false;
    }
}


/**
 * Gibt ein flaches Array mit Strings der Rollen eines Benutzers zurück.
 * @param int $userID
 * @return multitype: Array of Strings,
 */
function user_roles($userID)
{
    $userID = (int)$userID;
    $query = db_query("SELECT roles FROM account WHERE Person_id=" . $userID . " LIMIT 1");
    $rs = db_fetch_array($query);

    $curr_roles = preg_split('/,/', $rs['roles'], -1, PREG_SPLIT_NO_EMPTY);

    //Nur Rollen ausgeben, die auch in der Konfiguration (conf.php) vorgesehen sind
    return array_intersect($curr_roles, user_getUserRoles());
}

function user_printRolesSelect($user_roles = Array())
{
    ?>
    <select name="roles[]" size="5" multiple="multiple" style="width:100%">
        <?php
        $rollen = user_getUserRoles();
        foreach ($rollen as $rolle => $value) {
            echo '<option value="' . htmlspecialchars($value) . '"' . ((in_array($value, $user_roles)) ? "selected=\"selected\"" : "") . '>' . htmlspecialchars($value) . "</option>";
        }
        ?>
    </select>
    <?php
}

/**
 * Schreibt den HTML-String eines Select-Feldes,
 * das alle Benutzer auflistet, die die Rolle $role haben.
 * @param int $selectedID
 * @param string $role
 * @param boolean $showAll
 */
function user_printSelect_byRole($selectedID, $role, $showAll = false)
{
    ?>
    <select name="userID" size="1">
        <?php
        if ($showAll) {
            echo '<option value="-1">alle</option>';
        }
        $users = user_listByRole($role);
        while ($rs = db_fetch_array($users)) {
            echo '<option value="' . $rs['id'] . '"' . (($selectedID == $rs['id']) ? "selected=\"selected\"" : "") . '>' . htmlspecialchars($rs['login']) . "</option>";
        }
        ?>
    </select>
    <?php
}


/**
 * Gibt den Benutzernamen (Login) zu einer gegebenen ID zurück.
 * Wird die ID nicht gefunden, wird "gelöscht/unbekannt" zurückgegeben.
 * @param $userID
 * @return string
 */
function user_loginByID($userID)
{
    //Temporärer Cache für Login-Namen (in den Listen wird häufig der gleiche Name abgerufen)
    static $userLoginCache = array();

    $userID = (int)$userID;
    //prüfen ob im cache
    if (array_key_exists($userID, $userLoginCache)) {
        return $userLoginCache[$userID];
    } else {
        //daten laden
        $query = db_query("SELECT login FROM account WHERE Person_id=" . $userID . " LIMIT 1");
        $rs = db_fetch_array($query);

        if (!$rs['login']) $rs['login'] = "|gelöscht/unbekannt|";

        //cache befüllen
        $userLoginCache[$userID] = $rs['login'];
        return $rs['login'];
    }
}

/**
 * Gibt alle Benutzer zurück, mit Ausnahme der ID 0 (System-benutzer)
 * @return resource
 */
function user_list()
{
    $user_query = db_query(
        "	SELECT
				id,
				login,
				name,
				vorname,
				email,
				roles,
				lastlogindate,
				createby,
				createdate,
				activated,
				maxChildren,
				checkedMail,
			FROM
				account JOIN person ON Person_id = id
			WHERE
				id > 0 AND roles = 'benutzer'
			ORDER BY login"
    );
    return $user_query;
}

function user_list_admins()
{
    $user_query = db_query(
        "	SELECT
				id,
				login,
				name,
				vorname,
				email,
				roles,
				lastlogindate,
				createby,
				createdate,
				activated,
				maxChildren,
				checkedMail

			FROM
				account JOIN person ON Person_id = id
			WHERE
				id > 0 AND roles in ('admin', 'mitarbeiter')
			ORDER BY login"
    );
    return $user_query;
}

/**
 * Gibt eine Liste der Benutzer zurück, die die Rolle $role tragen.
 * Ist $exclusiv == true, werden nur die Benutzer ausgegeben, die
 * keine andere Rolle besitzen als in $role angegeben.
 *
 * @param String $role
 * @param boolean $exclusiv
 * @return resource
 */
function user_listByRole($role, $exclusive = false)
{
    if ($exclusive) {
        $and = " AND roles LIKE '" . db_secure_chars($role) . "' ";
    } else {
        $and = " AND FIND_IN_SET('" . db_secure_chars($role) . "',roles) > 0";
    }

    $user_query = db_query(
        "	SELECT
				id,
				login,
				name,
				vorname,
				email,
				roles,
				lastlogindate,
				createby,
				createdate,
				activated,
				maxChildren,
				checkedMail
			FROM
				account JOIN person ON Person_id = id
			WHERE
				id > 0
			" . $and . "
			ORDER BY login"
    );
    return $user_query;
}

function user_idByEmail($email)
{
    $mail = db_secure_chars($email);
    $query = db_query("
					SELECT id
					FROM person
					WHERE email='" . $mail . "'
					");
    $result = db_fetch_array($query);
    if ($result['id']) {
        $userID = (int)$result['id'];
        return $userID;
    } else {
        return false;
    }
}

function user_byID($id)
{
    $id = (int)$id;
    $user_query = db_query(
        "	SELECT
				login,
				name,
				vorname,
				email,
				roles,
				strasse,
				plz,
				ort,
				tel,	
				lastlogindate,
				faultylogins,
				createby,
				createdate,
				activated,
				checkedMail,
				maxChildren
				
			FROM
				account JOIN person ON Person_id = id
			WHERE
				id = " . $id . "
			LIMIT 1
		"
    );
    $rs = db_fetch_array($user_query);
    return $rs;
}

function user_getUserRoles()
{
    return preg_split('/,/', CONF_USERROLES, -1, PREG_SPLIT_NO_EMPTY);
}

function user_add($personId, $login, $passwd, $roles)
{
    if (is_array($roles)) {
        $rolesString = implode(",", $roles);
    } else {
        $rolesString = $roles;
    }
    $dbresult = db_query(
        "	INSERT INTO
				account
				(
					Person_id,
					login,
					password,
					roles,
					createby,
					createdate
				)
				VALUES
				(	'" . db_secure_chars($personId) . "',
					'" . db_secure_chars($login) . "',
					'" . passhash::hash(md5($passwd)) . "',
					'" . db_secure_chars($rolesString) . "',
					4,
					NOW()
				)"
    );
    if (!$dbresult) {
        return false;
    }
    return true;
}

function user_add_new($personId, $passwd, $roles, $dateisammlungId)
{
    if (is_array($roles)) {
        $rolesString = implode(",", $roles);
    } else {
        $rolesString = $roles;
    }
    $dbresult = db_query(
        "	INSERT INTO
				account
				(
					Person_id,
					password,
					roles,
					createby,
					createdate,
					Dateisammlung_id
				)
				VALUES
				(	'" . (int)$personId . "',
					'" . passhash::hash(md5($passwd)) . "',
					'" . db_secure_chars($rolesString) . "',
					4,
					NOW(),
					'".(int)$dateisammlungId."'
				)"
    );
    if (!$dbresult) {
        return false;
    }
    return true;
}

/**
 * Löscht den Angegebenen Benutzer.
 * @param int $userID
 */
function user_deleteByID($userID)
{
    $userID = (int)$userID;
    $result = false;
    $a = db_query("DELETE FROM account WHERE Person_id='$userID' AND Person_id > 0  LIMIT 1");
    $b = db_query("DELETE FROM token WHERE Person_id='$userID'  AND Person_id > 0");
    $c = db_query("DELETE FROM antrag WHERE Person_id= '$userID' AND Person_id >0 LIMIT 1");
    $d = db_query("DELETE FROM person WHERE id='$userID' AND id > 0  LIMIT 1");
    if($a && $b && $c && $d){
        $result = true;
    }
    return $result;
}

function user_deleteKinder($userID){
    $userId = (int) $userID;
    $result = false;
    $query  = db_query("SELECT Person_id FROM kind WHERE antrag_person_id ='".$userId. "'");
    while($rs = db_fetch_array($query)){
        if(kind_delete($rs['Person_id'])){
            $result = true;
        }else{
            $result = false;
        }
    }
    return $result;
}

/**
 * Gibt true zurück, wenn der Login-Name $login noch nicht existiert, false sonst.
 * @param String $login
 * @return boolean
 */
function user_check_unique($login)
{
    if (db_secure_chars($login) != '') {
        $query = db_query("SELECT count(*) AS resultcount FROM account WHERE login LIKE '" . db_secure_chars($login) . "'");
        $rs = db_fetch_array($query);

        if (!$rs['resultcount']) {
            return true;
        }
    }
    return false;
}

function user_setPassword($userId, $password)
{
    $userID = (int)$userId;
    $pw = db_secure_chars($password);
    $query = db_query("
					UPDATE account
					SET password='" . $pw . "'
					WHERE Person_id='" . $userID . "'
					");
    if ($query) {
        return true;
    } else {
        return false;
    }
}

/**
 * Gibt die ID des aktuellen Benutzers zurück
 * @return int UserID
 */
function user_getUserID()
{
    return (int)$_SESSION['userID'];
}

function user_getLogin()
{
    return $_SESSION['login'];
}

function user_getKinder()
{
    $userId = (int)$_SESSION['userID'];
    $query = db_query("
			SELECT id, vorname
			FROM person JOIN kind on Person_id = id
			WHERE Antrag_Person_id =" . $userId
    );
    return $query;

}

function user_getKinderAnzahl($userID){
    $userId = (int) $userID;
    $anzahl = false;
    $query = db_query("
                   SELECT COUNT(Person_id)
                   FROM kind
                   WHERE Antrag_Person_id =" .$userId
    );
    while($count = db_fetch_array($query)){
        $anzahl = $count["0"];
    }
    return $anzahl;
}

function user_hatGeschenke($userID){
    $userId = (int) $userID;
    $kinderArr = user_getKinderAdmin($userId);
    $result = false;
    while($kind = db_fetch_array($kinderArr)){
        if(kind_hatGeschenk($kind['id'])){
            $result = true;
        }
    }
    return $result;
}

function user_hatKinder($userId){
    $userID = (int)$userId;
    $result = false;
    $query = db_query("
			SELECT id, vorname, name, geburtstag, geschlecht
			FROM person JOIN kind on Person_id = id
			WHERE Antrag_Person_id =" . $userID
    );
    $rs = db_fetch_array($query);
    if(count($rs['id']) > 0){
        $result = true;
    }
    return $result;
}

function user_getKinderAdmin($userId)
{
    $userID = (int)$userId;
    $query = db_query("
			SELECT id, vorname, name, geburtstag, geschlecht
			FROM person JOIN kind on Person_id = id
			WHERE Antrag_Person_id =" . $userID
    );
    return $query;

}

function user_deactivate_all()
{

    db_query(
        "UPDATE account
			SET activated = '0'
			WHERE Person_id >0"
    );
}

function user_getAllEmail()
{
    $query = db_query(
        "SELECT email 
			 FROM person join account on id = Person_id
			 WHERE email IS NOT NULL 
			 AND roles = 'benutzer'"
    );
    return $query;
}

function user_getEmailsGeschenke(){
    $arrayId = array();
    $query = db_query(
            "SELECT Person_id from antrag"
    );
    while($rs = db_fetch_array($query)){
        array_push($arrayId, $rs['Person_id']);
    }
    $ids = implode(',', $arrayId);
    $query2 = db_query(
                "SELECT email FROM person JOIN account on id = Person_id
                 WHERE email IS NOT NULL 
                 AND   checkedMail = 1
                 AND   activated  = 1
                 AND   id NOT IN('".$ids."')
                 AND roles = 'benutzer'
                 ");
    return $query2;
}

function user_hatAntrag($userId)
{
    $userID = $userId;
    $query = db_query(
        "SELECT Person_id
			 FROM antrag
			 WHERE Person_id='" . $userID ."'
			 AND geloescht_von IS NULL
			 "
    );
    $rs = db_fetch_array($query);
    if ($rs) {
        return true;
    } else {
        return false;
    }
}

function user_resetEmailValidation($userId)
{
    $userID = $userId;
    $query = db_query("
                UPDATE account
                SET checkedMail = 0
                WHERE Person_id='" . $userID . "'
                ");
    if ($query) {
        return true;
    } else {
        return false;
    }
}

/**
 * Löscht die Session
 */
function user_logout()
{
    @session_unset();
    @session_destroy();
}

function user_checkRegistrationData($user, $person){
    if (!(empty($person["name"])
        || empty($person["vorname"])
        || empty($person["email"])
        || empty($person["plz"])
        || empty($person["ort"])
        || empty($person["strasse"])
        || empty($user["password"])
        || empty($person["readDatenschutz"]))
    ) {
        if (user_check_password($user["password"], $user["passwordCheck"])
            && geo_check_deutsche_plz(intval($person["plz"])) && !user_mailIsUsed($person['email'])
            && $person["readDatenschutz"] == "on") {
            return 1; //Alles in Ordnung
        } else {
            if (!geo_check_deutsche_plz(intval($person["plz"]))) {
                return 2; //Falsche PLZ
            }
            if (!user_check_password($user["password"], $user["passwordCheck"])) {
                return 3;//Passwort nicht erlaubt oder stimmen nicht überein
            }
            if(user_mailIsUsed($person["email"])){
                return 4;//Email adresse schon verwendet
            }
            if(!isset($person["readDatenschutz"])){
                return 5;//Checkbox nicht angewählt
            }
            return 6;//Anderer Fehler
        }
    } else {
        return 6;//Nicht alle Daten Ausgefüllt
    }
}