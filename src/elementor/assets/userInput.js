handleStoreCheck("leaderboardMembers").then((leaderboardMembers) => {
	registerAsyncSelect(
		{
			loadOptions: (inputValue) =>
				handleFetch(`/users_list/${inputValue}?is_verified=portfolio`).then(
					(res) =>
						res.result.users_list.map((user) => ({
							key: user.user_name,
							name: user.user_name,
						})),
				),
			onChange: (self, value) => {
				self.newValue = value.key;
				self.saveValue();
			},
			defaultOptions: leaderboardMembers,
		},
		"user-input",
	);
});
