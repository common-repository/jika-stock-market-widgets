function waitForElement(selector, remove = false) {
	const handleCheck = () => {
		if (Array.isArray(selector)) {
			return !selector.some((v) =>
				remove ? document.querySelector(v) : !document.querySelector(v),
			);
		}
		const result = document.querySelector(selector);
		if (remove) {
			return !result;
		}
		return result;
	};
	return new Promise((resolve) => {
		let element = handleCheck();
		if (element) {
			return resolve(element);
		}

		const observer = new MutationObserver((mutations) => {
			element = handleCheck();
			if (element) {
				observer.disconnect();
				resolve(element);
			}
		});

		observer.observe(document.body, {
			childList: true,
			subtree: true,
		});
	});
}
