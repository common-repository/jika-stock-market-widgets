window.addEventListener("elementor/init", () => {
	const inputView = elementor.modules.controls.BaseData.extend({
		onReady() {
			const self = this;
			this.newValue = "";
			const heightSelector = `.${my_elementor_obj.control_uid.replace(
				"default-{{{ data._cid }}}",
				`height-${this.model.cid}`,
			)}`;
			const autoSelector = `.${my_elementor_obj.control_uid.replace(
				"default-{{{ data._cid }}}",
				`auto-${this.model.cid}`,
			)}`;
			this.value =
				this.options.elementSettingsModel.attributes[
					this.model.attributes.name
				];
			console.log(this.value);
			waitForElement([heightSelector, autoSelector]).then(() => {
				const heightEl = jQuery(heightSelector);
				const autoEl = jQuery(autoSelector);
				heightEl.change((event) => {
					self.value.height = event.target.value;
					self.saveValue();
				});
				autoEl.change((event) => {
					self.value.auto = event.target.checked;
					if (event.target.checked) {
						heightEl.attr("disabled", true);
					} else {
						heightEl.attr("disabled", false);
					}
					self.saveValue();
				});
			});
		},
		saveValue() {
			this.setValue(this.value);
		},
	});
	elementor.addControlView("jika-widget-height", inputView);
});
