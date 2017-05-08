var elixir = require('laravel-elixir');

elixir(function(mix) {
	mix.rubySass([
		'main.sass'
	], './public/assets/css/main.css')
	.rubySass([
		'center.sass'
	], './public/assets/css/center.css')
	.scripts([
		'./bower_components/jquery/dist/jquery.js',
		'./bower_components/isotope/dist/isotope.pkgd.js',
		'./bower_components/bootstrap-sass/assets/javascripts/bootstrap.js',
		'./bower_components/jquery.cookie/jquery.cookie.js',
		'./bower_components/imagesloaded/imagesloaded.pkgd.js',
		'./bower_components/lavalamp/js/jquery.lavalamp.js',
		'./bower_components/swipebox/src/js/jquery.swipebox.js',
        './resources/assets/js/main.js'
    ], './public/assets/js')
    .copy('./bower_components/bootstrap-sass/assets/fonts/bootstrap', './public/assets/fonts/bootstrap')
	.copy('./resources/assets/fonts', './public/assets/fonts');
});
