
AbstractModel = require "../abstract/AbstractModel.coffee"



Views = []
Views["NavigationView"] = require "./NavigationView.coffee"
Views["SectionView"] = require "./SectionView.coffee"


class AppMediator extends Backbone.View

    constructor: (opts) ->
        super(opts)
        @sections = []

    start: ->
        @generateNav()
        @generateSections(@model.get("routes") , "")



    generateNav: ->
        @navigation = new Views["NavigationView"]
            el: "nav"
            model: @model

    generateSections: (routes , root) =>

        for route in routes

            sectionClass = if route.sectionClass? then route.sectionClass else "SectionView"
            section = new Views[sectionClass]
                el: "#content"
                template: @model.preloader.getResult(route.id)
                model: new AbstractModel
                    url: root + route.path

            section.model.attributes.path = root + route.path
            section.model.attributes.view = route.view

            @sections.push section

            if route.paths?

                @generateSections(route.paths , section.model.attributes.path)




    getSectionByPath: (path) ->
        for section in @sections
            if path is section.model.get "path"
                return section

        return false


    onSectionLoaded: (section) =>
        if @currentSection?
            section.model.set("needsRender", true)
            @currentSection.transitionOut(section.transitionIn)
            @currentSection = section;
        else
            @currentSection = section
            @currentSection.transitionIn()

    onSectionData: (section) =>
        #console.log "dataLoaded"




    gotoView: (view) =>

        console.log view

        @navigation.updateUI view
        section = @getSectionByPath "/#{view}"

        if section?
            if section.model.get("assetsLoaded")
                @onSectionLoaded(section)
            else
                section.on "assetsLoaded" , @onSectionLoaded
                section.on "dataLoaded" , @onSectionData
                section.model.fetch()
        else
            throw new Error("Section Undefined")





module.exports = AppMediator