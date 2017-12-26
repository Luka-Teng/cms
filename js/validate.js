(function(factory){
  if(typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module depending on jQuery.
    define(['jquery'], factory);
  }
  else {
    // No AMD. Register plugin with global jQuery object.
    factory(jQuery);
  }
})(function($){
	$.validate=function(el,opts){
		var $this=$(el);
		var es_top=$this.height()+10;
		var defaults={
			test:[{case:"null"}]
		}
		var settings={};
		var namespace="validate"
		var __methods={};
		
		var alert_template=$("<div class='alert-panel'></div>");
		
		$.data(el,"validate",$this);
		
		//private methods
		__methods={		
			init:function(opts){
				settings=$.extend(true,{},defaults,opts);
				settings.test[settings.test.length-1].case!=="null"?settings.test.push({case:"null"}):"";
				__methods.render(settings);
			},
			
			render:function(_settings){
				var style=_settings.css;
				$this.parent().css("position","relative");
				__methods.bind($this)
			},
			
			bind:function(el){
				el.on("keyup",__methods.fuct);
				el.on("change",__methods.fuct);
			},
			
			unbind:function(el){
				el.off("keyup",__methods.fuct);
				el.off("change",__methods.fuct);
				el.parent().find(".alert-panel").remove();
			},
			
			fuct:function(){
				__methods.validate(settings.test);
			},
			
			validate:function(options){
				var alert_new=alert_template.clone();
				var alert_panel=$this.parent().children(".alert-panel");
				var value=$this.val();

				function alertChange(message){
					if(alert_panel.text()!=message){
						alert_panel.remove();
						alert_new.text(message);
						__methods.show(alert_new);
					}
				}

				for(var i in options){
					if(options[i].case==="nonzero"){
						
						if(value.length===0){
							alertChange(options[i].message);
							break;
						}
					}

					if(options[i].case==="reg"){
						if(!options[i].reg.test(value)&&value.length>0){
							alertChange(options[i].message);
							break;
						}
					}

					if(options[i].case==="null"){
						if(alert_panel.length>0){
							alert_panel.remove();
							break;
						}
					}    
				}
			},
			
			show:function(_panel){
				$this.parent().append(_panel);
			}			
		}
		
		//public methods
		validate=$this.methods={
			refresh:function(opts){				
				__methods.unbind($this);
				settings=$.extend(true,{},defaults)
			},
			add:function(opts){
				__methods.unbind($this);
				var new_default=settings.test;
				new_default.splice(new_default.length-1,1)
				Array.prototype.push.apply(new_default,opts);
				new_default.push({case:"null"});
				var new_opts={test:new_default}
				__methods.init(new_opts);
			},
			test: function(){
				__methods.validate(settings.test);
			}
		}
		
		__methods.init(opts);
		
	}
	$.fn.validate=function(options){
		if(options===undefined){options={}}
		if(typeof options==='object'){
			return this.each(function(index,el){
				if($(this).data("validate")!==undefined){
					$(this).data("validate").methods.refresh();
				}else{
					new $.validate(el,options)
				}
			})
		}else{
			var __arguments=arguments;
			return this.each(function(index,el){
				if($(el).data("validate")!==undefined){
					if($(el).data("validate").methods[options]){												
						$(el).data("validate").methods[options].apply($(this),Array.prototype.slice.call(__arguments,1));
					}else{
						$.error("Method "+options+" does not exist on jQuery.validate.js");
					}
				}else{
					console.log("not yet init:");
					console.log(el);
				}
			})
		}
	}
})