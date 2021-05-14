const gulp = require('gulp')
const uglify = require('gulp-uglify')
const rename = require('gulp-rename')
const cssmin = require('gulp-clean-css')
const browserify = require('browserify')
const source = require('vinyl-source-stream')
const buffer = require('vinyl-buffer')

const css = function () {
  return gulp.src('./assets/src/css/*.css')
    .pipe(cssmin({ sourceMap: true }))
    .pipe(gulp.dest('./assets/css'))
}
gulp.task('css', css)

function bundleScript (entryFile) {
  return () =>
    browserify({
      entries: 'assets/src/js/' + entryFile
    }).transform('babelify', {
      presets: ['@babel/preset-env']
    })
      .bundle()
      .pipe(source(entryFile))
      .pipe(buffer())
      .pipe(uglify())
      .pipe(gulp.dest('./assets/js'))
}

const js = gulp.parallel(bundleScript('script.js'), bundleScript('admin.js'))
gulp.task('js', js)

gulp.task('default', gulp.parallel('js', 'css'))

gulp.task('watch', function () {
  gulp.watch('./assets/src/js/**/*.js', js)
  gulp.watch('./assets/src/css/**/*.css', css)
})
