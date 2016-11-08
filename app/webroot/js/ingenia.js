var Ing = function() {
    if (typeof console === 'undefined') {
        console = {
            log: function() {
            }
        };
    }
    if (typeof $ === 'undefined') {
        console.log('Es necesario jQuery');
        return false;
    }
    var me = this;
    this.util = {
        randomId: function() {
            return Date.now();
        }
    };
};

var ing = new Ing();