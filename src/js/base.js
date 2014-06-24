


/*


*/

(function () {

    Base = (function () {

        var Base = function (opts) {

            this._events = {};


        };

        Base.prototype.on = function (event, callback) {
            if(!this._events[event]) this._events[event] = [];
            this._events[event].push(callback);
        };

        Base.prototype.off = function (event, callback) {
            for(var binding in this._events) {  
                if(binding === event) {
                    var callGroup = this._events[binding];
                    if(callback) {
                        for(var hk = 0 ; hk < callGroup.length; hk++) {
                            var handler = callGroup[hk];
                            if(handler.toString() === callback.toString()) {
                                callGroup.splice(hk,1);
                                return;
                            }
                        }
                        this.off(event);
                    }else{
                        delete this._events[binding];
                        return;
                    }

                }
            }
        };

        Base.prototype.trigger = function (event) {
            for(var binding in this._events) {
                if(binding === event) {
                    var callGroup = this._events[binding];
                    for(var hk = 0 ; hk < callGroup.length; hk++) {
                        var handler = callGroup[hk];
                        handler();
                    }
                }
            }
        };

        Base.prototype.proxy = function (callback , context, args) {

            var ctx;

            if(!context)
                ctx = this;
            else
                ctx = context;
            if(typeof(callback) === "function")
                return callback.bind(ctx, args);
            else
                this.handleError("No Function Passed To Proxy");
            
        };

    


        Base.prototype.handleError = function (message) {
            throw new Error(message);
        };


   


        

        return Base;

    })();



}).call(window);   


        