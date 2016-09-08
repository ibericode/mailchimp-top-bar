'use strict';

const gulp = require('gulp');
const uglify = require('gulp-uglify');
const rename = require("gulp-rename");
const cssmin = require('gulp-cssmin');
const sourcemaps = require('gulp-sourcemaps');
const sass = require('gulp-sass');
const browserify = require('browserify');
const source = require('vinyl-source-stream');
const buffer = require('vinyl-buffer');
const wpPot = require('gulp-wp-pot');
const sort = require('gulp-sort');
const config = require('./package.json')
const insert = require('gulp-insert');

gulp.task('default', ['browserify', 'sass', 'minify-js', 'minify-css', 'languages' ]);

gulp.task('sass', function () {
    var files = './assets/scss/[^_]*.scss';

    return gulp.src(files)
    // create .css file
        .pipe(sass())
        .pipe(rename({ extname: '.css' }))
        .pipe(gulp.dest('./assets/css'))
});


gulp.task('browserify', function() {
    return browserify({
        entries: './assets/browserify/script.js'
    }).on('error', console.log)
        .bundle()
        .pipe(source('script.js'))
        .pipe(insert.wrap('(function () { var require = undefined; var module = undefined; var exports = undefined; var define = undefined;', '; })();'))
        .pipe(buffer())
        .pipe(gulp.dest('./assets/js'));
});

gulp.task('minify-js', ['browserify'], function() {
    return gulp.src(['./assets/js/**/*.js','!./assets/js/**/*.min.js'])
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(buffer())
        .pipe(uglify().on('error', console.log))
        .pipe(rename({extname: '.min.js'}))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('./assets/js'));
});

gulp.task('minify-css', ['sass'], function() {
    return gulp.src(["./assets/css/**/*[^.min].css"])
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(cssmin({ sourceMap: true }))
        .pipe(rename({extname: '.min.css'}))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest("./assets/css"));
});

gulp.task('languages', function () {
    return gulp.src(['src/**/*.php', 'views/**/*.php'])
        .pipe(sort())
        .pipe(wpPot( {
            domain: config.name,
            destFile: config.name + '.pot'
        } ))
        .pipe(gulp.dest('languages'));
});

// Rerun the task when a file changes
gulp.task('watch', function() {
    gulp.watch('./assets/browserify/**/*.js', ['browserify']);
    gulp.watch('./assets/scss/**/*.scss', ['sass']);
});