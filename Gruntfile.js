module.exports = function (grunt) {
    const sass = require('sass');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-concurrent');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin'); // Add this line

    grunt.registerTask('default', ['concurrent:watchall']);
    grunt.registerTask("vendors", ["uglify:vendor"]);
    grunt.registerTask("build", ["sass:main", "cssmin", "uglify:main"]); // Add this line

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        sass: {
            main: {
                options: {
                    sourceMap: true,
                    outputStyle: 'expanded', // Use expanded for development
                    implementation: sass,
                },
                files: {
                    './css/styles.css': './scss/styles.scss'
                }
            },
        },

        cssmin: {
            target: {
                files: {
                    './css/styles.min.css': ['./css/styles.css']
                }
            }
        },

        watch: {
            scss: {
                files: ['./scss/**/*.scss'],
                tasks: ['sass:main', 'cssmin'], // Add cssmin here
                options: {
                    spawn: false,
                },
            },
            js: {
                files: ['./scripts/**/*.js'],
                tasks: ['uglify:main'],
                options: {
                    spawn: false,
                },
            },
        },

        uglify: {
            main: {
                options: {
                    sourceMap: false,
                    compress: true,
                    mangle: false,
                },
                files: {
                    "./js/scripts.min.js": ["./scripts/**/*.js"],
                },
            },
            vendor: {
                options: {
                    sourceMap: false,
                    compress: true,
                    mangle: false,
                },
                files: {
                    "./js/scripts-vendor.min.js": [
                        "./node_modules/bootstrap/dist/js/bootstrap.min.js",
                        "./node_modules/@glidejs/glide/dist/glide.min.js",
                        "./node_modules/lightgallery/lightgallery.min.js"
                    ],
                },
            },
        },
        concurrent: {
            options: {
                logConcurrentOutput: true,
                limit: 10,
            },
            watchall: {
                tasks: ["watch:scss", "watch:js"],
            },
        },
    });
};