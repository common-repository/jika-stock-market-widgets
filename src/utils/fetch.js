const baseUrl = "https://www.jika.io/api";

/**
 *
 * @param {string} path
 * @param {RequestInit | undefined} config
 */

const handleFetch = (path, config) =>
	fetch(`${baseUrl}${path}`, config).then((res) => res.json());

export { handleFetch };
