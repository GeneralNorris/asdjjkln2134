<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 09.03.2017
 * Time: 14:57
 */
$text1 = text_getText("text_startseite1");
$text2 = text_getText("text_startseite2");
$text3 = text_getText("text_startseite3");
$text4 = text_getText("text_startseite4");
$text5 = text_getText("text_startseite5");
$text6 = text_getText("text_startseite6");
?>
<!-- Banner -->
				<section id="banner">
					<header>
						<h2>Weihnachtsgeschenke für benachteiligte Kinder in Deutschland</h2>
					</header>
				</section>

<!-- CTA -->
                <section id="cta" class="wrapper style3">
                    <div class="container">
                    <header>
                        <h2><a href="index.php?action=spende">Spenden Sie jetzt ein Geschenk für ein Kind</a></h2>
                    </header>
                    </div>
                </section>

			<!-- Highlights -->
				<section class="wrapper style1">
					<div class="container">
                        <div id="text_startseite1" align="center" style="padding-bottom: 2em">
                            <?php echo $text1 ?>
                        </div>
						<div class="row 200%">
							<section class="4u 12u(narrower)">
								<div class="box highlight">
									<a href="https://www.kinderarmut-in-deutschland.de/erstanfrage/" target="_blank"><i class="icon major fa-pencil"></i></a>
                                    <div id="text_startseite2">
                                        <a href="https://www.kinderarmut-in-deutschland.de/erstanfrage/" target="_blank"><?php echo $text2 ?></a>
<!--                                        <h3>Registrieren Sie sich</h3>-->
<!--                                        <p>Melden Sie sich mit Ihren Daten. Sobald Ihre kompletten Daten vorliegen, werde Sie für das Engelbaum Projekt freigeschaltet. </p>-->
                                    </div>

								</div>
							</section>
							<section class="4u 12u(narrower)">
								<div class="box highlight">
									<i class="icon major fa-check-square-o"></i>
                                    <div id="text_startseite3">
                                        <?php echo $text3 ?>
<!--                                        <h3>Wählen Sie Geschenke aus</h3>-->
<!--                                        <p>Wählen Sie für jedes Ihrer Kindern ein passendes Geschenk aus. Wir verpacken dies und senden es kostenfrei zu.</p>-->
                                    </div>
								</div>
							</section>
							<section class="4u 12u(narrower)">
								<div class="box highlight">
									<i class="icon major fa-gift"></i>
                                    <div id="text_startseite4">
                                        <?php echo $text4 ?>
<!--                                        <h3>Freude schenken</h3>-->
<!--                                        <p>Ihr Kind wird sich über das Geschenk zu Weihnachten freuen. Genießen auch Sie den Moment der Freude in schwierigen Zeiten.</p>-->
                                    </div>
								</div>
							</section>
						</div>
					</div>
				</section>

			<!-- Gigantic Heading -->
				<!-- section class="wrapper style2">
					<div class="container">
						<header class="major">
							<h2>Kinderw&uuml;nsche erf&uuml;llen!</h2>
							<p>Zaubern Sie am Weihnachtsabend ein Leuchten in die Augen notleidender Kinder in Deutschland.
							 In unserem Spendenshop finden Sie Geschenkw&uuml;nsche von Kindern, die sonst vielleicht kein Weihnachtsgeschenk bekommen.
							  Erf&uuml;llen Sie einem notleidenden Kind in Deutschland einen Weihnachtswunsch. Unser Spendenshop ist seit 22.12. geschlossen.<br><br>
                              Sie k&ouml;nnen hier gerne f&uuml;r unsere anderen Projekte spenden. &uuml;bers Jahr haben
                              wir viele Projekte ausschlie&szlig;lich f&uuml;r Kinder,
                              die von Kinderarmut betroffen sind. Vielen Dank f&uuml;r Ihre Unterst&uuml;tzung.</p>
						</header>
					</div>
				</section

			<!-- Posts -->
				<section class="wrapper style2">
					<div class="container">
						<div class="row">
							<section class="6u 12u(narrower)">
								<div class="box post">
									<a href="#" class="image left"><img src="frontend_theme/images/pic01.jpg" alt="" /></a>
									<div class="inner" id="text_startseite5">
                                        <?php echo $text5 ?>
