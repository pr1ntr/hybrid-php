module.exports = function(grunt) {
    grunt.initConfig({
        app: grunt.file.readJSON('hybrid-app.json'),
        stylus: {
            dist: {
                options: {
                    paths: ['<%= app.config.source.css %>'],
                    define: {
                        "cdn_root" : "<%= app.config.cdn.prod.cdn_root %>",
                        "import_tree" : require('stylus-import-tree')
                    },
                    use: [
                        
                    ],
                    import: [      //  @import 'foo', 'bar/moo', etc. into every .styl file
                      
                    ]
                },
                files: {
                    // 1:1 compile
                    '<%= app.config.deploy.css %><%= app.name %>.min.css': '<%= app.config.source.css %>/index.styl' // 1:1 compile
                }
            },
            dev: {
                options: {
                    paths: ['<%= app.config.source.css %>'],
                    define: {
                        "cdn_root" : "<%= app.config.cdn.dev.cdn_root %>",
                        "import_tree" : require('stylus-import-tree')
                    },
                    compress : false,
                    linenos: true,
                    use: [

                    ],
                    import: [      //  @import 'foo', 'bar/moo', etc. into every .styl file
                      
                    ]
                },
                files: {
                    '<%= app.config.deploy.css %><%= app.name %>.css': '<%= app.config.source.css %>/index.styl' // 1:1 compile
                    
                }
            }
       
        },
        cssmin: {
          add_banner: {
            options: {
              banner: '/* minified vendor css file */'
            },
            files: {
              '<%= app.config.deploy.css %><%= app.name %>-vendors.min.css': ['<%= app.config.deploy.css %><%= app.name %>-vendors.css']
            }
          }
        },
        bower: {
          dev: {
            dest: 'src/vendor',
            js_dest: 'src/vendor/js',
            css_dest: 'src/vendor/css'
          }
        },

        concat: {
          options: {
            // define a string to put between each file in the concatenated output
            separator: ';'
          },
          
          main: {
            // the files to concatenate
            files: {
                '<%= app.config.deploy.js %><%= app.name %>-vendors.js' : [
                    'src/vendor/js/underscore.js',
                    'src/vendor/js/backbone.js',
                    'src/vendor/js/**/*.js'
                ],
                '<%= app.config.deploy.css %><%= app.name %>-vendors.css' : [
                    'src/vendor/css/**/*.css'
                ]
            }
          }
        },
        uglify: {
            options: {
            // the banner is inserted at the top of the output
                banner: '/*! <%= app.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
            },
            dist: {
                files: {
                    '<%= app.config.deploy.js %><%= app.name %>.min.js': ['<%= app.config.deploy.js %><%= app.name %>.js'],
                    '<%= app.config.deploy.js %><%= app.name %>-vendors.min.js': ['<%= app.config.deploy.js %><%= app.name %>-vendors.js']
                }
            }
        },
        jshint: {
            // define the files to lint
            files: ['gruntfile.js', 'src/js/**/*.js'],
            // configure JSHint (documented at http://www.jshint.com/docs/)
            options: {
            // more options here if you want to override JSHint defaults
                globals: {
                    jQuery: true,
                    console: true,
                    module: true
                }
            }
        },
        watch: {
            js : {
                files: ['<%= jshint.files %>'],
                tasks: ['jshint' , 'concat' ]
            },
            app: {
                files: ['<%= app.config.source.js %>**/*.coffee'],
                tasks: ['browserify']
            },
            css : {
                files: ['<%= stylus.dev.options.paths +"/**/*.styl" %>'],
                tasks: ['stylus:dev' ]
            },
            copy : {
                files: ['<%= app.config.source.images %>**/*.*',
                    '<%= app.config.source.templates %>**/*.*'],
                tasks: ['copy:dev']
            }
            
        },
        copy: {

            dev: {
                files: [
                    {
                        expand:true,
                        cwd: "<%= app.config.source.images %>",
                        src: ["**"],
                        dest: "<%= app.config.deploy.images %>",
                        filter: 'isFile'
                    },
                    {
                        expand:true,
                        cwd: "<%= app.config.source.templates %>",
                        src: ["**"],
                        dest: "<%= app.config.templates.folder %>",
                        filter: 'isFile'
                    }

                ],
                options: {
                    process: function (content, srcpath) {
                        var name = grunt.config.data.app.name;
                        var fileNameJs = name+".js";
                        var fileNameJsVendors = name+"-vendors.js";
                        var fileNameCss = name+".css";
                        var fileNameCssVendors = name+"-vendors.css";
                        content = content.split("%%JS_PATH%%").join(fileNameJs);
                        content = content.split("%%JS_PATH_VENDORS%%").join(fileNameJsVendors);
                        content = content.split("%%CSS_PATH%%").join(fileNameCss);
                        content = content.split("%%CSS_PATH_VENDORS%%").join(fileNameCssVendors);
                        return content;
                    }
                }
            },
            dist: {
                files: [
                    {
                        expand:true,
                        cwd: "<%= app.config.source.templates %>",
                        src: ["**"],
                        dest: "<%= app.config.templates.folder %>",
                        filter: 'isFile'
                    }

                ],
                options: {
                    process: function (content, srcpath) {

                        var name = grunt.config.data.app.name;
                        var fileNameJs = name+".min.js";
                        var fileNameJsVendors = name+"-vendors.min.js";
                        var fileNameCss = name+".min.css";
                        var fileNameCssVendors = name+"-vendors.min.css";
                        content = content.split("%%JS_PATH%%").join(fileNameJs);
                        content = content.split("%%JS_PATH_VENDORS%%").join(fileNameJsVendors);
                        content = content.split("%%CSS_PATH%%").join(fileNameCss);
                        content = content.split("%%CSS_PATH_VENDORS%%").join(fileNameCssVendors);
                        return content;
                    }
                }
            }

        },  
        clean: {
          js: ["<%= app.config.deploy.js %>*.js", "!<%= app.config.deploy.js %>*.min.js"],
          css: ["<%= app.config.deploy.css %>*.css", "!<%= app.config.deploy.css %>*.min.css"]
        },
        imagemin: {
            dist: {
                files: [
                    {
                        // Set to true to enable the following options…
                        expand: true,
                        // cwd is 'current working directory'
                        cwd: '<%= app.config.source.images %>',
                        src: ['**/*.{png,jpg}'],
                        dest: '<%= app.config.deploy.images %>'
                    }
                ]
            }
        },
        browserify:{
            "dist": {
                "files": {
                    "<%= app.config.deploy.js %><%= app.name %>.js" : ["<%= app.config.source.js %>main.coffee"]
                }
            },
            "options": {
                "transform": ["coffeeify"]
            }

        }

    });
    grunt.loadNpmTasks('grunt-newer');
    grunt.loadNpmTasks('grunt-bower');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-stylus');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-clean');

    grunt.loadNpmTasks('grunt-browserify');

    
    grunt.registerTask('setup', ['bower' , 'dev']);
    grunt.registerTask('deploy', ['bower' , 'dist']);

    grunt.registerTask('default', ['jshint', 'concat',"browserify",'stylus:dev', 'newer:copy:dev', 'watch' ]);
    grunt.registerTask('dev', ['default']);
    grunt.registerTask('dist', ['jshint', 'concat', "browserify",'uglify' , 'stylus:dist', 'cssmin' , 'newer:imagemin:dist' , 'clean']);



 
   
};
