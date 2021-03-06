var gulp = require('gulp');
var phpunit = require('gulp-phpunit');
var plumber = require('gulp-plumber');

gulp.task('phpunit', function() {
    gulp.src('')
        .pipe(plumber())
        .pipe(phpunit('phpunit'))
        .pipe(plumber.stop());
});

gulp.task('watch', function () {
    gulp.watch(['**/*.php'], ['phpunit']);
});

// What tasks does running gulp trigger?
gulp.task('default', ['phpunit']);
