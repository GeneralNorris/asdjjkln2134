/**
 * Created by lvarnholt on 12.10.2016.
 */
$(function() {
    //--- tablesorter.com ---
    //Tablesorter an Node binden/ versch. Spalten nicht sortieren oder mit neuem Parser extrahieren (jquery.tablesorter.cutom_parsers.js)
    $("#dataTable").tablesorter({
        sortList: [[1,1],[2,1]] //--> Tabelle immer nach 1. und 2. Spalte aufsteigend sortieren.
    });
});
//END tablesorter.com ---