setInterval(
	() => {
		jQuery.post(
			jika_widgets_editor_obj.ajax_url,
			jika_widgets_editor_obj,
			(data) => {
				if (data) {
					jika_widgets_editor_obj.api_key = data;
				}
			},
		);
	},
	// run every 4.5 minutes to refresh api_key
	1000 * 60 * 4.5,
);
