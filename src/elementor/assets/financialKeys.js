handleStoreCheck("financialKeys").then((financialKeys) => {
	registerAsyncSelect(
		{
			loadOptions: (inputValue) =>
				financialKeys
					.reduce((acc, financialKey) => {
						if (Array.isArray(financialKey.options)) {
							return [
								...acc,
								...financialKey.options.filter(
									(v) =>
										!acc.find((k) => k.key === v.key) &&
										v.key.toLowerCase().includes(inputValue.toLowerCase()),
								),
							];
						}
						if (acc.find((v) => v.key === financialKey.key)) {
							return acc;
						}
						if (
							financialKey.key.toLowerCase().includes(inputValue.toLowerCase())
						) {
							return [...acc, financialKey];
						}
						return acc;
					}, [])
					.sort((a, b) => {
						if (a.key.toLowerCase() === inputValue.toLowerCase()) {
							return -1;
						}
						if (b.key.toLowerCase() === inputValue.toLowerCase()) {
							return 1;
						}
						const regExp = new RegExp(`^${inputValue}`, "i");
						if (regExp.test(a.key) && !regExp.test(b.key)) {
							return -1;
						}
						if (regExp.test(b.key) && !regExp.test(a.key)) {
							return 1;
						}
						return a.key < b.key ? -1 : 1;
					}),
			onChange: (self, value) => {
				self.newValue = value;
				self.saveValue();
			},
			isMulti: true,
			defaultOptions: financialKeys,
		},
		"financial-keys",
		"BaseMultiple",
	);
});
