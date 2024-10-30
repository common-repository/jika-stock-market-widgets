import { useBlockProps } from "@wordpress/block-editor";

export default function Save({ attributes }) {
	return (
		<div {...useBlockProps.save()}>
			<iframe
				className="jika-widgets-iframe"
				referrerPolicy="origin"
				width="100%"
				height={attributes.height}
				style={{
					background: attributes.background,
					padding: "10px",
					border: "none",
					borderRadius: "5px",
					boxShadow: "0 2px 4px 0 rgba(0,0,0,.2)",
				}}
				src={attributes.src}
			/>
		</div>
	);
}
