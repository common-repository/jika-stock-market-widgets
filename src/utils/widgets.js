import { currentYear } from "./date";
import { isProduction } from "./env";

const minExtraConfig = () => [
	{
		name: "backgroundColor",
		type: "color",
		label: "Background Color",
		placeholder: "#FFFFFF",
		value: "#FFFFFF",
	},
];

const maxExtraConfig = () => [
	{
		name: "textColor",
		type: "color",
		label: "Text Color",
		placeholder: "#161c2d",
		value: "#161c2d",
	},
	{
		name: "backgroundColor",
		type: "color",
		label: "Background Color",
		placeholder: "#FFFFFF",
		value: "#FFFFFF",
	},
	{
		name: "fontFamily",
		label: "Font Family",
		type: "options",
		options: [
			{ value: "Nunito", label: "Nunito" },
			{ value: "Arial", label: "Arial" },
			{ value: "Helvetica", label: "Helvetica" },
			{ value: "Times New Roman", label: "Times New Roman" },
			{ value: "Times", label: "Times" },
			{ value: "Courier New", label: "Courier New" },
			{ value: "Courier", label: "Courier" },
			{ value: "Verdana", label: "Verdana" },
			{ value: "Roboto", label: "Roboto" },
			{ value: "Open Sans", label: "Open Sans" },
			{ value: "Lato", label: "Lato" },
			{ value: "Montserrat", label: "Montserrat" },
			{ value: "Poppins", label: "Poppins" },
		],
		value: "Nunito",
	},
];

const graphColorConfig = () => ({
	name: "graphColor",
	type: "color",
	label: "Graph Color",
	placeholder: "#1652f0",
	value: "#1652f0",
});

const configTemplate = {
	UserPortfolio: [
		{
			name: "userName",
			type: "user",
			label: "User Name",
			placeholder: "User Name",
			value: "Noah Sebastian",
		},
		...minExtraConfig(),
	],
	PriceTargetForecast: [
		{
			name: "symbol",
			type: "symbol",
			label: "Symbol",
			placeholder: "AAPL",
			value: "AAPL",
		},
		graphColorConfig(),
		...maxExtraConfig(),
	],
	FundamentalsTable: [
		{
			name: "symbols",
			type: "symbols",
			label: "Symbols",
			placeholder: "AAPL,AMZN,META",
			value: ["AAPL", "AMZN", "META"],
		},
		{
			name: "keys",
			type: "financialKeys",
			label: "Financial Keys",
			placeholder: "Market Cap,Net Income",
			value: ["Market Cap", "Net Income"],
		},
		{
			name: "reportingPeriod",
			label: "Reporting Period",
			type: "options",
			options: [
				{ value: "quarter", label: "Quarter" },
				{ value: "annual", label: "Annual" },
			],
			value: "quarter",
		},
		{
			name: "from",
			label: "From Year",
			type: "yearRange",
			value: `${currentYear - 5}`,
		},
		{
			name: "to",
			label: "To Year",
			type: "yearRange",
			value: `${currentYear}`,
		},
		{
			name: "sortMethod",
			label: "Default Sort Method",
			type: "options",
			options: [
				{ value: "years", label: "Years" },
				{ value: "companies", label: "Companies" },
			],
			value: "years",
		},
		...maxExtraConfig(),
	],
	FundamentalsChart: [
		{
			name: "symbols",
			type: "symbols",
			label: "Symbols",
			placeholder: "AAPL,AMZN,META",
			value: ["AAPL", "AMZN", "META"],
		},
		{
			name: "keys",
			type: "financialKeys",
			label: "Financial Keys",
			placeholder: "Market Cap,Net Income",
			value: ["Market Cap", "Net Income"],
		},
		{
			name: "reportingPeriod",
			label: "Reporting Period",
			type: "options",
			options: [
				{ value: "quarter", label: "Quarter" },
				{ value: "annual", label: "Annual" },
			],
			value: "quarter",
		},
		{
			name: "from",
			label: "From Year",
			type: "yearRange",
			value: `${currentYear - 5}`,
		},
		{
			name: "to",
			label: "To Year",
			type: "yearRange",
			value: `${currentYear}`,
		},
		...maxExtraConfig(),
	],
	AreaChart: [
		{
			name: "symbol",
			type: "symbol",
			label: "Symbol",
			placeholder: "AAPL",
			value: "AAPL",
		},
		{
			name: "selection",
			label: "Default Period",
			type: "options",
			options: [
				{ value: "one_month", label: "One Month" },
				{ value: "three_month", label: "Three Months" },
				{ value: "six_months", label: "Six Months" },
				{ value: "ytd", label: "YTD - Year to Date" },
				{ value: "one_year", label: "One Year" },
				{ value: "three_year", label: "Three Years" },
				{ value: "five_year", label: "Five Years" },
				{ value: "all", label: "All" },
			],
			value: "one_year",
		},
		{
			name: "closeKey",
			label: "Close Key",
			type: "options",
			options: [
				{ value: "close", label: "Close" },
				{ value: "adjClose", label: "Adjusted Close" },
			],
			value: "close",
		},
		{
			name: "logo",
			type: "text",
			label: "Symbol Logo",
			value: "",
			placeholder: "https://",
		},
		graphColorConfig(),
		...maxExtraConfig(),
	],
};

