//plug-in
var gulp = require('gulp');
var browserify = require('browserify');
var browserSync = require('browser-sync').create();
var source = require('vinyl-source-stream');
var sass = require('gulp-sass');
var nodemon = require('nodemon');


// gulpタスクの作成
gulp.task('build', function (cb) {
    browserify({
            entries: ['main.js']
        }).bundle()
        .pipe(source('bundle.js'))
        .pipe(gulp.dest('dist/'));
    cb();
});
gulp.task('browser-sync', function (cb) {
    browserSync.init({
        server: {
            baseDir: "./",
            index: "index.html"
        }
    });
    cb();
});
gulp.task('bs-reload', function (cb) {
    browserSync.reload();
    cb();
});
gulp.task('sass', function () {
    return gulp.src('./*.scss')
        .pipe(sass())
        .pipe(gulp.dest('./'));
});

// Gulpを使ったファイルの監視
gulp.task('default', gulp.series(gulp.parallel('browser-sync', 'build'), function (cb) {
    gulp.watch('./src/*.js', gulp.task('build'));
    gulp.watch("./*.html", gulp.task('bs-reload'));
    gulp.watch("./*.scss", gulp.task('sass'));
    gulp.watch("./dist/*.+(js|css)", gulp.task('bs-reload'));
    cb();
}));
