const gulp = require('gulp'),
  gutil = require('gulp-util'),
  wait = require('gulp-wait'),
  replace = require('gulp-replace'),
  sass = require('gulp-sass'),
  browserSync = require('browser-sync'),
  autoprefixer = require('gulp-autoprefixer'),
  uglify = require('gulp-uglify'),
  jshint = require('gulp-jshint'),
  header = require('gulp-header'),
  rename = require('gulp-rename'),
  fileInclude = require('gulp-file-include'),
  cssnano = require('gulp-cssnano'),
  sourcemaps = require('gulp-sourcemaps'),
  imagemin = require('gulp-imagemin'),
  htmlmin = require('gulp-htmlmin'),
  package = require('./package.json');

const banner = [
  '/*!\n' +
  ' * <%= package.name %>\n' +
  ' * <%= package.title %>\n' +
  ' * <%= package.url %>\n' +
  ' * @author <%= package.author %>\n' +
  ' * @version <%= package.version %>\n' +
  ' * Copyright ' + new Date().getFullYear() + '. <%= package.license %> licensed.\n' +
  ' */',
  '\n'
].join('');

const sourceDirectory = 'landing/src/';
const templateDirectory = 'front/ui/theme/metaorganic/';
const assetsDirectory = templateDirectory + 'assets_landing_page/';

gulp.task('smarty', function () {
  return gulp.src(sourceDirectory + '/templates/*.html')
    .pipe(fileInclude({
      prefix: '@@'
    }))
    .pipe(gulp.dest('landing/build'))
    .pipe(replace('assets/', '<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/'))
    .pipe(htmlmin({
      collapseWhitespace: true
    }))
    .pipe(rename({
      extname: '.tpl'
    }))
    .pipe(gulp.dest(templateDirectory + 'template/landing_page'));
});

gulp.task('favicon', function () {
  return gulp.src(sourceDirectory + '*.ico')
    .pipe(gulp.dest('landing/build'))
    .pipe(gulp.dest(assetsDirectory));
});

gulp.task('fonts', function () {
  return gulp.src(sourceDirectory + 'assets/fonts/*')
    .pipe(gulp.dest('landing/build/assets/fonts'))
    .pipe(gulp.dest(assetsDirectory + 'fonts'));
});

gulp.task('img', function () {
  return gulp.src(sourceDirectory + 'assets/img/*')
    .pipe(imagemin())
    .pipe(gulp.dest('landing/build/assets/img'))
    .pipe(gulp.dest(assetsDirectory + 'img'));
});

gulp.task('css', function () {
  return gulp.src(sourceDirectory + 'assets/scss/style.scss')
    .pipe(sourcemaps.init())
    .pipe(wait(200))
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer('last 4 version'))
    .pipe(cssnano())
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(header(banner, {
      package: package
    }))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('landing/build/assets/css'))
    .pipe(gulp.dest(assetsDirectory + 'css'))
    .pipe(browserSync.reload({
      stream: true
    }));
});

gulp.task('js', function () {
  gulp.src(sourceDirectory + 'assets/js/scripts.js')
    .pipe(sourcemaps.init())
    .pipe(jshint('.jshintrc'))
    .pipe(jshint.reporter('default'))
    .pipe(header(banner, {
      package: package
    }))
    .pipe(uglify())
    .on('error', function (err) {
      gutil.log(gutil.colors.red('[Error]'), err.toString());
    })
    .pipe(header(banner, {
      package: package
    }))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('landing/build/assets/js'))
    .pipe(gulp.dest(assetsDirectory + 'js'))
    .pipe(browserSync.reload({
      stream: true,
      once: true
    }));
});

gulp.task('browser-sync', function() {
  browserSync.init(null, {
    server: {
      baseDir: "landing/build"
    }
  });
});

gulp.task('bs-reload', function() {
  browserSync.reload();
});

gulp.task('default', ['smarty', 'css', 'js', 'favicon', 'img', 'browser-sync', 'fonts'], function () {
  gulp.watch(sourceDirectory + "assets/scss/**/*.scss", ['css']);
  gulp.watch(sourceDirectory + "assets/js/*.js", ['js']);
  gulp.watch(sourceDirectory + '/templates/*.html', ['smarty']);
  gulp.watch(sourceDirectory + "*.ico", ['favicon']);
  gulp.watch(sourceDirectory + "assets/img/*.{png,jpg,jpeg,gif,svg}", ['img']);
  gulp.watch(sourceDirectory + "assets/fonts/*.{woff2,woff,otf,ttf}", ['fonts']);
  gulp.watch("landing/build/*.html", ['bs-reload']);
});
