/**
 * Gemeinsam genutzte Objekte/Funktionen
 */

$(function() {
	$("#helpbutton").button();
	
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
	
	//SUCHE in den Zeilen der dataTable	
 	//Array für die Suche vorbereiten
	  var haystack = [];
	  $('#dataTable>tbody>tr').each(function(i, ele){
	    haystack.push({id:$(this).attr('id'), keywords:$(this).text()});
	  });
	  $('#pattern').keyup(function(){
	    var pattern = $.trim($(this).val());
	    if( pattern.length == 0){
	     // Suchfeld leer, alles anzeigen:
	      $('#dataTable>tbody>tr').css('display','');
	      $('#pattern').css('border-color','');
	    } else if( pattern.length >= 2 ){
	      //Suchen!
	      //pattern maskieren: https://developer.mozilla.org/en/docs/Web/JavaScript/Guide/Regular_Expressions
	      pattern = pattern.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
	      //haystack durchsuchen und Element-IDs mit Treffer zurückgeben
	      var trefferArr = jQuery.map(haystack, function (value, index) {
	        var regexp = new RegExp(pattern,"gi");
	        if(value.keywords.match(regexp)) return value.id;
	        return null;
	      });
	      $('#pattern').css('border-color','red');
	      //Alles Ausblenden
	      $('#dataTable>tbody>tr').css('display','none');
	      //Treffer einblenden
	      $.each(trefferArr, function(i, ele){
	        $('#dataTable>tbody>tr[id=\"'+this+'"]').css('display','');
	      });
	    }
	  });
	  //END SUCHE in den Zeilen der dataTable
	  
	  //Drucken-Button
	  $('#print').click(function(e){
		  e.preventDefault();
		  window.print();
	  });
});
