import { register, select, dispatch } from "@wordpress/data";

if (!select("jika-widgets-data")) {
	register({
		name: "jika-widgets-data",
		instantiate: () => {
			const store = {};
			const listeners = new Set();

			function storeChanged() {
				for (const listener of listeners) {
					listener();
				}
			}

			/**
			 * @public
			 * @method subscribe
			 * @param {Function} listener
			 * @returns {Function}
			 */

			function subscribe(listener) {
				listeners.add(listener);
				return () => listeners.delete(listener);
			}

			/**
			 * @name storeGet
			 * @param {string} name
			 * @returns {*}
			 */

			function storeGet(key) {
				return store[key];
			}

			/**
			 * @name storeSet
			 * @param {string} name
			 * @param {*} value
			 */

			function storeSet(key, value) {
				if (typeof value === "function") {
					value = value(store[key]);
				}
				if (value instanceof Promise) {
					value.then((res) => {
						store[key] = res;
					});
				} else {
					store[key] = value;
				}
			}

			return {
				getSelectors() {
					return {
						get: function (key) {
							return storeGet(key);
						},
					};
				},
				getActions() {
					return {
						set: function (key, value) {
							storeSet(key, value);
							storeChanged();
						},
					};
				},
				subscribe,
			};
		},
	});
}

function getStoreValue(key) {
	return select("jika-widgets-data").get(key);
}

function setStoreValue(key, value) {
	return dispatch("jika-widgets-data").set(key, value);
}

const store = {
	get: getStoreValue,
	set: setStoreValue,
};

export default store;
