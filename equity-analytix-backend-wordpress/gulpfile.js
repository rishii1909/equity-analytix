const gulp = require('gulp'),
    sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    cssNano = require('gulp-cssnano'),
    minify = require('gulp-minify'),
    autoprefixer = require('gulp-autoprefixer'),
    imagemin     = require('gulp-imagemin'),
    imgCompress  = require('imagemin-jpeg-recompress');


gulp.task('sass', function () {
    return gulp.src('markups/dist/scss/**/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(autoprefixer(['last 15 versions', '> 1%', 'ie 8', 'ie 7'], {cascade: true}))
        .pipe(cssNano())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('markups/dist/css'))
});


gulp.task('watch', function () {
    gulp.watch('markups/dist/scss/**/*.scss', gulp.parallel('sass'))
});


//build
gulp.task('css', function () {
    return gulp.src('markups/dist/css/**/*.css')
        .pipe(autoprefixer(['last 15 versions', '> 1%', 'ie 8', 'ie 7'], {cascade: true}))
        .pipe(cssNano())
        .pipe(gulp.dest('markups/build/css'))
});

gulp.task('html', function () {
    return gulp.src('markups/dist/*.html')
        .pipe(gulp.dest('markups/build'))
});

gulp.task('image', function () {
    return gulp.src('markups/dist/images/**/*.*')
        .pipe(imagemin([
            imgCompress({
                loops: 4,
                min: 75,
                max: 85,
                quality: 'high'
            }),
            imagemin.gifsicle(),
            imagemin.optipng(),
            imagemin.svgo()
        ]))
        .pipe(gulp.dest('markups/build/images'))
});

gulp.task('fonts', function () {
    return gulp.src('markups/dist/fonts/**/*.*')
        .pipe(gulp.dest('markups/build/fonts'))
});

gulp.task('js', function () {
    return gulp.src('markups/dist/js/**/*.js')
        .pipe(minify({noSource: true, ext: {min: '.js'}}))
        .pipe(gulp.dest('markups/build/js'))
});


gulp.task('build', gulp.parallel('css', 'fonts', 'js', 'image', 'html'));