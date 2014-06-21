var gulp = require('gulp');
var less = require('gulp-less');
var minifycss = require('gulp-minify-css');
var notify = require('gulp-notify');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var path = require('path');

gulp.task('less', function() {
    return gulp.src('src/less/main.less')
        .pipe(less({
            paths: [ path.join(__dirname, 'theme_components', 'bower', 'bootstrap', 'less') ]
        }))
        .pipe(minifycss())
        .pipe(gulp.dest('css'))
        .pipe(notify({"message": "LESS compiled!"}));
});

var third_party = [
    "theme_components/bower/jquery/dist/jquery.min.js",
    "theme_components/bower/bootstrap/dist/js/bootstrap.min.js",
    "theme_components/bower/angular/angular.js",
    "theme_components/bower/angular-route/angular-route.js",
    "theme_components/bower/bootbox/bootbox.js"
];

gulp.task('js', function() {
    return gulp.src(third_party.concat(['src/js/**/*.js']))
        .pipe(concat('main.js'))
        .pipe(uglify())
        .pipe(gulp.dest('js'))
        .pipe(notify({"message": "JS compiled!"}));
});

gulp.task('watch', function() {
    gulp.watch('src/less/*.less', ['less']);

    gulp.watch('src/js/**/*.js', ['js']);
})

gulp.task('default', ['less', 'js', 'watch']);