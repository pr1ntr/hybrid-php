
AbstractModel = require "../abstract/AbstractModel.coffee"

class AppModel extends AbstractModel

    constructor: (opts) ->
        super(opts)


    processData: ->
        @preloadTemplates()
        @loadAssets()


    preloadTemplates: ->

        routes = @get("routes")
        for route in routes

            @manifest.push
                id: route.id
                src:route.view








module.exports = AppModel