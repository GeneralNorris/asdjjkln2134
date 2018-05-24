<?php
require_once ("functions/texts.php");
function mail_send($to , $subject , $message){

	// ############################
	// # Zusaetzliche SMTP Header #

	$additional_headers = "MIME-Version: 1.0" . "\r\n";
	$additional_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	// Absender
	if (defined("SMTP_FROM")){
		$additional_headers .= "From: ".SMTP_FROM."\n";
	}

	// # Zusaetzliche SMTP Header #
	// ############################

	
	// ###################################
	// # Zusaetzliche Sendmail Parameter #
	$additional_parameters = " ";
	if (defined("SMTP_ENVELOPE_SENDER")){
		$additional_parameters .= "-f ".SMTP_ENVELOPE_SENDER." ";
	}
	// # Zusaetzliche Sendmail Parameter #
	// ###################################
	
	if(filter_var($to, FILTER_VALIDATE_EMAIL)) {

		mail ( $to ,  utf8_decode($subject) ,  $message ,  $additional_headers ,  $additional_parameters );
	} else {
		//log_add("");
	}
}

function mail_send_testMail($test){

    $text = "<html>";
    $text .= text_getText($test);
    $text .= "</html>";

//    $text = html_entity_decode($text);
//	mail_send($antrag["email"], CONF_APPNAME.": Wir haben Ihren Antrag geprüft und genehmigt.", $text);
    mail_send("l.varnholt@mediadialog.de",CONF_APPNAME.": Wir haben Ihren Antrag geprüft und genehmigt.", $text);
    mail_send("lukas.varnholt@student.uni-siegen.de",CONF_APPNAME.": Wir haben Ihren Antrag geprüft und genehmigt.", $text);
}

function mail_send_antragAdd($antragId){
	$antrag = antrag_getById($antragId);
	$text = "<html>";
	$text .= text_getText("email_geschenkwunschEingegangen");
    $text .= "</html>";
    $text = str_replace("%vorgangsnummer%",$antrag['Vorgang_id'],$text);
//    $text = html_entity_decode($text);
	//Mail an Admin -> Ein Antrag ist da
    $subject = CONF_APPNAME.": Wir haben ihre Geschenkwünsche erhalten";
    $subject = "=?utf-8?b?".base64_encode($subject)."?=";
	mail_send(CONF_SYSTEM_MAIL_ADDRESS, CONF_APPNAME.": Ein neuer Geschenke-Wunsch ist eingegangen", "Es ist ein neuer Antrag eingegangen. Vorgangsnummer: ".$antrag['Vorgang_id']);
	mail_send($antrag["email"], $subject, $text);
	
}

function mail_send_antragFreigeschaltet($antragId){
	$antrag = antrag_getById($antragId);
    $text = "<html>";
    $text .= text_getText("email_geschenkFreigeschaltet");
    $text .= "</html>";
    $text = str_replace("%vorgangsnummer%",$antrag['Vorgang_id'],$text);
//    $text = html_entity_decode($text);
    $subject = CONF_APPNAME.": Ihre Geschenkwünsche wurden bestätigt";
    $subject = "=?utf-8?b?".base64_encode($subject)."?=";
	mail_send($antrag["email"], $subject, $text);
}

function mail_send_spendeEinesGeschenksBestaetigt($geschenk){
	$antrag = antrag_getById($geschenk["Antrag_Person_id"]);
    $text = "<html>";
	$text .= text_getText("email_spendeEingegangen");
    $text .= "</html>";
    $text = str_replace("%antragsnummer%",$antrag['Vorgang_id'],$text);
    $text = str_replace("%geschenk%",$geschenk['bezeichnung'],$text);
    $text = str_replace("%kind%",$geschenk['vorname'],$text);#
//    $text = html_entity_decode($text);
    $subject = CONF_APPNAME.": Ein Spender für das Geschenk für ".$geschenk['vorname']." wurde gefunden.";
    $subject = "=?utf-8?b?".base64_encode($subject)."?=";
	mail_send($antrag["email"], $subject, $text);
}


function mail_send_spendeAdd($spendeId){
	$spende = spende_getById($spendeId);
    $text = "<html>";
	$text .= text_getText("email_anSpender");
    $text .= "</html>";
    $text = str_replace("%spendenhoehe%",$spende['summe'],$text);
    $text = str_replace("%spendennummer%",$spende['Vorgang_id'],$text);
//    $text = html_entity_decode($text);
    $subject = CONF_APPNAME.": Vielen Dank für Ihre Spendenzusage";
    $subject = "=?utf-8?b?".base64_encode($subject)."?=";
	mail_send(CONF_SYSTEM_MAIL_ADDRESS, CONF_APPNAME.": Eine neue Spende ist eingegangen", "Es ist eine neue Spende eingegangen.\n Bitte prüfen! Vorgangsnummer:".$spende['Vorgang_id']);
	mail_send($spende["email"], $subject, $text);
}

