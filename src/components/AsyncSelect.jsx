import { useState, useCallback, useRef, useEffect, Fragment } from "react";
import { Dropdown, SearchControl } from "@wordpress/components";
import useDebounceWithCancel from "../utils/debounceWithCancel";

export default function AsyncSelect(props) {
	const {
		onChange,
		loadOptions,
		isMulti,
		defaultValue,
		defaultOptions = "No options",
		placeholder = "Search",
	} = props;
	const selectedRef = useRef();
	const searchRef = useRef();
	const [optionsCache, setOptionsCache] = useState({});
	const [open, setOpen] = useState(false);
	const [selected, setSelected] = useState(defaultValue);
	const [options, setOptions] = useState([]);
	const [isLoading, setIsLoading] = useState(false);
	const [loadingText, setLoadingText] = useState("Loading");
	const [input, setInput] = useState("");
	const [handleLoadOptions, cancelLoadOptions] = useDebounceWithCancel(
		(inputValue) =>
			new Promise((resolve) => {
				if (optionsCache[inputValue]) {
					resolve(optionsCache[inputValue]);
				} else {
					const loadOptionsCall = loadOptions(inputValue);
					if (loadOptionsCall instanceof Promise) {
						loadOptionsCall.then((data) => {
							setOptionsCache((currentState) => ({
								...currentState,
								[inputValue]: data,
							}));
							resolve(data);
						});
					} else {
						setOptionsCache((currentState) => ({
							...currentState,
							[inputValue]: loadOptionsCall,
						}));
						resolve(loadOptionsCall);
					}
				}
			})
				.then(setOptions)
				.then(() => {
					setIsLoading(false);
				}),
	);
	const handleChange = useCallback(
		(inputValue) => {
			cancelLoadOptions();
			setInput(inputValue);
			if (inputValue) {
				setIsLoading(true);
				handleLoadOptions(inputValue);
			} else {
				setIsLoading(false);
				setOptions([]);
			}
		},
		[cancelLoadOptions, handleLoadOptions],
	);
	const RenderToggle = useCallback(
		({ isOpen, onToggle }) => (
			<div
				className={`jika-widgets-async-select${
					isMulti ? " jika-widgets-async-select-multi" : ""
				}`}
				onClick={(event) => {
					if (event.target.name.includes("remove-option")) {
						const item = event.target.name.replace("remove-option-", "");
						const newSelected = selected.filter((v) => v !== item);
						setSelected(newSelected);
						onChange(newSelected);
					} else {
						searchRef.current.focus();
					}
				}}
				onFocus={() => {
					setTimeout(() => {
						searchRef.current.focus();
					}, 100);
				}}
			>
				{!input && selected ? (
					<div ref={selectedRef} className="jika-widgets-async-select-selected">
						{!isMulti ? (
							selected
						) : (
							<>
								{selected.map((item) => (
									<div
										key={item}
										className="jika-widgets-async-select-selected-item"
									>
										<div>{item}</div>
										<button type="button" name={`remove-option-${item}`}>
											x
										</button>
									</div>
								))}
								<button type="button">+</button>
							</>
						)}
					</div>
				) : null}
				<SearchControl
					ref={searchRef}
					value={input}
					onChange={(value) => {
						handleChange(value);
						if ((isOpen && !value) || (!isOpen && value)) {
							onToggle();
						}
					}}
					placeholder={selected ? "" : placeholder}
					onBlur={(event) => {
						if (
							!["components-popover", "jika-widgets-async-select-option"].some(
								(v) => event.relatedTarget.className.includes(v),
							)
						) {
							setOpen(false);
						}
					}}
					onFocus={() => {
						setOpen(true);
					}}
				/>
			</div>
		),
		[input, selected, isMulti, placeholder, handleChange],
	);
	const RenderContent = useCallback(() => {
		let currentOptions;
		if (isLoading) {
			currentOptions = loadingText;
		} else if (options.length === 0) {
			if (!input) {
				currentOptions = defaultOptions;
			} else {
				currentOptions = "No options";
			}
		} else {
			currentOptions = options;
		}
		if (Array.isArray(currentOptions)) {
			currentOptions = currentOptions
				.reduce((acc, option) => {
					if (Array.isArray(option.options)) {
						return [
							...acc,
							{
								...option,
								options: option.options.filter((v) => {
									if (Array.isArray(selected)) {
										return !selected.find((s) => s === v.key);
									}
									return selected !== v.key;
								}),
							},
						];
					}
					if (Array.isArray(selected)) {
						if (!selected.find((v) => v === option.key)) {
							return [...acc, option];
						}
						return acc;
					}
					if (selected !== option.key) {
						return [...acc, option];
					}
					return acc;
				}, [])
				.map((option) => {
					if (Array.isArray(option.options)) {
						return (
							<Fragment key={option.label}>
								<h4>{option.label}</h4>
								{option.options.map((v) => (
									<button
										key={v.key}
										type="button"
										className="jika-widgets-async-select-option"
										style={{}}
										onClick={() => {
											console.log("option click");
											setInput("");
											let newSelected;
											if (isMulti) {
												if (!selected) {
													newSelected = [v.key];
												} else {
													newSelected = [...new Set([...selected, v.key])];
												}
												setSelected(newSelected);
												onChange(newSelected);
											} else {
												setSelected(v.name);
												onChange(v);
											}
											setOpen(false);
										}}
									>
										{v.name}
									</button>
								))}
							</Fragment>
						);
					}
					return (
						<button
							key={option.key}
							type="button"
							className="jika-widgets-async-select-option"
							style={{}}
							onClick={() => {
								console.log("option click");
								setInput("");
								let newSelected;
								if (isMulti) {
									if (!selected) {
										newSelected = [option.key];
									} else {
										newSelected = [...new Set([...selected, option.key])];
									}
									setSelected(newSelected);
									onChange(newSelected);
								} else {
									setSelected(option.name);
									onChange(option);
								}
								setOpen(false);
							}}
						>
							{option.name}
						</button>
					);
				});
		}
		return (
			<div className="jika-widgets-async-select-options">{currentOptions}</div>
		);
	}, [isLoading, loadingText, options, input, selected]);
	useEffect(() => {
		let intervalId;
		if (isLoading) {
			intervalId = setInterval(() => {
				setLoadingText((currentState) => {
					let dots = currentState.replace("Loading", "");
					if (dots.length === 3) {
						dots = "";
					} else {
						dots += ".";
					}
					const newState = `Loading${dots}`;
					return newState;
				});
			}, 500);
		} else {
			clearInterval(intervalId);
			setLoadingText("Loading");
		}
		return () => {
			clearInterval(intervalId);
		};
	}, [isLoading]);
	useEffect(() => {
		if (searchRef.current && selectedRef.current) {
			searchRef.current.style.height = `${
				selectedRef.current.offsetHeight + 20
			}px`;
		}
	}, [selected]);
	return (
		<Dropdown
			open={open}
			onToggle={setOpen}
			renderToggle={RenderToggle}
			renderContent={RenderContent}
		/>
	);
}
