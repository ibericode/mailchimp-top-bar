'use strict'

const gulp = require('gulp')
const uglify = require('gulp-uglify')
const rename = require('gulp-rename')
const cssmin = require('gulp-cssmin')
const sourcemaps = require('gulp-sourcemaps')
const sass = require('gulp-sass')
const browserify = require('browserify')
const source = require('vinyl-source-stream')
const buffer = require('vinyl-buffer')
const wpPot = require('gulp-wp-pot')
const sort = require('gulp-sort')
const config = require('./package.json')
const insert = require('gulp-insert')

gulp.task('sass', function () {
  const files = './assets/scss/[^_]*.scss'

  return gulp.src(files)
    .pipe(sass())
    .pipe(rename({ extname: '.css' }))
    .pipe(gulp.dest('./assets/css'))
})

function bundleScript (entryFile, bundleName) {
  return () =>
    browserify({
      entries: entryFile
    }).transform('babelify', {
      presets: ['@babel/preset-env']
    })
      .bundle()
      .pipe(source(entryFile.split('/').pop()))
      .pipe(insert.wrap('(function () { var require = undefined; var module = undefined; var exports = undefined; var define = undefined;', '; })();'))
      .pipe(buffer())
      .pipe(gulp.dest('./assets/js'))
}

gulp.task('browserify-script', bundleScript('./assets/browserify/script.js', 'script.js'))
gulp.task('browserify-admin', bundleScript('./assets/browserify/admin.js', 'script.js'))
gulp.task('browserify', gulp.series('browserify-script', 'browserify-admin'))

gulp.task('minify-js', gulp.series('browserify', function () {
  return gulp.src(['./assets/js/**/*.js', '!./assets/js/**/*.min.js'])
    .pipe(sourcemaps.init({ loadMaps: true }))
    .pipe(buffer())
    .pipe(uglify().on('error', console.log))
    .pipe(rename({ extname: '.min.js' }))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('./assets/js'))
}))

gulp.task('minify-css', gulp.series('sass', function () {
  return gulp.src(['./assets/css/**/*[^.min].css'])
    .pipe(sourcemaps.init({ loadMaps: true }))
    .pipe(cssmin({ sourceMap: true }))
    .pipe(rename({ extname: '.min.css' }))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('./assets/css'))
}))

gulp.task('default', gulp.series('browserify', 'sass', 'minify-js', 'minify-css'))

// Rerun the task when a file changes
gulp.task('watch', function () {
  gulp.watch('./assets/browserify/**/*.js', ['browserify'])
  gulp.watch('./assets/scss/**/*.scss', ['sass'])
})
