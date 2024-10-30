// Fetch utils

const baseUrl = isProduction
	? "https://www.jika.io/api"
	: "https://www.jika.io/api/sandbox";

const createBasicAuth = (userName, password) => {
	const encoded = btoa(`${userName}:${password}`);
	return `Basic ${encoded}`;
};

const createBearerAuth = () => `Bearer ${my_ajax_obj.token}`;

/**
 *
 * @param {'basic' | 'bearer'} authType
 * @param  {...any} args
 * @returns
 */

const getAuthHeader = (authType = "bearer", ...args) => {
	let func;
	if (authType === "bearer") {
		func = createBearerAuth;
	} else {
		func = createBasicAuth;
	}
	return { Authorization: func(...args) };
};

const fetchError = (message, status) => {
	const newFetchError = new Error(message);
	newFetchError.status = status;
	return newFetchError;
};

/**
 *
 * @param {string} path
 * @param {RequestInit | undefined} config
 */

const handleFetch = (path, config) =>
	fetch(`${baseUrl}${path}`, config).then(async (res) => {
		const data = await res.json().catch(() => ({}));
		if (!res.ok) {
			throw fetchError(data.message, res.status);
		}
		if (data.token) {
			my_ajax_obj.token = data.token;
		}
		return data;
	});

const login = (userName, password) =>
	handleFetch("/widget_user/tokens", {
		headers: getAuthHeader("basic", userName, password),
	});

const signup = (email, password, domain) => {
	const data = new URLSearchParams();
	data.append("email", email);
	data.append("password", password);
	data.append("domain", domain);
	data.append("platform", "wp");
	return handleFetch("/widget_user/signup", {
		method: "POST",
		body: data,
	});
};

const contactUs = (email, domain, companyProfile, requestType, note) =>
	handleFetch("/widget_user/contact_us", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify({
			email,
			domain,
			company_profile: companyProfile,
			request_type: requestType,
			note,
			platform: "wp",
		}),
	});

const handleUploadTrademark = async (trademark) => {
	const data = new FormData();
	data.append("trademark", trademark);
	return handleFetch("/widget_user/setup/logo", {
		method: "POST",
		headers: getAuthHeader("bearer"),
		body: data,
	});
};

const handleUpdateDomain = async (domain) => {
	const data = new FormData();
	data.append("domain", domain);
	return handleFetch("/widget_user/setup/domain", {
		method: "POST",
		headers: {
			...getAuthHeader("bearer"),
		},
		body: data,
	});
};

const handleSubscribe = (subscription, plan, period) => {
	const data = {
		subscription,
		plan,
		period,
	};
	return handleFetch("/payments/subscription", {
		method: "POST",
		headers: {
			...getAuthHeader("bearer"),
			"Content-Type": "application/json",
		},
		body: JSON.stringify(data),
	});
};

const handleSubscriptionCancel = () => {
	return handleFetch("/payments/subscription/cancel", {
		method: "POST",
		headers: getAuthHeader("bearer"),
	});
};

const getSubscriptionId = () => {
	return handleFetch("/payments/subscription/id", {
		headers: getAuthHeader("bearer"),
	});
};

// elements utils

/**
 *
 * @param {keyof HTMLElementTagNameMap} tag
 * @param {HTMLElement} props
 * @returns
 */

const createElementWithProps = (tag, props) => {
	const element = document.createElement(tag);
	Object.entries(props).forEach(([key, value]) => {
		element[key] = value;
	});
	return element;
};

/**
 *
 * @param {HTMLElement} props
 * @param {'primary'} variant
 * @returns
 */

const createButton = (props, variant = "primary") => {
	if (!props.type) {
		props.type = "button";
	}
	if (!props.className) {
		props.className = "";
	} else {
		props.className += " ";
	}
	props.className += `button button-${variant}`;
	const button = createElementWithProps("button", props);
	return button;
};

