// Play initial animations
window.setTimeout(function() {
	document.querySelector('body').classList.remove('is-preload');
}, 100);

// MathJax symbols for math equations
window.MathJax = {
	tex: {
		inlineMath: [['$', '$']],
	},
	options: {
		skipHtmlTags: {'[-]': ['code', 'pre']}
	}
};
