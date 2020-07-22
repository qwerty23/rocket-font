WebFontConfig = {
	custom: {
		families: ['Nanum Brush Script'],
		urls: ['http://fonts.googleapis.com/earlyaccess/nanumbrushscript.css']
	},
	active: function() {
		sessionStorage.fonts = true;
	}
};

(function() {
	var wf = document.createElement('script');
	var s = document.getElementsByTagName('script')[0];
	
	wf.src = 'https://cdn.jsdelivr.net/webfontloader/1.6.24/webfontloader.js';
	wf.type = 'text/javascript';
	wf.async = 'true';
	
	s.parentNode.insertBefore(wf, s);
	
	/*
	if (sessionStorage.fonts) {
        console.log("Fonts installed.");
        document.documentElement.classList.add('wf-active');
      } else {
        console.log("No fonts installed.");
      }
     */
})();