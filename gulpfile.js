const gulp = require('gulp')
const uglify = require('gulp-uglify')
const rename = require('gulp-rename')
const cssmin = require('gulp-clean-css')
const sourcemaps = require('gulp-sourcemaps')
const sass = require('gulp-sass')
const browserify = require('browserify')
const source = require('vinyl-source-stream')
const buffer = require('vinyl-buffer')

gulp.task('css', function () {
  const files = './assets/src/scss/[^_]*.scss'

  return gulp.src(files)
    .pipe(sass())
    .pipe(rename({ extname: '.css' }))
    .pipe(gulp.dest('./assets/css'))

    .pipe(sourcemaps.init({ loadMaps: true }))
    .pipe(cssmin({ sourceMap: true }))
    .pipe(rename({ extname: '.min.css' }))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('./assets/css'))
})

function bundleScript (entryFile) {
  return () =>
    browserify({
      entries: 'assets/src/js/' + entryFile
    }).transform('babelify', {
      presets: ['@babel/preset-env']
    })
      .bundle()
      .pipe(source(entryFile))
      .pipe(gulp.dest('./assets/js'))

      .pipe(buffer())
      .pipe(sourcemaps.init({ loadMaps: true }))
      .pipe(uglify())
      .pipe(rename({ extname: '.min.js' }))
      .pipe(sourcemaps.write('./'))
      .pipe(gulp.dest('./assets/js'))
}

gulp.task('js', gulp.parallel(bundleScript('script.js'), bundleScript('admin.js')))

gulp.task('default', gulp.parallel('js', 'css'))

gulp.task('watch', function () {
  gulp.watch('./assets/src/js/**/*.js', ['js'])
  gulp.watch('./assets/src/scss/**/*.scss', ['css'])
})
