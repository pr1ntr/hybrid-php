AppMediator = require('./view/AppMediator.coffee')

class HybridApp extends Backbone.Router

    constructor: ->
        @initialize()


    initialize: ->
        @initView()
        @initModel()
        @initRouter()

    initModel: ->

    initView: ->
        @viewMediator = new AppMediator
            el: "body"
            model: {}


    initRouter: ->
        @route "*path" , @gotoSection
        Backbone.history.start
            pushState:true
            root: window.ROOT or "/"


    gotoSection: =>





module.exports = HybridApp