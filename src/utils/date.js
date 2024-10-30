const date = new Date();

const currentYear = date.getFullYear();

const yearsRange = [];

for (let i = 2011; i <= currentYear; i += 1) {
	yearsRange.push(i.toString());
}

export { currentYear, yearsRange };