<!--										<h3>Alleinerziehend? Hartz4 Empfänger?</h3>-->
<!--							<p>Wenn Ihre Familie von Kinderarmut betroffen ist und Sie in Deutschland wohnen, können Sie bei Engelbaum&reg;-->
<!--                                für jedes Kind bis 15 Jahre ein schönes, für Sie kostenloses Weihnachtsgeschenk aus unserer Wunschliste beantragen.<br><br>-->
<!--                                Wir freuen uns, Ihnen und Ihren Kindern in dieser sicher schwierigen Phase Ihres Lebens zu helfen.-->
<!--                                Die Anmeldung zum Engelbaum Projekt für alleinerziehende Mütter ist seit 15.Dezember geschlossen!</p>-->
									</div>
								</div>
							</section>
							<section class="6u 12u(narrower)">
								<div class="box post">
									<a href="#" class="image left"><img src="frontend_theme/images/pic02.jpg" alt="" /></a>
									<div class="inner" id="text_startseite6">
                                        <?php echo $text6 ?>
<!--										<h3>Kinderwünsche erfüllen!</h3>-->
<!--                                        <p>Zaubern Sie am Weihnachtsabend ein Leuchten in die Augen notleidender Kinder in Deutschland.-->
<!--                                            In unserem Spendenshop finden Sie Geschenkwünsche von Kindern, die sonst vielleicht kein Weihnachtsgeschenk bekommen.-->
<!--                                            Erfüllen Sie einem notleidenden Kind in Deutschland einen Weihnachtswunsch. Unser Spendenshop ist seit 22.12. geschlossen.<br><br>-->
<!--                                            Sie können hier gerne für unsere anderen Projekte spenden.-->
<!--                                            übers Jahr haben wir viele Projekte ausschließlich für Kinder, die von Kinderarmut betroffen sind.-->
<!--                                            Vielen Dank für Ihre Unterstützung.</p>-->
									</div>
								</div>
							</section>
						</div>
					</div>
				</section>

			<!-- CTA -->
				<section id="cta" class="wrapper style3">
					<div class="container">
						<header>
							<h2>Engelbaum&reg; hilft in schwierigen Zeiten</h2>
						</header>
					</div>
				</section>

<script>
//    window.onload = function() {
//        document.getElementById('makeEditable').onclick = makeEditable();
//        document.getElementById('saveTexts').onclick = saveTexts();
//    };
    $(document).ready(function(){
        var texts = $('body').find('#text_startseite1,#text_startseite2,#text_startseite3,#text_startseite4,#text_startseite5,#text_startseite6');
        $( "#makeEditable" ).bind({
            click: function() {
                makeEditable(texts);
            }
        });
        $( "#saveTexts" ).bind({
            click: function() {
                saveTexts(texts);
            }
        });
    });

    //Texte editierbar machen, CKEditor für jeden Text erstellen, Speicher-button zeigen
    //Arguments: text:string
    function makeEditable(text){
        text.attr('contenteditable', 'true');
        text.css('outline', '#000000 dotted thin');
        CKEDITOR.disableAutoInline = true;
        CKEDITOR.inline('text_startseite1');
        CKEDITOR.inline('text_startseite2');
        CKEDITOR.inline('text_startseite3');
        CKEDITOR.inline('text_startseite4');
        CKEDITOR.inline('text_startseite5');
        CKEDITOR.inline('text_startseite6');
        $('#makeEditable').hide();
        $("#saveTexts").show();
    }

    //Texte und deren Stellen in Arrays übertagen und anschließend per Ajax an saveTexts senden
    //Anschließend alle Textfelder wieder non-Editable machen, Speicher-Button entfernen
    //Arguments: text:string
    function saveTexts(text){
        var texts = [];
        var sites = [];
        texts[0] = CKEDITOR.instances.text_startseite1.getData();
        texts[1] = CKEDITOR.instances.text_startseite2.getData();
        texts[2] = CKEDITOR.instances.text_startseite3.getData();
        texts[3] = CKEDITOR.instances.text_startseite4.getData();
        texts[4] = CKEDITOR.instances.text_startseite5.getData();
        texts[5] = CKEDITOR.instances.text_startseite6.getData();

        sites[0] = "text_startseite1";
        sites[1] = "text_startseite2";
        sites[2] = "text_startseite3";
        sites[3] = "text_startseite4";
        sites[4] = "text_startseite5";
        sites[5] = "text_startseite6";

        $.ajax({
            url: 'index.php?action=do_saveTexts',
            type: 'POST',
            data: {
                texte : texts,
                sites : sites
            },
            success: function(msg) {
                if(msg.indexOf("success") != -1){
                    alert("Texte erfolgreich gespeichert!")
                }else if(msg.indexOf("false") != -1){
                    alert("Es ist ein Fehler aufgetreten - Texte konnten nicht gespeichert werden.")
                }
            }
        });
        $('#saveTexts').hide();
        for(name in CKEDITOR.instances)
        {
            CKEDITOR.instances[name].destroy(true);
        }
        text.attr('contenteditable', 'false');
        text.css('outline', 'none');
        $("#makeEditable").show();
    }

</script>