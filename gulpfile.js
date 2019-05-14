// Gulp 4.0

var gulp          = require('gulp'),
    sass          = require('gulp-sass'),
    postcss       = require('gulp-postcss'),
    autoprefixer  = require('autoprefixer'),
    cssnano       = require('cssnano'),
    sourcemaps    = require('gulp-sourcemaps'),
    deporder      = require('gulp-deporder'),
    concat        = require('gulp-concat'),
    mqpacker      = require('css-mqpacker'),
    stripdebug    = require('gulp-strip-debug'),
    babel         = require('gulp-babel'),
    uglify        = require('gulp-uglify'),
    browserSync   = require('browser-sync').create();

// Gather project variables in one section for ease of use
const configOpts = {
  proxy:     'localhost/slimpress_master',
  base:      './',

  stylesrc:  'sass/**/{*.scss,_*.scss}',
  styledest: 'css',
  stylefile: 'slimpress.min.css',

  jssrc:     'js/source/*.js',
  jsdest:    'js',
  jsfile:    'slimpress.min.js'
}

const syncOpts = {
  proxy:   configOpts.proxy,
  baseDir: configOpts.base,
  open:    false,
  notify:  true
};

var paths = {
  styles: {
    src:  configOpts.stylesrc,
    dest: configOpts.styledest,
    file: configOpts.stylefile
  },

  js: {
    src:  configOpts.jssrc,
    dest: configOpts.jsdest,
    file: configOpts.jsfile
  }
};

function style() {
  return gulp
    .src(paths.styles.src)
    // Initialize sourcemaps before compilation starts
    //.pipe(sourcemaps.init())
    .pipe(concat(paths.styles.file))
    .pipe(sass())
    .on('error', sass.logError)
    // Use postcss with autoprefixer and compress the compiled file using cssnano
    .pipe(postcss([autoprefixer({browsers: ['> 1%'], cascade: false, grid: true}), mqpacker(), cssnano()]))
    // Now add/write the sourcemaps
    //.pipe(sourcemaps.write('./'))
    .pipe(gulp.dest(paths.styles.dest))
    .pipe(browserSync.stream());
}

function js() {
  return gulp
    .src(paths.js.src)
    // Maintain IE 10 & 11 compatibility
    .pipe(babel({
      presets: ['@babel/env']
    }))
    .pipe(deporder())
    .pipe(concat(paths.js.file))
    // Use for debugging if desired
    //.pipe(stripdebug())
    .pipe(uglify())
    .pipe(gulp.dest(paths.js.dest))
    .pipe(browserSync.stream());
}

// Reload the page
function reload() {
  browserSync.reload();
}

function watch() {
  browserSync.init(syncOpts);
  gulp.watch(paths.styles.src, style);
  gulp.watch(paths.js.src, js);
  // Tell gulp which files to watch to trigger the reload
  gulp.watch(['*.html', '*.css', '*.js', '*.php']).on('change', browserSync.reload);
}

// Expose the task by exporting it. This allows you to run it from the commandline using $ gulp style
// We don't have to expose the reload function. It's currently only useful in other functions
exports.watch = watch;
exports.style = style;
exports.js    = js;

// Specify if tasks run in series or parallel using `gulp.series` and `gulp.parallel`
var build = gulp.parallel(style, js, watch);

// You can still use `gulp.task` to expose tasks
// gulp.task('build', build);
gulp.task('default', build);