const createSuccessNotice = (form, message) => {
	const successNotice = document.createElement("div");
	successNotice.className = "notice is-dismissible  updated";
	const successNoticeMessage = document.createElement("p");
	successNoticeMessage.textContent = message;
	const successNoticeDismissButton = document.createElement("button");
	successNoticeDismissButton.type = "button";
	successNoticeDismissButton.className = "notice-dismiss";
	successNoticeDismissButton.onclick = () =>
		form.parentElement.removeChild(successNotice);
	successNotice.append(successNoticeMessage, successNoticeDismissButton);
	form.parentElement.prepend(successNotice);
};

// ajax utils

const ajaxPost = (action, data, callback) => {
	const { ajax_url, nonce } = my_ajax_obj;
	jQuery.post(
		ajax_url,
		{
			_ajax_nonce: nonce,
			action,
			...data,
		},
		callback,
	);
};

// Paypal data

let paypalButtons;

const plansIds = {
	production: {
		business: {
			monthly: "P-0UB63026V1306202YMW4QP7A",
			annually: "P-5ST91621FA5466136MW4QPPI",
		},
		enterprise: {
			monthly: "P-09775653JC2457618MW4QPBQ",
			annually: "P-8YS088838H4496913MW4QOWQ",
		},
	},
	development: {
		business: {
			monthly: "P-49N160356B894240YMWZDZOA",
			annually: "P-3H188263Y4467252NMWZD36Y",
		},
		enterprise: {
			monthly: "P-2MG263859C231894EMWZD4YA",
			annually: "P-51B16060SH6757511MWZD5FI",
		},
	},
};

// Plan Buttons

const planInputEl = jQuery("input#plan");

const buttons = [
	{
		name: "free",
		label: "Free",
		subtitle: "All widgets, free to use forever.",
		price: { monthly: "$0", annually: "$0" },
		features: [
			"Bug Fixes",
			"Custom Branding (1 Domain)",
			"500 Pageviews / month",
		],
	},
	{
		name: "business",
		label: "Business",
		subtitle: "More Pageviews, Priority Support.",
		price: { monthly: "$19.99", annually: "$11.99" },
		features: [
			"All Free Features",
			"Priority Support",
			"5000 Pageviews / month",
		],
		popular: true,
	},
	{
		name: "enterprise",
		label: "Enterprise",
		subtitle: "Unlimited Pageviews, Multiple domains and feature requests.",
		price: { monthly: "$119.99", annually: "$71.99" },
		features: [
			"All Business Features",
			"Dedicated Account Manager",
			"Multi-Language Options",
			"Custom Branding (Multiple Domains)",
			"Widget & Feature Requests",
			"Unlimited Pageviews / month",
		],
	},
];

let planPeriod = "annually";
const togglePlanPeriod = () => {
	if (planPeriod === "monthly") {
		planPeriod = "annually";
	} else {
		planPeriod = "monthly";
	}
};

const planPeriodEl = document.createElement("div");
planPeriodEl.className = "jika-widgets-plan-period";
const monthlyLabel = document.createElement("h3");
monthlyLabel.textContent = "Monthly";
planPeriodEl.append(monthlyLabel);

const planPeriodToggle = document.createElement("button");
planPeriodToggle.className = "jika-widgets-plan-period-toggle toggled";
planPeriodToggle.type = "button";
planPeriodEl.append(planPeriodToggle);

const annuallyLabel = document.createElement("h3");
annuallyLabel.textContent = "Annually";
planPeriodEl.append(annuallyLabel);

const annuallyLabelSave = document.createElement("h3");
annuallyLabelSave.textContent = "Save 40%";
annuallyLabelSave.style.background = "#fe9431";
planPeriodEl.append(annuallyLabelSave);

jQuery(".jika-widges-plans").prepend(planPeriodEl);

jQuery(".jika-widgets-plan-period-toggle").click(() => {
	planPeriodToggle.classList.toggle("toggled");
	// jQuery(".jika-widgets-plan-period-toggle").toggleClass("toggled");
	togglePlanPeriod();
	buttons.forEach((button) => {
		jQuery("#plan_" + button.name + " .jika-widgets-plan-price > h3").text(
			button.price[planPeriod] || button.price,
		);
	});
});

