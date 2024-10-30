class Store {
	constructor() {
		this._listeners = {};
		this._store = {};
	}

	on(key, callback) {
		if (!this._listeners[key]) {
			this._listeners[key] = [];
		}
		if (!this._listeners[key].includes(callback)) {
			this._listeners[key].push(callback);
		}
	}

	off(key, callback) {
		if (this._listeners[key]) {
			this._listeners[key] = this._listeners[key].filter((v) => v !== callback);
		}
	}

	emit(key, ...args) {
		if (this._listeners[key]) {
			this._listeners[key].forEach((v) => {
				v(...args);
			});
		}
	}

	/**
	 * @public
	 * @method create
	 * @param {string} key
	 * @param {*} value
	 * @returns {{
	 *  get: () => *;
	 *  set: (newValue: *) => void;
	 *  subscribe: (callback: Function) => void;
	 *  unsubscribe: (callback: Function) => void;
	 *  key: string;
	 *}}
	 */
	create(key, value) {
		this._store[key] = value;
		this.emit(`change-${key}`, value);
		return {
			get: () => this.get(key),
			set: (newValue) => this.set(key, newValue),
			subscribe: (callback) => {
				this.on(`change-${key}`, callback);
			},
			unsubscribe: (callback) => {
				this.removeListener(`change-${key}`, callback);
			},
			key,
		};
	}

	/**
	 * @public
	 * @method get
	 * @param {string} name
	 * @returns {*}
	 */

	get(key) {
		return this._store[key];
	}

	/**
	 * @public
	 * @method set
	 * @param {string} name
	 * @param {*} value
	 */

	set(key, value) {
		if (typeof value === "function") {
			value = value(this._store[key]);
		}
		if (value instanceof Promise) {
			value.then((res) => {
				this._store[key] = res;
				this.emit(`change-${key}`, res);
			});
		} else {
			this._store[key] = value;
			this.emit(`change-${key}`, value);
		}
	}
}

const store = new Store();

const handleStoreCheck = (key) =>
	new Promise((resolve) => {
		const companyList = store.get(key);
		if (companyList) {
			resolve(companyList);
		} else {
			store.on(`change-${key}`, resolve);
		}
	});
