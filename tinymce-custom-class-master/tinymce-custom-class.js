function makeTabs()
{
		jQuery(".tabs-menu a").click(function(event) {
        event.preventDefault();
        jQuery(this).parent().addClass("current");
        jQuery(this).parent().siblings().removeClass("current");
        var tab = jQuery(this).attr("href");
        jQuery(".tab-content").not(tab).css("display", "none");
        jQuery(tab).fadeIn();
    });
}
function nafsInsertCustom()
{
	var customShortCode = '';
		/*var lat 	= jQuery("#nafs-lat").val();
		var lng 	= jQuery("#nafs-lng").val();
		var zoom 	= jQuery("#nafs-zoom").val();
		var width 	= jQuery("#nafs-width").val();
		var height 	= jQuery("#nafs-height").val();
		var center 	= jQuery("#nafs-center").val();*/
		customShortCode +='[nafs_gmap';
		customShortCode += nafsPairIt('lat', "#nafs-lat");
		customShortCode += nafsPairIt('lng', "#nafs-lng");
		customShortCode += nafsPairIt('zoom', "#nafs-zoom");
		customShortCode += nafsPairIt('width', "#nafs-width");
		customShortCode += nafsPairIt('height', "#nafs-height");
		customShortCode += nafsPairIt('center', "#nafs-center");
		customShortCode +='][/nafs_gmap]';
		return customShortCode;
}
function nafsPairIt(k, id)
{
	var v =jQuery(id).val();
	if(v && v!=='undefined')
	{
		return (' '+k+'='+v);
	}
	else
	{
	return '';
	}
}
(function() {
    tinymce.PluginManager.add( 'custom_class', function( editor, url ) {
        // Add Button to Visual Editor Toolbar
        editor.addButton('custom_class', {
            title: 'Insert Google Map',
            cmd: 'insert_nafs_map',
            icon: 'icon dashicons-location',
        });
 
        // Add Command when Button Clicked
        editor.addCommand('insert_nafs_map', function() {
		makeTabs();
			//document.getElementById("theButton").onclick();
			var i=0;
			jQuery('#nafs_gmap_popuptrigger').trigger('click');
			jQuery('.nafs_gmap_insertlink').click(function(){
				var theClicked	= jQuery(this).attr('data-attr-id');
				var keyType		= jQuery(this).attr('data-attr-item');
				var shortCode	= '[nafs_gmap wrapper=\"nafs_gmap_wrap\" '+keyType+'=\"'+theClicked+'\"][/nafs_gmap]';
				if(++i==1)
				{
				editor.execCommand('mceInsertContent', false, shortCode);
				}
				tb_remove();
				});
				
		jQuery("#nafs-insert-custom").click(function(){
			if(++i==1)
				{
					var nafscustomShortCode = nafsInsertCustom();
					editor.execCommand('mceInsertContent', false, nafscustomShortCode);
				}
			});
				
        });

				
        // Enable/disable the button on the node change event
        editor.onNodeChange.add(function( editor ) {
            // Get selected text, and assume we'll disable our button
            var selection = editor.selection.getContent();
            var disable = true;

            // If we have some text selected, don't disable the button
            if ( selection ) {
                disable = false;
            }

            // Define whether our button should be enabled or disabled
           // editor.controlManager.setDisabled( 'custom_class', disable );
        });
    });
})();