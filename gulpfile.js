const gulp = require('gulp')
const uglify = require('gulp-uglify')
const rename = require('gulp-rename')
const cssmin = require('gulp-clean-css')
const sourcemaps = require('gulp-sourcemaps')
const sass = require('gulp-sass')
const browserify = require('browserify')
const source = require('vinyl-source-stream')

gulp.task('sass', function () {
  const files = './assets/scss/[^_]*.scss'

  return gulp.src(files)
    .pipe(sass())
    .pipe(rename({ extname: '.css' }))
    .pipe(gulp.dest('./assets/css'))
})

function bundleScript (entryFile) {
  return () =>
    browserify({
      entries: 'assets/browserify/' + entryFile
    }).transform('babelify', {
      presets: ['@babel/preset-env']
    })
      .bundle()
      .pipe(source(entryFile))
      .pipe(gulp.dest('./assets/js'))
}

gulp.task('js', gulp.parallel(bundleScript('script.js'), bundleScript('admin.js')))

gulp.task('minify-js', gulp.series('js', function () {
  return gulp.src(['./assets/js/**/*.js', '!./assets/js/**/*.min.js'])
    .pipe(sourcemaps.init({ loadMaps: true }))
    .pipe(uglify())
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

gulp.task('default', gulp.series('js', 'sass', 'minify-js', 'minify-css'))

// Rerun the task when a file changes
gulp.task('watch', function () {
  gulp.watch('./assets/browserify/**/*.js', ['js'])
  gulp.watch('./assets/scss/**/*.scss', ['sass'])
})
