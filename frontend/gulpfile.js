var gulp = require('gulp');
var less = require('gulp-less');
var minifycss = require('gulp-minify-css');
var notify = require('gulp-notify');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var path = require('path');

var BASE_PATH = '../public/assets/';

gulp.task('less', function() {
    return gulp.src('src/less/main.less')
        .pipe(less({
            paths: [ path.join(__dirname, 'theme_components', 'bower', 'bootstrap', 'less') ]
        }))
        .pipe(minifycss())
        .pipe(gulp.dest(BASE_PATH + 'css'))
        .pipe(notify({"message": "LESS compiled!"}));
});

var third_party = [
    "theme_components/bower/jquery/dist/jquery.min.js",
    "theme_components/bower/bootstrap/dist/js/bootstrap.min.js",
    "theme_components/bower/angular/angular.js",
    "theme_components/bower/angular-route/angular-route.js",
    "theme_components/bower/lodash/dist/lodash.underscore.min.js",
    "theme_components/bower/bluebird/js/browser/bluebird.js",
    "theme_components/bower/angular-google-maps/dist/angular-google-maps.min.js",
    "theme_components/bower/angular-sanitize/angular-sanitize.min.js",
    "theme_components/bower/angular-bootstrap/ui-bootstrap.min.js",
    "theme_components/bower/angular-bootstrap/ui-bootstrap-tpls.min.js",
    "theme_components/bower/ng-flow/dist/ng-flow-standalone.min.js",
    "theme_components/bower/angular-dialog-service/dialogs.min.js",
    "theme_components/bower/angular-dragdrop-ganarajpr/draganddrop.js"
];

gulp.task('js', function() {
    return gulp.src(third_party.concat(['src/js/**/*.js']))
        .pipe(concat('main.js'))
        //.pipe(uglify())
        .pipe(gulp.dest(BASE_PATH + 'js'))
        .pipe(notify({"message": "JS compiled!"}));
});

gulp.task('watch', function() {
    gulp.watch('src/less/*.less', ['less']);

    gulp.watch('src/js/**/*.js', ['js']);
})

gulp.task('default', ['less', 'js', 'watch']);