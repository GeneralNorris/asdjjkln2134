$(function() {
	//--- tablesorter.com ---   
	//Tablesorter an Node binden/ versch. Spalten nicht sortieren oder mit neuem Parser extrahieren (jquery.tablesorter.cutom_parsers.js)
	$("#dataTable").tablesorter({
		sortList: [[0,0],[1,1]] //--> Tabelle immer nach 1. und 2. Spalte aufsteigend sortieren.
	});
	//END tablesorter.com ---
	
	var $tableTRs = $("#dataTable tr");
	var $sourceTR;
	var $sourceA;
	
	
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
					
				},
				close: function(event, ui) {
					
				}
			});
		

	$tableTRs.find("a.edit, a.add").add("div.buttonbar a.add").click(function (e) { 
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
					$.ajax({
							type:"POST",
							url:$innerFORM.attr('action'), 
							data:$innerFORM.serialize(),
					        cache: false,
							success:function(data){
								//Wenn nichts in der Antwort steht, ist die Bearbeitung ohne Fehler abgeschlossen
								if(jQuery.trim(data) == ""){
										$editDialog.dialog('close');
//										var url = "";
//										window.location.href =url;
										window.location.reload();
								}else{
									//Fehlermeldungen über dem Formular ausgeben
									$editDialog.find('#errors').remove();
									$innerFORM.before('<div id="errors">'+data+'</div>');
								}
							}
					});
					return false;
		 		});
		 		
			}
		);
	});
//END editDialog ---
});