let currentButton;

const buttonsEl = {};

buttons.forEach(function (button) {
	const { name, label, subtitle, price, features } = button;
	const buttonEl = jQuery("#plan_" + name);

	buttonsEl[name] = buttonEl;

	// Adding plan buttons content

	const planContent = [];
	const planLabel = document.createElement("h2");
	planLabel.className = "jika-widgets-plan-label";
	planLabel.textContent = label || "\xa0";
	planContent.push(planLabel);
	const planPrice = document.createElement("div");
	planPrice.className = "jika-widgets-plan-price";
	const planPriceLabel = document.createElement("h3");
	planPriceLabel.textContent = price[planPeriod] || price;
	planPrice.appendChild(planPriceLabel);
	if (typeof price !== "string") {
		const planPriceSuffix = document.createElement("h4");
		planPriceSuffix.textContent = " / Month";
		planPrice.appendChild(planPriceSuffix);
	}
	planContent.push(planPrice);
	const planSubtitle = document.createElement("h4");
	planSubtitle.textContent = subtitle;
	planContent.push(planSubtitle);
	const planFeatures = document.createElement("ul");
	planFeatures.className = "jika-widgets-plan-features";
	planFeatures.append(
		...features.map((feature) => {
			const planFeature = document.createElement("li");
			const planFeatureIcon = document.createElement("span");
			planFeatureIcon.className = "dashicons dashicons-yes";
			const planFeatureText = document.createElement("p");
			planFeatureText.textContent = feature;
			planFeature.append(planFeatureIcon, feature);
			return planFeature;
		}),
	);
	planContent.push(planFeatures);
	if (button.popular) {
		const planPopularTag = document.createElement("div");
		planPopularTag.className =
			"jika-widgets-plan-tag jika-widgets-plan-popular-tag";
		const planPopularTagIcon = document.createElement("span");
		planPopularTagIcon.className = "dashicons dashicons-star-filled";
		planPopularTag.append(planPopularTagIcon, "Popular");
		planContent.push(planPopularTag);
	}
	if (buttonEl.hasClass("jika-widgets-current")) {
		currentButton = button;
		const planCurrentTag = document.createElement("div");
		planCurrentTag.className =
			"jika-widgets-plan-tag jika-widgets-plan-current-tag";
		planCurrentTag.textContent = "Current";
		planContent.push(planCurrentTag);
		currentButton.removeCurrentPlanTag = () =>
			jQuery(".jika-widgets-plan-current-tag").remove();
	}
	jQuery("#plan_" + name + " .jika-widgets-spinner").remove();
	buttonEl.append(planContent);

	// handle plan selection

	buttonEl.click(function () {
		buttons
			.filter((v) => v !== button)
			.forEach((v) => {
				buttonsEl[v.name].removeClass("jika-widgets-active");
			});
		jQuery(".jika_widgets_paypal_buttons .button-danger").remove();
		buttonEl.addClass("jika-widgets-active");
		if (paypalButtons) {
			paypalButtons.close();
			paypalButtons = false;
		}
		if (
			!buttonEl.hasClass("jika-widgets-current") &&
			buttonEl.attr("id") !== "plan_free"
		) {
			if (!isProduction) {
				paypalButtons = {
					element: createButton({
						className: "jika-widgets-sandbox-paypal-button",
						textContent: "Sandbox Paypal Subscription",
						style:
							"width: 100%; font-size: 24px; border-radius: 5px; padding: 5px 0;",
					}),
					close: () =>
						jQuery(
							".jika_widgets_paypal_buttons .jika-widgets-sandbox-paypal-button",
						).remove(),
				};
				paypalButtons.element.addEventListener("click", () => {
					let action = Promise.resolve();
					if (currentButton.name !== "free") {
						action = getSubscriptionId();
					}
					action.then(() => {
						handleSubscribe(
							{ subscriptionID: "subscriptionID" },
							button.name,
							planPeriod,
						).then((res) => {
							buttonsEl[currentButton.name].removeClass("jika-widgets-current");
							currentButton.removeCurrentPlanTag();
							delete currentButton.removeCurrentPlanTag;
							currentButton = button;
							const planCurrentTag = document.createElement("div");
							planCurrentTag.className =
								"jika-widgets-plan-tag jika-widgets-plan-current-tag";
							planCurrentTag.textContent = "Current";
							buttonsEl[currentButton.name].addClass("jika-widgets-current");
							buttonsEl[currentButton.name].append(planCurrentTag);
							currentButton.removeCurrentPlanTag = () =>
								jQuery(".jika-widgets-plan-current-tag").remove();
							ajaxPost(
								"jika_widgets_after_paypal_subscription",
								{ plan: res.plan, token: res.token },
								function () {
									buttons.forEach((v) => {
										if (v.name !== res.plan) {
											buttonsEl[v.name].removeClass("jika-widgets-current");
										} else {
											buttonsEl[v.name].addClass("jika-widgets-current");
										}
									});
									paypalButtons.close();
								},
							);
						});
					});
				});
				jQuery(".jika_widgets_paypal_buttons").append(paypalButtons.element);
			} else if (window.paypal) {
				paypalButtons = paypal.Buttons({
					style: {
						shape: "rect",
						label: "subscribe",
					},
					createSubscription(data, actions) {
						let action = actions.subscription.create;
						if (currentButton.name !== "free") {
							action = (options) =>
								getSubscriptionId().then((res) =>
									actions.subscription.revise(res.subscription_id, options),
								);
						}
						return action({
							plan_id:
								plansIds[isProduction ? "production" : "development"][
									button.name
								][planPeriod],
						}).then((orderId) => orderId);
					},
					onApprove(data) {
						// Before ajax request we can send data to our server directly from JS
						// Auth Token is available at my_ajax_obj.token
						handleSubscribe(data, button.name, planPeriod).then((res) => {
							buttonsEl[currentButton.name].removeClass("jika-widgets-current");
							currentButton.removeCurrentPlanTag();
							delete currentButton.removeCurrentPlanTag;
							currentButton = button;
							const planCurrentTag = document.createElement("div");
							planCurrentTag.className =
								"jika-widgets-plan-tag jika-widgets-plan-current-tag";
							planCurrentTag.textContent = "Current";
							buttonsEl[currentButton.name].addClass("jika-widgets-current");
							buttonsEl[currentButton.name].append(planCurrentTag);
							currentButton.removeCurrentPlanTag = () =>
								jQuery(".jika-widgets-plan-current-tag").remove();
							ajaxPost(
								"jika_widgets_after_paypal_subscription",
								{ plan: res.plan, token: res.token },
								function () {
									buttons.forEach((v) => {
										if (v.name !== res.plan) {
											buttonsEl[v.name].removeClass("jika-widgets-current");
										} else {
											buttonsEl[v.name].addClass("jika-widgets-current");
										}
									});
									paypalButtons.close();
								},
							);
						});
					},
				});
				paypalButtons.render(".jika_widgets_paypal_buttons");
			}
		} else if (!buttonEl.hasClass("jika-widgets-current")) {
			// if clicked on free and has another plan
			const paypalCancelButton = createButton(
				{
					className: "jika-widgets-paypal-cancel-button",
					textContent: "Cancel Subscription",
				},
				"danger",
			);
			paypalCancelButton.addEventListener("click", () => {
				handleSubscriptionCancel().then((res) => {
					buttonsEl[currentButton.name].removeClass("jika-widgets-current");
					currentButton.removeCurrentPlanTag();
					delete currentButton.removeCurrentPlanTag;
					currentButton = button;
					const planCurrentTag = document.createElement("div");
					planCurrentTag.className =
						"jika-widgets-plan-tag jika-widgets-plan-current-tag";
					planCurrentTag.textContent = "Current";
					buttonsEl[currentButton.name].addClass("jika-widgets-current");
					buttonsEl[currentButton.name].append(planCurrentTag);
					currentButton.removeCurrentPlanTag = () =>
						jQuery(".jika-widgets-plan-current-tag").remove();
					ajaxPost(
						"jika_widgets_after_paypal_subscription",
						{ plan: res.plan, token: res.token },
						function (newPlan) {
							buttons.forEach((v) => {
								if (v.name !== res.plan) {
									buttonsEl[v.name].removeClass("jika-widgets-current");
								} else {
									buttonsEl[v.name].addClass("jika-widgets-current");
								}
							});
							jQuery(
								".jika_widgets_paypal_buttons .jika-widgets-paypal-cancel-button",
							).remove();
						},
					);
				});
			});
			jQuery(".jika_widgets_paypal_buttons").append(paypalCancelButton);
		}
		planInputEl.attr("value", button.name);
	});
});