const valuesFromConfig = (currentConfig) => {
	const values = {};
	currentConfig.forEach((configItem) => {
		values[configItem.name] = configItem.value;
	});
	return values;
};

const iframeHeights = {
	UserPortfolio: 375,
	PriceTargetForecast: 570,
	FundamentalsChart: 286.6,
	FundamentalsTable: 37,
	AreaChart: 450,
};

const calculateIframeHeight = (embedComponent, keys) => {
	let height;
	const calculatedHeight = iframeHeights[embedComponent];
	if (Array.isArray(keys)) {
		keys = keys.length;
	} else {
		keys = keys?.split(",")?.length || 1;
	}
	switch (embedComponent) {
		case "FundamentalsChart":
			height = 65 + calculatedHeight * keys;
			break;
		case "FundamentalsTable":
			height = 105 + calculatedHeight * keys;
			if (height > 510) {
				height = 640;
			}
			break;
		default:
			height = calculatedHeight;
			break;
	}
	return height;
};

const baseEmbedUrl = !isProduction
	? "https://www.jika.io/embed/sandbox"
	: "https://www.jika.io/embed";

const embedOptionsBaseUrls = {
	AreaChart: `${baseEmbedUrl}/area-chart`,
	FundamentalsChart: `${baseEmbedUrl}/fundamentals-chart`,
	FundamentalsTable: `${baseEmbedUrl}/fundamentals-table`,
	PriceTargetForecast: `${baseEmbedUrl}/forecast-price-target`,
	UserPortfolio: `${baseEmbedUrl}/user-portfolio`,
};

const widgetsInfoData = {
	AreaChart: {
		title: "Real-Time Stock Price Chart",
		text: "With our stock ticker widgets, you can easily display real-time stock prices and updates on your website or blog. Simply choose the stock you want to track, customize the widget to match your content, and copy and paste the iframe code onto your page.",
	},
	FundamentalsChart: {
		title: "Advanced Stock Comparison Graph",
		text: "Our stock comparison charts allow you to compare any metric for multiple stocks side by side. With our easy-to-use chart builder, you can select the stocks and metrics you want to compare. Whether you're comparing stocks from the same industry or across different sectors, our charts provide a comprehensive view of stock performance.",
	},
	PriceTargetForecast: {
		title: "Stock Prediction and Forecast Widget",
		text: "Our stock predictions and forecasts widget is using the most updated analysts rating data to provide predictions for stock prices. By embedding our widgets on your site, you can provide your audience with valuable insights into the stock market and help them make informed investment decisions.",
	},
	UserPortfolio: {
		title: "Stock Portfolio Performance Chart",
		text: "With our stock portfolio widgets, you can easily share and embed your investment portfolio on your website, substack, notion dashboard or blog. Our widgets update in real-time, so your audience can see the latest changes to your portfolio as they happen. You will first need an account in Jika.io with a connected portfolio in order to use this widget.",
	},
	FundamentalsTable: {
		title: "Company's Financial Metrics Table",
		text: "Our balance sheet widgets allow you to embed the financial data of a company onto your website or blog. This data includes key financial metrics such as assets, liabilities, and shareholder equity, providing your audience with valuable insights into a company's financial health.",
	},
};

export {
	configTemplate,
	valuesFromConfig,
	calculateIframeHeight,
	embedOptionsBaseUrls,
	widgetsInfoData,
};
