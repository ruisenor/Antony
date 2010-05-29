/*
 * Unobtrusivelib 1.1
 *
 * Copyright (c) 2008 Pierre Bertet (pierrebertet.net)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 */

(function($){
	$.unobtrusivelib = function (enabled_modules) {
		
		var modules = {
			popup: function () {
				$("a[rel^=popup]").each(function (i) {
					var popupName = 'popup_' + i + '_' + new Date().getTime();
					
					$(this).click(function (e) {
						e.preventDefault();
						var dims = this.getAttribute('rel').match(/.*\[([0-9]+)-([0-9]+)\].*/);
						window.open(this.getAttribute('href'), popupName, 'width=' + dims[1] + ',height=' + dims[2] + ',resizable,scrollbars');
					});
				});
			},
			
			external: function () {
				$("a[rel~=external]").click(function(e){
					e.preventDefault();
					window.open(this.href);
				});
			},
			
			autoClearInput: function () {
				
				var defaultClass = "autoclear-default";
				
				$("input.autoclear:text, input.autoclear:password, textarea.autoclear").each(function(){
					
					var $this = $(this);
					
					if ( $this.val() == this.defaultValue ) {
						$this.addClass(defaultClass);
					}
					
					$this
					.focus(function () {
						if ( this.defaultValue == $this.val() )
							$this.removeClass(defaultClass).val("");
					})
					.blur(function () {
						if ( $this.val() == "" )
							$this.addClass(defaultClass).val( this.defaultValue );
					});
					
				});
			},
			
			autoFocusInput: function () {
				var focusElmts = $("input.autofocus");
				if (focusElmts.length != 0){
					focusElmts.get(0).focus();
				}
			}
		};
		
		if (!!enabled_modules) {
			$.each(enabled_modules,function(i,n){
				if(modules[n]){
					modules[n]();
				}
			});
		}
		else {
			$.each(modules,function(i,n){n();});
		}
	};
})(jQuery);