// handle domain change

let initialDomain = jQuery("#domain").val();

jQuery("#domain").change(function (event) {
	const form = event.target.closest("form");
	if (
		!["signup", "contact"].includes(form.querySelector("#auth_submit")?.name)
	) {
		const { value } = event.target;
		if (value !== initialDomain) {
			jQuery(".jika-widgets-update-domain-button").remove();

			const updateButton = createButton({
				className: "jika-widgets-update-domain-button",
				textContent: "Update Domain",
			});
			event.target.parentElement.appendChild(updateButton);
			updateButton.addEventListener("click", function () {
				handleUpdateDomain(value).then((res) => {
					ajaxPost(
						"jika_widgets_update_domain",
						{
							domain: res.domains[0],
							token: res.token,
						},
						() => {
							createSuccessNotice(form, "Successfully update domain");
							jQuery(".jika-widgets-update-domain-button").remove();
							initialDomain = value;
						},
					);
				});
			});
		} else {
			jQuery(".jika-widgets-update-domain-button").remove();
		}
	}
	// event.target.parentElement.appendChild(updateButton);
});

// handle trademark upload

jQuery("#trademark-alias").click(function () {
	jQuery("#trademark").click();
});

jQuery("#trademark").change(function (event) {
	jQuery("#trademark-alias").addClass("hidden");
	jQuery(".jika_widgets_trademark .jika_widgets_trademark_preview").remove();
	jQuery(".jika-widgets-upload-trademark-button").remove();
	const [file] = event.target.files;
	const updateButton = createButton({
		className: "jika-widgets-upload-trademark-button",
		textContent: "Upload Trademark",
	});
	updateButton.addEventListener("click", function () {
		// Before ajax request we can send data to our server directly from JS
		// Auth Token is available at my_ajax_obj.token
		// Its better here to upload file to s3 and pass url to ajax
		jQuery(".jika_widgets_trademark").addClass("jika_widgets_loading");
		const spinner = document.createElement("div");
		spinner.className = "jika-widgets-spinner";
		jQuery(".jika_widgets_trademark").append(spinner);
		handleUploadTrademark(file).then((res) => {
			ajaxPost(
				"jika_widgets_update_trademark",
				{
					trademark: res.trademark,
					token: res.token,
				},
				() => {
					jQuery("#trademark-alias").removeClass("hidden");
					jQuery(".jika-widgets-upload-trademark-button").remove();
					jQuery(
						".jika_widgets_trademark .jika_widgets_trademark_preview",
					).remove();
					const imagePreview = document.createElement("img");
					imagePreview.className = "jika_widgets_trademark_preview";
					imagePreview.src = res.trademark;
					imagePreview.alt = "";
					imagePreview.height = 35;
					imagePreview.width = 35;
					jQuery(".jika_widgets_trademark").prepend(imagePreview);
					jQuery(".jika_widgets_trademark .jika-widgets-spinner").remove();
					jQuery(".jika_widgets_trademark").removeClass("jika_widgets_loading");
					jQuery(".jika_widgets_trademark input").val("");
					createSuccessNotice(
						event.target.closest("form"),
						"Successfully update trademark",
					);
				},
			);
		});
	});
	const imagePreview = document.createElement("img");
	imagePreview.className = "jika_widgets_trademark_preview";
	imagePreview.src = URL.createObjectURL(file);
	imagePreview.alt = "";
	imagePreview.height = 35;
	imagePreview.width = 35;
	jQuery(".jika_widgets_trademark").prepend(imagePreview, updateButton);
});

