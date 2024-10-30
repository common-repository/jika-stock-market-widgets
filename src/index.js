import { registerBlockType, getBlockType } from "@wordpress/blocks";
import "./styles/style.scss";
import block1metadata from "./blocks/real-time-stock-price-chart/block.json";
import block1edit from "./blocks/real-time-stock-price-chart/edit";
import block2metadata from "./blocks/advanced-stock-comparison-graph/block.json";
import block2edit from "./blocks/advanced-stock-comparison-graph/edit";
import block3metadata from "./blocks/company-financial-metrics-table/block.json";
import block3edit from "./blocks/company-financial-metrics-table/edit";
import block4metadata from "./blocks/stock-prediction-and-forecast-widget/block.json";
import block4edit from "./blocks/stock-prediction-and-forecast-widget/edit";
import block5metadata from "./blocks/stock-portfolio-performance-chart/block.json";
import block5edit from "./blocks/stock-portfolio-performance-chart/edit";
import save from "./blocks/save";

[
	[block1metadata, block1edit],
	[block2metadata, block2edit],
	[block3metadata, block3edit],
	[block4metadata, block4edit],
	[block5metadata, block5edit],
].forEach(([block, edit]) => {
	if (!getBlockType(block.name)) {
		registerBlockType(block.name, {
			edit,
			save,
		});
	}
});