//TODO: ??? wenn ein Geschenk versendet ist
function mail_send_confirmationLink($url,$email){
    $text = "<html>";
    $text .= text_getText("email_ConfirmationLink");
    $text .= "</html>";
    $subject = CONF_APPNAME.": Bitte Bestätigen sie ihre Email-Adresse";
    $subject = "=?utf-8?b?".base64_encode($subject)."?=";
//    $text = html_entity_decode($text,null,'UTF-8');
	mail_send($email, $subject, $url.$text);

}

function mail_send_passwordLink($url,$email){
    $text = "<html>";
    $text .= text_getText("email_passwordResetLink");
    $text .= "</html>";
//    $text = html_entity_decode($text,null,'UTF-8');
    $subject = CONF_APPNAME.": Link aufrufen um Passwort zurückzusetzen";
    $subject = "=?utf-8?b?".base64_encode($subject)."?=";
    mail_send($email, $subject, $text.$url);
}

function mail_send_mailConfirmed($userId){
	$user =user_byID($userId);
    $text = "<html>";
    $text .= text_getText("email_mailConfirmed");
    $text .= "</html>";
//    $text = html_entity_decode($text,null,'UTF-8');
    $subject = CONF_APPNAME.": Email-Adresse Bestätigt";
    $subject = "=?utf-8?b?".base64_encode($subject)."?=";
	mail_send(CONF_SYSTEM_MAIL_ADDRESS, CONF_APPNAME, "Der Account mit dem Login:".$user['login']." und dem Namen:".$user['name']." ".$user['vorname']." hat sich Registriert und die Email-Adresse bestätigt");
    mail_send($user['email'], $subject, $text);
}

function mail_send_mailRegistered($userId){
    $user =user_byID($userId);
    $text = "<html>";
    $text .= text_getText("email_benutzerRegistriert");
    $text .= "</html>";
//    $text = html_entity_decode($text,null,'UTF-8');
    $subject = CONF_APPNAME.": Registrierung";
    $subject = "=?utf-8?b?".base64_encode($subject)."?=";
    mail_send(CONF_SYSTEM_MAIL_ADDRESS, CONF_APPNAME, "Der Account mit dem Login:".$user['login']." und dem Namen:".$user['name']." ".$user['vorname']." hat sich Registriert");
    mail_send($user['email'], $subject, $text);
}

function mail_send_accountActivated($personId){
	$user = user_byID($personId);
    $text = "<html>";
	$text .= text_getText("email_accountAktiviert");
    $text .= "</html>";
    $text = str_replace("%login%",$user['login'],$text);
//    $text = html_entity_decode($text,null,'UTF-8');
    $subject = CONF_APPNAME.": Ihr Account wurde Aktiviert";
    $subject = "=?utf-8?b?".base64_encode($subject)."?=";
	mail_send(CONF_SYSTEM_MAIL_ADDRESS, CONF_APPNAME, "Der Account mit der Email:".$user['email']."wurde Aktiviert");
	mail_send($user['email'], $subject, $text);
			
}

//function mail_send_accountDeactivated($personId){
//	$user = user_byID($personId);
//    $text = "<html>";
//	$text .= text_getText("userDeaktiviert");
//    $text .= "</html>";
//    $text = str_replace("%login%",$user['login'],$text);
////    $text = html_entity_decode($text,null,'UTF-8');
//	mail_send(CONF_SYSTEM_MAIL_ADDRESS, CONF_APPNAME, "Der Account mit der IdentNr:".$user['kundennr']."wurde deaktiviert");
//	mail_send($user['email'], CONF_APPNAME.": Ihr Account wurde deaktiviert", $text);
//}


//function mail_send_kindDeleted($mutterId){
//	$user = user_byID($mutterId);
//    $text = "<html>";
//	$text .= text_getText("kindDeleted");
//    $text .= "</html>";
////    $text = html_entity_decode($text,null,'UTF-8');
//	mail_send(CONF_SYSTEM_MAIL_ADDRESS, CONF_APPNAME, "Der Account mit der IdentNr:".$user['kundennr']."hatte ein zu altes Kind, welches gelöscht wurde");
//	mail_send($user['email'], CONF_APPNAME.": Ihr Kind ist zu Alt geworden", $text);
//}


//Sendet Email an alle Mütter
function mail_send_antraegeVerfuegbar(){
	$emaillist = user_getEmailsGeschenke();
    $text = "<html>";
    $text .= text_getText("email_geschenkeshopOnline");
    $text .= "</html>";
//    $text = html_entity_decode($text,null,'UTF-8');
    $subject = CONF_APPNAME.": Geschenke jetzt Verfügbar";
    $subject = "=?utf-8?b?".base64_encode($subject)."?=";
	while($mails = db_fetch_array($emaillist)){
	    $email = $mails['email'];
		mail_send($email, $subject, $text);
	}
}
//Sendet Email an eine Mutter
function mail_send_geschenkVerfuegbar($mutterId){
    $user = user_byID($mutterId);
    $email = $user['email'];
    $text = "<html>";
    $text .= text_getText("email_geschenkeshopOnline");
    $text .= "</html>";
//    $text = html_entity_decode($text,null,'UTF-8');
    $subject = CONF_APPNAME.": Geschenke jetzt Verfügbar";
    $subject = "=?utf-8?b?".base64_encode($subject)."?=";
    mail_send($email, $subject, $text);
}