// Handle auth_type change (login/signup)

jQuery("#auth_toggle").click(async (event) => {
	const ajaxData = {};
	if (event.target.name === "login") {
		ajaxData.auth_type = "login";
	} else {
		ajaxData.auth_type = "signup";
	}
	ajaxPost("jika_widgets_auth_toggle", ajaxData, function () {
		event.target.closest("form").submit();
	});
});

// Handle Password check

const passwordRequirements = [
	["8 characters long", (v) => v.length > 7],
	["Upper-case letter", (v) => /[A-Z]/.test(v)],
	["Lower-case letter", (v) => /[a-z]/.test(v)],
	["Number", (v) => /[0-9]/.test(v)],
];

const passwordRequirementsElements = [];

passwordRequirements.forEach(([label]) => {
	const element = document.createElement("p");
	element.className = "password-requirement";
	element.textContent = label;
	passwordRequirementsElements.push(element);
});

jQuery('input[name="jika_widgets_options[jika_widgets_password]"]').change(
	(event) => {
		jQuery("p.password-requirement").remove();
		const { pattern, value } = event.target;
		if (pattern && value) {
			const isInvalid = passwordRequirements.some(([, requirementCheck], i) => {
				if (!requirementCheck(value)) {
					passwordRequirementsElements[i].style.color = "red";
					return true;
				}
				passwordRequirementsElements[i].style.color = "green";
				return false;
			});
			if (isInvalid) {
				jQuery(
					'input[name="jika_widgets_options[jika_widgets_password]"]',
				).after(passwordRequirementsElements);
			}
		}
	},
);

