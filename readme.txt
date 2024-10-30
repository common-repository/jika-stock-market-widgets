=== Jika.io Stock Market Widgets ===
Contributors: jikaio
Tags: stocks, ticker, quote, finance, quotes, stock, financial, index, indices, market, list, overview, commodity, commodities, currency, currencies, forex, foreign exchange, equity, equities, crypto
Requires at least: 6.4
Requires PHP: 7.0
Tested up to: 6.4
Stable tag: 1.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Stock Market Widgets for WordPress By Jika.io

== Description ==

### Jika.io Stock Market Widgets

The Jika.io Stock Market Widgets WordPress plugin enables seamless integration of stock widgets from Jika.io into your WordPress site using Gutenberg blocks, Elementor widgets, and shortcodes.

With the Jika.io Stock Market Widgets plugin, enhancing your WordPress website with dynamic stock information has never been easier. If you need any help or have questions, feel free to [reach out for support](https://www.jika.io/contact).

== Installation ==

To install the Jika.io Stock Market Widgets plugin, follow these steps:

1. Download the plugin files.
2. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress.
4. Create an account in your WordPress administration interface.

== Available Widgets ==

### 1. Real Time Stock Price Chart

With our stock ticker widget, you can easily display real-time stock prices and updates on your website or blog. Simply choose the stock you want to track and customize the widget to match your content.

**Options:**

**symbol** (default="AAPL"): The symbol of the stock.

**selection** (default="one_year"): Time period for the data.

- "one_month"
- "three_month"
- "six_month"
- "ytd"
- "one_year"
- "three_year"
- "five_year"
- "all"

**close_key** (default="close"): Key for close price data.

- "close"
- "adjClose"

**symbol_logo** (URL, optional): URL of the stock symbol logo.

**graph_color** (default=#1652F0): Color of the graph.

**background_color** (default=#FFFFFF): Background color of the widget.

**font-family** (default="Nunito"): Font family for the widget text.

### 2. Advanced Stock Comparison Graph

Our stock comparison charts allow you to compare any metric for multiple stocks side by side. With our easy-to-use chart builder, you can select the stocks and metrics you want to compare. Whether you're comparing stocks from the same industry or across different sectors, our charts provide a comprehensive view of stock performance.

**Options:**

**symbols** (default="AAPL,AMZN"): Comma-separated list of stock symbols.

**keys** (default="Market Cap,Net Income"): Comma-separated list of financial metrics to display.

**reporting_period** (default="quarter"): Reporting period for the financial metrics.

- "quarter"
- "annual".

**from** (default=current year): Start year for the financial data.

**to** (default=current year - 5): End year for the financial data.

**text_color** (default="#161C2D"): Color of the text.

**background_color** (default="#FFFFFF"): Background color of the widget.

**font-family** (default="Nunito"): Font family for the widget text.

### 3. Stock Prediction and Forecast Widget

Our stock predictions and forecasts widget is using the most updated analysts rating data to provide predictions for stock prices. By embedding our widgets on your site, you can provide your audience with valuable insights into the stock market and help them make informed investment decisions.

**Options:**

**symbol** (default="AAPL"): The symbol of the stock.

**graph_color** (default=#1652F0): Color of the graph.

**background_color** (default="#FFFFFF"): Background color of the widget.

### 4. Stock Portfolio Performance Chart

With our stock portfolio widgets, you can easily share and embed your investment portfolio on your website, substack, notion dashboard or blog. Our widgets update in real-time, so your audience can see the latest changes to your portfolio as they happen. You will first need an account in Jika.io with a connected portfolio in order to use this widget.

**Options:**

**user_name** (default="Noah Sebastian"): Your Jika.io username.

**background_color** (default="#FFFFFF"): Background color of the widget.

### 5. Company Financial Metrics Table

Our balance sheet widgets allow you to embed the financial data of a company onto your website or blog. This data includes key financial metrics such as assets, liabilities, and shareholder equity, providing your audience with valuable insights into a company's financial health.

**Options:**

**symbols** (default="AAPL,AMZN"): Comma-separated list of stock symbols.

**keys** (default="Market Cap,Net Income"): Comma-separated list of financial metrics to display.

**reporting_period** (default="quarter"): Reporting period for the financial metrics.

- "quarter"
- "annual".

**from** (default=current year): Start year for the financial data.

**to** (default=current year - 5): End year for the financial data.

**sort_method** (default="years"): Sorting method for the table.

- "years"
- "companies"

**text_color** (default="#161C2D"): Color of the text.

**background_color** (default="#FFFFFF"): Background color of the widget.

**font-family** (default="Nunito"): Font family for the widget text.

== Shortcode Examples ==

To embed these stock widgets using shortcodes, use the shortcode `jika_stock_widget` followed by the appropriate type and options.

#### Real Time Stock Price Chart

`
[jika_stock_widget type="real-time-stock-price-chart" symbol="AAPL" selection="one_year" close_key="close" symbol_logo="https://example.com/aapl_logo.png" graph_color="#ff0000" background_color="#ffffff" font_family="Arial"]
`

#### Advanced Stock Comparison Graph

`
[jika_stock_widget type="advanced-stock-comparison-graph" symbols="AAPL,AMZN" keys="Market Cap,Net Income" reporting_period="quarter" from="2020" to="2022" text_color="#000000" background_color="#ffffff" font_family="Arial"]
`

#### Stock Prediction and Forecast Widget

`
[jika_stock_widget type="stock-prediction-and-forecast" symbol="AAPL" graph_color="#ff0000" background_color="#ffffff"]
`

#### Stock Portfolio Performance Chart

`
[jika_stock_widget type="stock-portfolio-performance-chart" user_name="your_username" background_color="#ffffff"]
`

#### Company Financial Metrics Table

`
[jika_stock_widget type="company-financial-metrics-table" symbols="AAPL,AMZN" keys="Market Cap,Net Income" reporting_period="quarter" from="2020" to="2022" sort_method="years" text_color="#000000" background_color="#ffffff" font_family="Arial"]
`

== Screenshots ==

1. Real Time Stock Price Chart
2. Advanced Stock Comparison Graph
3. Stock Prediction and Forecast Widget
4. Stock Portfolio Performance Chart
5. Company Financial Metrics Table

== Contributors & Developers ==

Welcome, developers! This section provides a quick guide to get started with our project.

### Setting Up Environment Variables

To begin working on our project, follow these simple steps:

1. Create a new file named `.env` in the root directory of the project.

2. Add the following content to the `.env` file:

`
ENV=development
`

3. Save the `.env` file.

**That's it!**

You've successfully configured the development environment, and the plugin is now operating in sandbox mode, simplifying the development process.

**Other Notes**

- Sandbox users will remain cached for one hour after their most recent activity.

- Domains using 127.0.0.1 | localhost might face unexpected iframe usage issues due to conflicts.

== 3rd Party and external services ==

This plugin relies on external services to enhance its functionality and provide users with an optimal experience. We are committed to transparency regarding the integration of these services within our plugin. Below are the details of the external services utilized:

### 1. jika.io API:

- **Description:** Our plugin integrates with our API to manage user data efficiently and securely. This API facilitates various functionalities within the plugin, including login / signup / usage control.
- **Service URL:** [jika.io API](https://www.jika.io/)
- **Terms of Use:** Please refer to the jika.io API's [Terms of Use](https://www.jika.io/termsofuse).
- **Privacy Policy:** For information regarding data handling and privacy practices, please review the [Privacy Policy](https://www.jika.io/privacypolicy) of jika.io.

### 2. PayPal JavaScript SDK:

- **Description:** To enable seamless payment processing, our plugin utilizes the PayPal JavaScript SDK. This SDK allows us to securely collect payments from users through PayPal.
- **Service URL:** [PayPal JavaScript SDK](https://developer.paypal.com/docs/business/javascript-sdk/)
- **Terms of Use:** Please review PayPal's [Terms of Use](https://www.paypal.com/us/webapps/mpp/ua/useragreement-full).
- **Privacy Policy:** For information regarding data handling and privacy practices, please review PayPal's [Privacy Policy](https://www.paypal.com/us/webapps/mpp/ua/privacy-full).

We prioritize user trust and data protection. Therefore, we have provided comprehensive documentation to ensure transparency regarding the use of external services. Users can review the terms of use and privacy policies associated with these services to make informed decisions about their data.

For any further inquiries or concerns regarding external service dependencies, please contact our support team at [info@jika.io](mailto:info@jika.io).


== Reference ==

### Font Family

- `Nunito`
- `Arial`
- `Helvetica`
- `Times New Roman`
- `Times`
- `Courier New`
- `Courier`
- `Verdana`
- `Roboto`
- `Open Sans`
- `Lato`
- `Montserrat`
- `Poppins`

### Financial keys

#### Key Metrics

- `Revenue Per Share`
- `Net Income Per Share`
- `Operating Cash-Flow Per Share`
- `Free Cash-Flow Per Share`
- `Cash Per Share`
- `Book Value Per Share`
- `Tangible Book Value Per Share`
- `Shareholders Equity Per Share`
- `Interest Debt Per Share`
- `Earnings Yield`
- `Free Cash-Flow Yield`
- `Days Of Sales Outstanding`
- `Days Of Payables Outstanding`
- `Days Of Inventory On Hand`
- `Capex Per Share`
- `Market Cap`

#### Income Statement

- `Revenue`
- `Cost of Revenue`
- `Gross Profit`
- `Operating Expenses`
- `Research And Development Expenses`
- `SG&A Expenses`
- `Selling And Marketing Expenses`
- `Other Operating Income Or Expenses`
- `Interest Expense`
- `Depreciation And Amortization`
- `EBITDA`
- `Operating Income`
- `Total Non-Operating Income or Expense`
- `Pre-Tax Income`
- `Income Taxes`
- `Net Income`
- `EPS - Earnings Per Share`
- `EPS Diluted`
- `Weighted Average Shares vs. Shares Outstanding`
- `Weighted Average Shares vs. Shares Outstanding Diluted`

#### Balance Sheet

- `Cash And Cash Equivalents`
- `Short Term Investments`
- `Receivables`
- `Inventory`
- `Other Current Assets`
- `Total Current Assets`
- `Property, Plant And Equipment`
- `Goodwill And Intangible Assets`
- `Long-Term investments`
- `Tax Assets`
- `Other Non-Current Assets`
- `Total Non-Current Assets`
- `Other Assets`
- `Total Assets`
- `Account Payables`
- `Short Term Debt`
- `Tax Payables`
- `Deferred Revenue`
- `Other Current Liabilities`
- `Total Current Liabilities`
- `Long Term Debt`
- `Deferred Revenue Non Current`
- `Deferred Tax Liabilities Non Current`
- `Other Non-Current Liabilities`
- `Total Long Term Liabilities`
- `Other Liabilities`
- `Total Liabilities`
- `Common Stock Net`
- `Retained Earnings (Accumulated Deficit)`
- `Comprehensive Income`
- `Other Share Holders Equity`
- `Share Holder Equity`
- `Total Liabilities and Share Holders Equity`
- `Total Investments`
- `Total Debt`
- `Net Debt`

#### Ratios

- `Current Ratio`
- `Quick Ratio`
- `Cash Ratio`
- `Gross Profit Margin`
- `Operating Profit Margin`
- `Pre-Tax Profit Margin`
- `Net Profit Margin`
- `Effective Tax Rate`
- `Return On Assets`
- `Return On Equity`
- `Return On Capital Employed`
- `Debt Ratio`
- `Debt To Equity Ratio`
- `Long-Term Debt To Capitalization`
- `Total Debt To Capitalization`
- `Interest Coverage`
- `Cash-Flow To Debt Ratio`
- `Receivables Turnover`
- `Payables Turnover`
- `Inventory Turnover`
- `Fixed Asset Turnover`
- `Asset Turnover`
- `Payout Ratio`
- `Operating Cash-Flow To Sales Ratio`
- `Free Cash-Flow To Operating Cash-Flow Ratio`
- `Cash-Flow Coverage Ratios`
- `Short-Term Coverage Ratios`
- `Capital Expenditure Coverage Ratio`
- `Dividend Paid And Capex Coverage Ratio`
- `Dividend Payout Ratio`
- `Price To Book Ratio`
- `Price To Sales Ratio`
- `PE Ratio`
- `Price To Free Cash-Flows Ratio`
- `Price To Cash-Flow Ratio`
- `PEG Ratio`

#### Financial Growth

- `Revenue Growth`
- `Gross Profit Growth`
- `EBIT Growth`
- `Operating Income Growth`
- `Net Income Growth`
- `EPS Growth`
- `EPS Diluted Growth`
- `Weighted Average Shares Growth`
- `Weighted Average Shares Diluted Growth`
- `Dividends Per Share Growth`
- `Operating Cash-Flow Growth`
- `Free Cash-Flow Growth`
- `10-Year Revenue Growth Per Share`
- `5-Year Revenue Growth Per Share`
- `3-Year Revenue Growth Per Share`
- `10-Year Operating Cash-Flow Growth Per Share`
- `5-Year Operating Cash-Flow Growth Per Share`
- `3-Year Operating Cash-Flow Growth Per Share`
- `10-Year Net Income Growth Per Share`
- `5-Year Net Income Growth Per Share`
- `3-Year Net Income Growth Per Share`
- `10-Year Shareholders Equity Growth Per Share`
- `5-Year Shareholders Equity Growth Per Share`
- `3-Year Shareholders Equity Growth Per Share`
- `10-Year Dividend Growth Per Share`
- `5-Year Dividend Growth Per Share`
- `3-Year Dividend Growth Per Share`
- `Receivables Growth`
- `Inventory Growth`
- `Asset Growth`
- `Book Value Per Share Growth`
- `Debt Growth`
- `R&D Expense Growth`
- `SG&A Expenses Growth`

#### Cash Flow Statement

- `Net Income or Loss`
- `Depreciation And Amortization`
- `Deferred Income Tax`
- `Stock-Based Compensation`
- `Change In Working Capital`
- `Other Non-Cash Items`
- `Change In Accounts Receivable`
- `Change In Inventories`
- `Change In Accounts Payable`
- `Other Working Capital`
- `Cash Flow From Operating Activities`
- `Net Change In Property, Plant And Equipment`
- `Net Acquisitions or Divestitures`
- `Purchase Of Investments`
- `Sales or Maturities Of Investments`
- `Investing Activities - Other`
- `Cash Flow From Investing Activities`
- `Debt Repayment`
- `Net Common Equity Issued`
- `Net Common Equity Repurchased`
- `Total Common And Preferred Stock Dividends Paid`
- `Financial Activities - Other`
- `Cash Flow From Financial Activities`
- `Effect Of Forex Changes On Cash`
- `Net Cash Flow`
- `Cash At Beginning Of Period`
- `Cash At End Of Period`
- `Operating Cash Flow`
- `Capital Expenditure`
- `Free Cash Flow`

== Changelog ==

= 1.0.0 =
* Relaesed
