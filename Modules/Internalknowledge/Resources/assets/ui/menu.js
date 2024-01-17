import { canvasClear } from '../diagram/canvas-clear.js';
import { dgrmPngChunkGet, dgrmPngCreate } from '../diagram/dgrm-png.js';
import { deserialize, serialize } from '../diagram/dgrm-serialization.js';
import { generateKey, srvSave } from '../diagram/dgrm-srv.js';
import { fileOpen, fileSave } from '../infrastructure/file.js';
import { tipShow, uiDisable } from './ui.js';

export class Menu extends HTMLElement {
	connectedCallback() {
		const shadow = this.attachShadow({ mode: 'closed' });
		shadow.innerHTML = `
			<style>
			.menu {
				position: fixed;
				top: 15px;
				left: 15px;
				cursor: pointer;
			}
			#options {
				position: fixed;
				padding: 15px;
				box-shadow: 0px 0px 58px 2px rgb(34 60 80 / 20%);
				border-radius: 16px;
				background-color: rgba(255,255,255, .9);

				top: 0px;
				left: 0px;

				z-index: 1;
			}

			#options div, #options a { 
				color: rgb(13, 110, 253); 
				cursor: pointer; margin: 10px 0;
				display: flex;
				align-items: center;
				line-height: 25px;
				text-decoration: none;
			}
			#options div svg, #options a svg { margin-right: 10px; }
			</style>
			<svg id="menu" class="menu" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 4h18v2H3V4zm0 7h18v2H3v-2zm0 7h18v2H3v-2z" fill="rgb(52,71,103)"/></svg>
			<div id="options" style="visibility: hidden;">
			 	<div id="menu2" style="margin: 0 0 15px;"><svg viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 4h18v2H3V4zm0 7h18v2H3v-2zm0 7h18v2H3v-2z" fill="rgb(52,71,103)"/></svg></div>
				<div id="link"><svg viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.06 8.11l1.415 1.415a7 7 0 0 1 0 9.9l-.354.353a7 7 0 0 1-9.9-9.9l1.415 1.415a5 5 0 1 0 7.071 7.071l.354-.354a5 5 0 0 0 0-7.07l-1.415-1.415 1.415-1.414zm6.718 6.011l-1.414-1.414a5 5 0 1 0-7.071-7.071l-.354.354a5 5 0 0 0 0 7.07l1.415 1.415-1.415 1.414-1.414-1.414a7 7 0 0 1 0-9.9l.354-.353a7 7 0 0 1 9.9 9.9z" fill="rgb(52,71,103)"/></svg>Copy link to diagram</div>
		 	</div>`;

		const options = shadow.getElementById('options');
		function toggle() { options.style.visibility = options.style.visibility === 'visible' ? 'hidden' : 'visible'; }

		/** @param {string} id, @param {()=>void} handler */
		function click(id, handler) {
			shadow.getElementById(id).onclick = _ => {
				uiDisable(true);
				handler();
				toggle();
				uiDisable(false);
			};
		}

		shadow.getElementById('menu').onclick = toggle;
		shadow.getElementById('menu2').onclick = toggle;

		click('link', async () => {
			const serialized = serialize(this._canvas);
			if (serialized.s.length === 0) { alertEmpty(); return; }

			const key = generateKey();
			const url = window.location.pathname; // Get the path of the URL
			const articleId = url.substring(url.lastIndexOf('/') + 1); // Extract the articleId from the URL path

			// Modify the URL with the 'k' parameter
			const newUrl = window.location.href + '/?k=' + key;

			// Use clipboard before server call - to fix 'Document is not focused'
			await navigator.clipboard.writeText(newUrl);
			await srvSave(key, serialized, articleId);

			alert('Link to diagram copied to clipboard');
		});
	}

	/** @param {CanvasElement} canvas */
	init(canvas) {
		/** @private */ this._canvas = canvas;

		// file drag to window
		document.body.addEventListener('dragover', evt => { evt.preventDefault(); });
		document.body.addEventListener('drop', async evt => {
			evt.preventDefault();

			if (evt.dataTransfer?.items?.length !== 1 ||
				evt.dataTransfer.items[0].kind !== 'file' ||
				evt.dataTransfer.items[0].type !== 'image/png') {
				alertCantOpen(); return;
			}

			await loadData(this._canvas, evt.dataTransfer.items[0].getAsFile());
		});
	}
};
customElements.define('ap-menu', Menu);

/** @param {CanvasElement} canvas,  @param {Blob} png  */
async function loadData(canvas, png) {
	const dgrmChunk = await dgrmPngChunkGet(png);
	if (!dgrmChunk) { alertCantOpen(); return; }
	if (deserialize(canvas, JSON.parse(dgrmChunk))) {
		tipShow(false);
	}
}

const alertCantOpen = () => alert('File cannot be read. Use the exact image file you got from the application.');
const alertEmpty = () => alert('Diagram is empty');

/** @typedef { {x:number, y:number} } Point */
/** @typedef { import('../infrastructure/canvas-smbl.js').CanvasElement } CanvasElement */
