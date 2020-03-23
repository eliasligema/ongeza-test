
//const { src, dest, watch, parallel } = require('gulp');
const gulp = require('gulp');
const concat = require('gulp-concat');
const minify = require('gulp-minify');
const minifyCSS = require('gulp-csso');
var sass = require('gulp-sass');

sass.compiler = require('node-sass');

// Scripts Task
gulp.task('scripts', function() {
    return gulp.src([
        './resources/assets/vendor/popper.min.js',
        './resources/assets/bootstrap/js/bootstrap.bundle.js',
        
        './resources/assets/vendor/accordion/jquery.dcjqaccordion.2.7.js',
        './resources/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
        './resources/assets/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
        './resources/assets/vendor/bs-custom-file-input/js/bs-custom-file-input.min.js',
        './resources/assets/vendor/slimscroll/js/jquery.nicescroll.min.js',
        './resources/assets/vendor/slimscroll/js/jquery.slimscroll.js',

        './resources/assets/vendor/daterangepicker/moment.min.js',
        './resources/assets/vendor/daterangepicker/daterangepicker.js',
        './resources/assets/vendor/fontawesome-iconpicker-master/dist/js/fontawesome-iconpicker.js',
        './resources/assets/vendor/fullcalendar/fullcalendar.min.js',
        './resources/assets/vendor/ubilabs-geocomplete/jquery.geocomplete.js',
        './resources/assets/vendor/chartjs/js/utils.js',
        './resources/assets/vendor/chartjs/js/chart.bundle.js',

        './resources/assets/vendor/iEdit/iEdit.js',
        './resources/assets/vendor/tagify/tagify.min.js',
        './resources/assets/vendor/tagify/tagify.polyfills.min.js',
        './resources/assets/vendor/tagify/jquery.tagify.min.js',
        './resources/assets/vendor/custom.js'
    ])
    .pipe(concat('./scripts.js'))
    .pipe(minify())
    .pipe(gulp.dest('./public/js/'));
});

// Styles Task
gulp.task('styles', function() {
    return gulp.src("./resources/assets/sass/styles.scss")
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(gulp.dest("./public/css/"));
});

gulp.task('watch', function() {
	gulp.watch('./resources/assets/**/*.scss', gulp.series('styles'));
	gulp.watch('./resources/assets/vendor/**/*.js', gulp.series('scripts'));
});

// Default Task
gulp.task('default', gulp.parallel('styles', 'scripts', 'watch'));
