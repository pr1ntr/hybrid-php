(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
var HybridApp,
  __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

HybridApp = (function(_super) {
  __extends(HybridApp, _super);

  function HybridApp() {
    console.log("new app");
  }

  return HybridApp;

})(Backbone.Router);

module.exports = HybridApp;


},{}],2:[function(require,module,exports){
var HybridApp;

HybridApp = require("./com/HybridApp.coffee");

$(document).ready((function(_this) {
  return function() {
    return new HybridApp;
  };
})(this));


},{"./com/HybridApp.coffee":1}]},{},[2]);