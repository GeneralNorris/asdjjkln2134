/**
 * Created by lvarnholt on 22.07.2016.
 */
$(function() {
    //--- tablesorter.com ---
    //Tablesorter an Node binden/ versch. Spalten nicht sortieren oder mit neuem Parser extrahieren (jquery.tablesorter.cutom_parsers.js)
    $("#dataTable").tablesorter({headers: { 1:{sorter:'text'}, 3:{sorter:'innerFirst_A_text'},  8:{sorter: false} }});
    //END tablesorter.com ---

    var $tableTRs = $("#dataTable tr");
    var $sourceTR;
    var $sourceA;

//--- removeDialog ---
    var $removeDialog = $('<div></div>')
        .dialog({
            closeOnEscape: false,
            autoOpen: false,
            modal: true,
            buttons: {
                'Abbrechen': function() {
                    $(this).dialog('close');
                },
                'Ja': function(e) {
                    var dialog = $(this);
                    //den Link asynchron im Hintergrund aufrufen
                    $.get($sourceA.attr('href')+'&ajax=true',	function(data) {
                        if(jQuery.trim(data) == ""){
                            $sourceTR.css('background-color', '#FFB2B2');
                            //Request war erfolgreich, Zeile in der Tabelle löschen und Dialog schließen
                            $sourceTR.fadeOut('slow',function(){$sourceTR.remove();});
                            dialog.dialog('close');
                        }else{
                            $removeDialog.html('<div id="action">'+data+'</div>');
                            $(":button:contains('Ja')").attr("disabled","disabled").addClass('ui-state-disabled');
                        }
                    });
                }
            },
            open: function(event, ui) {
                $(this).html('Diesen Eintrag löschen?');
                $(":button:contains('Ja')").removeAttr("disabled").removeClass('ui-state-disabled');
                $(this).parent().children().children('.ui-dialog-titlebar-close').hide(); //"X" zum schließen des Dialoges verbergen
                //hostRowColor = $sourceTR.css('backgroundColor');
                var tmp = $sourceTR.attr('style');
                hostRowColor = tmp.substr(tmp.lastIndexOf(':')+1);
                $sourceTR.css('background-color', 'silver');
            },
            close: function(event, ui) {
                $sourceTR.animate({	"background-color": hostRowColor }, 2000, function(){});
            }
        });

    $tableTRs.find("a.delete").click(function (e) {
        e.preventDefault();
        $sourceTR = $(this).parent().parent(); //Verweis auf Zeile, sehr unschön weil global. Als Eigenschaft des Dialoges anlegen!?
        $sourceA = $(this);
        $removeDialog.dialog("option" , {title: $(this).attr( 'title' )+'?' } );
        $removeDialog.dialog('open');
    });

//END removeDialog --

//--- editDialog ---
    var $editDialog = $('<div id="action"></div>')
        .dialog({
            height: 550,
            width: 490,
            closeOnEscape: true,
            autoOpen: false,
            modal: true,
            buttons: {
                'Abbrechen': function() {
                    $(this).dialog('close');
                },
                'Senden': function() {
                    $editDialog.find('form:not(.noJQuery)').submit();
                }
            },
            open: function(event, ui) {
                if(!$sourceA.hasClass('add')){
                    //hostRowColor = $sourceTR.css('backgroundColor');
                    var tmp = $sourceTR.attr('style');
                    hostRowColor = tmp.substr(tmp.lastIndexOf(':')+1);
                    $sourceTR.css('background-color', 'silver');
                }
            },
            close: function(event, ui) {
                if(!$sourceA.hasClass('add')){
                    $sourceTR.animate({	"background-color": hostRowColor }, 2000, function(){});
                }
            }
        });

    $tableTRs.find("a.edit, a.add").click(function (e) {
        e.preventDefault();
        $sourceTR = $(this).parent().parent();
        $sourceA = $(this);
        $editDialog.dialog("option" , {title: $(this).attr( 'title' )} );
        $editDialog.html("Loading...");
        $editDialog.dialog('open');
        //Inhalt des Dialoges async laden
        $editDialog.load($(this).attr('href')+'&ajax=true',
            function(data) {
                var $innerFORM = $editDialog.find('form:not(.noJQuery)');
                //Forumlar Senden
                $innerFORM.submit(function() {
                    $.post(
                        $innerFORM.attr('action')+'&ajax=true',
                        $innerFORM.serialize(),
                        function(data){
                            //Wenn nichts in der Antwort steht, ist die Bearbeitung ohne Fehler abgeschlossen
                            if(jQuery.trim(data) == ""){
                                if($sourceA.hasClass('add')){
                                    var url = "portal.php?action=user";
                                    window.location.href =url;
                                }else{

                                    //Wenn die Antwort empfangen wurde, Dialog schließen und Daten in Tabelle aktualisieren
                                    var fields = $innerFORM.serializeArray();//Formulardaten in Array ablegen
                                    $sourceTR.find("td[name='roles']").text(''); //Zelle auf 0 setzen, für den Fall, das die Checkbox nicht übertragen wurde(nicht angeklickt)
                                    jQuery.each(fields, function(i, field){

                                        if(fields[i].name == 'roles[]'){
                                            $sourceTR.find("td[name='roles']").append(fields[i].value + ","); // Zellen haben Atriibut "name" um sie zu identifizieren
                                        }else if(fields[i].name == 'email'){
                                            $sourceTR.find("td[name='"+ fields[i].name +"'] a").text(fields[i].value);
                                            $sourceTR.find("td[name='"+ fields[i].name +"'] a").prop('href', 'mailto:'+fields[i].value);
                                        }else{
                                            $sourceTR.find("td[name='"+ fields[i].name +"']").text(fields[i].value);
                                        }
                                    });
                                }
                                $editDialog.dialog('close');
                            }else{
                                //Fehlermeldungen über dem Formular ausgeben
                                $editDialog.find('#errors').remove();
                                $innerFORM.before('<div id="errors">'+data+'</div>');
                            }
                        }
                    );
                    return false;
                });
            }
        );
    });
//END editDialog ---
});