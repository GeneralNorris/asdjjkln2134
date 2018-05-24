/* Geändert 2011 mediaDialog 
 * Depends:
 *   - jQuery 1.4.2+
 *   - jQuery UI 1.8 widget factory
 *   - jQuery UI sortable
*/


/*
 * jQuery MultiSelect UI Widget 1.8
 * Copyright (c) 2010 Eric Hynds
 *
 * http://www.erichynds.com/jquery/jquery-ui-multiselect-widget/
 *
 * Depends:
 *   - jQuery 1.4.2+
 *   - jQuery UI 1.8 widget factory
 * Optional:
 *   - jQuery UI effects
 *   - jQuery UI position utility
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
*/

(function($, undefined){

var multiselectID = 0;

$.widget("ech.multiselect", {
	
	// default options
	options: {
		header: true,
		height: 175,
		minWidth: 225,
		classes: '',
		checkAllText: 'Alle auswählen',
		uncheckAllText: 'Auswahl entfernen',
		noneSelectedText: 'Bitte auswählen',
		selectedText: '# ausgewählt',
//MEDIADIALOG	
		addElementText: 'Eintrag hinzufuegen',
		delElementText: 'Eintrag löschen',
		separatorText: ' | ',
//END_MEDIADIALOG
		selectedList: 0,
		show: '',
		hide: '',
		autoOpen: false,
		multiple: true,
//MEDIADIALOG	
		editable: false,
//END_MEDIADIALOG
		position: {}
	},

	_create: function(){
		var el = this.element.hide(),
			o = this.options;
		this.speed = $.fx.speeds._default; // default speed for effects
		this._isOpen = false; // assume no
	
		var 
			button = (this.button = $('<button type="button"><span class="ui-icon ui-icon-triangle-2-n-s"></span></button>'))
				.addClass('ui-multiselect ui-widget ui-state-default ui-corner-all')
				.addClass( o.classes )
				.attr({ 'title':el.attr('title'), 'aria-haspopup':true })
				.insertAfter( el ),
			
			buttonlabel = (this.buttonlabel = $('<span></span>'))
				.html( o.noneSelectedText )
				.appendTo( button ),
				
			menu = (this.menu = $('<div />'))
				.addClass('ui-multiselect-menu ui-widget ui-widget-content ui-corner-all')
				.addClass( o.classes )
				.insertAfter( button ),
				
			header = (this.header = $('<div />'))
				.addClass('ui-widget-header ui-corner-all ui-multiselect-header ui-helper-clearfix')
				.appendTo( menu ),
				
			headerLinkContainer = (this.headerLinkContainer = $('<ul />'))
				.addClass('ui-helper-reset')
				.html(function(){
					if( o.header === true ){
						return '<li><a class="ui-multiselect-all" href="#"><span class="ui-icon ui-icon-check"></span><span>' + o.checkAllText + '</span></a></li><li><a class="ui-multiselect-none" href="#"><span class="ui-icon ui-icon-closethick"></span><span>' + o.uncheckAllText + '</span></a></li>';
					} else if(typeof o.header === "string"){
						return '<li>' + o.header + '</li>';
					} else {
						return '';
					}
				})
				.append('<li class="ui-multiselect-close"><a href="#" class="ui-multiselect-close"><span class="ui-icon ui-icon-circle-close"></span></a></li>')
				.appendTo( header ),
			
			checkboxContainer = (this.checkboxContainer = $('<ul />'))
				.addClass('ui-multiselect-checkboxes ui-helper-reset')
				.appendTo( menu );
//MEDIADIALOG	
		var deletedItems =(this.deletedItems = []);
		var newItems = (this.newItems = []);
		
		if(this.options.editable){
				footer = (this.footer = $('<div style="text-align:right; padding-right:17px"><input id="newItemName" type="text" size="33" maxlength="33" style="width:'+(this.options.minWidth-98)+'px"/><span title="'+o.addElementText+'" class="ui-multiselect-newbutton">+</span></div>'))
					.addClass('ui-widget-header ui-corner-all ui-multiselect-footer ui-helper-clearfix')
					.appendTo( menu );

			checkboxContainer.sortable({ axis: 'y', distance: 5 });
			//FIXME: Noch nicht gespeicherte Sortierung bleibt nicht erhalten beil löschen/erstellen von Kategorien 
			
		}
//END_MEDIADIALOG
		
		// perform event bindings
		this._bindEvents();
		
		// build menu
		this.refresh( true );
	},
	
	_init: function(){
		if( this.options.header === false ){
			this.header.hide();
		}
		if( !this.options.multiple ){
			this.headerLinkContainer.find('.ui-multiselect-all, .ui-multiselect-none').hide();
		}
		if( this.options.autoOpen ){
			this.open();
		}
		if( this.element.is(':disabled') ){
			this.disable();
		}
	},
	
	refresh: function( init ){
		var el = this.element,
			o = this.options,
			menu = this.menu,
			button = this.button,
			checkboxContainer = this.checkboxContainer,
			optgroups = [],
			id = el.attr('id') || multiselectID++; // unique ID for the label & option tags
		checkboxContainer.empty();
		
		// build items
		this.element.find('option').each(function(i){
			var $this = $(this), 
				title = $this.html(),
				value = this.value,
				inputID = this.id || 'ui-multiselect-'+id+'-option-'+i, 
				$parent = $this.parent(), 
				isDisabled = $this.is(':disabled'), 
				labelClasses = ['ui-corner-all'],
				label, input, li;
//MEDIADIALOG	
			var tooltip = "";
			if($(this).attr('title') != undefined){
				tooltip = $(this).attr('title');
			}
			var liid;
//END_MEDIADIALOG
			
			// is this an optgroup?
			if( $parent.is('optgroup') ){
				var optLabel = $parent.attr('label');
				
				// has this optgroup been added already?
				if( $.inArray(optLabel, optgroups) === -1 ){
					$('<li><a href="#">' + optLabel + '</a></li>')
						.addClass('ui-multiselect-optgroup-label')
						.appendTo( checkboxContainer );
					
					optgroups.push( optLabel );
				}
			}
			
			if( value.length > 0 ){
				if( isDisabled ){
					labelClasses.push('ui-state-disabled');
				}
//MEDIADIALOG	
				liid = id +'Order_'+value;
//END_MEDIADIALOG			
				li = $('<li />')
					.addClass(isDisabled ? 'ui-multiselect-disabled' : '')
//MEDIADIALOG					
					.attr({id:liid})
//END_MEDIADIALOG
					.appendTo( checkboxContainer );
					
				label = $('<label style="background-color:'+$this.attr('color')+'" title="'+tooltip+'"/>')
					.attr('for', inputID)
					.addClass(labelClasses.join(' '))
					.appendTo( li );
				
				// attr's are inlined to support form reset.  double checked attr is to support chrome bug - see #46
				$('<input type="'+(o.multiple ? 'checkbox' : 'radio')+'" '+(this.selected ? 'checked="checked"' : '')+ ' name="multiselect_'+id + '" />')
					.attr({ id:inputID, checked:this.selected, title:title, disabled:isDisabled, 'aria-disabled':isDisabled, 'aria-selected':this.selected })
					.val( value )
					.appendTo( label )
					.after('<span>'+title+'</span>');
//MEDIADIALOG		
				if(o.editable){
					$('<span title="'+o.delElementText+'">X</span>')
						.addClass('ui-multiselect-delbutton')
						.appendTo( label );
				}
//END_MEDIADIALOG
			}
		});
		
		// cache some moar useful elements
		this.labels = menu.find('label');
		
		// set widths
		this._setButtonWidth();
		this._setMenuWidth();
		
		// remember default value
		button[0].defaultValue = this.update();
		
		// broadcast refresh event; useful for widgets
		if( !init ){
			this._trigger('refresh');
		}
	},
	// updates the button text.  call refresh() to rebuild
	update: function(){
		var o = this.options,
			$inputs = this.labels.find('input'),
			$checked = $inputs.filter(':checked'),
			numChecked = $checked.length,
			value;
		
		if( numChecked === 0 ){
			value = o.noneSelectedText;
		} else {
			if($.isFunction(o.selectedText)){
				value = o.selectedText.call(this, numChecked, $inputs.length, $checked.get());
			} else if( /\d/.test(o.selectedList) && o.selectedList > 0 && numChecked <= o.selectedList){
				value = $checked.map(function(){ return this.title; }).get().join(o.separatorText);
			} else {
				value = o.selectedText.replace('#', numChecked).replace('#', $inputs.length);
			}
		}
		
		this.buttonlabel.html( value );
		return value;
	},
	
	// binds events
	_bindEvents: function(){
		var self = this, button = this.button;
		
		function clickHandler(){
			self[ self._isOpen ? 'close' : 'open' ]();
			return false;
		}
		
		// webkit doesn't like it when you click on the span :(
		button.find('span').bind('click.multiselect', clickHandler);
		
		// button events
		button.bind({
			click: clickHandler,
			keypress: function(e){
				switch(e.which){
					case 27: // esc
					case 38: // up
					case 37: // left
						self.close();
						break;
					case 39: // right
					case 40: // down
						self.open();
						break;
				}
			},
			mouseenter: function(){
				if( !button.hasClass('ui-state-disabled') ){
					$(this).addClass('ui-state-hover');
				}
			},
			mouseleave: function(){
				$(this).removeClass('ui-state-hover');
			},
			focus: function(){
				if( !button.hasClass('ui-state-disabled') ){
					$(this).addClass('ui-state-focus');
				}
			},
			blur: function(){
				$(this).removeClass('ui-state-focus');
			}
		});

		// header links
		this.header
			.delegate('a', 'click.multiselect', function(e){
				// close link
				if( $(this).hasClass('ui-multiselect-close') ){
					self.close();
			
				// check all / uncheck all
				} else {
					self[ $(this).hasClass('ui-multiselect-all') ? 'checkAll' : 'uncheckAll' ]();
				}
			
				e.preventDefault();
			});
		
		// optgroup label toggle support
		this.menu
			.delegate('li.ui-multiselect-optgroup-label a', 'click.multiselect', function(e){
				var $this = $(this),
					$inputs = $this.parent().nextUntil('li.ui-multiselect-optgroup-label').find('input:visible:not(:disabled)');
				
				// toggle inputs
				self._toggleChecked( $inputs.filter(':checked').length !== $inputs.length, $inputs );
				
				// trigger event
				self._trigger('optgrouptoggle', e, {
					inputs: $inputs.get(),
					label: $this.parent().text(),
					checked: $inputs[0].checked
				});
				
				e.preventDefault();
			})
//MEDIADIALOG	
			.delegate('span.ui-multiselect-delbutton', 'click.multiselect', function(e){
				self._deleteItem($(this).parent().parent());
				e.preventDefault();
			})
//END-MEDIADIALOG	
			.delegate('label', 'mouseenter', function(){
				if( !$(this).hasClass('ui-state-disabled') ){
					self.labels.removeClass('ui-state-hover');
					$(this).addClass('ui-state-hover').find('input').focus();
				}
			})
			.delegate('label', 'keydown', function(e){
				switch(e.which){
					case 9: // tab
					case 27: // esc
						self.close();
						break;
					case 38: // up
					case 40: // down
					case 37: // left
					case 39: // right
						self._traverse(e.which, this);
						e.preventDefault();
						break;
					case 13: // enter
						e.preventDefault();
						$(this).find('input').trigger('click');
						break;
				}
			})
			.delegate(':checkbox, :radio', 'click', function(e){
				var $this = $(this),
					val = this.value,
					checked = this.checked,
					tags = self.element.find('option');
				
				// bail if this input is disabled or the event is cancelled
				if( $this.is(':disabled') || self._trigger('click', e, { value:val, text:this.title, checked:checked }) === false ){
					e.preventDefault();
					return;
				}
				
				// make sure the original option tags are unselected first 
				// in a single select
				if( !self.options.multiple ){
					tags.not(function(){
						return this.value === val;
					}).removeAttr('selected');
				}
				
				// toggle aria state
				$this.attr('aria-selected', checked);
				
				// set the original option tag to selected
				tags.filter(function(){
					return this.value === val;
				}).prop('selected', (checked ? 'selected' : ''));
				
				// setTimeout is to fix multiselect issue #14 and #47. caused by jQuery issue #3827
				// http://bugs.jquery.com/ticket/3827 
				setTimeout($.proxy(self.update, self), 10);
			});
//MEDIADIALOG		
		// footer links
		if(this.options.editable){
			this.footer
				.delegate('span.ui-multiselect-newbutton', 'click', function(e){
					var itemName = $.trim($('#newItemName').val());
					$('#newItemName').val('');
					if(itemName != ''){
						self._newItem(itemName);
					}
				});
		}
//END_MEDIADIALOG
		
		// close each widget when clicking on any other element/anywhere else on the page
		$(document).bind('click.multiselect', function(e){
			var $target = $(e.target);
			
			if(self._isOpen && !$.contains(self.menu[0], e.target) && !$target.is('button.ui-multiselect') 
//MEDIADIALOG
					&& !$target.is('span.ui-multiselect-delbutton')				
//END_MEDIADIALOG				
					){
				self.close();
			}
		});
		
		// deal with form resets.  the problem here is that buttons aren't
		// restored to their defaultValue prop on form reset, and the reset
		// handler fires before the form is actually reset.  delaying it a bit
		// gives the form inputs time to clear.
		$(this.element[0].form).bind('reset', function(){
			setTimeout(function(){ self.update(); }, 10);
		});
	},

	// set button width
	_setButtonWidth: function(){
		var width = this.element.outerWidth(),
			o = this.options;
			
		if( /\d/.test(o.minWidth) && width < o.minWidth){
			width = o.minWidth;
		}
		
		// set widths
		this.button.width( width );
	},
	
	// set menu width
	_setMenuWidth: function(){
		var m = this.menu,
			width = this.button.outerWidth()-
				parseInt(m.css('padding-left'),10)-
				parseInt(m.css('padding-right'),10)-
				parseInt(m.css('border-right-width'),10)-
				parseInt(m.css('border-left-width'),10);
				
		m.width( width || this.button.outerWidth() );
	},
	
	// move up or down within the menu
	_traverse: function(which, start){
		var $start = $(start),
			moveToLast = which === 38 || which === 37,
			
			// select the first li that isn't an optgroup label / disabled
			$next = $start.parent()[moveToLast ? 'prevAll' : 'nextAll']('li:not(.ui-multiselect-disabled, .ui-multiselect-optgroup-label)')[ moveToLast ? 'last' : 'first']();
		
		// if at the first/last element
		if( !$next.length ){
			var $container = this.menu.find('ul:last');
			
			// move to the first/last
			this.menu.find('label')[ moveToLast ? 'last' : 'first' ]().trigger('mouseover');
			
			// set scroll position
			$container.scrollTop( moveToLast ? $container.height() : 0 );
			
		} else {
			$next.find('label').trigger('mouseover');
		}
	},

	_toggleChecked: function(flag, group){
		var $inputs = (group && group.length) ?
			group :
			this.labels.find('input');
		
		// toggle state on inputs
		$inputs
			.not(':disabled')
			.attr({ 'checked':flag, 'aria-selected':flag }); 
		
		this.update();
		
		var values = $inputs.map(function(){
			return this.value;
		}).get();
		
		// toggle state on original option tags
		this.element.find('option').filter(function(){
			return !this.disabled && $.inArray(this.value, values) > -1;
		}).attr({ 'selected':flag, 'aria-selected':flag });
	},

	_toggleDisabled: function( flag ){
		this.button
			.attr({ 'disabled':flag, 'aria-disabled':flag })[ flag ? 'addClass' : 'removeClass' ]('ui-state-disabled');
		
		this.menu
			.find('input')
			.attr({ 'disabled':flag, 'aria-disabled':flag })
			.parent()[ flag ? 'addClass' : 'removeClass' ]('ui-state-disabled');
		
		this.element
			.attr({ 'disabled':flag, 'aria-disabled':flag });
	},
	
	// open the menu
	open: function(e){
		var self = this,
			button = this.button,
			menu = this.menu,
			speed = this.speed,
			o = this.options;
	
		// bail if the multiselectopen event returns false, this widget is disabled, or is already open 
		if( this._trigger('beforeopen') === false || button.hasClass('ui-state-disabled') || this._isOpen ){
			return;
		}
		
		// close other instances
		$(':ech-multiselect').not(this.element).each(function(){
			var $this = $(this);
			
			if( $this.multiselect('isOpen') ){
				$this.multiselect('close');
			}
		});
		
		var $container = menu.find('ul:last'),
			effect = o.show,
			pos = button.position();
		
		// figure out opening effects/speeds
		if( $.isArray(o.show) ){
			effect = o.show[0];
			speed = o.show[1] || self.speed;
		}
		
		// set the scroll of the checkbox container
		$container.scrollTop(0).height(o.height);
		
		// position and show menu
		if( $.ui.position && !$.isEmptyObject(o.position) ){
			o.position.of = o.position.of || button;
			
			menu
				.show()
				.position( o.position )
				.hide()
				.show( effect, speed );
		
		// if position utility is not available...
		} else {
			menu.css({ 
				top: pos.top+button.outerHeight(),
				left: pos.left
			}).show( effect, speed );
		}
		
		// select the first option
		// triggering both mouseover and mouseover because 1.4.2+ has a bug where triggering mouseover
		// will actually trigger mouseenter.  the mouseenter trigger is there for when it's eventually fixed
		this.labels.eq(0).trigger('mouseover').trigger('mouseenter').find('input').trigger('focus');
		
		button.addClass('ui-state-active');
		this._isOpen = true;
		this._trigger('open');
	},
	
	// close the menu
	close: function(){
		if(this._trigger('beforeclose') === false){
			return;
		}
	
		var o = this.options, effect = o.hide, speed = this.speed;
		
		// figure out opening effects/speeds
		if( $.isArray(o.hide) ){
			effect = o.hide[0];
			speed = o.hide[1] || this.speed;
		}
	
		this.menu.hide(effect, speed);
		this.button.removeClass('ui-state-active').trigger('blur').trigger('mouseleave');
		this._trigger('close');
		this._isOpen = false;
	},

	enable: function(){
		this._toggleDisabled(false);
	},
	
	disable: function(){
		this._toggleDisabled(true);
	},
	
	checkAll: function(e){
		this._toggleChecked(true);
		this._trigger('checkAll');
	},
	
	uncheckAll: function(){
		this._toggleChecked(false);
		this._trigger('uncheckAll');
	},
	
	getChecked: function(){
		return this.menu.find('input').filter(':checked');
	},
	
	destroy: function(){
		// remove classes + data
		$.Widget.prototype.destroy.call( this );
		
		this.button.remove();
		this.menu.remove();
		this.element.show();
		
		return this;
	},
	
	isOpen: function(){
		return this._isOpen;
	},
	
	widget: function(){
		return this.menu;
	},

//MEDIADIALOG
	getOrder: function(){
		if(this.options.editable){
			return this.checkboxContainer.sortable( "serialize" );
		}else{
			return '';
		}
	},
	
	getNew: function(){
		var newString = '';
		if(this.options.editable){
			var length = this.newItems.length;
			var elementID = this.element.attr('id'); 
			
			$.each(this.newItems, function(index, value) {
				//Aufspaltung des Array-Elementes: eintext#new0
				//und Ausgabe als String für assoziatives Array
				//auf Serverseite: idDesSelectfeldes[new0]=eintext
				var gluePos = value.lastIndexOf('#'),
					id = value.substr(gluePos+1),
					title = value.substring(0,gluePos);
				
				newString += elementID+'New['+id+']='+title;
				
				if(! (index == length-1) ){
					newString += '&';
				}
			});
		}
		return newString;
	},
	
	getDeleted: function(){
		var deletedString = '';
		var length = this.deletedItems.length;
		
		if(this.options.editable && length > 0){
			if(confirm('Zum Entfernen gewählte Kategorien jetzt für alle Benutzer löschen?\n\n')){
				var elementID = this.element.attr('id');
				$.each(this.deletedItems, function(index, value) { 
					deletedString += elementID+'Deleted[]='+value;
					if(! (index == length-1)){
						deletedString += '&';
					}
				});
			}
		}
		return deletedString;
	},
	
	_newItem: function(name){
		
		//Feststellen ob Kategorie bereits vorhanden
		var isUnique = true;
		this.element.find('option').each(function(e){
			if (name == $(this).text())
				isUnique = false;
		});
		if(isUnique){
			var newItemLocalID = this.newItems.length;
			this.newItems.push(name+'#'+'new'+newItemLocalID);

			//neues Element im original Select-Feld hinzufuegen
			$('<option value="new'+newItemLocalID+'">'+name+'</option>')
				.appendTo(this.element);
			//Liste neu aufbauen
			this.refresh();
		}
	},
	
	_deleteItem: function($li){
		var idstr = $li.attr('id');
		var id = idstr.substr(idstr.lastIndexOf('_')+1);
		var optionEle = this.element.find('option').filter(function(){ return $(this).val() == id; })

		if(idstr.indexOf('new') == -1){//kein gerade neu erstelltes Element -> in Löschliste aufnehmen
			this.deletedItems.push(id);
		}else{ //es wurde ein gerade neu Erstelltes Element wieder gelöscht
			var pos = $.inArray(optionEle.text()+'#'+id, this.newItems);
			//entsprechenden Eintrag aus Array entfernen
			this.newItems = this.newItems.slice(0,pos).concat( this.newItems.slice(pos+1) );
		}
		//löschen im original Select-Feld
		optionEle.remove();
		//neu laden der Liste
		this.refresh();
	},
//END_MEDIADIALOG
	
	// react to option changes after initialization
	_setOption: function( key, value ){
		var menu = this.menu;
		
		switch(key){
			case 'header':
				menu.find('div.ui-multiselect-header')[ value ? 'show' : 'hide' ]();
				break;
			case 'checkAllText':
				menu.find('a.ui-multiselect-all span').eq(-1).text(value);
				break;
			case 'uncheckAllText':
				menu.find('a.ui-multiselect-none span').eq(-1).text(value);
				break;
			case 'height':
				menu.find('ul:last').height( parseInt(value,10) );
				break;
			case 'minWidth':
				this.options[ key ] = parseInt(value,10);
				this._setButtonWidth();
				this._setMenuWidth();
				break;
//MEDIADIALOG
			case 'separatorText':
//END_MEDIADIALOG
			case 'selectedText':
			case 'selectedList':
			case 'noneSelectedText':
				this.options[key] = value; // these all needs to update immediately for the update() call
				this.update();
				break;
			case 'classes':
				menu.add(this.button).removeClass(this.options.classes).addClass(value);
				break;
		}
		
		$.Widget.prototype._setOption.apply( this, arguments );
	}
});

})(jQuery);