// Handle Form submits

const getFormValues = (form, ...keys) =>
	keys.map((key) => form[`jika_widgets_options[jika_widgets_${key}]`].value);

jQuery("#auth_submit").click(async function (event) {
	let submitAction;
	const form = event.target.closest("form");
	const { name } = event.target;
	if (form.reportValidity()) {
		if (name === "signup") {
			const [email, password, domain] = getFormValues(
				form,
				"email",
				"password",
				"domain",
			);
			submitAction = signup(email, password, domain);
		}
		if (name === "login") {
			const [email, password] = getFormValues(form, "email", "password");
			submitAction = login(email, password);
		}
		if (name === "contact") {
			const [email, domain, companyProfile, requestType, note] = getFormValues(
				form,
				"email",
				"domain",
				"company-profile",
				"request-type",
				"note",
			);
			submitAction = contactUs(
				email,
				domain,
				companyProfile,
				requestType,
				note,
			);
		}
	}
	if (submitAction) {
		form.classList.add("hidden");
		const spinner = document.createElement("div");
		spinner.className = "jika-widgets-spinner";
		form.parentElement.append(spinner);
		await submitAction
			.then((res) => {
				const ajaxData = {};
				Object.entries(res).forEach(([k, v]) => {
					if (k === "domains") {
						k = "domain";
						[v] = v;
					}
					ajaxData[k] = v;
				});
				if (name === "contact") {
					form.reset();
					form.classList.remove("hidden");
					form.parentElement.removeChild(spinner);
					createSuccessNotice(form, res.message);
				} else {
					ajaxPost("jika_widgets_auth_submit", ajaxData, function () {
						form.submit();
					});
				}
			})
			.catch((error) => {
				form.classList.remove("hidden");
				form.parentElement.removeChild(spinner);
				let errorMessage =
					"Something went wrong, please check your input and try again";
				if (error.status === 401) {
					errorMessage = "Wrong email or password";
				} else if (error.status === 409) {
					errorMessage = "Email already in use";
				} else if (error.status === 400) {
					errorMessage = error.message;
				}
				jQuery("#auth_error").text(errorMessage);
			});
	}
});

// Usage data

jQuery(() => {
	document.body.style.setProperty(
		"--usage",
		jQuery('input[name="jika_widgets_options[jika_widgets_usage]"]').data(
			"usage",
		),
	);
});
