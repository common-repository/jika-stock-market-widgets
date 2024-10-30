export default function HeightInput(props) {
	const { height, auto, handleChange } = props;
	return (
		<>
			<input
				type="text"
				name="height"
				onChange={handleChange}
				value={height}
				disabled={auto}
			></input>
			<div style={{ margin: "5px 0 0 0" }}>
				Auto{" "}
				<input
					type="checkbox"
					name="auto"
					onChange={handleChange}
					defaultChecked={auto}
				></input>
			</div>
		</>
	);
}
