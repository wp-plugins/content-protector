/**
 * editor_plugin_src.js
 *
 */

(function() {
	tinymce.create('tinymce.plugins.rot13ShortcodePlugin', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished its initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceRot13Content');
			ed.addCommand('mceRot13Content', function() {
				tinyMCE.activeEditor.execCommand('mceReplaceContent', false, '[' + rot13AdminTinyMCEOptions.tag + ']{$selection}[/' + rot13AdminTinyMCEOptions.tag + ']');
			});

			// Register button
			ed.addButton('rot13_encoder_decoder', {
				title : 'Display content as ROT13',
				cmd : 'mceRot13Content',
				image : url + '/img/rot13button.jpg'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('rot13_encoder', n.nodeName == 'IMG');
			});
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'ROT13 shortcode plugin',
				author : 'K. Tough',
				authorurl : 'http://wordpress.org/extend/plugins/rot13-encoderdecoder',
				infourl : 'http://wordpress.org/extend/plugins/rot13-encoderdecoder',
				version : rot13AdminTinyMCEOptions.version
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('rot13_encoder_decoder', tinymce.plugins.rot13ShortcodePlugin);
})();