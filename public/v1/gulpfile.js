const gulp = require('gulp');
const plumber = require('gulp-plumber');
const notify = require('gulp-notify');
const sourcemap = require('gulp-sourcemaps');
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const csso = require('gulp-csso');
const concat = require('gulp-concat');
const rename = require('gulp-rename');
const sync = require('browser-sync').create();
const del = require('del');
const terser = require('gulp-terser');
const imagemin = require('gulp-imagemin');
const pngquant = require('imagemin-pngquant');
const svgstore = require('gulp-svgstore');
const webp = require('gulp-webp');

const reload = (done) => {
    sync.reload();
    done();
};
exports.reload = reload;

const server = (done) => {
    sync.init({
        server: {
            baseDir: 'build',
        },
        cors: true,
        notify: false,
        ui: false,
    });
    done();
};
exports.server = server;

const deleteBuild = () => {
    return del('build');
};
exports.deleteBuild = deleteBuild;

const copyAssets = () => {
    return gulp
        .src(
            [
                'source/fonts/**/*.{woff,woff2}',
                'source/images/**',
                'source/libs/**',
                'source/flickity/**',
                'source/favicon/**',
            ],
            {
                base: 'source',
            }
        )
        .pipe(gulp.dest('build'));
};
exports.copyAssets = copyAssets;

const copyHtml = () => {
    return gulp.src('source/*.html').pipe(plumber()).pipe(gulp.dest('build')).pipe(sync.stream());
};
exports.copyHtml = copyHtml;

const minifyStyles = () => {
    return gulp
        .src('source/sass/style.scss')
        .pipe(
            plumber({
                errorHandler: notify.onError(function (err) {
                    return {
                        title: 'Styles',
                        message: err.message,
                    };
                }),
            })
        )
        .pipe(sourcemap.init())
        .pipe(sass())
        .pipe(postcss([autoprefixer()]))
        .pipe(gulp.dest('build/css'))
        .pipe(csso({ restructure: false }))
        .pipe(rename({ suffix: '.min' }))
        .pipe(sourcemap.write('.'))
        .pipe(gulp.dest('build/css'))
        .pipe(sync.stream());
};
exports.minifyStyles = minifyStyles;

const minifyImages = () => {
    return gulp
        .src('source/images/**/*.{jpg,png,svg}')
        .pipe(
            imagemin([
                pngquant(),
                // imagemin.optipng({optimizationLevel: 3}),
                imagemin.mozjpeg({ quality: 75, progressive: true }),
                imagemin.svgo({
                    plugins: [{ removeViewBox: true }, { cleanupIDs: false }],
                }),
                // imagemin.gifsicle({interlaced: true}),
            ])
        )
        .pipe(gulp.dest('build/images'));
};
exports.minifyImages = minifyImages;

const createSprite = () => {
    return gulp
        .src('source/images/**/icon-*.svg')
        .pipe(svgstore({ inlineSvg: true }))
        .pipe(rename('sprite.svg'))
        .pipe(gulp.dest('build/images'));
};
exports.createSprite = createSprite;

const createWebp = () => {
    return gulp
        .src('source/images/**/*.{jpg,png}')
        .pipe(webp({ quality: 90 }))
        .pipe(gulp.dest('build/images'));
};
exports.createWebp = createWebp;

const minifyScripts = () => {
    return gulp
        .src(['source/js/**/*.js'])
        .pipe(concat('scripts.js'))
        .pipe(gulp.dest('build/js'))
        .pipe(terser())
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest('build/js'));
};
exports.minifyScripts = minifyScripts;

const watcher = () => {
    gulp.watch('source/sass/**/*.scss', gulp.series(minifyStyles));
    gulp.watch('source/js/**/*.js', gulp.series(copyAssets, minifyScripts));
    gulp.watch('source/images/**/*', gulp.series(processImages));
    gulp.watch('source/*.html', gulp.series(copyHtml));

    gulp.watch('build/js/**/*.js').on('change', sync.reload);
    // gulp.watch('build/images/**/*').on('change', sync.reload);
    // gulp.watch('build/*.html').on('change', sync.reload);
};
exports.watcher = watcher;

const processImages = (exports.processImages = gulp.series(minifyImages, createSprite, createWebp));
const build = (exports.build = gulp.series(
    copyAssets,
    minifyStyles,
    minifyScripts,
    copyHtml
));

exports.default = gulp.series(build, server, watcher);
