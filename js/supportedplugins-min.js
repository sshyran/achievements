(function(a){function b(b){b=a.trim(b);a("#post-body-content > .current, #dpa-toolbar-views a.current").removeClass("current");a("#post-body-content > ."+b+", #dpa-toolbar-views li a."+b).addClass("current");"grid"===b?a(".dpa-toolbar-slider").addClass("current"):a(".dpa-toolbar-slider").removeClass("current");a.cookie("dpa_sp_view",b,{path:"/"})}function c(b){var c=b.prop("class");a("#post-body-content > .detail > ul li").removeClass("current");b.addClass("current");a("#dpa-detail-contents > div").removeClass("current");a("#dpa-detail-contents ."+c).addClass("current");a.cookie("dpa_sp_lastplugin",c,{path:"/"})}a(document).ready(function(){a("#post-body-content > .detail > ul li").on("click.achievements",function(b){b.preventDefault();c(a(this))});a("#post-body-content > .list .plugin img").on("click.achievements",function(d){d.preventDefault();b("detail");c(a("#post-body-content > .detail > ul li."+a(this).prop("class")))});a("#post-body-content > .grid a").on("click.achievements",function(d){d.preventDefault();b("detail");c(a("#post-body-content > .detail > ul li."+a(this).children("img").prop("class")))});a("#dpa-toolbar-slider").on("change.achievements",function(b){var c=7.72*this.value*10;a(".grid img").each(function(b,d){a(d).css("width",c+"px")});a.cookie("dpa_sp_zoom",this.value,{path:"/"})});a("#dpa-toolbar-wrapper a").on("click.achievements",function(c){c.preventDefault();var d=a(this),e=d.prop("class");if(d.hasClass("current"))return;b(e)});a("#dpa-toolbar-search").on("keyup.achievements",function(b){b.preventDefault();var c=a(this).val(),d="",e=a("#post-body-content > .current").prop("class");if(e.indexOf("grid")>=0){e="grid";d="#post-body-content > .grid img"}else if(e.indexOf("list")>=0){e="list";d="#post-body-content > .list table .name"}else if(e.indexOf("detail")>=0){e="detail";d="#post-body-content > .detail > ul li"}a(d).each(function(){var b=a(this);"grid"===e?b.prop("alt").search(new RegExp(c,"i"))<0?b.fadeOut():b.show():"list"===e?b.text().search(new RegExp(c,"i"))<0?b.parent().fadeOut():b.parent().show():"detail"===e&&(b.prop("class").search(new RegExp(c,"i"))<0?b.fadeOut():b.show())})})})})(jQuery);