const articleId = '{{ session("articleId") }}';
const textBox = document.getElementsByName("article_url")[0]; // Assuming there is only one element with the name "textboxName"
const textBoxValue = textBox.value;
const svrApi = textBoxValue + '/internalknowledge/mindmap/' + articleId + '/';

/**
 * @param {string} key
 * @param {DiagramSerialized} serialized
 * @returns {Promise}
 */
export async function srvSave(key, serialized, articleId) {

	const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	const postApi = textBoxValue +'/internalknowledge/mindmap/' + key + '/' + articleId;

	const newUrl = window.location.href + '?k=' + key;

	// Add the key to the serialized object
	// serialized.key = serialized;
	const requestData = {
		data: serialized,
		newurl: newUrl,
		url: postApi
	};

	try {
		const response = await fetch(postApi, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json;charset=utf-8',
				'X-CSRF-TOKEN': csrfToken,
			},
			body: JSON.stringify(requestData)
		});

		if (response.ok) {
			const responseData = await response.json();
			const recordId = responseData.record_id; // Access the ID from the response
			console.log('Record ID:', recordId);
		} else {
			console.error('Failed to save data.');
		}
	} catch (error) {
		console.error('Error:', error);
	}
}


/**
 * get diagram json by key
 * @param {string} key
 * @returns {Promise<DiagramSerialized>}
 */
export async function srvGet(key, articleId) {

	const getApi =  textBoxValue + '/internalknowledge/getmindmap/' + articleId + '/' + key;

	const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	try {
		const response = await fetch(getApi, {
			method: 'GET',
			headers: {
				'Content-Type': 'application/json;charset=utf-8',
				'X-CSRF-TOKEN': csrfToken,
			},
		});
		if (response.ok) {
			const responseData = await response.json();
			// Process the JSON data here
			console.log('JSON Data:', responseData);
		} else {
			console.error('Failed to fetch data.');
		}
	} catch (error) {
		console.error('Error:', error);
	}


	return (await fetch(`${getApi}/`)).json();
}

export function generateKey() {
	const arr = new Uint8Array((8 / 2));
	window.crypto.getRandomValues(arr);
	const date = new Date();
	return `${date.getUTCFullYear()}${(date.getUTCMonth() + 1).toString().padStart(2, '0')}${Array.from(arr, dec => dec.toString(16).padStart(2, '0')).join('')}`;
}

/** @typedef { import("./dgrm-serialization").DiagramSerialized } DiagramSerialized */