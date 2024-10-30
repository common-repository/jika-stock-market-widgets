const date = new Date();

const currentYear = date.getFullYear();

const yearsRange = [];

for (let i = 2011; i <= currentYear; i += 1) {
	yearsRange.push(i.toString());
}

const generateOptions = (options) =>
	options.map((year) => {
		const option = document.createElement("option");
		option.value = year;
		option.textContent = year;
		return option;
	});

const generateFromTo = (fromYear, toYear) => {
	const fromOptions = generateOptions(yearsRange);
	const toOptions = generateOptions(
		yearsRange.filter((item) => item >= fromYear),
	);
	return [fromOptions, toOptions];
};

window.addEventListener("elementor/init", () => {
	const inputView = elementor.modules.controls.BaseMultiple.extend({
		onReady() {
			const self = this;
			const fromSelector = `.${my_elementor_obj.control_uid.replace(
				"default-{{{ data._cid }}}",
				`from-${this.model.cid}`,
			)}`;
			const toSelector = `.${my_elementor_obj.control_uid.replace(
				"default-{{{ data._cid }}}",
				`to-${this.model.cid}`,
			)}`;
			this.initialValue =
				this.options.elementSettingsModel.attributes[
					this.model.attributes.name
				];
			if (this.initialValue.from > this.initialValue.to) {
				this.initialValue.to = this.initialValue.from;
			}
			this.newValue = this.initialValue;
			waitForElement([fromSelector, toSelector]).then(() => {
				let [fromOptions, toOptions] = generateFromTo(
					self.initialValue.from,
					self.initialValue.to,
				);
				const fromEl = jQuery(fromSelector);
				const toEl = jQuery(toSelector);
				fromEl.append(...fromOptions);
				fromEl.val(self.initialValue.from);
				toEl.append(...toOptions);
				toEl.val(self.initialValue.to);
				const handleChange = (event) => {
					const { target } = event;
					if (target.className.includes("from")) {
						self.newValue.from = target.value;
						if (target.value > self.newValue.to) {
							self.newValue.to = self.newValue.from;
						}
					} else {
						self.newValue.to = target.value;
					}
					[fromOptions, toOptions] = generateFromTo(
						self.newValue.from,
						self.newValue.to,
					);
					jQuery(`${fromSelector} option`).remove();
					jQuery(`${toSelector} option`).remove();
					fromEl.append(...fromOptions);
					fromEl.val(self.newValue.from);
					toEl.append(...toOptions);
					toEl.val(self.newValue.to);
					self.saveValue();
				};
				fromEl.on("change", handleChange);
				toEl.on("change", handleChange);
			});
		},
		saveValue() {
			this.setValue(this.newValue);
		},
	});
	elementor.addControlView("years-range", inputView);
});
