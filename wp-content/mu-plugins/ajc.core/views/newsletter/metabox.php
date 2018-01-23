<style>
pre {
	height: 120px;
	overflow: auto;
	font-family: “Consolas”,monospace;
	font-size: 9pt;
	text-align:left;
	background-color: #FCF7EC;
	overflow-x: auto; /* Use horizontal scroller if needed; for Firefox 2, not */
	white-space: pre-wrap; /* css-3 */
	white-space: -moz-pre-wrap !important; /* Mozilla, since 1999 */
	word-wrap: break-word; /* Internet Explorer 5.5+ */
	margin: 0px 0px 0px 0px;
	padding:5px 5px 3px 5px;
	white-space : normal; /* crucial for IE 6, maybe 7? */
}
</style>
<button id="newsletter-markup" data-id="<?php global $post; echo $post->ID; ?>">Get Markup</button>
<pre id="markup-output"></pre>