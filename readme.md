#Starter Project

####Description
A simple starter project using grunt.

####Features
* CSS is compile from stylus. Minified for disitribution
    * creates a source css file
    * creates a vendor css file
* JS is concatinated and minified for distribution
    * creates a source js file
    * creates a vendor js file
* HTML uses an EJS template to input server and build specific variables from package.json (e.g., cdn_url path)
* Optimizes images for distribution
* Uses bower for front-end library management along with grunt integration.




####Initialization
* npm install
* bower install


####Commands

* grunt setup - runs all necessary tasks to build the app and then runs the dev task
* grunt deploy - runs all necessary tasks to build the app and then builds a distribution

* grunt or grunt dev - Build for development in /dev and run watch on working files. Also runs test server on port 3001
* grunt dist - Build for production in /dist
* grunt dist:test - Build for production and run test server on port 3001
