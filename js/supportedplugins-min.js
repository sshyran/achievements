(function(a){a(document).ready(function(){a("#dpa-toolbar-slider").on("change.achievements",function(b){var c=this.value*10;a(".grid .plugin").each(function(b,d){var e=2.5*c,f=7.72*c;a(d).css("height",e+"px").css("width",f+"px")})});a("#dpa-toolbar-wrapper a").on("click.achievements",function(b){b.preventDefault();var c=a(this),d=c.prop("class");if(c.hasClass("current"))return;"grid"===d?a(".dpa-toolbar-slider").addClass("current"):a(".dpa-toolbar-slider").removeClass("current");c.parent().parent().find("a").removeClass("current");c.addClass("current");a("#post-body-content > div").removeClass("current");a("#post-body-content > div."+d).addClass("current")})})})(jQuery);