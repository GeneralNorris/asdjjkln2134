//Parser für deutsches Datum hinzufügen
$.tablesorter.addParser({
	id: 'germandate',
	is: function(s) {
		return false;
	},
	format: function(s) {
		var a = s.split('.');
		if(a == "---" || a == "" ){ // Wenn kein Datum in der Zelle steht
			a[0] = 1;
			a[1] = 1;
			a[2] = 1;
		}else{
			a[1] = a[1].replace(/^[0]+/g,"");
		}
		return new Date(a.reverse().join("/")).getTime();
	},
	type: 'numeric'
});

$.tablesorter.addParser({
	id: 'innerFirst_A_numeric',
	is: function(s) {
		return false;
	},
	format: function(s) {
		var jqObj = $('<div>'+s+'</div>');
		var aNode = jqObj.find("a:first");
		if(aNode.html() == null){
			return parseInt(s);
		}else{
			return parseInt(aNode.html());
		}
	},
	type: 'numeric'
});

$.tablesorter.addParser({
	id: 'innerFirst_A_text',
	is: function(s) {
		return false;
	},
	format: function(s) {
		var jqObj = $('<div>'+s+'</div>');
		var aNode = jqObj.find("a:first");
		if(aNode.html() == null){
			return s;
		}else{
			return aNode.html();
		}
	},
	type: 'text'
});

$.tablesorter.addParser({ //Benötigt: .tablesorter({textExtraction: function(node){return node.innerHTML}, ... 
	id: 'innerFirst_A_sortorder',
	is: function(s) {
		return false;
	},
	format: function(s) {
		var jqObj = $('<div>'+s+'</div>');
		var aNode = jqObj.find("a:first");
		return parseInt(aNode.attr('sortorder'));
	},
	type: 'numeric'
});

$.tablesorter.addParser({ 
	id: 'ipaddress2', 
	is: function(s) { 
		return false; 
	},
	format: function(s) {
		function pad(number, length) {
			var str = '' + $.trim(number);
			while (str.length < length) {
				str = '0' + str;
			}
			return str;
		}
		var n = s.split(".");
		return '1'+pad(n[0],3)+ pad(n[1],3)+pad(n[2],3)+pad(n[3],3);
	},
	type: 'numeric' 
});