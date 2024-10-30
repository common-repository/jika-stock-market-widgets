import store from "./store";

const loadCompanyList = async () =>
	fetch("https://www.jika.io/api/company_list/")
		.then((res) => res.json())
		.then((res) => {
			const resultList = [];
			try {
				res.result.company_list[0].options.forEach((option) => {
					resultList.push({
						key: option.symbol,
						name: `${option.symbol} - ${option.company_name}`,
					});
				});
			} catch (error) {
				console.error(error);
			}
			store.set("companyList", resultList);
		})
		.catch((error) => {
			// console.log(error);
		});

// fetch financial keys
const loadFinancialKeys = async () =>
	fetch("https://www.jika.io/api/financial_keys")
		.then((res) => res.json())
		.then(({ result: { financial_keys: res } }) => {
			if (res.length < 1) {
				throw new Error();
			} else {
				const tempMetricOptions = [];
				res.forEach((item) => {
					const keys = item.keys.map((key) => {
						const tempKey = {
							key: key.title,
							name: key.title,
						};
						return tempKey;
					});
					tempMetricOptions.push({
						label: item.title,
						options: keys,
					});
				});
				store.set("financialKeys", tempMetricOptions);
			}
		})
		.catch((error) => {
			// console.log(error);
		});

const loadTrendingLeaderboards = async () =>
	fetch(
		"https://www.jika.io/api/leaderboard/jika?type=trending&limit=5&shuffle=true",
	)
		.then((res) => res.json())
		.then((res) => {
			const leaderboardMembers = res.leaderboard_members.map(
				(leaderboardMember) => ({
					key: leaderboardMember.userName,
					name: leaderboardMember.userName,
				}),
			);
			store.set("leaderboardMembers", leaderboardMembers);
		})
		.catch((error) => {
			// console.log(error);
		});

const kickstart = async () => {
	if (!store.get("kickstart")) {
		store.set("kickstart", true);
		Promise.all([
			loadCompanyList(),
			loadFinancialKeys(),
			loadTrendingLeaderboards(),
		]);
	}
};

export default kickstart;
