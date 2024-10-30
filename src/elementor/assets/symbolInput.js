handleStoreCheck("companyList").then((companyList) => {
	registerAsyncSelect(
		{
			loadOptions: (inputValue) =>
				handleFetch(`/company_list/${inputValue}/not-etf`).then((res) =>
					res.result.company_list.map((item) => ({
						key: item.symbol,
						name: `${item.symbol} - ${item.company_name}`,
					})),
				),
			onChange: (self, value) => {
				self.newValue = value.key;
				self.saveValue();
			},
			defaultOptions: companyList,
		},
		"symbol-input",
	);
});
