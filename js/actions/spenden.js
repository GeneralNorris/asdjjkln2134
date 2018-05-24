$(function() {
	//--- tablesorter.com ---   
	//Tablesorter an Node binden/ versch. Spalten nicht sortieren oder mit neuem Parser extrahieren (jquery.tablesorter.cutom_parsers.js)
	$("#dataTable").tablesorter();
	//END tablesorter.com ---
	
	var $tableTRs = $("#dataTable tr");
	var $sourceTR;
	var $sourceA;
		

	//--- confirmDialog ---
	var $confirmDialog = $('<div></div>')
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
						window.location.href = $sourceA.attr('href');
					}
				},
				open: function(event, ui) {
					$(this).html($sourceA.attr('title')+'?');
				}
			});
	$tableTRs.find("a.freigabe, a.delete").click(function (e) { 
		e.preventDefault(); 
		$sourceTR = $(this).parent().parent();
		$sourceA = $(this);
		$confirmDialog.dialog("option" , {title: '' } );
		
		$confirmDialog.dialog('open');
	});
	
	
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
				
				//FILEUPLOAD funktioniert so nicht __> HTML5? neue JQueryversion?
//				$innerFORM.submit(function() {
//					$.ajax({
//							type:"POST",
//							url:$innerFORM.attr('action'), 
//							data:$innerFORM.serialize(),
//					        cache: false,
//					        contentType: false,
//					        processData: false,
//							success:function(data){
//								//Wenn nichts in der Antwort steht, ist die Bearbeitung ohne Fehler abgeschlossen
//								if(jQuery.trim(data) == ""){
//										$editDialog.dialog('close');
//										var url = "";
//										window.location.href =url;
//								}else{
//									//Fehlermeldungen über dem Formular ausgeben
//									$editDialog.find('#errors').remove();
//									$innerFORM.before('<div id="errors">'+data+'</div>');
//								}
//							}
//					});
//					return false;
//		 		});
		 		
			}
		);
	});
//END editDialog ---
});