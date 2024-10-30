import { useState, useCallback, useEffect } from "react";
import { useSelect } from "@wordpress/data";
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { ColorPicker, Dropdown, TextControl } from "@wordpress/components";
import "../styles/editor.scss";
import {
	calculateIframeHeight,
	configTemplate,
	embedOptionsBaseUrls,
	valuesFromConfig,
	widgetsInfoData,
} from "../utils/widgets";
import AsyncSelect from "./AsyncSelect";
import HeightInput from "./HeightInput";
import { yearsRange } from "../utils/date";
import store from "../utils/store";
import { handleFetch } from "../utils/fetch";
import kickstart from "../utils/kickstart";

if (!store.get("kickstart")) {
	kickstart();
}

export default function IframeBlock(props) {
	const { name, attributes, setAttributes } = props;
	const config = configTemplate[name];
	const financialKeys = useSelect(() => store.get("financialKeys"));
	const companyList = useSelect(() => store.get("companyList"));
	const leaderboardMembers = useSelect(() => store.get("leaderboardMembers"));
	const [values, setValues] = useState(valuesFromConfig(config));
	const handleChange = useCallback((event) => {
		const { name, value, checked } = event.target;
		if (["backgroundColor", "height", "auto"].includes(name)) {
			if (name === "auto") {
				setAttributes({ [name]: checked });
			} else {
				setAttributes({ [name]: value });
			}
		}
		if (!["height", "auto"].includes(name)) {
			setValues((currentState) => ({ ...currentState, [name]: value }));
		}
	}, []);
	const handleGenerate = useCallback(() => {
		let newIframeSrc = `${embedOptionsBaseUrls[name]}?`;
		Object.entries(values).forEach(([key, value]) => {
			if (Array.isArray(value)) {
				value = value.join(",");
			}
			if (value) {
				newIframeSrc += `${key}=${value.replace(/^#/, "")}&`;
			}
		});
		const height = calculateIframeHeight(name, values.keys);
		const attributesSet = { src: newIframeSrc };
		if (attributes.auto) {
			attributesSet.height = height;
		}
		setAttributes(attributesSet);
	}, [name, values, attributes.auto]);
	useEffect(() => {
		handleGenerate();
	}, []);
	return (
		<>
			<InspectorControls>
				<div className="jika-widgets-inspector-controls">
					{config.map((configItem) => {
						if (configItem.type === "text") {
							return (
								<div key={configItem.name}>
									<h4>{configItem.label}</h4>
									<input
										type="text"
										name={configItem.name}
										placeholder={configItem.placeholder}
										value={values[configItem.name]}
										onChange={handleChange}
									/>
								</div>
							);
						}
						if (configItem.type === "financialKeys") {
							return (
								<div key={configItem.name}>
									<h4>{configItem.label}</h4>
									<AsyncSelect
										loadOptions={(inputValue) =>
											financialKeys
												.reduce((acc, financialKey) => {
													if (Array.isArray(financialKey.options)) {
														return [
															...acc,
															...financialKey.options.filter(
																(v) =>
																	!acc.find((k) => k.key === v.key) &&
																	v.key
																		.toLowerCase()
																		.includes(inputValue.toLowerCase()),
															),
														];
													}
													if (acc.find((v) => v.key === financialKey.key)) {
														return acc;
													}
													if (
														financialKey.key
															.toLowerCase()
															.includes(inputValue.toLowerCase())
													) {
														return [...acc, financialKey];
													}
													return acc;
												}, [])
												.sort((a, b) => {
													if (
														a.key.toLowerCase() === inputValue.toLowerCase()
													) {
														return -1;
													}
													if (
														b.key.toLowerCase() === inputValue.toLowerCase()
													) {
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
												})
										}
										onChange={(value) => {
											handleChange({
												target: { name: configItem.name, value: value },
											});
										}}
										defaultValue={values[configItem.name]}
										defaultOptions={financialKeys}
										isMulti
									/>
								</div>
							);
						}
						if (configItem.type === "user") {
							return (
								<div key={configItem.name}>
									<h4>{configItem.label}</h4>
									<AsyncSelect
										loadOptions={(inputValue) =>
											handleFetch(
												`/users_list/${inputValue}?is_verified=portfolio`,
											).then((res) =>
												res.result.users_list.map((user) => ({
													key: user.user_name,
													name: user.user_name,
												})),
											)
										}
										onChange={(value) => {
											handleChange({
												target: { name: configItem.name, value: value.key },
											});
										}}
										defaultValue={values[configItem.name]}
										defaultOptions={leaderboardMembers}
									/>
								</div>
							);
						}
						if (configItem.type === "symbol") {
							return (
								<div key={configItem.name}>
									<h4>{configItem.label}</h4>
									<AsyncSelect
										loadOptions={(inputValue) =>
											handleFetch(`/company_list/${inputValue}/not-etf`).then(
												(res) =>
													res.result.company_list.map((item) => ({
														key: item.symbol,
														name: `${item.symbol} - ${item.company_name}`,
													})),
											)
										}
										onChange={(value) => {
											handleChange({
												target: { name: configItem.name, value: value.key },
											});
										}}
										defaultValue={values[configItem.name]}
										defaultOptions={companyList}
									/>
								</div>
							);
						}
						if (configItem.type === "symbols") {
							return (
								<div key={configItem.name}>
									<h4>{configItem.label}</h4>
									<AsyncSelect
										loadOptions={(inputValue) =>
											handleFetch(`/company_list/${inputValue}/not-etf`).then(
												(res) =>
													res.result.company_list.map((item) => ({
														key: item.symbol,
														name: `${item.symbol} - ${item.company_name}`,
													})),
											)
										}
										onChange={(value) => {
											handleChange({
												target: { name: configItem.name, value },
											});
										}}
										defaultValue={values[configItem.name]}
										defaultOptions={companyList}
										isMulti
									/>
								</div>
							);
						}
						if (configItem.type === "options") {
							return (
								<div key={configItem.name}>
									<h4>{configItem.label}</h4>
									<select
										name={configItem.name}
										onChange={handleChange}
										value={values[configItem.name]}
									>
										{configItem.options.map((option) => (
											<option key={option.value} value={option.value}>
												{option.label}
											</option>
										))}
									</select>
								</div>
							);
						}
						if (configItem.type === "yearRange") {
							return (
								<div key={configItem.name}>
									<h4>{configItem.label}</h4>
									<select
										name={configItem.name}
										value={values[configItem.name]}
										onChange={handleChange}
									>
										{configItem.name === "from"
											? yearsRange.map((item, index) => (
													<option key={`${index}-${item}`} value={item}>
														{item}
													</option>
											  ))
											: yearsRange
													.filter((item) => item >= values.from)
													.map((item, index) => (
														<option key={`${index}-${item}`} value={item}>
															{item}
														</option>
													))}
									</select>
								</div>
							);
						}
						if (configItem.type === "color") {
							return (
								<div key={configItem.name}>
									<h4>{configItem.label}</h4>
									<Dropdown
										className="my-container-class-name"
										contentClassName="my-popover-content-classname"
										popoverProps={{ placement: "bottom-start" }}
										renderToggle={({ isOpen, onToggle }) => (
											<TextControl
												variant="primary"
												onClick={onToggle}
												value={values[configItem.name]}
												defaultValue={values[configItem.name]}
												onChange={(value) => {
													handleChange({
														target: { name: configItem.name, value },
													});
												}}
												aria-expanded={isOpen}
											/>
										)}
										renderContent={() => (
											<ColorPicker
												color={values[configItem.name]}
												defaultValue={values[configItem.name]}
												onChange={(value) => {
													handleChange({
														target: { name: configItem.name, value },
													});
												}}
											/>
										)}
									/>
								</div>
							);
						}
					})}
					<div>
						<h4>Height</h4>
						<HeightInput
							height={attributes.height}
							auto={attributes.auto}
							handleChange={handleChange}
						/>
					</div>
					<button
						type="button"
						onClick={handleGenerate}
						style={{
							cursor: "pointer",
							margin: "15px 0",
							padding: "15px",
							border: "none",
							backgroundColor: "#1652F0",
							color: "white",
							borderRadius: "5px",
							fontWeight: "bold",
						}}
					>
						Generate Block
					</button>
				</div>
			</InspectorControls>
			<div {...useBlockProps()}>
				<h3>{`Jika Widgets – ${widgetsInfoData[name].title}`}</h3>
				{attributes.src ? (
					<iframe
						className="jika-widgets-iframe"
						referrerPolicy="no-referrer-when-downgrade"
						width="100%"
						height={attributes.height}
						style={{
							background: attributes.background,
							padding: "10px",
							border: "none",
							borderRadius: "5px",
							boxShadow: "0 2px 4px 0 rgba(0,0,0,.2)",
						}}
						src={`${attributes.src}&api_key=${encodeURIComponent(
							jika_widgets_editor_obj.api_key,
						)}`}
					/>
				) : null}
			</div>
		</>
	);
}
